<?php
include "../db/config.php";

function getAllEmployees()
{
    $stmt = "Select * from Employee";
    $result = $GLOBALS['dbconn']->query($stmt);
    $employees = array();
    if($result->num_rows >0){
        while($row = $result->fetch_assoc()){
            $employees[] = $row;
        }
    }
    return $employees;
    } else {
        echo json_encode(array("message" => "Qeury Failed!", "status" => false, "code" => "6101"), JSON_PRETTY_PRINT);
    }


function createSingleEmployee($data)
{
    $stmt = $GLOBALS['dbconn']->prepare("INSERT INTO Employee (Employee_id, email, telephone, first_name, last_name,username,password,dob) 
    VALUES (?, ?, ?, ?, ?,?,?,?)");
    $stmt->bind_param('isssssss', $data['EmployeeID'],$data['email'],$data['telephone'],$data['FirstName'],$data['LastName'],$data['username'],$data['password'],$data['dob']);
    return mysqli_stmt_execute($stmt);
}
?>