<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// include 'db_connect.php'; // Include the database connection file
$postData = file_get_contents('php://input');
$param = json_decode($postData, true);
$username = $param['username'];
$password = $param['password'];

$data = array();
echo 'sadasdsad'. isset($_SESSION['username']);
if (isset($_SESSION['username'])) {
    session_destroy();
    $data = array(
        'isLogout' => true
    );
} else {
    $data = array(
        'isLogout' => false
    );
}

echo json_encode($data);


// Return data as JSON response
// header('Content-Type: application/json');
// echo json_encode($subCategoryArr);

// Close the database connection
// $conn->close();
