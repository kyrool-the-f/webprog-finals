<?php
include "auth_check.php";
include "config.php"; 

// Fetch all employees from database
$sql = "SELECT contact_us_id, full_name, email, contact_number, service, message, submitted_at FROM contact_us_forms ORDER BY contact_us_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us — Car Rentals Admin</title>
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
            <h1>Contact Us Forms</h1>
            <p>Manage contact us form submissions.</p>
        </header>

        <div class="admin-wrapper">
            <aside class="sidebar">
                <h3>Categories</h3>
                <ul>
                    <li><a href="admin.php">Dashboard</a></li>
                    <li><a href="orders.php">Orders</a></li>
                    <li><a href="vehicles.php">Vehicles</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="employees.php">Employees</a></li>
                    <li><a href="contactus.php" class="active">Contact Us</a></li>
                </ul>
            </aside>

            <section class="main-content">
                <section class="table-section">
                    <h2>Registered Contact Us Forms</h2>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Contact Form ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                                <th>Service</th>
                                <th>Message</th>
                                <th>Submitted At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['contact_us_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['contact_number']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['service']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['submitted_at']) . "</td>";
                                    echo "<td><button class='btn small danger' onclick=\"deleteContact(" . $row['contact_us_id'] . ")\">Remove</button></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' style='text-align: center;'>No contact us forms found</td></tr>";
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
        function deleteContact(contactId) {
            if (!confirm('Are you sure you want to delete this contact form?')) {
                return;
            }

            fetch('contactus_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=delete_contact&contact_us_id=${contactId}`
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
                alert('Error deleting contact form: ' + err.message);
            });
        }
    </script>

    <?php
    $conn->close();
    ?>
</body>
</html>
