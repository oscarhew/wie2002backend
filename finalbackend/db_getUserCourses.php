<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db_connect.php'; // Include the database connection file

$postData = file_get_contents('php://input');
$param = json_decode($postData, true);
$username = $param['username'];
$category = $param['category'];


// Fetch data from the database

$sql = "SELECT 
    course.id as id,
    course.title as courseName,
    course.description as `description`,
    course.image as img,
    enrollment.progress as progress 
FROM course INNER JOIN enrollment ON enrollment.courseId = course.Id 
INNER JOIN user ON enrollment.userId = user.Id 
INNER JOIN sub_category ON sub_category.Id = course.categoryId 
WHERE user.username = '" . $username . "'";

if (!is_null($category)){
    $sql = $sql . " AND sub_category.name = '". $category. "'";
}


$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $progress = (int)$row['progress'];
        $row['progress'] = $progress;
        $data[] = $row;
        
    }
}

echo json_encode($data);


// Return data as JSON response
// header('Content-Type: application/json');
// echo json_encode($subCategoryArr);

// Close the database connection
$conn->close();
