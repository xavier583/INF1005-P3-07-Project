<?php
session_start();

// ─── Handle Cart Actions ──────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Remove item
    if (isset($_POST['remove_item'])) {
        $pid = (int)$_POST['product_id'];
        unset($_SESSION['cart'][$pid]);
    }

    // Update quantity
    if (isset($_POST['update_qty'])) {
        $pid = (int)$_POST['product_id'];
        $qty = (int)$_POST['quantity'];
        if ($qty <= 0) {
            unset($_SESSION['cart'][$pid]);
        } else {
            $_SESSION['cart'][$pid]['quantity'] = $qty;
        }
    }

    // Clear entire cart
    if (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = [];
    }
}

$cart  = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
$itemCount = array_sum(array_column($cart, 'quantity'));
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<main class="container my-5">

    <h1 class="cart-title text-center mb-5">Your Cart
        <?php if ($itemCount > 0): ?>
            <span class="cart-count"><?= $itemCount ?></span>
        <?php endif; ?>
    </h1>

    <?php if (empty($cart)): ?>
    <!-- Empty Cart State -->
    <div class="text-center empty-cart py-5">
        <div class="empty-cart-icon mb-4">🛒</div>
        <h4 class="mb-2">Your cart is empty</h4>
        <p class="text-muted mb-4">Looks like you haven't added anything yet.</p>
        <a href="products.php" class="btn btn-dark px-5">Browse Products</a>
    </div>

    <?php else: ?>
    <div class="row g-5">

        <!-- Cart Items -->
        <div class="col-lg-8">

            <!-- Column Headers -->
            <div class="row d-none d-md-flex text-muted small text-uppercase mb-2 px-2" style="letter-spacing:0.08em;">
                <div class="col-6">Item</div>
                <div class="col-2 text-center">Price</div>
                <div class="col-2 text-center">Qty</div>
                <div class="col-2 text-end">Subtotal</div>
            </div>
            <hr class="d-none d-md-block mt-0">

            <?php foreach ($cart as $item): ?>
            <div class="cart-item row align-items-center py-3 border-bottom">

                <!-- Image + Name -->
                <div class="col-12 col-md-6 d-flex align-items-center gap-3 mb-3 mb-md-0">
                    <img
                        src="images/<?= htmlspecialchars($item['image']) ?>"
                        alt="<?= htmlspecialchars($item['name']) ?>"
                        class="cart-item-img"
                    >
                    <div>
                        <p class="cart-item-brand mb-0"><?= htmlspecialchars($item['brand']) ?></p>
                        <p class="cart-item-name mb-0"><?= htmlspecialchars($item['name']) ?></p>
                        <!-- Mobile price -->
                        <p class="d-md-none text-muted small mb-0">$<?= number_format($item['price'], 2) ?></p>

                        <!-- Remove (mobile) -->
                        <form method="POST" class="d-md-none mt-1">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <button type="submit" name="remove_item" class="btn btn-link text-danger p-0 small">Remove</button>
                        </form>
                    </div>
                </div>

                <!-- Unit Price (desktop) -->
                <div class="col-md-2 text-center d-none d-md-block">
                    <span class="cart-price">$<?= number_format($item['price'], 2) ?></span>
                </div>

                <!-- Quantity -->
                <div class="col-6 col-md-2 text-center">
                    <form method="POST" class="d-flex align-items-center justify-content-center gap-1">
                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                        <button type="submit" name="update_qty" class="qty-btn" onclick="this.form.quantity.value=Math.max(0,parseInt(this.form.quantity.value)-1)">−</button>
                        <input
                            type="number"
                            name="quantity"
                            value="<?= $item['quantity'] ?>"
                            min="0"
                            max="99"
                            class="qty-input"
                            onchange="this.form.update_qty.click()"
                        >
                        <button type="submit" name="update_qty" class="qty-btn" onclick="this.form.quantity.value=parseInt(this.form.quantity.value)+1">+</button>
                    </form>
                </div>

                <!-- Subtotal + Remove (desktop) -->
                <div class="col-6 col-md-2 text-end d-flex flex-column align-items-end">
                    <span class="cart-subtotal">$<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                    <form method="POST" class="d-none d-md-block mt-1">
                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                        <button type="submit" name="remove_item" class="btn btn-link text-danger p-0 small">Remove</button>
                    </form>
                </div>

            </div>
            <?php endforeach; ?>

            <!-- Clear Cart -->
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <a href="products.php" class="btn btn-outline-dark btn-sm">← Continue Shopping</a>
                <form method="POST">
                    <button type="submit" name="clear_cart" class="btn btn-outline-danger btn-sm"
                        onclick="return confirm('Clear your entire cart?')">
                        Clear Cart
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="summary-card p-4">
                <h5 class="summary-title mb-4">Order Summary</h5>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Items (<?= $itemCount ?>)</span>
                    <span>$<?= number_format($total, 2) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Shipping</span>
                    <span class="text-success">Free</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">GST (9%)</span>
                    <span>$<?= number_format($total * 0.09, 2) ?></span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <strong>Total</strong>
                    <strong class="summary-total">$<?= number_format($total * 1.09, 2) ?></strong>
                </div>

                <a href="checkout.php" class="btn btn-dark w-100 checkout-btn">
                    Proceed to Checkout
                </a>
                <p class="text-muted text-center small mt-3">Secure checkout · All currencies in SGD</p>
            </div>
        </div>

    </div>
    <?php endif; ?>

</main>

<style>
    .cart-title {
        font-family: 'Georgia', serif;
        font-size: 2rem;
        font-weight: 400;
        position: relative;
        display: inline-block;
    }
    .cart-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #1a1a1a;
        color: #fff;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        font-size: 0.85rem;
        font-family: sans-serif;
        vertical-align: middle;
        margin-left: 8px;
    }
    .empty-cart-icon {
        font-size: 4rem;
    }
    .cart-item-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
        background: #f5f5f5;
        flex-shrink: 0;
    }
    .cart-item-brand {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #888;
    }
    .cart-item-name {
        font-size: 0.9rem;
        font-weight: 500;
        color: #1a1a1a;
        line-height: 1.3;
    }
    .cart-price {
        font-size: 0.95rem;
        color: #444;
    }
    .cart-subtotal {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a1a;
    }
    .qty-btn {
        width: 28px;
        height: 28px;
        border: 1px solid #ccc;
        background: #fff;
        border-radius: 4px;
        font-size: 1rem;
        line-height: 1;
        cursor: pointer;
        transition: background 0.15s;
    }
    .qty-btn:hover {
        background: #f0f0f0;
    }
    .qty-input {
        width: 40px;
        height: 28px;
        text-align: center;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 0.9rem;
        -moz-appearance: textfield;
    }
    .qty-input::-webkit-inner-spin-button,
    .qty-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
    }
    .summary-card {
        background: #fafafa;
        border: 1px solid #eee;
        border-radius: 10px;
        position: sticky;
        top: 20px;
    }
    .summary-title {
        font-family: 'Georgia', serif;
        font-weight: 400;
        font-size: 1.2rem;
    }
    .summary-total {
        font-size: 1.2rem;
        color: #1a1a1a;
    }
    .checkout-btn {
        padding: 13px;
        letter-spacing: 0.06em;
        font-size: 0.95rem;
    }
</style>

<?php include 'includes/footer.php'; ?>