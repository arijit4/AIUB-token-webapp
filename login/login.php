<?php
session_start();
if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: adminHome.php");
    } else if ($_SESSION['role'] == 'student') {
        header("Location: dashboard.php");
    } else if ($_SESSION['role'] == 'teacher') {
        header("Location: teacherHome.php");
    } else if ($_SESSION['role'] == 'supervisor') {
        header("Location: supervisorHome.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
</head>
<body>
<h1>Queue Management system </h1>
<h2>Login to your account</h2>
<form action="login-handler.php" method="post">
    ID: <input type="text" id="username" name="username"><br>
    <br>
    Password: <input type="password" id="password" name="password"><br>
    <br>
    <input type="submit" value="Login">
    <br><br>
    <p class="register-text">Don't have an account?
        <a href="createAccount.php">Create an account</a>
    </p>


</form>

</form>
<?php if (isset($_SESSION['error_message'])): ?>
    <script>
        alert("<?php echo $_SESSION['error_message']; ?>");
    </script>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

</body>
</html>