<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$rootPath = ".";
include 'includes/header.php';
include 'includes/nav.php';

$user_id = (int)$_SESSION['user_id'];
$username = $_SESSION['username'] ?? '';
$email = $_SESSION['email'] ?? '';

$orders = [];
$reviews = [];

include "php/db_connect.php";

if (isset($conn) && !$conn->connect_error) {
    $orderCheck = $conn->query("SHOW TABLES LIKE 'maison_reluxe_orders'");
    $itemCheck = $conn->query("SHOW TABLES LIKE 'maison_reluxe_order_items'");

    if ($orderCheck && $orderCheck->num_rows > 0 && $itemCheck && $itemCheck->num_rows > 0) {
        $orderStmt = $conn->prepare("
            SELECT 
                o.order_id,
                o.order_date,
                o.total_amount,
                o.status,
                o.country,
                o.address,
                o.city,
                o.postal_code,
                p.name AS product_name,
                oi.quantity,
                oi.price
            FROM maison_reluxe_orders o
            INNER JOIN maison_reluxe_order_items oi ON o.order_id = oi.order_id
            INNER JOIN maison_reluxe_products p ON oi.product_id = p.product_id
            WHERE o.member_id = ?
            ORDER BY o.order_date DESC
        ");
        $orderStmt->bind_param("i", $user_id);
        $orderStmt->execute();
        $orderResult = $orderStmt->get_result();

        while ($row = $orderResult->fetch_assoc()) {
            $orders[] = $row;
        }
        $orderStmt->close();
    }

    $reviewCheck = $conn->query("SHOW TABLES LIKE 'maison_reluxe_reviews'");
    if ($reviewCheck && $reviewCheck->num_rows > 0) {
        $reviewStmt = $conn->prepare("
            SELECT r.review_id, r.rating, r.review_text, r.created_at, p.name AS product_name
            FROM maison_reluxe_reviews r
            INNER JOIN maison_reluxe_products p ON r.product_id = p.product_id
            WHERE r.member_id = ?
            ORDER BY r.created_at DESC
        ");
        $reviewStmt->bind_param("i", $user_id);
        $reviewStmt->execute();
        $reviewResult = $reviewStmt->get_result();

        while ($row = $reviewResult->fetch_assoc()) {
            $reviews[] = $row;
        }
        $reviewStmt->close();
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

<div class="profile-page container py-5">
    <div class="profile-hero text-center mb-5">
        <div class="profile-avatar mx-auto mb-3">
            <?php echo strtoupper(substr($username, 0, 1)); ?>
        </div>
        <h1 class="profile-title mb-2">My Profile</h1>
        <p class="profile-subtitle mb-0">Manage your account, orders, and reviews in one place.</p>
    </div>

    <div class="profile-card mb-4">
        <div class="section-heading">
            <h2>User Information</h2>
            <p>Your account details</p>
        </div>

        <div class="info-grid">
            <div class="info-box">
                <span class="info-label">Username</span>
                <span class="info-value"><?php echo htmlspecialchars($username); ?></span>
            </div>
            <div class="info-box">
                <span class="info-label">Email</span>
                <span class="info-value"><?php echo htmlspecialchars($email); ?></span>
            </div>
        </div>

        <a href="logout.php" class="btn btn-outline-dark">Log Out</a>
    </div>

    <div class="profile-card mb-4">
        <div class="section-heading">
            <h2>Order History</h2>
            <p>Your shipped and completed orders</p>
        </div>

        <?php if (count($orders) > 0): ?>
            <div class="orders-list">
                <?php foreach ($orders as $order): ?>
                    <div class="order-item">
                        <div class="order-top">
                            <div>
                                <h5 class="mb-1">Order #<?php echo htmlspecialchars($order['order_id']); ?></h5>
                                <div class="order-product"><?php echo htmlspecialchars($order['product_name']); ?></div>
                            </div>
                            <span class="status-badge"><?php echo htmlspecialchars(ucfirst($order['status'])); ?></span>
                        </div>

                        <div class="order-details">
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                            <p><strong>Quantity:</strong> <?php echo htmlspecialchars($order['quantity']); ?></p>
                            <p><strong>Item Price:</strong> $<?php echo number_format((float)$order['price'], 2); ?></p>
                            <p><strong>Total Amount:</strong> $<?php echo number_format((float)$order['total_amount'], 2); ?></p>
                            <p>
                                <strong>Shipping Address:</strong>
                                <?php
                                echo htmlspecialchars($order['address']) . ", ";
                                echo htmlspecialchars($order['city']) . ", ";
                                echo htmlspecialchars($order['country']) . " ";
                                echo htmlspecialchars($order['postal_code']);
                                ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h4>No orders found yet</h4>
                <p>Your completed or shipped orders will appear here.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="profile-card">
        <div class="section-heading">
            <h2>My Reviews</h2>
            <p>Your recent product feedback</p>
        </div>

        <?php if (count($reviews) > 0): ?>
            <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-item">
                        <div class="review-top">
                            <div>
                                <h5 class="review-product mb-1"><?php echo htmlspecialchars($review['product_name']); ?></h5>
                                <div class="review-stars"><?php echo renderStars($review['rating']); ?></div>
                            </div>
                            <small class="review-date"><?php echo htmlspecialchars($review['created_at']); ?></small>
                        </div>
                        <p class="review-text mb-0"><?php echo htmlspecialchars($review['review_text']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h4>No reviews found yet</h4>
                <p>Your product reviews will appear here once added.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .profile-page {
        max-width: 1100px;
    }

    .profile-hero {
        padding: 10px 0 0;
    }

    .profile-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: #111;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    }

    .profile-title {
        font-size: 3rem;
        font-weight: 600;
        color: #1f1f1f;
    }

    .profile-subtitle {
        color: #666;
        font-size: 1.05rem;
    }

    .profile-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 18px;
        padding: 28px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }

    .section-heading h2 {
        font-size: 2rem;
        margin-bottom: 6px;
        color: #1f1f1f;
    }

    .section-heading p {
        color: #777;
        margin-bottom: 22px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 18px;
    }

    .info-box {
        background: #f8f8f8;
        border-radius: 14px;
        padding: 18px 20px;
        border: 1px solid #efefef;
    }

    .info-label {
        display: block;
        font-size: 0.9rem;
        color: #777;
        margin-bottom: 6px;
    }

    .info-value {
        font-size: 1.2rem;
        font-weight: 600;
        color: #1f1f1f;
        word-break: break-word;
    }

    .orders-list,
    .reviews-list {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .order-item,
    .review-item {
        background: #fafafa;
        border: 1px solid #ececec;
        border-radius: 14px;
        padding: 18px 20px;
    }

    .order-top,
    .review-top {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .order-product,
    .review-product {
        font-size: 1.08rem;
        font-weight: 600;
        color: #1f1f1f;
    }

    .order-details p {
        margin-bottom: 8px;
        color: #444;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 999px;
        background: #111;
        color: #fff;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 26px 10px 10px;
        color: #666;
    }

    .empty-state h4 {
        color: #1f1f1f;
        margin-bottom: 10px;
    }

    .review-stars {
        color: #111;
        letter-spacing: 2px;
        font-size: 1.05rem;
    }

    .review-date {
        color: #777;
        white-space: nowrap;
    }

    .review-text {
        color: #444;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .profile-title {
            font-size: 2.2rem;
        }

        .section-heading h2 {
            font-size: 1.6rem;
        }

        .order-top,
        .review-top {
            flex-direction: column;
        }

        .review-date {
            white-space: normal;
        }
    }
</style>

<?php include 'includes/footer.php'; ?>