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
    <title>About Us | Dry Zone - Cantilan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>
<div id="pageContent">
    <section class="container" style="max-width:900px; margin-top:50px; margin-bottom:50px;">
        <h2 class="section-title">About Dry Zone - Cantilan</h2>
        <div style="background:white; border-radius:14px; box-shadow:0 6px 18px rgba(0,0,0,0.07); padding:30px; font-size:1.08rem;">
            <p>
                <strong>Dry Zone - Cantilan</strong> is your comprehensive directory for laundry shops and services in Cantilan, Surigao del Sur. 
                Founded with the vision to make laundry services easily discoverable for residents and visitors, our platform brings together a curated list of local laundry providers, complete with shop details, ratings, locations, and available services.
            </p>
            <p>
                We believe that clean clothes should be convenient for everyone. By offering a user-friendly directory, we help you compare prices, find special offers, and choose the best laundry shop for your needs—whether you're looking for express service, eco-friendly cleaning, or pickup and delivery options.
            </p>
            <p>
                <strong>Our Mission:</strong><br>
                To connect the community of Cantilan with trusted laundry service providers, ensuring convenience, quality, and satisfaction for all.
            </p>
            <p>
                <strong>Why Use Dry Zone - Cantilan?</strong>
                <ul style="margin-top:10px; margin-bottom:10px; padding-left:20px;">
                    <li>Find and compare local laundry shops instantly</li>
                    <li>Access shop locations and contact details easily</li>
                    <li>See genuine customer reviews and shop ratings</li>
                    <li>Discover services tailored to your needs (wash, dry, fold, express, eco-friendly, and more)</li>
                </ul>
            </p>
            <p>
                Have suggestions or feedback? Reach out to us at <a href="mailto:info@dryzonecantilan.com">info@dryzonecantilan.com</a>.
            </p>
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
</body>
</html>

