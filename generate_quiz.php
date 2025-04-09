<?php
include 'config.php';

function generateQuestions($material_id, $book_name) {
    global $conn;

    // Fetch study material text
    $stmt = $conn->prepare("SELECT content FROM study_materials WHERE id = ?");
    $stmt->bind_param("i", $material_id);
    $stmt->execute();
    $stmt->bind_result($content);
    $stmt->fetch();
    $stmt->close();

    if (!$content) {
        return "No study material found!";
    }

    // Google Gemini API Key
    $apiKey = "AIzaSyCXC5hEto7oQqRMGDU2qJWKibBHUXwzsVY";

    // Gemini API Request Format
    $data = [
        "contents" => [
            ["parts" => [["text" => "Generate 3 multiple-choice questions from this text:\n" . substr($content, 0, 1500)]]]
        ]
    ];

    // Call Gemini API
    $ch = curl_init("https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateText?key=$apiKey");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return "cURL Error: " . curl_error($ch);
    }
    curl_close($ch);

    // Decode API response
    $responseData = json_decode($response, true);

    // Debugging - Print API response
    echo "<pre>";
    print_r($responseData);
    echo "</pre>";
    exit();  // Stop execution here for debugging

    if (!isset($responseData['candidates'][0]['content'])) {
        return "Error: No questions generated.";
    }

    // Extract AI-generated text
    $questionsText = $responseData['candidates'][0]['content'];

    // Regex to extract questions and options
    preg_match_all('/(.*?)\?\s*A\)(.*?)\s*B\)(.*?)\s*C\)(.*?)\s*D\)(.*?)\s*Answer:\s*([A-D])/', $questionsText, $matches, PREG_SET_ORDER);

    if (empty($matches)) {
        return "Error: No questions extracted.";
    }

    // Insert into database
    foreach ($matches as $match) {
        $stmt = $conn->prepare("INSERT INTO quiz_questions (book_name, topic, question, option_a, option_b, option_c, option_d, correct_option) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $book_name, $match[1], $match[1], $match[2], $match[3], $match[4], $match[5], $match[6]);
        
        if (!$stmt->execute()) {
            return "Database Error: " . $stmt->error;
        }
    }

    return "Quiz generated successfully!";
}

// Run quiz generation when requested
if (isset($_GET['material_id']) && isset($_GET['book_name'])) {
    echo generateQuestions($_GET['material_id'], $_GET['book_name']);
}
?>
