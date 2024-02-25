<?php
// profile.php
include("../model/EmployeeModel.php");

// same name with file name for router invoke
function EmployeeController($pathArray, $method, $data)
{

    // controller logic for api GET http://localhost:9000/api/v1/Employee
    if (count($pathArray) == 0 && $method == "GET") {
        getAllEmployees();
    }
    // controller logic for api GET http://localhost:9000/api/v1/Employee/1
    if (count($pathArray) == 1 && $method == "GET") {
        getEmployeeInfo($pathArray[0]);
    }

  // controller logic for api POST http://localhost:9000/api/v1/Employee
    if (count($pathArray) == 0 && $method == "POST") {
        if (isset($data)) {
            createEmployee($data);
        } else {
            echo json_encode(array("message" => "Invalid Json Body", "status" => false, "code" => "5201"), JSON_PRETTY_PRINT);
        }

    }

}

function getAllEmployee(){

    $Employee = getEmployeeList();

    if (isset($Employee)) {
        $response['EmployeeID'] = $Employee['Employee_id'];
        $response['FirstName'] = $Employee['first_name'];
        $response['LastName'] = $Employee['last_name'];
        $response['email'] = $Employee['email'];
        $response['telephone'] = $Employee['telephone'];
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("message" => "No result found.", "status" => false, "code" => "4101"), JSON_PRETTY_PRINT);
    }

}

function getEmployeeInfo($id)
{
    $Employee = getEmployeeByID($id);

    if (isset($Employee)) {
        $response['EmployeeID'] = $Employee['Employee_id'];
        $response['FirstName'] = $Employee['first_name'];
        $response['LastName'] = $Employee['last_name'];
        $response['email'] = $Employee['email'];
        $response['telephone'] = $Employee['telephone'];
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("message" => "No result found.", "status" => false, "code" => "4101"), JSON_PRETTY_PRINT);
    }

}

function createEmployee($data)
{
    $isCreate = createSingleEmployee($data);
    if ($isCreate) {
        echo json_encode(array("message" => "Create Employee Successfully!.", "status" => true, "code" => "5202"), JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("message" => "Create Employee Failed.", "status" => false, "code" => "5202"), JSON_PRETTY_PRINT);
    }
}

?>