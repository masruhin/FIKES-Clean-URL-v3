<?php
require_once __DIR__ . '/config/auth.php';

/*
 * Jangan izinkan halaman login disimpan oleh browser.
 * Jika admin sudah login, langsung arahkan ke Dashboard.
 */
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');

$project_url = substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], '/admin/'));

if (is_login()) {
  header('Location: index.php');
  exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = md5($_POST['password'] ?? '');

  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
  $stmt->execute([$username]);
  $user = $stmt->fetch();

  if ($user && hash_equals($user['password'], $password)) {
    /* Buat ID session baru setelah login berhasil. */
    session_regenerate_id(true);

    $_SESSION['admin_id'] = $user['id'];
    $_SESSION['admin_nama'] = $user['nama'];
    $_SESSION['admin_role'] = $user['role'];

    session_write_close();

    header('Location: index.php');
    exit;
  }
  $error = 'Username atau password salah.';
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <title>Login Admin FIKES</title>
  <link rel="stylesheet" href="<?= e($project_url) ?>/admin/assets/css/admin.css">

  <script>
    /*
     * Mengatasi Back/Forward Cache (bfcache).
     * Jika login.php ditampilkan kembali dari cache browser,
     * halaman dimuat ulang agar PHP mengecek session terbaru.
     */
    window.addEventListener('pageshow', function(event) {
      if (event.persisted) {
        window.location.reload();
      }
    });
  </script>
</head>

<body class="login-page">
  <div class="login-card">
    <div class="brand-mark">F</div>
    <h1>Admin FIKES</h1>
    <p>Fakultas Ilmu Kesehatan</p>
    <?php if ($error): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>
    <form method="post" autocomplete="off">
      <label>Username</label>
      <input type="text" name="username" placeholder="Masukkan username" autocomplete="username" required>
      <label>Password</label>
      <input type="password" name="password" placeholder="Masukkan password" autocomplete="current-password" required>
      <button type="submit" class="btn primary full">Masuk ke Dashboard</button>
    </form>
    <small>Demo: admin / admin123</small>
  </div>
</body>

</html>
