<?php
header('Content-Type: application/json');

// Check if a file was uploaded
if (!isset($_FILES['book-file']) {
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

// File upload path
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$fileName = basename($_FILES['book-file']['name']);
$uploadFilePath = $uploadDir . $fileName;

// Move the uploaded file to the uploads directory
if (move_uploaded_file($_FILES['book-file']['tmp_name'], $uploadFilePath)) {
    // Extract text from the PDF (requires a PDF parser library like `smalot/pdfparser`)
    require 'vendor/autoload.php'; // Include Composer autoloader
    $parser = new \Smalot\PdfParser\Parser();
    $pdf = $parser->parseFile($uploadFilePath);
    $text = $pdf->getText();

    // Save the extracted text to a file
    $textFilePath = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME) . '.txt';
    file_put_contents($textFilePath, $text);

    echo json_encode(['success' => true, 'textFilePath' => $textFilePath]);
} else {
    echo json_encode(['error' => 'File upload failed']);
}
?>