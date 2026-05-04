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
$room_no_raw = trim($_POST['room_no'] ?? '');
$sem_raw = trim($_POST['sem'] ?? '');

if ($enrollment_no <= 0) {
    header("Location: update-student.html?error=" . urlencode("Enrollment number is required."));
    exit();
}

// Check if student exists
$check_sql = "SELECT full_name, gender, dob, phone, email, room_no, sem FROM students WHERE enrollment_no = ?";
$check_stmt = $conn->prepare($check_sql);

if (!$check_stmt) {
    header("Location: update-student.html?error=" . urlencode("Database error."));
    exit();
}

$check_stmt->bind_param("i", $enrollment_no);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if (!$check_result || $check_result->num_rows === 0) {
    $check_stmt->close();
    header("Location: update-student.html?error=" . urlencode("Student not found."));
    exit();
}

$current = $check_result->fetch_assoc();
$check_stmt->close();

$full_name = $full_name !== '' ? $full_name : $current['full_name'];
$gender = $gender !== '' ? $gender : $current['gender'];
$dob = $dob !== '' ? $dob : $current['dob'];
$phone = $phone !== '' ? $phone : $current['phone'];
$email = $email !== '' ? $email : $current['email'];

if ($room_no_raw === '') {
    $room_no = intval($current['room_no']);
} else {
    $room_no = intval($room_no_raw);
    if ($room_no <= 0) {
        header("Location: update-student.html?error=" . urlencode("Room number must be greater than 0."));
        exit();
    }
}

if ($sem_raw === '') {
    $sem = intval($current['sem']);
} else {
    $sem = intval($sem_raw);
    if ($sem <= 0) {
        header("Location: update-student.html?error=" . urlencode("Semester must be greater than 0."));
        exit();
    }
}

$sql = "UPDATE students SET full_name = ?, gender = ?, dob = ?, phone = ?, email = ?, room_no = ?, sem = ? WHERE enrollment_no = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    header("Location: update-student.html?error=" . urlencode("Database error."));
    exit();
}

$stmt->bind_param("sssssiii", $full_name, $gender, $dob, $phone, $email, $room_no, $sem, $enrollment_no);

if ($stmt->execute()) {
    header("Location: update-student.html?success=1&message=" . urlencode("Student record updated."));
} else {
    header("Location: update-student.html?error=" . urlencode("Database error."));
}

$stmt->close();
$conn->close();
exit();
?>
