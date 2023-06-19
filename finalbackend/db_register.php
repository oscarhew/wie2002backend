<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db_connect.php'; // Include the database connection file

$postData = file_get_contents('php://input');
$param = json_decode($postData, true);
$username = $param['username'];
$email = $param['email'];
$password = $param['password'];

// check username
if (!is_null($username)) {
    $sql = "SELECT * FROM user where username='" . $username . "'";
    $result = $conn->query($sql);
    $data = array();
    if (($result->num_rows > 0)) {
        echo "Username has been taken. Please use another username";
    } else {
        $sql = "SELECT * FROM user where email='" . $email . "'";
        $result = $conn->query($sql);
        $data = array();
        if (($result->num_rows > 0)) {
            echo "Email has been taken. Please use another email";
        } else {
            // Fetch data from the database
            $sql = "INSERT INTO user (username, email, registrationDate, password)
        VALUES ('" . $username . "', '" . $email . "', '" . date("Y-m-d") . "','" . $password . "')";

            if ($conn->query($sql) === TRUE) {
                $sql = "SELECT * FROM user where username='" . $username . "'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $id = $row['id'];
                $data = array(
                    'userId' =>  $id
                );
                $data = json_encode($data);
                echo $data;
            } else {
                echo "Error updating record: ";
            }
        }
    }

    // Return data as JSON response
    // header('Content-Type: application/json');
    // echo json_encode($subCategoryArr);

    // Close the database connection
    $conn->close();
}
