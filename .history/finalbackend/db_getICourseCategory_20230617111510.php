<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db_connect.php'; // Include the database connection file

// Fetch data from the database
$sql = "SELECT * FROM sub_category"; // Replace with your table name
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Fetch all courses based on sub_category
$subCategoryArr = array();
foreach($data as $row){
    $singleData = array(
        'id' => $row['id'],
        'subCategoryName' => $row['name']
    );
    array_push($subCategoryArr, $singleData);
}

// print_r($subCategoryArr);

$data = array();
$json_arr = array();
$full_json = array();
foreach($subCategoryArr as $singleCategory){
    $subCategoryName;
    // need add course.image_url, it's remove for now
    $sql = "SELECT course.id, course.title, course.description, course.image, course.authorId, sub_category.id, sub_category.name
    FROM course". " INNER JOIN sub_category ON sub_category.id = course.categoryId " ." WHERE sub_category.id=". $singleCategory['id'];
    
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
            $subCategoryName = $row['sub_category_name'];
        }
        
        $json_arr = array(
            'category' => $subCategoryName,
            'courses' => $data
        );
    }
    $data = array();
    array_push($full_json, $json_arr);
    $json_arr = array();
}

echo json_encode($full_json);


// Return data as JSON response
// header('Content-Type: application/json');
// echo json_encode($subCategoryArr);

// Close the database connection
$conn->close();
