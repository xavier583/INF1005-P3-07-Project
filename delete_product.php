<?php
session_start();
require 'php/db_connect.php';

// Admin guard — only logged-in admins can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    // Delete from maison_reluxe_products using product_id (correct table + column)
    $stmt = $conn->prepare(
        "DELETE FROM maison_reluxe_products WHERE product_id = ?"
    );
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header('Location: products.php?msg=deleted');
    } else {
        echo 'Error deleting product: ' . htmlspecialchars($conn->error);
    }

    $stmt->close();
} else {
    header('Location: products.php');
}
exit;
?>