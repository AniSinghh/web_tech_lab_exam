<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $_SESSION["visitor_name"] = trim($_POST["visitor_name"]);

    header("Location: welcome.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Adamas University</h1>
    <h3>Please Enter Your Name</h3>
    <form method="post" action="">
        <label>Your Name:</label>
        <input type="text" name="visitor_name" required>
        <button type="submit">Enter</button>
    </form>
</body>
</html>
