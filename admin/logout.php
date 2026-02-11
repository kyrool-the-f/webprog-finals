<?php
session_start();

// Log the logout to audit_logs before destroying session
if (isset($_SESSION['admin_id'])) {
    include "config.php";
    $log_sql = "INSERT INTO audit_logs (admin_id, action, date_time) VALUES (?, ?, NOW())";
    $log_stmt = $conn->prepare($log_sql);
    if ($log_stmt) {
        $admin_id = $_SESSION['admin_id'];
        $action = 'LOGOUT';
        $log_stmt->bind_param("is", $admin_id, $action);
        $log_stmt->execute();
        $log_stmt->close();
    }
    $conn->close();
}

session_destroy();
header("Location: index.php");
exit();
?>
