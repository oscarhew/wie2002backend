<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db_connect.php'; // Include the database connection file

$postData = file_get_contents('php://input');
$param = json_decode($postData, true);
$courseId = $param['courseId'];
$userId = $param['userId'];
$index = $param['index'];

// Fetch data from the database
$sql = "SELECT 
    course.categoryId 
FROM course 
WHERE course.id = " . $courseId;
$result = $conn->query($sql);
$categoryId = 1;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categoryId = $row['categoryId'];
    }
}

$sql = "SELECT 
    COUNT(id) as totalProgressCount 
    FROM lesson 
    WHERE categoryId = '" . $categoryId . "'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$totalProgressCount = $row['totalProgressCount'];

$sql = "SELECT 
    id as enrollmentId 
    FROM enrollment 
    WHERE courseId = '" . $courseId . "' AND userId = '". $userId . "'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$enrollmentId = $row['enrollmentId'];

$currentProgress = ceil(($index / $totalProgressCount) * 100);

$sql = "UPDATE enrollment
    SET progress = " . (int)$currentProgress.
    " WHERE id = '" . $enrollmentId . "'";

if ($conn->query($sql) === TRUE) {
    echo json_encode('Update successfully'. $totalProgressCount);
}else{
    echo json_encode('fail to update progress');
}

// Return data as JSON response
// header('Content-Type: application/json');
// echo json_encode($subCategoryArr);

// Close the database connection
$conn->close();
