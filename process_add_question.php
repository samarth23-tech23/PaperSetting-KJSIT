<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have a way to retrieve the teacher_id
    $teacher_id = $_POST['teacher_id'];

    $mysqli = new mysqli("localhost", "root", "", "kjsit", 3307);
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $question_text = $_POST['question_text'];
    $image_path = $_POST['image_path'];
    $marks = $_POST['marks'];
    $subject_id = $_POST['subject_id'];
    $co_id = $_POST['co_id'];
    $bt_id = $_POST['bt_id'];

    // Prepare the SQL statement to insert the new question
    $insert_question_query = "INSERT INTO questions (question_text, image_path, marks, subject_id, teacher_id) VALUES (?, ?, ?, ?, ?)";
    
    // Create a prepared statement
    $stmt_question = $mysqli->prepare($insert_question_query);

    if ($stmt_question) {
        // Bind parameters and execute the statement
        $stmt_question->bind_param("sssii", $question_text, $image_path, $marks, $subject_id, $teacher_id);
        $stmt_question->execute();

        // Check if the question is added successfully
        if ($stmt_question->affected_rows > 0) {
            $question_id = $stmt_question->insert_id;
            
            // Insert into attributes table to map CO and BT to the question
            $insert_attribute_query = "INSERT INTO attributes (question_id, co_id, bt_id) VALUES (?, ?, ?)";
            $stmt_attributes = $mysqli->prepare($insert_attribute_query);

            if ($stmt_attributes) {
                $stmt_attributes->bind_param("iii", $question_id, $co_id, $bt_id);
                $stmt_attributes->execute();

                if ($stmt_attributes->affected_rows > 0) {
                    echo "Question and attributes added successfully!";
                } else {
                    echo "Failed to add attributes. Error: " . $stmt_attributes->error;
                }

                $stmt_attributes->close();
            } else {
                echo "Attributes statement preparation error: " . $mysqli->error;
            }
        } else {
            echo "Failed to add the question. Error: " . $stmt_question->error;
        }

        $stmt_question->close();
    } else {
        echo "Question statement preparation error: " . $mysqli->error;
    }

    $mysqli->close();
} else {
    echo "Invalid request method!";
}
?>
