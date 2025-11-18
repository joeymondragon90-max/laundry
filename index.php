<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dry Zone - Cantilan | Local Laundry Shops Directory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>
<div id="pageContent">
    <section class="hero">
        <div class="container">
            <h2>Find Laundry Shops in Cantilan</h2>
            <p>Discover the best laundry services near you. Compare prices, services, and locations.</p>
            <a href="#shops" class="btn">Explore Shops</a>
        </div>
    </section>
    <section class="container" id="shops">
        <div class="shops">
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./bubblebox.png" class="photo-link">
                        <img src="./bubblebox.png" alt="Daily BubbleBox Laundry Hub">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.8</div>
                </div>
                <div class="shop-content">
                    <h3>Daily BubbleBox Laundry Hub</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Poblacion, Cantilan</span>
                    </div>
                    <p>Complete laundry services with free pickup and delivery within Poblacion area.</p>
                    <a href="shop1.php" class="btn">View Details</a>
                </div>
            </div>
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./Lava.jpg" class="photo-link">
                        <img src="./Lava.jpg" alt="Lava'z Laundry Shop">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.5</div>
                </div>
                <div class="shop-content">
                    <h3>Lava'z Laundry Shop</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>P-4, Falcon St., Magosilom, Cantilan</span>
                    </div>
                    <p>Offering wash, dry, and fold services with eco-friendly detergents.</p>
                    <a href="shop2.php" class="btn">View Details</a>
                </div>
            </div>
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./fluff.png" class="photo-link">
                        <img src="./fluff.png" alt="Fluff'n Fold Express Laundry Shop">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.7</div>
                </div>
                <div class="shop-content">
                    <h3>Fluff'n Fold Express Laundry Shop</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Purok-2 magosilom, Cantilan</span>
                    </div>
                    <p>Specializing in dry cleaning and delicate fabric care.</p>
                    <a href="shop3.php" class="btn">View Details</a>
                </div>
            </div>
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./Methsuliah.png" class="photo-link">
                        <img src="./Methsuliah.png" alt="Methusilah's Laundry Shop">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.3</div>
                </div>
                <div class="shop-content">
                    <h3>Methusilah's Laundry Shop</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Purok-5, Sitio Tapa, Brgy. San Pedro, Cantilan</span>
                    </div>
                    <p>Express laundry service with 3-hour turnaround available.</p>
                    <a href="shop4.php" class="btn">View Details</a>
                </div>
            </div>
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./EP.jpg" class="photo-link">
                        <img src="./EP.jpg" alt="EP Laundry Shop">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.6</div>
                </div>
                <div class="shop-content">
                    <h3>EP Laundry Shop</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Urbiztondo St., Purok 3 Magosilom, Cantilan</span>
                    </div>
                    <p>Professional washing and ironing services with pickup available.</p>
                    <a href="shop5.php" class="btn">View Details</a>
                </div>
            </div>
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./Frankie.png" class="photo-link">
                        <img src="./Frankie.png" alt="Frankie Laundry Shop">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.9</div>
                </div>
                <div class="shop-content">
                    <h3>Frankie Laundry Shop</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Orillaneda St., Purok-3, Lininti-an Cantilan</span>
                    </div>
                    <p>Environmentally friendly laundry using hypoallergenic detergents.</p>
                    <a href="shop6.php" class="btn">View Details</a>
                </div>
            </div>
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./wash.png" class="photo-link">
                        <img src="./wash.png" alt="Eco Clean Laundry">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.4</div>
                </div>
                <div class="shop-content">
                    <h3>Wash & Shine Laundry Shop</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Magosilom, Cantilan</span>
                    </div>
                    <p>Your affordable laundry shop in Cantilan.</p>
                    <a href="shop7.php" class="btn">View Details</a>
                </div>
            </div>
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./washerman.png" class="photo-link">
                        <img src="./washerman.png" alt="Eco Clean Laundry">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.6</div>
                </div>
                <div class="shop-content">
                    <h3>Washerman Laundry Shop</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Pag-antayan, Cantilan</span>
                    </div>
                    <p>Experience the ultimate convenience with Washerman Laundry Shop! We offer fast, reliable wash-and-fold services designed to save you time and hassle.</p>
                    <a href="shop8.php" class="btn">View Details</a>
                </div>
            </div>
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./everybody.png" class="photo-link">
                        <img src="./everybody.png" alt="Eco Clean Laundry">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.1</div>
                </div>
                <div class="shop-content">
                    <h3>Everybody Laundry Shop</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Calagdaan, Cantilan</span>
                    </div>
                    <p>From delicate garments to everyday wear, our professional team provides meticulous washing, drying, and expert folding, using premium detergents and conditioners.</p>
                    <a href="shop9.php" class="btn">View Details</a>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Dry Zone - Cantilan</h3>
                    <p>Your directory for laundry services in Cantilan, Surigao del Sur.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="location.php">Location Map</a></li>
                        <li><a href="services.php">Services</a></li>
                        <li><a href="about.php">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> Poblacion, Cantilan, Surigao del Sur</li>
                        <li><i class="fas fa-phone"></i> (086) 234-5678</li>
                        <li><i class="fas fa-envelope"></i> info@dryzonecantilan.com</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Dry Zone - Cantilan. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>
<div id="imgModal" class="img-modal" style="display:none; position:fixed; z-index:9999; left:0;top:0;width:100vw;height:100vh;background: rgba(44,62,80,0.85);align-items:center;justify-content:center;">
    <span class="img-modal-close" id="imgModalClose" style="position:absolute;top:40px;right:60px;font-size:3rem;color:#fff;cursor:pointer;z-index:10001;">&times;</span>
    <img class="img-modal-content" id="imgModalImg" src="" alt="Photo" style="max-width:90vw;max-height:80vh;display:block;margin:auto;box-shadow:0 8px 40px rgba(0,0,0,0.5);border-radius:16px;">
</div>
<script src="script.js"></script>
</body>
</html>

