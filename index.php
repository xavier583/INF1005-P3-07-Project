<?php
$rootPath = ".";
include 'includes/header.php';
?>
<?php include 'includes/nav.php'; ?>

<!-- Hero Video -->
<section class = "hero">
    <video autoplay muted loop id="hero-video">
        <source src="images/Maison Reluxe Homepage.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="hero-overlay">
        <h1 class="Maison Reluxe">Maison Reluxe</h1>
        <p> Curated Luxury, Timeless Style. </p>
        <a href = "products.php" class="btn btn-primary">Explore Collection</a>
    </div>
</section>

<!-- Slideshow -->
<section class="slideshow">
    <div class="slide fade">
        <img src = "images/jewellery/bvlgari_bracelet.png" alt="Bvlgari Bracelet">
    </div>
    <div class="slide fade">
        <img src = "images/bags/chanel_bag.png" alt="Chanel Bag">
    </div>
    <div class="slide fade">
        <img src = "images/shoes/gucci 1.jpeg" alt="Gucci Shoes">
    </div>
    <div class="slide fade">
        <img src = "images/watches/rolex 1.jpeg" alt="Rolex Watch">
    </div>
</section>

<!-- Featured Products -->
<section class="featured">
    <h2>Featured Pieces</h2>
    <div class="product-grid">
        <div class="product">
            <img src="images/bags/birkin 1.jpeg" alt="Hermès Bag">
            <h3>Hermès Birkin 30 Cacao</h3>
        </div>
        <div class="product">
            <img src="images/watches/rolex 4.jpeg" alt="Rolex Watch">
            <h3>Rolex Yacht-Master</h3>
        </div>
        <div class="product">
            <img src="images/jewellery/cartier_bracelet.jpg" alt="Cartier Bracelet">
            <h3>Cartier Love 18k Yellow Gold Bracelet</h3>
        </div>
    </div>
</section>


<?php include 'includes/footer.php'; ?>
<script src = "js/slideshow.js"></script>
