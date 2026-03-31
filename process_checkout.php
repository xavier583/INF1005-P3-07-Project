<?php session_start();
 error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/php/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit();
}

$country = sanitize_input($_POST['country'] ?? '');
$address = sanitize_input($_POST['address'] ?? '');
$city = sanitize_input($_POST['city'] ?? '');
$postal_code = sanitize_input($_POST['postal_code'] ?? '');
$card_number = sanitize_input($_POST['card_number'] ?? '');
$expiry = trim($_POST['expiry'] ?? '');
$cvv = sanitize_input($_POST['cvv'] ?? '');
$card_name = sanitize_input($_POST['card_name'] ?? '');

$errors = [];
$errorMsg = '';
$success = true;

$_SESSION['old'] = [
    'country' => $country,
    'address' => $address,
    'city' => $city,
    'postal_code' => $postal_code,
];

if ($country === '') {
    $errors['country'] = "Country is required.";
}
if ($address === '') {
    $errors['address'] = "Street address is required.";
}
if ($postal_code === ''){
    $errors['postal_code'] = "Postal code is required.";
} elseif (!preg_match('/^[A-Za-z0-9\s\-]{3,10}$/', $postal_code)) {
    $errors['postal_code'] = "Invalid postal code format.";
}
$clean_card_number = preg_replace('/\s+/', '', $card_number);
if ($clean_card_number === '') {
    $errors['card_number'] = "Card number is required.";
} elseif (!preg_match('/^\d{13,19}$/', $clean_card_number)) {
    $errors['card_number'] = "Card Number must be between 13 and 19 digits.";
}

if ($expiry === '') {
    $errors['expiry'] = "Expiry date is required.";
} elseif (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry)) {
    $errors['expiry'] = "Expiry date must be in MM/YY format.";
} else {
    $parts = explode('/', $expiry);
    $month = (int)$parts[0];
    $year = (int)$parts[1] + 2000;
    $currentYear = (int)date('Y');
    $currentMonth = (int)date('m');
    if ($year < $currentYear || ($year === $currentYear && $month < $currentMonth)) {
        $errors['expiry'] = "Card has expired. Use a different card.";
    }
}
if ($cvv === '') {
    $errors['cvv'] = "CVV is required.";
} elseif (!preg_match('/^\d{3,4}$/', $cvv)) {
    $errors['cvv'] = "CVV must be 3 or 4 digits.";
}
if ($card_name === '') {
    $errors['card_name'] = "Cardholder name is required.";
} elseif (!preg_match('/^[A-Za-z\s]+$/', $card_name)) {
    $errors['card_name'] = "Cardholder name can only contain letters and spaces.";
}
function saveOrderToDB()
{
     global $conn, $country, $address, $city, $postal_code, $total_amount, $cart, $errorMsg, $success;

     mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    include "php/db_connect.php";

    $member_id = (int)($_SESSION['user_id'] ?? 0);
    if ($member_id <= 0) {
        $errorMsg = "You must be logged in to place an order.";
        $success = false;
        return false;
    }

    if (!isset($conn) || $conn->connect_error) {
        $errorMsg = "Database connection failed.";
        $success = false;
        if (isset($conn)) {
            $conn->close();
        }
        return false;
    }
    $conn->begin_transaction();

try {
     $stmt = $conn->prepare("INSERT INTO maison_reluxe_orders 
        (order_number, member_id, order_date, status, total_amount, shipping_address, city, country, postal_code) 
        VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        $errorMsg = "Failed to prepare order insert query.";
        $success = false;
        $conn->close();
                return false;
    }

    $order_number = strtoupper(bin2hex(random_bytes(4)));
    $status = "Pending";
    $cart  = $_SESSION['cart'] ?? [];
    $total_amount = 0;
    foreach ($cart as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }

    $stmt->bind_param("sisdssss", 
        $order_number,
        $member_id,
        $status,
        $total_amount,
        $address,
        $city,
        $country,
        $postal_code);

   if (!$stmt->execute()) {
        $errorMsg = "Failed to insert order: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
        $conn->close();
        return false;
    }

    $order_id = $stmt->insert_id;
    $stmt->close();

      if (!empty($cart)) {
          $requiresExplicitItemId = false;
        $nextItemId = 0;

        $schemaStmt = $conn->prepare("
            SELECT EXTRA
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'maison_reluxe_order_items'
              AND COLUMN_NAME = 'item_id'
            LIMIT 1
        ");

        if ($schemaStmt) {
            $schemaStmt->execute();
            $schemaResult = $schemaStmt->get_result();
            $schemaRow = $schemaResult ? $schemaResult->fetch_assoc() : null;
            $schemaStmt->close();

            if ($schemaRow && stripos((string)$schemaRow['EXTRA'], 'auto_increment') === false) {
                $requiresExplicitItemId = true;
                $nextIdResult = $conn->query("SELECT COALESCE(MAX(item_id), 0) + 1 AS next_item_id FROM maison_reluxe_order_items");
                if ($nextIdResult) {
                    $nextItemId = (int)($nextIdResult->fetch_assoc()['next_item_id'] ?? 1);
                    $nextIdResult->free();
                } else {
                    $nextItemId = 1;
                }
            }
        }

        if ($requiresExplicitItemId) {
            $itemStmt = $conn->prepare("
                INSERT INTO maison_reluxe_order_items 
                (item_id, order_id, product_id, quantity, price) 
                VALUES (?, ?, ?, ?, ?)
            ");
        } else {
            $itemStmt = $conn->prepare("
                INSERT INTO maison_reluxe_order_items 
                (order_id, product_id, quantity, price) 
                VALUES (?, ?, ?, ?)
            ");
        }

         if (!$itemStmt) {
            $errorMsg = "Failed to prepare order items insert query.";
            $success = false;
            $conn->close();
                return false;
        }

            foreach ($cart as $item) {
                $product_id = (int)($item['product_id'] ?? $item['id'] ?? 0);
                $quantity = (int)($item['quantity'] ?? 0);
                $price = (float)($item['price'] ?? 0);

                if ($product_id <= 0 || $quantity <= 0) {
                     $errorMsg = "One or more cart items are invalid. Please update your cart and try again.";
                     $success = false;
                     $itemStmt->close();
                     $conn->close();
                     return false;
                }

            if ($requiresExplicitItemId) {
                $item_id = $nextItemId++;
                $itemStmt->bind_param("iiiid", $item_id, $order_id, $product_id, $quantity, $price);
            } else {
                $itemStmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
            }

            if (!$itemStmt->execute()) {
                $errorMsg = "Failed to insert order item: (" . $itemStmt->errno . ") " . $itemStmt->error;
                $success = false;
                $itemStmt->close();
                $conn->close();
                return false;
            }
        }

        $itemStmt->close();
     }
    $conn->commit();

} catch (Exception $e) {
    $conn->rollback();
    $errorMsg = $e->getMessage();
    $success = false;
    $conn->close();
    return false;
}

    $conn->close();
    return true;
}
if (empty($errors))
    {
        if (saveOrderToDB()) {
            $_SESSION['cart'] = [];
            unset($_SESSION['old']);
            header('Location: checkout_success.php');
            exit();
        }

        $_SESSION['errors'] = ['general' => ($errorMsg !== '' ? $errorMsg : 'Unable to process checkout. Please try again.')];
        header('Location: checkout.php');
        exit();
    }
else {
    $_SESSION['errors'] = $errors;
    header('Location: checkout.php');
    exit();
}
