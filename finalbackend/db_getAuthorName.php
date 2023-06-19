<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db_connect.php'; // Include the database connection file

$userID = $_GET['param'];

// Fetch data from the database
$sql = "SELECT name FROM author WHERE userId = $userID";

$result = $conn->query($sql);
$data = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data .= $row['name'];
    }
}

echo json_encode($data);

$conn->close();
?>