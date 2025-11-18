<?php
session_start();
require 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$customer_id = $_SESSION['user_id'];
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

// Get order details
$stmt = $conn->prepare("
    SELECT 
        id, shop_name, service_type, weight_kg, total_amount,
        student_discount, voucher_code, voucher_discount,
        payment_method, reference_number, status, 
        pickup_date, delivery_date, order_date, notes
    FROM orders
    WHERE id = ? AND customer_id = ?
");

$stmt->bind_param("ii", $order_id, $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    header('Location: order_tracking.php');
    exit;
}

// Get order status history
$history_stmt = $conn->prepare("
    SELECT new_status, changed_at, notes
    FROM order_status_history
    WHERE order_id = ?
    ORDER BY changed_at DESC
");

$history_stmt->bind_param("i", $order_id);
$history_stmt->execute();
$history_result = $history_stmt->get_result();
$history = $history_result->fetch_all(MYSQLI_ASSOC);
$history_stmt->close();

$conn->close();

$status_colors = [
    'pending' => '#f59e0b',
    'processing' => '#3b82f6',
    'ready' => '#10b981',
    'delivered' => '#8b5cf6',
    'cancelled' => '#ef4444'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #<?php echo $order['id']; ?> - Dry Zone Cantilan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .order-detail-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .order-detail-header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-detail-header h1 {
            margin: 0;
            font-size: 1.8rem;
        }

        .order-detail-header .status-badge {
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
        }

        .detail-section {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
        }

        .detail-section h3 {
            margin: 0 0 20px 0;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-section h3 i {
            color: #2563eb;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .detail-item {
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 15px;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #0f172a;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .timeline-section {
            background: #f8fafc;
            padding: 0;
            border: none;
        }

        .timeline-content {
            padding: 25px;
        }

        .timeline-item {
            display: flex;
            margin-bottom: 25px;
            position: relative;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-dot {
            width: 40px;
            height: 40px;
            background: #e2e8f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #64748b;
            font-weight: bold;
        }

        .timeline-item.completed .timeline-dot {
            background: #10b981;
            color: white;
        }

        .timeline-item.current .timeline-dot {
            background: #2563eb;
            color: white;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7); }
            50% { box-shadow: 0 0 0 15px rgba(37, 99, 235, 0); }
        }

        .timeline-body {
            flex: 1;
            margin-left: 20px;
        }

        .timeline-body strong {
            display: block;
            color: #0f172a;
            margin-bottom: 5px;
        }

        .timeline-body small {
            color: #64748b;
        }

        .timeline-note {
            background: white;
            padding: 10px;
            border-radius: 6px;
            margin-top: 8px;
            border-left: 3px solid #2563eb;
            color: #334155;
            font-size: 0.9rem;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .action-buttons a, .action-buttons button {
            flex: 1;
            padding: 12px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s;
        }

        .btn-back {
            background: #f1f5f9;
            color: #0f172a;
            border: 1px solid #cbd5e1;
        }

        .btn-back:hover {
            background: #e2e8f0;
        }

        .btn-chat-order {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
        }

        .btn-chat-order:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        @media (max-width: 600px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .order-detail-header {
                flex-direction: column;
                text-align: center;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="order-detail-container">
    <div class="order-detail-header">
        <div>
            <h1><i class="fas fa-receipt"></i> Order #<?php echo $order['id']; ?></h1>
            <p style="margin: 5px 0 0 0;">Placed on <?php echo date('F d, Y', strtotime($order['order_date'])); ?></p>
        </div>
        <div class="status-badge">
            <?php echo strtoupper($order['status']); ?>
        </div>
    </div>

    <!-- Shop & Service Info -->
    <div class="detail-section">
        <h3><i class="fas fa-store"></i> Service Details</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Shop Name</div>
                <div class="detail-value"><?php echo htmlspecialchars($order['shop_name']); ?></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Service Type</div>
                <div class="detail-value"><?php echo htmlspecialchars($order['service_type'] ?? 'N/A'); ?></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Weight</div>
                <div class="detail-value"><?php echo $order['weight_kg'] ? $order['weight_kg'] . ' kg' : 'N/A'; ?></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Status</div>
                <div class="detail-value" style="color: <?php echo $status_colors[$order['status']] ?? '#0f172a'; ?>;">
                    <?php echo ucfirst($order['status']); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Info -->
    <div class="detail-section">
        <h3><i class="fas fa-receipt"></i> Payment Details</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Base Amount</div>
                <div class="detail-value">₱<?php echo number_format($order['total_amount'] + $order['student_discount'] + $order['voucher_discount'], 2); ?></div>
            </div>
            <?php if ($order['student_discount'] > 0): ?>
                <div class="detail-item">
                    <div class="detail-label">Student Discount</div>
                    <div class="detail-value" style="color: #10b981;">-₱<?php echo number_format($order['student_discount'], 2); ?></div>
                </div>
            <?php endif; ?>
            <?php if ($order['voucher_discount'] > 0): ?>
                <div class="detail-item">
                    <div class="detail-label">Voucher (<?php echo htmlspecialchars($order['voucher_code']); ?>)</div>
                    <div class="detail-value" style="color: #10b981;">-₱<?php echo number_format($order['voucher_discount'], 2); ?></div>
                </div>
            <?php endif; ?>
            <div class="detail-item">
                <div class="detail-label">Total Amount</div>
                <div class="detail-value" style="font-size: 1.3rem; color: #2563eb;">₱<?php echo number_format($order['total_amount'], 2); ?></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Payment Method</div>
                <div class="detail-value"><?php echo htmlspecialchars($order['payment_method'] ?? 'N/A'); ?></div>
            </div>
            <?php if ($order['reference_number']): ?>
                <div class="detail-item">
                    <div class="detail-label">Reference #</div>
                    <div class="detail-value"><?php echo htmlspecialchars($order['reference_number']); ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Status Timeline -->
    <div class="detail-section timeline-section">
        <div class="timeline-content">
            <h3><i class="fas fa-history"></i> Order Timeline</h3>
            <?php if (!empty($history)): ?>
                <?php foreach ($history as $index => $item): ?>
                    <div class="timeline-item <?php echo $index === 0 ? 'current' : 'completed'; ?>">
                        <div class="timeline-dot">
                            <?php if ($index === 0): ?>
                                <i class="fas fa-hourglass-half"></i>
                            <?php else: ?>
                                <i class="fas fa-check"></i>
                            <?php endif; ?>
                        </div>
                        <div class="timeline-body">
                            <strong><?php echo ucfirst($item['new_status']); ?></strong>
                            <small><?php echo date('F d, Y \a\t H:i', strtotime($item['changed_at'])); ?></small>
                            <?php if ($item['notes']): ?>
                                <div class="timeline-note"><?php echo htmlspecialchars($item['notes']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #64748b;">No status updates yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="order_tracking.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
        <a href="chat.php?shop=<?php echo urlencode($order['shop_name']); ?>" class="btn-chat-order">
            <i class="fas fa-comments"></i> Chat with Shop
        </a>
    </div>
</div>

<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Dry Zone - Cantilan</h3>
                <p>Your directory for laundry services in Cantilan, Surigao del Sur.</p>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
