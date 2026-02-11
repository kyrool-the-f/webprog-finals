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

    include "config.php";

    if (!$conn) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }

    $stmt = mysqli_prepare($conn, "
        INSERT INTO contact_us_forms
        (full_name, email, contact_number, service, message, submitted_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");

    if (!$stmt) {
        throw new Exception("Prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sssss",
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['service'],
        $_POST['message']
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);

    echo json_encode(["success" => true, "message" => "Message submitted successfully"]);
    exit();

} catch (Exception $e) {
    error_log("Contact form error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
    exit();
}
