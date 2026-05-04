<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container dashboard-container">
        <h1>🏢 Hostel Management System</h1>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

        <div class="dashboard-nav">
            <a href="add-student.php" class="nav-link">
                <strong>➕ Add New Student</strong>
                <span>Create a new student record</span>
            </a>

            <a href="view-students.php" class="nav-link">
                <strong>📋 View All Students</strong>
                <span>See all student records</span>
            </a>

            <a href="update-semester.php" class="nav-link">
                <strong>📚 Update Semester</strong>
                <span>Modify student semester</span>
            </a>

            <a href="delete-student.php" class="nav-link">
                <strong>🗑️ Delete Student</strong>
                <span>Remove a student record</span>
            </a>
        </div>

        <form action="logout.php" method="GET" style="margin-top: 30px;">
            <button type="submit" class="btn-secondary">Logout</button>
        </form>
    </div>
</body>
</html>
