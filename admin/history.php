<?php
include "auth_check.php";
include "config.php"; 

// Set charset to UTF-8
$conn->set_charset("utf8");

// Fetch orders from database
$sql = "SELECT oh.form_id, oh.full_name, oh.email, oh.contact_number, oh.vehicle_type, oh.assigned_car, oh.service_type, oh.pickup_location, oh.pickup_datetime, oh.return_location, oh.return_datetime, oh.drivers_license, 
        c.model_name as vehicle_name
        FROM orders_history oh
        LEFT JOIN cars c ON oh.assigned_car = c.cars_id
        ORDER BY oh.form_id DESC";
$result = $conn->query($sql);
$bookings = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History — Car Rentals</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="history.css">
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
                <li class="active"><a href="history.php">History</a></li>
                <li><a href="audit_log.php">Audit Log</a></li>
                <li class="admin-info">
                    <a href="logout.php" class="logout-btn">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="admin-container">
        <header class="admin-header">
            <h1>Orders History</h1>
            <p>View all past orders and rental transactions.</p>
        </header>

        <div class="admin-wrapper">
            <aside class="sidebar">
                <h3>Filter</h3>
                <ul>
                    <li><a href="history.php" class="active">All Bookings</a></li>
                    <li><a href="completed.php">Completed</a></li>
                    <li><a href="cancelled.php">Cancelled</a></li>
                </ul>
            </aside>

            <section class="main-content">
                <section class="table-section">
                    <h2>Complete Orders History</h2>
                    <table class="admin-table" data-table="orders_history">
                        <thead>
                            <tr>
                                <th data-column="form_id">Form ID</th>
                                <th data-column="full_name">Full Name</th>
                                <th data-column="email">Email</th>
                                <th data-column="contact_number">Contact Number</th>
                                <th data-column="vehicle_type">Vehicle Type</th>
                                <th data-column="assigned_car">Assigned Vehicle</th>
                                <th data-column="service_type">Service Type</th>
                                <th data-column="pickup_location">Pickup Location</th>
                                <th data-column="pickup_datetime">Pickup DateTime</th>
                                <th data-column="return_location">Return Location</th>
                                <th data-column="return_datetime">Return DateTime</th>
                                <th data-column="drivers_license">Drivers License</th>
                                <th data-column="Action">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($bookings) > 0) {
                                $record_id = 1;
                                foreach ($bookings as $booking) {
                                    ?>
                                    <tr data-record-id="<?php echo $record_id; ?>" data-db-id="<?php echo htmlspecialchars($booking['form_id']); ?>">
                                        <td data-column="form_id" data-value="<?php echo htmlspecialchars($booking['form_id']); ?>"><?php echo htmlspecialchars($booking['form_id']); ?></td>
                                        <td data-column="full_name" data-value="<?php echo htmlspecialchars($booking['full_name']); ?>"><?php echo htmlspecialchars($booking['full_name']); ?></td>
                                        <td data-column="email" data-value="<?php echo htmlspecialchars($booking['email']); ?>"><?php echo htmlspecialchars($booking['email']); ?></td>
                                        <td data-column="contact_number" data-value="<?php echo htmlspecialchars($booking['contact_number']); ?>"><?php echo htmlspecialchars($booking['contact_number']); ?></td>
                                        <td data-column="vehicle_type" data-value="<?php echo htmlspecialchars($booking['vehicle_type']); ?>"><?php echo htmlspecialchars($booking['vehicle_type']); ?></td>
                                        <td data-column="service_type" data-value="<?php echo htmlspecialchars($booking['service_type']); ?>"><?php echo htmlspecialchars($booking['service_type']); ?></td>
                                        <td data-column="assigned_car" data-value="<?php echo htmlspecialchars($booking['assigned_car']); ?>"><?php echo htmlspecialchars($booking['assigned_car']); ?></td>
                                        <td data-column="pickup_location" data-value="<?php echo htmlspecialchars($booking['pickup_location']); ?>"><?php echo htmlspecialchars($booking['pickup_location']); ?></td>
                                        <td data-column="pickup_datetime" data-value="<?php echo htmlspecialchars($booking['pickup_datetime']); ?>"><?php echo htmlspecialchars($booking['pickup_datetime']); ?></td>
                                        <td data-column="return_location" data-value="<?php echo htmlspecialchars($booking['return_location']); ?>"><?php echo htmlspecialchars($booking['return_location']); ?></td>
                                        <td data-column="return_datetime" data-value="<?php echo htmlspecialchars($booking['return_datetime']); ?>"><?php echo htmlspecialchars($booking['return_datetime']); ?></td>
                                        <td data-column="drivers_license"><button class="btn small">View License</button></td>                                        
                                        <td>
                                            <button class="btn small danger" onclick="deleteHistoryRecord(<?php echo $booking['form_id']; ?>)">Delete</button>
                                        </td>
                                    </tr>
                                    <?php
                                    $record_id++;
                                }
                            } else {
                                echo '<tr><td colspan="10" style="text-align: center; padding: 20px;">No orders found</td></tr>';
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
        // Database Table Adapter - Works with any backend database
        class DatabaseTableAdapter {
            constructor(tableElement) {
                this.table = tableElement;
                this.tableName = tableElement.dataset.table;
            }

            // Get all records from table
            getAllRecords() {
                const records = [];
                this.table.querySelectorAll('tbody tr').forEach(row => {
                    records.push(this.getRowData(row));
                });
                return records;
            }

            // Get specific record by ID
            getRecord(recordId) {
                const row = this.table.querySelector(`tr[data-record-id="${recordId}"]`);
                return row ? this.getRowData(row) : null;
            }

            // Extract row data into object matching database columns
            getRowData(row) {
                const record = {
                    record_id: row.dataset.recordId,
                    db_id: row.dataset.dbId
                };
                
                row.querySelectorAll('td').forEach(cell => {
                    const column = cell.dataset.column;
                    const value = cell.dataset.value;
                    if (column && value) {
                        record[column] = value;
                    }
                });
                
                return record;
            }

            // Update row with database values
            updateRow(recordId, data) {
                const row = this.table.querySelector(`tr[data-record-id="${recordId}"]`);
                if (!row) return false;

                Object.keys(data).forEach(column => {
                    const cell = row.querySelector(`td[data-column="${column}"]`);
                    if (cell) {
                        cell.dataset.value = data[column];
                        // Update display if not a formatted field
                        if (column !== 'status') {
                            cell.textContent = data[column];
                        }
                    }
                });

                return true;
            }

            // Add new row from database record
            addRecord(recordData) {
                const tbody = this.table.querySelector('tbody');
                const newRow = document.createElement('tr');
                
                const recordId = Object.keys(tbody.querySelectorAll('tr')).length + 1;
                newRow.dataset.recordId = recordId;
                newRow.dataset.dbId = recordData.db_id || recordData.booking_id;

                let html = '';
                const columnHeaders = Array.from(this.table.querySelectorAll('thead th'))
                    .map(th => th.dataset.column);

                columnHeaders.forEach(column => {
                    const value = recordData[column] || '';
                    html += `<td data-column="${column}" data-value="${value}">${value}</td>`;
                });

                newRow.innerHTML = html;
                tbody.appendChild(newRow);
            }

            // Remove row
            removeRecord(recordId) {
                const row = this.table.querySelector(`tr[data-record-id="${recordId}"]`);
                if (row) {
                    row.remove();
                    return true;
                }
                return false;
            }

            // Export all data for database submission
            exportForDatabase() {
                return {
                    table: this.tableName,
                    records: this.getAllRecords()
                };
            }
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const ordersTable = document.querySelector('[data-table="orders_history"]');
            const adapter = new DatabaseTableAdapter(ordersTable);

            // Example: Log all records
            console.log('Records in table:', adapter.getAllRecords());

            // Example: Access specific record
            console.log('Record 1:', adapter.getRecord(1));

            // Example: Export for database
            console.log('Export format:', adapter.exportForDatabase());
        });

        function deleteHistoryRecord(formId) {
            if (!confirm('Are you sure you want to delete this history record?')) {
                return;
            }

            fetch('order_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=delete_history&form_id=${formId}`
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
                alert('Error deleting history record');
            });
        }
    </script>

</body>
</html>
<?php
// Close the database connection
$conn->close();
?>
