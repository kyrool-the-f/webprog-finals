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
    if ($action === "create_employee") {
        $first_name = $_POST['first_name'] ?? '';
        $middle_initial = $_POST['middle_initial'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $address = $_POST['address'] ?? '';
        $position = $_POST['position'] ?? '';
        $status = $_POST['status'] ?? 'Active';
        
        if (!$first_name || !$last_name) {
            throw new Exception("First name and last name are required");
        }
        
        $sql = "INSERT INTO employees (first_name, middle_initial, last_name, address, position, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("ssssss", $first_name, $middle_initial, $last_name, $address, $position, $status);
        
        if (!$stmt->execute()) {
            throw new Exception("Insert failed: " . $stmt->error);
        }
        
        $stmt->close();
        $response = ["success" => true, "message" => "Employee created successfully"];
    }
    
    elseif ($action === "get_employee") {
        $employee_id = (int)$_POST['employee_id'];
        
        if (!$employee_id) {
            throw new Exception("Invalid employee ID");
        }
        
        $sql = "SELECT employee_id, first_name, middle_initial, last_name, address, position, status FROM employees WHERE employee_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("i", $employee_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if (!$result) {
            throw new Exception("Employee not found");
        }
        
        $response = ["success" => true, "employee" => $result];
    }
    
    elseif ($action === "update_employee") {
        $employee_id = (int)$_POST['employee_id'];
        $first_name = $_POST['first_name'] ?? '';
        $middle_initial = $_POST['middle_initial'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $address = $_POST['address'] ?? '';
        $position = $_POST['position'] ?? '';
        $status = $_POST['status'] ?? '';
        
        if (!$employee_id || !$first_name || !$last_name) {
            throw new Exception("Missing required fields");
        }
        
        $sql = "UPDATE employees SET first_name = ?, middle_initial = ?, last_name = ?, address = ?, position = ?, status = ? WHERE employee_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("ssssssi", $first_name, $middle_initial, $last_name, $address, $position, $status, $employee_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Update failed: " . $stmt->error);
        }
        
        $stmt->close();
        $response = ["success" => true, "message" => "Employee updated successfully"];
    }
    
    elseif ($action === "delete_employee") {
        $employee_id = (int)$_POST['employee_id'];
        
        if (!$employee_id) {
            throw new Exception("Invalid employee ID");
        }
        
        $sql = "DELETE FROM employees WHERE employee_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("i", $employee_id);
        if (!$stmt->execute()) {
            throw new Exception("Delete failed: " . $stmt->error);
        }
        
        $stmt->close();
        $response = ["success" => true, "message" => "Employee deleted successfully"];
    }
    
} catch (Exception $e) {
    http_response_code(500);
    $response = ["success" => false, "message" => $e->getMessage()];
    error_log("Employee action error: " . $e->getMessage());
}

echo json_encode($response);
exit();
?>
