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
    <title>Our Services | Dry Zone - Cantilan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>
<section class="services-section">
    <h2 class="section-title">Our Services</h2>
    <div class="services-grid">
        <div class="service-card">
            <span class="service-icon"><i class="fas fa-tshirt"></i></span>
            <div class="service-title">Wash & Fold</div>
            <div class="service-desc">Professional washing and careful folding for your everyday clothes.</div>
        </div>
        <div class="service-card">
            <span class="service-icon"><i class="fas fa-shirt"></i></span>
            <div class="service-title">Ironing</div>
            <div class="service-desc">Expert ironing for crisp, wrinkle-free garments.</div>
        </div>
        <div class="service-card">
            <span class="service-icon"><i class="fas fa-soap"></i></span>
            <div class="service-title">Dry Cleaning</div>
            <div class="service-desc">Specialized dry cleaning for delicate and special-care fabrics.</div>
        </div>
        <div class="service-card">
            <span class="service-icon"><i class="fas fa-truck"></i></span>
            <div class="service-title">Pickup & Delivery</div>
            <div class="service-desc">Convenient pickup and delivery service for your laundry needs.</div>
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
<script src="script.js"></script>
</body>
</html>

