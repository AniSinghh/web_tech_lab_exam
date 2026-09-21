<?php
session_start();

if (!isset($_SESSION["name"])) {
    header("Location: q1.php");
    exit();
}

$name = $_SESSION["name"];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($name); ?></h2>

<a href="logout.php">Logout</a>

</body>
</html>