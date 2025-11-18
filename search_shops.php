<?php
header('Content-Type: application/json');

require 'db.php';

// Get filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 10000;
$min_rating = isset($_GET['min_rating']) ? (float)$_GET['min_rating'] : 0;
$service_type = isset($_GET['service']) ? trim($_GET['service']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$sort_by = isset($_GET['sort']) ? trim($_GET['sort']) : 'rating'; // rating, price, name

// Validate sort parameter
$valid_sorts = ['rating', 'price_asc', 'price_desc', 'name', 'popular'];
if (!in_array($sort_by, $valid_sorts)) {
    $sort_by = 'rating';
}

// Base query
$query = "SELECT 
    shop_name, 
    location, 
    description, 
    rating, 
    num_reviews,
    primary_service,
    avg_price,
    has_pickup_delivery
FROM shop_directory
WHERE 1=1";

$params = [];
$types = '';

// Add search filter (by name or location)
if (!empty($search)) {
    $search_term = "%$search%";
    $query .= " AND (shop_name LIKE ? OR location LIKE ? OR description LIKE ?)";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= 'sss';
}

// Add service type filter
if (!empty($service_type)) {
    $query .= " AND primary_service LIKE ?";
    $params[] = "%$service_type%";
    $types .= 's';
}

// Add location filter
if (!empty($location)) {
    $query .= " AND location LIKE ?";
    $params[] = "%$location%";
    $types .= 's';
}

// Add rating filter
if ($min_rating > 0) {
    $query .= " AND rating >= ?";
    $params[] = $min_rating;
    $types .= 'd';
}

// Add price filter
$query .= " AND avg_price BETWEEN ? AND ?";
$params[] = $min_price;
$params[] = $max_price;
$types .= 'dd';

// Add sorting
$order_map = [
    'rating' => 'ORDER BY rating DESC, num_reviews DESC',
    'price_asc' => 'ORDER BY avg_price ASC',
    'price_desc' => 'ORDER BY avg_price DESC',
    'name' => 'ORDER BY shop_name ASC',
    'popular' => 'ORDER BY num_reviews DESC, rating DESC'
];

$query .= ' ' . $order_map[$sort_by];
$query .= ' LIMIT 50';

// Execute query
$stmt = $conn->prepare($query);

if ($params && $types) {
    $stmt->bind_param($types, ...$params);
}

if (!$stmt->execute()) {
    echo json_encode([
        'success' => false,
        'message' => 'Query failed: ' . $stmt->error
    ]);
    exit;
}

$result = $stmt->get_result();
$shops = [];

while ($row = $result->fetch_assoc()) {
    $shops[] = [
        'name' => $row['shop_name'],
        'location' => $row['location'],
        'description' => $row['description'],
        'rating' => (float)$row['rating'],
        'reviews' => (int)$row['num_reviews'],
        'service' => $row['primary_service'],
        'avg_price' => (float)$row['avg_price'],
        'pickup_delivery' => (bool)$row['has_pickup_delivery']
    ];
}

$stmt->close();
$conn->close();

echo json_encode([
    'success' => true,
    'count' => count($shops),
    'shops' => $shops,
    'filters' => [
        'search' => $search,
        'min_price' => $min_price,
        'max_price' => $max_price,
        'min_rating' => $min_rating,
        'service' => $service_type,
        'location' => $location,
        'sort' => $sort_by
    ]
]);
?>
