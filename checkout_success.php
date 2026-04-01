<?php session_start();
$rootpath = ".";
include 'includes/header.php';
include 'includes/nav.php';
?>

<main id="main-content">
    <div class ="success-container">
        <h2>Thank you for your purchase!</h2>
        <p>Your order has been successfully processed. We will send you a confirmation email shortly.</p>
        <a href="products.php" class="success-btn">Continue Shopping</a>
    </div>
</main>

<?php include 'includes/footer.php'; ?>