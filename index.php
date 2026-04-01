<?php
$rootPath = ".";
include 'includes/header.php';
?>
<?php include 'includes/nav.php'; ?>

<main id="main-content">

<!-- Hero Video -->
<section class = "hero">
    <video autoplay muted loop id="hero-video">
        <source src="images/Maison Reluxe Homepage.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="hero-overlay">
        <img src = "images/brand_logo.png" alt="Maison Reluxe Logo" class="hero-logo">
        <p> Curated Luxury, Timeless Style. </p>
        <a href = "products.php" class="mainpage-btn">Explore Collection</a>     
    </div>
    <button id = "video-control" class="video-control" aria-label="Pause Video">❚❚</button>
</section>

<!-- Slideshow -->
<section class="home-slideshow">
    <div class="home-slide">
        <img src = "images/jewellery/bvlgari_bracelet.png" alt="Bvlgari Bracelet">
        <div class="home-slide-banner">
            <p class="home-slide-copy">Discover Our Many Products</p>
            <a href="product_detail.php?id=18" class="home-slide-action">View This Item</a>
        </div>
    </div>
    <div class="home-slide">
        <img src = "images/bags/chanel_bag.png" alt="Chanel Bag">
        <div class="home-slide-banner">
            <p class="home-slide-copy">Discover Our Many Products</p>
            <a href="product_detail.php?id=27" class="home-slide-action">View This Item</a>
        </div>
    </div>
    <div class="home-slide">
        <img src = "images/shoes/gucci 1.jpeg" alt="Gucci Shoes">
        <div class="home-slide-banner">
            <p class="home-slide-copy">Discover Our Many Products</p>
            <a href="product_detail.php?id=11" class="home-slide-action">View This Item</a>
        </div>
    </div>
    <div class="home-slide">
        <img src = "images/watches/rolex 1.jpeg" alt="Rolex Watch">
        <div class="home-slide-banner">
            <p class="home-slide-copy">Discover Our Many Products</p>
            <a href="product_detail.php?id=1" class="home-slide-action">View This Item</a>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="featured">
    <h2>Featured Pieces</h2>
    <div class="container featured-cards">
        <div class="row g-4 justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100">
                    <a href="product_detail.php?id=6">
                        <img src="images/bags/birkin 1.jpeg" class="card-img-top" alt="Hermès Bag">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">Hermès Birkin 30 Cacao</h5>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100">
                    <a href="product_detail.php?id=4">
                        <img src="images/watches/rolex 4.jpeg" class="card-img-top" alt="Rolex Watch">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">Rolex Yacht-Master</h5>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100">
                    <a href="product_detail.php?id=17">
                        <img src="images/jewellery/cartier_bracelet.jpg" class="card-img-top" alt="Cartier Bracelet">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">Cartier Love 18k Yellow Gold Bracelet</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</main>


<script src="<?php echo $rootPath; ?>/js/slideshow.js"></script>
<script src="<?php echo $rootPath; ?>/js/videoControl.js"></script>

<?php include 'includes/footer.php'; ?>

