<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ./login.php");
    exit();
}
$message = $_SESSION['error_message'] ?? '';
$token_id = $_GET['token_id'] ?? ($_SESSION['token_id'] ?? null);
unset($_SESSION['error_message']);
?>
<html lang="en">
<head>
    <title>Token View</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h1>Token View</h1>
<h1>Apply for Registration Token</h1>

<?php if ($message): ?>
    <p class="error">
        <?php echo $message; ?>
    </p>
<?php endif; ?>

<?php if ($token_id): ?>
    <p>
        Your token has been created. Please note your token number:
        <strong><?php echo "#T-" . $token_id; ?></strong>
    </p>
<?php endif; ?>

<form action="../controller/token-handler.php" method="POST">
    <button type="submit" name="generate_token">Generate Token</button>
</form>

<p><a href="student_dashboard.php">Back to Dashboard</a></p>


</body>
</html>