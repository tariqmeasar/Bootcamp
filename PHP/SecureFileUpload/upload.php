<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['uploaded_file'])) {
    http_response_code(400);
    exit('Invalid request.');
}

$file = $_FILES['uploaded_file'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    exit('Upload error.');
}

$maxSize = 2 * 1024 * 1024;
if ($file['size'] > $maxSize) {
    http_response_code(413);
    exit('File size exceeds limit.');
}

$allowedExtensions = ['jpg', 'png'];
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($extension, $allowedExtensions, true)) {
    http_response_code(415);
    exit('Invalid file extension.');
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

$allowedMimeTypes = [
    'jpg' => 'image/jpeg',
    'png' => 'image/png'
];

if (!isset($allowedMimeTypes[$extension]) || $allowedMimeTypes[$extension] !== $mimeType) {
    http_response_code(415);
    exit('File content mismatch.');
}

$uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$newFileName = bin2hex(random_bytes(16)) . '.' . $extension;
$destination = $uploadDir . $newFileName;

if (move_uploaded_file($file['tmp_name'], $destination)) {
    chmod($destination, 0644);
    echo 'File uploaded successfully: ' . htmlspecialchars($newFileName, ENT_QUOTES, 'UTF-8');
} else {
    http_response_code(500);
    exit('Failed to save file.');
}
