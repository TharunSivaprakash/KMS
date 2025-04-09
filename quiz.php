<?php
session_start();
include 'config.php';
 // ✅ Added Header File

function getQuizQuestions($topic) {
    return [
        [
            "question" => "What is the importance of $topic?",
            "option_a" => "It is widely used in technology.",
            "option_b" => "It has no real-world applications.",
            "option_c" => "It is only useful in the past.",
            "option_d" => "It is a fictional concept.",
            "correct" => "A"
        ],
        [
            "question" => "Which field does $topic belong to?",
            "option_a" => "Science & Technology",
            "option_b" => "History",
            "option_c" => "Geography",
            "option_d" => "Sports",
            "correct" => "A"
        ],
        [
            "question" => "How can $topic be applied in real life?",
            "option_a" => "Used in industries and businesses",
            "option_b" => "Has no practical use",
            "option_c" => "Only useful for entertainment",
            "option_d" => "None of the above",
            "correct" => "A"
        ]
    ];
}

// ✅ Handle Quiz Generation Request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['generate_quiz'])) {
    $_SESSION['topic'] = $_POST['topic'];
    $_SESSION['quiz_questions'] = getQuizQuestions($_POST['topic']);
    $_SESSION['quiz_started'] = false; // ✅ Ensure questions don't show immediately
    header("Location: quiz.php");
    exit();
}

// ✅ Handle Start Quiz Request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['start_quiz'])) {
    $_SESSION['quiz_started'] = true;
    header("Location: quiz.php");
    exit();
}

// ✅ Handle Quiz Submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_quiz'])) {
    $score = 0;
    $questions = $_SESSION['quiz_questions'];

    foreach ($_POST['answers'] as $index => $answer) {
        if ($answer === $questions[$index]['correct']) {
            $score++;
        }
    }

    $_SESSION['quiz_score'] = $score;
    header("Location: quiz.php?result=true");
    exit();
}

$topic = $_SESSION['topic'] ?? "";
$questions = $_SESSION['quiz_questions'] ?? [];
$quiz_started = $_SESSION['quiz_started'] ?? false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Quiz Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .quiz-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .quiz-card {
            padding: 15px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            transition: all 0.3s ease-in-out;
        }
        .quiz-card:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?> <!-- ✅ Include Navigation -->

<div class="quiz-container">
    <h2 class="text-center">Generate Quiz</h2>
    <form method="POST" class="text-center">
        <input type="text" name="topic" class="form-control mb-3" placeholder="Enter Topic" required>
        <button type="submit" name="generate_quiz" class="btn btn-primary w-100">Generate Quiz</button>
    </form>

    <?php if (!empty($questions) && !$quiz_started): ?>
        <!-- ✅ Show "Start Quiz" button instead of questions -->
        <h3 class="text-center mt-4">Quiz on: <?php echo htmlspecialchars($topic); ?></h3>
        <form method="POST" class="text-center">
            <button type="submit" name="start_quiz" class="btn btn-warning w-100">Start Quiz</button>
        </form>
    <?php endif; ?>

    <?php if ($quiz_started): ?>
        <h3 class="text-center mt-4">Quiz on: <?php echo htmlspecialchars($topic); ?></h3>
        <form method="POST">
            <?php foreach ($questions as $index => $q): ?>
                <div class="quiz-card">
                    <p><b><?php echo $q['question']; ?></b></p>
                    <input type="radio" name="answers[<?php echo $index; ?>]" value="A"> <?php echo $q['option_a']; ?><br>
                    <input type="radio" name="answers[<?php echo $index; ?>]" value="B"> <?php echo $q['option_b']; ?><br>
                    <input type="radio" name="answers[<?php echo $index; ?>]" value="C"> <?php echo $q['option_c']; ?><br>
                    <input type="radio" name="answers[<?php echo $index; ?>]" value="D"> <?php echo $q['option_d']; ?><br>
                </div>
            <?php endforeach; ?>
            <button type="submit" name="submit_quiz" class="btn btn-success w-100 mt-3">Submit Quiz</button>
        </form>
    <?php endif; ?>

    <?php if (isset($_GET['result'])): ?>
        <h3 class="text-center mt-3">Your Score: <?php echo $_SESSION['quiz_score']; ?>/3</h3>
    <?php endif; ?>
</div>

</body>
</html>
