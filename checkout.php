<?php session_start(); ?>

<?php
$rootPath = ".";
include 'includes/header.php';
?>
<?php include 'includes/nav.php'; ?>
<div class="checkout-container">
    <h2>Checkout -- Maison Reluxe</h2>
    <form action ="process_checkout.php" method="POST">
        <!-- Billing Details -->
        <div class="checkout-section">
            <h2 class="section-title">Billing Details</h2>

            <div class = "field">
                <label>Country / Region *</label>
                <input type ="text" name="country" placeholder="Enter your country" required>
            </div>
            <div class="field">
                <label>Street Address *</label>
                <input type="text" name="address" placeholder="House number and street name" required>
            </div>
            <div class = "field">
                <label>Town / City </label>
                <input type="text" name="city" placeholder="Enter your city"> 
            </div>
            <div class="field">
                <label>Postal Code *</label>
                <input type="text" name="postal_code" placeholder="Enter your postal code" required>
            </div>
        </div>

        <!-- Payment -->
        <div class="checkout-section payment-box">
            <div class ="payment-header">
                <h3>Payment</h3>
                <div class ="cards">
                    <img src = "https://img.icons8.com/color/48/visa.png"/>
                    <img src = "https://img.icons8.com/color/48/mastercard.png"/>
                    <img src = "https://img.icons8.com/color/48/amex.png"/>
                </div>
            </div>

            <div class="payment-inner">
                <div class="field">
                    <label>Card Number</label>
                    <input type="text" name="card_number" placeholder="1234 1234 1234 1234" required>
                </div>

                <div class = "row">
                    <div class="field">
                        <label>Expiry Date</label>
                        <input type="text" name="expiry" placeholder="MM/YY" required>
                    </div>
                    <div class="field">
                        <label>CVV</label>
                        <input type="text" name="cvv" placeholder="123" required>
                    </div>
                </div>

                <div class ="field">
                    <label> Cardholder name</label>
                    <input type="text" name="card_name" placeholder="Name on Card" required>
                </div>
            </div>
        </div>
        <button class="pay-btn">Pay Now</button>
    </form>

</div>
<?php include 'includes/footer.php'; ?>

