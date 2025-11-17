<?php
$servername = "localhost";      // or your server IP
$username = "root"; // replace with your DB username
$password = ""; // replace with your DB password
$dbname = "login_register";       // replace with your database name

// Enable error reporting for mysqli to catch exceptions
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // First try to connect to the specific database
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check if role column exists and add it if needed (for existing databases)
    $checkColumn = $conn->query("SHOW COLUMNS FROM `users` LIKE 'role'");
    if ($checkColumn && $checkColumn->num_rows == 0) {
        $alterSql = "ALTER TABLE `users` ADD COLUMN `role` enum('customer','seller','admin') NOT NULL DEFAULT 'customer' AFTER `password`";
        $conn->query($alterSql);
    }
} catch (mysqli_sql_exception $e) {
    // Database doesn't exist or connection failed, create it
    // Connect without specifying database
    $conn = new mysqli($servername, $username, $password);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if ($conn->query($sql) === TRUE) {
        // Select the new database
        $conn->select_db($dbname);
        
        // Check if table exists
        $tableCheck = $conn->query("SHOW TABLES LIKE 'users'");
        if ($tableCheck->num_rows == 0) {
            // Create users table only if it doesn't exist
            $sql = "CREATE TABLE `users` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `full_name` varchar(255) NOT NULL,
                `email` varchar(255) NOT NULL,
                `password` varchar(255) NOT NULL,
                `role` enum('customer','seller','admin') NOT NULL DEFAULT 'customer',
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            if (!$conn->query($sql)) {
                die("Error creating table: " . $conn->error);
            }
        } else {
            // Table exists, check if role column exists
            $checkColumn = $conn->query("SHOW COLUMNS FROM `users` LIKE 'role'");
            if ($checkColumn && $checkColumn->num_rows == 0) {
                $alterSql = "ALTER TABLE `users` ADD COLUMN `role` enum('customer','seller','admin') NOT NULL DEFAULT 'customer' AFTER `password`";
                $conn->query($alterSql);
            }
        }
        
        // Create orders table
        $ordersTableCheck = $conn->query("SHOW TABLES LIKE 'orders'");
        if ($ordersTableCheck->num_rows == 0) {
            $ordersSql = "CREATE TABLE `orders` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `shop_name` varchar(255) NOT NULL,
                `customer_name` varchar(255) NOT NULL,
                `customer_email` varchar(255) NOT NULL,
                `customer_phone` varchar(50) NOT NULL,
                `customer_address` text NOT NULL,
                `services` text NOT NULL,
                `item_count` int(11) NOT NULL,
                `urgency` varchar(50) NOT NULL DEFAULT 'normal',
                `special_instructions` text,
                `pickup_date` date NOT NULL,
                `pickup_time` time NOT NULL,
                `status` enum('pending','confirmed','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
                `total_price` decimal(10,2) DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`),
                KEY `status` (`status`),
                FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            if (!$conn->query($ordersSql)) {
                // If foreign key fails, create without it
                $ordersSql = str_replace(', FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE', '', $ordersSql);
                $conn->query($ordersSql);
            }
        }
    } else {
        die("Error creating database: " . $conn->error);
    }
}

// Also check and create orders table for existing databases
if (!$conn->connect_error) {
    $ordersTableCheck = $conn->query("SHOW TABLES LIKE 'orders'");
    if ($ordersTableCheck->num_rows == 0) {
        $ordersSql = "CREATE TABLE `orders` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `shop_name` varchar(255) NOT NULL,
            `customer_name` varchar(255) NOT NULL,
            `customer_email` varchar(255) NOT NULL,
            `customer_phone` varchar(50) NOT NULL,
            `customer_address` text NOT NULL,
            `services` text NOT NULL,
            `item_count` int(11) NOT NULL,
            `urgency` varchar(50) NOT NULL DEFAULT 'normal',
            `special_instructions` text,
            `pickup_date` date NOT NULL,
            `pickup_time` time NOT NULL,
            `status` enum('pending','confirmed','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
            `total_price` decimal(10,2) DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`),
            KEY `status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $conn->query($ordersSql);
    }
}

// Ensure reviews table exists
if (!$conn->connect_error) {
    $reviewsTableCheck = $conn->query("SHOW TABLES LIKE 'reviews'");
    if ($reviewsTableCheck->num_rows == 0) {
        $reviewsSql = "CREATE TABLE `reviews` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `shop_name` varchar(255) NOT NULL,
            `user_id` int(11) DEFAULT NULL,
            `user_name` varchar(255) DEFAULT NULL,
            `rating` tinyint(1) NOT NULL,
            `comment` text,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `shop_name` (`shop_name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $conn->query($reviewsSql);
    }
}
?>
