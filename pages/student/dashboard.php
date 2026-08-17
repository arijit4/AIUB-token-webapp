<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['role'] !== 'student') {
    header('Location: ./' . $_SESSION['role'] . '/dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Student's Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../style.css">

</head>
<body>
<h1>Hello student, <?php echo $_SESSION['id'] ?></h1>
<a href="apply-token.php">
    <h3>Apply for Token</h3>
</a>

<a href="queue-status.php">
    <h3>Monitor Queue</h3>
</a>

<a href="report-absence.php">
    <h3>Report Absence</h3>
</a>
<form action="../../login/logout.php" method="post">
    <input type="submit" value="Logout">
</form>
</body>
</html>