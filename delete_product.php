<?php
session_start();
require 'php/db_connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    // Delete from maison_reluxe_products
    $stmt = $conn->prepare(
        "UPDATE maison_reluxe_products SET deleted = 1 WHERE product_id = ?"
    );
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
    $stmt->close();
        header('Location: products.php?msg=deleted');
    } else {
    $stmt->close();
        echo 'Error deleting product: ' . htmlspecialchars($conn->error);
    }

} else {
    header('Location: products.php');
}
exit;
?>