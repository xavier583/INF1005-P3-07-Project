<?php session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit();
}

$country = trim($_POST['country'] ?? '');
$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$postal_code = trim($_POST['postal_code'] ?? '');
$card_number = trim($_POST['card_number'] ?? '');
$expiry = trim($_POST['expiry'] ?? '');
$cvv = trim($_POST['cvv'] ?? '');
$card_name = trim($_POST['card_name'] ?? '');

$errors = [];

$_SESSION['old'] = [
    'country' => $country,
    'address' => $address,
    'city' => $city,
    'postal_code' => $postal_code,
    'card_name' => $card_name
];

if ($country === '') {
    $errors[] = "Country is required.";
}
if ($address === '') {
    $errors[] = "Street address is required.";
}
if ($postal_code === ''){
    $errors[] = "Postal code is required.";
} elseif (!preg_match('/^[A-Za-z0-9\s\-]{3,10}$/', $postal_code)) {
    $errors[] = "Invalid postal code format.";
}
if ($card_number === '') {
    $errors[] = "Card number is required.";
} elseif (!preg_match('/^\d{13,19}$/', $card_number)) {
    $errors[] = "Card Number must be between 13 and 19 digits.";
}
if ($expiry === '') {
    $errors[] = "Expiry date is required.";
} elseif (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry)) {
    $errors[] = "Expiry date must be in MM/YY format.";
} else {
    $parts = explode('/', $expiry);
    $month = (int)$parts[0];
    $year = (int)$parts[1] + 2000;
    $currentYear = (int)date('Y');
    $currentMonth = (int)date('m');
    if ($year < $currentYear || ($year === $currentYear && $month < $currentMonth)) {
        $errors[] = "Card has expired. Use a different card.";
    }
}
if ($cvv === '') {
    $errors[] = "CVV is required.";
} elseif (!preg_match('/^\d{3,4}$/', $cvv)) {
    $errors[] = "CVV must be 3 or 4 digits.";
}
if ($card_name === '') {
    $errors[] = "Cardholder name is required.";
} elseif (!preg_match('/^[A-Za-z\s]+$/', $card_name)) {
    $errors[] = "Cardholder name can only contain letters and spaces.";
}
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: checkout.php');
    exit();
}

unset($_SESSION['old']);
header('Location: checkout_success.php');
exit();