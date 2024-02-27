<?php
include "../db/config.php";

// CRUD--C
function createSingleProduct($data)
{
    $stmt = $GLOBALS['dbconn']->prepare("INSERT INTO `Product` (`Product_id`, `first_name`, `last_name`, `email`, `department`, `phone_number`, `address`) 
    VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('issssss', $data['ProductID'],$data['email'],$data['FirstName'],$data['LastName'],$data['department'],$data['phone_number'],$data['address']);
    return mysqli_stmt_execute($stmt);
}



//CRUD - R
function getProductByID($id)
{
    $stmt = $GLOBALS['dbconn']->prepare("Select * from Product where Product_id=?");
    $stmt->bind_param('s', $id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $Product = $result->fetch_assoc();
        return $Product;
    } else {
        echo json_encode(array("message" => "Qeury Failed!", "status" => false, "code" => "6101"), JSON_PRETTY_PRINT);
    }
}


// CRUD - R ALL
function getAllProducts() {
    $sql = "SELECT * FROM Product";
    $result = $GLOBALS['dbconn']->query($sql);
    $Products = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $Products[] = $row;
        }
    }
    return $Products;
}

// CRUD - U
function updateProduct($id, $email) {
    $sql = "UPDATE Product SET  email='$email' WHERE id=$id";
    if ($GLOBALS['dbconn']->query($sql) === TRUE) {
        return "Product updated successfully";
    } else {
        return "Error updating Product: " . $GLOBALS['dbconn']->error;
    }
}

// CRUD - D
function deleteProduct($id) {
    $sql = "DELETE FROM Products WHERE id=$id";
    if ($GLOBALS['dbconn']->query($sql) === TRUE) {
        return "Product deleted successfully";
    } else {
        return "Error deleting Product: " . $GLOBALS['dbconn']->error;
    }
}

?>
