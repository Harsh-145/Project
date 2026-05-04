<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html?error=" . urlencode("Please login first."));
    exit();
}

include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: update-student.html");
    exit();
}

$enrollment_no = intval($_POST['enrollment_no'] ?? 0);
$full_name = trim($_POST['full_name'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$dob = trim($_POST['dob'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$room_no = intval($_POST['room_no'] ?? 0);
$sem = intval($_POST['sem'] ?? 0);

if ($enrollment_no <= 0 || $room_no <= 0 || $sem <= 0 || $full_name === '' || $gender === '' || $dob === '' || $phone === '') {
    header("Location: update-student.html?error=" . urlencode("All required fields must be filled."));
    exit();
}

$sql = "UPDATE students SET full_name = ?, gender = ?, dob = ?, phone = ?, email = ?, room_no = ?, sem = ? WHERE enrollment_no = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    header("Location: update-student.html?error=" . urlencode("Database error."));
    exit();
}

$stmt->bind_param("sssssiii", $full_name, $gender, $dob, $phone, $email, $room_no, $sem, $enrollment_no);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        header("Location: update-student.html?success=1&message=" . urlencode("Student record updated."));
    } else {
        header("Location: update-student.html?error=" . urlencode("Student not found or no changes."));
    }
} else {
    header("Location: update-student.html?error=" . urlencode("Database error."));
}

$stmt->close();
$conn->close();
exit();
?>
