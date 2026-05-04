<?php
session_start();
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.html");
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    header("Location: login.html?error=" . urlencode("Please fill in all fields."));
    exit();
}

$sql = "SELECT username FROM admins WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    header("Location: login.html?error=" . urlencode("Database error."));
    exit();
}

$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $_SESSION['username'] = $username;
    header("Location: dashboard.html");
} else {
    header("Location: login.html?error=" . urlencode("Invalid username or password."));
}

$stmt->close();
$conn->close();
exit();
?>
