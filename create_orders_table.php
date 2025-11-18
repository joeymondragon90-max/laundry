<?php
require 'db.php';

// Create orders table with tracking
$sql = "CREATE TABLE IF NOT EXISTS orders (
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

if ($conn->query($sql) === TRUE) {
    echo "Orders table created successfully!<br>";
} else {
    echo "Error creating orders table: " . $conn->error . "<br>";
}

// Create order status history table for tracking
$sql2 = "CREATE TABLE IF NOT EXISTS order_status_history (
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

if ($conn->query($sql2) === TRUE) {
    echo "Order status history table created successfully!<br>";
} else {
    echo "Error creating order status history table: " . $conn->error . "<br>";
}

echo "Database setup complete!";
$conn->close();
?>
