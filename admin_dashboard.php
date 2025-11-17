<?php
session_start();
require 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Get statistics
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$totalSellers = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'seller'")->fetch_assoc()['count'];
$totalCustomers = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer'")->fetch_assoc()['count'];
$recentUsers = $conn->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 10");

// Get all users for management
$allUsers = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Dry Zone Cantilan</title>
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
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
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
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th {
            background: var(--light);
            padding: 12px;
            text-align: left;
            color: var(--medium);
            font-weight: 600;
            border-bottom: 2px solid var(--accent);
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #e4eef1;
        }
        table tr:hover {
            background: var(--light);
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-admin {
            background: #e74c3c;
            color: white;
        }
        .badge-seller {
            background: var(--warning);
            color: white;
        }
        .badge-customer {
            background: var(--secondary);
            color: white;
        }
        .btn-action {
            padding: 6px 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            margin: 0 3px;
            transition: all 0.2s;
        }
        .btn-edit {
            background: var(--secondary);
            color: white;
        }
        .btn-edit:hover {
            background: var(--primary);
        }
        .btn-delete {
            background: var(--danger);
            color: white;
        }
        .btn-delete:hover {
            background: #c0392b;
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
        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="container">
            <div class="dashboard-header">
                <div>
                    <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
                    <p style="color: var(--medium); margin: 5px 0 0 0;">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
                </div>
                <div class="user-info">
                    <a href="index.html" class="btn" style="margin-right: 10px;"><i class="fas fa-home"></i> Home</a>
                    <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-users"></i></div>
                    <h3>Total Users</h3>
                    <p class="value"><?php echo $totalUsers; ?></p>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-store"></i></div>
                    <h3>Total Sellers</h3>
                    <p class="value"><?php echo $totalSellers; ?></p>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-user-friends"></i></div>
                    <h3>Total Customers</h3>
                    <p class="value"><?php echo $totalCustomers; ?></p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="fas fa-users-cog"></i> User Management</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = $allUsers->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $user['role']; ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <button class="btn-action btn-edit" onclick="editUser(<?php echo $user['id']; ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <button class="btn-action btn-delete" onclick="deleteUser(<?php echo $user['id']; ?>)">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editUser(id) {
            // TODO: Implement edit user functionality
            alert('Edit user functionality coming soon!');
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                // TODO: Implement delete user functionality
                alert('Delete user functionality coming soon!');
            }
        }
    </script>
</body>
</html>

