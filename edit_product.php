<?php
session_start();
require 'php/db_connect.php';

$message = "";
$product = null;

// Look up the product based on ID
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $name = $_POST['name'];
    $price = (float)$_POST['price'];
    $image = $_POST['image'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE products SET name=?, price=?, image=?, category=?, brand=?, description=? WHERE id=?");
    $stmt->bind_param("sdssssi", $name, $price, $image, $category, $brand, $description, $id);

    if ($stmt->execute()) {
        $message = "Product updated successfully!";
        // Refresh product data
        $product['name'] = $name;
        $product['price'] = $price;
        $product['image'] = $image;
        $product['category'] = $category;
        $product['brand'] = $brand;
        $product['description'] = $description;
    } else {
        $message = "Error updating product: " . $conn->error;
    }
    $stmt->close();
}

if (!$product) {
    die("Product not found or ID not provided.");
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<main class="container my-5">
    <h2>Edit Product</h2>
    
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="edit_product.php?id=<?= $product['id'] ?>">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Image Path</label>
            <input type="text" name="image" class="form-control" value="<?= htmlspecialchars($product['image']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($product['category']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Brand</label>
            <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($product['brand']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-warning">Update Product</button>
        <a href="products.php" class="btn btn-outline-secondary">Back to Products</a>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
