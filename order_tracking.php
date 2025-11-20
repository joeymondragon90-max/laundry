<?php
session_start();
require 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?return=customer_orders.php');
    exit;
}

$customer_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'Customer';

// Get all orders for this customer
$stmt = $conn->prepare("
    SELECT 
        id, shop_name, service_type, weight_kg, total_amount, 
        status, pickup_date, delivery_date, order_date, payment_method
    FROM orders
    WHERE customer_id = ?
    ORDER BY order_date DESC
    LIMIT 20
");

$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get status count
$status_stmt = $conn->prepare("
    SELECT status, COUNT(*) as count
    FROM orders
    WHERE customer_id = ?
    GROUP BY status
");
$status_stmt->bind_param("i", $customer_id);
$status_stmt->execute();
$status_result = $status_stmt->get_result();
$status_counts = [];
while ($row = $status_result->fetch_assoc()) {
    $status_counts[$row['status']] = $row['count'];
}
$status_stmt->close();

$conn->close();

// Status colors and icons
$status_info = [
    'pending' => ['color' => '#f59e0b', 'icon' => 'fa-clock', 'label' => 'Pending'],
    'processing' => ['color' => '#3b82f6', 'icon' => 'fa-cogs', 'label' => 'Processing'],
    'ready' => ['color' => '#10b981', 'icon' => 'fa-check-circle', 'label' => 'Ready'],
    'delivered' => ['color' => '#8b5cf6', 'icon' => 'fa-box', 'label' => 'Delivered'],
    'cancelled' => ['color' => '#ef4444', 'icon' => 'fa-times-circle', 'label' => 'Cancelled']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Dry Zone Cantilan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .orders-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .orders-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .orders-header h1 {
            font-size: 2rem;
            color: #0f172a;
            margin: 0;
        }

        .orders-stats {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .stat-card {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            text-align: center;
            min-width: 100px;
        }

        .stat-card strong {
            display: block;
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .stat-card small {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .orders-list {
            display: grid;
            gap: 20px;
        }

        .order-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .order-card:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .order-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: 20px;
        }

        .order-info h3 {
            margin: 0 0 10px 0;
            color: #0f172a;
            font-size: 1.1rem;
        }

        .order-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            color: #64748b;
            font-size: 0.9rem;
        }

        .detail-item strong {
            color: #334155;
            display: block;
            margin-bottom: 3px;
        }

        .order-status {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: bold;
            white-space: nowrap;
        }

        .status-pending { background: #fef3c7; color: #b45309; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-ready { background: #d1fae5; color: #065f46; }
        .status-delivered { background: #ede9fe; color: #5b21b6; }
        .status-cancelled { background: #fee2e2; color: #7f1d1d; }

        .order-timeline {
            padding: 20px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            display: flex;
            margin-bottom: 15px;
            position: relative;
        }

        .timeline-icon {
            position: absolute;
            left: -30px;
            width: 24px;
            height: 24px;
            background: #2563eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.7rem;
        }

        .timeline-item.past .timeline-icon {
            background: #10b981;
        }

        .timeline-item.current .timeline-icon {
            background: #f59e0b;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
            50% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
        }

        .timeline-content {
            flex: 1;
        }

        .timeline-content strong {
            color: #0f172a;
            display: block;
            margin-bottom: 3px;
        }

        .timeline-content small {
            color: #64748b;
        }

        .order-actions {
            padding: 20px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 10px;
        }

        .order-actions a, .order-actions button {
            flex: 1;
            padding: 10px 15px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .btn-track {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
        }

        .btn-track:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-chat {
            background: #f0f9ff;
            color: #2563eb;
            border: 1px solid #2563eb;
        }

        .btn-chat:hover {
            background: #e0f2fe;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #334155;
            margin-bottom: 10px;
        }

        .empty-state a {
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }

        .empty-state a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .order-header {
                flex-direction: column;
            }

            .order-details {
                grid-template-columns: 1fr 1fr;
            }

            .order-actions {
                flex-direction: column;
            }

            .orders-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="orders-container">
    <div class="orders-header">
        <div>
            <h1><i class="fas fa-shopping-bag"></i> My Orders</h1>
            <p style="color: #64748b; margin: 5px 0 0 0;">Welcome, <?php echo htmlspecialchars($user_name); ?></p>
        </div>
        <div class="orders-stats">
            <?php foreach ($status_counts as $status => $count): ?>
                <div class="stat-card">
                    <strong><?php echo $count; ?></strong>
                    <small><?php echo ucfirst($status); ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="orders-list">
        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No orders yet</h3>
                <p>You haven't placed any orders yet.</p>
                <a href="index.php"><i class="fas fa-arrow-right"></i> Browse laundry shops</a>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order): 
                $status = $order['status'];
                $status_data = $status_info[$status] ?? $status_info['pending'];
            ?>
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info" style="flex: 1;">
                            <h3>Order #<?php echo $order['id']; ?> - <?php echo htmlspecialchars($order['shop_name']); ?></h3>
                            <div class="order-details">
                                <div>
                                    <strong>Service:</strong>
                                    <?php echo htmlspecialchars($order['service_type'] ?? 'N/A'); ?>
                                </div>
                                <div>
                                    <strong>Weight:</strong>
                                    <?php echo $order['weight_kg'] ? $order['weight_kg'] . ' kg' : 'N/A'; ?>
                                </div>
                                <div>
                                    <strong>Estimated Total:</strong>
                                    ₱<?php echo number_format($order['total_amount'], 2); ?>
                                    <br><small style="color: #87BAC3; font-size: 0.85rem;"><i class="fas fa-info-circle"></i> Final amount confirmed at pickup/delivery</small>
                                </div>
                                <div>
                                    <strong>Date:</strong>
                                    <?php echo date('M d, Y', strtotime($order['order_date'])); ?>
                                </div>
                            </div>
                        </div>
                        <div class="order-status status-<?php echo $status; ?>">
                            <i class="fas <?php echo $status_data['icon']; ?>"></i>
                            <?php echo $status_data['label']; ?>
                        </div>
                    </div>

                    <div class="order-timeline">
                        <div class="timeline">
                            <?php
                            $timeline = [
                                'pending' => 'Order Placed',
                                'processing' => 'Being Processed',
                                'ready' => 'Ready for Pickup',
                                'delivered' => 'Delivered'
                            ];
                            $statuses = ['pending', 'processing', 'ready', 'delivered'];
                            $current_status_index = array_search($status, $statuses);
                            ?>
                            <?php foreach ($statuses as $index => $timeline_status): ?>
                                <div class="timeline-item <?php echo $index < $current_status_index ? 'past' : ($index === $current_status_index ? 'current' : ''); ?>">
                                    <div class="timeline-icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <strong><?php echo $timeline[$timeline_status]; ?></strong>
                                        <?php if ($timeline_status === 'pending'): ?>
                                            <small><?php echo date('M d, Y H:i', strtotime($order['order_date'])); ?></small>
                                        <?php elseif ($timeline_status === 'ready' && $order['delivery_date']): ?>
                                            <small><?php echo date('M d, Y H:i', strtotime($order['delivery_date'])); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="order-actions">
                        <a href="order_details.php?order_id=<?php echo $order['id']; ?>" class="btn-track">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        <a href="chat.php?shop=<?php echo urlencode($order['shop_name']); ?>" class="btn-chat">
                            <i class="fas fa-comments"></i> Chat Shop
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Dry Zone - Cantilan</h3>
                <p>Your directory for laundry services in Cantilan, Surigao del Sur.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Dry Zone Cantilan. All rights reserved.</p>
        </div>
    </div>
</footer>
</body>
</html>
