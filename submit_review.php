<?php
header('Content-Type: application/json');
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

$shop = isset($_POST['shop']) ? trim($_POST['shop']) : '';
$rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

if ($shop === '' || $rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Missing or invalid data']);
    exit;
}

$user_id = null;
$user_name = null;
if (isset($_SESSION['user_email'])) {
    // try to get user id and name from users table
    $email = $_SESSION['user_email'];
    $stmt = $conn->prepare("SELECT id, full_name FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $user_id = (int)$row['id'];
        $user_name = $row['full_name'];
    }
    $stmt->close();
}

// if user not logged in, allow anonymous reviews with user_name 'Guest'
if ($user_name === null) {
    $user_name = 'Guest';
}

$insert = $conn->prepare("INSERT INTO reviews (shop_name, user_id, user_name, rating, comment) VALUES (?, ?, ?, ?, ?)");
$insert->bind_param('sisis', $shop, $user_id, $user_name, $rating, $comment);
if ($insert->execute()) {
    echo json_encode(['success' => true, 'message' => 'Review submitted']);
} else {
    echo json_encode(['success' => false, 'message' => 'DB error']);
}
$insert->close();
$conn->close();
?>
