<?php session_start();

// Create cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add item to cart
if (isset($_POST['add'])) {
    $item = [
        "name" => $_POST['name'],
        "price" => $_POST['price']
    ];

    $_SESSION['cart'][] = $item;
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
                    <th>Product Name</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'];
                ?>
                <tr>
                    <td><?=$item['name']?></td>
                    <td><?=number_format($item['price'], 2)?></td>
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
