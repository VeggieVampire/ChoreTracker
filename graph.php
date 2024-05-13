<?php
// Get tally data from tally.log file
$logData = file("tally.log", FILE_IGNORE_NEW_LINES);
$tallyData = [];
foreach ($logData as $line) {
    $parts = explode(":", $line);
    $name = $parts[0];
    $tally = isset($parts[1]) ? intval($parts[1]) : 0;
    $tallyData[$name] = $tally;
}

// Get payout data from chores_log.txt file
$logData = file("chores_log.txt", FILE_IGNORE_NEW_LINES);
$payoutData = [];
foreach ($logData as $line) {
    if (strpos($line, "received a payout of") !== false) {
        $parts = explode(" ", $line);
        $name = $parts[3]; // Adjusted to get the name from the correct position
        $payout = intval($parts[count($parts) - 3]); // Extract the payout amount
        if (isset($payoutData[$name])) {
            $payoutData[$name] += $payout; // Add to existing payout if name already exists
        } else {
            $payoutData[$name] = $payout; // Otherwise, create a new entry
        }
    }
}

// Initialize combined tallies and payouts
$combinedTallies = [];
$combinedPayouts = [];

// Merge tallies and payouts
foreach ($tallyData as $name => $tally) {
    $combinedTallies[$name] = $tally;
    $combinedPayouts[$name] = isset($payoutData[$name]) ? $payoutData[$name] : 0;
}

// Get unique names
$uniqueNames = array_keys($combinedTallies);

// Initialize labels and data arrays
$labels = $uniqueNames;
$tallyData = array_fill(0, count($uniqueNames), 0);
$payoutData = array_fill(0, count($uniqueNames), 0);

// Update data arrays with combined tallies and payouts
foreach ($uniqueNames as $index => $name) {
    if (isset($combinedTallies[$name])) {
        $tallyData[$index] = $combinedTallies[$name];
    }
    if (isset($combinedPayouts[$name])) {
        $payoutData[$index] = $combinedPayouts[$name];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chores Tally Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Chores Tally Graph</h2>
    <button onclick="togglePayouts()">Toggle Payouts</button>
    <canvas id="myChart" width="400" height="200"></canvas>

    <script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart;

    function togglePayouts() {
        if (myChart.data.datasets.length === 1) {
            // Add payout dataset
            myChart.data.datasets.push({
                label: 'Payout',
                data: <?php echo json_encode($payoutData); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            });
        } else {
            // Remove payout dataset
            myChart.data.datasets.pop();
        }
        myChart.update();
    }

    document.addEventListener("DOMContentLoaded", function(event) {
        myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Tally',
                    data: <?php echo json_encode($tallyData); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
    </script>
</body>
</html>
