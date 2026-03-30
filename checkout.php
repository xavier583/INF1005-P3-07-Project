<?php session_start(); 

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];

unset($_SESSION['errors'], $_SESSION['old']);


$rootPath = ".";
include 'includes/header.php';
include 'includes/nav.php'; 
function oldValue($key, $old) {
    return htmlspecialchars($old[$key] ?? '');
}
?>

<div class="checkout-container">
        <form action ="process_checkout.php" method="POST">
        <!-- Billing Details -->
        <div class="checkout-section">
            <h2 class="section-title">Billing Details</h2>

            <div class = "field">
                <label>Country / Region *</label>
                <input type ="text" name="country" placeholder="Enter your country" value ="<?php echo oldValue('country', $old); ?>" required>
                <?php if (isset($errors['country'])): ?>
                    <div class="field-error"><?php echo $errors['country']; ?></div>
                <?php endif; ?>
            </div>
            <div class="field">
                <label>Street Address *</label>
                <input type="text" name="address" placeholder="House number and street name" value ="<?php echo oldValue('address', $old); ?>" required>
                <?php if (isset($errors['address'])): ?>
                    <div class="field-error"><?php echo $errors['address']; ?></div>
                <?php endif; ?>
            </div>
            <div class = "field">
                <label>Town / City </label>
                <input type="text" name="city" placeholder="Enter your city" value ="<?php echo oldValue('city', $old); ?>"> 
            </div>
            <div class="field">
                <label>Postal Code *</label>
                <input type="text" name="postal_code" placeholder="Enter your postal code" value ="<?php echo oldValue('postal_code', $old); ?>" required>
                <?php if (isset($errors['postal_code'])): ?>
                    <div class="field-error"><?php echo $errors['postal_code']; ?></div>
                <?php endif; ?> 
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
                    <?php if (isset($errors['card_number'])): ?>
                        <div class="field-error"><?php echo $errors['card_number']; ?></div>
                    <?php endif; ?>
                </div>

                <div class = "row">
                    <div class="field">
                        <label>Expiry Date</label>
                        <input type="text" id="expiry" name="expiry" placeholder="MM/YY" maxlength="5" required>
                        <?php if (isset($errors['expiry'])): ?>
                            <div class="field-error"><?php echo $errors['expiry']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="field">
                        <label>CVV</label>
                        <input type="text" name="cvv" placeholder="123" required>
                        <?php if (isset($errors['cvv'])): ?>
                            <div class="field-error"><?php echo $errors['cvv']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class ="field">
                    <label> Cardholder name</label>
                    <input type="text" name="card_name" placeholder="Name on Card" required>
                    <?php if (isset($errors['card_name'])): ?>
                        <div class="field-error"><?php echo $errors['card_name']; ?></div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <button class="pay-btn">Pay Now</button>
    </form>

</div>

<script>
    // Auto-format expiry date with automatic "/" insertion
    document.getElementById('expiry').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
        
        if (value.length >= 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        
        e.target.value = value;
    });
</script>

<?php include 'includes/footer.php'; ?>
