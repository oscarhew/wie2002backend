<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db_connect.php'; // Include the database connection file

$username = $_GET['param'];

// Fetch data from the database
$sql = "SELECT 
    course.id as id,
    course.title as courseName,
    course.description as `description`,
    course.image as img,
    sub_category.Id as subCategoryId,
    sub_category.name as subCategoryName 
FROM course INNER JOIN enrollment ON enrollment.courseId = course.Id 
INNER JOIN user ON enrollment.userId = user.Id 
INNER JOIN sub_category ON sub_category.id = course.categoryId 
WHERE user.username = '" . $username . "'";

$result = $conn->query($sql);

$data = array();
$categoryDetailArr = array();
$singleListVidoes = array();
$allListVideos = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categoryId = $row['subCategoryName'];
        $data[] = $categoryId;
        
    }
}




echo json_encode($data);


// Return data as JSON response
// header('Content-Type: application/json');
// echo json_encode($subCategoryArr);

// Close the database connection
$conn->close();
