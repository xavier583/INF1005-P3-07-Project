<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once 'php/db_connect.php';

$currentPage = basename($_SERVER['PHP_SELF']);
$cartCount = 0;
$wishlistCount = 0;
$isLoggedIn = false;
$isAdmin = false;
$profileLink = "";
$wishlistLink = "";
$member_id = 0;

$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && isset($_SESSION['role']) && $_SESSION['role'] == 'admin';

if ($isLoggedIn)
    $member_id = $_SESSION['user_id'];

if ($isLoggedIn) {

     $stmt = $conn->prepare("
        SELECT SUM(c.quantity) AS total
        FROM maison_reluxe_cart c
        JOIN maison_reluxe_products p ON c.product_id = p.product_id
        WHERE c.member_id = ? AND c.quantity > 0 AND p.deleted = 0
    ");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $cartCount = (int)$row['total'];
    $stmt->close();
} else {
    $cartCount = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
}

if ($isLoggedIn) {
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM maison_reluxe_wishlist w
        JOIN maison_reluxe_products p ON w.product_id = p.product_id
        WHERE w.member_id = ? AND p.deleted = 0
    ");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $wishlistCount = (int)$row['total'];

    $stmt->close();
}

if ($isLoggedIn) {
    $profileLink = "profile.php";
    $wishlistLink = "products.php?wishlist=1";
} else
    $profileLink = $wishlistLink = "login.php";
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
                    <a class="nav-link <?php if ($currentPage == 'index.php') echo 'active fw-bold'; ?>" href="<?php echo $rootPath; ?>/index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if ($currentPage == 'products.php') echo 'active fw-bold'; ?>" href="<?php echo $rootPath; ?>/products.php">Products</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if ($currentPage == 'review.php') echo 'active fw-bold'; ?>" href="<?php echo $rootPath; ?>/review.php">Reviews</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if ($currentPage == 'about.php') echo 'active fw-bold'; ?>" href="<?php echo $rootPath; ?>/about.php">About Us</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">

                <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link icon-nav-link <?php if ($currentPage == 'add_product.php') echo 'active'; ?>" href="<?php echo $rootPath . "/add_product.php"; ?>">
                            <span class="nav-icon-wrap d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-plus-circle"></i>
                            </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link icon-nav-link <?php if ($currentPage == 'import_csv.php') echo 'active'; ?>" href="<?php echo $rootPath . "/import_csv.php"; ?>">
                            <span class="nav-icon-wrap d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-database-add"></i>
                            </span>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link icon-nav-link <?php if ($currentPage == 'products.php' && isset($_GET['wishlist']) && $_GET['wishlist'] === '1') echo 'active'; ?>" href="<?php echo $rootPath . "/" . $wishlistLink; ?>">
                        <span class="nav-icon-wrap cart-icon-wrap position-relative d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-heart"></i>
                            <?php if ($wishlistCount > 0): ?>
                                <span class="icon-count-badge position-absolute badge rounded-pill bg-dark">
                                    <?= $wishlistCount > 99 ? '99+' : $wishlistCount ?>
                                </span>
                            <?php endif; ?>
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link icon-nav-link <?php if ($currentPage == 'cart.php') echo 'active'; ?>" href="<?php echo $rootPath; ?>/cart.php">
                        <span class="nav-icon-wrap cart-icon-wrap position-relative d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-cart"></i>
                            <?php if ($cartCount > 0): ?>
                                <span class="icon-count-badge position-absolute badge rounded-pill bg-dark">
                                    <?= $cartCount > 99 ? '99+' : $cartCount ?>
                                </span>
                            <?php endif; ?>
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link icon-nav-link <?php if ($currentPage == 'profile.php') echo 'active'; ?>" href="<?php echo $rootPath . "/" . $profileLink; ?>">
                        <span class="nav-icon-wrap d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-person"></i>
                        </span>
                    </a>
                </li>
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