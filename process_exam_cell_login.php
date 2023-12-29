<?php
$mysqli = new mysqli("localhost", "root", "", "kjsit",3307);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT user_id, password FROM exam_cell_users WHERE email = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id, $stored_password);
    $stmt->fetch();

    // Verify the entered password (plain text)
    if ($password === $stored_password) {
        session_start();
        $_SESSION['user_id'] = $user_id;
        header("Location: examcell_dashboard.php");
        exit();
    } else {
        echo "Invalid email or password";
    }
} else {
    echo "User not found";
}

$stmt->close();
$mysqli->close();
?>
