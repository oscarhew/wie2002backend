<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db_connect.php'; // Include the database connection file

$postData = file_get_contents('php://input');
$param = json_decode($postData, true);
$userId = $param['userId'];
$courseId = $param['courseId'];

// Fetch data from the database
$sql = "INSERT INTO enrollment (userId, courseId, enrollmentDate, progress) 
VALUES (" . $userId . ", " . $courseId . ", ". date("Y-m-d") . ", 0)";

if ($conn->query($sql) === TRUE) {
    echo "Update query executed successfully.";
} else {
    echo "Error updating record: " . $conn->error;
}


// Return data as JSON response
// header('Content-Type: application/json');
// echo json_encode($subCategoryArr);

// Close the database connection
$conn->close();
