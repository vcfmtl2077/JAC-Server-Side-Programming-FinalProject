<?php
// profile.php
include("../model/CustomerModel.php");

// same name with file name for router invoke
function CustomerController($pathArray, $method, $data)
{

    // controller logic
    if (count($pathArray) == 1 && $method == "GET") {
        getCustomerInfo($pathArray[0]);
    }
    error_log("". json_encode($data));
    error_log($method);
    error_log(count($pathArray));
    if (count($pathArray) == 0 && $method == "POST") {
        if (isset($data)) {
            createCustomer($data);
        } else {
            echo json_encode(array("message" => "Invalid Json Body", "status" => false, "code" => "5201"), JSON_PRETTY_PRINT);
        }

    }

}

function getCustomerInfo($id)
{
    $customer = fetchCustomerByID($id);

    if (isset($customer)) {
        $response['CustomerID'] = $customer['customer_id'];
        $response['FirstName'] = $customer['first_name'];
        $response['LastName'] = $customer['last_name'];
        $response['email'] = $customer['email'];
        $response['telephone'] = $customer['telephone'];
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("message" => "No result found.", "status" => false, "code" => "4101"), JSON_PRETTY_PRINT);
    }

}

function createCustomer($data)
{
    $isCreate = createSingleCustomer($data);
    if ($isCreate) {
        echo json_encode(array("message" => "Create Customer Successfully!.", "status" => true, "code" => "5202"), JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("message" => "Create Customer Failed.", "status" => false, "code" => "5202"), JSON_PRETTY_PRINT);
    }
}

?>