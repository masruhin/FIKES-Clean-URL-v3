<?php
$page_title = 'Akun Admin';

require_once __DIR__ . '/../../config/auth.php';
wajib_login();

$admin_id = (int) ($_SESSION['admin_id'] ?? 0);

/* =========================================================
   CSRF
   ========================================================= */
$csrf_token = $_SESSION['akun_csrf'] ?? bin2hex(random_bytes(32));
$_SESSION['akun_csrf'] = $csrf_token;

/* =========================================================
   HELPER
   ========================================================= */
function akun_post(string $key, string $default = ''): string
{
    return trim((string) ($_POST[$key] ?? $default));
}

function akun_redirect(string $status, string $message): void
{
    header('Location: index.php?' . http_build_query([
        'status' => $status,
        'message' => $message,
    ]));
    exit;
}

function akun_valid_username(string $username): bool
{
    return (bool) preg_match('/^[A-Za-z0-9._-]{3,50}$/', $username);
}

function akun_valid_role(string $role): bool
{
    return in_array($role, ['admin', 'editor'], true);
}

/* =========================================================
   PROSES TAMBAH / EDIT / HAPUS
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = (string) ($_POST['csrf_token'] ?? '');

    if (!hash_equals($csrf_token, $token)) {
        akun_redirect('error', 'Token keamanan tidak valid. Silakan muat ulang halaman.');
    }

    $action = (string) ($_POST['action'] ?? '');

    try {
        /* -------------------------------------------------
           TAMBAH AKUN
           ------------------------------------------------- */
        if ($action === 'add') {
            $nama = akun_post('nama');
            $username = akun_post('username');
            $password = (string) ($_POST['password'] ?? '');
            $password_confirmation = (string) ($_POST['password_confirmation'] ?? '');
            $role = akun_post('role', 'editor');

            if ($nama === '' || $username === '' || $password === '') {
                akun_redirect('error', 'Nama, username, dan password wajib diisi.');
            }

            if (!akun_valid_username($username)) {
                akun_redirect('error', 'Username 3–50 karakter dan hanya boleh berisi huruf, angka, titik, garis bawah, atau tanda hubung.');
            }

            if (strlen($password) < 6) {
                akun_redirect('error', 'Password minimal 6 karakter.');
            }

            if ($password !== $password_confirmation) {
                akun_redirect('error', 'Konfirmasi password tidak sama.');
            }

            if (!akun_valid_role($role)) {
                akun_redirect('error', 'Role akun tidak valid.');
            }

            $cek = $pdo->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
            $cek->execute([$username]);

            if ($cek->fetch()) {
                akun_redirect('error', 'Username tersebut sudah digunakan.');
            }

            /* Login proyek saat ini menggunakan MD5. */
            $password_hash = md5($password);

            $stmt = $pdo->prepare(
                'INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([$nama, $username, $password_hash, $role]);

            akun_redirect('success', 'Akun baru berhasil ditambahkan.');
        }

        /* -------------------------------------------------
           EDIT AKUN
           ------------------------------------------------- */
        if ($action === 'edit') {
            $id = (int) ($_POST['id'] ?? 0);
            $nama = akun_post('nama');
            $username = akun_post('username');
            $password = (string) ($_POST['password'] ?? '');
            $password_confirmation = (string) ($_POST['password_confirmation'] ?? '');
            $role = akun_post('role', 'editor');

            if ($id <= 0 || $nama === '' || $username === '') {
                akun_redirect('error', 'Data akun belum lengkap.');
            }

            if (!akun_valid_username($username)) {
                akun_redirect('error', 'Username tidak valid.');
            }

            if (!akun_valid_role($role)) {
                akun_redirect('error', 'Role akun tidak valid.');
            }

            $cek = $pdo->prepare('SELECT id FROM users WHERE username = ? AND id <> ? LIMIT 1');
            $cek->execute([$username, $id]);

            if ($cek->fetch()) {
                akun_redirect('error', 'Username tersebut sudah digunakan oleh akun lain.');
            }

            if ($password !== '') {
                if (strlen($password) < 6) {
                    akun_redirect('error', 'Password baru minimal 6 karakter.');
                }

                if ($password !== $password_confirmation) {
                    akun_redirect('error', 'Konfirmasi password baru tidak sama.');
                }

                $stmt = $pdo->prepare(
                    'UPDATE users SET nama = ?, username = ?, password = ?, role = ? WHERE id = ?'
                );
                $stmt->execute([$nama, $username, md5($password), $role, $id]);
            } else {
                $stmt = $pdo->prepare(
                    'UPDATE users SET nama = ?, username = ?, role = ? WHERE id = ?'
                );
                $stmt->execute([$nama, $username, $role, $id]);
            }

            if ($id === $admin_id) {
                $_SESSION['admin_nama'] = $nama;
                $_SESSION['admin_role'] = $role;
            }

            akun_redirect('success', 'Data akun berhasil diperbarui.');
        }

        /* -------------------------------------------------
           HAPUS AKUN
           ------------------------------------------------- */
        if ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);

            if ($id <= 0) {
                akun_redirect('error', 'ID akun tidak valid.');
            }

            if ($id === $admin_id) {
                akun_redirect('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
            }

            $cek = $pdo->prepare('SELECT id FROM users WHERE id = ? LIMIT 1');
            $cek->execute([$id]);

            if (!$cek->fetch()) {
                akun_redirect('error', 'Akun tidak ditemukan.');
            }

            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$id]);

            akun_redirect('success', 'Akun berhasil dihapus.');
        }

        akun_redirect('error', 'Aksi tidak dikenali.');
    } catch (PDOException $e) {
        akun_redirect('error', 'Proses akun gagal. Periksa kembali data yang dimasukkan.');
    }
}

/* =========================================================
   DATA AKUN
   ========================================================= */
$stmt = $pdo->query(
    'SELECT id, nama, username, role, created_at
     FROM users
     ORDER BY id DESC'
);
$akun_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$admin = null;
foreach ($akun_list as $akun) {
    if ((int) $akun['id'] === $admin_id) {
        $admin = $akun;
        break;
    }
}

if (!$admin) {
    session_unset();
    session_destroy();
    header('Location: ../../login.php');
    exit;
}

$status = (string) ($_GET['status'] ?? '');
$message = (string) ($_GET['message'] ?? '');

/* CSS khusus halaman ini dimuat oleh header.php di dalam <head>. */
$page_styles = [
    'admin/modules/akun/akun-admin.css',
];

require __DIR__ . '/../../includes/header.php';
?>

<div class="akun-page">

    <!-- =====================================================
         HEADER HALAMAN
         ===================================================== -->
    <div class="akun-page-head">
        <div>
            <span class="akun-eyebrow">PENGATURAN AKUN</span>
            <h1>Manajemen Akun</h1>
            <p>Kelola profil Anda dan akun pengguna yang memiliki akses ke Dashboard FIKES.</p>
        </div>

        <button type="button" class="akun-btn akun-btn-primary" onclick="bukaModalTambah()">
            <span>＋</span>
            Tambah Akun
        </button>
    </div>

    <!-- =====================================================
         PROFIL AKUN AKTIF
         ===================================================== -->
    <section class="akun-profile-card">
        <div class="akun-profile-cover">
            <div class="akun-cover-pattern"></div>
        </div>

        <div class="akun-profile-body">
            <div class="akun-profile-avatar">
                <?= e(strtoupper(substr($admin['nama'], 0, 1))) ?>
            </div>

            <div class="akun-profile-main">
                <div class="akun-profile-name-row">
                    <div>
                        <h2><?= e($admin['nama']) ?></h2>
                        <p>@<?= e($admin['username']) ?></p>
                    </div>

                    <span class="akun-role-badge <?= e($admin['role']) ?>">
                        <?= e(ucfirst($admin['role'])) ?>
                    </span>
                </div>

                <div class="akun-profile-info">
                    <div>
                        <span>ID Akun</span>
                        <strong>#<?= (int) $admin['id'] ?></strong>
                    </div>
                    <div>
                        <span>Status</span>
                        <strong class="akun-status-active"><i></i> Aktif</strong>
                    </div>
                    <div>
                        <span>Terdaftar</span>
                        <strong><?= e(date('d M Y', strtotime($admin['created_at']))) ?></strong>
                    </div>
                </div>
            </div>

            <div class="akun-profile-action">
                <button
                    type="button"
                    class="akun-btn akun-btn-outline"
                    onclick='bukaModalEdit(<?= json_encode($admin, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>)'
                >
                    ✎ Edit Profil
                </button>
            </div>
        </div>
    </section>

    <!-- =====================================================
         DAFTAR AKUN
         ===================================================== -->
    <section class="akun-panel">
        <div class="akun-panel-head">
            <div>
                <span class="akun-section-label">USER ACCESS</span>
                <h2>Daftar Akun</h2>
                <p>Semua akun yang dapat masuk ke Dashboard Admin FIKES.</p>
            </div>

            <div class="akun-total">
                <strong><?= count($akun_list) ?></strong>
                <span>Total Akun</span>
            </div>
        </div>

        <div class="akun-toolbar">
            <div class="akun-search">
                <span>⌕</span>
                <input
                    type="search"
                    id="akunSearch"
                    placeholder="Cari nama, username, atau role..."
                    autocomplete="off"
                >
            </div>

            <div class="akun-page-size">
                <label for="akunPageSize">Tampilkan</label>
                <select id="akunPageSize">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <div class="akun-table-wrap">
            <table class="akun-table" id="akunTable">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Terdaftar</th>
                        <th class="akun-col-action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($akun_list as $index => $akun): ?>
                        <tr
                            data-search="<?= e(strtolower($akun['nama'] . ' ' . $akun['username'] . ' ' . $akun['role'])) ?>"
                            data-id="<?= (int) $akun['id'] ?>"
                        >
                            <td class="akun-no"><?= $index + 1 ?></td>
                            <td>
                                <div class="akun-user-cell">
                                    <div class="akun-mini-avatar">
                                        <?= e(strtoupper(substr($akun['nama'], 0, 1))) ?>
                                    </div>
                                    <div>
                                        <strong><?= e($akun['nama']) ?></strong>
                                        <?php if ((int) $akun['id'] === $admin_id): ?>
                                            <span class="akun-you">Anda</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="akun-username">@<?= e($akun['username']) ?></span>
                            </td>
                            <td>
                                <span class="akun-role-badge <?= e($akun['role']) ?>">
                                    <?= e(ucfirst($akun['role'])) ?>
                                </span>
                            </td>
                            <td><?= e(date('d-m-Y', strtotime($akun['created_at']))) ?></td>
                            <td class="akun-actions">
                                <button
                                    type="button"
                                    class="akun-icon-btn edit"
                                    title="Edit akun"
                                    onclick='bukaModalEdit(<?= json_encode($akun, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>)'
                                >✎</button>

                                <?php if ((int) $akun['id'] === $admin_id): ?>
                                    <button
                                        type="button"
                                        class="akun-icon-btn delete disabled"
                                        title="Akun aktif tidak dapat dihapus"
                                        disabled
                                    >⌫</button>
                                <?php else: ?>
                                    <button
                                        type="button"
                                        class="akun-icon-btn delete"
                                        title="Hapus akun"
                                        onclick="hapusAkun(<?= (int) $akun['id'] ?>, <?= htmlspecialchars(json_encode($akun['nama']), ENT_QUOTES, 'UTF-8') ?>)"
                                    >⌫</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (!$akun_list): ?>
                <div class="akun-empty">
                    <div>◎</div>
                    <h3>Belum ada akun</h3>
                    <p>Tambahkan akun administrator atau editor untuk mulai mengelola akses.</p>
                </div>
            <?php endif; ?>

            <div class="akun-no-result" id="akunNoResult">
                <div>⌕</div>
                <strong>Data tidak ditemukan</strong>
                <span>Coba gunakan kata kunci pencarian yang lain.</span>
            </div>
        </div>

        <div class="akun-table-footer">
            <div id="akunInfo">Menampilkan 0 dari 0 data</div>
            <div class="akun-pagination" id="akunPagination"></div>
        </div>
    </section>
</div>

<!-- =========================================================
     MODAL TAMBAH / EDIT AKUN
     ========================================================= -->
<div class="akun-modal" id="akunModal" aria-hidden="true">
    <div class="akun-modal-box" role="dialog" aria-modal="true" aria-labelledby="akunModalTitle">
        <div class="akun-modal-head">
            <div>
                <span class="akun-eyebrow">USER MANAGEMENT</span>
                <h2 id="akunModalTitle">Tambah Akun</h2>
                <p id="akunModalDescription">Buat akun baru untuk mengakses Dashboard FIKES.</p>
            </div>

            <button type="button" class="akun-modal-close" onclick="tutupModal()" aria-label="Tutup">×</button>
        </div>

        <form method="post" id="akunForm" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="id" id="formId" value="">

            <div class="akun-form-section">
                <div class="akun-form-section-title">
                    <span>01</span>
                    <div>
                        <strong>Informasi Akun</strong>
                        <small>Identitas yang akan digunakan pengguna.</small>
                    </div>
                </div>

                <div class="akun-form-grid">
                    <div class="akun-field full">
                        <label for="formNama">Nama Lengkap <b>*</b></label>
                        <input type="text" id="formNama" name="nama" maxlength="100" placeholder="Contoh: Administrator FIKES" required>
                    </div>

                    <div class="akun-field">
                        <label for="formUsername">Username <b>*</b></label>
                        <input type="text" id="formUsername" name="username" maxlength="50" placeholder="Contoh: admin_fikes" required>
                        <small>3–50 karakter: huruf, angka, titik, _ atau -.</small>
                    </div>

                    <div class="akun-field">
                        <label for="formRole">Role <b>*</b></label>
                        <select id="formRole" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="editor" selected>Editor</option>
                        </select>
                        <small>Role mengikuti nilai pada database users.</small>
                    </div>
                </div>
            </div>

            <div class="akun-form-section">
                <div class="akun-form-section-title">
                    <span>02</span>
                    <div>
                        <strong>Keamanan</strong>
                        <small id="passwordHelp">Password wajib diisi untuk akun baru.</small>
                    </div>
                </div>

                <div class="akun-form-grid">
                    <div class="akun-field">
                        <label for="formPassword">Password <b id="passwordRequired">*</b></label>
                        <div class="akun-password-wrap">
                            <input type="password" id="formPassword" name="password" minlength="6" placeholder="Minimal 6 karakter">
                            <button type="button" onclick="togglePassword('formPassword', this)" aria-label="Tampilkan password">◉</button>
                        </div>
                    </div>

                    <div class="akun-field">
                        <label for="formPasswordConfirmation">Konfirmasi Password <b id="confirmationRequired">*</b></label>
                        <input type="password" id="formPasswordConfirmation" name="password_confirmation" minlength="6" placeholder="Ulangi password">
                    </div>
                </div>
            </div>

            <div class="akun-modal-actions">
                <button type="button" class="akun-btn akun-btn-light" onclick="tutupModal()">Batal</button>
                <button type="submit" class="akun-btn akun-btn-primary" id="submitAkunBtn">Simpan Akun</button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================
     FORM HAPUS AKUN
     ========================================================= -->
<form method="post" id="deleteAkunForm" class="akun-hidden-form">
    <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" id="deleteAkunId" value="">
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
window.AKUN_PAGE = <?= json_encode([
    'status' => $status,
    'message' => $message,
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
</script>
<script src="<?= e($project_url) ?>/admin/modules/akun/akun-admin.js?v=20260918"></script>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
