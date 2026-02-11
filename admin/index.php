<?php
session_start();
include "config.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        // Query to find admin
        $sql = "SELECT admin_id, username FROM admins WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_username'] = $admin['username'];
            $stmt->close();
            
            // Log the login to audit_logs
            $log_sql = "INSERT INTO audit_logs (admin_id, action, date_time) VALUES (?, ?, NOW())";
            $log_stmt = $conn->prepare($log_sql);
            if ($log_stmt) {
                $action = 'LOGIN';
                $log_stmt->bind_param("is", $admin['admin_id'], $action);
                $log_stmt->execute();
                $log_stmt->close();
            }
            
            header("Location: admin.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Car Rentals</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <img src="Caloocars.png" alt="Car Rentals Logo" class="login-logo">
                <h1>Admin Login</h1>
                <p>CalooCars Management System</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Enter your username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>

                <button type="submit" class="login-btn">Login</button>
            </form>

            <div class="login-footer">
                <p>&copy; 2023 Car Rentals. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
