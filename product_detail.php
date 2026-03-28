<?php
/*
 * product_detail.php – reads product from MySQL (maison_reluxe_products).
 * Cart and wishlist logic unchanged.
 */

session_start();
require_once 'php/db_connect.php';

if (!isset($_SESSION['wishlist']) || !is_array($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// ── Fetch the requested product from DB ───────────────────────────────────────
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: products.php');
    exit;
}

$stmt = $conn->prepare(
    "SELECT product_id AS id, name, price, image, category, brand, description
     FROM maison_reluxe_products
     WHERE product_id = ?
     LIMIT 1"
);
$stmt->bind_param('i', $id);
$stmt->execute();
$result  = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    header('Location: products.php');
    exit;
}

// ── Wishlist toggle ───────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_wishlist'])) {
    $pid = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $key = array_search($pid, $_SESSION['wishlist'], true);

    if ($key === false) {
        $_SESSION['wishlist'][] = $pid;
    } else {
        unset($_SESSION['wishlist'][$key]);
        $_SESSION['wishlist'] = array_values($_SESSION['wishlist']);
    }

    header('Location: product_detail.php?id=' . $id);
    exit;
}

// ── Add to Cart ───────────────────────────────────────────────────────────────
$cartMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $pid = (int)$_POST['product_id'];
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]['quantity']++;
    } else {
        $_SESSION['cart'][$pid] = [
            'id'       => (int)$product['id'],
            'name'     => $product['name'],
            'price'    => (float)$product['price'],
            'image'    => $product['image'],
            'brand'    => $product['brand'],
            'quantity' => 1,
        ];
    }
    $cartMessage = 'success';
}

// ── Gallery images (unchanged logic — scans filesystem for extra images) ──────
function buildProductGalleryImages(string $imagePath): array
{
    if ($imagePath === '') return [];

    $gallery  = [str_replace('\\', '/', $imagePath)];
    $baseDir  = dirname($imagePath);
    $baseName = pathinfo($imagePath, PATHINFO_FILENAME);
    $root     = __DIR__ . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
    $pattern  = $root . str_replace('/', DIRECTORY_SEPARATOR, $baseDir)
                . DIRECTORY_SEPARATOR . $baseName . '*';

    foreach (glob($pattern) ?: [] as $file) {
        if (!is_file($file)) continue;
        $rel = str_replace('\\', '/', substr($file, strlen($root)));
        if (!preg_match('/\.(jpe?g|png|webp|gif)$/i', $rel)) continue;
        if (!in_array($rel, $gallery, true)) $gallery[] = $rel;
    }

    return $gallery;
}

$galleryImages = buildProductGalleryImages($product['image']);
$isWishlisted  = in_array((int)$product['id'], array_map('intval', $_SESSION['wishlist']), true);

// ── Related products from DB (same category, excluding current) ───────────────
$stmt = $conn->prepare(
    "SELECT product_id AS id, name, price, image, brand
     FROM maison_reluxe_products
     WHERE category = ? AND product_id != ?
     ORDER BY RAND()
     LIMIT 3"
);
$stmt->bind_param('si', $product['category'], $id);
$stmt->execute();
$relatedResult = $stmt->get_result();
$related = [];
while ($row = $relatedResult->fetch_assoc()) {
    $related[] = $row;
}
$stmt->close();
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<main class="container my-5">

    <?php if ($cartMessage === 'success'): ?>
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
        <strong><?= htmlspecialchars($product['name']) ?></strong> has been added to your cart.
        <a href="cart.php" class="alert-link ms-2">View Cart →</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="products.php" class="text-dark">Products</a></li>
            <li class="breadcrumb-item">
                <a href="products.php?category=<?= urlencode($product['category']) ?>" class="text-dark">
                    <?= htmlspecialchars($product['category']) ?>
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </nav>

    <!-- Product Detail -->
    <div class="row g-5 align-items-start">

        <!-- Image Gallery -->
        <div class="col-md-6">
            <div id="productGalleryCarousel" class="carousel slide detail-carousel" data-bs-ride="false">
                <div class="carousel-inner detail-img-wrapper">
                    <?php foreach ($galleryImages as $index => $img): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <img src="images/<?= htmlspecialchars($img) ?>"
                             alt="<?= htmlspecialchars($product['name']) ?> image <?= $index + 1 ?>"
                             class="detail-img d-block w-100">
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($galleryImages) > 1): ?>
                <button class="carousel-control-prev" type="button"
                        data-bs-target="#productGalleryCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button"
                        data-bs-target="#productGalleryCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
                <div class="carousel-indicators detail-carousel-indicators">
                    <?php foreach ($galleryImages as $index => $img): ?>
                    <button type="button"
                            data-bs-target="#productGalleryCarousel"
                            data-bs-slide-to="<?= $index ?>"
                            class="<?= $index === 0 ? 'active' : '' ?>"
                            aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                            aria-label="Slide <?= $index + 1 ?>"></button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-md-6">
            <p class="detail-brand"><?= htmlspecialchars($product['brand']) ?></p>
            <h1 class="detail-name"><?= htmlspecialchars($product['name']) ?></h1>
            <p class="detail-category text-muted mb-3"><?= htmlspecialchars($product['category']) ?></p>
            <p class="detail-price">$<?= number_format((float)$product['price'], 2) ?></p>
            <hr>
            <p class="detail-description"><?= htmlspecialchars($product['description']) ?></p>
            <hr>

            <form method="POST" action="">
                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                <button type="submit" name="add_to_cart" class="btn btn-dark btn-lg w-100 add-cart-btn">
                    Add to Cart
                </button>
            </form>

            <form method="POST" action="" class="mt-2">
                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                <button type="submit" name="toggle_wishlist"
                        class="btn btn-outline-dark w-100 wishlist-detail-btn <?= $isWishlisted ? 'active' : '' ?>">
                    <i class="bi <?= $isWishlisted ? 'bi-heart-fill' : 'bi-heart' ?> me-2"></i>
                    <?= $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' ?>
                </button>
            </form>

            <a href="products.php?category=<?= urlencode($product['category']) ?>"
               class="btn btn-outline-dark mt-3 w-100">
                ← Back to <?= htmlspecialchars($product['category']) ?>
            </a>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($related)): ?>
    <div class="mt-5 pt-4 border-top">
        <h4 class="mb-4 related-title">You May Also Like</h4>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($related as $r): ?>
            <div class="col">
                <a href="product_detail.php?id=<?= (int)$r['id'] ?>" class="text-decoration-none text-dark">
                    <div class="card h-100 product-card border-0 shadow-sm">
                        <div class="related-img-wrapper">
                            <img src="images/<?= htmlspecialchars($r['image']) ?>"
                                 alt="<?= htmlspecialchars($r['name']) ?>"
                                 class="card-img-top related-img">
                        </div>
                        <div class="card-body">
                            <p class="product-brand mb-1"><?= htmlspecialchars($r['brand']) ?></p>
                            <h6 class="card-title"><?= htmlspecialchars($r['name']) ?></h6>
                            <p class="product-price mb-0">$<?= number_format((float)$r['price'], 2) ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</main>

<style>
    .detail-carousel { border-radius:8px; overflow:hidden; background:#f5f5f5; }
    .detail-img-wrapper { width:100%; height:520px; }
    .detail-img { width:100%; height:520px; object-fit:cover; }
    .detail-carousel .carousel-control-prev,
    .detail-carousel .carousel-control-next { width:12%; }
    .detail-carousel .carousel-control-prev-icon,
    .detail-carousel .carousel-control-next-icon {
        background-color:rgba(0,0,0,0.45); border-radius:50%;
        background-size:55%; width:2.4rem; height:2.4rem;
    }
    .detail-carousel-indicators { margin-bottom:0.7rem; }
    .detail-carousel-indicators [data-bs-target] {
        width:8px; height:8px; border-radius:50%; border:0; opacity:0.65;
    }
    .detail-brand { font-size:0.8rem; text-transform:uppercase; letter-spacing:0.15em; color:#888; margin-bottom:4px; }
    .detail-name { font-family:'Georgia',serif; font-size:1.6rem; font-weight:400; line-height:1.3; color:#1a1a1a; }
    .detail-price { font-size:1.5rem; font-weight:600; color:#1a1a1a; }
    .detail-description { font-size:0.95rem; line-height:1.8; color:#444; }
    .add-cart-btn { letter-spacing:0.08em; padding:14px; font-size:0.95rem; }
    .wishlist-detail-btn.active { border-color:#f1c9cf; background:#fff7f8; color:#c71f37; }
    .related-title { font-family:'Georgia',serif; font-weight:400; }
    .related-img-wrapper { height:220px; overflow:hidden; background:#f5f5f5; }
    .related-img { width:100%; height:100%; object-fit:cover; transition:transform 0.3s ease; }
    .product-card { transition:transform 0.2s ease,box-shadow 0.2s ease; border-radius:8px; overflow:hidden; }
    .product-card:hover { transform:translateY(-4px); box-shadow:0 8px 24px rgba(0,0,0,0.1) !important; }
    .product-card:hover .related-img { transform:scale(1.05); }
    .product-brand { font-size:0.75rem; text-transform:uppercase; letter-spacing:0.1em; color:#888; }
    .product-price { font-size:1rem; font-weight:600; color:#1a1a1a; }
    @media (max-width:768px) {
        .detail-img-wrapper, .detail-img { height:360px; }
    }
</style>

<?php include 'includes/footer.php'; ?>