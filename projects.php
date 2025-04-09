<?php
session_start();

include 'config.php';

// ✅ Fetch GitHub Repositories
$username = "TharunSivaprakash";
$api_url = "https://api.github.com/users/$username/repos";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'StudyBuddy'); // Required by GitHub API
$response = curl_exec($ch);

if ($response === false) {
    $github_repos = ["error" => "Error fetching data from GitHub: " . curl_error($ch)];
} else {
    $github_repos = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $github_repos = ["error" => "Error parsing JSON response: " . json_last_error_msg()];
    }
}

curl_close($ch);

// ✅ Fetch Projects from Database
$result = $conn->query("SELECT * FROM projects ORDER BY id DESC");

// ✅ Handle Project Submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_project'])) {
    $project_name = $conn->real_escape_string($_POST['project_name']);
    $conn->query("INSERT INTO projects (project_name) VALUES ('$project_name')");
    header("Location: projects.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - StudyBuddy</title>
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
            font-size: 1.1em;
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

<!-- ✅ Projects Section -->
<div class="container">
    <h2>Add a Project</h2>
    <form method="POST">
        <input type="text" name="project_name" placeholder="Enter project details" required>
        <button type="submit" name="add_project">Add Project</button>
    </form>

    <h2>🚀 Ongoing Projects</h2>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li><?php echo htmlspecialchars($row['project_name']); ?></li>
        <?php endwhile; ?>
    </ul>
</div>

<!-- ✅ GitHub Repositories Section -->
<div class="container">
    <h2>📂 GitHub Repositories</h2>
    <ul>
        <?php
        if (isset($github_repos['error'])) {
            echo "<li>Error: " . $github_repos['error'] . "</li>";
        } else {
            foreach ($github_repos as $repo) {
                echo "<li><a href='" . htmlspecialchars($repo['html_url']) . "' target='_blank'>" . htmlspecialchars($repo['name']) . "</a></li>";
            }
        }
        ?>
    </ul>
</div>

</body>
</html>
