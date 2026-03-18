<?php
session_start();

// ─── Same Product Array (copy from products.php) ──────────────────────────
// In future, this will be replaced by a DB query
$products = [
    ['id'=>1,'name'=>'Rolex Submariner Ceramic Bezel','price'=>20500.00,'image'=>'rolex 1.jpeg','category'=>'Watches','brand'=>'Rolex','description'=>'A legendary diver\'s watch with a unidirectional rotatable bezel and Cerachrom ceramic insert. Water-resistant to 300 metres, powered by the calibre 3235 movement. A timeless icon of precision and luxury.'],
    ['id'=>2,'name'=>'Rolex Datejust Stainless Steel','price'=>22000.00,'image'=>'rolex 2.jpeg','category'=>'Watches','brand'=>'Rolex','description'=>'The quintessential dress watch. Features a classic Jubilee bracelet, Cyclops lens over the date, and a scratch-resistant sapphire crystal. A symbol of refined taste.'],
    ['id'=>3,'name'=>'Rolex Cosmograph Daytona','price'=>35100.00,'image'=>'rolex 3.jpeg','category'=>'Watches','brand'=>'Rolex','description'=>'Born for the race track, perfected for everyday luxury. The Daytona features a tachymetric scale and three chronograph counters. One of the most coveted watches in the world.'],
    ['id'=>4,'name'=>'Rolex Yacht-Master','price'=>45000.00,'image'=>'rolex 4.jpeg','category'=>'Watches','brand'=>'Rolex','description'=>'Designed for seafaring adventurers, the Yacht-Master combines nautical elegance with Rolex\'s uncompromising standards. Features a bidirectional rotatable bezel and supple Oysterflex bracelet.'],
    ['id'=>5,'name'=>'Rolex Day-Date Rose Gold','price'=>12000.00,'image'=>'rolex 5.jpeg','category'=>'Watches','brand'=>'Rolex','description'=>'The watch of presidents. Crafted in 18ct Everose gold, the Day-Date displays both the day and date. An emblem of achievement worn by world leaders and visionaries.'],
    ['id'=>6,'name'=>'Hermès Birkin 30 Cacao','price'=>25000.00,'image'=>'birkin 1.jpeg','category'=>'Bags','brand'=>'Hermès','description'=>'The ultimate status symbol in rich Cacao Togo leather. Hand-stitched by a single artisan in France, the Birkin 30 is more than a bag — it is a lifelong investment.'],
    ['id'=>7,'name'=>'Hermès Birkin 30 Tin Alligator','price'=>48000.00,'image'=>'birkin 2.jpeg','category'=>'Bags','brand'=>'Hermès','description'=>'Exceptional rarity in Tin Porosus Alligator leather. The luminous sheen and natural pattern of each scale make every piece one-of-a-kind. Palladium hardware finishes this masterpiece.'],
    ['id'=>8,'name'=>'Hermès Birkin 30 Caramel','price'=>22000.00,'image'=>'birkin 3.jpeg','category'=>'Bags','brand'=>'Hermès','description'=>'A warm and versatile Caramel Clemence leather Birkin. Soft, supple, and scratch-resistant — ideal for the discerning collector seeking everyday elegance.'],
    ['id'=>9,'name'=>'Hermès Birkin 30 Emerald Alligator','price'=>40000.00,'image'=>'birkin 4.jpeg','category'=>'Bags','brand'=>'Hermès','description'=>'Deep Emerald Porosus Alligator with gold hardware. A jewel-toned statement piece that commands attention. Among the rarest colourways in the Birkin family.'],
    ['id'=>10,'name'=>'Hermès Birkin 30 Midnight Alligator','price'=>46000.00,'image'=>'birkin 5.jpeg','category'=>'Bags','brand'=>'Hermès','description'=>'Midnight Blue Porosus Alligator with palladium hardware. Dramatic, sophisticated, and impossibly rare. A collector\'s crown jewel in the deepest shade of night.'],
    ['id'=>11,'name'=>'Gucci Wine n Dance Heels','price'=>7800.00,'image'=>'gucci 1.jpeg','category'=>'Shoes','brand'=>'Gucci','description'=>'Bold and theatrical, these Gucci heels in rich burgundy leather are made for the woman who commands every room. Featuring the iconic double-G hardware and signature stiletto heel.'],
    ['id'=>12,'name'=>'Gucci Corporate Beige Flops','price'=>12500.00,'image'=>'gucci 2.jpeg','category'=>'Shoes','brand'=>'Gucci','description'=>'Effortless corporate luxury in nude beige. Crafted from supple calfskin with a padded footbed and the Gucci monogram discreetly embossed at the insole. Power dressing redefined.'],
    ['id'=>13,'name'=>'Gucci V Black Sandals','price'=>9500.00,'image'=>'gucci 3.jpeg','category'=>'Shoes','brand'=>'Gucci','description'=>'Sleek black leather sandals with a geometric V-strap silhouette and gold-toned Gucci buckle. The perfect complement to both evening wear and resort collections.'],
    ['id'=>14,'name'=>'Chanel Classic Plaited Dress','price'=>9500.00,'image'=>'chanel 1.jpeg','category'=>'Clothes','brand'=>'Chanel','description'=>'An exquisite piece from Chanel\'s atelier featuring the house\'s signature plaited braid trim. Crafted from double-faced wool in ivory, this dress embodies understated Parisian chic.'],
    ['id'=>15,'name'=>'Chanel Bosswoman Suit','price'=>16000.00,'image'=>'chanel 2.jpeg','category'=>'Clothes','brand'=>'Chanel','description'=>'Power and femininity in perfect balance. This iconic Chanel tweed suit features contrast trim, gold-toned buttons, and a structured silhouette. A wardrobe cornerstone for the modern woman.'],
    ['id'=>16,'name'=>'Chanel Plaited Knit Sweater','price'=>12500.00,'image'=>'chanel 3.jpeg','category'=>'Clothes','brand'=>'Chanel','description'=>'Luxurious cashmere-blend knit with Chanel\'s signature plaited detailing at the cuffs and hem. Effortlessly elegant and impeccably soft — the definition of quiet luxury.'],
    ['id'=>17,'name'=>'Van Cleef Butterflaura','price'=>850.00,'image'=>'vancleef 1.jpeg','category'=>'Jewellery','brand'=>'Van Cleef & Arpels','description'=>'A delicate butterfly and floral motif in 18ct yellow gold with sparkling pavé diamonds. Part of Van Cleef\'s celebrated nature-inspired collection, a symbol of grace and transformation.'],
    ['id'=>18,'name'=>'Van Cleef Entirely Golden','price'=>990.00,'image'=>'vancleef 2.jpeg','category'=>'Jewellery','brand'=>'Van Cleef & Arpels','description'=>'Pure 18ct yellow gold craftsmanship at its finest. This iconic Van Cleef piece radiates warmth and timeless elegance, shaped in the Maison\'s signature four-leaf clover motif.'],
    ['id'=>19,'name'=>'Van Cleef Open Ladybug','price'=>780.00,'image'=>'vancleef 3.jpeg','category'=>'Jewellery','brand'=>'Van Cleef & Arpels','description'=>'A whimsical open-set ladybug charm in yellow gold with onyx spots. Playful, lucky, and unmistakably Van Cleef — a beloved piece from the Lucky Animals collection.'],
    ['id'=>20,'name'=>'Van Cleef Wondering Ladybug','price'=>780.00,'image'=>'vancleef 4.jpeg','category'=>'Jewellery','brand'=>'Van Cleef & Arpels','description'=>'The iconic ladybug in motion — crafted in 18ct gold with lacquered wings and diamond accents. A talisman of good fortune worn by collectors worldwide.'],
    ['id'=>21,'name'=>'Van Cleef Filled With Love','price'=>950.00,'image'=>'vancleef 5.jpeg','category'=>'Jewellery','brand'=>'Van Cleef & Arpels','description'=>'A heart-shaped pendant set with brilliant-cut diamonds in white gold. Romantic and radiant, this piece captures Van Cleef\'s timeless dedication to love and beauty.'],
    ['id'=>22,'name'=>'YSL Classic Dark Shades','price'=>450.00,'image'=>'ysl 1.jpeg','category'=>'Accessories','brand'=>'Saint Laurent','description'=>'Sleek rectangular frames in matte black acetate with dark grey gradient lenses. The YSL monogram adorns each temple — understated luxury for the modern icon.'],
    ['id'=>23,'name'=>'YSL Cat Print Shades','price'=>650.00,'image'=>'ysl 2.jpeg','category'=>'Accessories','brand'=>'Saint Laurent','description'=>'A bold cat-eye silhouette with the Saint Laurent logo in gold-toned hardware. Crafted in Italian acetate — the ultimate accessory for the fashion-forward woman.'],
    ['id'=>24,'name'=>'YSL Snow Sunnies Midnight','price'=>950.00,'image'=>'ysl 3.jpeg','category'=>'Accessories','brand'=>'Saint Laurent','description'=>'Shield-style sunglasses in Midnight Black with mirrored lenses. Futuristic and fierce — a statement piece from Saint Laurent\'s avant-garde eyewear line.'],
    ['id'=>25,'name'=>'YSL Snow Sunnies Sunlight','price'=>950.00,'image'=>'ysl 4.jpeg','category'=>'Accessories','brand'=>'Saint Laurent','description'=>'The same iconic shield silhouette in gold-tinted lenses with a champagne frame. Luminous and commanding — made for those who prefer their luxury sun-kissed.'],
];

// ─── Find product by ID ───────────────────────────────────────────────────────
$id      = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;
foreach ($products as $p) {
    if ($p['id'] === $id) {
        $product = $p;
        break;
    }
}

if (!$product) {
    header('Location: products.php');
    exit;
}

// ─── Handle Add to Cart ───────────────────────────────────────────────────────
$cartMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $pid = (int)$_POST['product_id'];

    // If already in cart, increase quantity
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]['quantity']++;
    } else {
        $_SESSION['cart'][$pid] = [
            'id'       => $product['id'],
            'name'     => $product['name'],
            'price'    => $product['price'],
            'image'    => $product['image'],
            'brand'    => $product['brand'],
            'quantity' => 1,
        ];
    }
    $cartMessage = 'success';
}

// ─── Related products (same category, exclude current) ───────────────────────
$related = array_filter($products, fn($p) => $p['category'] === $product['category'] && $p['id'] !== $product['id']);
$related = array_slice($related, 0, 3);
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<main class="container my-5">

    <!-- Success Toast -->
    <?php if ($cartMessage === 'success'): ?>
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
        <strong><?= htmlspecialchars($product['name']) ?></strong> has been added to your cart.
        <a href="cart.php" class="alert-link ms-2">View Cart →</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="products.php" class="text-dark">Products</a></li>
            <li class="breadcrumb-item"><a href="products.php?category=<?= urlencode($product['category']) ?>" class="text-dark"><?= htmlspecialchars($product['category']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </nav>

    <!-- Product Detail -->
    <div class="row g-5 align-items-start">

        <!-- Image -->
        <div class="col-md-6">
            <div class="detail-img-wrapper">
                <img
                    src="images/<?= htmlspecialchars($product['image']) ?>"
                    alt="<?= htmlspecialchars($product['name']) ?>"
                    class="detail-img"
                >
            </div>
        </div>

        <!-- Info -->
        <div class="col-md-6">
            <p class="detail-brand"><?= htmlspecialchars($product['brand']) ?></p>
            <h1 class="detail-name"><?= htmlspecialchars($product['name']) ?></h1>
            <p class="detail-category text-muted mb-3"><?= htmlspecialchars($product['category']) ?></p>
            <p class="detail-price">$<?= number_format($product['price'], 2) ?></p>
            <hr>
            <p class="detail-description"><?= htmlspecialchars($product['description']) ?></p>
            <hr>

            <!-- Add to Cart Form -->
            <form method="POST" action="">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <button type="submit" name="add_to_cart" class="btn btn-dark btn-lg w-100 add-cart-btn">
                    Add to Cart
                </button>
            </form>

            <a href="products.php?category=<?= urlencode($product['category']) ?>" class="btn btn-outline-dark mt-3 w-100">
                ← Back to <?= htmlspecialchars($product['category']) ?>
            </a>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($related)): ?>
    <div class="mt-5 pt-4 border-top">
        <h4 class="mb-4 related-title">You May Also Like</h4>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($related as $r): ?>
            <div class="col">
                <a href="product_detail.php?id=<?= $r['id'] ?>" class="text-decoration-none text-dark">
                    <div class="card h-100 product-card border-0 shadow-sm">
                        <div class="related-img-wrapper">
                            <img src="images/<?= htmlspecialchars($r['image']) ?>"
                                 alt="<?= htmlspecialchars($r['name']) ?>"
                                 class="card-img-top related-img">
                        </div>
                        <div class="card-body">
                            <p class="product-brand mb-1"><?= htmlspecialchars($r['brand']) ?></p>
                            <h6 class="card-title"><?= htmlspecialchars($r['name']) ?></h6>
                            <p class="product-price mb-0">$<?= number_format($r['price'], 2) ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</main>

<style>
    .detail-img-wrapper {
        width: 100%;
        height: 520px;
        background: #f5f5f5;
        border-radius: 8px;
        overflow: hidden;
    }
    .detail-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .detail-brand {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #888;
        margin-bottom: 4px;
    }
    .detail-name {
        font-family: 'Georgia', serif;
        font-size: 1.6rem;
        font-weight: 400;
        line-height: 1.3;
        color: #1a1a1a;
    }
    .detail-price {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a1a1a;
    }
    .detail-description {
        font-size: 0.95rem;
        line-height: 1.8;
        color: #444;
    }
    .add-cart-btn {
        letter-spacing: 0.08em;
        padding: 14px;
        font-size: 0.95rem;
    }
    .add-cart-btn:hover {
        background: #333;
    }
    .related-title {
        font-family: 'Georgia', serif;
        font-weight: 400;
    }
    .related-img-wrapper {
        height: 220px;
        overflow: hidden;
        background: #f5f5f5;
    }
    .related-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .product-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 8px;
        overflow: hidden;
    }
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1) !important;
    }
    .product-card:hover .related-img {
        transform: scale(1.05);
    }
    .product-brand {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #888;
    }
    .product-price {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a1a;
    }
</style>

<?php include 'includes/footer.php'; ?>