<?php
session_start();
require 'php/db_connect.php';

// Only admins can access page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$message  = '';
$msgType  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']);
    $price       = (float)$_POST['price'];
    $image       = trim($_POST['image']);
    $category    = trim($_POST['category']);
    $brand       = trim($_POST['brand']);
    $description = trim($_POST['description']);

    // Add into maison_reluxe_products
    $stmt = $conn->prepare(
        "INSERT INTO maison_reluxe_products (name, price, image, category, brand, description)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param('sdssss', $name, $price, $image, $category, $brand, $description);

    if ($stmt->execute()) {
        $message = 'Product added successfully!';
        $msgType = 'success';
    } else {
        $message = 'Error adding product: ' . htmlspecialchars($conn->error);
        $msgType = 'danger';
    }
    $stmt->close();
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<main class="container my-5">
    <h2 style="font-family:'Georgia',serif; font-weight:400;">Add New Product</h2>

    <?php if ($message): ?>
        <div class="alert alert-<?= $msgType ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="add_product.php">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Price (SGD)</label>
            <input type="number" step="0.01" min="0" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Image Path (e.g., watches/rolex1.jpeg)</label>
            <input type="text" name="image" class="form-control" required>
            <div class="form-text">Path is relative to the <code>images/</code> folder.</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select" required>
                <option value="" disabled selected>Select a category</option>
                <option value="Watches">Watches</option>
                <option value="Bags">Bags</option>
                <option value="Shoes">Shoes</option>
                <option value="Clothes">Clothes</option>
                <option value="Jewellery">Jewellery</option>
                <option value="Accessories">Accessories</option>
            </select>
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
        <a href="products.php" class="btn btn-outline-secondary ms-2">Back to Products</a>
    </form>
</main>

<?php include 'includes/footer.php'; ?>