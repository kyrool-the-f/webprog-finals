<?php
include "auth_check.php";
include "config.php"; 

// Fetch all employees from database
$sql = "SELECT employee_id, first_name, middle_initial, last_name, address, position, status FROM employees ORDER BY employee_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees — Car Rentals Admin</title>
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
            <h1>Employees Management</h1>
            <p>Manage employee records, positions and status.</p>
        </header>

        <div class="admin-wrapper">
            <aside class="sidebar">
                <h3>Categories</h3>
                <ul>
                    <li><a href="admin.php">Dashboard</a></li>
                    <li><a href="orders.php">Orders</a></li>
                    <li><a href="vehicles.php">Vehicles</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="employees.php" class="active">Employees</a></li>
                    <li><a href="contactus.php">Contact Us</a></li>
                </ul>
            </aside>

            <section class="main-content">
                <div style="margin-bottom: 16px;">
                    <button class="btn" onclick="openAddModal()">+ Add Employee</button>
                </div>
                
                <section class="table-section">
                    <h2>Employee Records</h2>
                    <table class="admin-table">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>First Name</th>
                                <th>Middle Initial</th>
                                <th>Last Name</th>
                                <th>Address</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['employee_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['middle_initial']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['position']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                    echo "<td><button class='btn small' onclick=\"openEditModal(" . $row['employee_id'] . ")\">Edit</button> <button class='btn small danger' onclick=\"deleteEmployee(" . $row['employee_id'] . ")\">Remove</button></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' style='text-align: center;'>No employees found</td></tr>";
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

    <!-- Edit Employee Modal -->
    <div id="edit-employee-modal" class="modal-overlay">
        <div class="modal-box">
            <a href="#" class="modal-close">&times;</a>
            <h2>Edit Employee</h2>
            <form id="edit-employee-form">
                <input type="hidden" id="employee-id">
                <div class="form-group">
                    <label for="first-name">First Name:</label>
                    <input type="text" id="first-name" required>
                </div>
                <div class="form-group">
                    <label for="middle-initial">Middle Initial:</label>
                    <input type="text" id="middle-initial" maxlength="1">
                </div>
                <div class="form-group">
                    <label for="last-name">Last Name:</label>
                    <input type="text" id="last-name" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address">
                </div>
                <div class="form-group">
                    <label for="position">Position:</label>
                    <input type="text" id="position">
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <button type="submit" class="signin-btn">Update Employee</button>
            </form>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div id="add-employee-modal" class="modal-overlay">
        <div class="modal-box">
            <a href="#" class="modal-close">&times;</a>
            <h2>Add New Employee</h2>
            <form id="add-employee-form">
                <div class="form-group">
                    <label for="add-first-name">First Name:</label>
                    <input type="text" id="add-first-name" required>
                </div>
                <div class="form-group">
                    <label for="add-middle-initial">Middle Initial:</label>
                    <input type="text" id="add-middle-initial" maxlength="1">
                </div>
                <div class="form-group">
                    <label for="add-last-name">Last Name:</label>
                    <input type="text" id="add-last-name" required>
                </div>
                <div class="form-group">
                    <label for="add-address">Address:</label>
                    <input type="text" id="add-address">
                </div>
                <div class="form-group">
                    <label for="add-position">Position:</label>
                    <input type="text" id="add-position">
                </div>
                <div class="form-group">
                    <label for="add-status">Status:</label>
                    <select id="add-status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <button type="submit" class="signin-btn">Add Employee</button>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('add-employee-form').reset();
            document.getElementById('add-employee-modal').style.display = 'flex';
        }

        document.getElementById('add-employee-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const firstName = document.getElementById('add-first-name').value;
            const middleInitial = document.getElementById('add-middle-initial').value;
            const lastName = document.getElementById('add-last-name').value;
            const address = document.getElementById('add-address').value;
            const position = document.getElementById('add-position').value;
            const status = document.getElementById('add-status').value;

            fetch('employee_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=create_employee&first_name=${encodeURIComponent(firstName)}&middle_initial=${encodeURIComponent(middleInitial)}&last_name=${encodeURIComponent(lastName)}&address=${encodeURIComponent(address)}&position=${encodeURIComponent(position)}&status=${encodeURIComponent(status)}`
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
                    document.getElementById('add-employee-modal').style.display = 'none';
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error adding employee: ' + err.message);
            });
        });

        function openEditModal(employeeId) {
            fetch('employee_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=get_employee&employee_id=${employeeId}`
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
                    const emp = data.employee;
                    document.getElementById('employee-id').value = emp.employee_id;
                    document.getElementById('first-name').value = emp.first_name;
                    document.getElementById('middle-initial').value = emp.middle_initial || '';
                    document.getElementById('last-name').value = emp.last_name;
                    document.getElementById('address').value = emp.address || '';
                    document.getElementById('position').value = emp.position || '';
                    document.getElementById('status').value = emp.status;
                    document.getElementById('edit-employee-modal').style.display = 'flex';
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error loading employee: ' + err.message);
            });
        }

        document.getElementById('edit-employee-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const employeeId = document.getElementById('employee-id').value;
            const firstName = document.getElementById('first-name').value;
            const middleInitial = document.getElementById('middle-initial').value;
            const lastName = document.getElementById('last-name').value;
            const address = document.getElementById('address').value;
            const position = document.getElementById('position').value;
            const status = document.getElementById('status').value;

            fetch('employee_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=update_employee&employee_id=${employeeId}&first_name=${encodeURIComponent(firstName)}&middle_initial=${encodeURIComponent(middleInitial)}&last_name=${encodeURIComponent(lastName)}&address=${encodeURIComponent(address)}&position=${encodeURIComponent(position)}&status=${encodeURIComponent(status)}`
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
                    document.getElementById('edit-employee-modal').style.display = 'none';
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error updating employee: ' + err.message);
            });
        });

        function deleteEmployee(employeeId) {
            if (!confirm('Are you sure you want to delete this employee?')) {
                return;
            }

            fetch('employee_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=delete_employee&employee_id=${employeeId}`
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
                alert('Error deleting employee: ' + err.message);
            });
        }

        // Close modal on close button
        document.querySelector('#edit-employee-modal .modal-close').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('edit-employee-modal').style.display = 'none';
        });

        document.querySelector('#add-employee-modal .modal-close').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('add-employee-modal').style.display = 'none';
        });

        // Close modal on outside click
        window.addEventListener('click', function(e) {
            const editModal = document.getElementById('edit-employee-modal');
            const addModal = document.getElementById('add-employee-modal');
            if (e.target === editModal) {
                editModal.style.display = 'none';
            }
            if (e.target === addModal) {
                addModal.style.display = 'none';
            }
        });
    </script>

    <?php
    $conn->close();
    ?>
</body>
</html>
