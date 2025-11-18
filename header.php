<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['user_email']);
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
?>
<header>
    <div class="container container-header header-row">
        <div class="logo" style="margin-left:24px;">
            <a href="index.php" class="photo-link" id="shopLogoLink">
                <img src="Dry Zone Logo.jpg" alt="Dry Zone Cantilan Logo" style="height:64px;width:auto;margin-right:12px;" id="shopLogo">
            </a>
            <h1>Dry Zone - Cantilan</h1>
        </div>
        <div class="nav-flex">
            <ul class="nav-links">
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="location.php"><i class="fas fa-map-marked-alt"></i> Location Map</a></li>
                <li><a href="services.php"><i class="fas fa-concierge-bell"></i> Services</a></li>
                <li><a href="about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
                <?php if ($is_logged_in): ?>
                    <?php if ($user_role === 'admin'): ?>
                        <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Admin</a></li>
                    <?php elseif ($user_role === 'seller'): ?>
                        <li><a href="seller_dashboard.php"><i class="fas fa-store"></i> Dashboard</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <?php if ($is_logged_in): ?>
                <div class="user-profile-dropdown">
                    <button class="profile-trigger" id="profileTrigger">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo htmlspecialchars($user_name); ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="profile-dropdown-menu" id="profileDropdown">
                        <div class="profile-header">
                            <div class="profile-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="profile-info">
                                <div class="profile-name"><?php echo htmlspecialchars($user_name); ?></div>
                                <div class="profile-email"><?php echo htmlspecialchars($user_email); ?></div>
                                <div class="profile-role">
                                    <span class="role-badge role-<?php echo $user_role; ?>"><?php echo ucfirst($user_role); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="profile-menu-divider"></div>
                        <?php if ($user_role === 'customer'): ?>
                        <a href="order_tracking.php" class="profile-menu-item">
                            <i class="fas fa-shopping-bag"></i>
                            <span>My Orders</span>
                        </a>
                        <?php endif; ?>
                        <div class="profile-menu-divider"></div>
                        <a href="logout.php" class="profile-menu-item logout-item">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn" style="margin-right: 10px;"><i class="fas fa-sign-in-alt"></i> Login</a>
                <a href="register.php" class="btn" style="background: var(--primary);"><i class="fas fa-user-plus"></i> Register</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<script>
// Profile dropdown toggle (can be included in any page)
document.addEventListener('DOMContentLoaded', function() {
    const profileTrigger = document.getElementById('profileTrigger');
    const userProfileDropdown = document.querySelector('.user-profile-dropdown');
    
    if (profileTrigger && userProfileDropdown) {
        // Toggle dropdown on click
        profileTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            userProfileDropdown.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userProfileDropdown.contains(e.target)) {
                userProfileDropdown.classList.remove('active');
            }
        });
        
        // Close dropdown on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                userProfileDropdown.classList.remove('active');
            }
        });
    }
});
</script>

