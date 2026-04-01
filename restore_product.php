<?php
session_start();
require 'php/db_connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
// Admin guard — only logged-in admins can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    // Restore into maison_reluxe_products using product_id (correct table + column)
    $stmt = $conn->prepare(
        "UPDATE maison_reluxe_products SET deleted = 0 WHERE product_id = ?"
    );
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
    $stmt->close();
        header('Location: products.php?msg=restored');
    } else {
    $stmt->close();
        echo 'Error restoring product: ' . htmlspecialchars($conn->error);
    }

} else {
    header('Location: products.php');
}
exit;
?>