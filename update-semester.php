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
    $new_sem = intval($_POST['sem']);

    $sql = "UPDATE students SET sem = ? WHERE enrollment_no = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $error = true;
        $message = "Database Error: " . $conn->error;
    } else {
        $stmt->bind_param("ii", $new_sem, $enrollment_no);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $success = true;
                $message = "Semester updated successfully!";
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
    <title>Update Semester</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>📚 Update Student Semester</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST" id="updateSemesterForm">
            <div class="form-group">
                <label for="enrollment_no">Enrollment No:</label>
                <input type="number" id="enrollment_no" name="enrollment_no" value="<?php echo htmlspecialchars($_POST['enrollment_no'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="sem">New Semester:</label>
                <input type="number" id="sem" name="sem" min="1" value="<?php echo htmlspecialchars($_POST['sem'] ?? ''); ?>" required>
            </div>

            <button type="submit">Update Semester</button>
        </form>

        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>

    <script>
        document.getElementById('updateSemesterForm').addEventListener('submit', function(e) {
            const sem = parseInt(document.getElementById('sem').value);

            if (sem <= 0) {
                e.preventDefault();
                alert('Semester must be greater than 0');
                return;
            }
        });
    </script>
</body>
</html>
