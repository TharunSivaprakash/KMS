<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Timetable</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <?php
    include 'header.php';
    include 'config.php';

    // Fetch Exams from DB
    $result = $conn->query("SELECT * FROM exams ORDER BY date ASC");
    ?>

    <h2>📅 Exam Timetable</h2>
    <table>
        <tr>
            <th>Exam Name</th>
            <th>Date</th>
            <th>Time</th>
            <th>Venue</th>
            <th>Download Timetable</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo date("d M Y", strtotime($row['date'])); ?></td>
            <td><?php echo htmlspecialchars($row['time']); ?></td>
            <td><?php echo htmlspecialchars($row['venue']); ?></td>
            <td>
                <?php
                $timetable_dir = "uploads/timetables/";
                $filename = strtolower(str_replace(" ", "_", $row['title'])) . "_timetable.pdf";
                $timetable_path = $timetable_dir . $filename;
                
                if (empty($row['timetable_path'])) {
                    $updateQuery = "UPDATE exams SET timetable_path='$timetable_path' WHERE id=" . $row['id'];
                    $conn->query($updateQuery);
                }
                
                if (file_exists($timetable_path)): ?>
                    <a href="<?php echo $timetable_path; ?>" download>📥 Download</a>
                <?php else: ?>
                    <span style="color: red;">❌ Not Available</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
