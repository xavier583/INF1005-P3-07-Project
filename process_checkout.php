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
$expiry = sanitize_input($_POST['expiry'] ?? '');
$cvv = sanitize_input($_POST['cvv'] ?? '');
$card_name = sanitize_input($_POST['card_name'] ?? '');

$errors = [];

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
     global $conn, $member_id, $country, $address, $city, $postal_code, $total_amount, $cart, $errorMsg, $success;

     mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    include "php/db_connect.php";

    if (!isset($conn) || $conn->connect_error) {
        $errorMsg = "Database connection failed.";
        $success = false;
        $conn->close();
        return;
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
                return;
    }

    $order_number = bin2hex(random_bytes(4));
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
                return;
    }

    $order_id = $stmt->insert_id;
    $stmt->close();

     if (!empty($cart)) {
        $itemStmt = $conn->prepare("
            INSERT INTO maison_reluxe_order_items 
            (order_id, product_id, quantity, price) 
            VALUES (?, ?, ?, ?)
        ");

         if (!$itemStmt) {
            $errorMsg = "Failed to prepare order items insert query.";
            $success = false;
            $conn->close();
                return;
        }

         foreach ($cart as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];

            $itemStmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);

            if (!$itemStmt->execute()) {
                $errorMsg = "Failed to insert order item: (" . $itemStmt->errno . ") " . $itemStmt->error;
                $success = false;
                $itemStmt->close();
                $conn->close();
                return;
            }
        }

        $itemStmt->close();
     }
    $conn->commit();

} catch (Exception $e) {
    $conn->rollback();
    $errorMsg = $e->getMessage();
    $success = false;
}
   

    echo "<p>You did it!</p>";
    $conn->close();
}
if (empty($errors))
    {
        saveOrderToDB();
        $_SESSION['cart'] = [];
        unset($_SESSION['old']);
        header('Location: checkout_success.php');
        exit();
    }
else {
    $_SESSION['errors'] = $errors;
    echo "<p>" . htmlspecialchars($errorMsg) . "</p>";
    //header('Location: checkout.php');
    //exit();
}
