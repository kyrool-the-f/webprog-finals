<?php
include "auth_check.php";
include "config.php"; 

// Set charset to UTF-8
$conn->set_charset("utf8");

// Fetch orders from database with vehicle information
$sql = "SELECT form_id, full_name, email, contact_number, assigned_car, service_type, 
        pickup_location, pickup_datetime, drivers_license, return_location, return_datetime, status,
        cm.car_model as vehicle_name
        FROM orders_forms of
        LEFT JOIN car_model cm ON of.vehicle_type = cm.model_id
        ORDER BY form_id DESC";

$result = $conn->query($sql);
$orders = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders — Car Rentals Admin</title>
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
            <h1>Orders Management</h1>
            <p>View, manage and track all customer orders.</p>
        </header>

        <div class="admin-wrapper">
            <aside class="sidebar">
                <h3>Categories</h3>
                <ul>
                    <li><a href="admin.php">Dashboard</a></li>
                    <li><a href="orders.php" class="active">Orders</a></li>
                    <li><a href="vehicles.php">Vehicles</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="employees.php">Employees</a></li>
                    <li><a href="contactus.php">Contact Us</a></li>
                </ul>
            </aside>

            <section class="main-content">
                <section class="table-section">
                    <h2>All Orders</h2>
                    <table class="admin-table" data-table="orders_forms">
                        <thead>
                            <tr>
                                <th data-column="form_id">Form ID</th>
                                <th data-column="full_name">Full Name</th>
                                <th data-column="email">Email</th>
                                <th data-column="contact_number">Contact #</th>
                                
                                <th data-column="vehicle_type">Vehicle</th>
                                <th data-column="assigned_car">Assigned Vehicle</th>
                                <th data-column="service_type">Service Type</th>
                                <th data-column="pickup_location">Pickup Location</th>
                                <th data-column="pickup_datetime">Pickup DateTime</th>
                                <th data-column="return_location">Return Location</th>
                                <th data-column="return_datetime">Return DateTime</th>
                                <th data-column="drivers_license">Driver's License</th>
                                <th data-column="status">Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($orders) > 0) {
                                $record_id = 1;
                                foreach ($orders as $order) {
                                    $vehicle = !empty($order['vehicle_name']) ? htmlspecialchars($order['vehicle_name']) : 'N/A';
                                    ?>
                                    <tr data-record-id="<?php echo $record_id; ?>" data-db-id="<?php echo htmlspecialchars($order['form_id']); ?>">
                                        <td data-column="form_id" data-value="<?php echo htmlspecialchars($order['form_id']); ?>"><?php echo htmlspecialchars($order['form_id']); ?></td>
                                        <td data-column="full_name" data-value="<?php echo htmlspecialchars($order['full_name']); ?>"><?php echo htmlspecialchars($order['full_name']); ?></td>
                                        <td data-column="email" data-value="<?php echo htmlspecialchars($order['email']); ?>"><?php echo htmlspecialchars($order['email']); ?></td>
                                        <td data-column="contact_number" data-value="<?php echo htmlspecialchars($order['contact_number']); ?>"><?php echo htmlspecialchars($order['contact_number']); ?></td>
                                        <td data-column="vehicle_name" data-value="<?php echo $vehicle; ?>"><?php echo $vehicle; ?></td>
                                        <td data-column="assigned_car" data-value="<?php echo htmlspecialchars($order['assigned_car']); ?>"><?php echo htmlspecialchars($order['assigned_car']); ?></td>
                                        <td data-column="service_type" data-value="<?php echo htmlspecialchars($order['service_type']); ?>"><?php echo htmlspecialchars($order['service_type']); ?></td>
                                        <td data-column="pickup_location" data-value="<?php echo htmlspecialchars($order['pickup_location']); ?>"><?php echo htmlspecialchars($order['pickup_location']); ?></td>
                                        <td data-column="pickup_datetime" data-value="<?php echo htmlspecialchars($order['pickup_datetime']); ?>"><?php echo htmlspecialchars($order['pickup_datetime']); ?></td>
                                        <td data-column="return_location" data-value="<?php echo htmlspecialchars($order['return_location']); ?>"><?php echo htmlspecialchars($order['return_location']); ?></td>
                                        <td data-column="return_datetime" data-value="<?php echo htmlspecialchars($order['return_datetime']); ?>"><?php echo htmlspecialchars($order['return_datetime']); ?></td>
                                        <td data-column="drivers_license"><button class="btn small" onclick="viewLicense('<?php echo htmlspecialchars($order['drivers_license']); ?>')">View License</button></td>
                                        <td data-column="status" data-value="<?php echo htmlspecialchars($order['status']); ?>">
                                            <select class="status-select" onchange="updateStatus(<?php echo $order['form_id']; ?>, this.value)">
                                                <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="Completed" <?php echo $order['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                <option value="Cancelled" <?php echo $order['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class="btn small" onclick="assignVehicle(<?php echo $order['form_id']; ?>)">Assign Car</button>
                                            <button class="btn small danger" onclick="removeOrder(this, <?php echo $order['form_id']; ?>)">Remove</button>
                                        </td>
                                    </tr>
                                    <?php
                                    $record_id++;
                                }
                            } else {
                                echo '<tr><td colspan="11" style="text-align: center; padding: 20px;">No orders found</td></tr>';
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

    <!-- Assign Vehicle Modal -->
    <div id="assign-modal" class="modal-overlay">
        <div class="modal-box">
            <a href="#" class="modal-close">&times;</a>
            <h2>Assign Vehicle</h2>
            <form id="assign-form">
                <div class="form-group">
                    <label for="order-id">Order ID:</label>
                    <input type="text" id="order-id" readonly>
                </div>
                <div class="form-group">
                    <label for="vehicle-select">Select Vehicle:</label>
                    <select id="vehicle-select" required>
                        <option value="">Choose an available vehicle...</option>
                    </select>
                </div>
                <button type="submit" class="signin-btn">Assign Vehicle</button>
            </form>
        </div>
    </div>

    <script>
        let currentOrderId = null;

        function assignVehicle(formId) {
            currentOrderId = formId;
            document.getElementById('order-id').value = formId;
            
            // Fetch available vehicles
            fetch('order_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=get_available_cars'
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
                const select = document.getElementById('vehicle-select');
                select.innerHTML = '<option value="">Choose an available vehicle...</option>';
                
                if (data.success && data.cars.length > 0) {
                    data.cars.forEach(car => {
                        const option = document.createElement('option');
                        option.value = car.cars_id;
                        option.textContent = `${car.plate_number} (ID: ${car.cars_id})`;
                        select.appendChild(option);
                    });
                    document.getElementById('assign-modal').style.display = 'flex';
                } else {
                    alert('No vehicles available');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error fetching vehicles: ' + err.message);
            });
        }

        document.getElementById('assign-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const carId = document.getElementById('vehicle-select').value;
            if (!carId) {
                alert('Please select a vehicle');
                return;
            }

            fetch('order_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=assign_vehicle&form_id=${currentOrderId}&car_id=${carId}`
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
                    document.getElementById('assign-modal').style.display = 'none';
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error assigning vehicle: ' + err.message);
            });
        });

        function updateStatus(formId, status) {
            fetch('order_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=update_status&form_id=${formId}&status=${status}`
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
                alert('Error updating status: ' + err.message);
            });
        }

        function removeOrder(buttonElement, formId) {
            // Find the row containing this button
            const row = buttonElement.closest('tr');
            
            // Find the status select in this row
            const statusSelect = row.querySelector('.status-select');
            const status = statusSelect ? statusSelect.value : 'Cancelled';
            
            if (!confirm(`Are you sure you want to remove this order with status: ${status}?`)) {
                return;
            }

            fetch('order_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=cancel_order&form_id=${formId}&status=${encodeURIComponent(status)}`
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
                alert('Error removing order: ' + err.message);
            });
        }

        function cancelOrder(formId) {
            if (!confirm('Are you sure you want to cancel this order?')) {
                return;
            }

            fetch('order_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=cancel_order&form_id=${formId}&status=Cancelled`
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
                alert('Error cancelling order: ' + err.message);
            });
        }

        function viewLicense(licenseData) {
            alert('License file: ' + licenseData.substring(0, 50) + '...');
        }

        // Close modal on close button
        document.querySelector('#assign-modal .modal-close').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('assign-modal').style.display = 'none';
        });

        // Close modal on outside click
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('assign-modal');
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>
