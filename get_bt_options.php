<?php
// db_connection.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kjsit";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['co_id'])) {
    $coId = $_GET['co_id'];

    // Adjust the query based on your database structure
    $sql = "SELECT DISTINCT b.bt_id, b.bt_description FROM bts b
    INNER JOIN bt_co_mapping btco ON b.bt_id = btco.bt_id
    WHERE btco.co_id = $coId";


    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $btOptions = array();

        while ($row = $result->fetch_assoc()) {
            $btOptions[] = $row;
        }

        // Return BT options as JSON
        echo json_encode($btOptions);
    } else {
        // Return an empty array if no BT options found
        echo json_encode(array());
    }
} else {
    // Return an empty array if CO ID is not provided
    echo json_encode(array());
}

// Close the database connection
$conn->close();
?>
