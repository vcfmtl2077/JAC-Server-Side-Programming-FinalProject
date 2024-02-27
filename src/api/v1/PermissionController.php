<?php
// profile.php
include("../model/PermissionModel.php");

// same name with file name for router invoke
function PermissionController($pathArray, $method, $data)
{

    // controller logic for api GET http://localhost:9000/api/v1/permission/1
    if (count($pathArray) == 1 && $method == "GET") {
        getPermissionInfo($pathArray[0]);
    }

  // controller logic for api POST http://localhost:9000/api/v1/permission
    if (count($pathArray) == 0 && $method == "POST") {
        if (isset($data)) {
            createRolePermission($data);
        } else {
            echo json_encode(array("message" => "Invalid Json Body", "status" => false, "code" => "5201"), JSON_PRETTY_PRINT);
        }

    }

    if (count($pathArray) == 0 && $method == "PUT") {
        if (isset($data)) {
            updateRolePermission($data);
        } else {
            echo json_encode(array("message" => "Invalid Json Body", "status" => false, "code" => "5201"), JSON_PRETTY_PRINT);
        }

    }

    if (count($pathArray) == 1 && $method == "DELETE") {
        deleteRolePermission($pathArray[0]);
    }

}

function getPermissionInfo($id)
{
    $permission = fetchPermissionByID($id);

    if (isset($permission)) {
        echo json_encode($permission, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("message" => "No result found.", "status" => false, "code" => "4101"), JSON_PRETTY_PRINT);
    }

}

function createRolePermission($data)
{
    $isCreate = createSignleRolePermission($data);
    if ($isCreate) {
        echo json_encode(array("message" => "Create RolePermission Successfully!.", "status" => true, "code" => "5202"), JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("message" => "Create RolePermission Failed.", "status" => false, "code" => "5202"), JSON_PRETTY_PRINT);
    }
}

function updateRolePermission($data)
{
    $isCreate = updateSignleRolePermission($data);
    if ($isCreate) {
        echo json_encode(array("message" => "Update RolePermission Successfully!.", "status" => true, "code" => "5202"), JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("message" => "Update RolePermission Failed.", "status" => false, "code" => "5202"), JSON_PRETTY_PRINT);
    }
}

function deleteRolePermission($data)
{
    $isCreate = deleteSignleRolePermission($data);
    if ($isCreate) {
        echo json_encode(array("message" => "Delete RolePermission Successfully!.", "status" => true, "code" => "5202"), JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("message" => "Delete RolePermission Failed.", "status" => false, "code" => "5202"), JSON_PRETTY_PRINT);
    }
}

?>