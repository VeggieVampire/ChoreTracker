<?php
// Define the correct password
$correctPassword = "your_password_here";

// Initialize the error message variable
$errorMessage = "";

// Initialize the success message variable
$successMessage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the password is correct
    if (isset($_POST["password"]) && $_POST["password"] === $correctPassword) {
        // Get the submitted data
        $choresData = isset($_POST["chores"]) ? $_POST["chores"] : '';
        $tallyData = isset($_POST["tally"]) ? $_POST["tally"] : '';
        $logData = isset($_POST["log"]) ? $_POST["log"] : '';

        // Write the new data to the files
        file_put_contents("chores.values", $choresData);
        file_put_contents("tally.log", $tallyData);
        file_put_contents("chores_log.txt", $logData);

        // Set the success message
        $successMessage = "Files were successfully edited!";
    } else {
        // Incorrect password
        $errorMessage = "Incorrect password!";
    }
}

// Default to displaying the contents of chores.values
$defaultChoresData = file_get_contents("chores.values");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Data</h1>
        <?php if (!empty($successMessage)): ?>
            <p class="success-message"><?php echo $successMessage; ?></p>
        <?php endif; ?>
        <?php if (!empty($errorMessage)): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="chores">Edit Chores:</label>
            <textarea id="chores" name="chores" rows="10" cols="50"><?php echo htmlspecialchars($defaultChoresData); ?></textarea>

            <label for="tally">Edit Tally Log:</label>
            <textarea id="tally" name="tally" rows="10" cols="50"><?php echo htmlspecialchars(file_get_contents("tally.log")); ?></textarea>

            <label for="log">Edit Chores Log:</label>
            <textarea id="log" name="log" rows="10" cols="50"><?php echo htmlspecialchars(file_get_contents("chores_log.txt")); ?></textarea>

            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
