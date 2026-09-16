<?php
$page_title = 'Kemahasiswaan';
require_once __DIR__ . '/../../config/auth.php';
wajib_login();

$jenis = ($_GET['jenis'] ?? 'himpunan') === 'ukm' ? 'ukm' : 'himpunan';
$labelJenis = $jenis === 'ukm' ? 'UKM Kemahasiswaan' : 'Himpunan Mahasiswa';
$baseUrl = 'index.php?jenis=' . urlencode($jenis);
$uploadDir = __DIR__ . '/../../uploads/kemahasiswaan/logo/';
if (!is_dir($uploadDir)) @mkdir($uploadDir, 0777, true);

function km_slug($s) {
    $s = trim($s);
    $s = preg_replace('/[^a-zA-Z0-9]+/', '-', $s);
    $s = trim($s, '-');
    return strtolower($s ?: 'organisasi-' . time());
}
function km_upload_logo($field, $old = '') {
    global $uploadDir;
    if (empty($_FILES[$field]['name'])) return $old;
    $allowed = ['jpg','jpeg','png','webp','svg'];
    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) return $old;
    if ($_FILES[$field]['size'] > 3 * 1024 * 1024) return $old;
    $name = 'org_' . date('YmdHis') . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
    if (move_uploaded_file($_FILES[$field]['tmp_name'], $uploadDir . $name)) {
        if ($old && is_file($uploadDir . basename($old))) @unlink($uploadDir . basename($old));
        return $name;
    }
    return $old;
}

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $st = $pdo->prepare('SELECT slug,logo FROM kemahasiswaan_organisasi WHERE id=? AND jenis=?');
    $st->execute([$id, $jenis]); $old = $st->fetch();
    if ($old) {
        $pdo->prepare('DELETE FROM kemahasiswaan_organisasi WHERE id=? AND jenis=?')->execute([$id,$jenis]);
        foreach (['kemahasiswaan_pengurus','kemahasiswaan_anggota','kemahasiswaan_kegiatan','kemahasiswaan_galeri'] as $t) {
            $pdo->prepare("DELETE FROM $t WHERE organisasi_slug=?")->execute([$old['slug']]);
        }
        if (!empty($old['logo']) && is_file($uploadDir . basename($old['logo']))) @unlink($uploadDir . basename($old['logo']));
    }
    header('Location:' . $baseUrl . '&ok=' . urlencode('Organisasi berhasil dihapus.')); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save_org') {
            $id = (int)($_POST['id'] ?? 0);
            $nama = trim($_POST['nama'] ?? '');
            $slug = km_slug($_POST['slug'] ?? $nama);
            $kategori = trim($_POST['kategori'] ?? '');
            $deskripsi = trim($_POST['deskripsi'] ?? '');
            $fokus = trim($_POST['fokus'] ?? '');
            $visi = trim($_POST['visi'] ?? '');
            $misi = trim($_POST['misi'] ?? '');
            $ketua = trim($_POST['ketua_nama'] ?? '');
            $sekretariat = trim($_POST['sekretariat'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telepon = trim($_POST['telepon'] ?? '');
            $instagram = trim($_POST['instagram'] ?? '');
            $facebook = trim($_POST['facebook'] ?? '');
            $youtube = trim($_POST['youtube'] ?? '');
            $status = ($_POST['status'] ?? 'aktif') === 'nonaktif' ? 'nonaktif' : 'aktif';
            $urut = max(1,(int)($_POST['nomor_urut'] ?? 1));
            $oldLogo = trim($_POST['old_logo'] ?? '');
            $logo = km_upload_logo('logo', $oldLogo);
            if ($nama === '') throw new RuntimeException('Nama organisasi wajib diisi.');
            if ($id) {
                $sql='UPDATE kemahasiswaan_organisasi SET nama=?,slug=?,kategori=?,deskripsi=?,fokus=?,visi=?,misi=?,ketua_nama=?,sekretariat=?,email=?,telepon=?,instagram=?,facebook=?,youtube=?,logo=?,status=?,nomor_urut=? WHERE id=? AND jenis=?';
                $pdo->prepare($sql)->execute([$nama,$slug,$kategori,$deskripsi,$fokus,$visi,$misi,$ketua,$sekretariat,$email,$telepon,$instagram,$facebook,$youtube,$logo,$status,$urut,$id,$jenis]);
            } else {
                $sql='INSERT INTO kemahasiswaan_organisasi(jenis,nama,slug,kategori,deskripsi,fokus,visi,misi,ketua_nama,sekretariat,email,telepon,instagram,facebook,youtube,logo,status,nomor_urut) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
                $pdo->prepare($sql)->execute([$jenis,$nama,$slug,$kategori,$deskripsi,$fokus,$visi,$misi,$ketua,$sekretariat,$email,$telepon,$instagram,$facebook,$youtube,$logo,$status,$urut]);
            }
            header('Location:' . $baseUrl . '&ok=' . urlencode($id?'Data organisasi berhasil diperbarui.':'Organisasi berhasil ditambahkan.')); exit;
        }
    } catch (Throwable $e) {
        header('Location:' . $baseUrl . '&err=' . urlencode($e->getMessage())); exit;
    }
}

$editId=(int)($_GET['edit']??0); $edit=[];
if ($editId) { $st=$pdo->prepare('SELECT * FROM kemahasiswaan_organisasi WHERE id=? AND jenis=?'); $st->execute([$editId,$jenis]); $edit=$st->fetch()?:[]; }
$data=$pdo->prepare('SELECT * FROM kemahasiswaan_organisasi WHERE jenis=? ORDER BY nomor_urut ASC,id DESC'); $data->execute([$jenis]); $data=$data->fetchAll();

include __DIR__.'/../../includes/header.php';
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
.km-wrap{padding:24px;max-width:none}.km-tabs{display:flex;gap:8px;margin-bottom:18px}.km-tabs a{padding:10px 15px;border-radius:10px;text-decoration:none;border:1px solid #dce7e3;color:#36534a;background:#fff;font-weight:700;font-size:13px}.km-tabs a.active{background:#087f5b;color:#fff;border-color:#087f5b}.km-grid{display:grid;grid-template-columns:1.1fr .9fr;gap:20px}.km-card{background:#fff;border:1px solid #e3ebe8;border-radius:16px;padding:20px;box-shadow:0 8px 25px rgba(18,55,42,.05)}.km-form{display:grid;grid-template-columns:1fr 1fr;gap:14px}.km-form .full{grid-column:1/-1}.km-form label{display:block;font-size:12px;font-weight:700;color:#38564d;margin-bottom:6px}.km-form input,.km-form select,.km-form textarea{width:100%;box-sizing:border-box;border:1px solid #dce7e3;border-radius:10px;padding:10px 12px;font:inherit;background:#fff}.km-form textarea{min-height:90px;resize:vertical}.km-actions{display:flex;gap:8px;flex-wrap:wrap}.km-btn{display:inline-flex;align-items:center;justify-content:center;padding:9px 13px;border-radius:9px;border:0;text-decoration:none;font-weight:700;font-size:12px;cursor:pointer;background:#eef4f1;color:#26463b}.km-btn.primary{background:#087f5b;color:#fff}.km-btn.danger{background:#fff0f0;color:#b42318}.km-table{width:100%;border-collapse:collapse}.km-table th,.km-table td{padding:11px 10px;border-bottom:1px solid #edf2f0;text-align:left;font-size:12px;vertical-align:middle}.km-table th{color:#567169;background:#f8fbfa}.km-logo{width:48px;height:48px;border-radius:10px;object-fit:contain;background:#f4f8f6;border:1px solid #e1ebe7}.km-mini{font-size:11px;color:#70817b}.km-title{font-size:15px;color:#173b36;font-weight:800;margin:0 0 4px}.km-count{font-size:12px;color:#60746c}.km-detail{margin-top:14px;padding-top:14px;border-top:1px solid #edf2f0}.km-help{padding:12px;border-radius:10px;background:#f3faf7;color:#4e6960;font-size:12px;line-height:1.7;margin-bottom:15px}@media(max-width:1000px){.km-grid{grid-template-columns:1fr}}@media(max-width:650px){.km-wrap{padding:14px}.km-form{grid-template-columns:1fr}.km-form .full{grid-column:auto}.km-table{min-width:760px}.km-card.table-card{overflow:auto}}
</style>
<div class="km-wrap">
  <div class="page-head"><div><span class="eyebrow">KEMAHASISWAAN</span><h1><?=e($labelJenis)?></h1><p>Kelola profil organisasi dan seluruh informasi yang ditampilkan pada halaman frontend.</p></div><a class="btn primary" href="<?=$baseUrl?>">+ Organisasi Baru</a></div>
  <div class="km-tabs"><a class="<?= $jenis==='himpunan'?'active':'' ?>" href="index.php?jenis=himpunan">Himpunan Mahasiswa</a><a class="<?= $jenis==='ukm'?'active':'' ?>" href="index.php?jenis=ukm">UKM Kemahasiswaan</a></div>
  <div class="km-grid">
    <div class="km-card">
      <h2><?= $edit ? 'Edit Organisasi' : 'Tambah Organisasi' ?></h2>
      <div class="km-help">Isi profil utama di sini. Setelah disimpan, gunakan tombol <b>Kelola Detail</b> untuk mengatur pengurus, anggota, kegiatan, dan galeri.</div>
      <form class="km-form" method="post" enctype="multipart/form-data"><input type="hidden" name="action" value="save_org"><input type="hidden" name="id" value="<?=e($edit['id']??0)?>"><input type="hidden" name="old_logo" value="<?=e($edit['logo']??'')?>">
        <div><label>Nama Organisasi *</label><input name="nama" required value="<?=e($edit['nama']??'')?>"></div>
        <div><label>Slug URL *</label><input name="slug" required value="<?=e($edit['slug']??'')?>" placeholder="contoh: himafarda"></div>
        <div><label>Kategori</label><input name="kategori" value="<?=e($edit['kategori']??'')?>" placeholder="Olahraga, Seni, Sosial..." ></div>
        <div><label>Urutan</label><input type="number" min="1" name="nomor_urut" value="<?=e($edit['nomor_urut']??1)?>"></div>
        <div class="full"><label>Deskripsi</label><textarea name="deskripsi"><?=e($edit['deskripsi']??'')?></textarea></div>
        <div class="full"><label>Fokus / Bidang Kegiatan</label><textarea name="fokus"><?=e($edit['fokus']??'')?></textarea></div>
        <div><label>Visi</label><textarea name="visi"><?=e($edit['visi']??'')?></textarea></div><div><label>Misi</label><textarea name="misi"><?=e($edit['misi']??'')?></textarea></div>
        <div><label>Ketua Saat Ini</label><input name="ketua_nama" value="<?=e($edit['ketua_nama']??'')?>"></div><div><label>Sekretariat</label><input name="sekretariat" value="<?=e($edit['sekretariat']??'')?>"></div>
        <div><label>Email</label><input type="email" name="email" value="<?=e($edit['email']??'')?>"></div><div><label>Telepon</label><input name="telepon" value="<?=e($edit['telepon']??'')?>"></div>
        <div><label>Instagram</label><input name="instagram" value="<?=e($edit['instagram']??'')?>"></div><div><label>Facebook</label><input name="facebook" value="<?=e($edit['facebook']??'')?>"></div>
        <div><label>YouTube</label><input name="youtube" value="<?=e($edit['youtube']??'')?>"></div><div><label>Status</label><select name="status"><option value="aktif" <?=($edit['status']??'aktif')==='aktif'?'selected':''?>>Aktif</option><option value="nonaktif" <?=($edit['status']??'')==='nonaktif'?'selected':''?>>Nonaktif</option></select></div>
        <div class="full"><label>Logo (JPG, PNG, WEBP, SVG — maks. 3 MB)</label><input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp,.svg"><?php if(!empty($edit['logo'])):?><div class="km-mini">Logo saat ini: <?=e($edit['logo'])?></div><?php endif;?></div>
        <div class="full km-actions"><button class="km-btn primary" type="submit">Simpan <?=e($labelJenis)?></button><?php if($edit):?><a class="km-btn" href="<?=$baseUrl?>">Batal Edit</a><?php endif;?></div>
      </form>
    </div>
    <div class="km-card table-card"><h2>Daftar <?=e($labelJenis)?></h2><table class="km-table"><thead><tr><th>Logo</th><th>Organisasi</th><th>Kategori</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php if(!$data):?><tr><td colspan="5">Belum ada data.</td></tr><?php else: foreach($data as $d):?><tr><td><?php if($d['logo']):?><img class="km-logo" src="../../uploads/kemahasiswaan/logo/<?=rawurlencode(basename($d['logo']))?>" alt="<?=e($d['nama'])?>"><?php else:?><div class="km-logo"></div><?php endif;?></td><td><div class="km-title"><?=e($d['nama'])?></div><div class="km-mini">/<?=$jenis?>/<?=e($d['slug'])?></div></td><td><?=e($d['kategori']?:'-')?></td><td><?=e($d['status'])?></td><td><div class="km-actions"><a class="km-btn" href="index.php?jenis=<?=$jenis?>&edit=<?=$d['id']?>">Edit</a><a class="km-btn primary" href="detail.php?jenis=<?=$jenis?>&slug=<?=rawurlencode($d['slug'])?>">Kelola Detail</a><a class="km-btn danger" href="index.php?jenis=<?=$jenis?>&hapus=<?=$d['id']?>" onclick="return confirm('Hapus organisasi dan seluruh detailnya?')">Hapus</a></div></td></tr><?php endforeach; endif;?></tbody></table></div>
  </div>
</div>
<?php if(isset($_GET['ok'])):?><script>Swal.fire({icon:'success',title:'Berhasil',text:<?=json_encode($_GET['ok'])?>,timer:1800,showConfirmButton:false});</script><?php endif;?>
<?php if(isset($_GET['err'])):?><script>Swal.fire({icon:'error',title:'Gagal',text:<?=json_encode($_GET['err'])?>});</script><?php endif;?>
<?php require __DIR__.'/../../includes/footer.php'; ?>
