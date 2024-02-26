<?php
include "../db/config.php";

function fetchBlogerByID($id)
{
    $stmt = $GLOBALS['dbconn']->prepare("Select * from Blog where bloger_id=?");
    $stmt->bind_param('s', $id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $Bloger = $result->fetch_assoc();
        return $Bloger;
    } else {
        echo json_encode(array("message" => "Qeury Failed!", "status" => false, "code" => "6101"), JSON_PRETTY_PRINT);
    }
}

function createSingleBloger($data)
{
    $stmt = $GLOBALS['dbconn']->prepare("INSERT INTO Bloge (blog_id, title, content, author ,publish_date) 
    VALUES (?, ?,?,?,?)");
    $stmt->bind_param('isssssss', $data['blog_id'],$data['title'],$data['title'],$data['author'],$data['publish_date']);
    return mysqli_stmt_execute($stmt);
}
?>