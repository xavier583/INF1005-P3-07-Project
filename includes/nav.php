<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($rootPath) || $rootPath === '') {
    $rootPath = '.';
}

$currentPage = basename($_SERVER['PHP_SELF']);
$cartCount = 0;
$wishlistCount = 0;

if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += isset($item['quantity']) ? (int)$item['quantity'] : 1;
    }
}

if (isset($_SESSION['wishlist']) && is_array($_SESSION['wishlist'])) {
    $wishlistCount = count($_SESSION['wishlist']);
}

$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand brand-name" href="<?php echo $rootPath; ?>/index.php">Maison Reluxe</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php if($currentPage == 'index.php') echo 'active fw-bold'; ?>" href="<?php echo $rootPath; ?>/index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if($currentPage == 'products.php') echo 'active fw-bold'; ?>" href="<?php echo $rootPath; ?>/products.php">Products</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if($currentPage == 'about.php') echo 'active fw-bold'; ?>" href="<?php echo $rootPath; ?>/about.php">About Us</a>
                </li>

                <?php if ($isLoggedIn): ?>
                <li class="nav-item">
                    <a class="nav-link <?php if($currentPage == 'profile.php') echo 'active fw-bold'; ?>" href="<?php echo $rootPath; ?>/profile.php">My Profile</a>
                </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link icon-nav-link <?php if($currentPage == 'products.php' && isset($_GET['wishlist']) && $_GET['wishlist'] === '1') echo 'active'; ?>" href="<?php echo $rootPath; ?>/products.php?wishlist=1">
                        <span class="nav-icon-wrap cart-icon-wrap position-relative d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-heart"></i>
                            <?php if ($wishlistCount > 0): ?>
                            <span class="icon-count-badge position-absolute badge rounded-pill bg-dark">
                                <?php echo $wishlistCount > 99 ? '99+' : $wishlistCount; ?>
                            </span>
                            <?php endif; ?>
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link icon-nav-link <?php if($currentPage == 'cart.php') echo 'active'; ?>" href="<?php echo $rootPath; ?>/cart.php">
                        <span class="nav-icon-wrap cart-icon-wrap position-relative d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-cart"></i>
                            <?php if ($cartCount > 0): ?>
                            <span class="icon-count-badge position-absolute badge rounded-pill bg-dark">
                                <?php echo $cartCount > 99 ? '99+' : $cartCount; ?>
                            </span>
                            <?php endif; ?>
                        </span>
                    </a>
                </li>

                <?php if ($isLoggedIn): ?>
                    <li class="nav-item d-flex align-items-center me-2">
                        <span class="nav-link">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link icon-nav-link <?php if($currentPage == 'profile.php') echo 'active'; ?>" href="<?php echo $rootPath; ?>/profile.php">
                            <span class="nav-icon-wrap d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-person"></i>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $rootPath; ?>/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if($currentPage == 'login.php') echo 'active fw-bold'; ?>" href="<?php echo $rootPath; ?>/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if($currentPage == 'register.php') echo 'active fw-bold'; ?>" href="<?php echo $rootPath; ?>/register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<style>
    .icon-nav-link {
        display: inline-flex;
        align-items: center;
    }

    .nav-icon-wrap {
        line-height: 1;
        min-width: 1.6rem;
        min-height: 1.6rem;
    }

    .cart-icon-wrap {
        overflow: visible;
    }

    .icon-count-badge {
        top: 0;
        right: 0;
        transform: translate(55%, -45%);
        font-size: 0.65rem;
        min-width: 1.1rem;
        height: 1.1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 0.3rem;
    }
</style>