<?php
session_start();
require 'db.php';

// Check if user is logged in and is seller
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'seller') {
    header('Location: login.php');
    exit();
}

// Get seller statistics (placeholder - you can add orders table later)
$sellerId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - Dry Zone Cantilan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #e8f2f6 0%, #f5f7fa 100%);
            padding: 20px;
        }
        .dashboard-header {
            background: white;
            padding: 25px 30px;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(39,77,96,0.08);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .dashboard-header h1 {
            color: var(--primary);
            margin: 0;
            font-size: 1.8rem;
        }
        .dashboard-header .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(39,77,96,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(39,77,96,0.12);
        }
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
            background: linear-gradient(135deg, var(--warning) 0%, #f39c12 100%);
            color: white;
        }
        .stat-card h3 {
            color: var(--medium);
            font-size: 0.95rem;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-card .value {
            color: var(--primary);
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }
        .content-section {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(39,77,96,0.08);
            margin-bottom: 30px;
        }
        .content-section h2 {
            color: var(--primary);
            margin: 0 0 20px 0;
            font-size: 1.5rem;
        }
        .order-card {
            background: var(--light);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            border-left: 4px solid var(--secondary);
            transition: all 0.2s;
        }
        .order-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(39,77,96,0.1);
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }
        .order-id {
            font-weight: 700;
            color: var(--primary);
        }
        .order-status {
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-pending {
            background: #f39c12;
            color: white;
        }
        .status-processing {
            background: var(--secondary);
            color: white;
        }
        .status-completed {
            background: #27ae60;
            color: white;
        }
        .status-cancelled {
            background: var(--danger);
            color: white;
        }
        .order-details {
            color: var(--medium);
            margin: 10px 0;
        }
        .order-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .btn-action {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-update {
            background: var(--secondary);
            color: white;
        }
        .btn-update:hover {
            background: var(--primary);
        }
        .btn-view {
            background: var(--accent);
            color: white;
        }
        .btn-view:hover {
            background: var(--secondary);
        }
        .btn-logout {
            background: var(--danger);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-logout:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--medium);
        }
        .empty-state i {
            font-size: 4rem;
            color: var(--accent);
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--medium);
            font-weight: 600;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e4eef1;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--secondary);
        }
        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="container">
            <div class="dashboard-header">
                <div>
                    <h1><i class="fas fa-store"></i> Seller Dashboard</h1>
                    <p style="color: var(--medium); margin: 5px 0 0 0;">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
                </div>
                <div class="user-info">
                    <a href="index.html" class="btn" style="margin-right: 10px;"><i class="fas fa-home"></i> Home</a>
                    <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                    <h3>Total Orders</h3>
                    <p class="value">0</p>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-clock"></i></div>
                    <h3>Pending Orders</h3>
                    <p class="value">0</p>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                    <h3>Completed Orders</h3>
                    <p class="value">0</p>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-peso-sign"></i></div>
                    <h3>Total Revenue</h3>
                    <p class="value">₱0</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="fas fa-shopping-bag"></i> Recent Orders</h2>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No orders yet</h3>
                    <p>Orders will appear here once customers place them.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="fas fa-cog"></i> Shop Settings</h2>
                <form>
                    <div class="form-group">
                        <label for="shop_name">Shop Name</label>
                        <input type="text" id="shop_name" name="shop_name" placeholder="Enter your shop name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="shop_description">Shop Description</label>
                        <textarea id="shop_description" name="shop_description" rows="4" placeholder="Describe your laundry services"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="shop_location">Location</label>
                        <input type="text" id="shop_location" name="shop_location" placeholder="Enter shop location">
                    </div>
                    <div class="form-group">
                        <label for="shop_phone">Contact Number</label>
                        <input type="tel" id="shop_phone" name="shop_phone" placeholder="Enter contact number">
                    </div>
                    <button type="submit" class="btn" style="width: 100%;">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // TODO: Add order management functionality
        console.log('Seller dashboard loaded');
    </script>
</body>
</html>

