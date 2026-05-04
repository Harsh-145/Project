<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html?error=" . urlencode("Please login first."));
    exit();
}

include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: delete-student.html");
    exit();
}

$enrollment_no = intval($_POST['enrollment_no'] ?? 0);

if ($enrollment_no <= 0) {
    header("Location: delete-student.html?error=" . urlencode("Enrollment number is required."));
    exit();
}

$sql = "DELETE FROM students WHERE enrollment_no = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    header("Location: delete-student.html?error=" . urlencode("Database error."));
    exit();
}

$stmt->bind_param("i", $enrollment_no);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        header("Location: delete-student.html?success=1&message=" . urlencode("Student deleted successfully."));
    } else {
        header("Location: delete-student.html?error=" . urlencode("Student not found."));
    }
} else {
    header("Location: delete-student.html?error=" . urlencode("Database error."));
}

$stmt->close();
$conn->close();
exit();
?>
