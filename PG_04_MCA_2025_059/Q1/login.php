<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);

    if ($name != "") {
        $_SESSION["name"] = $name;
        header("Location: welcome.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Q1 - Login</title>
</head>
<body>

<h2>Login</h2>

<form method="POST">
    <label>Enter your name:</label>
    <input type="text" name="name" required>

    <button type="submit">Login</button>
</form>

</body>
</html>