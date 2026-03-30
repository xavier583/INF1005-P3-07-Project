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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

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
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    header('Location: products.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_wishlist'])) {
    $pid = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
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

$cartMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $pid = (int) $_POST['product_id'];

    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]['quantity']++;
    } else {
        $_SESSION['cart'][$pid] = [
            'id'       => (int) $product['id'],
            'name'     => $product['name'],
            'price'    => (float) $product['price'],
            'image'    => $product['image'],
            'brand'    => $product['brand'],
            'quantity' => 1,
        ];
    }

    $cartMessage = 'success';
}

function normalizeImagePath(string $path): string
{
    $path = trim(str_replace('\\', '/', $path));

    if ($path === '') {
        return 'images/placeholder.jpg';
    }

    if (strpos($path, 'images/') === 0) {
        return $path;
    }

    return 'images/' . ltrim($path, '/');
}

function buildProductGalleryImages(string $imagePath): array
{
    $imagePath = trim(str_replace('\\', '/', $imagePath));

    if ($imagePath === '') {
        return [];
    }

    $relativePath = preg_replace('#^images/#', '', $imagePath);

    $gallery = [normalizeImagePath($imagePath)];
    $baseDir = dirname($relativePath);
    $baseName = pathinfo($relativePath, PATHINFO_FILENAME);
    $root = __DIR__ . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;

    $scanDir = $root;
    if ($baseDir !== '.' && $baseDir !== '') {
        $scanDir .= str_replace('/', DIRECTORY_SEPARATOR, $baseDir) . DIRECTORY_SEPARATOR;
    }

    $pattern = $scanDir . $baseName . '*';

    foreach (glob($pattern) ?: [] as $file) {
        if (!is_file($file)) {
            continue;
        }

        if (!preg_match('/\.(jpe?g|png|webp|gif)$/i', $file)) {
            continue;
        }

        $relativeToProject = str_replace('\\', '/', substr($file, strlen(__DIR__) + 1));

        if (!in_array($relativeToProject, $gallery, true)) {
            $gallery[] = $relativeToProject;
        }
    }

    return $gallery;
}

$galleryImages = buildProductGalleryImages($product['image']);
$isWishlisted = in_array((int) $product['id'], array_map('intval', $_SESSION['wishlist']), true);

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

<main class="container my-5 product-detail-page">

    <?php if ($cartMessage === 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            <strong><?= htmlspecialchars($product['name']) ?></strong> has been added to your cart.
            <a href="cart.php" class="alert-link ms-2">View Cart →</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <nav aria-label="breadcrumb" class="mb-4 text-start">
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

    <div class="row g-5 align-items-start product-detail-layout">

        <div class="col-lg-6 col-md-6">
            <div id="productGalleryCarousel" class="carousel slide detail-carousel" data-bs-ride="false">
                <div class="carousel-inner">
                    <?php foreach ($galleryImages as $index => $img): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <div class="detail-img-wrapper">
                                <img
                                    src="<?= htmlspecialchars($img) ?>"
                                    alt="<?= htmlspecialchars($product['name']) ?> image <?= $index + 1 ?>"
                                    class="detail-img"
                                >
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($galleryImages) > 1): ?>
                    <button class="carousel-control-prev custom-carousel-control" type="button" data-bs-target="#productGalleryCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon custom-carousel-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>

                    <button class="carousel-control-next custom-carousel-control" type="button" data-bs-target="#productGalleryCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon custom-carousel-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>

                    <div class="carousel-indicators detail-carousel-indicators">
                        <?php foreach ($galleryImages as $index => $img): ?>
                            <button
                                type="button"
                                data-bs-target="#productGalleryCarousel"
                                data-bs-slide-to="<?= $index ?>"
                                class="<?= $index === 0 ? 'active' : '' ?>"
                                aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                                aria-label="Slide <?= $index + 1 ?>"
                            ></button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="detail-info text-start">
                <p class="detail-brand"><?= htmlspecialchars($product['brand']) ?></p>
                <h1 class="detail-name"><?= htmlspecialchars($product['name']) ?></h1>
                <p class="detail-category text-muted mb-3"><?= htmlspecialchars($product['category']) ?></p>
                <p class="detail-price">$<?= number_format((float) $product['price'], 2) ?></p>
                <hr>
                <p class="detail-description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                <hr>

                <form method="POST" action="">
                    <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                    <button type="submit" name="add_to_cart" class="btn btn-dark btn-lg w-100 add-cart-btn">
                        Add to Cart
                    </button>
                </form>

                <form method="POST" action="" class="mt-2">
                    <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                    <button
                        type="submit"
                        name="toggle_wishlist"
                        class="btn btn-outline-dark w-100 wishlist-detail-btn <?= $isWishlisted ? 'active' : '' ?>"
                    >
                        <i class="bi <?= $isWishlisted ? 'bi-heart-fill' : 'bi-heart' ?> me-2"></i>
                        <?= $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' ?>
                    </button>
                </form>

                <a href="products.php?category=<?= urlencode($product['category']) ?>" class="btn btn-outline-dark mt-3 w-100">
                    ← Back to <?= htmlspecialchars($product['category']) ?>
                </a>
            </div>
        </div>
    </div>

    <?php if (!empty($related)): ?>
        <div class="mt-5 pt-4 border-top">
            <h4 class="mb-4 related-title text-start">You May Also Like</h4>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($related as $r): ?>
                    <div class="col">
                        <a href="product_detail.php?id=<?= (int) $r['id'] ?>" class="text-decoration-none text-dark">
                            <div class="card h-100 product-card border-0 shadow-sm">
                                <div class="related-img-wrapper">
                                    <img
                                        src="<?= htmlspecialchars(normalizeImagePath($r['image'])) ?>"
                                        alt="<?= htmlspecialchars($r['name']) ?>"
                                        class="card-img-top related-img"
                                    >
                                </div>
                                <div class="card-body text-start">
                                    <p class="product-brand mb-1"><?= htmlspecialchars($r['brand']) ?></p>
                                    <h6 class="card-title"><?= htmlspecialchars($r['name']) ?></h6>
                                    <p class="product-price mb-0">$<?= number_format((float) $r['price'], 2) ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

</main>

<?php include 'includes/footer.php'; ?>