<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['role'] != 'student') {
    header('Location: ./' . $_SESSION['role'] . '_dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Student's Dashboard</title>
</head>
<body>
<h1>Hello, <?php echo $_SESSION['name'] ?></h1>

<?php if (isset($_SESSION['status_message'])): ?>
    <p><?php echo htmlspecialchars($_SESSION['status_message']); ?></p>
    <?php unset($_SESSION['status_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <p class="error"><?php echo htmlspecialchars($_SESSION['error_message']); ?></p>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<a href="token_view.php">
    <h3>Apply for Token</h3>
</a>

<a href="queue-status.php">
    <h3>Monitor Queue</h3>
</a>

<a href="../controller/report_handler.php">
    <h3>Report Absence</h3>
</a>
<form action="logout.php" method="post">
    <input type="submit" value="Logout">
</form>
</body>
</html>