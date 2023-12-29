<?php
// Assuming you have a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kjsit";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$departmentId = $_GET['department_id'];

$sql = "SELECT subject_id, subject_name,Course_code FROM subjects WHERE department_id = $departmentId";

$result = $conn->query($sql);

$subjects = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($subjects);
?>
