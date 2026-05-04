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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enrollment_no = intval($_POST['enrollment_no']);
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $room_no = intval($_POST['room_no']);
    $sem = intval($_POST['sem']);

    $sql = "INSERT INTO students (enrollment_no, full_name, gender, dob, phone, email, room_no, sem) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $error = "Database Error: " . $conn->error;
    } else {
        $stmt->bind_param("isssssii", $enrollment_no, $full_name, $gender, $dob, $phone, $email, $room_no, $sem);

        if ($stmt->execute()) {
            $success = true;
        } else {
            if ($conn->errno === 1062) {
                $error = "Database Error! Enrollment number already exists.";
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
    <title>Add Student</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>➕ Add New Student Record</h2>

        <?php if ($success): ?>
            <div class="alert alert-success">✓ Student Record Saved Successfully!</div>
            <div class="link-text">
                <p><a href="dashboard.php">← Return to Dashboard</a></p>
            </div>
        <?php elseif ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <form method="POST" id="addStudentForm">
                <div class="form-group">
                    <label for="enrollment_no">Enrollment No:</label>
                    <input type="number" id="enrollment_no" name="enrollment_no" value="<?php echo htmlspecialchars($_POST['enrollment_no'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($_POST['dob'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="room_no">Room No:</label>
                    <input type="number" id="room_no" name="room_no" value="<?php echo htmlspecialchars($_POST['room_no'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="sem">Semester:</label>
                    <input type="number" id="sem" name="sem" min="1" value="<?php echo htmlspecialchars($_POST['sem'] ?? ''); ?>" required>
                </div>

                <button type="submit">Save Student</button>
            </form>

            <div class="link-text">
                <p><a href="dashboard.php">← Back to Dashboard</a></p>
            </div>
        <?php else: ?>
            <form method="POST" id="addStudentForm">
                <div class="form-group">
                    <label for="enrollment_no">Enrollment No:</label>
                    <input type="number" id="enrollment_no" name="enrollment_no" required>
                </div>

                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>

                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email">
                </div>

                <div class="form-group">
                    <label for="room_no">Room No:</label>
                    <input type="number" id="room_no" name="room_no" required>
                </div>

                <div class="form-group">
                    <label for="sem">Semester:</label>
                    <input type="number" id="sem" name="sem" min="1" required>
                </div>

                <button type="submit">Save Student</button>
            </form>

            <div class="link-text">
                <p><a href="dashboard.php">← Back to Dashboard</a></p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('addStudentForm')?.addEventListener('submit', function(e) {
            const phone = document.getElementById('phone').value.trim();
            const room_no = parseInt(document.getElementById('room_no').value);
            const sem = parseInt(document.getElementById('sem').value);

            if (phone.length < 10) {
                e.preventDefault();
                alert('Phone number must be at least 10 digits');
                return;
            }

            if (room_no <= 0) {
                e.preventDefault();
                alert('Room number must be greater than 0');
                return;
            }

            if (sem <= 0) {
                e.preventDefault();
                alert('Semester must be greater than 0');
                return;
            }
        });
    </script>
</body>
</html>
