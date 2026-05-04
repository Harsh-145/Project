<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'config.php';

$success = false;
$error = false;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enrollment_no = intval($_POST['enrollment_no']);

    $sql = "DELETE FROM students WHERE enrollment_no = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $error = true;
        $message = "Database Error: " . $conn->error;
    } else {
        $stmt->bind_param("i", $enrollment_no);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $success = true;
                $message = "Student deleted successfully!";
            } else {
                $error = true;
                $message = "Student not found.";
            }
        } else {
            $error = true;
            $message = "Database Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>🗑️ Delete Student Record</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST" id="deleteStudentForm">
            <div class="form-group">
                <label for="enrollment_no">Enrollment No to Delete:</label>
                <input type="number" id="enrollment_no" name="enrollment_no" value="<?php echo htmlspecialchars($_POST['enrollment_no'] ?? ''); ?>" required>
            </div>

            <div class="button-group">
                <button type="submit" onclick="return confirm('Are you sure you want to delete this student? This action cannot be undone.');">Delete Student</button>
                <a href="dashboard.php"><button type="button" class="btn-secondary">Back to Dashboard</button></a>
            </div>
        </form>

        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>

    <script>
        document.getElementById('deleteStudentForm').addEventListener('submit', function(e) {
            const enrollmentNo = document.getElementById('enrollment_no').value.trim();

            if (enrollmentNo === '') {
                e.preventDefault();
                alert('Please enter enrollment number');
                return;
            }
        });
    </script>
</body>
</html>
