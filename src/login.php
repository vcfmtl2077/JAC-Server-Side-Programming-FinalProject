<?php
include("db/config.php");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$username = $data["username"];
$enteredPassword = $data["password"];
$role = $data["role"];


if ($role === "user") {
    $stmt = mysqli_prepare($dbconn, "SELECT first_name, last_name, telephone, password FROM Customer WHERE username = ?");
} else {
    $stmt = mysqli_prepare($dbconn, "SELECT password FROM Employee WHERE username = ?");
}

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars(mysqli_error($dbconn)));
}

mysqli_stmt_bind_param($stmt, 's', $username);

mysqli_stmt_execute($stmt);


if ($role === "user") {
    mysqli_stmt_bind_result($stmt, $first_name, $last_name, $telephone, $hashedPassword);
} else {
    mysqli_stmt_bind_result($stmt, $hashedPassword);
}

$response = array();
if (mysqli_stmt_fetch($stmt)) {
    if (password_verify($enteredPassword, $hashedPassword)) {
        if ($role === "user") {
            $response['first_name'] = $first_name;
            $response['last_name'] = $last_name;
            $response['telephone'] = $telephone;
        }
        $response['username'] = $username;
        $response['role'] = $role;
        $response['status'] = true;
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("message" => "Invalid Credentials.", "status" => false));
    }
} else {
    echo json_encode(array("message" => "No User Found.", "status" => false));
}

mysqli_stmt_close($stmt);

mysqli_close($dbconn);

?>