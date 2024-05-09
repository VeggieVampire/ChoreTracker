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

// Get unique names
$uniqueNames = array_keys($tallyData);

// Initialize labels and data arrays
$labels = $uniqueNames;
$data = array_fill(0, count($uniqueNames), 0);

// Update data array with tallies
foreach ($uniqueNames as $index => $name) {
    if (isset($tallyData[$name])) {
        $data[$index] = $tallyData[$name];
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
    <canvas id="myChart" width="400" height="200"></canvas>

    <script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Tally',
                data: <?php echo json_encode($data); ?>,
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
    </script>
</body>
</html>
