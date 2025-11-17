<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Please log in to place an order.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $shop_name = isset($_POST['shop_name']) ? trim($_POST['shop_name']) : 'General Laundry Shop';
    $customer_name = trim($_POST['customerName']);
    $customer_email = trim($_POST['customerEmail']);
    $customer_phone = trim($_POST['customerPhone']);
    $customer_address = trim($_POST['customerAddress']);
    
    // Handle services array - PHP receives services[] as $_POST['services'] array
    $services = '';
    if (isset($_POST['services']) && is_array($_POST['services'])) {
        // Map service values to readable names
        $serviceNames = [
            'wash' => 'Washing',
            'dry' => 'Drying',
            'fold' => 'Folding',
            'iron' => 'Ironing',
            'dryclean' => 'Dry Cleaning',
            'express' => 'Express Service'
        ];
        $serviceList = [];
        foreach ($_POST['services'] as $service) {
            $serviceList[] = isset($serviceNames[$service]) ? $serviceNames[$service] : ucfirst($service);
        }
        $services = implode(', ', $serviceList);
    } elseif (isset($_POST['services']) && !empty($_POST['services'])) {
        $services = $_POST['services'];
    }
    
    // pricing and count
    $pricing_mode = isset($_POST['pricing_mode']) ? $_POST['pricing_mode'] : 'per_kg';
    $item_count = 0;
    $total_price = null;
    $urgency = isset($_POST['urgency']) ? $_POST['urgency'] : 'normal';
    $special_instructions = isset($_POST['specialInstructions']) ? trim($_POST['specialInstructions']) : '';
    $pickup_date = $_POST['pickupDate'];
    $pickup_time = $_POST['pickupTime'];
    
    // Validate required fields (basic)
    if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($customer_address) || empty($services) || empty($pickup_date) || empty($pickup_time)) {
        echo json_encode(['success' => false, 'error' => 'Please fill in all required fields.']);
        exit();
    }

    // Compute total price based on pricing mode
    if ($pricing_mode === 'per_kg') {
        $weight_kg = isset($_POST['weight_kg']) ? floatval($_POST['weight_kg']) : 0;
        $price_per_kg = isset($_POST['price_per_kg']) ? floatval($_POST['price_per_kg']) : 0;
        if ($weight_kg <= 0 || $price_per_kg <= 0) {
            echo json_encode(['success' => false, 'error' => 'Please provide valid weight and price per kg.']);
            exit();
        }
        $total_price = round($weight_kg * $price_per_kg, 2);
        // store a representative item_count (0 for per-kg)
        $item_count = 0;
    } else {
        // per 8kg pack
        $num_packs = isset($_POST['num_packs']) ? intval($_POST['num_packs']) : 0;
        $rate_type = isset($_POST['rate_type']) ? $_POST['rate_type'] : 'normal';
        $price_per_8kg = isset($_POST['price_per_8kg']) ? floatval($_POST['price_per_8kg']) : 0;
        $price_per_8kg_student = isset($_POST['price_per_8kg_student']) ? floatval($_POST['price_per_8kg_student']) : 0;
        if ($num_packs <= 0) {
            echo json_encode(['success' => false, 'error' => 'Please provide valid number of packs.']);
            exit();
        }
        if ($rate_type === 'student' && $price_per_8kg_student > 0) {
            $total_price = round($num_packs * $price_per_8kg_student, 2);
        } else {
            $total_price = round($num_packs * $price_per_8kg, 2);
        }
        $item_count = $num_packs;
    }
    
    // Insert order into database (including computed total_price)
    $stmt = $conn->prepare("INSERT INTO orders (user_id, shop_name, customer_name, customer_email, customer_phone, customer_address, services, item_count, urgency, special_instructions, pickup_date, pickup_time, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssissssd", $user_id, $shop_name, $customer_name, $customer_email, $customer_phone, $customer_address, $services, $item_count, $urgency, $special_instructions, $pickup_date, $pickup_time, $total_price);
    
    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        echo json_encode([
            'success' => true, 
            'message' => 'Order submitted successfully! Your order ID is #' . $order_id,
            'order_id' => $order_id
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to submit order. Please try again.']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>

