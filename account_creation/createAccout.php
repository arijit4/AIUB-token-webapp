<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create account</title>
    <script src="validation_helper.js"></script>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h1>Create your account</h1>
<form onsubmit="return validateDets()" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <table>
        <tr>
            <td>Name</td>
            <td>: <input type="text" name="name" id="name"></td>
            <td>
                <p id="err_name" class="error"></p>
            </td>
        </tr>
        <tr>
            <td>AIUB ID</td>
            <td>: <input type="text" name="id" id="id"></td>
            <td>
                <p id="err_id" class="error"></p>
            </td>
        </tr>
        <tr>
            <td>Password</td>
            <td>: <input type="password" name="pass" id="pass"></td>
            <td>
                <p id="err_pass" class="error"></p>
            </td>
        </tr>
        <tr>
            <td>Confirm Password</td>
            <td>: <input type="password" name="cpass" id="cpass"></td>
            <td>
                <p id="err_cpass" class="error"></p>
            </td>
        </tr>
    </table>
    <input type="submit" value="Create account">
</form>
</body>
</html>