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
    if ($pricing_mode === 'per_item') {
        $item_count = isset($_POST['itemCount']) ? intval($_POST['itemCount']) : 0;
        $price_per_item = isset($_POST['pricePerItem']) ? floatval($_POST['pricePerItem']) : 0;
        if ($item_count <= 0 || $price_per_item <= 0) {
            echo json_encode(['success' => false, 'error' => 'Please provide valid item count and price per item.']);
            exit();
        }
        $base_amount = $item_count * $price_per_item;
        $weight_kg = $item_count / 5; // Estimate: 5 items per 1kg
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
            $base_amount = round($num_packs * $price_per_8kg_student, 2);
        } else {
            $base_amount = round($num_packs * $price_per_8kg, 2);
        }
        $item_count = $num_packs * 45; // Estimate: 45 items per 8kg pack
        $weight_kg = $num_packs * 8;
    }
    
    // Add urgency fee
    $urgency_fee = 0;
    if ($urgency === 'urgent') $urgency_fee = 50;
    if ($urgency === 'express') $urgency_fee = 100;
    
    // Calculate delivery fee (free if order >= 300)
    $delivery_option = isset($_POST['delivery_option']) ? $_POST['delivery_option'] : 'pickup';
    $delivery_fee = 0;
    if ($delivery_option === 'delivery' && ($base_amount + $urgency_fee) < 300) {
        $delivery_fee = 50;
    }
    
    $total_price = round($base_amount + $urgency_fee + $delivery_fee, 2);
    
    // Insert order into database with new schema
    $student_discount = 0;
    $voucher_discount = 0;
    
    $stmt = $conn->prepare("INSERT INTO orders (customer_id, shop_name, service_type, weight_kg, total_amount, student_discount, voucher_discount, pickup_date, delivery_date, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULL, ?, 'pending')");
    
    $notes = "Services: $services | Address: $customer_address | Phone: $customer_phone | Email: $customer_email | Instructions: $special_instructions";
    $pickup_datetime = $pickup_date . ' ' . $pickup_time;
    
    $stmt->bind_param("issdddss", $user_id, $shop_name, $services, $weight_kg, $total_price, $student_discount, $voucher_discount, $pickup_datetime, $notes);
    
    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        echo json_encode([
            'success' => true, 
            'message' => 'Order submitted successfully! Your order ID is #' . $order_id,
            'order_id' => $order_id
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to submit order. Error: ' . $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>

