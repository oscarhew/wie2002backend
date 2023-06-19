<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db_connect.php'; // Include the database connection file

$postData = file_get_contents('php://input');
$param = json_decode($postData, true);
$userID = $param['id'];
$courseName = $param['title'];
$authorName = $param['author'];
$price = $param['price'];
$description = $param['description'];
$categoryName = $param['category'];
$url = $param['urlInput'];
$overview = 'Join the Complete Web Development Bootcamp: the highest-rated, comprehensive course to become a full-stack web developer. Learn the latest tools and technologies used by top companies like Apple, Google, and Netflix. With animated explanations, real-world projects, and 65+ hours of video tutorials, even beginners can become masters. Save $12,000 compared to in-person bootcamps. Start coding and change your life today!';
$whatWillLearn = 'Build 16 web development projects for your portfolio, ready to apply for junior developer jobs._Learn the latest technologies, including Javascript, React, Node and even Web3 development._After the course you will be able to build ANY website you want._Build fully-fledged websites and web apps for your startup or business._Work as a freelance web developer.';
$courseInclude = '23 hours on-demand video_6 coding exercises_Full lifetime access_Certificate of completion';
$img = 'https://img-b.udemycdn.com/course/240x135/1565838_e54e_16.jpg';

//get course id
//$sql = "select id from course order by id DESC limit 1;";
//$sqlCourseID = mysqli_query($conn, $sql);
$authorID = 0;

$sqlCourseId = "SELECT * FROM course order by id DESC limit 1";
$resultCourse = mysqli_query($conn, $sqlCourseId);
if (!$resultCourse) {
    die('Failed to fetch course ID: ' . mysqli_error($conn));
}
$rowCourse = mysqli_fetch_assoc($resultCourse);
$sqlCourseID = $rowCourse['id'];
$sqlCourseID += 1;

//get author id
$sqlAuthor = "SELECT id FROM author WHERE name = '$authorName'";
$resultAuthor = mysqli_query($conn, $sqlAuthor);
//if new author then create a new entry, must be user since they can login
if ($resultAuthor->num_rows == 0) {
    $sqlInsertAuthor = "INSERT INTO author (userId, name, image, rating, rateCount, student, courses, introduction, reviews)
                        VALUES ($userID, '$authorName', 'https://img-b.udemycdn.com/user/200_H/31334738_a13c_3.jpg', 0.0, 0, 0, 1, 'Allow me to introduce This Instructor, a remarkable individual who brings a unique set of skills and qualities to the table. With their expertise in various subjects, This Instructor has consistently demonstrated an exceptional ability to deliver engaging and informative lessons to students. They possess a profound understanding of their field and have successfully guided numerous students towards academic success. What sets This Instructor apart is their unwavering dedication to fostering a supportive and inclusive learning environment, where students feel encouraged to ask questions and actively participate. With their passion for teaching and commitment to student growth, This Instructor is sure to make a positive and lasting impact on those they teach.', 0)";
    if (mysqli_query($conn, $sqlInsertAuthor)) {
        $authorId = mysqli_insert_id($conn);
        $authorID = $authorId;
    } else {
        die('Error creating new author: ' . mysqli_error($conn));
    }
}

$sqlAuthor = "SELECT id FROM author WHERE name = '$authorName'";
$resultAuthor = mysqli_query($conn, $sqlAuthor);
if (!$resultAuthor) {
    die('Failed to fetch author ID: ' . mysqli_error($conn));
}
$rowAuthor = mysqli_fetch_assoc($resultAuthor);
$authorID = $rowAuthor['id'];

$sqlCategory = "SELECT id FROM sub_category WHERE name = '$categoryName'";
$resultCategory = mysqli_query($conn, $sqlCategory);
if (!$resultCategory) {
    die('Failed to fetch category ID: ' . mysqli_error($connection));
}
$rowCategory = mysqli_fetch_assoc($resultCategory);
$categoryId = $rowCategory['id'];

$sql = "INSERT INTO course 
        (authorId, categoryId, rate, title, price, rateCount, overview, description, whatWillLearn, courseIncludes, createdDate, image)
        VALUES ($authorID, $categoryId, 0.00, '$courseName', $price, 0, '$overview', '$description', '$whatWillLearn', 
            '$courseInclude', " . date('Y-m-d') . ", '$img')";
if ($conn->query($sql) === TRUE) {
    echo json_encode('Update successfully');
} else {
    echo json_encode('fail to update course');
}

$conn->close();
