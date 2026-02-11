<?php
include "auth_check.php";
include "config.php"; 

// Set charset to UTF-8
$conn->set_charset("utf8");

// Fetch audit logs from database with admin information
$sql = "SELECT al.logs_id, al.admin_id, a.username, al.action, al.date_time
        FROM audit_logs al
        LEFT JOIN admins a ON al.admin_id = a.admin_id
        ORDER BY al.logs_id DESC";

$result = $conn->query($sql);
$logs = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Log — Car Rentals</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="audit_log.css">
</head>
<body>
    <nav>
        <div class="nav-container">
            <div class="logo-placeholder">
                <img src="Caloocars.png" alt="rental">
            </div>
            <ul>
                <li><a href="admin.php">Admin</a></li>
                <span class="admin-username">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            </ul>
            <ul class="nav-right">
                <li><a href="history.php">History</a></li>
                <li class="active"><a href="audit_log.php">Audit Log</a></li>
                <li class="admin-info">
                    <a href="logout.php" class="logout-btn">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="admin-container">
        <header class="admin-header">
            <h1>Audit Log</h1>
            <p>Track all system activities and administrative actions.</p>
        </header>
            <section class="main-content">
                <section class="table-section">
                    <h2>System Activity Log</h2>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Log ID</th>
                                <th>Admin ID</th>
                                <th>Username</th>
                                <th>Action</th>
                                <th>Date and Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($logs) > 0) {
                                foreach ($logs as $log) {
                                    $actionBadge = $log['action'] === 'LOGIN' ? '<span style="color: green; font-weight: bold;">LOGIN</span>' : '<span style="color: red; font-weight: bold;">LOGOUT</span>';
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($log['logs_id']); ?></td>
                                        <td><?php echo htmlspecialchars($log['admin_id']); ?></td>
                                        <td><?php echo htmlspecialchars($log['username']); ?></td>
                                        <td><?php echo $actionBadge; ?></td>
                                        <td><?php echo htmlspecialchars($log['date_time']); ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="5" style="text-align: center; padding: 20px;">No audit logs found</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </section>
            </section>

        <footer class="admin-footer">
            <p>&copy; 2023 Car Rentals. Admin tools for site management.</p>
        </footer>
    </main>

    <?php
    $conn->close();
    ?>
</body>
</html>
