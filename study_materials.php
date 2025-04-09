<?php
include 'header.php';  // Ensure header is correctly included
include 'db_connect.php';  // Ensure database connection

// Handle File Upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $file_name = $_FILES["file"]["name"];
    $file_tmp = $_FILES["file"]["tmp_name"];
    $upload_dir = "uploads/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_path = $upload_dir . basename($file_name);

    if (move_uploaded_file($file_tmp, $file_path)) {
        $stmt = $conn->prepare("INSERT INTO study_materials (title, file_path) VALUES (?, ?)");
        $stmt->bind_param("ss", $file_name, $file_path);
        if ($stmt->execute()) {
            $message = "<p style='color:green;'>✅ File uploaded successfully!</p>";
        } else {
            $message = "<p style='color:red;'>❌ Failed to save file in database!</p>";
        }
    } else {
        $message = "<p style='color:red;'>❌ File upload failed!</p>";
    }
}

// Fetch Uploaded Files
$result = $conn->query("SELECT * FROM study_materials ORDER BY uploaded_at DESC");
$total_files = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Materials - StudyBuddy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
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
        h2, h3 {
            margin-bottom: 10px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background: #f4f4f4;
            margin: 8px 0;
            padding: 10px;
            border-radius: 5px;
            font-size: 1em;
        }
        input {
            margin: 10px 0;
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
    </style>
</head>
<body>

<!-- ✅ Navbar (from header.php) -->
<div class="container">
    <h2>📚 Study Materials</h2>
    <p><strong>Total Notes: <?php echo $total_files; ?></strong></p>

    <!-- ✅ Display Messages -->
    <?php if (isset($message)) echo $message; ?>

    <h3>Upload New Study Material</h3>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>

    <h3>Available Notes</h3>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                📄 <a href="<?php echo $row['file_path']; ?>" target="_blank"><?php echo htmlspecialchars($row['title']); ?></a> 
                <br><small>📅 Uploaded on <?php echo date("d M Y", strtotime($row['uploaded_at'])); ?></small>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

</body>
</html>
