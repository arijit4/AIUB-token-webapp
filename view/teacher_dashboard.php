<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../model/Tokens.php";
    $token_model = new Tokens();
    $current_token = $_POST['current_token'];
    $s = $token_model->update_token_status($current_token, 'Completed');
    header("Location: teacher_dashboard.php");
    exit();
}
include_once "../model/rooms.php";
include_once "../model/Tokens.php";
include_once "../utils/table_generator.php";
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] != 'teacher') {
    header('Location: ./' . $_SESSION['role'] . '_dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <title>Teacher's Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
<?php
$room_model = new Rooms();
$associated_room_id = $room_model->get_room_associated_with_teacher($_SESSION['id'])['room_id'];
if (!$associated_room_id) {
    echo "<p>You are not assigned to any room yet. Please contact the admin.</p>";
} else {
    $room_details = $room_model->get_all_rooms();
    $tc = $room_model->get_number_of_tokens_in_each_room();
    $tg = new TableGenerator();
    echo $tg->generate_table(
            $tc,
            "Students being served at different rooms:",
            ['Room Name', 'Supervisor Name', 'Number of students waiting']
    );

}
?>
<br><br><br>
<div>
    <fieldset class="display-span">
        <legend>Now serving</legend>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <?php
            if ($associated_room_id) {
                $token_model = new Tokens();
                $currently_being_served = $token_model->currently_being_served($associated_room_id);
                if ($currently_being_served != null) {
                    $currently_being_served = $currently_being_served['token_id'];

                    echo "<p>Currently serving: #T-" . $currently_being_served . "</p>";
                    echo '<input name="current_token" type="hidden" value="' . $currently_being_served . '">';
                    echo '<input type = "submit" value = "Mark completed" >';
                } else {
                    echo " <p>This room is all caught up!</p> ";
                }
            }
            ?>
        </form>
    </fieldset>

    <div class="display-span">
        <?php
        if ($associated_room_id) {
            $token_list = $token_model->teacher_view_tokens($associated_room_id);
            if ($token_list) {
                echo $tg->generate_queue_table(
                        $token_list,
                        "Students waiting in your room:",
                        ['Token', 'Student Name']
                );
            }
        } else {
            echo "<p>You are not assigned to any room yet. Please contact the admin.</p>";
        }
        ?>
    </div>
</div>
</body>