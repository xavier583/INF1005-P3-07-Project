<?php session_start();

// Create cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add item to cart
if (isset($_POST['add'])) {
    $item = [
        "id" => $_POST['id'],
        "name" => $_POST['name'],
        "price" => $_POST['price'],
        "image" => $_POST['image']
    ];

    $_SESSION['cart'][] = $item;
}
// Remove item from cart
if (isset($_POST['remove'])) {
    $index = $_POST['index'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); //Reindex array after removal
    }
}
$rootPath = ".";
include 'includes/header.php'; ?>

<?php include 'includes/nav.php'; ?>

<div class="container mt-5">
    <h1>Shopping Cart</h1>
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is currently empty.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Price ($)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'];
                ?>
                <tr>
                    <td><img src="<?=$item['image']?>" alt="<?=$item['name']?>" width="100"></td>
                    <td><?=$item['name']?></td>
                    <td><?=number_format($item['price'], 2)?></td>
                    <td>
                        <form method = "POST">
                            <input type="hidden" name="index" value="<?=array_search($item, $_SESSION['cart'])?>">
                            <button type="submit" name="remove" class="btn btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>

                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong>$<?=number_format($total, 2)?></strong></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
</div>

            

<?php include 'includes/footer.php'; ?>
