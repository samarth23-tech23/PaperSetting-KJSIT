<?php
// Establish a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kjsit";
$port = 3307; // Change this to your MySQL port if needed

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $templateName = $_POST['template_name'];
    $numSections = $_POST['num_sections'];

    // Initialize arrays with default values
    $totalRowsPerSection = isset($_POST['total_rows_per_section']) ? $_POST['total_rows_per_section'] : [];
    $rowsToSolvePerSection = isset($_POST['rows_to_solve_per_section']) ? $_POST['rows_to_solve_per_section'] : [];

    // Validate data (you may add more validation as needed)
    if (empty($templateName) || empty($numSections) || count($totalRowsPerSection) != $numSections || count($rowsToSolvePerSection) != $numSections) {
        echo "Invalid form data. Please fill in all fields.";
        exit;
    }

    // Convert arrays to JSON strings for database storage
    $totalRowsPerSectionJSON = json_encode($totalRowsPerSection);
    $rowsToSolvePerSectionJSON = json_encode($rowsToSolvePerSection);

    // Insert a new record into the database
    $insertQuery = "INSERT INTO `template` (`template_name`, `num_sections`, `total_rows_per_section`, `rows_to_solve_per_section`) 
                    VALUES ('$templateName', $numSections, '$totalRowsPerSectionJSON', '$rowsToSolvePerSectionJSON')";
    $result = $conn->query($insertQuery);

    if ($result) {
        echo "Template saved successfully!";
    } else {
        echo "Error saving template: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
