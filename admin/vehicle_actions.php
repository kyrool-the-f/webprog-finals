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
    if ($action === "add_vehicle") {
        $model_id = (int)$_POST['model_id'];
        $plate_number = $_POST['plate_number'] ?? '';
        
        if (!$model_id || !$plate_number) {
            throw new Exception("Invalid model or plate number");
        }
        
        $sql = "INSERT INTO cars (model_name, plate_number, status) VALUES (?, ?, 'Available')";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("is", $model_id, $plate_number);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $stmt->close();
        $response = ["success" => true, "message" => "Vehicle added successfully"];
        
    } elseif ($action === "update_vehicle_status") {
        $car_id = (int)$_POST['car_id'];
        $status = $_POST['status'] ?? '';
        
        if (!$car_id || !in_array($status, ['Available', 'Rented', 'Maintenance'])) {
            throw new Exception("Invalid car ID or status");
        }
        
        $sql = "UPDATE cars SET status = ? WHERE cars_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("si", $status, $car_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $stmt->close();
        $response = ["success" => true, "message" => "Vehicle status updated"];
        
    } elseif ($action === "delete_vehicle") {
        $car_id = (int)$_POST['car_id'];
        
        if (!$car_id) {
            throw new Exception("Invalid car ID");
        }
        
        // Check if vehicle is assigned to any order
        $check_sql = "SELECT COUNT(*) as count FROM orders_forms WHERE assigned_car = ?";
        $check_stmt = $conn->prepare($check_sql);
        if (!$check_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $check_stmt->bind_param("i", $car_id);
        if (!$check_stmt->execute()) {
            throw new Exception("Execute failed: " . $check_stmt->error);
        }
        
        $check_result = $check_stmt->get_result()->fetch_assoc();
        $check_stmt->close();
        
        if ($check_result['count'] > 0) {
            throw new Exception("Cannot delete vehicle assigned to an order");
        }
        
        $delete_sql = "DELETE FROM cars WHERE cars_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        if (!$delete_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $delete_stmt->bind_param("i", $car_id);
        
        if (!$delete_stmt->execute()) {
            throw new Exception("Delete failed: " . $delete_stmt->error);
        }
        
        $delete_stmt->close();
        $response = ["success" => true, "message" => "Vehicle deleted successfully"];
        
    } elseif ($action === "get_models") {
        $sql = "SELECT model_id, car_model FROM car_model ORDER BY car_model";
        $result = $conn->query($sql);
        if (!$result) {
            throw new Exception("Query failed: " . $conn->error);
        }
        
        $models = [];
        while ($row = $result->fetch_assoc()) {
            $models[] = $row;
        }
        
        $response = ["success" => true, "models" => $models];
    }
    
} catch (Exception $e) {
    http_response_code(500);
    $response = ["success" => false, "message" => $e->getMessage()];
    error_log("Vehicle action error: " . $e->getMessage());
}

echo json_encode($response);
exit();
