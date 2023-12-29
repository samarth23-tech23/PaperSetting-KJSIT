<?php
// Start a session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}

// Connect to your MySQL database
$mysqli = new mysqli("localhost", "root", "", "kjsit", 3307);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve the teacher's subjects and name
$teacher_id = $_SESSION['teacher_id'];
$sql = "SELECT s.subject_id, s.subject_name, t.teacher_name FROM Subjects s
        INNER JOIN Teacher_Subjects ts ON s.subject_id = ts.subject_id
        INNER JOIN Teacher t ON ts.teacher_id = t.teacher_id
        WHERE t.teacher_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

// Display the teacher's name, subjects, and buttons
echo "<h1>Teacher Profile</h1>";
echo "<p>Welcome, Teacher!</p>";

if ($result->num_rows > 0) {
    echo "<h2>Teacher Information:</h2>";
    $row = $result->fetch_assoc();
    echo "<p>Teacher Name: " . $row['teacher_name'] . "</p>";

    echo "<h2>Subjects Taught:</h2>";
    echo "<ul>";
    do {
        $subject_id = $row['subject_id'];
        echo "<li>" . $row['subject_name'] . 
             " <a href='co_bt.php?subject_id=" . $subject_id . "'>Set CO and BT Levels</a> " .
             " <a href='form2.php?subject_id=" . $subject_id . "&teacher_id=" . $teacher_id . "'>Set Question Bank</a> " ;
    } while ($row = $result->fetch_assoc());
    echo "</ul>";
} else {
    echo "<p>No subjects found for this teacher.</p>";
}

$mysqli->close();
?>
