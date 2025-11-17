<?php
session_start();

// If already logged in, redirect based on role
if (isset($_SESSION['user_email'])) {
    $role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'customer';
    if ($role === 'admin') {
        header('Location: admin_dashboard.php');
        exit();
    } elseif ($role === 'seller') {
        header('Location: seller_dashboard.php');
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
}

$error = '';
$errors = array();
$success = '';
$is_logged_in = isset($_SESSION['user_email']);
$is_ajax = isset($_POST['from_modal']) && $_POST['from_modal'] == '1';

if (!$is_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'db.php';
    $fullname = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $passwordRepeat = $_POST["repeat_password"];
    $role = isset($_POST['role']) ? $_POST['role'] : 'customer';

    if (empty($fullname) || empty($email) || empty($password) || empty($passwordRepeat)) {
        $errors[] = "All fields are required.";
    }
    if ($password !== $passwordRepeat) {
        $errors[] = "Passwords do not match.";
    }
    if (!in_array($role, ['customer', 'seller', 'admin'])) {
        $role = 'customer'; // Default to customer if invalid role
    }
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "This email is already registered. Please log in or use another email.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $insertStmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
            $insertStmt->bind_param("ssss", $fullname, $email, $hashedPassword, $role);

            if ($insertStmt->execute()) {
                $userId = $insertStmt->insert_id;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_name'] = $fullname;
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_role'] = $role;
                $success = "Registration successful! Redirecting...";
                $_POST = array();
                
                // If AJAX request, return JSON
                if ($is_ajax) {
                    header('Content-Type: application/json');
                    $redirectUrl = 'index.php';
                    if ($role === 'admin') {
                        $redirectUrl = 'admin_dashboard.php';
                    } elseif ($role === 'seller') {
                        $redirectUrl = 'seller_dashboard.php';
                    }
                    echo json_encode(['success' => $success, 'redirect' => $redirectUrl]);
                    exit();
                }
                
                // Regular form submission - redirect based on role
                if ($role === 'admin') {
                    header('Location: admin_dashboard.php');
                    exit();
                } elseif ($role === 'seller') {
                    header('Location: seller_dashboard.php');
                    exit();
                } else {
                    header('Location: index.php');
                    exit();
                }
            } else {
                $error = "An error occurred while registering. Please try again.";
            }
            $insertStmt->close();
        }
        $stmt->close();
    }
    
    // If AJAX request and there are errors, return JSON
    if ($is_ajax && (!empty($errors) || $error)) {
        header('Content-Type: application/json');
        if (!empty($errors)) {
            echo json_encode(['errors' => $errors]);
        } else {
            echo json_encode(['error' => $error]);
        }
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - Dry Zone Cantilan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="register-container" style="max-width:400px;margin:64px auto;">
    <div class="modal-content" style="box-shadow:none;">
        <button type="button" class="modal-close-btn" id="modalCloseBtn" aria-label="Close">&times;</button>
        <h2 style="text-align:center;color:var(--primary);margin-bottom:20px;">Register</h2>
        <?php if ($is_logged_in): ?>
            <div class="alert alert-warning text-center" role="alert">
                You have already registered and logged in with this account.
            </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success text-center" role="alert">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?php foreach ($errors as $err) echo htmlspecialchars($err) . "<br>"; ?>
            </div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger text-center" role="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="register.php" method="post" <?php if ($is_logged_in) echo 'style="pointer-events:none;opacity:0.6;"'; ?>>
            <div class="form-group">
                <input type="text" class="form-control" name="full_name" placeholder="Full Name" required value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password" required>
            </div>
            <div class="form-group">
                <select class="form-control" name="role" required>
                    <option value="customer" <?php echo (isset($_POST['role']) && $_POST['role'] === 'customer') ? 'selected' : ''; ?>>Customer</option>
                    <option value="seller" <?php echo (isset($_POST['role']) && $_POST['role'] === 'seller') ? 'selected' : ''; ?>>Seller</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Register" name="submit">
            <span class="modal-link"><a href="login.php" style="color:var(--primary);text-decoration:underline;">Already have an account? Log In</a></span>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var closeBtn = document.getElementById('modalCloseBtn');
    var registerContainer = document.querySelector('.register-container');
    if (closeBtn && registerContainer) {
        closeBtn.addEventListener('click', function() {
            registerContainer.style.display = 'none';
        });
    }
});
</script>
</body>
</html>