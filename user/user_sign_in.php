<?php
session_start();
header("Content-Type: application/json");
include "config.php";

$response = ["success" => false];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT user_id, password, first_name FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && $user = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['first_name'];
                $_SESSION['logged_in'] = true;
                $response["success"] = true;
                $response["user_name"] = $user['first_name'];
            } else {
                $response["message"] = "Invalid credentials";
            }
        } else {
            $response["message"] = "User not found";
        }

        mysqli_stmt_close($stmt);
    } else {
        $response["message"] = "Database error";
    }
}

echo json_encode($response);
exit();
?>