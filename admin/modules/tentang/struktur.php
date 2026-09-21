<?php
$page_title = 'Riwayat Struktur Organisasi';
require_once __DIR__ . '/../../config/auth.php';
wajib_login();

$project_url = '';
if (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) {
  $project_url = substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], '/admin/'));
}

$pesan = '';
$tipe = 'success';

if (isset($_GET['hapus'])) {
  $id = (int) $_GET['hapus'];
  $stmt = $pdo->prepare("DELETE FROM struktur_organisasi WHERE id=?");
  $stmt->execute([$id]);
  header('Location: struktur.php?pesan=' . ($stmt->rowCount() ? 'hapus' : 'gagal'));
  exit;
}

if (isset($_GET['pesan'])) {
  if ($_GET['pesan'] === 'simpan') $pesan = 'Riwayat struktur organisasi berhasil disimpan.';
  if ($_GET['pesan'] === 'hapus') $pesan = 'Riwayat struktur organisasi berhasil dihapus.';
  if ($_GET['pesan'] === 'gagal') { $pesan = 'Data tidak ditemukan.'; $tipe = 'danger'; }
}

$edit = null;
if (isset($_GET['edit'])) {
  $id = (int) $_GET['edit'];
  $stmt = $pdo->prepare("SELECT * FROM struktur_organisasi WHERE id=?");
  $stmt->execute([$id]);
  $edit = $stmt->fetch();
  if (!$edit) { $pesan = 'Data riwayat tidak ditemukan.'; $tipe = 'danger'; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = (int) ($_POST['id'] ?? 0);
  $periode = trim($_POST['periode'] ?? '');
  $sk_rektor = trim($_POST['sk_rektor'] ?? '');
  $dekan = trim($_POST['dekan'] ?? '');
  $wakil_akademik = trim($_POST['wakil_dekan_akademik'] ?? '');
  $wakil_adum = trim($_POST['wakil_dekan_adum_keu'] ?? '');
  $wakil_kemahasiswaan = trim($_POST['wakil_dekan_kemahasiswaan'] ?? '');

  if ($periode === '' || $sk_rektor === '' || $dekan === '' || $wakil_akademik === '' || $wakil_adum === '' || $wakil_kemahasiswaan === '') {
    $pesan = 'Semua field riwayat wajib diisi.';
    $tipe = 'danger';
  } elseif ($id) {
    $stmt = $pdo->prepare("UPDATE struktur_organisasi SET periode=?, sk_rektor=?, dekan=?, wakil_dekan_akademik=?, wakil_dekan_adum_keu=?, wakil_dekan_kemahasiswaan=? WHERE id=?");
    $stmt->execute([$periode, $sk_rektor, $dekan, $wakil_akademik, $wakil_adum, $wakil_kemahasiswaan, $id]);
    header('Location: struktur.php?pesan=simpan');
    exit;
  } else {
    $stmt = $pdo->prepare("INSERT INTO struktur_organisasi (periode,sk_rektor,dekan,wakil_dekan_akademik,wakil_dekan_adum_keu,wakil_dekan_kemahasiswaan) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$periode, $sk_rektor, $dekan, $wakil_akademik, $wakil_adum, $wakil_kemahasiswaan]);
    header('Location: struktur.php?pesan=simpan');
    exit;
  }
}

$data = $pdo->query("SELECT * FROM struktur_organisasi ORDER BY id DESC")->fetchAll();
?>
<?php require __DIR__ . '/../../includes/header.php'; ?>

<div class="page-head">
  <div>
    <span class="eyebrow">TENTANG FIKES</span>
    <h1>Riwayat Struktur Organisasi</h1>
    <p>Kelola riwayat periode, SK Rektor, dan susunan pimpinan FIKES. Gambar struktur dikelola di menu terpisah.</p>
  </div>
  <div>
    <a href="struktur.php" class="btn">↻ Reset</a>
    <button type="button" class="btn primary" onclick="bukaModalStruktur()">＋ Tambah Riwayat</button>
  </div>
</div>

<?php if ($pesan): ?><div class="alert <?= e($tipe) ?>"><?= e($pesan) ?></div><?php endif; ?>

<div class="panel">
  <div class="panel-head">
    <div>
      <h2>Data Riwayat Kepemimpinan</h2>
      <p style="margin:4px 0 0;color:#8993a5;font-size:11px;">Satu baris = satu periode kepemimpinan.</p>
    </div>
  </div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>No</th><th>Periode</th><th>SK Rektor</th><th>Dekan</th><th>Wakil Dekan Akademik</th><th>Wakil Dekan Adum & Keu</th><th>Wakil Dekan Kemahasiswaan</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php if (!$data): ?>
        <tr><td colspan="8" style="text-align:center;color:#8993a5;padding:35px;">Belum ada riwayat struktur organisasi.</td></tr>
      <?php else: ?>
        <?php $no=1; foreach ($data as $row): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><span class="badge success"><?= e($row['periode']) ?></span></td>
            <td style="white-space:normal;min-width:190px;"><?= e($row['sk_rektor']) ?></td>
            <td style="white-space:normal;min-width:180px;"><?= e($row['dekan']) ?></td>
            <td style="white-space:normal;min-width:210px;"><?= e($row['wakil_dekan_akademik']) ?></td>
            <td style="white-space:normal;min-width:210px;"><?= e($row['wakil_dekan_adum_keu']) ?></td>
            <td style="white-space:normal;min-width:210px;"><?= e($row['wakil_dekan_kemahasiswaan']) ?></td>
            <td><a href="?edit=<?= (int)$row['id'] ?>" class="btn small">Edit</a> <a href="?hapus=<?= (int)$row['id'] ?>" class="btn small danger-text" onclick="return confirm('Hapus riwayat periode <?= e($row['periode']) ?>?')">Hapus</a></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal <?= $edit ? 'show' : '' ?>" id="modalStruktur">
  <div class="modal-box large">
    <div class="modal-head"><div><span class="eyebrow">FORM RIWAYAT</span><h2><?= $edit ? 'Edit Riwayat Struktur' : 'Tambah Riwayat Struktur' ?></h2></div><button type="button" onclick="tutupModalStruktur()">×</button></div>
    <form method="post">
      <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
      <div class="form-grid">
        <div><label>Periode</label><input type="text" name="periode" value="<?= e($edit['periode'] ?? '') ?>" placeholder="Contoh: 2024 - 2026" required></div>
        <div><label>SK Rektor</label><input type="text" name="sk_rektor" value="<?= e($edit['sk_rektor'] ?? '') ?>" placeholder="Nomor SK Rektor" required></div>
        <div><label>Dekan</label><input type="text" name="dekan" value="<?= e($edit['dekan'] ?? '') ?>" required></div>
        <div><label>Wakil Dekan Bidang Akademik</label><input type="text" name="wakil_dekan_akademik" value="<?= e($edit['wakil_dekan_akademik'] ?? '') ?>" required></div>
        <div><label>Wakil Dekan Bidang Adum & Keu</label><input type="text" name="wakil_dekan_adum_keu" value="<?= e($edit['wakil_dekan_adum_keu'] ?? '') ?>" required></div>
        <div><label>Wakil Dekan Bidang Kemahasiswaan</label><input type="text" name="wakil_dekan_kemahasiswaan" value="<?= e($edit['wakil_dekan_kemahasiswaan'] ?? '') ?>" required></div>
      </div>
      <div class="modal-actions"><button type="button" class="btn" onclick="tutupModalStruktur()">Batal</button><button type="submit" class="btn primary">💾 Simpan Riwayat</button></div>
    </form>
  </div>
</div>
<script>
function bukaModalStruktur(){document.getElementById('modalStruktur').classList.add('show');}
function tutupModalStruktur(){document.getElementById('modalStruktur').classList.remove('show');if(window.history.replaceState&&<?= $edit ? 'true':'false' ?>)window.history.replaceState({},document.title,'struktur.php');}
document.getElementById('modalStruktur').addEventListener('click',function(e){if(e.target===this)tutupModalStruktur();});
</script>
