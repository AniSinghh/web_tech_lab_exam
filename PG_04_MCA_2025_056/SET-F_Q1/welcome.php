<?php
session_start();

if (!isset($_SESSION["visitor_name"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <h2>
        <?php 
        echo "Welcome to Adamas University, " . htmlspecialchars($_SESSION["visitor_name"]) . "!"; 
        ?>
    </h2>
    <p><a href="logout.php">Log Out</a></p>
</body>
</html>
