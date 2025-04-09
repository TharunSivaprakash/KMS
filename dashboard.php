<?php
session_start();
include 'config.php';  // Database connection

// Function to execute query safely and handle errors
function fetch_count($conn, $query) {
    $result = $conn->query($query);
    if (!$result) {
        die("Database Query Failed: " . $conn->error); // Display error message
    }
    return $result->fetch_assoc()['total'] ?? 0;
}

// ✅ Fetch Study Materials Count
$materials = fetch_count($conn, "SELECT COUNT(*) AS total FROM study_materials");

// ✅ Fetch Exams Count
$upcoming_exams = fetch_count($conn, "SELECT COUNT(*) AS total FROM exams WHERE date >= CURDATE()");
$past_exams = fetch_count($conn, "SELECT COUNT(*) AS total FROM exams WHERE date < CURDATE()");

// ✅ Fetch Goals Count
$completed_goals = fetch_count($conn, "SELECT COUNT(*) AS total FROM goals WHERE is_completed = 'completed'");
$pending_goals = fetch_count($conn, "SELECT COUNT(*) AS total FROM goals WHERE is_completed = 'pending'");

// ✅ Fetch Projects Count
$ongoing_projects = fetch_count($conn, "SELECT COUNT(*) AS total FROM projects");
$pending_projects = 0; // No status differentiation, so set pending_projects to 0 or remove if unnecessary


// ✅ Ensure session user_id exists
$user_id = $_SESSION['user_id'] ?? 0; 

// ✅ Count attempted quizzes from a 'quiz_attempts' table (Assumed correct structure)
$attempted_quizzes_result = $conn->query("SELECT COUNT(*) AS total FROM quiz_attempts WHERE user_id = $user_id");
$attempted_quizzes = ($attempted_quizzes_result && $attempted_quizzes_result->num_rows > 0) ? $attempted_quizzes_result->fetch_assoc()['total'] : 0;

// ✅ Fetch best quiz score (assuming 'quiz_attempts' has a 'score' column)
$best_score_result = $conn->query("SELECT MAX(score) AS best FROM quiz_attempts WHERE user_id = $user_id");
$best_score = ($best_score_result && $best_score_result->num_rows > 0) ? $best_score_result->fetch_assoc()['best'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyBuddy Dashboard</title>
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
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 30px;
            justify-content: center;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            flex: 1 1 300px;
            max-width: 350px;
            text-align: center;
        }
        .card h2 {
            font-size: 1.4em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .card p {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 10px;
        }
        .card button {
            background: #000;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
        }
        .stats {
            font-size: 1.5em;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- ✅ Navbar -->
    <div class="navbar">
        <div class="logo"> <b>Track & Study</b> Dashboard</div>
        <div>
            <a href="dashboard.php">Dashboard</a>
            <a href="study_materials.php">Study Materials</a>
            <a href="goals.php">Goals</a>
            <a href="projects.php">Projects</a>
            <a href="exams.php">Exams</a>
            <a href="quiz.php">Quiz</a>
        </div>
    </div>

    <!-- ✅ Dashboard Stats -->
    <div class="container">
        <div class="card">
            <h2>My Dashboard</h2>
            <p>Study Progress <span class="stats"><?php echo $materials; ?></span></p>
            <button onclick="location.href='study_materials.php'">View Details</button>

            <p>Upcoming Exams <span class="stats"><?php echo $upcoming_exams; ?></span></p>
            <button onclick="location.href='exams.php'">View Exams</button>

            <p>Active Goals <span class="stats"><?php echo $pending_goals; ?></span></p>
            <button onclick="location.href='goals.php'">View Goals</button>
        </div>

        <div class="card">
            <h2>Study material</h2>
            <p>Number of books <span class="stats"><?php echo $upcoming_exams; ?></span></p>
            <button onclick="location.href='study_materials.php'">Books</button>

            <p>Shared with you <span class="stats"><?php echo $past_exams; ?></span></p>
            <button onclick="location.href='study_materials.php'">See Books</button>
        </div>
        <div class="card">
            <h2>Exams</h2>
            <p>Upcoming Exams <span class="stats"><?php echo $upcoming_exams; ?></span></p>
            <button onclick="location.href='exams.php'">View Schedule</button>

            <p>Past Exams <span class="stats"><?php echo $past_exams; ?></span></p>
            <button onclick="location.href='exams.php'">View Results</button>
        </div>

        <div class="card">
            <h2>Self assesment</h2>
            <p>Attempted Quizzes <span class="stats"><?php echo $attempted_quizzes; ?></span></p>
            <button onclick="location.href='quiz.php'">Start Quiz</button>

            <p>Best Score <span class="stats"><?php echo $best_score; ?></span></p>
            <button onclick="location.href='quiz.php'">View Scores</button>
        </div>

        <div class="card">
            <h2>Goals</h2>
            <p>Completed Goals <span class="stats"><?php echo $completed_goals; ?></span></p>
            <button onclick="location.href='goals.php'">View Progress</button>

            <p>Pending Goals <span class="stats"><?php echo $pending_goals; ?></span></p>
            <button onclick="location.href='goals.php'">Update Goals</button>
        </div>

        <div class="card">
            <h2>Projects</h2>
            <p>Ongoing Projects <span class="stats"><?php echo $ongoing_projects; ?></span></p>
            <button onclick="location.href='projects.php'">View Details</button>

            <p>Pending Projects <span class="stats"><?php echo $pending_projects; ?></span></p>
            <button onclick="location.href='projects.php'">Start Now</button>
        </div>
        <div class="card">
            <h2>Productivity Tools</h2>
            <p>start timer <span class="stats"></span></p>
            <button onclick="location.href='pomo.php'">TIME START</button>

        </div>
    </div>

</body>
</html>
