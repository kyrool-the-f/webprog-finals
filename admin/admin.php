<?php
include "auth_check.php"; 
include "config.php"; 
// Fetch recent orders
$orders_sql = "SELECT form_id, full_name, vehicle_type, pickup_datetime
              FROM orders_forms 
              ORDER BY form_id DESC 
              LIMIT 5";
$orders_result = $conn->query($orders_sql);
$orders = array();
if ($orders_result->num_rows > 0) {
    while($row = $orders_result->fetch_assoc()) {
        $orders[] = $row;
    }
}

// Count total bookings
$total_bookings_sql = "SELECT COUNT(*) as total FROM orders_forms";
$total_bookings_result = $conn->query($total_bookings_sql);
$total_bookings = 0;
if ($total_bookings_result->num_rows > 0) {
    $row = $total_bookings_result->fetch_assoc();
    $total_bookings = $row['total'];
}

// Count active vehicles
$active_vehicles_sql = "SELECT COUNT(*) as total FROM cars WHERE status = 'Available'";
$active_vehicles_result = $conn->query($active_vehicles_sql);
$active_vehicles = 0;
if ($active_vehicles_result->num_rows > 0) {
    $row = $active_vehicles_result->fetch_assoc();
    $active_vehicles = $row['total'];
}

// Count registered users
$registered_users_sql = "SELECT COUNT(*) as total FROM users";
$registered_users_result = $conn->query($registered_users_sql);
$registered_users = 0;
if ($registered_users_result->num_rows > 0) {
    $row = $registered_users_result->fetch_assoc();
    $registered_users = $row['total'];
}

// Fetch vehicle statuses
$cars_sql = "SELECT c.cars_id, cm.car_model, cm.seater_model, c.status, c.plate_number
            FROM cars c
            LEFT JOIN car_model cm ON c.model_name = cm.model_id
            ORDER BY c.cars_id
            LIMIT 10";
$cars_result = $conn->query($cars_sql);
$cars = array();
if ($cars_result->num_rows > 0) {
    while($row = $cars_result->fetch_assoc()) {
        $cars[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Car Rentals</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <nav>
        <div class="nav-container">
            <div class="logo-placeholder">
                <img src="Caloocars.png" alt="rental">
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
            <h1>Admin Dashboard</h1>
            <p>Overview of bookings, vehicles and users.</p>
        </header>

        <div class="admin-wrapper">
            <aside class="sidebar">
                <h3>Categories</h3>
                <ul>
                    <li><a href="admin.php" class="active">Dashboard</a></li>
                    <li><a href="orders.php">Orders</a></li>
                    <li><a href="vehicles.php">Vehicles</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="employees.php">Employees</a></li>
                    <li><a href="contactus.php">Contact Us</a></li>
                </ul>
            </aside>

            <section class="main-content">
                <section class="cards">
                    <div class="card">
                        <h3>Total Bookings</h3>
                        <p class="large"><?php echo $total_bookings; ?></p>
                    </div>
                    <div class="card">
                        <h3>Active Vehicles</h3>
                        <p class="large"><?php echo $active_vehicles; ?></p>
                    </div>
                    <div class="card">
                        <h3>Registered Users</h3>
                        <p class="large"><?php echo $registered_users; ?></p>
                    </div>
                </section>

                <div class="two-column">
            <div class="column-left">
                <section class="table-section">
                    <h2>Orders</h2>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Vehicle</th>
                                <th>Pickup Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($orders) > 0) {
                                foreach ($orders as $order) {
                                    echo "<tr>";
                                    echo "<td>#" . htmlspecialchars($order['form_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($order['full_name']) . "</td>";
                                    echo "<td>" . ($order['vehicle_type'] ?: 'N/A') . "</td>";
                                    echo "<td>" . htmlspecialchars(date('Y-m-d', strtotime($order['pickup_datetime']))) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No orders found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </section>
            </div>

            <div class="column-right">
                <section class="table-section">
                    <h2>Cars Status</h2>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Vehicle ID</th>
                                <th>Model</th>
                                <th>Plate</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($cars) > 0) {
                                foreach ($cars as $car) {
                                    $model = !empty($car['car_model']) ? htmlspecialchars($car['car_model']) : 'N/A';
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($car['cars_id']) . "</td>";
                                    echo "<td>" . $model . "</td>";
                                    echo "<td>" . htmlspecialchars($car['plate_number']) . "</td>";
                                    echo "<td>" . htmlspecialchars($car['status']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No vehicles found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </section>
            </div>
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
