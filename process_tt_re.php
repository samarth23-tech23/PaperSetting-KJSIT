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

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if each field is set before accessing its value
    $acedamic_year = isset($_POST['acedamic_year']) ? mysqli_real_escape_string($conn, $_POST['acedamic_year']) : '';
    $department_id = isset($_POST['department_id']) ? mysqli_real_escape_string($conn, $_POST['department_id']) : '';
    $test = isset($_POST['test']) ? mysqli_real_escape_string($conn, $_POST['test']) : '';
    $marks = isset($_POST['marks']) ? mysqli_real_escape_string($conn, $_POST['marks']) : '';
    $semester = isset($_POST['semester']) ? mysqli_real_escape_string($conn, $_POST['semester']) : '';
    $exam_date = isset($_POST['exam_date']) ? mysqli_real_escape_string($conn, $_POST['exam_date']) : '';
    $year = isset($_POST['year']) ? mysqli_real_escape_string($conn, $_POST['year']) : '';
    $course_code = isset($_POST['subject_id']) ? mysqli_real_escape_string($conn, $_POST['subject_id']) : '';
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : '';

    // Insert data into the exam_schedule_tt table
    $sql = "INSERT INTO exam_schedule_tt (academic_year, department_id, test, marks, semester, exam_date, year, status, course_code)
            VALUES ('$acedamic_year', '$department_id', '$test', '$marks', '$semester', '$exam_date', '$year', '$status', '$course_code')";

    if ($conn->query($sql) === TRUE) {
        echo "Record inserted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
