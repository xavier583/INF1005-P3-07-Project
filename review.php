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
            LIMIT 12
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
                            <div class="quote-mark">&#8220;</div>

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

<style>
    .upgraded-section {
        padding: 80px 0;
    }

    .section-intro {
        max-width: 760px;
        margin: 0 auto 40px;
    }

    .section-label {
        display: inline-block;
        font-size: 0.85rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #777;
        margin-bottom: 12px;
    }

    .section-intro h2 {
        font-size: 2.6rem;
        margin-bottom: 14px;
        color: #1f1f1f;
    }

    .section-intro p {
        color: #666;
        font-size: 1.05rem;
        line-height: 1.7;
    }

    .review-preview {
        background: linear-gradient(180deg, #ffffff 0%, #f9f9f9 100%);
    }

    .review-card {
        position: relative;
        background: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 22px;
        padding: 26px;
        box-shadow: 0 12px 34px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .review-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.1);
    }

    .quote-mark {
        position: absolute;
        top: 8px;
        right: 18px;
        font-size: 4rem;
        line-height: 1;
        color: rgba(0, 0, 0, 0.06);
        font-weight: 700;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 18px;
        position: relative;
        z-index: 1;
    }

    .review-product {
        font-size: 1.12rem;
        font-weight: 700;
        color: #1f1f1f;
        margin-bottom: 6px;
    }

    .review-stars {
        letter-spacing: 2px;
        color: #111;
        font-size: 1rem;
    }

    .review-user {
        background: #111;
        color: #fff;
        border-radius: 999px;
        padding: 7px 13px;
        font-size: 0.78rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .review-message {
        color: #444;
        line-height: 1.8;
        margin-bottom: 18px;
        position: relative;
        z-index: 1;
    }

    .review-footer {
        border-top: 1px solid #ececec;
        padding-top: 14px;
    }

    .review-date {
        color: #888;
    }

    .review-empty {
        background: #fafafa;
        border: 1px solid #ececec;
        border-radius: 18px;
        padding: 35px 20px;
    }

    .review-empty h4 {
        color: #1f1f1f;
        margin-bottom: 10px;
    }

    .review-empty p {
        color: #666;
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .upgraded-section {
            padding: 60px 0;
        }

        .section-intro h2 {
            font-size: 2rem;
        }

        .review-header {
            flex-direction: column;
        }

        .review-user {
            white-space: normal;
        }
    }
</style>

<?php include 'includes/footer.php'; ?>