<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<h1>Account created successfully</h1>";
    header('Location: ../dashboard/admin_dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create account</title>
    <script src="../account_creation/validation_helper.js"></script>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h1>Create your account</h1>
<?php
if (isset($_SESSION["error_creation"])) {
    echo "<p class='error'>" . $_SESSION["error_creation"] . "</p>";
    unset($_SESSION["error_creation"]);
}
?>
<form onsubmit="return validateDets()" action="../controller/account-creation-handler.php" method="POST">
    <table>
        <tr>
            <td><label for="name">Name</label></td>
            <td>: <input type="text" name="name" id="name" placeholder="Abraham Lincoln"></td>
            <td>
                <p id="err_name" class="error"></p>
            </td>
        </tr>
        <tr>
            <td><label for="id">ID</label></td>
            <td>: <input type="text" name="id" id="id" placeholder="24-57775-2"></td>
            <td>
                <p id="err_id" class="error"><?php
                    if (isset($_SESSION["error_id"])) {
                        echo $_SESSION["error_id"];
                        unset($_SESSION["error_id"]);
                    } ?></p>
            </td>
        </tr>
        <tr>
            <td><label for="pass">Password</label></td>
            <td>: <input type="password" name="pass" id="pass" placeholder="123"></td>
            <td>
                <p id="err_pass" class="error"><?php
                    if (isset($_SESSION["error_name"])) {
                        echo $_SESSION["error_name"];
                        unset($_SESSION["error_name"]);
                    } ?></p>
            </td>
        </tr>
        <tr>
            <td><label for="cpass">Confirm password</label></td>
            <td>: <input type="password" name="cpass" id="cpass" placeholder="123"></td>
            <td>
                <p id="err_cpass" class="error"></p>
            </td>
        </tr>
    </table>
    <input type="submit" value="Create account">
</form>
</body>
</html>