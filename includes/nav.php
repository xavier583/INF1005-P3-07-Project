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

<nav class="navbar navbar-expand-lg custom-navbar sticky-top">
    <div class="container-fluid px-lg-4">
        <a class="navbar-brand brand-name" href="<?php echo $rootPath; ?>/index.php">Maison Reluxe</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto main-nav-links">
                <li class="nav-item">
                    <a class="nav-link <?php if($currentPage == 'index.php') echo 'active'; ?>" href="<?php echo $rootPath; ?>/index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if($currentPage == 'products.php') echo 'active'; ?>" href="<?php echo $rootPath; ?>/products.php">Products</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if($currentPage == 'about.php') echo 'active'; ?>" href="<?php echo $rootPath; ?>/about.php">About Us</a>
                </li>

                <?php if ($isLoggedIn): ?>
                <li class="nav-item">
                    <a class="nav-link <?php if($currentPage == 'profile.php') echo 'active'; ?>" href="<?php echo $rootPath; ?>/profile.php">My Profile</a>
                </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center action-nav">
                <li class="nav-item me-lg-2">
                    <a class="nav-link icon-link <?php if($currentPage == 'products.php' && isset($_GET['wishlist']) && $_GET['wishlist'] === '1') echo 'active'; ?>" href="<?php echo $rootPath; ?>/products.php?wishlist=1">
                        <span class="icon-bubble">♡</span>
                        <?php if ($wishlistCount > 0): ?>
                            <span class="count-badge"><?php echo $wishlistCount > 99 ? '99+' : $wishlistCount; ?></span>
                        <?php endif; ?>
                    </a>
                </li>

                <li class="nav-item me-lg-3">
                    <a class="nav-link icon-link <?php if($currentPage == 'cart.php') echo 'active'; ?>" href="<?php echo $rootPath; ?>/cart.php">
                        <span class="icon-bubble">🛍</span>
                        <?php if ($cartCount > 0): ?>
                            <span class="count-badge"><?php echo $cartCount > 99 ? '99+' : $cartCount; ?></span>
                        <?php endif; ?>
                    </a>
                </li>

                <?php if ($isLoggedIn): ?>
                    <li class="nav-item me-lg-2">
                        <span class="nav-link welcome-text">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link logout-link" href="<?php echo $rootPath; ?>/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item me-lg-2">
                        <a class="nav-link auth-link <?php if($currentPage == 'login.php') echo 'active'; ?>" href="<?php echo $rootPath; ?>/login.php">Login</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link register-pill <?php if($currentPage == 'register.php') echo 'active'; ?>" href="<?php echo $rootPath; ?>/register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<style>
    .custom-navbar {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
        padding-top: 14px;
        padding-bottom: 14px;
    }

    .brand-name {
        font-size: 1.45rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #111 !important;
    }

    .main-nav-links .nav-link,
    .action-nav .nav-link {
        color: #333 !important;
        font-weight: 500;
        margin: 0 8px;
        position: relative;
        transition: all 0.25s ease;
    }

    .main-nav-links .nav-link:hover,
    .action-nav .nav-link:hover {
        color: #000 !important;
    }

    .main-nav-links .nav-link.active {
        color: #000 !important;
        font-weight: 700;
    }

    .main-nav-links .nav-link.active::after {
        content: "";
        position: absolute;
        left: 10px;
        right: 10px;
        bottom: 2px;
        height: 2px;
        background: #111;
        border-radius: 999px;
    }

    .icon-link {
        display: inline-flex;
        align-items: center;
        position: relative;
    }

    .icon-bubble {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f6f6f6;
        border: 1px solid #ececec;
        font-size: 1rem;
    }

    .count-badge {
        position: absolute;
        top: 2px;
        right: 0;
        transform: translate(35%, -25%);
        background: #111;
        color: #fff;
        font-size: 0.7rem;
        min-width: 18px;
        height: 18px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
    }

    .welcome-text {
        color: #666 !important;
        font-weight: 600;
    }

    .auth-link {
        border-radius: 999px;
    }

    .register-pill,
    .logout-link {
        padding: 8px 16px !important;
        border-radius: 999px;
        border: 1px solid #111;
        font-weight: 600;
    }

    .register-pill {
        background: #111;
        color: #fff !important;
    }

    .register-pill {
    background: rgba(255, 255, 255, 0.7);
    color: #111 !important;
    border: 1px solid rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);

    padding: 8px 18px !important;
    border-radius: 999px;

    font-weight: 600;

    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);

    transition: all 0.25s ease;
}

.register-pill:hover {
    background: rgba(255, 255, 255, 0.95);
    color: #000 !important;

    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
}

    .logout-link {
        color: #111 !important;
        background: transparent;
    }

    .logout-link:hover {
        background: #111;
        color: #fff !important;
    }

    @media (max-width: 991px) {
        .main-nav-links {
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .main-nav-links .nav-link.active::after {
            display: none;
        }

        .action-nav {
            align-items: flex-start !important;
        }

        .action-nav .nav-link {
            margin: 6px 0;
        }
    }
</style>