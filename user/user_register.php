<?php
include "config.php";

$response = ["success" => false];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = $_POST['firstname'];
    $middleinitial = $_POST['middleinitial'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (first_name, middle_initial, last_name, email, password)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $firstname, $middleinitial, $lastname, $email, $password);

    if(mysqli_stmt_execute($stmt)){
        $response["success"] = true;
    } else {
        $response["message"] = mysqli_error($conn);
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>