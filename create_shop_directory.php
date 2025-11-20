<?php
require 'db.php';

$sql = "CREATE TABLE IF NOT EXISTS shop_directory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shop_name VARCHAR(255) NOT NULL UNIQUE,
    location VARCHAR(255) NOT NULL,
    description TEXT,
    rating DECIMAL(3,1) DEFAULT 4.0,
    num_reviews INT DEFAULT 0,
    primary_service VARCHAR(100),
    avg_price DECIMAL(8,2) DEFAULT 30.00,
    has_pickup_delivery BOOLEAN DEFAULT TRUE,
    phone_number VARCHAR(20),
    operating_hours VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (shop_name),
    INDEX (location),
    INDEX (rating),
    INDEX (avg_price)
)";

if ($conn->query($sql) === TRUE) {
    echo "Shop directory table created successfully!<br><br>";
    
    $shops = [
        ['Daily BubbleBox Laundry Hub', 'Poblacion, Cantilan', 'Complete laundry services with free pickup and delivery. Specializing in wash & fold and dry cleaning. Open 7AM-6PM daily.', 4.8, 124, 'Wash & Fold', 28.00, 1],
        ["Lava'z Laundry Shop", 'P-4, Falcon St., Magosilom, Cantilan', 'Eco-friendly laundry using sustainable detergents. Wash & fold specialist with student discounts. Open 8AM-7PM.', 4.5, 89, 'Eco-Friendly', 32.00, 1],
        ['Fluff\'n Fold Express Laundry Shop', 'Purok-2 Magosilom, Cantilan', 'Express same-day service and heavy items specialist. Free delivery within 2km. Offers ironing and starch service. Open 7AM-8PM.', 4.7, 102, 'Express Service', 35.00, 1],
        ["Methusilah's Laundry Shop", 'Purok-5, Sitio Tapa, San Pedro, Cantilan', 'Budget-friendly wash-and-fold service. Student discount 20%. Perfect for daily laundry needs. Open 8AM-6PM.', 4.3, 76, 'Budget', 25.00, 1],
        ['EP Laundry Shop', 'Lininti-an, Cantilan', 'Premium dry cleaning specialist for formal wear and delicate items. Same-day express service available. Open 9AM-5PM.', 4.4, 65, 'Dry Cleaning', 45.00, 1],
        ['Frankie Laundry Shop', 'Orillaneda St., Purok-3, Lininti-an Cantilan', 'Premium service with expert stain removal and delicate fabric care. Corporate and bulk orders welcome. Open 8AM-6PM.', 4.9, 135, 'Premium', 40.00, 1],
        ['Wash & Shine Laundry Shop', 'Magosilom, Cantilan', 'Affordable family laundry with heavy items service. Free delivery for orders ₱300+. Bed sheets and curtains specialty. Open 7AM-7PM.', 4.6, 92, 'Family', 30.00, 1],
        ['Washerman Laundry Shop', 'Pag-antayan, Cantilan', 'Reliable wash-and-fold with quick turnaround. Student-friendly pricing. Quality assured with satisfaction guarantee. Open 8AM-6PM.', 4.6, 98, 'Wash & Fold', 29.00, 1],
        ['Everybody Laundry Shop', 'Calagdaan, Cantilan', 'Full-service laundry including starch, ironing, and special orders. Bulk discounts available. Open 7AM-8PM daily.', 4.1, 54, 'Full Service', 38.00, 1]
    ];

    foreach ($shops as $shop) {
        $stmt = $conn->prepare("
            INSERT INTO shop_directory (shop_name, location, description, rating, num_reviews, primary_service, avg_price, has_pickup_delivery)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param("sssdisdi", $shop[0], $shop[1], $shop[2], $shop[3], $shop[4], $shop[5], $shop[6], $shop[7]);
        
        if ($stmt->execute()) {
            echo "✓ Inserted: " . $shop[0] . "<br>";
        } else {
            echo "✗ Failed to insert " . $shop[0] . ": " . $stmt->error . "<br>";
        }
        
        $stmt->close();
    }
    
    echo "<br>Shop directory setup complete!";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
