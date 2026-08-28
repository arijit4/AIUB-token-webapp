<?php
session_start();
include_once "../model/tokens.php";
if (!isset($_SESSION['id'])) {
    header("Location: ./login.php");
    exit();
}
$message = $_SESSION['error_message'] ?? '';
$token_id = $_GET['token_id'] ?? ($_SESSION['token_id'] ?? null);
if(!$token_id) {
    $token_model = new Tokens();
    $waiting_token = $token_model->get_waiting_token_for_user((int)$_SESSION['id']);
    if ($waiting_token) {
        $token_id = (int)$waiting_token['token_id'];
        $_SESSION['token_id'] = $token_id;
    }
}
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

<?php if (isset($token_id)): ?>
    <p>
        Your token has been created. Please note your token number:
        <strong><?php echo "#T-" . $token_id; ?></strong>
    </p>
<?php endif; ?>



<form action="../controller/token-handler.php" method="POST">

    <input type="submit" <?php
    $token_model = new Tokens();
    $token_exists = $token_model->token_already_exists($_SESSION['id']);
    if($token_exists) echo 'style="visibility: hidden;"' ?>value="Generate Token" name="generate_token"></input>
</form>


<p><a href="student_dashboard.php">Back to Dashboard</a></p>


</body>
</html>