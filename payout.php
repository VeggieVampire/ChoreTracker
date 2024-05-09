<?php
// Check if name is provided in the URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["name"])) {
    $name = $_GET["name"];

    // Read tally data from the log file
    $logData = file("tally.log", FILE_IGNORE_NEW_LINES);
    $dishwashers = [];
    if (!empty($logData)) {
        foreach ($logData as $line) {
            $parts = explode(":", $line);
            $dishwashers[$parts[0]] = isset($parts[1]) ? intval($parts[1]) : 0;
        }
    }

    // Deduct 20 from the person's tally if their tally is 20 or more
    if (isset($dishwashers[$name]) && $dishwashers[$name] >= 20) {
        $dishwashers[$name] -= 20;

        // Update tally data in the log file
        $log = "";
        foreach ($dishwashers as $key => $value) {
            $log .= "$key:$value\n";
        }
        file_put_contents("tally.log", $log);

        // Add entry to chores_log.txt
        $logChores = "" . date("Y-m-d H:i:s") . " - $name received a payout of 20 tally points.";
        $existingLog = file_get_contents("chores_log.txt");
        $newLogEntry = $logChores . PHP_EOL . $existingLog; // Prepend new entry to existing data
        file_put_contents("chores_log.txt", $newLogEntry);
    }
}

// Redirect back to index.php
header("Location: index.php");
exit;
?>
