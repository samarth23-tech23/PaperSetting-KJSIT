<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle form submission here
    $subjectId = $_POST["subject_id"];
    
    $questionTexts = $_POST["question_text"];
    $imagePaths = $_POST["image_path"];
    $marks = $_POST["marks"];
    $coNumbers = $_POST["co_number"];
    $btLevels = $_POST["bt_level"];

    // Create a database connection (You should use your database credentials)
    $mysqli = new mysqli("localhost", "root", "", "kjsit", 3307);

    // Check the connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Loop through the submitted data and insert it into the database
    foreach ($questionTexts as $index => $questionText) {
        // You should perform proper database insertions here, using prepared statements for security
        $query = "INSERT INTO questions (subject_id, question_text, image_path, marks, co_number, bt_level) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("isssis", $subjectId, $questionText, $imagePaths[$index], $marks[$index], $coNumbers[$index], $btLevels[$index]);

        if ($stmt->execute() === false) {
            echo "Error: " . $stmt->error;
        }
    }

    // Close the database connection
    $mysqli->close();

    // Redirect or display a success message
    echo "Data inserted sucessfullly";
} else {
    // Handle invalid requests or provide an error message
    echo "Invalid request!";
}
?>
