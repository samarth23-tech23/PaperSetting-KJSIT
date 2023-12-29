<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mysqli = new mysqli("localhost", "root", "", "kjsit", 3307);

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Get subject_id from the form submission
    $subject_id = $mysqli->real_escape_string($_POST["subject_id"]);

    // Check if CO form is submitted
    if (isset($_POST["co_number"], $_POST["co_description"])) {
        $co_number = $mysqli->real_escape_string($_POST["co_number"]);
        $co_description = $mysqli->real_escape_string($_POST["co_description"]);

        // Insert CO level
        $co_query = "INSERT INTO cos (co_number, co_description) VALUES ('$co_number', '$co_description')";
        if ($mysqli->query($co_query)) {
            // Get the inserted CO level ID
            $co_id = $mysqli->insert_id;

            // Map the CO level to the selected subject
            $mapping_query = "INSERT INTO subject_co_mapping (subject_id, co_id) VALUES ('$subject_id', '$co_id')";
            $mysqli->query($mapping_query);
        } else {
            echo "CO Query Error: " . $mysqli->error;
        }
    }

    // Check if BT form is submitted
    if (isset($_POST["bt_level"], $_POST["bt_description"])) {
        $bt_level = $mysqli->real_escape_string($_POST["bt_level"]);
        $bt_description = $mysqli->real_escape_string($_POST["bt_description"]);

        // Insert BT level
        $bt_query = "INSERT INTO bts (bt_level, bt_description) VALUES ('$bt_level', '$bt_description')";
        if ($mysqli->query($bt_query)) {
            // Get the inserted BT level ID
            $bt_id = $mysqli->insert_id;

            // Map the BT level to the selected subject
            $mapping_query = "INSERT INTO subject_bt_mapping (subject_id, bt_id) VALUES ('$subject_id', '$bt_id')";
            $mysqli->query($mapping_query);
        } else {
            echo "BT Query Error: " . $mysqli->error;
        }
    }

    $mysqli->close();
}

header("Location: co_bt.php?subject_id=$subject_id");
exit();
?>
