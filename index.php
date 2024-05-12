<!DOCTYPE html>
<html>
<head>
    <title>Chores Form</title>
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
<?php
// Load tally data from the log file
$logData = file("tally.log", FILE_IGNORE_NEW_LINES);
$tallyData = [];
foreach ($logData as $line) {
    $parts = explode(":", $line);
    $name = $parts[0];
    $tally = isset($parts[1]) ? floatval($parts[1]) : 0; // Cast to float instead of intval
    $tallyData[$name] = $tally;
}
?>
        <?php foreach ($tallyData as $name => $tally): ?>
            <li><?php echo $name; ?>'s - Tally: <?php echo $tally; ?>
                <?php if ($tally >= 20): ?>
                    <button onclick="payout('<?php echo $name; ?>')">Payout</button>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Chores Form</h2>
    <form method="post" action="calculate_tally.php">
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
</div>

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
<?php include 'graph.php';?>
<h2>Chores Log:</h2>
<pre>
<?php echo file_get_contents("chores_log.txt"); ?>
</pre>

</body>
</html>
