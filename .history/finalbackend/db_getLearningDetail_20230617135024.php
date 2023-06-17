<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db_connect.php'; // Include the database connection file

$courseId = $_GET['param'];

// Fetch data from the database
$sql = "SELECT 
    course.id as id,
    lesson.title as category,
    lesson.content as title,
    lesson.video as link 
FROM lesson INNER JOIN course ON lesson.courseId = course.id  
WHERE course.id = " . $courseId;
$result = $conn->query($sql);

$data = array();
$categoryDetailArr = array();
$singleListVidoes = array();
$allListVideos = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $listVideos = array();

        $category = $row['category'];
        $titleName = explode('_', $row['title']);
        $titleLink = explode('_', $row['link']);
        
        $json_arr = array();

        for($i = 0; $i < sizeof($titleName); $i++){
            $json_arr = array(
                'title' => $titleName[$i],
                'link' => $titleLink[$i]
            );
            $listVideos[] = $json_arr;
        }
        
        $singleListVidoes = array(
            'category' => $row['category'],
            'videos' => $listVideos
        );

        $allListVideos[] = $singleListVidoes;

        $data[] = $row;
        break;
    }
}

$finalJson =  array(
    'viewed' => 4,
    'total' => 10,
    'listVideos' => $allListVideos
);

echo json_encode($finalJson);


// Return data as JSON response
// header('Content-Type: application/json');
// echo json_encode($subCategoryArr);

// Close the database connection
$conn->close();
