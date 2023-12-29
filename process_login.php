<?php
// Connect to your MySQL database
$mysqli = new mysqli("localhost", "root", "", "kjsit", 3307);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve form data
$email = $_POST['email'];
$password = $_POST['password'];

// Verify the user's credentials
$sql = "SELECT user_id, teacher_id, password FROM users WHERE email = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id, $teacher_id, $stored_password);
    $stmt->fetch();

    // Verify the entered password (without hashing)
    if ($password === $stored_password) {
        // Successfully logged in
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['teacher_id'] = $teacher_id;
        header("Location: profile.php"); // Redirect to the profile page
        exit();
    } else {
        // Invalid password
        echo "Invalid email or password";
    }

} else {
    // User not found
    echo "User not found";
}

$stmt->close();
$mysqli->close();
?>
