<?php
include "auth_check.php";
include "config.php"; 

// Set charset to UTF-8
$conn->set_charset("utf8");

// Fetch vehicles from database with model information
$sql = "SELECT c.cars_id, c.plate_number, c.status, cm.car_model, cm.type_model, cm.seater_model
        FROM cars c
        LEFT JOIN car_model cm ON c.model_name = cm.model_id
        ORDER BY c.cars_id DESC";

$result = $conn->query($sql);
$vehicles = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicles — Car Rentals Admin</title>
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
            <h1>Vehicles Management</h1>
            <p>Manage your vehicle inventory, availability and status.</p>
        </header>

        <div class="admin-wrapper">
            <aside class="sidebar">
                <h3>Categories</h3>
                <ul>
                    <li><a href="admin.php">Dashboard</a></li>
                    <li><a href="orders.php">Orders</a></li>
                    <li><a href="vehicles.php" class="active">Vehicles</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="employees.php">Employees</a></li>
                    <li><a href="contactus.php">Contact Us</a></li>
                </ul>
            </aside>

            <section class="main-content">
                <div style="margin-bottom: 16px;">
                    <button class="btn" onclick="openAddVehicleModal()">+ Add Vehicle</button>
                </div>
                
                <section class="table-section">
                    <h2>Vehicle Inventory</h2>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Vehicle ID</th>
                                <th>Model</th>
                                <th>Type</th>
                                <th>Seats</th>
                                <th>Plate Number</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($vehicles) > 0) {
                                foreach ($vehicles as $vehicle) {
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($vehicle['cars_id']); ?></td>
                                        <td><?php echo htmlspecialchars($vehicle['car_model']); ?></td>
                                        <td><?php echo htmlspecialchars($vehicle['type_model']); ?></td>
                                        <td><?php echo htmlspecialchars($vehicle['seater_model']); ?></td>
                                        <td><?php echo htmlspecialchars($vehicle['plate_number']); ?></td>
                                        <td>
                                            <select class="status-select" onchange="updateVehicleStatus(<?php echo $vehicle['cars_id']; ?>, this.value)">
                                                <option value="Available" <?php echo $vehicle['status'] === 'Available' ? 'selected' : ''; ?>>Available</option>
                                                <option value="Rented" <?php echo $vehicle['status'] === 'Rented' ? 'selected' : ''; ?>>Rented</option>
                                                <option value="Maintenance" <?php echo $vehicle['status'] === 'Maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                                            </select>
                                        </td>
                                        <td><button class="btn small" onclick="deleteVehicle(<?php echo $vehicle['cars_id']; ?>)">Delete</button></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="7" style="text-align: center; padding: 20px;">No vehicles found</td></tr>';
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

    <!-- Add/Edit Vehicle Modal -->
    <div id="vehicle-modal" class="modal-overlay">
        <div class="modal-box">
            <a href="#" class="modal-close">&times;</a>
            <h2>Add Vehicle</h2>
            <form id="vehicle-form">
                <div class="form-group">
                    <label for="model-select">Model:</label>
                    <select id="model-select" required>
                        <option value="">Select a model...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="plate-number">Plate Number:</label>
                    <input type="text" id="plate-number" placeholder="e.g., ABC-001" required>
                </div>
                <button type="submit" class="signin-btn">Add Vehicle</button>
            </form>
        </div>
    </div>

    <script>
        function openAddVehicleModal() {
            // Fetch car models
            fetch('vehicle_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=get_models'
            })
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('model-select');
                select.innerHTML = '<option value="">Select a model...</option>';
                
                if (data.success && data.models.length > 0) {
                    data.models.forEach(model => {
                        const option = document.createElement('option');
                        option.value = model.model_id;
                        option.textContent = model.car_model;
                        select.appendChild(option);
                    });
                    document.getElementById('vehicle-modal').style.display = 'flex';
                } else {
                    alert('Error loading models');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error loading models');
            });
        }

        document.getElementById('vehicle-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const modelId = document.getElementById('model-select').value;
            const plateNumber = document.getElementById('plate-number').value;
            
            if (!modelId || !plateNumber) {
                alert('Please fill all fields');
                return;
            }

            fetch('vehicle_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=add_vehicle&model_id=${modelId}&plate_number=${plateNumber}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    document.getElementById('vehicle-modal').style.display = 'none';
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error adding vehicle');
            });
        });

        function updateVehicleStatus(carId, status) {
            fetch('vehicle_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=update_vehicle_status&car_id=${carId}&status=${status}`
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message);
                    location.reload();
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error updating vehicle status');
            });
        }

        function deleteVehicle(carId) {
            if (!confirm('Are you sure you want to delete this vehicle?')) {
                return;
            }

            fetch('vehicle_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=delete_vehicle&car_id=${carId}`
            })
            .then(res => res.json())
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
                alert('Error deleting vehicle');
            });
        }

        // Close modal on close button
        document.querySelector('#vehicle-modal .modal-close').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('vehicle-modal').style.display = 'none';
        });

        // Close modal on outside click
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('vehicle-modal');
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>
