<?php
/*
 * products.php  – reads product data from MySQL (maison_reluxe_products).
 * Wishlist still stored in session as before.
 */

session_start();
require_once 'php/db_connect.php';

// ── Wishlist toggle ───────────────────────────────────────────────────────────
if (!isset($_SESSION['wishlist']) || !is_array($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_wishlist'])) {
    $pid = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $key = array_search($pid, $_SESSION['wishlist'], true);

    if ($key === false) {
        $_SESSION['wishlist'][] = $pid;
    } else {
        unset($_SESSION['wishlist'][$key]);
        $_SESSION['wishlist'] = array_values($_SESSION['wishlist']);
    }

    $redirectTo = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : 'products.php';
    if (strpos($redirectTo, 'products.php') !== 0) {
        $redirectTo = 'products.php';
    }
    header('Location: ' . $redirectTo);
    exit;
}

// ── Category banner images (unchanged) ───────────────────────────────────────
$categories = [
    'Watches'     => 'product page/watch_product_page.jpg',
    'Jewellery'   => 'product page/jewellery_product_page.jpg',
    'Shoes'       => 'product page/shoes_product_page.jpg',
    'Clothes'     => 'product page/clothes_product_page.jpg',
    'Bags'        => 'product page/bags_product_page.jpg',
    'Accessories' => 'product page/accessories_product_page.jpg',
];

// ── Read request params ───────────────────────────────────────────────────────
$activeCategory = isset($_GET['category']) ? trim($_GET['category']) : null;
$showGrid       = $activeCategory && array_key_exists($activeCategory, $categories);
$searchQuery    = isset($_GET['search']) ? trim($_GET['search']) : '';
$showWishlist   = isset($_GET['wishlist']) && $_GET['wishlist'] === '1';
$wishlistIds    = array_map('intval', $_SESSION['wishlist']);
$currentUrl     = 'products.php' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');

// ── Fetch products from database ──────────────────────────────────────────────

/**
 * Build a safe query and return an array of product rows.
 * $category : string|null  – filter by category, or null for all
 * $search   : string       – search term, or '' for no filter
 */
function fetchProducts(mysqli $conn, ?string $category, string $search): array {
    $where  = [];
    $params = [];
    $types  = '';

    if ($category !== null) {
        $where[]  = 'category = ?';
        $params[] = $category;
        $types   .= 's';
    }

    if ($search !== '') {
        $like     = '%' . $search . '%';
        $where[]  = '(name LIKE ? OR brand LIKE ? OR description LIKE ?)';
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $types   .= 'sss';
    }

    $sql = 'SELECT product_id AS id, name, price, image, category, brand, description
            FROM maison_reluxe_products';
    if (!empty($where)) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= ' ORDER BY product_id';

    if (empty($params)) {
        $result = $conn->query($sql);
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $stmt->close();
    return $rows;
}

// Products for the current view
$filtered       = $showGrid  ? fetchProducts($conn, $activeCategory, $searchQuery) : [];
$globalFiltered = (!$showGrid && $searchQuery !== '') ? fetchProducts($conn, null, $searchQuery) : [];

// Wishlist products
$wishlistProducts = [];
if ($showWishlist && !empty($wishlistIds)) {
    $placeholders = implode(',', array_fill(0, count($wishlistIds), '?'));
    $types        = str_repeat('i', count($wishlistIds));
    $stmt = $conn->prepare(
        "SELECT product_id AS id, name, price, image, category, brand, description
         FROM maison_reluxe_products
         WHERE product_id IN ($placeholders)
         ORDER BY product_id"
    );
    $stmt->bind_param($types, ...$wishlistIds);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $wishlistProducts[] = $row;
    }
    $stmt->close();
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<main class="container my-5">

<?php if ($showWishlist): ?>
    <div class="text-center mb-5">
        <h1 class="products-title">Your Wishlist</h1>
        <p class="text-muted" style="font-size:1.05em;">Items you hearted will appear here.</p>
        <a href="products.php" class="btn btn-outline-dark btn-sm mt-2">← Back to Products</a>
    </div>

    <?php if (empty($wishlistProducts)): ?>
        <p class="text-center text-muted">Your wishlist is empty. Tap the heart icon on a product to save it.</p>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($wishlistProducts as $product): ?>
            <?php $isWishlisted = in_array((int)$product['id'], $wishlistIds, true); ?>
            <div class="col">
                <div class="product-tile position-relative">
                    <form method="POST" class="wishlist-toggle-form">
                        <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                        <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($currentUrl) ?>">
                        <button type="submit" name="toggle_wishlist" class="wishlist-btn active" aria-label="Remove from wishlist">
                            <i class="bi <?= $isWishlisted ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                        </button>
                    </form>
                    <a href="product_detail.php?id=<?= $product['id'] ?>" class="text-decoration-none text-dark">
                        <div class="card h-100 product-card border-0 shadow-sm">
                            <div class="product-img-wrapper">
                                <img src="images/<?= htmlspecialchars($product['image']) ?>"
                                     alt="<?= htmlspecialchars($product['name']) ?>"
                                     class="card-img-top product-img">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <p class="product-brand mb-1"><?= htmlspecialchars($product['brand']) ?></p>
                                <h6 class="card-title product-name"><?= htmlspecialchars($product['name']) ?></h6>
                                <p class="product-price mt-auto mb-0">$<?= number_format((float)$product['price'], 2) ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php elseif (!$showGrid): ?>
<!-- ═══════════════════════════════════════════════════════════
     VIEW 1: Category Landing (image cards)
══════════════════════════════════════════════════════════════ -->
    <div class="text-center mb-5">
        <h1 class="products-title">Our Products</h1>
        <p class="text-muted" style="font-size:1.1em;">Browse our collection of luxury secondhand goods.</p>
        <h2 class="categories-subtitle mt-4">Categories</h2>

        <form method="GET" action="products.php" class="product-search-form mt-4 mb-4">
            <div class="input-group">
                <input type="text" name="search"
                       value="<?= htmlspecialchars($searchQuery) ?>"
                       class="form-control"
                       placeholder="Search all products by name, brand, or keyword"
                       aria-label="Search all products">
                <button type="submit" class="btn btn-dark">Search</button>
                <?php if ($searchQuery !== ''): ?>
                    <a href="products.php" class="btn btn-outline-secondary">Clear</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center mb-5">
        <?php foreach ($categories as $catName => $catImage): ?>
        <div class="col">
            <a href="products.php?category=<?= urlencode($catName) ?>" class="text-decoration-none">
                <div class="category-card">
                    <img src="images/<?= htmlspecialchars($catImage) ?>"
                         alt="<?= htmlspecialchars($catName) ?>"
                         class="category-img">
                    <div class="category-overlay">
                        <span class="category-label"><?= htmlspecialchars($catName) ?></span>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if ($searchQuery !== ''): ?>
        <div class="mb-3 text-center text-muted small">
            <?= count($globalFiltered) ?> result<?= count($globalFiltered) === 1 ? '' : 's' ?> for "<?= htmlspecialchars($searchQuery) ?>"
        </div>
        <?php if (empty($globalFiltered)): ?>
            <p class="text-center text-muted mb-5">No products match your search.</p>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
                <?php foreach ($globalFiltered as $product): ?>
                <?php $isWishlisted = in_array((int)$product['id'], $wishlistIds, true); ?>
                <div class="col">
                    <div class="product-tile position-relative">
                        <form method="POST" class="wishlist-toggle-form">
                            <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                            <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($currentUrl) ?>">
                            <button type="submit" name="toggle_wishlist"
                                    class="wishlist-btn <?= $isWishlisted ? 'active' : '' ?>"
                                    aria-label="<?= $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' ?>">
                                <i class="bi <?= $isWishlisted ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                            </button>
                        </form>
                        <a href="product_detail.php?id=<?= $product['id'] ?>" class="text-decoration-none text-dark">
                            <div class="card h-100 product-card border-0 shadow-sm">
                                <div class="product-img-wrapper">
                                    <img src="images/<?= htmlspecialchars($product['image']) ?>"
                                         alt="<?= htmlspecialchars($product['name']) ?>"
                                         class="card-img-top product-img">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <p class="product-brand mb-1"><?= htmlspecialchars($product['brand']) ?></p>
                                    <h6 class="card-title product-name"><?= htmlspecialchars($product['name']) ?></h6>
                                    <p class="product-price mt-auto mb-0">$<?= number_format((float)$product['price'], 2) ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

<?php else: ?>
<!-- ═══════════════════════════════════════════════════════════
     VIEW 2: Product Grid for selected category
══════════════════════════════════════════════════════════════ -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="products.php" class="text-dark">All Categories</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($activeCategory) ?></li>
        </ol>
    </nav>

    <div class="category-banner mb-5"
         style="background-image: url('images/<?= htmlspecialchars($categories[$activeCategory]) ?>');">
        <div class="category-banner-overlay">
            <h1 class="category-banner-title"><?= htmlspecialchars($activeCategory) ?></h1>
            <p class="category-banner-sub">
                <?= count($filtered) ?> items available<?= $searchQuery !== '' ? ' for &quot;' . htmlspecialchars($searchQuery) . '&quot;' : '' ?>
            </p>
        </div>
    </div>

    <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
        <a href="products.php" class="btn btn-dark btn-sm">← All Categories</a>
        <?php foreach ($categories as $catName => $catImage): ?>
            <?php if ($catName !== $activeCategory): ?>
            <a href="products.php?category=<?= urlencode($catName) ?>" class="btn btn-outline-dark btn-sm">
                <?= htmlspecialchars($catName) ?>
            </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <?php if (empty($filtered)): ?>
        <p class="text-center text-muted">
            No products found<?= $searchQuery !== '' ? ' for &quot;' . htmlspecialchars($searchQuery) . '&quot;' : '' ?> in this category.
        </p>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($filtered as $product): ?>
            <?php $isWishlisted = in_array((int)$product['id'], $wishlistIds, true); ?>
            <div class="col">
                <div class="product-tile position-relative">
                    <form method="POST" class="wishlist-toggle-form">
                        <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                        <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($currentUrl) ?>">
                        <button type="submit" name="toggle_wishlist"
                                class="wishlist-btn <?= $isWishlisted ? 'active' : '' ?>"
                                aria-label="<?= $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' ?>">
                            <i class="bi <?= $isWishlisted ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                        </button>
                    </form>
                    <a href="product_detail.php?id=<?= $product['id'] ?>" class="text-decoration-none text-dark">
                        <div class="card h-100 product-card border-0 shadow-sm">
                            <div class="product-img-wrapper">
                                <img src="images/<?= htmlspecialchars($product['image']) ?>"
                                     alt="<?= htmlspecialchars($product['name']) ?>"
                                     class="card-img-top product-img">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <p class="product-brand mb-1"><?= htmlspecialchars($product['brand']) ?></p>
                                <h6 class="card-title product-name"><?= htmlspecialchars($product['name']) ?></h6>
                                <p class="product-price mt-auto mb-0">$<?= number_format((float)$product['price'], 2) ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php endif; ?>
</main>

<style>
    .products-title { font-family:'Georgia',serif; font-size:2rem; font-weight:400; letter-spacing:.04em; }
    .categories-subtitle { font-family:'Georgia',serif; font-size:1.4rem; font-weight:400; color:#444; }
    .category-card { position:relative; border-radius:10px; overflow:hidden; cursor:pointer; height:200px; box-shadow:0 4px 12px rgba(0,0,0,.1); transition:transform .25s ease,box-shadow .25s ease; }
    .category-card:hover { transform:translateY(-5px); box-shadow:0 10px 28px rgba(0,0,0,.18); }
    .category-img { width:100%; height:100%; object-fit:cover; transition:transform .35s ease; display:block; }
    .category-card:hover .category-img { transform:scale(1.07); }
    .category-overlay { position:absolute; inset:0; background:rgba(0,0,0,.38); display:flex; align-items:center; justify-content:center; transition:background .25s ease; }
    .category-card:hover .category-overlay { background:rgba(0,0,0,.50); }
    .category-label { color:#fff; font-size:1.3rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; text-shadow:0 1px 4px rgba(0,0,0,.5); }
    .category-banner { width:100%; height:200px; border-radius:10px; background-size:cover; background-position:center; position:relative; overflow:hidden; }
    .category-banner-overlay { position:absolute; inset:0; background:rgba(0,0,0,.45); display:flex; flex-direction:column; align-items:center; justify-content:center; }
    .category-banner-title { font-family:'Georgia',serif; font-size:2.2rem; font-weight:400; color:#fff; letter-spacing:.08em; text-shadow:0 2px 6px rgba(0,0,0,.4); margin:0; }
    .category-banner-sub { color:rgba(255,255,255,.8); font-size:.9rem; margin-top:6px; margin-bottom:0; }
    .product-search-form { max-width:760px; width:100%; margin-left:auto; margin-right:auto; }
    .product-search-form .input-group .form-control { min-height:46px; }
    .product-tile { height:100%; }
    .wishlist-toggle-form { position:absolute; top:10px; right:10px; z-index:5; margin:0; width:auto; }
    .wishlist-btn { width:36px; height:36px; border-radius:50%; border:1px solid #dedede; background:rgba(255,255,255,.95); color:#333; display:inline-flex; align-items:center; justify-content:center; transition:all .2s ease; }
    .wishlist-btn:hover { border-color:#1a1a1a; color:#1a1a1a; }
    .wishlist-btn.active { color:#c71f37; border-color:#f1c9cf; background:#fff7f8; }
    .product-card { border-radius:8px; overflow:hidden; transition:transform .2s ease,box-shadow .2s ease; cursor:pointer; }
    .product-card:hover { transform:translateY(-5px); box-shadow:0 8px 24px rgba(0,0,0,.12) !important; }
    .product-img-wrapper { width:100%; height:250px; overflow:hidden; background:#f5f5f5; }
    .product-img { width:100%; height:100%; object-fit:cover; transition:transform .3s ease; }
    .product-card:hover .product-img { transform:scale(1.05); }
    .product-brand { font-size:.75rem; text-transform:uppercase; letter-spacing:.1em; color:#888; }
    .product-name { font-size:.9rem; font-weight:500; color:#2c2c2c; line-height:1.4; }
    .product-price { font-size:1rem; font-weight:600; color:#1a1a1a; }
</style>

<?php include 'includes/footer.php'; ?>