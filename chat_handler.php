<?php
header('Content-Type: application/json');
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$customer_id = $_SESSION['user_id'];
$sender_name = $_SESSION['user_name'] ?? 'Anonymous';
$shop_name = trim($_POST['shop_name'] ?? '');
$message = trim($_POST['message'] ?? '');
$sender_type = $_POST['sender_type'] ?? 'customer'; // 'customer' or 'shop'

// Validate inputs
if (empty($shop_name) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Message and shop name are required']);
    exit;
}

// Prevent XSS
$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
$shop_name = htmlspecialchars($shop_name, ENT_QUOTES, 'UTF-8');
$sender_name = htmlspecialchars($sender_name, ENT_QUOTES, 'UTF-8');

// Insert message into database
$stmt = $conn->prepare("
    INSERT INTO chat_messages (customer_id, shop_name, sender_type, sender_name, message)
    VALUES (?, ?, ?, ?, ?)
");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("issss", $customer_id, $shop_name, $sender_type, $sender_name, $message);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Message sent successfully',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send message: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
