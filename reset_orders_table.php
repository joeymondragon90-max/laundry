<?php
// Reset and recreate orders table with proper schema
require 'db.php';

echo "<h2>Resetting Orders Table</h2>";
echo "<hr>";

// Drop existing tables if they exist
$drop_history = "DROP TABLE IF EXISTS order_status_history";
$drop_orders = "DROP TABLE IF EXISTS orders";

if ($conn->query($drop_history) === TRUE) {
    echo "✓ Dropped existing order_status_history table<br>";
}

if ($conn->query($drop_orders) === TRUE) {
    echo "✓ Dropped existing orders table<br>";
}

// Create fresh orders table with ALL required columns
$create_orders = "CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    shop_name VARCHAR(255) NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    service_type VARCHAR(100),
    weight_kg DECIMAL(5,2),
    total_amount DECIMAL(10,2) NOT NULL,
    student_discount DECIMAL(10,2) DEFAULT 0,
    voucher_code VARCHAR(50),
    voucher_discount DECIMAL(10,2) DEFAULT 0,
    payment_method VARCHAR(100),
    reference_number VARCHAR(100),
    status ENUM('pending', 'processing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
    pickup_date DATETIME,
    delivery_date DATETIME,
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (shop_name),
    INDEX (customer_id),
    INDEX (status),
    INDEX (order_date)
)";

if ($conn->query($create_orders) === TRUE) {
    echo "✓ Orders table created with all columns<br>";
} else {
    echo "✗ Error creating orders table: " . $conn->error . "<br>";
}

// Create fresh order_status_history table
$create_history = "CREATE TABLE order_status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    old_status VARCHAR(50),
    new_status VARCHAR(50) NOT NULL,
    changed_by VARCHAR(100),
    changed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    INDEX (order_id),
    INDEX (changed_at)
)";

if ($conn->query($create_history) === TRUE) {
    echo "✓ Order status history table created<br>";
} else {
    echo "✗ Error creating order_status_history table: " . $conn->error . "<br>";
}

echo "<hr>";
echo "<h3>✓ Tables Reset Complete!</h3>";
echo "<p><a href='index.php'>← Back to Home</a></p>";

$conn->close();
?>
