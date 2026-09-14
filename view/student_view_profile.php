<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: .php");
    exit();
}
if ($_SESSION['role'] != 'student') {
    header('Location: ./' . $_SESSION['role'] . '_dashboard.php');
    exit();
}
?>
<html>
<head>
    <title>Student's Profile</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
    <h1> <?php echo htmlspecialchars($_SESSION['name']); ?>'s Profile</h1>
    <table class="app-table">
        <tr>
            <th colspan="2">Identity verification</th>
        </tr>
        <tr>
            <td>Student Name</td>
            <td><?php echo htmlspecialchars($_SESSION['name']); ?></td>
        </tr>
        <tr>
            <td>Student ID</td>
            <td><?php echo htmlspecialchars($_SESSION['uni_id']); ?></td>
        </tr>
    </table>
    <br>
    <form action="../view/student_dashboard.php" method="post">
        <input type="submit" value="Back to Dashboard">
    </form>
</body>
</html>


