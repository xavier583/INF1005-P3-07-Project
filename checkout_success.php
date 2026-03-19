<?php session_start();
$rootpath = ".";
include 'includes/header.php';
include 'includes/nav.php';
?>

<div class ="success-container">
    <h2>Thank you for your purchase!</h2>
    <p>Your order has been successfully processed. We will send you a confirmation email shortly.</p>
    <a href="products.php" class="success-btn">Continue Shopping</a>
</div>

<?php include 'includes/footer.php'; ?>