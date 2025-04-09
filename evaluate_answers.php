<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
    $answers = $_POST['answer'];
    $material_id = $_POST['material_id'];
    $score = 0;
    $total = count($answers);

    foreach ($answers as $question_id => $user_answer) {
        $correct = $conn->query("SELECT correct_answer FROM quizzes WHERE id = $question_id")->fetch_assoc()['correct_answer'];
        if ($user_answer == $correct) {
            $score++;
        }
    }

    echo "<div class='container'><h2>🎯 Quiz Results</h2>";
    echo "<p>Your Score: <strong>$score / $total</strong></p>";
    echo "<a href='quiz.php'>Take Another Quiz</a></div>";
}

?>

<style>
    .container {
        max-width: 400px;
        margin: auto;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
</style>
