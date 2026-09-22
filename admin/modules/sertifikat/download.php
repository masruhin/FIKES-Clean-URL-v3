<?php
require_once __DIR__ . '/../../config/auth.php';
wajib_login();
require_once __DIR__ . '/../../config/database.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    http_response_code(400);
    exit('ID sertifikat tidak valid.');
}

$stmt = $pdo->prepare('SELECT file_sertifikat FROM sertifikat_akreditasi WHERE id_sertifikat = ? LIMIT 1');
$stmt->execute([$id]);
$file = $stmt->fetchColumn();

if (!$file) {
    http_response_code(404);
    exit('File sertifikat tidak ditemukan.');
}

$file = basename((string)$file);
$path = __DIR__ . '/../../uploads/upload-sertifikat/' . $file;

if (!is_file($path) || !is_readable($path)) {
    http_response_code(404);
    exit('File sertifikat tidak tersedia.');
}

$mime = 'application/octet-stream';
if (function_exists('finfo_open')) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if ($finfo) {
        $detected = finfo_file($finfo, $path);
        if ($detected) {
            $mime = $detected;
        }
        finfo_close($finfo);
    }
}

while (ob_get_level() > 0) {
    ob_end_clean();
}

header('Content-Type: ' . $mime);
header('Content-Length: ' . (string)filesize($path));
header('Content-Disposition: attachment; filename="' . addcslashes($file, "\\\"") . '"');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, max-age=0, must-revalidate');

readfile($path);
exit;
