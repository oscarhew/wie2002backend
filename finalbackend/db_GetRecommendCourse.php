<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db_connect.php'; // Include the database connection file

// Fetch data from the database
$sql = "SELECT 
    course.id as id,
    course.image as `image`, 
    author.name as `author`, 
    course.rate as rate, 
    course.title as title, 
    course.price as price, 
    course.rateCount as rateCount 
FROM course INNER JOIN author ON author.ID = course.authorID ORDER BY course.rateCount DESC";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}


echo json_encode($data);


// Return data as JSON response
// header('Content-Type: application/json');
// echo json_encode($subCategoryArr);

// Close the database connection
$conn->close();
