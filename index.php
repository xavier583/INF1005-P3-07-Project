<?php
session_start();
$rootPath = ".";
include 'includes/header.php';
include 'includes/nav.php';

$homepageReviews = [];

include "php/db_connect.php";

if (isset($conn) && !$conn->connect_error) {
    $reviewCheck = $conn->query("SHOW TABLES LIKE 'maison_reluxe_reviews'");

    if ($reviewCheck && $reviewCheck->num_rows > 0) {
        $stmt = $conn->prepare("
            SELECT r.rating, r.review_text, r.created_at, m.username, p.name AS product_name
            FROM maison_reluxe_reviews r
            INNER JOIN maison_reluxe_members m ON r.member_id = m.member_id
            INNER JOIN maison_reluxe_products p ON r.product_id = p.product_id
            ORDER BY r.created_at DESC
            LIMIT 3
        ");

        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $homepageReviews[] = $row;
            }

            $stmt->close();
        }
    }

    $conn->close();
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

<section class="hero">
    <video autoplay muted loop playsinline id="hero-video">
        <source src="images/Maison Reluxe Homepage.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="hero-overlay">
        <img src="images/brand_logo.png" alt="Maison Reluxe Logo" class="hero-logo">

        <div class="hero-copy">
            <p>Curated Luxury, Timeless Style.</p>
            <a href="products.php" class="mainpage-btn">Explore Collection</a>
        </div>
    </div>

    <button id="video-control" class="video-control" aria-label="Pause Video">❚❚</button>
</section>

<section class="slideshow">
    <div class="slide">
        <img src="images/jewellery/bvlgari_bracelet.png" alt="Bvlgari Bracelet">
        <div class="slide-banner">
            <p class="slide-copy">Discover Our Many Products</p>
            <a href="product_detail.php?id=18" class="slide-action">View This Item</a>
        </div>
    </div>

    <div class="slide">
        <img src="images/bags/chanel_bag.png" alt="Chanel Bag">
        <div class="slide-banner">
            <p class="slide-copy">Discover Our Many Products</p>
            <a href="product_detail.php?id=27" class="slide-action">View This Item</a>
        </div>
    </div>

    <div class="slide">
        <img src="images/shoes/gucci 1.jpeg" alt="Gucci Shoes">
        <div class="slide-banner">
            <p class="slide-copy">Discover Our Many Products</p>
            <a href="product_detail.php?id=11" class="slide-action">View This Item</a>
        </div>
    </div>

    <div class="slide">
        <img src="images/watches/rolex 1.jpeg" alt="Rolex Watch">
        <div class="slide-banner">
            <p class="slide-copy">Discover Our Many Products</p>
            <a href="product_detail.php?id=1" class="slide-action">View This Item</a>
        </div>
    </div>
</section>

<section class="featured upgraded-section">
    <div class="section-intro text-center">
        <span class="section-label">Maison Reluxe</span>
        <h2>Featured Pieces</h2>
        <p>Handpicked luxury items chosen for timeless elegance and standout craftsmanship.</p>
    </div>

    <div class="container featured-cards">
        <div class="row g-4 justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card featured-card h-100">
                    <img src="images/bags/birkin 1.jpeg" class="card-img-top" alt="Hermès Bag">
                    <div class="card-body">
                        <h5 class="card-title">Hermès Birkin 30 Cacao</h5>
                        <p class="card-text">A signature statement piece with refined structure and iconic craftsmanship.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card featured-card h-100">
                    <img src="images/watches/rolex 4.jpeg" class="card-img-top" alt="Rolex Watch">
                    <div class="card-body">
                        <h5 class="card-title">Rolex Yacht-Master</h5>
                        <p class="card-text">A polished sports-luxury watch built for prestige, function, and presence.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card featured-card h-100">
                    <img src="images/jewellery/cartier_bracelet.jpg" class="card-img-top" alt="Cartier Bracelet">
                    <div class="card-body">
                        <h5 class="card-title">Cartier Love 18k Yellow Gold Bracelet</h5>
                        <p class="card-text">An elegant jewellery classic loved for its minimalist design and luxury finish.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="brand-promise upgraded-section">
    <div class="container">
        <div class="promise-box text-center">
            <span class="section-label">Why Maison Reluxe</span>
            <h2>Luxury you can trust</h2>
            <p>
                We curate premium second-hand designer fashion, jewellery, and watches with a focus on
                authenticity, timeless style, and exceptional condition.
            </p>

            <div class="promise-grid">
                <div class="promise-item">
                    <h5>Authenticity First</h5>
                    <p>Every item is carefully selected to give buyers confidence and peace of mind.</p>
                </div>

                <div class="promise-item">
                    <h5>Curated Selection</h5>
                    <p>Only standout pieces with enduring style make it into our collection.</p>
                </div>

                <div class="promise-item">
                    <h5>Luxury Reimagined</h5>
                    <p>We make premium fashion more accessible through thoughtful resale.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="review-preview upgraded-section">
    <div class="container">
        <div class="section-intro text-center">
            <span class="section-label">Customer Voices</span>
            <h2>What our customers are saying</h2>
            <p>Recent feedback from members who shopped with Maison Reluxe.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php if (count($homepageReviews) > 0): ?>
                <?php foreach ($homepageReviews as $review): ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="review-card h-100">
                            <div class="quote-mark">“</div>

                            <div class="review-header">
                                <div>
                                    <h5 class="review-product"><?php echo htmlspecialchars($review['product_name']); ?></h5>
                                    <div class="review-stars"><?php echo renderStars($review['rating']); ?></div>
                                </div>
                                <span class="review-user"><?php echo htmlspecialchars($review['username']); ?></span>
                            </div>

                            <p class="review-message">
                                <?php echo htmlspecialchars($review['review_text']); ?>
                            </p>

                            <div class="review-footer">
                                <small class="review-date"><?php echo htmlspecialchars($review['created_at']); ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="review-empty text-center">
                        <h4>No reviews yet</h4>
                        <p>Customer reviews will appear here once members start leaving feedback.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script src="./js/slideshow.js"></script>
<script src="./js/videoControl.js"></script>

<?php include 'includes/footer.php'; ?>