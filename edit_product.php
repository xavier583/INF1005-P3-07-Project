<?php
session_start();
require 'php/db_connect.php';

// Admin guard — only logged-in admins can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$message = '';
$msgType = '';
$product = null;

// Fetch product by product_id (correct column name)
if (isset($_GET['id'])) {
    $id   = (int)$_GET['id'];
    $stmt = $conn->prepare(
        "SELECT * FROM maison_reluxe_products WHERE product_id = ?"
    );
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id          = (int)$_POST['id'];
    $name        = trim($_POST['name']);
    $price       = (float)$_POST['price'];
    $image       = trim($_POST['image']);
    $category    = trim($_POST['category']);
    $brand       = trim($_POST['brand']);
    $description = trim($_POST['description']);

    // Update maison_reluxe_products using product_id (correct column name)
    $stmt = $conn->prepare(
        "UPDATE maison_reluxe_products
         SET name=?, price=?, image=?, category=?, brand=?, description=?
         WHERE product_id=?"
    );
    $stmt->bind_param('sdssssi', $name, $price, $image, $category, $brand, $description, $id);

    if ($stmt->execute()) {
        $message = 'Product updated successfully!';
        $msgType = 'success';
        // Refresh product data shown in the form
        $product['name']        = $name;
        $product['price']       = $price;
        $product['image']       = $image;
        $product['category']    = $category;
        $product['brand']       = $brand;
        $product['description'] = $description;
    } else {
        $message = 'Error updating product: ' . htmlspecialchars($conn->error);
        $msgType = 'danger';
    }
    $stmt->close();
}

if (!$product) {
    die('Product not found or ID not provided.');
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<main class="container my-5">
    <h2 style="font-family:'Georgia',serif; font-weight:400;">Edit Product</h2>

    <?php if ($message): ?>
        <div class="alert alert-<?= $msgType ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="edit_product.php?id=<?= (int)$product['product_id'] ?>">
        <input type="hidden" name="id" value="<?= (int)$product['product_id'] ?>">

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control"
                   value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Price (SGD)</label>
            <input type="number" step="0.01" min="0" name="price" class="form-control"
                   value="<?= htmlspecialchars($product['price']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Image Path</label>
            <input type="text" name="image" class="form-control"
                   value="<?= htmlspecialchars($product['image']) ?>" required>
            <div class="form-text">Path is relative to the <code>images/</code> folder.</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select" required>
                <?php foreach (['Watches','Bags','Shoes','Clothes','Jewellery','Accessories'] as $cat): ?>
                <option value="<?= $cat ?>" <?= $product['category'] === $cat ? 'selected' : '' ?>>
                    <?= $cat ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Brand</label>
            <input type="text" name="brand" class="form-control"
                   value="<?= htmlspecialchars($product['brand']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-warning">Update Product</button>
        <a href="product_detail.php?id=<?php echo $product['product_id'];?>" class="btn btn-outline-secondary ms-2">Back to Products</a>
    </form>
</main>

<?php include 'includes/footer.php'; ?>