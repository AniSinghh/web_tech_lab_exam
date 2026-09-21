<?php
session_start();
$valid_username = "student";
$valid_password = "1234";
$student_name = "Aniket Singh";
$attendance = 82;
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION["student_name"] = $student_name;
        $_SESSION["attendance"] = $attendance;

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Attendance Portal</title>
</head>
<body>
<h2>Student Attendance Portal</h2>
<?php if ($error !== ""): ?>
    <p><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>
<form method="POST">
    <label>Username:</label>
    <input type="text" name="username" required>
    <br><br>
    <label>Password:</label>
    <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>

</form>
</body>
</html>