<?php
require 'db.php';

// Create chat messages table
$sql = "CREATE TABLE IF NOT EXISTS chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    shop_name VARCHAR(255) NOT NULL,
    sender_type ENUM('customer', 'shop') NOT NULL,
    sender_name VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (shop_name),
    INDEX (customer_id),
    INDEX (timestamp)
)";

if ($conn->query($sql) === TRUE) {
    echo "Chat messages table created successfully!";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
