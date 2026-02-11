<?php
include "auth_check.php";
include "config.php"; 

// Fetch all users from database
$sql = "SELECT user_id, first_name, middle_initial, last_name, email FROM users ORDER BY user_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users — Car Rentals Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <nav>
        <div class="nav-container">
            <div class="logo-placeholder">
                <img src="Caloocars.png" alt="logo">
            </div>
            <ul>
                <li class="active"><a href="admin.php">Admin</a></li>
                <span class="admin-username">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            </ul>
            <ul class="nav-right">
                <li><a href="history.php">History</a></li>
                <li><a href="audit_log.php">Audit Log</a></li>
                <li class="admin-info">
                    <a href="logout.php" class="logout-btn">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="admin-container">
        <header class="admin-header">
            <h1>Users Management</h1>
            <p>Manage user accounts, permissions and activity.</p>
        </header>

        <div class="admin-wrapper">
            <aside class="sidebar">
                <h3>Categories</h3>
                <ul>
                    <li><a href="admin.php">Dashboard</a></li>
                    <li><a href="orders.php">Orders</a></li>
                    <li><a href="vehicles.php">Vehicles</a></li>
                    <li><a href="users.php" class="active">Users</a></li>
                    <li><a href="employees.php">Employees</a></li>
                    <li><a href="contactus.php">Contact Us</a></li>
                </ul>
            </aside>

            <section class="main-content">
                <section class="table-section">
                    <h2>Registered Users</h2>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>First Name</th>
                                <th>Middle Initial</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['middle_initial']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                    echo "<td>Active</td>";
                                    echo "<td><button class='btn small danger' onclick=\"deleteUser(" . $row['user_id'] . ")\">Delete</button></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No users found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </section>
            </section>
        </div>

        <footer class="admin-footer">
            <p>&copy; 2023 Car Rentals. Admin tools for site management.</p>
        </footer>
    </main>

    <script>
        function deleteUser(userId) {
            if (!confirm('Are you sure you want to delete this user?')) {
                return;
            }

            fetch('user_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=delete_user&user_id=${userId}`
            })
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.text();
            })
            .then(text => {
                if (!text) throw new Error('Empty response from server');
                return JSON.parse(text);
            })
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error deleting user: ' + err.message);
            });
        }
    </script>

    <?php
    $conn->close();
    ?>
</body>
</html>
