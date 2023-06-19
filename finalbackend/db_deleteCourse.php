<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db_connect.php'; // Include the database connection file

$postData = file_get_contents('php://input');
$param = json_decode($postData, true);
$id = $param[0]['id'];

$sql = "DELETE FROM course WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo json_encode('Update successfully');
}else{
    echo json_encode('fail to update course');
}          


$conn->close();
