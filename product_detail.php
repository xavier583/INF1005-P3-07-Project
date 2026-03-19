<?php
session_start();

// ─── Product Data (must match products.php) ───────────────────────────────────
$products = [
    ['id'=>1,'name'=>'Rolex Submariner Ceramic Bezel','price'=>20500.00,'image'=>'watches/rolex 1.jpeg','category'=>'Watches','brand'=>'Rolex','description'=>'A legendary diver\'s watch with a unidirectional rotatable bezel and Cerachrom ceramic insert. Water-resistant to 300 metres, powered by the calibre 3235 movement. A timeless icon of precision and luxury.'],
    ['id'=>2,'name'=>'Rolex Datejust Stainless Steel','price'=>22000.00,'image'=>'watches/rolex 2.jpeg','category'=>'Watches','brand'=>'Rolex','description'=>'The quintessential dress watch. Features a classic Jubilee bracelet, Cyclops lens over the date, and a scratch-resistant sapphire crystal. A symbol of refined taste.'],
    ['id'=>3,'name'=>'Rolex Cosmograph Daytona','price'=>35100.00,'image'=>'watches/rolex 3.jpeg','category'=>'Watches','brand'=>'Rolex','description'=>'Born for the race track, perfected for everyday luxury. The Daytona features a tachymetric scale and three chronograph counters. One of the most coveted watches in the world.'],
    ['id'=>4,'name'=>'Rolex Yacht-Master','price'=>45000.00,'image'=>'watches/rolex 4.jpeg','category'=>'Watches','brand'=>'Rolex','description'=>'Designed for seafaring adventurers, the Yacht-Master combines nautical elegance with Rolex\'s uncompromising standards.'],
    ['id'=>5,'name'=>'Rolex Day-Date Rose Gold','price'=>12000.00,'image'=>'watches/rolex 5.jpeg','category'=>'Watches','brand'=>'Rolex','description'=>'The watch of presidents. Crafted in 18ct Everose gold, the Day-Date displays both the day and date.'],
    ['id'=>6,'name'=>'Hermès Birkin 30 Cacao','price'=>25000.00,'image'=>'bags/birkin 1.jpeg','category'=>'Bags','brand'=>'Hermès','description'=>'The ultimate status symbol in rich Cacao Togo leather. Hand-stitched by a single artisan in France.'],
    ['id'=>7,'name'=>'Hermès Birkin 30 Tin Alligator','price'=>48000.00,'image'=>'bags/birkin 2.jpeg','category'=>'Bags','brand'=>'Hermès','description'=>'Exceptional rarity in Tin Porosus Alligator leather. The luminous sheen and natural pattern of each scale make every piece one-of-a-kind.'],
    ['id'=>8,'name'=>'Hermès Birkin 30 Caramel','price'=>22000.00,'image'=>'bags/birkin 3.jpeg','category'=>'Bags','brand'=>'Hermès','description'=>'A warm and versatile Caramel Clemence leather Birkin. Soft, supple, and scratch-resistant.'],
    ['id'=>9,'name'=>'Hermès Birkin 30 Emerald Alligator','price'=>40000.00,'image'=>'bags/birkin 4.jpeg','category'=>'Bags','brand'=>'Hermès','description'=>'Deep Emerald Porosus Alligator with gold hardware. A jewel-toned statement piece that commands attention.'],
    ['id'=>10,'name'=>'Hermès Birkin 30 Midnight Alligator','price'=>46000.00,'image'=>'bags/birkin 5.jpeg','category'=>'Bags','brand'=>'Hermès','description'=>'Midnight Blue Porosus Alligator with palladium hardware. Dramatic, sophisticated, and impossibly rare.'],
    ['id'=>27,'name'=>'Chanel Mini Classic Handbag','price'=>5888.00,'image'=>'bags/chanel_bag.png','category'=>'Bags','brand'=>'Chanel','description'=>'The iconic Chanel Classic Flap in mini size, crafted from lambskin leather with gold-toned hardware. A timeless investment piece that transcends every season.'],
    ['id'=>28,'name'=>'Loewe Puzzle Bag Sage Green','price'=>4200.00,'image'=>'bags/loewe_bag.png','category'=>'Bags','brand'=>'Loewe','description'=>'The architectural Puzzle bag in soft sage green calfskin. Loewe\'s most iconic silhouette — geometric, functional, and unmistakably modern.'],
    ['id'=>29,'name'=>'Louis Vuitton Speedy Bandoulière 20','price'=>2500.00,'image'=>'bags/lv_bag.png','category'=>'Bags','brand'=>'Louis Vuitton','description'=>'The compact Speedy Bandoulière in the signature Monogram canvas with a detachable shoulder strap. A wardrobe staple reimagined for the contemporary woman.'],
    ['id'=>30,'name'=>'Miu Miu Wander Hobo Bag','price'=>3800.00,'image'=>'bags/miumiu_bag.png','category'=>'Bags','brand'=>'Miu Miu','description'=>'The Wander hobo in soft matelassé nappa leather with Miu Miu\'s signature ruching. Effortlessly chic and unmistakably feminine.'],
    ['id'=>31,'name'=>'Saint Laurent Le 5 À 7 Hobo','price'=>3200.00,'image'=>'bags/ysl_bag.png','category'=>'Bags','brand'=>'Saint Laurent','description'=>'The Le 5 À 7 in smooth black leather with a sleek crescent silhouette. A modern Saint Laurent icon that moves effortlessly from day to evening.'],
    ['id'=>11,'name'=>'Gucci Wine n Dance Heels','price'=>7800.00,'image'=>'shoes/gucci 1.jpeg','category'=>'Shoes','brand'=>'Gucci','description'=>'Bold and theatrical, these Gucci heels in rich burgundy leather are made for the woman who commands every room.'],
    ['id'=>12,'name'=>'Gucci Corporate Beige Flops','price'=>12500.00,'image'=>'shoes/gucci 2.jpeg','category'=>'Shoes','brand'=>'Gucci','description'=>'Effortless corporate luxury in nude beige. Crafted from supple calfskin with a padded footbed.'],
    ['id'=>13,'name'=>'Gucci V Black Sandals','price'=>9500.00,'image'=>'shoes/gucci 3.jpeg','category'=>'Shoes','brand'=>'Gucci','description'=>'Sleek black leather sandals with a geometric V-strap silhouette and gold-toned Gucci buckle.'],
    ['id'=>14,'name'=>'Chanel Classic Plaited Dress','price'=>9500.00,'image'=>'clothes/chanel 1.jpeg','category'=>'Clothes','brand'=>'Chanel','description'=>'An exquisite piece from Chanel\'s atelier featuring the house\'s signature plaited braid trim.'],
    ['id'=>15,'name'=>'Chanel Bosswoman Suit','price'=>16000.00,'image'=>'clothes/chanel 2.jpeg','category'=>'Clothes','brand'=>'Chanel','description'=>'Power and femininity in perfect balance. This iconic Chanel tweed suit features contrast trim and gold-toned buttons.'],
    ['id'=>16,'name'=>'Chanel Plaited Knit Sweater','price'=>12500.00,'image'=>'clothes/chanel 3.jpeg','category'=>'Clothes','brand'=>'Chanel','description'=>'Luxurious cashmere-blend knit with Chanel\'s signature plaited detailing at the cuffs and hem.'],
    ['id'=>17,'name'=>'Cartier Love 18k Yellow Gold Bracelet','price'=>14870.00,'image'=>'jewellery/cartier_bracelet.jpg','category'=>'Jewellery','brand'=>'Cartier','description'=>'The iconic Cartier Love bracelet in 18k yellow gold. A symbol of eternal devotion, secured with a screwdriver — a timeless declaration of love worn by icons worldwide.'],
    ['id'=>18,'name'=>'Bvlgari Serpenti Viper Bracelet 18k Rose Gold','price'=>50888.00,'image'=>'jewellery/bvlgari_bracelet.png','category'=>'Jewellery','brand'=>'Bvlgari','description'=>'The seductive Serpenti Viper bracelet in 18k rose gold with pavé diamonds. Inspired by the sinuous form of a snake, it wraps around the wrist in a bold declaration of luxury.'],
    ['id'=>19,'name'=>'Tiffany & Co. Return to Tiffany Silver Necklace','price'=>1550.00,'image'=>'jewellery/tiffany_necklace.jpg','category'=>'Jewellery','brand'=>'Tiffany & Co.','description'=>'The iconic Return to Tiffany heart tag pendant in sterling silver. A beloved symbol of connection and the unmistakable Tiffany legacy.'],
    ['id'=>20,'name'=>'Dior Pearl Drop Earrings','price'=>2800.00,'image'=>'jewellery/dior_earrings.png','category'=>'Jewellery','brand'=>'Dior','description'=>'Delicate pearl drop earrings bearing the signature CD logo. Refined and feminine, embodying the timeless elegance of the House of Dior.'],
    ['id'=>21,'name'=>'Chanel CC Pearl Ring','price'=>3200.00,'image'=>'jewellery/chanel_ring.png','category'=>'Jewellery','brand'=>'Chanel','description'=>'The iconic double-C motif reimagined as a lustrous pearl ring. A wearable piece of Chanel heritage bridging classic couture with contemporary jewellery design.'],
    ['id'=>22,'name'=>'Hermès Amulette Necklace','price'=>4500.00,'image'=>'jewellery/hermes_necklace.png','category'=>'Jewellery','brand'=>'Hermès','description'=>'A minimalist rose gold pendant bearing the discreet Hermès H signature. Understated luxury at its finest — the perfect everyday talisman.'],
    ['id'=>23,'name'=>'YSL Classic Dark Shades','price'=>450.00,'image'=>'accessories/ysl 1.jpeg','category'=>'Accessories','brand'=>'Saint Laurent','description'=>'Sleek rectangular frames in matte black acetate with dark grey gradient lenses. The YSL monogram adorns each temple.'],
    ['id'=>24,'name'=>'YSL Cat Print Shades','price'=>650.00,'image'=>'accessories/ysl 2.jpeg','category'=>'Accessories','brand'=>'Saint Laurent','description'=>'A bold cat-eye silhouette with the Saint Laurent logo in gold-toned hardware. Crafted in Italian acetate.'],
    ['id'=>25,'name'=>'YSL Snow Sunnies Midnight','price'=>950.00,'image'=>'accessories/ysl 3.jpeg','category'=>'Accessories','brand'=>'Saint Laurent','description'=>'Shield-style sunglasses in Midnight Black with mirrored lenses. Futuristic and fierce.'],
    ['id'=>26,'name'=>'YSL Snow Sunnies Sunlight','price'=>950.00,'image'=>'accessories/ysl 4.jpeg','category'=>'Accessories','brand'=>'Saint Laurent','description'=>'The iconic shield silhouette in gold-tinted lenses with a champagne frame. Luminous and commanding.'],
];

// ─── Find product by ID ───────────────────────────────────────────────────────
$id      = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;
foreach ($products as $p) {
    if ($p['id'] === $id) { $product = $p; break; }
}
if (!$product) { header('Location: products.php'); exit; }

function buildProductGalleryImages($imagePath)
{
    $gallery = [];

    if (!is_string($imagePath) || $imagePath === '') {
        return $gallery;
    }

    $gallery[] = str_replace('\\', '/', $imagePath);

    $baseDir  = dirname($imagePath);
    $baseName = pathinfo($imagePath, PATHINFO_FILENAME);
    $imagesRoot = __DIR__ . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;

    $searchPattern = $imagesRoot . str_replace('/', DIRECTORY_SEPARATOR, $baseDir)
        . DIRECTORY_SEPARATOR . $baseName . '*';

    $matches = glob($searchPattern) ?: [];

    foreach ($matches as $matchedFile) {
        if (!is_file($matchedFile)) {
            continue;
        }

        $relative = substr($matchedFile, strlen($imagesRoot));
        $relative = str_replace('\\', '/', $relative);

        if (!preg_match('/\.(jpe?g|png|webp|gif)$/i', $relative)) {
            continue;
        }

        if (!in_array($relative, $gallery, true)) {
            $gallery[] = $relative;
        }
    }

    return $gallery;
}

$galleryImages = buildProductGalleryImages($product['image']);

// ─── Handle Add to Cart ───────────────────────────────────────────────────────
$cartMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $pid = (int)$_POST['product_id'];
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

// ─── Related products ─────────────────────────────────────────────────────────
$related = array_filter($products, fn($p) => $p['category'] === $product['category'] && $p['id'] !== $product['id']);
$related = array_slice($related, 0, 3);
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<main class="container my-5">

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
        <div class="col-md-6">
            <div id="productGalleryCarousel" class="carousel slide detail-carousel" data-bs-ride="false">
                <div class="carousel-inner detail-img-wrapper">
                    <?php foreach ($galleryImages as $index => $galleryImage): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <img src="images/<?= htmlspecialchars($galleryImage) ?>"
                             alt="<?= htmlspecialchars($product['name']) ?> image <?= $index + 1 ?>"
                             class="detail-img d-block w-100">
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($galleryImages) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#productGalleryCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productGalleryCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>

                <div class="carousel-indicators detail-carousel-indicators">
                    <?php foreach ($galleryImages as $index => $galleryImage): ?>
                    <button type="button"
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
        <div class="col-md-6">
            <p class="detail-brand"><?= htmlspecialchars($product['brand']) ?></p>
            <h1 class="detail-name"><?= htmlspecialchars($product['name']) ?></h1>
            <p class="detail-category text-muted mb-3"><?= htmlspecialchars($product['category']) ?></p>
            <p class="detail-price">$<?= number_format($product['price'], 2) ?></p>
            <hr>
            <p class="detail-description"><?= htmlspecialchars($product['description']) ?></p>
            <hr>
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
    .detail-carousel { border-radius:8px; overflow:hidden; background:#f5f5f5; }
    .detail-img-wrapper { width:100%; height:520px; }
    .detail-img { width:100%; height:520px; object-fit:cover; }
    .detail-carousel .carousel-control-prev,
    .detail-carousel .carousel-control-next { width:12%; }
    .detail-carousel .carousel-control-prev-icon,
    .detail-carousel .carousel-control-next-icon {
        background-color: rgba(0,0,0,0.45);
        border-radius: 50%;
        background-size: 55%;
        width: 2.4rem;
        height: 2.4rem;
    }
    .detail-carousel-indicators { margin-bottom: 0.7rem; }
    .detail-carousel-indicators [data-bs-target] {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        border: 0;
        opacity: 0.65;
    }
    .detail-brand { font-size:0.8rem; text-transform:uppercase; letter-spacing:0.15em; color:#888; margin-bottom:4px; }
    .detail-name { font-family:'Georgia',serif; font-size:1.6rem; font-weight:400; line-height:1.3; color:#1a1a1a; }
    .detail-price { font-size:1.5rem; font-weight:600; color:#1a1a1a; }
    .detail-description { font-size:0.95rem; line-height:1.8; color:#444; }
    .add-cart-btn { letter-spacing:0.08em; padding:14px; font-size:0.95rem; }
    .related-title { font-family:'Georgia',serif; font-weight:400; }
    .related-img-wrapper { height:220px; overflow:hidden; background:#f5f5f5; }
    .related-img { width:100%; height:100%; object-fit:cover; transition:transform 0.3s ease; }
    .product-card { transition:transform 0.2s ease, box-shadow 0.2s ease; border-radius:8px; overflow:hidden; }
    .product-card:hover { transform:translateY(-4px); box-shadow:0 8px 24px rgba(0,0,0,0.1) !important; }
    .product-card:hover .related-img { transform:scale(1.05); }
    .product-brand { font-size:0.75rem; text-transform:uppercase; letter-spacing:0.1em; color:#888; }
    .product-price { font-size:1rem; font-weight:600; color:#1a1a1a; }

    @media (max-width: 768px) {
        .detail-img-wrapper,
        .detail-img {
            height: 360px;
        }
    }
</style>

<?php include 'includes/footer.php'; ?>