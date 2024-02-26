<?php
// profile.php
include("../model/BlogerModel.php");

// same name with file name for router invoke
function BlogerController($pathArray, $method, $data)
{

    // controller logic for api GET http://localhost:9000/api/v1/Bloger/1
    if (count($pathArray) == 1 && $method == "GET") {
        getBlogerInfo($pathArray[0]);
    }

  // controller logic for api POST http://localhost:9000/api/v1/Bloger
    if (count($pathArray) == 0 && $method == "POST") {
        if (isset($data)) {
            createBloger($data);
        } else {
            echo json_encode(array("message" => "Invalid Json Body", "status" => false, "code" => "5201"), JSON_PRETTY_PRINT);
        }

    }

}

function getBlogerInfo($id)
{
    $Bloger = fetchBlogerByID($id);

    if (isset($Bloger)) {
        $response['BlogerID'] = $Bloger['Bloger_id'];
        $response['FirstName'] = $Bloger['first_name'];
        $response['LastName'] = $Bloger['last_name'];
        $response['email'] = $Bloger['email'];
        $response['telephone'] = $Bloger['telephone'];
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("message" => "No result found.", "status" => false, "code" => "4101"), JSON_PRETTY_PRINT);
    }

}

function createBloger($data)
{
    $isCreate = createSingleBloger($data);
    if ($isCreate) {
        echo json_encode(array("message" => "Create Bloger Successfully!.", "status" => true, "code" => "5202"), JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("message" => "Create Bloger Failed.", "status" => false, "code" => "5202"), JSON_PRETTY_PRINT);
    }
}

?>