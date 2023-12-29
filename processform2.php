<?php
// Connect to your database
$mysqli = new mysqli("localhost", "root", "", "kjsit", 3307);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the subject_id from the form
    $subject_id = $_POST['subject_id'];

    // Loop through the submitted arrays
    $question_text = $_POST['question_text'];
    $image_path = $_POST['image_path'];
    $marks = $_POST['marks'];
    $co_levels = $_POST['co_level']; // Adjust the name to "co_level[]"
    $bt_levels = $_POST['bt_level']; // Adjust the name to "bt_level[]"

    foreach ($question_text as $key => $text) {
        // Check if the question_text is not empty (you can add additional validation here)
        if (!empty($text)) {
            // Insert the data into the 'questions' table
            $sql = "INSERT INTO questions (subject_id, question_text, image_path, marks) VALUES (?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("issi", $subject_id, $text, $image_path[$key], $marks[$key]);
            $stmt->execute();
            $stmt->close();

            // Retrieve the auto-generated question_id for the last inserted question
            $question_id = $mysqli->insert_id;

            // Insert data into the 'attributes' table
            $sql = "INSERT INTO attributes (question_id, co_number, bt_level) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($sql);

            // Use the CO and BT values as they are
            $stmt->bind_param("iss", $question_id, $co_levels[$key], $bt_levels[$key]);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Close the database connection
$mysqli->close();
?>
