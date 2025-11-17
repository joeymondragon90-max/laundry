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
$success = '';
$is_ajax = isset($_POST['from_modal']) && $_POST['from_modal'] == '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'db.php';
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, full_name, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $fullname, $role);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $fullname;
            $_SESSION['user_id'] = $id;
            $_SESSION['user_role'] = $role;
            $success = "Login successful! Redirecting...";
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
            $error = "Invalid email or password.";
        }
    } else {
        $error = "No account found with that email.";
    }
    
    // If AJAX request and there's an error, return JSON
    if ($is_ajax && $error) {
        header('Content-Type: application/json');
        echo json_encode(['error' => $error]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In - Dry Zone Cantilan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="register-container" style="max-width:400px;margin:64px auto;">
    <div class="modal-content" style="box-shadow:none;">
        <h2 style="text-align:center;color:var(--primary);margin-bottom:20px;">Log In</h2>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success text-center" role="alert">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger text-center" role="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off" class="modal-form active">
            <div class="form-group">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Log In">
            </div>
            <span class="modal-link"><a href="register.php" style="color:var(--primary);text-decoration:underline;">Don't have an account? Register</a></span>
        </form>
    </div>
</div>
</body>
</html>