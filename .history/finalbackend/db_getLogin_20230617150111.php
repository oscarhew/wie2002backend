<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db_connect.php'; // Include the database connection file
$postData = file_get_contents('php://input');
$param = json_decode($postData, true);
$username = $param['username'];
$password = $param['password'];

// Fetch data from the database
$sql = "SELECT * FROM user WHERE username = '" . $username . "'";

$result = $conn->query($sql);

$data = array();
session_start();


if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $storedPassword = $row["password"];

    if ($password === $storedPassword) {
        // Username and password combination is correct

        $data = array(
            'isLogin' => true
        );
        $_SESSION["username"] = $username;
    } else {
        // Invalid password

        $data = array(
            'isLogin' => false
        );
    }
} else {
    $data = array(
        'isLogin' => false
    );
}



echo json_encode($data);


// Return data as JSON response
// header('Content-Type: application/json');
// echo json_encode($subCategoryArr);

// Close the database connection
$conn->close();
