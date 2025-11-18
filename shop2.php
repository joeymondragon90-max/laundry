<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lava'z Laundry Hub | Dry Zone Cantilan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="shop1.css">
</head>
<body>
<main class="shop-detail-container container">
    <div class="shop-detail-header">
        <a href="./Lava.jpg" class="photo-link">
            <img src="./Lava.jpg" alt="Lava'z Laundry Hub" class="shop-detail-img">
        </a>
        <div class="shop-detail-info">
            <h2>Lava'z Laundry Hub</h2>
            <div class="shop-info"><i class="fas fa-map-marker-alt"></i> P-4, Falcon St., Magosilom, Cantilan</div>
            <div class="shop-info"><i class="fas fa-star rating"></i> 4.5 (89 reviews)</div>
            <div class="shop-desc">
                <p>Complete laundry services with free pickup and delivery within Poblacion area. Fast, reliable, and clean!</p>
            </div>
            <div class="shop-extra">
                <span><b>Daily Orders:</b> ~30 customers</span>
            </div>
        </div>
    </div>
    <section class="shop-services">
        <h3>Services & Pricing</h3>
        <table class="services-table" id="servicesTable">
            <tr>
                <th>Service</th>
                <th>Description</th>
                <th>Price</th>
            </tr>
            <tr>
                <td>Wash & Fold</td>
                <td>Standard clothes (min 5kg, max 8kg)</td>
                <td>₱30/kg</td>
            </tr>
            <tr>
                <td>Dry Cleaning</td>
                <td>Delicate fabrics (per item)</td>
                <td>₱170/item</td>
            </tr>
            <tr>
                <td>Mattress Cleaning</td>
                <td>Foam, blankets, comforters</td>
                <td>₱220/mattress</td>
            </tr>
            <tr>
                <td>Ironing</td>
                <td>Iron only, per kg</td>
                <td>₱25/kg</td>
            </tr>
        </table>
    </section>
    <section class="shop-photos">
        <h3>Shop Photos</h3>
        <div class="photos-row">
            <a href="./shop2.1.jpg" class="photo-link"><img src="./shop2.1.jpg" alt="Shop Interior"></a>
            <a href="./shop2.2.jpg" class="photo-link"><img src="./shop2.2.jpg" alt="Machines"></a>
        </div>
    </section>
    <div class="shop-actions">
        <a href="chat.php?shop=Lava%27z%20Laundry%20Shop"><button class="btn" id="chatBtn"><i class="fas fa-comments"></i> Chat</button></a>
        <a href="orderform.php?shop=Lava%27z%20Laundry%20Shop&return=shop2.php"><button class="btn" id="orderBtn"><i class="fas fa-shopping-cart"></i> Order Now</button></a>
    </div>
    <?php $shop_name = "Lava'z Laundry Hub"; include 'reviews_inc.php'; ?>
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
