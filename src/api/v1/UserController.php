<?php
// profile.php
include("../model/userModel.php");

function CustomerController($pathArray,$method,$data){

}

function userList($chars)
{

    echo json_encode(array( "id" => $chars));

    getUserByID($chars);
}

?>