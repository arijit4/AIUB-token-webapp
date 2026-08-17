<?php
session_start();
// Logic to handle the "Generate Token" button click
if (isset($_POST['generate_token'])) {

    // Path to the JSON file where tokens are stored
    $file = 'tokens.json';

    // Read existing tokens from the file and decode JSON into a PHP array
    $tokens = json_decode(file_get_contents($file), true);

    // Use the logged-in student's username as student_id for now
    $student_id = $_SESSION['id'];

    // Check if this student already has a token
    $existing_token = null;
    foreach ($tokens as $t) {
        if ($t['student_id'] === $student_id) {
            $existing_token = $t;
            break;
        }
    }

    if ($existing_token) {
        // Student already has a token, show it
        $message = "You already have Token #" . $existing_token['token_no'];
    } else {
        // Generate a new random 4-digit token number
        $token_no = rand(1000, 9999);

        // Create the new token record
        $new_token = [
            "token_no" => $token_no,
            "student_id" => $student_id
        ];

        // Add the new token to the tokens array
        $tokens[] = $new_token;

        // Save the updated array back to the JSON file
        file_put_contents($file, json_encode($tokens, JSON_PRETTY_PRINT));

        $message = "Token #" . $token_no . " generated successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Token</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <link rel="stylesheet" href="main.css">
</head>
<body>
<h1>Apply for Registration Token</h1>

<?php if (isset($message)): ?>
    <p style="color: green; font-weight: bold;">
        <?php echo $message; ?>
    </p>
<?php endif; ?>

<form action="apply-token.php" method="POST">
    <button type="submit" name="generate_token">Generate Token</button>
</form>

<p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>