<?php
// Establish a database connection (update with your database credentials)
$mysqli = new mysqli("localhost", "root", "", "kjsit", 3307);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve the selected teacher's ID from the request
$teacher_id = isset($_GET['teacher_id']) ? intval($_GET['teacher_id']) : 0;

if ($teacher_id === 0) {
    // Handle invalid or missing teacher ID
    echo json_encode([]);
    exit();
}

// Fetch subjects associated with the selected teacher
$sql = "SELECT s.subject_id, s.subject_name
        FROM Subjects s
        INNER JOIN Teacher_Subjects ts ON s.subject_id = ts.subject_id
        WHERE ts.teacher_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

$subjects = [];

while ($row = $result->fetch_assoc()) {
    $subjects[$row['subject_id']] = $row['subject_name'];
}

$mysqli->close();

// Return the subjects as JSON
echo json_encode($subjects);
?>
