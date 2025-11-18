<?php
require 'db.php';

// Create shop_directory table for search/filter functionality
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
    
    // Insert sample shop data
    $shops = [
        ['Daily BubbleBox Laundry Hub', 'Poblacion, Cantilan', 'Complete laundry services with free pickup and delivery within Poblacion area', 4.8, 124, 'Wash & Fold', 33.00, 1],
        ["Lava'z Laundry Shop", 'P-4, Falcon St., Magosilom, Cantilan', 'Offering wash, dry, and fold services with eco-friendly detergents', 4.5, 89, 'Eco-Friendly', 30.00, 1],
        ['Fluff\'n Fold Express Laundry Shop', 'Purok-2 Magosilom, Cantilan', 'Complete laundry services with free pickup and delivery', 4.7, 102, 'Express Service', 35.00, 1],
        ["Methusilah's Laundry Shop", 'Purok-5, Sitio Tapa, San Pedro, Cantilan', 'Fast, reliable wash-and-fold services', 4.3, 76, 'Wash & Fold', 32.00, 1],
        ['EP Laundry Shop', 'Lininti-an, Cantilan', 'Professional laundry with premium care', 4.4, 65, 'Dry Cleaning', 32.00, 1],
        ['Frankie Laundry Shop', 'Orillaneda St., Purok-3, Lininti-an Cantilan', 'Premium laundry services with expert care', 4.9, 135, 'Premium', 33.00, 1],
        ['Wash & Shine Laundry Shop', 'Magosilom, Cantilan', 'Affordable laundry shop in Cantilan', 4.6, 92, 'Wash & Fold', 33.00, 1],
        ['Washerman Laundry Shop', 'Pag-antayan, Cantilan', 'Fast, reliable wash-and-fold services', 4.6, 98, 'Wash & Fold', 33.00, 1],
        ['Everybody Laundry Shop', 'Calagdaan, Cantilan', 'Professional team with meticulous service', 4.1, 54, 'Premium', 50.00, 1]
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
