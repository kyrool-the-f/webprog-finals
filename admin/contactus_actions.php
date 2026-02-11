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
    if ($action === "delete_contact") {
        $contact_us_id = (int)$_POST['contact_us_id'];
        
        if (!$contact_us_id) {
            throw new Exception("Invalid contact form ID");
        }
        
        $sql = "DELETE FROM contact_us_forms WHERE contact_us_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("i", $contact_us_id);
        if (!$stmt->execute()) {
            throw new Exception("Delete failed: " . $stmt->error);
        }
        
        $stmt->close();
        $response = ["success" => true, "message" => "Contact form deleted successfully"];
    }
    
} catch (Exception $e) {
    http_response_code(500);
    $response = ["success" => false, "message" => $e->getMessage()];
    error_log("Contact action error: " . $e->getMessage());
}

echo json_encode($response);
exit();
?>
