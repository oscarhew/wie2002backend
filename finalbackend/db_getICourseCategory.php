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
        'id' => $row['sub_category_id'],
        'subCategoryName' => $row['sub_category_name']
    );
    array_push($subCategoryArr, $singleData);
}

// print_r($subCategoryArr);

$data = array();
$json_arr = array();
$full_json = array();
foreach($subCategoryArr as $singleCategory){
    $subCategoryName;
    $sql = "SELECT course.course_id, course.course_name, course.image_url, course.course_description, course.instructor_id, sub_category.sub_category_id, sub_category.sub_category_name
    FROM course". " INNER JOIN sub_category ON sub_category.sub_category_id = course.sub_category_id " ." WHERE sub_category.sub_category_id=". $singleCategory['id'];
    
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
