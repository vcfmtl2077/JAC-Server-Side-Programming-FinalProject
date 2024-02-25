<?php
include "../db/config.php";

// CRUD--C
function createSingleEmployee($data)
{
    $stmt = $GLOBALS['dbconn']->prepare("INSERT INTO `Employee` (`employee_id`, `first_name`, `last_name`, `email`, `department`, `phone_number`, `address`) 
    VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('issssss', $data['EmployeeID'],$data['email'],$data['FirstName'],$data['LastName'],$data['department'],$data['phone_number'],$data['address']);
    return mysqli_stmt_execute($stmt);
}



//CRUD - R
function getEmployeeByID($id)
{
    $stmt = $GLOBALS['dbconn']->prepare("Select * from Employee where Employee_id=?");
    $stmt->bind_param('s', $id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $Employee = $result->fetch_assoc();
        return $Employee;
    } else {
        echo json_encode(array("message" => "Qeury Failed!", "status" => false, "code" => "6101"), JSON_PRETTY_PRINT);
    }
}


// CRUD - R ALL
function getAllEmployees() {
    $sql = "SELECT * FROM employee";
    $result = $GLOBALS['dbconn']->query($sql);
    $employees = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $employees[] = $row;
        }
    }
    return $employees;
}

// CRUD - U
function updateEmployee($id, $email) {
    $sql = "UPDATE employees SET  email='$email' WHERE id=$id";
    if ($GLOBALS['dbconn']->query($sql) === TRUE) {
        return "Employee updated successfully";
    } else {
        return "Error updating employee: " . $GLOBALS['dbconn']->error;
    }
}

// CRUD - D
function deleteEmployee($id) {
    $sql = "DELETE FROM employees WHERE id=$id";
    if ($GLOBALS['dbconn']->query($sql) === TRUE) {
        return "Employee deleted successfully";
    } else {
        return "Error deleting employee: " . $GLOBALS['dbconn']->error;
    }
}

?>
