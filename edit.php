<?php
// Define the correct password
$correctPassword = "your_password_here";

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

        // Redirect back to the edit page or any other page
        header("Location: edit_data.php");
        exit;
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
            margin-top: 50px;
        }
        .container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="password"],
        textarea,
        input[type="submit"] {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="submit"] {
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Data</h1>
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
            <?php if (isset($errorMessage)): ?>
                <p class="error-message"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
