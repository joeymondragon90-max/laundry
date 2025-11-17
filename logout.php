<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Clear any cookies if they exist
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Return JSON response for AJAX requests or redirect for normal requests
if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
    header('Content-Type: application/json');
    echo json_encode(['success' => 'Logged out successfully']);
    exit();
}

// Redirect to overview page
header('Location: overview.html');
exit();
?>