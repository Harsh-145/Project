<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Please login first.'
    ]);
    exit();
}

include '../includes/config.php';

$students = [];

$sql = "SELECT enrollment_no, full_name, gender, dob, phone, email, room_no, sem FROM students ORDER BY enrollment_no";
$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching students.'
    ]);
    $conn->close();
    exit();
}

while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

$conn->close();

echo json_encode([
    'success' => true,
    'students' => $students
]);
?>
