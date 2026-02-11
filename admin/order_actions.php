<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

// Check authentication
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

include "config.php";

$action = $_POST['action'] ?? null;
$response = ["success" => false, "message" => "Invalid action"];

if (!$action) {
    http_response_code(400);
    echo json_encode($response);
    exit();
}

try {
    if ($action === "assign_vehicle") {
        $form_id = (int)$_POST['form_id'];
        $car_id = (int)$_POST['car_id'];
        
        if (!$form_id || !$car_id) {
            throw new Exception("Invalid form or car ID");
        }
        
        $sql = "UPDATE orders_forms SET assigned_car = ? WHERE form_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("ii", $car_id, $form_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        // Update car status to Rented
        $update_car = "UPDATE cars SET status = 'Rented' WHERE cars_id = ?";
        $car_stmt = $conn->prepare($update_car);
        if ($car_stmt) {
            $car_stmt->bind_param("i", $car_id);
            $car_stmt->execute();
            $car_stmt->close();
        }
        
        $stmt->close();
        $response = ["success" => true, "message" => "Vehicle assigned successfully"];
        
    } elseif ($action === "update_status") {
        $form_id = (int)$_POST['form_id'];
        $status = $_POST['status'] ?? '';
        
        if (!$form_id || !in_array($status, ['Pending', 'Completed', 'Cancelled'])) {
            throw new Exception("Invalid form ID or status");
        }
        
        // Just update the status in orders_forms
        $sql = "UPDATE orders_forms SET status = ? WHERE form_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("si", $status, $form_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $stmt->close();
        $response = ["success" => true, "message" => "Status updated successfully"];
        
    } elseif ($action === "cancel_order") {
        $form_id = (int)$_POST['form_id'];
        $status = $_POST['status'] ?? 'Cancelled';
        
        if (!in_array($status, ['Pending', 'Completed', 'Cancelled'])) {
            $status = 'Cancelled';
        }
        
        if (!$form_id) {
            throw new Exception("Invalid form ID");
        }
        
        // Temporarily disable foreign key checks to avoid constraint issues
        $conn->query("SET FOREIGN_KEY_CHECKS=0");
        
        // Get order details before deletion
        $get_sql = "SELECT * FROM orders_forms WHERE form_id = ?";
        $get_stmt = $conn->prepare($get_sql);
        if (!$get_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $get_stmt->bind_param("i", $form_id);
        if (!$get_stmt->execute()) {
            throw new Exception("Execute failed: " . $get_stmt->error);
        }
        
        $order = $get_stmt->get_result()->fetch_assoc();
        $get_stmt->close();
        
        if (!$order) {
            throw new Exception("Order not found");
        }
        
        // Insert into history with the provided status
        $insert_sql = "INSERT INTO orders_history 
                      (form_id, full_name, email, contact_number, vehicle_type, assigned_car, 
                       service_type, pickup_location, pickup_datetime, return_location, 
                       return_datetime, drivers_license, status)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        if (!$insert_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $assigned_car = $order['assigned_car'] ?? 0;
        $insert_stmt->bind_param("issiissssssss",
            $form_id,
            $order['full_name'],
            $order['email'],
            $order['contact_number'],
            $order['vehicle_type'],
            $assigned_car,
            $order['service_type'],
            $order['pickup_location'],
            $order['pickup_datetime'],
            $order['return_location'],
            $order['return_datetime'],
            $order['drivers_license'],
            $status
        );
        
        if (!$insert_stmt->execute()) {
            throw new Exception("Insert failed: " . $insert_stmt->error);
        }
        
        $insert_stmt->close();
        
        // If car was assigned, make it available again
        if ($assigned_car) {
            $update_car = "UPDATE cars SET status = 'Available' WHERE cars_id = ?";
            $car_stmt = $conn->prepare($update_car);
            if ($car_stmt) {
                $car_stmt->bind_param("i", $assigned_car);
                $car_stmt->execute();
                $car_stmt->close();
            }
        }
        
        // Delete from orders_forms
        $delete_sql = "DELETE FROM orders_forms WHERE form_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        if (!$delete_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $delete_stmt->bind_param("i", $form_id);
        if (!$delete_stmt->execute()) {
            throw new Exception("Delete failed: " . $delete_stmt->error);
        }
        
        $delete_stmt->close();
        
        // Re-enable foreign key checks
        $conn->query("SET FOREIGN_KEY_CHECKS=1");
        
        $response = ["success" => true, "message" => "Order removed and moved to history with status: " . $status];
        
    } elseif ($action === "delete_history") {
        $form_id = (int)$_POST['form_id'];
        
        if (!$form_id) {
            throw new Exception("Invalid form ID");
        }
        
        // Get assigned car before deletion
        $get_sql = "SELECT assigned_car FROM orders_history WHERE form_id = ?";
        $get_stmt = $conn->prepare($get_sql);
        if (!$get_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $get_stmt->bind_param("i", $form_id);
        if (!$get_stmt->execute()) {
            throw new Exception("Execute failed: " . $get_stmt->error);
        }
        
        $result = $get_stmt->get_result()->fetch_assoc();
        $get_stmt->close();
        
        if ($result && $result['assigned_car']) {
            $update_car = "UPDATE cars SET status = 'Available' WHERE cars_id = ?";
            $car_stmt = $conn->prepare($update_car);
            if ($car_stmt) {
                $car_stmt->bind_param("i", $result['assigned_car']);
                $car_stmt->execute();
                $car_stmt->close();
            }
        }
        
        $delete_sql = "DELETE FROM orders_history WHERE form_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        if (!$delete_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $delete_stmt->bind_param("i", $form_id);
        if (!$delete_stmt->execute()) {
            throw new Exception("Delete failed: " . $delete_stmt->error);
        }
        
        $delete_stmt->close();
        $response = ["success" => true, "message" => "History record deleted"];
        
    } elseif ($action === "get_available_cars") {
        $sql = "SELECT c.cars_id, c.plate_number, c.model_name FROM cars c WHERE c.status = 'Available' ORDER BY c.cars_id";
        $result = $conn->query($sql);
        if (!$result) {
            throw new Exception("Query failed: " . $conn->error);
        }
        
        $cars = [];
        while ($row = $result->fetch_assoc()) {
            $cars[] = $row;
        }
        
        $response = ["success" => true, "cars" => $cars];
    }
    
} catch (Exception $e) {
    http_response_code(500);
    $response = ["success" => false, "message" => $e->getMessage()];
    error_log("Order action error: " . $e->getMessage());
}

echo json_encode($response);
exit();
