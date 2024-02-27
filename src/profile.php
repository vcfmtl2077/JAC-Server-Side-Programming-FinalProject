<?php

include("db/config.php");
header("Content-Type: application/json");

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (isset($data["username"])) {
    $username = $data["username"];

    $query = $dbconn->prepare("SELECT o.order_id, o.order_date, p.name, od.quantity, o.total_amount, o.status
                               FROM Customer c
                               JOIN `Order` o ON c.customer_id = o.customer_id
                               JOIN OrderItem od ON o.order_id = od.order_id
                               JOIN Product p ON od.product_id = p.product_id
                               WHERE c.username = ?");

    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result) {
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        echo json_encode($orders, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("message" => "No orders found for user.", "status" => false));
    }

    $query->close();
} else {
    echo json_encode(array("message" => "Username not provided.", "status" => false));
}

$dbconn->close();
?>

    

    