<?php
/*
 * product_detail.php – reads product from MySQL (maison_reluxe_products).
 * Cart and wishlist logic unchanged.
 */

session_start();
require_once 'php/db_connect.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: products.php');
    exit;
}

$stmt = $conn->prepare(
    "SELECT product_id AS id, name, price, image, category, brand, description,deleted
     FROM maison_reluxe_products
     WHERE product_id = ?
     LIMIT 1"
);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

$avgRating = 0;
$totalReviews = 0;

$stmt = $conn->prepare(
    "SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_reviews
    FROM maison_reluxe_reviews
    WHERE product_id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    $avgRating = round($data['avg_rating'], 1);
    $totalReviews = (int)$data['total_reviews'];
}

$stmt->close();

if (!$product) {
    header('Location: products.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_wishlist'])) {
    $pid = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
    $member_id = $_SESSION['user_id'] ?? null;

    // Check if already in wishlist
    $stmt = $conn->prepare("SELECT wishlist_id FROM maison_reluxe_wishlist WHERE member_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $member_id, $pid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // REMOVE
        $stmt = $conn->prepare("DELETE FROM maison_reluxe_wishlist WHERE member_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $member_id, $pid);
        $stmt->execute();
    } else {
        // ADD
        $stmt = $conn->prepare("INSERT INTO maison_reluxe_wishlist (member_id, product_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $member_id, $pid);
        $stmt->execute();
    }

    $stmt->close();
}

$cartMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $pid = (int) $_POST['product_id'];
    $member_id = $_SESSION['user_id'] ?? null;

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

    if ($member_id) {
        $stmt = $conn->prepare("
            INSERT INTO maison_reluxe_cart (member_id, product_id, quantity)
            VALUES (?, ?, 1)
            ON DUPLICATE KEY UPDATE quantity = quantity + 1
        ");
        $stmt->bind_param("ii", $member_id, $pid);
        $stmt->execute();
        $stmt->close();
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
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['user_id']);
if ($isLoggedIn)
    $member_id = $_SESSION['user_id'];
else
    $member_id = 0;
$isWishlisted = false;

if ($isLoggedIn) {
    $stmt = $conn->prepare("SELECT wishlist_id FROM maison_reluxe_wishlist WHERE member_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $member_id, $product['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $isWishlisted = $result->num_rows > 0;
    $stmt->close();
}

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
$reviewStmt = $conn->prepare("
    SELECT r.rating, r.review_text, r.created_at, m.username
    FROM maison_reluxe_reviews r
    INNER JOIN maison_reluxe_members m ON r.member_id = m.member_id
    WHERE r.product_id = ?
    ORDER BY r.created_at DESC
");
$reviewStmt->bind_param("i", $id);
$reviewStmt->execute();
$reviewResult = $reviewStmt->get_result();

$productReviews = [];
while ($row = $reviewResult->fetch_assoc()) {
    $productReviews[] = $row;
}
$reviewStmt->close();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $member_id = (int) $_SESSION['user_id'];
    $rating = (int) $_POST['rating'];
    $review_text = trim($_POST['review_text']);

    if ($rating >= 1 && $rating <= 5) {
        $stmt = $conn->prepare("
            INSERT INTO maison_reluxe_reviews (member_id, product_id, rating, review_text, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("iiis", $member_id, $id, $rating, $review_text);
        $stmt->execute();
        $stmt->close();

        header("Location: product_detail.php?id=" . $id);
        exit();
    }
}

function renderStars($rating)
{
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= $i <= (int)$rating ? '★' : '☆';
    }
    return $stars;
}
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
                                    class="detail-img">
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
                                aria-label="Slide <?= $index + 1 ?>"></button>
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

                <?php if ($product['deleted'] == 0): ?>
                    <form method="POST" action="" class="flex-fill">
                        <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                        <button type="submit" name="add_to_cart" class="btn btn-dark w-100">
                            Add to Cart
                        </button>
                    </form>

                    <form method="<?= $isLoggedIn ? 'POST' : 'GET' ?>" action="<?= $isLoggedIn ? '' : 'login.php' ?>" class="flex-fill">
                        <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                        <button
                            type="submit"
                            <?= $isLoggedIn ? 'name="toggle_wishlist"' : '' ?>
                            class="btn btn-outline-dark w-100 <?= $isWishlisted ? 'active' : '' ?>">
                            <i class="bi <?= $isWishlisted ? 'bi-heart-fill' : 'bi-heart' ?> me-2"></i>
                            <?= $isLoggedIn ? ($isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist') : 'Login to use Wishlist' ?>
                        </button>
                    </form>
                <?php else: ?>
                    <button type="button" class="btn btn-secondary w-100" disabled>
                        Unavailable
                    </button>
                <?php endif; ?>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <form method="GET" action="edit_product.php" class="flex-fill">
                        <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                        <button type="submit" class="btn btn-primary w-100">
                            Edit Product
                        </button>
                    </form>
                    <?php if ($product['deleted'] == 0): ?>
                        <form method="POST" action="delete_product.php" class="flex-fill" onsubmit="return confirm('Are you sure you want to remove this product?');">
                            <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                            <button type="submit" class="btn btn-danger w-100">
                                Remove Product
                            </button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="restore_product.php" class="flex-fill">
                            <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                            <button type="submit" class="btn btn-success w-100">
                                Restore Product
                            </button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>

                <a href="products.php?category=<?= urlencode($product['category']) ?>" class="btn btn-outline-dark mt-3 w-100">
                    ← Back to <?= htmlspecialchars($product['category']) ?>
                </a>
            </div>
        </div>
    </div>

    <div class="mt-5 pt-4 border-top">
        <h4 class="mb-4 text-start">Customer Reviews</h4>
        <div class="mb-3">
            <?php if ($totalReviews > 0): ?>
                <div class="average-rating">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= round($avgRating) ? '★' : '☆';
                    }
                    ?>
                    <span class="ms-2 text-muted">
                        <?= $avgRating ?> / 5 (<?= $totalReviews ?> review<?= $totalReviews > 1 ? 's' : '' ?>)
                    </span>
                </div>
            <?php else: ?>
                <span class="text-muted">No reviews yet</span>
            <?php endif; ?>
        </div>

        <?php if (!empty($productReviews)): ?>
            <div class="reviews-list">
                <?php foreach ($productReviews as $review): ?>
                    <div class="review-item">
                        <div class="review-top">
                            <div>
                                <strong><?php echo htmlspecialchars($review['username']); ?></strong>
                                <div class="review-stars">
                                    <?php echo renderStars($review['rating']); ?>
                                </div>
                            </div>
                            <small class="review-date">
                                <?php echo htmlspecialchars($review['created_at']); ?>
                            </small>
                        </div>
                        <p class="review-text mb-0">
                            <?php echo htmlspecialchars($review['review_text']); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>Be the first to review this product.</p>
            </div>
        <?php endif; ?>

        <button class="btn btn-dark mt-3" onclick="toggleReviewForm()">Leave a Review</button>

        <div id="reviewForm" style="display:none;" class="mt-3">
            <form method="POST">
                <div class="mb-2">
                    <div class="mb-3">
                        <label class="form-label">Rating:</label>
                        <div class="star-rating">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>" required>
                                <label for="star<?= $i ?>">★</label>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <div class="mb-2">
                    <textarea name="review_text" class="form-control" rows="3" placeholder="Write your review..." required></textarea>
                </div>

                <button type="submit" name="submit_review" class="btn btn-success">
                    Submit Review
                </button>
            </form>
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
                                        class="card-img-top related-img">
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

<script>
    function toggleReviewForm() {
        const form = document.getElementById("reviewForm");
        form.style.display = form.style.display === "none" ? "block" : "none";
    }
</script>

<style>
    .review-stars {
        color: #f5c518;
    }

    .star-rating {
        direction: rtl;
        display: inline-flex;
        gap: 5px;
        font-size: 1.6rem;

    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        cursor: pointer;
        color: #ccc;
        transition: color 0.2s;
    }

    .star-rating input:checked~label {
        color: #f5c518;
    }

    .star-rating label:hover,
    .star-rating label:hover~label {
        color: #f5c518;
    }

    .average-rating {
        font-size: 1.2rem;
        color: #f5c518;
        letter-spacing: 2px;
    }
</style>


<?php include 'includes/footer.php'; ?>