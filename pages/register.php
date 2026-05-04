<?php
session_start();
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.html");
    exit();
}

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $email === '' || $password === '') {
    header("Location: register.html?error=" . urlencode("All fields are required."));
    exit();
}

$sql = "INSERT INTO admins (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    header("Location: register.html?error=" . urlencode("Database error."));
    exit();
}

$stmt->bind_param("sss", $username, $email, $password);

if ($stmt->execute()) {
    header("Location: login.html?success=1&message=" . urlencode("Account created. Please login."));
} else {
    if ($conn->errno === 1062) {
        header("Location: register.html?error=" . urlencode("Username or email already exists."));
    } else {
        header("Location: register.html?error=" . urlencode("Database error."));
    }
}

$stmt->close();
$conn->close();
exit();
?>
