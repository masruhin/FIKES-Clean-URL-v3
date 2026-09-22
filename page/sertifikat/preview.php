<?php
require_once __DIR__ . '/../../admin/config/database.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    http_response_code(400);
    exit('ID sertifikat tidak valid.');
}

$stmt = $pdo->prepare('SELECT file_sertifikat FROM sertifikat_akreditasi WHERE id_sertifikat = ? AND status_aktif = 1 LIMIT 1');
$stmt->execute([$id]);
$file = $stmt->fetchColumn();

if (!$file) {
    http_response_code(404);
    exit('File sertifikat tidak ditemukan.');
}

$file = basename((string)$file);
$path = __DIR__ . '/../../admin/uploads/upload-sertifikat/' . $file;

if (!is_file($path) || !is_readable($path)) {
    http_response_code(404);
    exit('File sertifikat tidak tersedia.');
}

$mimeMap = [
    'pdf'  => 'application/pdf',
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'webp' => 'image/webp',
];

$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
$mime = $mimeMap[$ext] ?? '';

// Browser hanya dapat menampilkan format tertentu secara native.
// Untuk format Office, jangan memaksa Content-Disposition attachment;
// browser akan menentukan perilaku berdasarkan dukungannya.
if ($mime === '') {
    $mime = 'application/octet-stream';
}

while (ob_get_level() > 0) {
    ob_end_clean();
}

header('Content-Type: ' . $mime);
header('Content-Length: ' . (string)filesize($path));
header('Content-Disposition: inline; filename="' . addcslashes($file, "\\\"") . '"');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: public, max-age=3600');

readfile($path);
exit;
