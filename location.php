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
    <title>Location Map - Dry Zone Cantilan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>
<!-- Location Section -->
<section class="container" style="max-width:1000px;">
    <div class="location-section">
        <h2 class="section-title">Laundry Shops Location Map</h2>
        <p style="text-align: center; margin-bottom: 20px;">Find laundry shops near your location in Cantilan.</p>
        <div style="height: 400px; width: 100%; border-radius: 10px; margin: 20px auto; overflow: hidden;">
            <!-- Google Maps iframe example for Cantilan, Surigao del Sur -->
            <iframe
                src="https://www.google.com/maps?q=Cantilan,+Surigao+del+Sur,+Philippines&hl=en&z=14&output=embed"
                width="100%"
                height="400"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
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
</body>
</html>

