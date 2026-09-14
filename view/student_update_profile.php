<?php
session_start();
include_once "../model/Users.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}
if ($_SESSION['role'] != 'student') {
    header('Location: ./' . $_SESSION['role'] . '_dashboard.php');
    exit();
}

$message = $_SESSION['error_message'] ?? '';
unset($_SESSION['error_message']);

$user_model = new Users();
$user = $user_model->get_user($_SESSION['uni_id']);

?>
<html>
<head>
    <title>Update Profile</title>
    <script src="../asset/student_update_profile_validation.js"></script>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
    <h1>Update Your Profile</h1>
    <p><?php echo htmlspecialchars($message); ?></p>
    <form onsubmit="return validateProfileUpdate()" action="../controller/student_update_profile_handler.php" method="POST">
        <table class="app-table">
            <tr>
                <th colspan="2">Update your information</th>
            </tr>
            <tr>
                <td><label for="fullname">Full Name</label></td>
                <td><input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="password">Password</label></td>
                <td><input type="password" id="password" name="password" required></td>
            </tr>
            <tr>
                <td><label for="cpassword">Confirm Password</label></td>
                <td><input type="password" id="cpassword" name="cpassword" required></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="update_profile" value="Save Changes"></td>
            </tr>
        </table>
    </form>
    <form action="../view/student_dashboard.php" method="post">
        <input type="submit" value="Back to Dashboard">
    </form>
</body>
</html>
