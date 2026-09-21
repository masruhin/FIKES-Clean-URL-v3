<?php
$page_title = 'Gambar Struktur Organisasi';
require_once __DIR__ . '/../../config/auth.php';
wajib_login();

$project_url = '';
if (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) $project_url = substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], '/admin/'));
$upload_dir = __DIR__ . '/../../uploads/struktur/';
$upload_url = $project_url . '/admin/uploads/struktur/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

$pesan=''; $tipe='success';
function hapus_gambar_struktur($nama){ global $upload_dir; if($nama && is_file($upload_dir.$nama)) @unlink($upload_dir.$nama); }

if (isset($_GET['aktif'])) {
  $id=(int)$_GET['aktif'];
  $pdo->beginTransaction();
  try {
    $pdo->exec("UPDATE struktur_organisasi_gambar SET status='tidak_aktif'");
    $stmt=$pdo->prepare("UPDATE struktur_organisasi_gambar SET status='aktif' WHERE id=?");
    $stmt->execute([$id]);
    $pdo->commit();
    header('Location: gambar-struktur.php?pesan=aktif'); exit;
  } catch(Exception $e){ if($pdo->inTransaction())$pdo->rollBack(); $pesan='Status gambar gagal diubah.'; $tipe='danger'; }
}

if (isset($_GET['nonaktif'])) {
  $id=(int)$_GET['nonaktif'];
  $stmt=$pdo->prepare("UPDATE struktur_organisasi_gambar SET status='tidak_aktif' WHERE id=?");
  $stmt->execute([$id]);
  header('Location: gambar-struktur.php?pesan=nonaktif'); exit;
}

if (isset($_GET['hapus'])) {
  $id=(int)$_GET['hapus'];
  $stmt=$pdo->prepare("SELECT gambar FROM struktur_organisasi_gambar WHERE id=?"); $stmt->execute([$id]); $row=$stmt->fetch();
  if($row){$stmt=$pdo->prepare("DELETE FROM struktur_organisasi_gambar WHERE id=?");$stmt->execute([$id]);hapus_gambar_struktur($row['gambar']);header('Location: gambar-struktur.php?pesan=hapus');exit;}
  header('Location: gambar-struktur.php?pesan=gagal');exit;
}

if(isset($_GET['pesan'])){
  if($_GET['pesan']==='simpan')$pesan='Gambar struktur berhasil disimpan.';
  if($_GET['pesan']==='aktif')$pesan='Gambar ditetapkan sebagai gambar aktif. Gambar aktif sebelumnya otomatis menjadi tidak aktif.';
  if($_GET['pesan']==='nonaktif')$pesan='Gambar berhasil dinonaktifkan.';
  if($_GET['pesan']==='hapus')$pesan='Gambar berhasil dihapus.';
  if($_GET['pesan']==='format'){$pesan='Format gambar harus JPG, JPEG, PNG, atau WEBP.';$tipe='danger';}
  if($_GET['pesan']==='ukuran'){$pesan='Ukuran gambar maksimal 5 MB.';$tipe='danger';}
}

$edit=null;
if(isset($_GET['edit'])){ $stmt=$pdo->prepare("SELECT * FROM struktur_organisasi_gambar WHERE id=?");$stmt->execute([(int)$_GET['edit']]);$edit=$stmt->fetch(); }

if($_SERVER['REQUEST_METHOD']==='POST'){
  $id=(int)($_POST['id']??0); $judul=trim($_POST['judul']??''); $periode=trim($_POST['periode']??''); $status=($_POST['status']??'tidak_aktif')==='aktif'?'aktif':'tidak_aktif';
  $gambar_lama='';
  if($id){$stmt=$pdo->prepare("SELECT gambar FROM struktur_organisasi_gambar WHERE id=?");$stmt->execute([$id]);$lama=$stmt->fetch();$gambar_lama=$lama['gambar']??'';}
  $gambar_baru=$gambar_lama;
  if(!empty($_FILES['gambar']['name'])){
    $ext=strtolower(pathinfo($_FILES['gambar']['name'],PATHINFO_EXTENSION)); $allowed=['jpg','jpeg','png','webp'];
    if(!in_array($ext,$allowed,true)){header('Location: gambar-struktur.php?pesan=format');exit;}
    if((int)$_FILES['gambar']['size']>5*1024*1024){header('Location: gambar-struktur.php?pesan=ukuran');exit;}
    $gambar_baru='struktur_gambar_'.date('YmdHis').'_'.bin2hex(random_bytes(4)).'.'.$ext;
    if(!move_uploaded_file($_FILES['gambar']['tmp_name'],$upload_dir.$gambar_baru)){ $pesan='Gambar gagal diupload. Pastikan folder upload dapat ditulis.';$tipe='danger'; }
    else if($gambar_lama) hapus_gambar_struktur($gambar_lama);
  } elseif(!$id) { $pesan='Silakan pilih gambar struktur organisasi.'; $tipe='danger'; }

  if($pesan===''){
    if($status==='aktif') $pdo->exec("UPDATE struktur_organisasi_gambar SET status='tidak_aktif'");
    if($id){$stmt=$pdo->prepare("UPDATE struktur_organisasi_gambar SET judul=?, periode=?, gambar=?, status=? WHERE id=?");$stmt->execute([$judul,$periode,$gambar_baru,$status,$id]);}
    else {$stmt=$pdo->prepare("INSERT INTO struktur_organisasi_gambar (judul,periode,gambar,status) VALUES (?,?,?,?)");$stmt->execute([$judul,$periode,$gambar_baru,$status]);}
    header('Location: gambar-struktur.php?pesan=simpan');exit;
  }
}

$data=$pdo->query("SELECT * FROM struktur_organisasi_gambar ORDER BY (status='aktif') DESC,id DESC")->fetchAll();
?>
<?php require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-head">
  <div><span class="eyebrow">TENTANG FIKES</span><h1>Gambar Struktur Organisasi</h1><p>Kelola gambar bagan struktur secara terpisah dari riwayat kepemimpinan.</p></div>
  <div><a href="gambar-struktur.php" class="btn">↻ Reset</a><button type="button" class="btn primary" onclick="bukaModalGambar()">＋ Tambah Gambar</button></div>
</div>
<?php if($pesan): ?><div class="alert <?= e($tipe) ?>"><?= e($pesan) ?></div><?php endif; ?>
<div class="panel">
  <div class="panel-head"><div><h2>Daftar Gambar Struktur</h2><p style="margin:4px 0 0;color:#8993a5;font-size:11px;">Hanya satu gambar dapat berstatus <b>AKTIF</b> dan akan ditampilkan di frontend.</p></div></div>
  <div class="table-wrap"><table><thead><tr><th>No</th><th>Preview</th><th>Judul</th><th>Periode</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
  <?php if(!$data): ?><tr><td colspan="6" style="text-align:center;color:#8993a5;padding:35px;">Belum ada gambar struktur organisasi.</td></tr>
  <?php else: $no=1; foreach($data as $row): ?><tr>
    <td><?= $no++ ?></td>
    <td><a href="<?= e($upload_url.$row['gambar']) ?>" target="_blank"><img src="<?= e($upload_url.$row['gambar']) ?>" alt="<?= e($row['judul']) ?>" style="width:120px;height:75px;object-fit:contain;background:#f7faf9;border-radius:7px;border:1px solid #e8ecf3;"></a></td>
    <td style="white-space:normal;min-width:180px;"><?= e($row['judul']) ?></td><td><?= e($row['periode'] ?: '-') ?></td>
    <td><?php if($row['status']==='aktif'): ?><span class="badge success">● AKTIF</span><?php else: ?><span class="badge">○ TIDAK AKTIF</span><?php endif; ?></td>
    <td><a href="?edit=<?= (int)$row['id'] ?>" class="btn small">Edit</a> <?php if($row['status']==='aktif'): ?><a href="?nonaktif=<?= (int)$row['id'] ?>" class="btn small" onclick="return confirm('Nonaktifkan gambar ini?')">Nonaktifkan</a><?php else: ?><a href="?aktif=<?= (int)$row['id'] ?>" class="btn small">Jadikan Aktif</a><?php endif; ?> <a href="?hapus=<?= (int)$row['id'] ?>" class="btn small danger-text" onclick="return confirm('Hapus gambar ini?')">Hapus</a></td>
  </tr><?php endforeach; endif; ?>
  </tbody></table></div>
</div>
<div class="modal <?= $edit ? 'show' : '' ?>" id="modalGambar"><div class="modal-box large">
  <div class="modal-head"><div><span class="eyebrow">FORM GAMBAR</span><h2><?= $edit?'Edit Gambar Struktur':'Tambah Gambar Struktur' ?></h2></div><button type="button" onclick="tutupModalGambar()">×</button></div>
  <form method="post" enctype="multipart/form-data"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>">
    <div class="form-grid">
      <div><label>Judul Gambar</label><input type="text" name="judul" value="<?= e($edit['judul']??'') ?>" placeholder="Contoh: Bagan Struktur Organisasi FIKES" required></div>
      <div><label>Periode</label><input type="text" name="periode" value="<?= e($edit['periode']??'') ?>" placeholder="Contoh: 2024 - 2026"></div>
      <div class="full"><label>File Gambar <?= $edit?'(kosongkan jika tidak diganti)':'' ?></label><input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp" <?= $edit?'':'required' ?>><small style="display:block;color:#8993a5;margin-top:-8px;margin-bottom:12px;">JPG, JPEG, PNG, WEBP. Maksimal 5 MB.</small><?php if(!empty($edit['gambar'])):?><img src="<?= e($upload_url.$edit['gambar']) ?>" alt="Preview" style="max-width:100%;max-height:300px;border:1px solid #e8ecf3;border-radius:10px;background:#f7faf9;padding:4px;"><?php endif; ?></div>
      <div><label>Status</label><select name="status"><option value="aktif" <?= (($edit['status']??'tidak_aktif')==='aktif')?'selected':'' ?>>AKTIF — tampil di frontend</option><option value="tidak_aktif" <?= (($edit['status']??'tidak_aktif')==='tidak_aktif')?'selected':'' ?>>TIDAK AKTIF — tidak tampil</option></select></div>
    </div>
    <div class="modal-actions"><button type="button" class="btn" onclick="tutupModalGambar()">Batal</button><button type="submit" class="btn primary">💾 Simpan Gambar</button></div>
  </form>
</div></div>
<script>
function bukaModalGambar(){document.getElementById('modalGambar').classList.add('show');}
function tutupModalGambar(){document.getElementById('modalGambar').classList.remove('show');if(window.history.replaceState&&<?= $edit?'true':'false' ?>)window.history.replaceState({},document.title,'gambar-struktur.php');}
document.getElementById('modalGambar').addEventListener('click',function(e){if(e.target===this)tutupModalGambar();});
</script>
