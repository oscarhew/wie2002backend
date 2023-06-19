<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db_connect.php'; // Include the database connection file

$postData = file_get_contents('php://input');
$param = json_decode($postData, true);
$courseName = $param['title'];
$authorName = $param['author'];
$price = $param['price'];
$description = $param['description'];
$categoryName = $param['category'];
$url = $param['urlInput'];

//get author id
$sqlAuthor = "SELECT id FROM author WHERE name = '$authorName'";
$resultAuthor = mysqli_query($conn, $sqlAuthor);
//if new author then create a new entry, must be user since they can login
if (!$resultAuthor) {
    //get user id first
    $sqlUserId = "SELECT id FROM user WHERE username = '$authorName'";
    $sqlInsertAuthor = "INSERT INTO author (userId, name, image, rating, rateCount, student, courses, introduction, reviews)
                        VALUES ($sqlUserId, $authorName, 'https://img-b.udemycdn.com/course/240x135/1565838_e54e_16.jpg', 0.0, 0, 0, 1, 'abc', 0)";
    if (mysqli_query($conn, $sqlInsertAuthor)) {
        $authorId = mysqli_insert_id($conn);
    } else {
        die('Error creating new author: ' . mysqli_error($conn));
    }
}
$rowAuthor = mysqli_fetch_assoc($resultAuthor);
$authorId = $rowAuthor['id'];
echo $authorId;

//get category id
//Python is normal, but the rest will have '}' at the back
// if($categoryName != 'Python'){
//     $categoryName = substr($categoryName, 0, -1);
// }
$sqlCategory = "SELECT id FROM sub_category WHERE name = '$categoryName'";
$resultCategory = mysqli_query($conn, $sqlCategory);
if (!$resultCategory) {
    die('Failed to fetch category ID: ' . mysqli_error($connection));
}
$rowCategory = mysqli_fetch_assoc($resultCategory);
$categoryId = $rowCategory['id'];
echo $categoryId;

$sql = "UPDATE course
        SET title =  '$courseName',
            price = $price,
            authorId = $authorId,
            categoryId = $categoryId,
            description = '$description'
        WHERE id = $id";
if ($conn->query($sql) === TRUE) {
    echo json_encode('Update successfully');
}else{
    echo json_encode('fail to update course');
}          

$sql = "UPDATE course
        SET title =  '$courseName' ,
            price = $price,
            description = '$description'
        WHERE id = $id";
if ($conn->query($sql) === TRUE) {
    echo json_encode('Update successfully');
}else{
    echo json_encode('fail to update course');
}      

$conn->close();
