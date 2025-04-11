<?php
include 'header.php';
include 'db_connect.php';

$material_id = $_GET['material_id'];
$result = $conn->query("SELECT * FROM quizzes WHERE material_id = $material_id");

if (!$result || $result->num_rows == 0) {
    die("No quiz available for this material.");
}
?>

<div class="container">
    <h2>📝 Take the Quiz</h2>
    <form action="evaluate_quiz.php" method="POST">
        <input type="hidden" name="material_id" value="<?php echo $material_id; ?>">
        <?php while ($row = $result->fetch_assoc()): ?>
            <p><b><?php echo $row['question']; ?></b></p>
            <input type="radio" name="answer[<?php echo $row['id']; ?>]" value="1"> <?php echo $row['option1']; ?><br>
            <input type="radio" name="answer[<?php echo $row['id']; ?>]" value="2"> <?php echo $row['option2']; ?><br>
            <input type="radio" name="answer[<?php echo $row['id']; ?>]" value="3"> <?php echo $row['option3']; ?><br>
            <input type="radio" name="answer[<?php echo $row['id']; ?>]" value="4"> <?php echo $row['option4']; ?><br>
        <?php endwhile; ?>
        <button type="submit">Submit Quiz</button>
    </form>
</div>
