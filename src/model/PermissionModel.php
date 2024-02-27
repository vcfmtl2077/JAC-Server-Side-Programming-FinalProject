<?php
include "../db/config.php";

function fetchPermissionByID($id)
{
    $stmt = $GLOBALS['dbconn']->prepare("Select * from RolePermission where role_id=?");
    $stmt->bind_param('s', $id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $customer = $result->fetch_assoc();
        return $customer;
    } else {
        echo json_encode(array("message" => "Qeury Failed!", "status" => false, "code" => "6101"), JSON_PRETTY_PRINT);
    }
}

function createSignleRolePermission($data)
{
    $insertKey = "(";
    $insertValue = "(";

    foreach ($data as $k => $v) {
        $insertKey .= $k.",";
        $insertValue .= "'$v',";
    }

    $insertKey = substr($insertKey,0,strlen($insertKey)-1).")";
    $insertValue = substr($insertValue,0,strlen($insertValue)-1).")";
    $sql = "INSERT INTO RolePermission ".$insertKey." VALUES ".$insertValue;
    // error_log(print_r($sql,true));
    $query=mysqli_query($GLOBALS['dbconn'],$sql);
    return $query;
}

function updateSignleRolePermission($data)
{
    $update='';
    foreach ($data as $k => $v) {
        if($k=="role_id"){
            continue;
        }
        $update .= "$k='$v',";
    }

    $update = substr($update,0,strlen($update)-1);
    $sql = "UPDATE RolePermission SET ".$update." WHERE (role_id='{$data['role_id']}')";
    // error_log(print_r($sql,true));
    $query=mysqli_query($GLOBALS['dbconn'],$sql);
    return $query;
}

function deleteSignleRolePermission($id)
{
    $sql = "DELETE FROM RolePermission WHERE (role_id='$id')";
    error_log(print_r($sql,true));
    $query=mysqli_query($GLOBALS['dbconn'],$sql);
    return $query;
}
?>