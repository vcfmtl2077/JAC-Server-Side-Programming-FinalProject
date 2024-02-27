<?php
// profile.php
include("../model/ProductModel.php");

// same name with file name for router invoke
function ProductController($pathArray, $method, $data)
{

    // controller logic for api GET http://localhost:9000/api/v1/Product
    if (count($pathArray) == 0 && $method == "GET") {
        getAllProduct();
    }
    // controller logic for api GET http://localhost:9000/api/v1/Product/1
    if (count($pathArray) == 1 && $method == "GET") {
        getProductInfo($pathArray[0]);
    }

  // controller logic for api POST http://localhost:9000/api/v1/Product
    if (count($pathArray) == 0 && $method == "POST") {
        if (isset($data)) {
            createProduct($data);
        } else {
            echo json_encode(array("message" => "Invalid Json Body", "status" => false, "code" => "5201"), JSON_PRETTY_PRINT);
        }

    }

}

function getAllProduct(){

    $Product = getAllProducts();
    if (isset($Product)) {
        // $response['ProductID'] = $Product['Product_id'];
        // $response['FirstName'] = $Product['first_name'];
        // $response['LastName'] = $Product['last_name'];
        // $response['email'] = $Product['email'];
        // $response['telephone'] = $Product['telephone'];
        echo json_encode($Product, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("message" => "No result found.", "status" => false, "code" => "4101"), JSON_PRETTY_PRINT);
    }

}

function getProductInfo($id)
{
    $Product = getProductByID($id);

    if (isset($Product)) {
        $response['ProductID'] = $Product['Product_id'];
        $response['FirstName'] = $Product['first_name'];
        $response['LastName'] = $Product['last_name'];
        $response['email'] = $Product['email'];
        $response['telephone'] = $Product['phone_number'];
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("message" => "No result found.", "status" => false, "code" => "4101"), JSON_PRETTY_PRINT);
    }

}

function createProduct($data)
{
    $isCreate = createSingleProduct($data);
    if ($isCreate) {
        echo json_encode(array("message" => "Create Product Successfully!.", "status" => true, "code" => "5202"), JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("message" => "Create Product Failed.", "status" => false, "code" => "5202"), JSON_PRETTY_PRINT);
    }
}

?>