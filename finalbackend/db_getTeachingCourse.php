<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db_connect.php'; // Include the database connection file

$userID = $_GET['param'];

// Fetch data from the database
$sql = "SELECT 
        course.id as id,
        course.title as courseName, 
        sub_category.name as category,
        author.name as author,
        course.description as description,
        course.price as price
 FROM course 
 INNER JOIN author ON course.authorId = author.id
 INNER JOIN sub_category ON course.categoryId = sub_category.id
 WHERE author.userId = " . $userID; 

$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['url'] = 'https://youtu.be/kqtD5dpn9C8';
        $data[] = $row;
    }
}

echo json_encode($data);

$conn->close();
?>