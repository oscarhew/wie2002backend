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
$sql = "SELECT * 
FROM enrollment  
WHERE enrollment.courseId = " . $courseId ." AND enrollment.userId = ". $userId;
$result = $conn->query($sql);

$data = array();
if ($result->num_rows == 0) {
    echo json_encode('true');
}else{
    echo json_encode('false');
}



// Return data as JSON response
// header('Content-Type: application/json');
// echo json_encode($subCategoryArr);

// Close the database connection
$conn->close();
