<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

include '../includes/config.php';

$success = false;
$error = false;
$message = '';
$student_data = null;

// Fetch student data if enrollment number provided
if (isset($_POST['action']) && $_POST['action'] === 'fetch') {
    $enrollment_no = intval($_POST['enrollment_no']);
    
    $sql = "SELECT * FROM students WHERE enrollment_no = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $enrollment_no);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $student_data = $result->fetch_assoc();
        } else {
            $error = true;
            $message = "Student not found with that enrollment number.";
        }
        $stmt->close();
    }
}

// Update student data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $enrollment_no = intval($_POST['enrollment_no']);
    $full_name = trim($_POST['full_name']);
    $gender = trim($_POST['gender']);
    $dob = trim($_POST['dob']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $room_no = intval($_POST['room_no']);
    $sem = intval($_POST['sem']);

    // Validation
    if (empty($full_name) || empty($phone) || empty($room_no) || empty($sem) || empty($gender) || empty($dob)) {
        $error = true;
        $message = "All fields are required.";
    } else {
        $sql = "UPDATE students SET full_name = ?, gender = ?, dob = ?, phone = ?, email = ?, room_no = ?, sem = ? WHERE enrollment_no = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            $error = true;
            $message = "Database Error: " . $conn->error;
        } else {
            $stmt->bind_param("ssssssii", $full_name, $gender, $dob, $phone, $email, $room_no, $sem, $enrollment_no);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $success = true;
                    $message = "Student information updated successfully!";
                    // Clear the form
                    $student_data = null;
                } else {
                    $error = true;
                    $message = "No changes were made to the student record.";
                }
            } else {
                $error = true;
                $message = "Database Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>✏️ Update Student Information</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (!$student_data): ?>
            <!-- Search form -->
            <form method="POST">
                <input type="hidden" name="action" value="fetch">
                <div class="form-group">
                    <label for="enrollment_no">Enter Enrollment Number:</label>
                    <input type="number" id="enrollment_no" name="enrollment_no" required placeholder="e.g., 101">
                </div>
                <button type="submit">Search Student</button>
            </form>
        <?php else: ?>
            <!-- Update form -->
            <form method="POST" id="updateStudentForm">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="enrollment_no" value="<?php echo htmlspecialchars($student_data['enrollment_no']); ?>">

                <div class="form-group">
                    <label>Enrollment Number:</label>
                    <input type="text" value="<?php echo htmlspecialchars($student_data['enrollment_no']); ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($student_data['full_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male" <?php echo $student_data['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo $student_data['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo $student_data['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($student_data['dob']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($student_data['phone']); ?>" required placeholder="10 digit phone number">
                </div>

                <div class="form-group">
                    <label for="email">Email (Optional):</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student_data['email']); ?>" placeholder="student@example.com">
                </div>

                <div class="form-group">
                    <label for="room_no">Room Number:</label>
                    <input type="number" id="room_no" name="room_no" value="<?php echo htmlspecialchars($student_data['room_no']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="sem">Semester:</label>
                    <input type="number" id="sem" name="sem" min="1" value="<?php echo htmlspecialchars($student_data['sem']); ?>" required>
                </div>

                <button type="submit">Update Student</button>
                <button type="button" class="btn-secondary" onclick="location.href='update-student.php'">Search Another</button>
            </form>
        <?php endif; ?>

        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>

    <script src="../assets/js/validation.js"></script>
</body>
</html>
