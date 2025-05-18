<?php
// Connect to MySQL
$host = "localhost";
$user = "root";
$password = ""; // change this if your MySQL has password
$db = "daily_tasks";

$conn = new mysqli($host, $user, $password, $db);

// Handle DB error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert task if submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $date = $_POST['date'];

    $stmt = $conn->prepare("INSERT INTO tasks (task_title, task_description, task_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $desc, $date);
    $stmt->execute();
    $stmt->close();
}

// Fetch tasks
$result = $conn->query("SELECT * FROM tasks ORDER BY task_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Task Tracker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }
        .container {
            background: #fff;
            padding: 25px;
            max-width: 700px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        input[type=text], input[type=date], textarea {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even){background-color: #f9f9f9}
    </style>
</head>
<body>
<div class="container">
    <h2>📝 Daily Task Tracker</h2>
    <form method="post">
        <label>Task Title:</label>
        <input type="text" name="title" required>

        <label>Description:</label>
        <textarea name="description" rows="4"></textarea>

        <label>Date:</label>
        <input type="date" name="date" required>

        <input type="submit" value="Save Task">
    </form>

    <h3>📋 Your Tasks</h3>
    <table>
        <tr>
            <th>Date</th>
            <th>Title</th>
            <th>Description</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['task_date']) ?></td>
            <td><?= htmlspecialchars($row['task_title']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['task_description'])) ?></td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
