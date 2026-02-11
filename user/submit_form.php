<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

session_start();
header("Content-Type: application/json");

try {
    if (!isset($_SESSION['logged_in'])) {
        http_response_code(401);
        echo json_encode([
            "success" => false,
            "message" => "You must be logged in to submit this form."
        ]);
        exit();
    }

    $userId = $_SESSION['user_id'] ?? null;

    // Validate email
    $email = $_POST['email'] ?? '';
    $emailRegex = '/^[a-zA-Z0-9._%+-]+@(sample|google|example|gmail|yahoo)\.(com|net|org|ph)$/i';
    if (!preg_match($emailRegex, $email)) {
        throw new Exception("Invalid email domain. Please use a valid email.");
    }

    // Validate contact number (Philippine format)
    $phone = $_POST['phone'] ?? '';
    $phoneRegex = '/^(09|\+639)\d{9}$/';
    if (!preg_match($phoneRegex, $phone)) {
        throw new Exception("Invalid contact number. Use format: 09123456789 or +639123456789");
    }

    // Validate dates
    $pickupDt = $_POST['pickup_dt'] ?? '';
    $returnDt = $_POST['return_dt'] ?? '';
    
    if (empty($pickupDt) || empty($returnDt)) {
        throw new Exception("Pickup and return dates are required");
    }

    $pickupDate = new DateTime($pickupDt);
    $returnDate = new DateTime($returnDt);
    $today = new DateTime();
    $tomorrow = new DateTime();
    $tomorrow->modify('+1 day');
    $tomorrow->setTime(0, 0, 0);

    if ($pickupDate < $tomorrow) {
        throw new Exception("Pickup must be at least 1 day in the future");
    }

    $diffInHours = ($returnDate->getTimestamp() - $pickupDate->getTimestamp()) / 3600;
    if ($diffInHours < 8) {
        throw new Exception("Return time must be at least 8 hours after pickup");
    }

    $licensePath = null;
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/licenses/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $filename = uniqid() . "_" . basename($_FILES['picture']['name']);
        $targetFile = $targetDir . $filename;
        if (!move_uploaded_file($_FILES['picture']['tmp_name'], $targetFile)) {
            throw new Exception("Failed to upload file");
        }
        $licensePath = $targetFile;
    }

    include "config.php";

    if (!$conn) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }

    $stmt = mysqli_prepare($conn, "
        INSERT INTO orders_forms
        (full_name, email, contact_number, vehicle_type, service_type,
         pickup_location, pickup_datetime, return_location, return_datetime, drivers_license, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        throw new Exception("Prepare failed: " . mysqli_error($conn));
    }

    $cartype = (int)$_POST['cartype'];
    $status = 'Pending';
    
    mysqli_stmt_bind_param(
        $stmt,
        "ssissssssss",
        $_POST['fullname'],
        $_POST['email'],
        $_POST['phone'],
        $cartype,
        $_POST['service'],
        $_POST['pickup_loc'],
        $_POST['pickup_dt'],
        $_POST['return_loc'],
        $_POST['return_dt'],
        $licensePath,
        $status
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);

    echo json_encode(["success" => true, "message" => "Booking submitted successfully"]);
    exit();

} catch (Exception $e) {
    error_log("Submit form error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
    exit();
}