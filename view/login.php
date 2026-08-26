<?php
session_start();
if (isset($_SESSION['id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ./admin_dashboard.php");
    } else if ($_SESSION['role'] == 'student') {
        header("Location: ./student_dashboard.php");
    } else if ($_SESSION['role'] == 'teacher') {
        header("Location: ./teacher_dashboard.php");
    } else if ($_SESSION['role'] == 'supervisor') {
        header("Location: ./supervisor_dashboard.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
<h1>Token Management system </h1>

<?php if (isset($_SESSION['error_message'])): ?>
    <script>
        alert("<?php echo $_SESSION['error_message']; ?>");
    </script>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>
<h2>Login to your account</h2>
<form action="../controller/login-handler.php" method="post">
    ID: <input type="text" id="id" name="id"><br>
    <br>
    Password: <input type="password" id="password" name="password"><br>
    <br>
    <input type="submit" value="Login">
    <br><br>
    <p class="register-text">Don't have an account?
        <a href="create-account.php">Create an account</a>
    </p>
</form>


</body>
</html>