<?php

include("db/config.php");
header("Content-Type: application/json");

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'fetch':
        echo json_encode(fetchCustomers($dbconn));
        break;
    case 'create':
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(createCustomer($dbconn, $data));
        break;   
    case 'edit':
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(editCustomer($dbconn, $data));
        break;
    case 'delete':
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(deleteCustomer($dbconn, $data));
        break;
    default:
        echo json_encode(array("message" => "Invalid action.", "status" => false));
}


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


function fetchCustomers($dbconn) {
    $query = "SELECT * FROM Customer";
    $result = mysqli_query($dbconn, $query);

    $customers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $customers[] = $row;
    }

    return $customers;
}

function createCustomer($dbconn, $data) {
    
    if (!isValidUsername($data["username"]) || !isValidName($data["first_name"]) || !isValidName($data["last_name"]) || !isValidPassword($data["password"]) || 
        !isValidDate($data["dob"]) || !isValidEmail($data["email"]) || !isValidPhone($data["telephone"]) ){
        echo json_encode(['success' => false, 'message' => 'Validation failed.']);
        exit;
    }
    
    $stmt = $dbconn->prepare("INSERT INTO Customer (username, password, first_name, last_name, email, telephone, dob,postcode,city,province) VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?)");
    $stmt->bind_param("ssssssssss", $username, $password, $first_name, $last_name, $email, $telephone, $dob, $postcode, $city, $province );

    $username = $data["username"];
    $password = password_hash($data["password"], PASSWORD_DEFAULT);
    $first_name = $data["first_name"];
    $last_name = $data["last_name"];
    $email = $data["email"];
    $telephone = $data["telephone"];
    $dob = $data["dob"];
    $postcode = $data["postcode"];
    $city = $data["city"];
    $province = $data["province"];

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
        exit;
    }
}



function editCustomer($dbconn, $data) {
    
    if (!isValidUsername($data["username"]) || !isValidName($data["first_name"]) || !isValidName($data["last_name"]) || !isValidPassword($data["password"]) || 
        !isValidDate($data["dob"]) || !isValidEmail($data["email"]) || !isValidPhone($data["telephone"]) ){
        echo json_encode(['success' => false, 'message' => 'Validation failed.']);
        exit;
    }

    $stmt = mysqli_prepare($dbconn, "SELECT password FROM Customer WHERE customer_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $data['customer_id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $hashedPassword);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);


    $passwordNeedsUpdate = !password_verify($data['password'], $hashedPassword);

    if ($passwordNeedsUpdate) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }
    $stmt = mysqli_prepare($dbconn, "UPDATE Customer SET password=?,first_name=?, last_name=?, dob=?, email=?, telephone=?, city=?, province=?, postcode=? WHERE customer_id=?");
    mysqli_stmt_bind_param($stmt, 'sssssssssi', $data['password'], $data['first_name'], $data['last_name'], $data['dob'], $data['email'], $data['telephone'], $data['city'], $data['province'],$data['postcode'],$data['customer_id']);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return array('status' => $success);
}

function deleteCustomer($dbconn, $data) {
    $stmt = mysqli_prepare($dbconn, "DELETE FROM Customer WHERE customer_id=?");
    mysqli_stmt_bind_param($stmt, 'i', $data['customer_id']);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return array('status' => $success);
}

mysqli_close($dbconn);
?>
