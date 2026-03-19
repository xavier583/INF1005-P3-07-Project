<?php session_start(); ?>
<?php
$rootPath = "..";
include '../includes/header.php';
?>


<?php include '../includes/nav.php'; ?>

<div class = "container mt-5">
    <h1>Jewellery Collection</h1>
    <div class = "row">

        <!--- Product 1 -->
        <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">

        <!-- Carousel START -->
        <div id="carousel1" class="carousel slide">
            <div class="carousel-inner">

                <div class="carousel-item active">
                    <img src="../images/jewellery/cartier_bracelet.jpg" class="d-block w-100" alt="Cartier 1">
                </div>

                <div class="carousel-item">
                    <img src="../images/jewellery/cartier_bracelet1.jpg" class="d-block w-100" alt="Cartier 2">
                </div>

                <div class="carousel-item">
                    <img src="../images/jewellery/cartier_bracelet2.jpg" class="d-block w-100" alt="Cartier 3">
                </div>
            </div>

            <!-- Arrows --> 
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel1" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#carousel1" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
        <!-- Carousel END -->

        <div class="card-body">
            <h4 class="card-title">Cartier</h4>
            <h6 class="card-title">Love 18k Yellow Gold Bracelet</h6>
            <p class="card-text">$14,870</p>
            <form method ="POST" action="../cart.php">
                <input type="hidden" name="id" value="1">
                <input type="hidden" name="name" value="Cartier Love 18k Yellow Gold Bracelet">
                <input type="hidden" name="price" value="14870">
                <input type="hidden" name="image" value="../images/jewellery/cartier_bracelet.jpg">
                <button type="submit" name = "add" class="btn btn-dark">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

        <!--- Product 2 -->
        <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">

        <!-- Carousel START -->
        <div id="carousel2" class="carousel slide">
            <div class="carousel-inner">

                <div class="carousel-item active">
                    <img src="../images/jewellery/bvlgari_bracelet.png" class="d-block w-100" alt="Bvlgari 1">
                </div>

                <div class="carousel-item">
                    <img src="../images/jewellery/bvlgari_bracelet1.jpg" class="d-block w-100" alt="Bvlgari 2">
                </div>

                <div class="carousel-item">
                    <img src="../images/jewellery/bvlgari_bracelet2.jpg" class="d-block w-100" alt="Bvlgari 3">
                </div>
            </div>

            <!-- Arrows --> 
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel2" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#carousel2" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
        <!-- Carousel END -->

        <div class="card-body">
            <h4 class="card-title">Bvlgari</h4>
            <h6 class="card-title">Serpenti Viper Bracelet 18k Rose Gold</h6>
            <p class="card-text">$50,888</p>
            <form method ="POST" action="../cart.php">
                <input type="hidden" name="id" value="2">
                <input type="hidden" name="name" value="Bvlgari Serpenti Viper Bracelet 18k Rose Gold">
                <input type="hidden" name="price" value="50888">
                <input type="hidden" name="image" value="../images/jewellery/bvlgari_bracelet.png">
                <button type="submit" name = "add" class="btn btn-dark">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

        <!--- Product 3 -->
        <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">

        <!-- Carousel START -->
        <div id="carousel3" class="carousel slide">
            <div class="carousel-inner">

                <div class="carousel-item active">
                    <img src="../images/jewellery/tiffany_necklace.jpg" class="d-block w-100" alt="Tiffany 1">
                </div>

                <div class="carousel-item">
                    <img src="../images/jewellery/tiffany_necklace1.jpg" class="d-block w-100" alt="Tiffany 2">
                </div>
            </div>

            <!-- Arrows --> 
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel3" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#carousel3" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
        <!-- Carousel END -->

        <div class="card-body">
            <h4 class="card-title">Tiffany & Co.</h4>
            <h6 class="card-title">Return to Tiffany Silver Necklace</h6>
            <p class="card-text">$1,550</p>
            <form method ="POST" action="../cart.php">
                <input type="hidden" name="id" value="3">
                <input type="hidden" name="name" value="Tiffany & Co. Return to Tiffany Silver Necklace">
                <input type="hidden" name="price" value="1550">
                <input type="hidden" name="image" value="../images/jewellery/tiffany_necklace.jpg">
                <button type="submit" name = "add" class="btn btn-dark">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

         <!--- Product 4 -->
        <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">

        <!-- Carousel START -->
        <div id="carousel4" class="carousel slide">
            <div class="carousel-inner">

                <div class="carousel-item active">
                    <img src="../images/jewellery/dior_earrings.png" class="d-block w-100" alt="Dior 1">
                </div>

                <div class="carousel-item">
                    <img src="../images/jewellery/dior_earrings1.png" class="d-block w-100" alt="Dior 2">
                </div>
            </div>

            <!-- Arrows --> 
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel4" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#carousel4" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
        <!-- Carousel END -->

        <div class="card-body">
            <h4 class="card-title">Dior</h4>
            <h6 class="card-title">Petit CD Earrings - Silver-Finish Metal with White Resin Pearls</h6>
            <p class="card-text">$450</p>
            <form method ="POST" action="../cart.php">
                <input type="hidden" name="id" value="4">
                <input type="hidden" name="name" value="Dior Petit CD Earrings - Silver-Finish Metal with White Resin Pearls">
                <input type="hidden" name="price" value="450">
                <input type="hidden" name="image" value="../images/jewellery/dior_earrings.png">
                <button type="submit" name = "add" class="btn btn-dark">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

        <!--- Product 5 -->
        <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">

        <!-- Carousel START -->
        <div id="carousel5" class="carousel slide">
            <div class="carousel-inner">

                <div class="carousel-item active">
                    <img src="../images/jewellery/chanel_ring1.png" class="d-block w-100" alt="Chanel 1">
                </div>

                <div class="carousel-item">
                    <img src="../images/jewellery/chanel_ring.png" class="d-block w-100" alt="Chanel 2">
                </div>
            </div>

            <!-- Arrows --> 
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel5" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#carousel5" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
        <!-- Carousel END -->

        <div class="card-body">
            <h4 class="card-title">Chanel</h4>
            <h6 class="card-title">Faux Pearl Matte Gold Tone CC Logo Ring</h6>
            <p class="card-text">$610</p>
            <form method ="POST" action="../cart.php">
                <input type="hidden" name="id" value="5">
                <input type="hidden" name="name" value="Chanel Faux Pearl Matte Gold Tone CC Logo Ring">
                <input type="hidden" name="price" value="610">
                <input type="hidden" name="image" value="../images/jewellery/chanel_ring.png">
                <button type="submit" name = "add" class="btn btn-dark">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

        <!--- Product 6 -->
        <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">

        <!-- Carousel START -->
        <div id="carousel6" class="carousel slide">
            <div class="carousel-inner">

                <div class="carousel-item active">
                    <img src="../images/jewellery/hermes_necklace.png" class="d-block w-100" alt="Hermes 1">
                </div>

                <div class="carousel-item">
                    <img src="../images/jewellery/hermes_necklace1.png" class="d-block w-100" alt="Hermes 2">
                </div>
            </div>

            <!-- Arrows --> 
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel6" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#carousel6" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
        <!-- Carousel END -->

        <div class="card-body">
            <h4 class="card-title">Hermès</h4>
            <h6 class="card-title">Mini Pop H Pendant - Rose Gold Plated</h6>
            <p class="card-text">$780</p>
            <form method ="POST" action="../cart.php">
                <input type="hidden" name="id" value="6">
                <input type="hidden" name="name" value="Hermès Mini Pop H Pendant - Rose Gold Plated">
                <input type="hidden" name="price" value="780">
                <input type="hidden" name="image" value="../images/jewellery/hermes_necklace.png">
                <button type="submit" name = "add" class="btn btn-dark">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

    </div>
</div>

<?php include '../includes/footer.php'; ?>