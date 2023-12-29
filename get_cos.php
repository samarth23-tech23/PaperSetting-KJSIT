<?php
// Connect to your database
$mysqli = new mysqli("localhost", "root", "", "kjsit", 3307);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve the selected subject_id from the AJAX request
$selectedSubject = $_GET['subject_id'];

// Fetch CO values for the selected subject from your database
$sql = "SELECT co1, co2, co3, co4, co5, co6 FROM subjects WHERE subject_id = $selectedSubject";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Create an associative array to store CO values
    $coValues = array();
    
    // Check each CO field and include it in the array if it's not null
    if (!is_null($row['co1'])) {
        $coValues['CO1'] = $row['co1'];
    }
    if (!is_null($row['co2'])) {
        $coValues['CO2'] = $row['co2'];
    }
    if (!is_null($row['co3'])) {
        $coValues['CO3'] = $row['co3'];
    }
    if (!is_null($row['co4'])) {
        $coValues['CO4'] = $row['co4'];
    }
    if (!is_null($row['co5'])) {
        $coValues['CO5'] = $row['co5'];
    }
    if (!is_null($row['co6'])) {
        $coValues['CO6'] = $row['co6'];
    }

    // Convert the associative array to JSON and send as the response
    echo json_encode($coValues);
} else {
    echo "No CO values found for the selected subject.";
}

$mysqli->close();
?>
