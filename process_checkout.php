<?php session_start();

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
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: checkout.php');
    exit();
}
$_SESSION['cart'] = [];
unset($_SESSION['old']);
header('Location: checkout_success.php');
exit();