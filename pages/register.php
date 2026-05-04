<?php
session_start();
include '../includes/config.php';

$success = false;
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "INSERT INTO admins (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $error = "Database Error: " . $conn->error;
    } else {
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            $success = true;
        } else {
            if ($conn->errno === 1062) {
                $error = "Error: Username or Email is already taken by another staff member.";
            } else {
                $error = "Database Error: " . $stmt->error;
            }
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
    <title>Admin Staff Registration</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>🏢 Hostel Management System</h1>
        <h2>Hostel Staff Registration</h2>

        <?php if ($success): ?>
            <div class="alert alert-success">✓ Admin Account Created Successfully!</div>
            <div class="link-text">
                <p><a href="../index.php">Click here to Login</a></p>
            </div>
        <?php elseif ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <form method="POST" id="registerForm">
                <div class="form-group">
                    <label for="username">Create Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="email">Staff Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Create Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit">Register Admin</button>
            </form>

            <div class="link-text">
                <p>Already a staff member? <a href="../index.php">Login here</a></p>
            </div>
        <?php else: ?>
            <form method="POST" id="registerForm">
                <div class="form-group">
                    <label for="username">Create Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="email">Staff Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Create Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit">Register Admin</button>
            </form>

            <div class="link-text">
                <p>Already a staff member? <a href="../index.php">Login here</a></p>
            </div>
        <?php endif; ?>
    </div>

    <script src="../assets/js/validation.js"></script>
</body>
</html>
