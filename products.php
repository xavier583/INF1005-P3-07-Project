<?php session_start(); ?>

<?php
$rootPath = ".";
include 'includes/header.php';
?>

    <?php include 'includes/nav.php'; ?>

    <style>
    .container {
        text-align: center;
    }

    .category-buttons {
        text-align: center;
        margin-top: 20px;
    }

    .category-buttons .btn {
        display: inline-block;
        margin: 10px;
        padding: 10px 20px;
    }
    </style>

    <main class="container">
        <h1>Our Products</h1>
        <p style="font-size: 1.3em; margin-top: 20px;">
            Browse our collection of luxury secondhand goods.
        </p>

        <h2>Categories</h2>

        <div class="category-buttons">
        <a href="Products/watches.php" class="category-watch">Watches</a>
        <a href="Products/jewellery.php" class="category-jewellery">Jewellery</a>
        <a href="Products/shoes.php" class="category-shoes">Shoes</a>
        <a href="Products/clothes.php" class="category-clothes">Clothes</a>
        <a href="Products/bags.php" class="category-bags">Bags</a>
        <a href="Products/accessories.php" class="category-accessories">Accessories</a>
    </div>
    </main>

    <?php include 'includes/footer.php'; ?>
