<?php
include 'db_connect.php'; // Ensure the correct database connection

// Handle adding a new goal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_goal'])) {
    $goal = $_POST['goal'];
    $sql = "INSERT INTO goals (goal_name, is_completed) VALUES ('$goal', 0)";
    $conn->query($sql);
}

// Handle updating goal status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_goal'])) {
    $id = $_POST['goal_id'];
    $is_completed = isset($_POST['is_completed']) ? 1 : 0;
    $conn->query("UPDATE goals SET is_completed = $is_completed WHERE id = $id");
}

// Fetch all goals
$result = $conn->query("SELECT * FROM goals");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goals - StudyBuddy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 30px;
            background-color: #000;
            color: white;
        }
        .navbar .logo {
            font-weight: bold;
            font-size: 1.2em;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-size: 1em;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            font-size: 1.5em;
            margin-bottom: 15px;
        }
        input[type="text"] {
            width: 80%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: black;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            background: #f4f4f4;
            margin: 8px 0;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        input[type="checkbox"] {
            transform: scale(1.2);
        }
    </style>
</head>
<body>

<!-- ✅ Navbar -->
<div class="navbar">
    <div class="logo"> <b>Track & Study</b></div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="study_materials.php">Study Materials</a>
        <a href="goals.php">Goals</a>
        <a href="projects.php">Projects</a>
        <a href="exams.php">Exams</a>
        <a href="quiz.php">Self assesment</a>
        
    </div>
</div>

<!-- ✅ Goals Section -->
<div class="container">
    <h2>Create a Goal</h2>
    <form method="POST">
        <input type="text" name="goal" placeholder="Enter goal" required>
        <button type="submit" name="add_goal">Add Goal</button>
    </form>

    <h2>Your Goals</h2>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <form method="POST">
                    <input type="hidden" name="goal_id" value="<?php echo $row['id']; ?>">
                    <input type="checkbox" name="is_completed" value="1" <?php echo $row['is_completed'] ? 'checked' : ''; ?> onchange="this.form.submit()">
                    <?php echo htmlspecialchars($row['goal_name']); ?>
                    <input type="hidden" name="update_goal" value="1">
                </form>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

</body>
</html>
