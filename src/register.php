<?php
include("db/config.php");
header("Content-Type: application/json");

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function isValidUsername($username) {
    if (empty($username)) {
        return false;
    }
    $username = test_input($username);
    return strlen($username) <= 255;
}

function isValidName($name) {
    if (empty($name)) {
        return false;
    }
    $name = test_input($name);
    return strlen($name) <= 50;
}

function isValidPassword($password) {
    if (empty($password)) {
        return false;
    }
    $password = test_input($password);
    $passwordPattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/';
    return preg_match($passwordPattern, $password);
}

function isValidDate($date, $format = 'Y-m-d') {
    if (empty($date)) {
        return false;
    }
    $date = test_input($date);
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function isValidEmail($email) {
    if (empty($email)) {
        return false;
    }
    $email = test_input($email);
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isValidPhone($phone) {
    if (empty($phone)) {
        return false;
    }
    $phone = test_input($phone);
    $phonePattern = '/^\+?(\d{1,3})?[-. ]?\(?\d{1,3}\)?[-. ]?\d{1,4}[-. ]?\d{1,4}[-. ]?\d{1,9}$/';
    return preg_match($phonePattern, $phone);
}


$data = json_decode(file_get_contents('php://input'), true);


if (!isValidUsername($data["username"]) || !isValidName($data["firstname"]) || !isValidName($data["lastname"]) ||!isValidPassword($data["password"]) || 
    !isValidDate($data["dob"]) || !isValidEmail($data["email"]) || !isValidPhone($data["telephone"]) ){
    echo json_encode(['success' => false, 'message' => 'Validation failed.']);
    exit;
}

$stmt = $dbconn->prepare("SELECT username FROM Customer WHERE username = ?");
$stmt->bind_param("s", $data["username"]);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username already exists. Please choose a different username.']);
    $stmt->close();
    exit;
}

$stmt->close();

$stmt = $dbconn->prepare("INSERT INTO Customer (username, password, first_name, last_name, email, telephone, dob,postcode,city,province) VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?)");
$stmt->bind_param("ssssssssss", $username, $password, $first_name, $last_name, $email, $telephone, $dob, $postcode, $city, $province );

$username = $data["username"];
$password = password_hash($data["password"], PASSWORD_DEFAULT);
$first_name = $data["firstname"];
$last_name = $data["lastname"];
$email = $data["email"];
$telephone = $data["telephone"];
$dob = $data["dob"];
$postcode = $data["postcode"];
$city = $data["city"];
$province = $data["province"];

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}

$stmt->close();
$dbconn->close();


?>
