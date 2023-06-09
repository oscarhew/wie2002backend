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
    $sql = "SELECT course.id as id,
    course.title, 
    course.description, 
    course.image, 
    author.name as authorName, 
    sub_category.id as subId, 
    sub_category.name,
    course.price as price,
    course.rate as rate,
    course.rateCount as rateCount 
    FROM course". " INNER JOIN sub_category ON sub_category.id = course.categoryId INNER JOIN author ON author.id = course.authorId" ." WHERE sub_category.id=". $singleCategory['id'];

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['author'] = $row['authorName'];
            $data[] = $row;
            $subCategoryName = $row['name'];
            $displayName = "";
            switch($subCategoryName){
                case "Python":
                    $displayName = "python";
                    break;
                case "Excel":
                    $displayName = "excel";
                    break;
                case "Web Developer":
                    $displayName = "webDeveloper";
                    break;
                case "JavaScript":
                    $displayName = "javaScript";
                    break;
                case "Data Science":
                    $displayName = "dataScience";
                    break;
                case "Amazon AWS":
                    $displayName = "aws";
                    break;
            }
        }
        
        $json_arr = array(
            'displayName' => $displayName,
            'category' => $subCategoryName,
            'courses' => $data
        );
    }
    $data = array();
    if($result -> num_rows != 0){
        array_push($full_json, $json_arr);
    }
    
    $json_arr = array();
}

echo json_encode($full_json);


// Return data as JSON response
// header('Content-Type: application/json');
// echo json_encode($subCategoryArr);

// Close the database connection
$conn->close();
