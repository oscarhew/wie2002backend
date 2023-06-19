<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db_connect.php'; // Include the database connection file

$courseId = $_GET['param'];

// Fetch data from the database
$sql = "SELECT 
    course.id as id,
    course.image as `image`, 
    course.rate as rate, 
    course.title as title, 
    course.price as price, 
    course.rateCount as rateCount,
    course.overview as overview,
    course.description as `description`,
    course.whatWillLearn as `whatWillLearn`,
    course.courseIncludes as `courseIncludes`,
    sub_category.id as `subCategoryId` 
FROM course INNER JOIN author ON author.id = course.authorId 
INNER JOIN sub_category ON sub_category.id = course.categoryId 
WHERE course.id = " . $courseId;
$result = $conn->query($sql);

$data = array();
$categoryId = 1;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courseIncludes = $row['courseIncludes'];
        $courseIncludes = explode('_', $courseIncludes);
        $row['courseIncludes'] = $courseIncludes;

        $whatWillLearn = $row['whatWillLearn'];
        $whatWillLearn = explode('_', $whatWillLearn);
        $row['whatWillLearn'] = $whatWillLearn;

        $categoryId = $row['subCategoryId'];

        $data = $row;
    }
}
// echo $categoryId;
// Get course contents
// Fetch data from the database
$sql = "SELECT 
    lesson.title as title,
    lesson.content as content  
FROM lesson 
WHERE lesson.categoryId = " . $categoryId;

$result = $conn->query($sql);

$lessons = array();
$courseContents = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $lessonContent = $row['content'];
        $lessonContent = explode('_', $lessonContent);
        $row['content'] = $lessonContent;
        $lessons[] = $row;
    }
    $courseContents = $lessons;
}

$data['courseContent'] = $courseContents;

// Get instructor details
// Fetch data from the database
$sql = "SELECT 
    author.name as `name`,
    author.image as `image`,
    author.rating as `rating`,
    author.reviews as `reviews`,
    author.student as `student`,
    author.courses as `course`,
    author.introduction as `introduction`
FROM author INNER JOIN course ON author.ID = course.authorID 
WHERE course.id = " . $courseId;
$result = $conn->query($sql);

$instructiorDetail = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $instructiorDetail[] = $row;
        $data['instructor'] = $instructiorDetail[0];
        $data['author'] = $row['name'];
    }
}

$data['enrolled'] = false;


echo json_encode($data);


// Return data as JSON response
// header('Content-Type: application/json');
// echo json_encode($subCategoryArr);

// Close the database connection
$conn->close();
