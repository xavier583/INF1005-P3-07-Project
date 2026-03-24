<?php
session_start();
require 'php/db_connect.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = (float)$_POST['price'];
    $image = $_POST['image']; // For simplicity, taking image path as text. You can add file upload logic later.
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $description = $_POST['description'];

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO products (name, price, image, category, brand, description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdssss", $name, $price, $image, $category, $brand, $description);

    if ($stmt->execute()) {
        $message = "Product added successfully!";
    } else {
        $message = "Error adding product: " . $conn->error;
    }
    $stmt->close();
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<main class="container my-5">
    <h2>Add New Product</h2>
    
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="add_product.php">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Image Path (e.g., watches/rolex1.jpeg)</label>
            <input type="text" name="image" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Brand</label>
            <input type="text" name="brand" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-dark">Add Product</button>
        <a href="products.php" class="btn btn-outline-secondary">Back to Products</a>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
