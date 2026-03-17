<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">

        <a class="navbar-brand brand-name" href="index.php">Maison Reluxe</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php if($currentPage == 'index.php') echo 'active fw-bold'; ?>" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if($currentPage == 'products.php') echo 'active fw-bold'; ?>" href="products.php">Products</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if($currentPage == 'about.php') echo 'active fw-bold'; ?>" href="about.php">About Us</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="cart.php">
                        <i class="bi bi-cart"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="login.php">
                <i class="bi bi-person"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>