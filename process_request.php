<?php
// process_request.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $exam_time = $_POST["exam_time"];
    $department_id = $_POST["department_id"];
    $exam_type = $_POST["exam_type"];
    $semester = $_POST["semester"];
    $subject_id = $_POST["subject_id"];
    $exam_date = $_POST["exam_date"];
    $marks = $_POST["marks"];
    $status = $_POST["status"];
    $year = $_POST["year"];

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

    // Prepare and execute SQL statement to insert data into exam_schedule table
    $sql = "INSERT INTO exam_schedule (exam_time, department_id, exam_type, semester, subject_id, exam_date, max_marks, status, year) 
            VALUES ('$exam_time', $department_id, '$exam_type', '$semester', $subject_id, '$exam_date', $marks, '$status', '$year')";

    if ($conn->query($sql) === TRUE) {
        // Insertion successful
        echo("Data inserted Succesfully");
        exit();
    } else {
        // Error in insertion
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    // Handle invalid request method
    header("HTTP/1.1 405 Method Not Allowed");
    echo "Method Not Allowed";
}
?>
