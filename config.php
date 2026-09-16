<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'hiresmart_db');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed. Make sure MySQL is running in XAMPP and that "
        . "database.sql has been imported. (" . $e->getMessage() . ")");
}

// Shared lists used by both the "post a job" form and the jobs search filters,
// kept in one place so they can never go out of sync.
define('JOB_CATEGORIES', [
    'IT / Software', 'Finance / Banking', 'Marketing', 'Sales', 'Engineering',
    'Healthcare', 'Education', 'Manufacturing', 'Customer Service', 'Other',
]);
define('JOB_TYPES', ['Full-time', 'Part-time', 'Internship', 'Contract', 'Remote']);
define('EXPERIENCE_LEVELS', ['Entry Level', 'Mid Level', 'Senior Level', 'Manager']);

// Upload rules
define('UPLOAD_DIR_PROFILE', __DIR__ . '/uploads/profile_images/');
define('UPLOAD_DIR_CV', __DIR__ . '/uploads/cvs/');
define('MAX_IMAGE_BYTES', 2 * 1024 * 1024);   // 2MB
define('MAX_CV_BYTES', 5 * 1024 * 1024);      // 5MB

function handle_upload(string $fieldName, string $destDir, array $allowedExt, int $maxBytes, string $prefix): ?string
{
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$fieldName];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('There was a problem uploading the file.');
    }
    if ($file['size'] > $maxBytes) {
        throw new RuntimeException('The file is too large.');
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        throw new RuntimeException('Invalid file type. Allowed: ' . implode(', ', $allowedExt));
    }

    // Verify the real MIME type, don't trust the extension alone.
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowedMimes = [
        'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
        'webp' => 'image/webp', 'pdf' => 'application/pdf',
    ];
    if (!isset($allowedMimes[$ext]) || $mime !== $allowedMimes[$ext]) {
        throw new RuntimeException('The file content does not match a valid ' . strtoupper($ext) . ' file.');
    }

    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $filename = $prefix . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
    $destPath = $destDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        throw new RuntimeException('Could not save the uploaded file.');
    }

    return 'uploads/' . basename($destDir) . '/' . $filename;
}