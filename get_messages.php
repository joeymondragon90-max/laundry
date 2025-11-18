<?php
header('Content-Type: application/json');
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$customer_id = $_SESSION['user_id'];
$shop_name = trim($_GET['shop_name'] ?? '');

if (empty($shop_name)) {
    echo json_encode(['success' => false, 'message' => 'Shop name is required']);
    exit;
}

$shop_name = htmlspecialchars($shop_name, ENT_QUOTES, 'UTF-8');

// Get last 50 messages for this chat
$stmt = $conn->prepare("
    SELECT id, sender_type, sender_name, message, timestamp, is_read
    FROM chat_messages
    WHERE customer_id = ? AND shop_name = ?
    ORDER BY timestamp ASC
    LIMIT 50
");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("is", $customer_id, $shop_name);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'id' => $row['id'],
        'sender_type' => $row['sender_type'],
        'sender_name' => $row['sender_name'],
        'message' => htmlspecialchars_decode($row['message']),
        'timestamp' => $row['timestamp'],
        'is_read' => (bool)$row['is_read']
    ];
}

// Mark messages as read
$update_stmt = $conn->prepare("
    UPDATE chat_messages
    SET is_read = TRUE
    WHERE customer_id = ? AND shop_name = ? AND sender_type = 'shop' AND is_read = FALSE
");

if ($update_stmt) {
    $update_stmt->bind_param("is", $customer_id, $shop_name);
    $update_stmt->execute();
    $update_stmt->close();
}

echo json_encode([
    'success' => true,
    'messages' => $messages,
    'count' => count($messages)
]);

$stmt->close();
$conn->close();
?>
