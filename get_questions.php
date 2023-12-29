<?php
// Establish a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kjsit";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve CO and BT Level from GET parameters
$coId = $_GET['co_id'];
$btId = $_GET['bt_id'];
$section = $_GET['section'];

// Function to get questions based on CO and BT Level
function getQuestionsFromDB($conn, $coId, $btId, $section)
{
    // Define the marks based on the section
    $marks = ($section == 1) ? 2 : (($section == 9) ? 4 : 8);

    // Adjust the SQL query to filter questions based on marks
    $sql = "SELECT q.question_id FROM attributes a
            JOIN questions q ON a.question_id = q.question_id
            WHERE a.co_id = $coId AND a.bt_id = $btId AND q.marks = $marks";

    $result = $conn->query($sql);

    $questions = array();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $questionId = $row['question_id'];
            // Now, fetch question text and marks using $questionId
            $questionDetails = getQuestionTextFromDB($conn, $questionId);
            if ($questionDetails !== null) {
                $questions[] = $questionDetails;
            }
        }
    }

    return $questions;
}

// Function to get question text and marks from the database
function getQuestionTextFromDB($conn, $questionId)
{
    $sql = "SELECT question_text, marks FROM questions WHERE question_id = $questionId";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return array(
            'question_text' => $row['question_text'],
            'marks' => $row['marks']
        );
    }

    return null;
}

try {
    // Fetch questions based on CO and BT Level
    $questions = getQuestionsFromDB($conn, $coId, $btId, $section);

    // Close the database connection
    $conn->close();

    // Return questions and marks as JSON response
    header('Content-Type: application/json');
    echo json_encode($questions);
} catch (Exception $e) {
    // Handle any exceptions and return a JSON error response
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
