<?php
// Database setup script for Dry Zone Cantilan
// This script will create the database and table if they don't exist

$servername = "localhost";
$username = "root";
$password = "";

try {
    // First connect without specifying a database
    $conn = new mysqli($servername, $username, $password);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS `login_register` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if ($conn->query($sql) === TRUE) {
        echo "Database 'login_register' created successfully or already exists.<br>";
    } else {
        echo "Error creating database: " . $conn->error . "<br>";
    }
    
    // Select the database
    $conn->select_db("login_register");
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `full_name` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL UNIQUE,
        `password` varchar(255) NOT NULL,
        `role` enum('customer','seller','admin') NOT NULL DEFAULT 'customer',
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'users' created successfully or already exists.<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
    
    // Add role column if it doesn't exist (for existing databases)
    $checkColumn = $conn->query("SHOW COLUMNS FROM `users` LIKE 'role'");
    if ($checkColumn && $checkColumn->num_rows == 0) {
        $alterSql = "ALTER TABLE `users` ADD COLUMN `role` enum('customer','seller','admin') NOT NULL DEFAULT 'customer' AFTER `password`";
        if ($conn->query($alterSql) === TRUE) {
            echo "Role column added successfully.<br>";
        } else {
            echo "Error adding role column: " . $conn->error . "<br>";
        }
    }
    
    // Check if table is empty and add a test user and admin
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        $testPassword = password_hash('password123', PASSWORD_BCRYPT);
        $adminPassword = password_hash('admin123', PASSWORD_BCRYPT);
        
        // Insert test customer
        $sql = "INSERT INTO users (full_name, email, password, role) VALUES ('Test User', 'test@example.com', '$testPassword', 'customer')";
        if ($conn->query($sql) === TRUE) {
            echo "Test user created successfully.<br>";
            echo "Test credentials: Email: test@example.com, Password: password123<br>";
        } else {
            echo "Error creating test user: " . $conn->error . "<br>";
        }
        
        // Insert admin user
        $sql = "INSERT INTO users (full_name, email, password, role) VALUES ('Admin User', 'admin@dryzone.com', '$adminPassword', 'admin')";
        if ($conn->query($sql) === TRUE) {
            echo "Admin user created successfully.<br>";
            echo "Admin credentials: Email: admin@dryzone.com, Password: admin123<br>";
        } else {
            echo "Error creating admin user: " . $conn->error . "<br>";
        }
    }
    
    echo "<br><strong>Database setup completed successfully!</strong><br>";
    echo "You can now use the login/register functionality.<br>";
    echo "<br><a href='overview.html'>Go to Overview Page</a>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
