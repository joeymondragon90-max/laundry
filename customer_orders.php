<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'customer';

// Get customer orders
$ordersQuery = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($ordersQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
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
            min-height: 100vh;
            background: linear-gradient(135deg, #e8f2f6 0%, #f5f7fa 100%);
            padding: 30px 20px;
        }
        .orders-header {
            background: white;
            padding: 25px 30px;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(39,77,96,0.08);
            margin-bottom: 30px;
        }
        .orders-header h1 {
            color: var(--primary);
            margin: 0;
            font-size: 1.8rem;
        }
        .orders-header p {
            color: var(--medium);
            margin: 5px 0 0 0;
        }
        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .order-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 12px rgba(39,77,96,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(39,77,96,0.12);
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .order-id {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
        }
        .order-date {
            color: var(--medium);
            font-size: 0.9rem;
        }
        .order-status {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-confirmed {
            background: #d1ecf1;
            color: #0c5460;
        }
        .status-processing {
            background: #cce5ff;
            color: #004085;
        }
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        .order-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .detail-label {
            font-size: 0.85rem;
            color: var(--medium);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .detail-value {
            font-size: 1rem;
            color: var(--dark);
        }
        .services-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 5px;
        }
        .service-badge {
            background: var(--light);
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            color: var(--primary);
            border: 1px solid var(--accent);
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(39,77,96,0.08);
        }
        .empty-state i {
            font-size: 4rem;
            color: var(--accent);
            margin-bottom: 20px;
        }
        .empty-state h3 {
            color: var(--medium);
            margin-bottom: 10px;
        }
        .empty-state p {
            color: var(--medium);
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .order-header {
                flex-direction: column;
            }
            .order-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="orders-container">
        <div class="container">
            <div class="orders-header">
                <h1><i class="fas fa-shopping-bag"></i> My Orders</h1>
                <p>View and track all your laundry service orders</p>
            </div>
            
            <?php if ($orders->num_rows > 0): ?>
                <div class="orders-list">
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <div>
                                    <div class="order-id">Order #<?php echo $order['id']; ?></div>
                                    <div class="order-date">
                                        <i class="fas fa-calendar"></i> <?php echo date('F d, Y', strtotime($order['created_at'])); ?>
                                    </div>
                                </div>
                                <span class="order-status status-<?php echo $order['status']; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </div>
                            
                            <div class="order-details">
                                <div class="detail-item">
                                    <span class="detail-label">Shop Name</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($order['shop_name']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Services</span>
                                    <div class="services-list">
                                        <?php 
                                        $services = explode(', ', $order['services']);
                                        foreach ($services as $service): 
                                        ?>
                                            <span class="service-badge"><?php echo htmlspecialchars($service); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Items</span>
                                    <span class="detail-value"><?php echo $order['item_count']; ?> items</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Urgency</span>
                                    <span class="detail-value"><?php echo ucfirst($order['urgency']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Pickup Date & Time</span>
                                    <span class="detail-value">
                                        <i class="fas fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($order['pickup_date'])); ?>
                                        <br>
                                        <i class="fas fa-clock"></i> <?php echo date('g:i A', strtotime($order['pickup_time'])); ?>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Address</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($order['customer_address']); ?></span>
                                </div>
                                <?php if (!empty($order['special_instructions'])): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Special Instructions</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($order['special_instructions']); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if ($order['total_price']): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Total Price</span>
                                    <span class="detail-value" style="font-weight: 700; color: var(--primary);">₱<?php echo number_format($order['total_price'], 2); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No Orders Yet</h3>
                    <p>You haven't placed any orders yet. Start by placing your first order!</p>
                    <a href="orderform.php" class="btn"><i class="fas fa-plus"></i> Place an Order</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

