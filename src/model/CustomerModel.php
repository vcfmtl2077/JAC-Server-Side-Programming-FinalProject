<?php
include "../db/config.php";

function fetchCustomerByID($id)
{
    $stmt = $GLOBALS['dbconn']->prepare("Select * from Customer where customer_id=?");
    $stmt->bind_param('s', $id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $customer = $result->fetch_assoc();
        return $customer;
    } else {
        echo json_encode(array("message" => "Qeury Failed!", "status" => false, "code" => "6101"), JSON_PRETTY_PRINT);
    }
}

function createSingleCustomer($data)
{
    $stmt = $GLOBALS['dbconn']->prepare("INSERT INTO Customer (customer_id, email, telephone, first_name, last_name,username,password,dob) 
    VALUES (?, ?, ?, ?, ?,?,?,?)");
    $stmt->bind_param('isssssss', $data['CustomerID'],$data['email'],$data['telephone'],$data['FirstName'],$data['LastName'],$data['username'],$data['password'],$data['dob']);
    return mysqli_stmt_execute($stmt);
}
?>