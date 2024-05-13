<?php
// Load tally data from the log file
$tallyLogData = file("tally.log", FILE_IGNORE_NEW_LINES);
$tallyData = [];

// Extract tally data from each log entry
foreach ($tallyLogData as $line) {
    $parts = explode(":", $line);
    $name = trim($parts[0]);
    $tally = isset($parts[1]) ? floatval($parts[1]) : 0; // Cast to float instead of intval
    $tallyData[$name] = $tally;
}

// Load chore log data from the log file
$choreLogData = file("chores_log.txt", FILE_IGNORE_NEW_LINES);
$allNames = [];
$unrecordedNames = [];

// Extract all names from chore log entries
foreach ($choreLogData as $line) {
    preg_match("/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} - ([^\s]+)/", $line, $matches);
    if (isset($matches[1])) {
        $name = trim($matches[1]);
        $allNames[$name] = true; // Use associative array to ensure uniqueness
    }
}

// Find unrecorded names from tally data
foreach ($tallyData as $name => $tally) {
    if (!isset($allNames[$name])) {
        $unrecordedNames[$name] = $tally;
    }
}

// Display names and tally
foreach ($allNames as $name => $_) {
    $totalTally = isset($tallyData[$name]) ? $tallyData[$name] : 0;
    echo "<li>$name's - Tally: $totalTally</li>";
}

// Display unrecorded names at the end
foreach ($unrecordedNames as $name => $tally) {
    echo "<li>$name's - Tally: $tally</li>";
}
?>
