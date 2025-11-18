<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Methusilah's Laundry Shop | Dry Zone Cantilan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="shop1.css">
</head>
<body>
<main class="shop-detail-container container">
    <div class="shop-detail-header">
        <a href="./Methsuliah.png" class="photo-link">
            <img src="./Methsuliah.png" alt="Methusilah's Laundry Shop" class="shop-detail-img">
        </a>
        <div class="shop-detail-info">
            <h2>Methusilah's Laundry Shop</h2>
            <div class="shop-info"><i class="fas fa-map-marker-alt"></i> Purok-5, Sitio Tapa, Brgy. San Pedro, Cantilan</div>
            <div class="shop-info"><i class="fas fa-star rating"></i> 4.3 (76 reviews)</div>
            <div class="shop-desc">
                <p>Complete laundry services with free pickup and delivery within Poblacion area. Fast, reliable, and clean!</p>
            </div>
            <div class="shop-extra">
                <span><b>Daily Orders:</b> ~54 customers</span>
            </div>
        </div>
    </div>
    <section class="shop-services">
        <h3>Services & Pricing</h3>
        <table class="services-table">
            <tr>
                <th>Service</th>
                <th>Description</th>
                <th>Price</th>
            </tr>
            <tr>
                <td>Wash & Fold</td>
                <td>Standard clothes (min 5kg, max 8kg)</td>
                <td>₱32/kg</td>
            </tr>
            <tr>
                <td>Dry Cleaning</td>
                <td>Delicate fabrics (per item)</td>
                <td>₱175/item</td>
            </tr>
            <tr>
                <td>Mattress Cleaning</td>
                <td>Foam, blankets, comforters</td>
                <td>₱240/mattress</td>
            </tr>
            <tr>
                <td>Ironing</td>
                <td>Iron only, per kg</td>
                <td>₱26/kg</td>
            </tr>
        </table>
    </section>
    <section class="shop-photos">
        <h3>Shop Photos</h3>
        <div class="photos-row">
            <a href="./shop4.1.avif" class="photo-link"><img src="./shop4.1.avif" alt="Shop Interior"></a>
            <a href="./shop4.2.jpg" class="photo-link"><img src="./shop4.2.jpg" alt="Machines"></a>
        </div>
    </section>
    <div class="shop-actions">
        <button class="btn" id="chatBtn"><i class="fas fa-comments"></i> Chat</button>
        <a href="orderform.php?shop=Methusilah%27s%20Laundry%20Shop&return=shop4.php"><button class="btn" id="orderBtn"><i class="fas fa-shopping-cart"></i> Order Now</button></a>
    </div>
    <?php $shop_name = "Methusilah's Laundry Shop"; include 'reviews_inc.php'; ?>
</main>
<div id="imgModal" class="img-modal">
  <span class="img-modal-close" id="imgModalClose">&times;</span>
  <img class="img-modal-content" id="imgModalImg" src="" alt="Photo">
</div>
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
                        <li><a href="index.html">Home</a></li>
                        <li><a href="location.html">Location Map</a></li>
                        <li><a href="services.html">Services</a></li>
                        <li><a href="about.html">About Us</a></li>
                    </ul>
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
<script src="shop1.js"></script>
</body>
</html>
