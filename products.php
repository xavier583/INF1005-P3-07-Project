<?php
session_start();

// ─── Product Data ─────────────────────────────────────────────────────────────
$products = [
    ['id'=>1,'name'=>'Rolex Submariner Ceramic Bezel','price'=>20500.00,'image'=>'rolex 1.jpeg','category'=>'Watches','brand'=>'Rolex','description'=>'A legendary diver\'s watch with a unidirectional rotatable bezel and Cerachrom ceramic insert. Water-resistant to 300 metres, powered by the calibre 3235 movement. A timeless icon of precision and luxury.'],
    ['id'=>2,'name'=>'Rolex Datejust Stainless Steel','price'=>22000.00,'image'=>'rolex 2.jpeg','category'=>'Watches','brand'=>'Rolex','description'=>'The quintessential dress watch. Features a classic Jubilee bracelet, Cyclops lens over the date, and a scratch-resistant sapphire crystal. A symbol of refined taste.'],
    ['id'=>3,'name'=>'Rolex Cosmograph Daytona','price'=>35100.00,'image'=>'rolex 3.jpeg','category'=>'Watches','brand'=>'Rolex','description'=>'Born for the race track, perfected for everyday luxury. The Daytona features a tachymetric scale and three chronograph counters. One of the most coveted watches in the world.'],
    ['id'=>4,'name'=>'Rolex Yacht-Master','price'=>45000.00,'image'=>'rolex 4.jpeg','category'=>'Watches','brand'=>'Rolex','description'=>'Designed for seafaring adventurers, the Yacht-Master combines nautical elegance with Rolex\'s uncompromising standards.'],
    ['id'=>5,'name'=>'Rolex Day-Date Rose Gold','price'=>12000.00,'image'=>'rolex 5.jpeg','category'=>'Watches','brand'=>'Rolex','description'=>'The watch of presidents. Crafted in 18ct Everose gold, the Day-Date displays both the day and date.'],
    ['id'=>6,'name'=>'Hermès Birkin 30 Cacao','price'=>25000.00,'image'=>'birkin 1.jpeg','category'=>'Bags','brand'=>'Hermès','description'=>'The ultimate status symbol in rich Cacao Togo leather. Hand-stitched by a single artisan in France.'],
    ['id'=>7,'name'=>'Hermès Birkin 30 Tin Alligator','price'=>48000.00,'image'=>'birkin 2.jpeg','category'=>'Bags','brand'=>'Hermès','description'=>'Exceptional rarity in Tin Porosus Alligator leather. The luminous sheen and natural pattern of each scale make every piece one-of-a-kind.'],
    ['id'=>8,'name'=>'Hermès Birkin 30 Caramel','price'=>22000.00,'image'=>'birkin 3.jpeg','category'=>'Bags','brand'=>'Hermès','description'=>'A warm and versatile Caramel Clemence leather Birkin. Soft, supple, and scratch-resistant.'],
    ['id'=>9,'name'=>'Hermès Birkin 30 Emerald Alligator','price'=>40000.00,'image'=>'birkin 4.jpeg','category'=>'Bags','brand'=>'Hermès','description'=>'Deep Emerald Porosus Alligator with gold hardware. A jewel-toned statement piece that commands attention.'],
    ['id'=>10,'name'=>'Hermès Birkin 30 Midnight Alligator','price'=>46000.00,'image'=>'birkin 5.jpeg','category'=>'Bags','brand'=>'Hermès','description'=>'Midnight Blue Porosus Alligator with palladium hardware. Dramatic, sophisticated, and impossibly rare.'],
    ['id'=>11,'name'=>'Gucci Wine n Dance Heels','price'=>7800.00,'image'=>'gucci 1.jpeg','category'=>'Shoes','brand'=>'Gucci','description'=>'Bold and theatrical, these Gucci heels in rich burgundy leather are made for the woman who commands every room.'],
    ['id'=>12,'name'=>'Gucci Corporate Beige Flops','price'=>12500.00,'image'=>'gucci 2.jpeg','category'=>'Shoes','brand'=>'Gucci','description'=>'Effortless corporate luxury in nude beige. Crafted from supple calfskin with a padded footbed.'],
    ['id'=>13,'name'=>'Gucci V Black Sandals','price'=>9500.00,'image'=>'gucci 3.jpeg','category'=>'Shoes','brand'=>'Gucci','description'=>'Sleek black leather sandals with a geometric V-strap silhouette and gold-toned Gucci buckle.'],
    ['id'=>14,'name'=>'Chanel Classic Plaited Dress','price'=>9500.00,'image'=>'chanel 1.jpeg','category'=>'Clothes','brand'=>'Chanel','description'=>'An exquisite piece from Chanel\'s atelier featuring the house\'s signature plaited braid trim.'],
    ['id'=>15,'name'=>'Chanel Bosswoman Suit','price'=>16000.00,'image'=>'chanel 2.jpeg','category'=>'Clothes','brand'=>'Chanel','description'=>'Power and femininity in perfect balance. This iconic Chanel tweed suit features contrast trim and gold-toned buttons.'],
    ['id'=>16,'name'=>'Chanel Plaited Knit Sweater','price'=>12500.00,'image'=>'chanel 3.jpeg','category'=>'Clothes','brand'=>'Chanel','description'=>'Luxurious cashmere-blend knit with Chanel\'s signature plaited detailing at the cuffs and hem.'],
    ['id'=>17,'name'=>'Van Cleef Butterflaura','price'=>850.00,'image'=>'vancleef 1.jpeg','category'=>'Jewellery','brand'=>'Van Cleef & Arpels','description'=>'A delicate butterfly and floral motif in 18ct yellow gold with sparkling pavé diamonds.'],
    ['id'=>18,'name'=>'Van Cleef Entirely Golden','price'=>990.00,'image'=>'vancleef 2.jpeg','category'=>'Jewellery','brand'=>'Van Cleef & Arpels','description'=>'Pure 18ct yellow gold craftsmanship at its finest. Shaped in the Maison\'s signature four-leaf clover motif.'],
    ['id'=>19,'name'=>'Van Cleef Open Ladybug','price'=>780.00,'image'=>'vancleef 3.jpeg','category'=>'Jewellery','brand'=>'Van Cleef & Arpels','description'=>'A whimsical open-set ladybug charm in yellow gold with onyx spots from the Lucky Animals collection.'],
    ['id'=>20,'name'=>'Van Cleef Wondering Ladybug','price'=>780.00,'image'=>'vancleef 4.jpeg','category'=>'Jewellery','brand'=>'Van Cleef & Arpels','description'=>'The iconic ladybug in motion — crafted in 18ct gold with lacquered wings and diamond accents.'],
    ['id'=>21,'name'=>'Van Cleef Filled With Love','price'=>950.00,'image'=>'vancleef 5.jpeg','category'=>'Jewellery','brand'=>'Van Cleef & Arpels','description'=>'A heart-shaped pendant set with brilliant-cut diamonds in white gold. Romantic and radiant.'],
    ['id'=>22,'name'=>'YSL Classic Dark Shades','price'=>450.00,'image'=>'ysl 1.jpeg','category'=>'Accessories','brand'=>'Saint Laurent','description'=>'Sleek rectangular frames in matte black acetate with dark grey gradient lenses.'],
    ['id'=>23,'name'=>'YSL Cat Print Shades','price'=>650.00,'image'=>'ysl 2.jpeg','category'=>'Accessories','brand'=>'Saint Laurent','description'=>'A bold cat-eye silhouette with the Saint Laurent logo in gold-toned hardware.'],
    ['id'=>24,'name'=>'YSL Snow Sunnies Midnight','price'=>950.00,'image'=>'ysl 3.jpeg','category'=>'Accessories','brand'=>'Saint Laurent','description'=>'Shield-style sunglasses in Midnight Black with mirrored lenses. Futuristic and fierce.'],
    ['id'=>25,'name'=>'YSL Snow Sunnies Sunlight','price'=>950.00,'image'=>'ysl 4.jpeg','category'=>'Accessories','brand'=>'Saint Laurent','description'=>'The iconic shield silhouette in gold-tinted lenses with a champagne frame.'],
];

$categories = [
    'Watches'     => 'product page/watch_product_page.jpg',
    'Jewellery'   => 'product page/jewellery_product_page.jpg',
    'Shoes'       => 'product page/shoes_product_page.jpg',
    'Clothes'     => 'product page/clothes_product_page.jpg',
    'Bags'        => 'product page/bags_product_page.jpg',
    'Accessories' => 'product page/accessories_product_page.jpg',
];

// ─── Determine view mode ──────────────────────────────────────────────────────
$activeCategory = isset($_GET['category']) ? $_GET['category'] : null;
$showGrid       = $activeCategory && array_key_exists($activeCategory, $categories);

$filtered = $showGrid
    ? array_filter($products, fn($p) => $p['category'] === $activeCategory)
    : [];
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<main class="container my-5">

<?php if (!$showGrid): ?>
<!-- ═══════════════════════════════════════════════════════════
     VIEW 1: Category Landing (image cards — original style)
══════════════════════════════════════════════════════════════ -->
    <div class="text-center mb-5">
        <h1 class="products-title">Our Products</h1>
        <p class="text-muted" style="font-size:1.1em;">Browse our collection of luxury secondhand goods.</p>
        <h2 class="categories-subtitle mt-4">Categories</h2>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center mb-5">
        <?php foreach ($categories as $catName => $catImage): ?>
        <div class="col">
            <a href="products.php?category=<?= urlencode($catName) ?>" class="text-decoration-none">
                <div class="category-card">
                    <img
                        src="images/<?= htmlspecialchars($catImage) ?>"
                        alt="<?= htmlspecialchars($catName) ?>"
                        class="category-img"
                    >
                    <div class="category-overlay">
                        <span class="category-label"><?= htmlspecialchars($catName) ?></span>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>

<?php else: ?>
<!-- ═══════════════════════════════════════════════════════════
     VIEW 2: Product Grid for selected category
══════════════════════════════════════════════════════════════ -->

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="products.php" class="text-dark">All Categories</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <?= htmlspecialchars($activeCategory) ?>
            </li>
        </ol>
    </nav>

    <!-- Category Header with banner image -->
    <div class="category-banner mb-5" style="background-image: url('images/<?= htmlspecialchars($categories[$activeCategory]) ?>');">
        <div class="category-banner-overlay">
            <h1 class="category-banner-title"><?= htmlspecialchars($activeCategory) ?></h1>
            <p class="category-banner-sub"><?= count($filtered) ?> items available</p>
        </div>
    </div>

    <!-- Jump to other categories -->
    <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
        <a href="products.php" class="btn btn-dark btn-sm">← All Categories</a>
        <?php foreach ($categories as $catName => $catImage): ?>
            <?php if ($catName !== $activeCategory): ?>
            <a href="products.php?category=<?= urlencode($catName) ?>"
               class="btn btn-outline-dark btn-sm">
                <?= htmlspecialchars($catName) ?>
            </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Product Grid -->
    <?php if (empty($filtered)): ?>
        <p class="text-center text-muted">No products found in this category.</p>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($filtered as $product): ?>
            <div class="col">
                <a href="product_detail.php?id=<?= $product['id'] ?>" class="text-decoration-none text-dark">
                    <div class="card h-100 product-card border-0 shadow-sm">
                        <div class="product-img-wrapper">
                            <img
                                src="images/<?= htmlspecialchars($product['image']) ?>"
                                alt="<?= htmlspecialchars($product['name']) ?>"
                                class="card-img-top product-img"
                            >
                        </div>
                        <div class="card-body d-flex flex-column">
                            <p class="product-brand mb-1"><?= htmlspecialchars($product['brand']) ?></p>
                            <h6 class="card-title product-name"><?= htmlspecialchars($product['name']) ?></h6>
                            <p class="product-price mt-auto mb-0">$<?= number_format($product['price'], 2) ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php endif; ?>
</main>

<style>
    /* ── Shared titles ── */
    .products-title {
        font-family: 'Georgia', serif;
        font-size: 2rem;
        font-weight: 400;
        letter-spacing: 0.04em;
    }
    .categories-subtitle {
        font-family: 'Georgia', serif;
        font-size: 1.4rem;
        font-weight: 400;
        color: #444;
    }

    /* ── Category Cards (View 1) ── */
    .category-card {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        height: 200px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 28px rgba(0,0,0,0.18);
    }
    .category-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s ease;
        display: block;
    }
    .category-card:hover .category-img {
        transform: scale(1.07);
    }
    .category-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.38);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.25s ease;
    }
    .category-card:hover .category-overlay {
        background: rgba(0,0,0,0.50);
    }
    .category-label {
        color: #fff;
        font-size: 1.3rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        text-shadow: 0 1px 4px rgba(0,0,0,0.5);
    }

    /* ── Category Banner (View 2 header) ── */
    .category-banner {
        width: 100%;
        height: 200px;
        border-radius: 10px;
        background-size: cover;
        background-position: center;
        position: relative;
        overflow: hidden;
    }
    .category-banner-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.45);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .category-banner-title {
        font-family: 'Georgia', serif;
        font-size: 2.2rem;
        font-weight: 400;
        color: #fff;
        letter-spacing: 0.08em;
        text-shadow: 0 2px 6px rgba(0,0,0,0.4);
        margin: 0;
    }
    .category-banner-sub {
        color: rgba(255,255,255,0.8);
        font-size: 0.9rem;
        margin-top: 6px;
        margin-bottom: 0;
        letter-spacing: 0.05em;
    }

    /* ── Product Cards (View 2) ── */
    .product-card {
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
    }
    .product-img-wrapper {
        width: 100%;
        height: 250px;
        overflow: hidden;
        background: #f5f5f5;
    }
    .product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .product-card:hover .product-img {
        transform: scale(1.05);
    }
    .product-brand {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #888;
    }
    .product-name {
        font-size: 0.9rem;
        font-weight: 500;
        color: #2c2c2c;
        line-height: 1.4;
    }
    .product-price {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a1a;
    }
</style>

<?php include 'includes/footer.php'; ?>