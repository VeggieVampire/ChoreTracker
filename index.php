<?php
// Load tally data from the log file
$logData = file("tally.log", FILE_IGNORE_NEW_LINES);
$tallyData = [];
foreach ($logData as $line) {
    $parts = explode(":", $line);
    $name = $parts[0];
    $tally = isset($parts[1]) ? intval($parts[1]) : 0;
    $tallyData[$name] = $tally;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"])) {
    $submittedName = $_POST["name"];
    $chore = isset($_POST["chore"]) ? $_POST["chore"] : "dishes";

    // Increment tally for the submitted name
    if (isset($tallyData[$submittedName])) {
        $tallyData[$submittedName]++;
    } else {
        $tallyData[$submittedName] = 1;
    }

    // Log the tally to tally.log
    $logEntries = [];
    foreach ($tallyData as $name => $tally) {
        $logEntries[] = "$name:$tally";
    }
    $log = implode(PHP_EOL, $logEntries);
    file_put_contents("tally.log", $log);

    // Log the chore and timestamp to chores_log.txt
    if ($chore === "other" && isset($_POST["custom_chore"])) {
        $custom_chore = $_POST["custom_chore"];
        $logChores = "" . date("Y-m-d H:i:s") . " - $submittedName $custom_chore.";
    } else {
        $logChores = "" . date("Y-m-d H:i:s") . " - $submittedName did the dishes.";
    }
    // Load existing log data
    $existingLog = file_get_contents("chores_log.txt");
    // Prepend new entry to existing data
    $newLogEntry = $logChores . PHP_EOL . $existingLog;
    // Write updated log data back to the file
    file_put_contents("chores_log.txt", $newLogEntry);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dishwasher Tally</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
  }
  .container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }
  h1 {
    color: #333;
    text-align: center;
  }
  h2 {
    color: #555;
  }
  ul {
    list-style: none;
    padding: 0;
  }
  li {
    margin-bottom: 10px;
    padding: 10px;
    background-color: #f9f9f9;
    border-radius: 6px;
  }
  button {
    padding: 6px 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  button:hover {
    background-color: #0056b3;
  }
  form {
    margin-top: 20px;
    background-color: #f5f5f5;
    padding: 20px;
    border-radius: 6px;
  }
  label {
    display: block;
    margin-bottom: 5px;
  }
  input[type="text"], input[type="radio"] {
    margin-bottom: 10px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  input[type="radio"] {
    margin-right: 10px;
  }
  input[type="submit"] {
    padding: 8px 20px;
    background-color: #28a745;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  input[type="submit"]:hover {
    background-color: #218838;
  }
</style>
</head>
<body>
<div class="container">
  <h1>Keeping It Clean: The Chronicles of Dishwashing Heroes</h1>

  <h2>List of Heroes:</h2>
  <ul>
    <?php foreach ($tallyData as $name => $tally): ?>
      <li><?php echo $name; ?> - Tally: <?php echo $tally; ?>
        <?php if ($tally >= 20): ?>
          <button onclick="payout('<?php echo $name; ?>')">Payout</button>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>

  <form method="post">
    <label for="name">Your Name:</label>
    <input type="text" name="name" required>
    <br>
    <input type="radio" name="chore" value="dishes" id="dishes" checked onchange="toggleCustomChore()">
    <label for="dishes">Dishes</label>
    <input type="radio" name="chore" value="other" id="other" onchange="toggleCustomChore()">
    <label for="other">Other</label>
    <input type="text" name="custom_chore" id="custom_chore" placeholder="Enter custom chore" style="display: none;">
    <br>
    <input type="submit" value="Submit">
  </form>

  <script>
  function toggleCustomChore() {
      var customChoreTextbox = document.getElementById("custom_chore");
      var otherRadio = document.getElementById("other");
      if (otherRadio.checked) {
          customChoreTextbox.style.display = "inline-block";
          customChoreTextbox.focus(); // Optional: Auto-focus on the text box
      } else {
          customChoreTextbox.style.display = "none";
      }
  }
  
  function payout(name) {
      if (confirm('Are you sure you want to payout 20 from ' + name + '\'s tally?')) {
          window.location.href = 'payout.php?name=' + encodeURIComponent(name);
      }
  }
  </script>
</div>
<?php include 'graph.php';?>
<!-- Log of chores -->
<h2>Chores Log:</h2>
<pre>
<?php echo file_get_contents("chores_log.txt"); ?>
</pre>

</body>
</html>
