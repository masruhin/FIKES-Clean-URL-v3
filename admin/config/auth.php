<?php
session_start();
require_once __DIR__ . '/database.php';

/*
|--------------------------------------------------------------------------
| Cegah halaman admin disimpan di cache browser
|--------------------------------------------------------------------------
| Ini penting agar setelah logout, tombol Back tidak menampilkan
| halaman admin yang masih tersimpan di cache.
*/
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');

function is_login()
{
  return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function wajib_login()
{
  if (!is_login()) {
    // Pastikan user yang sudah logout selalu kembali ke halaman login.
    header('Location: /fikes/admin/login.php');
    exit;
  }
}

function e($value)
{
  return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
// session_start();
// require_once __DIR__ . '/database.php';

// function is_login()
// {
//   return isset($_SESSION['admin_id']);
// }

// function wajib_login()
// {
//   if (!is_login()) {
//     header('Location: /fikes_admin_dashboard_fixed/admin/login.php');
//     exit;
//   }
// }

// function e($value)
// {
//   return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
// }
