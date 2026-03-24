<?php
session_start();
require 'php/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Redirect back to products page with a success message
        header("Location: products.php?msg=deleted");
    } else {
        echo "Error deleting product: " . $conn->error;
    }
    
    $stmt->close();
} else {
    echo "Invalid request.";
}
?>