<?php
// Comprehensive Database Setup Script for Dry Zone Cantilan
// This script creates all required tables and columns

require 'db.php';

echo "<h2>Dry Zone Cantilan - Database Setup</h2>";
echo "<hr>";

// 1. Create/Update orders table
$create_orders = "CREATE TABLE IF NOT EXISTS orders (
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
    echo "✓ Orders table created/verified<br>";
} else {
    if (strpos($conn->error, "already exists") !== false) {
        echo "✓ Orders table exists<br>";
    } else {
        echo "⚠ " . $conn->error . "<br>";
    }
}

// 2. Create order status history table
$create_history = "CREATE TABLE IF NOT EXISTS order_status_history (
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
    echo "✓ Order status history table created/verified<br>";
} else {
    if (strpos($conn->error, "already exists") !== false) {
        echo "✓ Order status history table exists<br>";
    } else {
        echo "⚠ " . $conn->error . "<br>";
    }
}

// 3. Create chat messages table
$create_chat = "CREATE TABLE IF NOT EXISTS chat_messages (
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

if ($conn->query($create_chat) === TRUE) {
    echo "✓ Chat messages table created/verified<br>";
} else {
    if (strpos($conn->error, "already exists") !== false) {
        echo "✓ Chat messages table exists<br>";
    } else {
        echo "⚠ " . $conn->error . "<br>";
    }
}

// 4. Create shop directory table
$create_shop_dir = "CREATE TABLE IF NOT EXISTS shop_directory (
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

if ($conn->query($create_shop_dir) === TRUE) {
    echo "✓ Shop directory table created/verified<br>";
} else {
    if (strpos($conn->error, "already exists") !== false) {
        echo "✓ Shop directory table exists<br>";
    } else {
        echo "⚠ " . $conn->error . "<br>";
    }
}

echo "<hr>";
echo "<h3>✓ Database Setup Complete!</h3>";
echo "<p>All tables and columns have been created/verified successfully.</p>";
echo "<p><strong>Features Enabled:</strong></p>";
echo "<ul>";
echo "<li>✓ Real-time Chat System (chat_messages)</li>";
echo "<li>✓ Order Tracking System (orders, order_status_history)</li>";
echo "<li>✓ Advanced Search & Filters (shop_directory)</li>";
echo "</ul>";
echo "<p style='margin-top: 20px;'><a href='index.php'>← Back to Home</a></p>";

$conn->close();
?>
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
