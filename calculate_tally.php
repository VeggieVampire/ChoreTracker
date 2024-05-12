<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load chore values from the chores.values file into $choresValues array
$choresValues = [];
$choresFile = fopen("chores.values", "r");
if ($choresFile) {
    while (($line = fgets($choresFile)) !== false) {
        $line = trim($line);
        $parts = explode(" ", $line);
        if (count($parts) == 2) {
            $chore = $parts[0];
            $value = floatval($parts[1]);
            $choresValues[$chore] = $value;
        }
    }
    fclose($choresFile);
} else {
    echo "Failed to open chores.values file.";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submittedName = $_POST["name"];
    $submittedChore = $_POST["chore"];
    $customChore = $_POST["custom_chore"];

    $totalTally = 0;

    // Load tally information from tally.log
    $tallyLog = [];
    if (file_exists("tally.log")) {
        // Read existing tally data
        $tallyData = file("tally.log", FILE_IGNORE_NEW_LINES);
        foreach ($tallyData as $line) {
            $parts = explode(":", $line);
            $name = $parts[0];
            $tally = intval($parts[1]);
            $tallyLog[$name] = $tally;
        }
    }

    // Check if the name already exists in tally.log
    if (array_key_exists($submittedName, $tallyLog)) {
        // Add the existing tally for the submitted name
        $totalTally += $tallyLog[$submittedName];
    }

    // Default tally value if no chore is found
    $defaultTallyValue = 1;

    // Check if the chore selected is "other" and custom chore is provided
    if ($submittedChore == "other" && !empty($customChore)) {
        // Custom chore logic
        $customTally = 0;
        // Iterate through each chore and its value in $choresValues
        foreach ($choresValues as $chore => $value) {
            // Check if the chore exists as a keyword in the custom chore
            if (strpos($customChore, $chore) !== false) {
                // Add its corresponding value to the custom tally
                $customTally += $value;
            }
        }
        // Set the total tally to the custom tally
        $totalTally = $customTally;
    } else {
        // Iterate through each chore and its value in $choresValues
        foreach ($choresValues as $chore => $value) {
            // Check if the chore exists as a keyword in the submitted chore
            if (strpos($submittedChore, $chore) !== false) {
                // Add its corresponding value to the total tally
                $totalTally += $value;
            }
        }
    }

    // If no chore is found, default to 1 tally
    if ($totalTally == 0) {
        $totalTally = $defaultTallyValue;
    }

    // Update tally for the submitted name
    $tallyLog[$submittedName] = isset($tallyLog[$submittedName]) ? $tallyLog[$submittedName] + $totalTally : $totalTally;

    // Save the updated tally information back to tally.log
    file_put_contents("tally.log", "");
    foreach ($tallyLog as $name => $tally) {
        file_put_contents("tally.log", "$name:$tally\n", FILE_APPEND);
    }

    // Log the chore and timestamp to chores_log.txt
    if ($submittedChore === "other" && isset($_POST["custom_chore"])) {
        $custom_chore = $_POST["custom_chore"];
        $logChores = "" . date("Y-m-d H:i:s") . " - $submittedName $custom_chore. Added Tally: $totalTally";
    } else {
        $logChores = "" . date("Y-m-d H:i:s") . " - $submittedName did the dishes.Added Tally: 1";
    }
    // Load existing log data
    $existingLog = file_get_contents("chores_log.txt");
    // Prepend new entry to existing data
    $newLogEntry = $logChores . PHP_EOL . $existingLog;
    // Write updated log data back to the file
    file_put_contents("chores_log.txt", $newLogEntry);

   // echo "Total Tally for $submittedName: $totalTally";

// Redirect back to index.php after submitting the form
header("Location: index.php");
exit;

}

?>
