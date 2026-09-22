<?php
require_once __DIR__ . '/../../config/auth.php';
wajib_login();
require_once __DIR__ . '/../../config/database.php';

$page_title = 'SPMI';
$uploadDir = __DIR__ . '/../../uploads/spmi/';
$uploadWeb = '/fikes/admin/uploads/spmi/';
if (!is_dir($uploadDir)) @mkdir($uploadDir, 0777, true);
if (empty($_SESSION['spmi_csrf'])) $_SESSION['spmi_csrf'] = bin2hex(random_bytes(24));
$csrf = $_SESSION['spmi_csrf'];
$flash = null;
function clean_text($v)
{
  return trim((string)($v ?? ''));
}
function valid_status($v)
{
  return in_array($v, ['aktif', 'nonaktif'], true) ? $v : 'aktif';
}
function save_upload($field, $allowed, $maxBytes, $dir)
{
  if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) return null;
  $f = $_FILES[$field];
  if ($f['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Upload gagal.');
  if ($f['size'] > $maxBytes) throw new RuntimeException('Ukuran file terlalu besar.');
  $fi = new finfo(FILEINFO_MIME_TYPE);
  $mime = $fi->file($f['tmp_name']);
  if (!isset($allowed[$mime])) throw new RuntimeException('Jenis file tidak diizinkan.');
  $ext = $allowed[$mime];
  $name = 'spmi_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
  if (!move_uploaded_file($f['tmp_name'], $dir . $name)) throw new RuntimeException('File gagal disimpan.');
  return $name;
}
function delete_upload($dir, $name)
{
  if ($name && is_file($dir . $name)) @unlink($dir . $name);
}

try {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['spmi_csrf'] ?? '', $_POST['csrf'] ?? '')) throw new RuntimeException('Token keamanan tidak valid. Muat ulang halaman.');
    $action = $_POST['action'] ?? '';
    $type = $_POST['type'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    $allowedImg = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $allowedDoc = ['application/pdf' => 'pdf', 'application/msword' => 'doc', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx', 'application/vnd.ms-excel' => 'xls', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx', 'application/vnd.ms-powerpoint' => 'ppt', 'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx'];
    if ($action === 'delete') {
      $map = ['informasi' => ['spmi_informasi', ''], 'siklus' => ['spmi_siklus', ''], 'dokumen' => ['spmi_dokumen', 'file_dokumen'], 'kegiatan' => ['spmi_kegiatan', 'gambar']];
      if (!isset($map[$type]) || $id < 1) throw new RuntimeException('Data tidak valid.');
      [$table, $filecol] = $map[$type];
      if ($filecol) {
        $q = $pdo->prepare("SELECT `$filecol` FROM `$table` WHERE id=?");
        $q->execute([$id]);
        $old = $q->fetchColumn();
        delete_upload($uploadDir, $old);
      }
      $q = $pdo->prepare("DELETE FROM `$table` WHERE id=?");
      $q->execute([$id]);
      $flash = ['type' => 'success', 'text' => 'Data berhasil dihapus.'];
    } else {
      if ($type === 'informasi') {
        $data = [clean_text($_POST['judul']), clean_text($_POST['ringkasan']), clean_text($_POST['isi']), valid_status($_POST['status'] ?? ''), (int)($_POST['nomor_urut'] ?? 0)];
        if ($id) {
          $q = $pdo->prepare('UPDATE spmi_informasi SET judul=?,ringkasan=?,isi=?,status=?,nomor_urut=? WHERE id=?');
          $q->execute([...$data, $id]);
        } else {
          $q = $pdo->prepare('INSERT INTO spmi_informasi(judul,ringkasan,isi,status,nomor_urut) VALUES(?,?,?,?,?)');
          $q->execute($data);
        }
        $flash = ['type' => 'success', 'text' => 'Informasi SPMI berhasil disimpan.'];
      } elseif ($type === 'siklus') {
        $data = [clean_text($_POST['tahap']), clean_text($_POST['kode']), clean_text($_POST['deskripsi']), valid_status($_POST['status'] ?? ''), (int)($_POST['nomor_urut'] ?? 0)];
        if ($id) {
          $q = $pdo->prepare('UPDATE spmi_siklus SET tahap=?,kode=?,deskripsi=?,status=?,nomor_urut=? WHERE id=?');
          $q->execute([...$data, $id]);
        } else {
          $q = $pdo->prepare('INSERT INTO spmi_siklus(tahap,kode,deskripsi,status,nomor_urut) VALUES(?,?,?,?,?)');
          $q->execute($data);
        }
        $flash = ['type' => 'success', 'text' => 'Tahap PPEPP berhasil disimpan.'];
      } elseif ($type === 'dokumen') {
        $new = save_upload('file_dokumen', $allowedDoc, 10 * 1024 * 1024, $uploadDir);
        if ($id) {
          $q = $pdo->prepare('SELECT file_dokumen FROM spmi_dokumen WHERE id=?');
          $q->execute([$id]);
          $old = $q->fetchColumn();
          if ($new) {
            delete_upload($uploadDir, $old);
            $sql = 'UPDATE spmi_dokumen SET judul=?,kategori=?,tahun=?,deskripsi=?,file_dokumen=?,status=?,nomor_urut=? WHERE id=?';
            $params = [clean_text($_POST['judul']), clean_text($_POST['kategori']), clean_text($_POST['tahun']), clean_text($_POST['deskripsi']), $new, valid_status($_POST['status'] ?? ''), (int)($_POST['nomor_urut'] ?? 0), $id];
          } else {
            $sql = 'UPDATE spmi_dokumen SET judul=?,kategori=?,tahun=?,deskripsi=?,status=?,nomor_urut=? WHERE id=?';
            $params = [clean_text($_POST['judul']), clean_text($_POST['kategori']), clean_text($_POST['tahun']), clean_text($_POST['deskripsi']), valid_status($_POST['status'] ?? ''), (int)($_POST['nomor_urut'] ?? 0), $id];
          }
          $q = $pdo->prepare($sql);
          $q->execute($params);
        } else {
          $q = $pdo->prepare('INSERT INTO spmi_dokumen(judul,kategori,tahun,deskripsi,file_dokumen,status,nomor_urut) VALUES(?,?,?,?,?,?,?)');
          $q->execute([clean_text($_POST['judul']), clean_text($_POST['kategori']), clean_text($_POST['tahun']), clean_text($_POST['deskripsi']), $new, valid_status($_POST['status'] ?? ''), (int)($_POST['nomor_urut'] ?? 0)]);
        }
        $flash = ['type' => 'success', 'text' => 'Dokumen SPMI berhasil disimpan.'];
      } elseif ($type === 'kegiatan') {
        $new = save_upload('gambar', $allowedImg, 5 * 1024 * 1024, $uploadDir);
        if ($id) {
          $q = $pdo->prepare('SELECT gambar FROM spmi_kegiatan WHERE id=?');
          $q->execute([$id]);
          $old = $q->fetchColumn();
          if ($new) {
            delete_upload($uploadDir, $old);
            $sql = 'UPDATE spmi_kegiatan SET judul=?,tanggal=?,lokasi=?,ringkasan=?,isi=?,gambar=?,status=? WHERE id=?';
            $params = [clean_text($_POST['judul']), clean_text($_POST['tanggal']) ?: null, clean_text($_POST['lokasi']), clean_text($_POST['ringkasan']), clean_text($_POST['isi']), $new, valid_status($_POST['status'] ?? ''), $id];
          } else {
            $sql = 'UPDATE spmi_kegiatan SET judul=?,tanggal=?,lokasi=?,ringkasan=?,isi=?,status=? WHERE id=?';
            $params = [clean_text($_POST['judul']), clean_text($_POST['tanggal']) ?: null, clean_text($_POST['lokasi']), clean_text($_POST['ringkasan']), clean_text($_POST['isi']), valid_status($_POST['status'] ?? ''), $id];
          }
          $q = $pdo->prepare($sql);
          $q->execute($params);
        } else {
          $q = $pdo->prepare('INSERT INTO spmi_kegiatan(judul,tanggal,lokasi,ringkasan,isi,gambar,status) VALUES(?,?,?,?,?,?,?)');
          $q->execute([clean_text($_POST['judul']), clean_text($_POST['tanggal']) ?: null, clean_text($_POST['lokasi']), clean_text($_POST['ringkasan']), clean_text($_POST['isi']), $new, valid_status($_POST['status'] ?? '')]);
        }
        $flash = ['type' => 'success', 'text' => 'Kegiatan SPMI berhasil disimpan.'];
      } else throw new RuntimeException('Jenis data tidak dikenali.');
    }
  } elseif (isset($_GET['delete'])) {
    throw new RuntimeException('Gunakan tombol hapus pada halaman untuk menjaga keamanan.');
  }
} catch (Throwable $e) {
  $flash = ['type' => 'error', 'text' => $e->getMessage()];
}

$informasi = $pdo->query('SELECT * FROM spmi_informasi ORDER BY nomor_urut ASC,id DESC')->fetchAll();
$siklus = $pdo->query('SELECT * FROM spmi_siklus ORDER BY nomor_urut ASC,id ASC')->fetchAll();
$dokumen = $pdo->query('SELECT * FROM spmi_dokumen ORDER BY kategori ASC,nomor_urut ASC,id DESC')->fetchAll();
$kegiatan = $pdo->query('SELECT * FROM spmi_kegiatan ORDER BY tanggal DESC,id DESC')->fetchAll();
include __DIR__ . '/../../includes/header.php';
?>
<style>
.spmi-admin {
  padding: 22px
}

.spmi-head {
  display: flex;
  justify-content: space-between;
  gap: 16px;
  align-items: center;
  margin-bottom: 18px
}

.spmi-head h2 {
  margin: 0;
  color: #123f39
}

.muted {
  color: #71807c;
  font-size: 13px
}

.tabs {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin: 15px 0
}

.tab {
  border: 1px solid #dbe5e1;
  background: #fff;
  padding: 9px 13px;
  border-radius: 9px;
  text-decoration: none;
  color: #355c55;
  font-weight: 700;
  font-size: 13px
}

.tab.active {
  background: #078f78;
  color: #fff;
  border-color: #078f78
}

.card {
  background: #fff;
  border: 1px solid #e7eeeb;
  border-radius: 14px;
  padding: 18px;
  margin-bottom: 18px
}

.card-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 10px;
  margin-bottom: 12px
}

.card-head h3 {
  margin: 0;
  color: #384919;
  font-size: 17px
}

.btn {
  border: 0;
  border-radius: 9px;
  padding: 9px 13px;
  font-weight: 700;
  cursor: pointer;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 6px
}

.primary {
  background: #078f78;
  color: #fff
}

.soft {
  background: #edf7f4;
  color: #087b68
}

.danger {
  background: #fee2e2;
  color: #b91c1c
}

.table-wrap {
  overflow: auto;
  border: 1px solid #edf1f0;
  border-radius: 12px
}

.table {
  width: 100%;
  min-width: 760px;
  border-collapse: collapse
}

.table th,
.table td {
  padding: 11px;
  border-bottom: 1px solid #edf1f0;
  text-align: left;
  font-size: 13px;
  vertical-align: top
}

.table th {
  background: #f3faf8;
  color: #285b55
}

.badge {
  display: inline-flex;
  padding: 4px 8px;
  border-radius: 999px;
  background: #e7f8f2;
  color: #087b68;
  font-size: 11px;
  font-weight: 700
}

.badge.off {
  background: #f1f3f3;
  color: #6b7774
}

.actions {
  display: flex;
  gap: 6px;
  flex-wrap: wrap
}

.search {
  width: 100%;
  max-width: 320px;
  border: 1px solid #dbe4e2;
  border-radius: 9px;
  padding: 10px 12px;
  margin-bottom: 10px
}

.modal {
  position: fixed;
  inset: 0;
  background: rgba(11, 34, 29, .55);
  display: none;
  align-items: center;
  justify-content: center;
  padding: 18px;
  z-index: 9999
}

.modal.show {
  display: flex
}

.modal-box {
  width: min(720px, 100%);
  max-height: 90vh;
  overflow: auto;
  background: #fff;
  border-radius: 16px;
  padding: 22px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, .2)
}

.modal-box h3 {
  margin: 0 0 16px;
  color: #17483f
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px
}

.field.full {
  grid-column: 1/-1
}

.field label {
  display: block;
  font-size: 12px;
  font-weight: 700;
  color: #4e6761;
  margin-bottom: 6px
}

.field input,
.field select,
.field textarea {
  width: 100%;
  box-sizing: border-box;
  border: 1px solid #dbe4e2;
  border-radius: 9px;
  padding: 10px 12px;
  font: inherit
}

.field textarea {
  min-height: 110px;
  resize: vertical
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 8px;
  margin-top: 16px
}

.hint {
  font-size: 11px;
  color: #7a8884;
  margin-top: 5px
}

@media(max-width:700px) {
  .spmi-admin {
    padding: 14px
  }

  .form-grid {
    grid-template-columns: 1fr
  }

  .field.full {
    grid-column: auto
  }

  .spmi-head {
    align-items: flex-start;
    flex-direction: column
  }
}
</style>
<div class="spmi-admin">
  <div class="spmi-head">
    <div>
      <h2>SPMI</h2>
      <div class="muted">Kelola informasi, siklus PPEPP, dokumen, dan kegiatan penjaminan mutu.</div>
    </div><button class="btn primary" data-open="info">＋ Tambah Informasi</button>
  </div>
  <div class="tabs"><a class="tab active" href="#informasi">Informasi</a><a class="tab" href="#ppepp">PPEPP</a><a
      class="tab" href="#dokumen">Dokumen</a><a class="tab" href="#kegiatan">Kegiatan</a></div>
  <section class="card" id="informasi">
    <div class="card-head">
      <h3>Informasi SPMI</h3><button class="btn soft" data-open="info">＋ Tambah</button>
    </div><input class="search" data-search="info" placeholder="Cari informasi...">
    <div class="table-wrap">
      <table class="table" data-table="info">
        <thead>
          <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Ringkasan</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody><?php foreach ($informasi as $i): ?><tr>
            <td><?= e($i['nomor_urut']) ?></td>
            <td><?= e($i['judul']) ?></td>
            <td><?= e(mb_strimwidth($i['ringkasan'] ?: $i['isi'], 0, 100, '...')) ?></td>
            <td><span class="badge <?= $i['status'] === 'aktif' ? '' : 'off' ?>"><?= e($i['status']) ?></span></td>
            <td>
              <div class="actions"><button class="btn soft edit-info"
                  data-json='<?= e(json_encode($i)) ?>'>Edit</button><button class="btn danger del"
                  data-type="informasi" data-id="<?= $i['id'] ?>">Hapus</button></div>
            </td>
          </tr><?php endforeach; ?></tbody>
      </table>
    </div>
  </section>
  <section class="card" id="ppepp">
    <div class="card-head">
      <h3>Siklus PPEPP</h3><button class="btn soft" data-open="siklus">＋ Tambah</button>
    </div><input class="search" data-search="siklus" placeholder="Cari tahap PPEPP...">
    <div class="table-wrap">
      <table class="table" data-table="siklus">
        <thead>
          <tr>
            <th>Urutan</th>
            <th>Kode</th>
            <th>Tahap</th>
            <th>Deskripsi</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody><?php foreach ($siklus as $s): ?><tr>
            <td><?= e($s['nomor_urut']) ?></td>
            <td><?= e($s['kode']) ?></td>
            <td><?= e($s['tahap']) ?></td>
            <td><?= e(mb_strimwidth($s['deskripsi'], 0, 110, '...')) ?></td>
            <td><span class="badge <?= $s['status'] === 'aktif' ? '' : 'off' ?>"><?= e($s['status']) ?></span></td>
            <td>
              <div class="actions"><button class="btn soft edit-siklus"
                  data-json='<?= e(json_encode($s)) ?>'>Edit</button><button class="btn danger del" data-type="siklus"
                  data-id="<?= $s['id'] ?>">Hapus</button></div>
            </td>
          </tr><?php endforeach; ?></tbody>
      </table>
    </div>
  </section>
  <section class="card" id="dokumen">
    <div class="card-head">
      <h3>Dokumen SPMI</h3><button class="btn soft" data-open="dokumen">＋ Tambah</button>
    </div><input class="search" data-search="dokumen" placeholder="Cari dokumen...">
    <div class="table-wrap">
      <table class="table" data-table="dokumen">
        <thead>
          <tr>
            <th>Kategori</th>
            <th>Judul</th>
            <th>Tahun</th>
            <th>File</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody><?php foreach ($dokumen as $d): ?><tr>
            <td><?= e($d['kategori']) ?></td>
            <td><?= e($d['judul']) ?></td>
            <td><?= e($d['tahun']) ?></td>
            <td><?= e($d['file_dokumen'] ?: '-') ?></td>
            <td><span class="badge <?= $d['status'] === 'aktif' ? '' : 'off' ?>"><?= e($d['status']) ?></span></td>
            <td>
              <div class="actions"><button class="btn soft edit-dokumen"
                  data-json='<?= e(json_encode($d)) ?>'>Edit</button><button class="btn danger del" data-type="dokumen"
                  data-id="<?= $d['id'] ?>">Hapus</button></div>
            </td>
          </tr><?php endforeach; ?></tbody>
      </table>
    </div>
  </section>
  <section class="card" id="kegiatan">
    <div class="card-head">
      <h3>Kegiatan SPMI</h3><button class="btn soft" data-open="kegiatan">＋ Tambah</button>
    </div><input class="search" data-search="kegiatan" placeholder="Cari kegiatan...">
    <div class="table-wrap">
      <table class="table" data-table="kegiatan">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Judul</th>
            <th>Lokasi</th>
            <th>Gambar</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody><?php foreach ($kegiatan as $k): ?><tr>
            <td><?= e($k['tanggal']) ?></td>
            <td><?= e($k['judul']) ?></td>
            <td><?= e($k['lokasi']) ?></td>
            <td><?= e($k['gambar'] ?: '-') ?></td>
            <td><span class="badge <?= $k['status'] === 'aktif' ? '' : 'off' ?>"><?= e($k['status']) ?></span></td>
            <td>
              <div class="actions"><button class="btn soft edit-kegiatan"
                  data-json='<?= e(json_encode($k)) ?>'>Edit</button><button class="btn danger del" data-type="kegiatan"
                  data-id="<?= $k['id'] ?>">Hapus</button></div>
            </td>
          </tr><?php endforeach; ?></tbody>
      </table>
    </div>
  </section>
</div>

<?php
function modal_start($id, $title, $type, $enctype = '')
{
  echo '<div class="modal" id="modal-' . $id . '"><div class="modal-box"><h3>' . $title . '</h3><form method="post" enctype="' . $enctype . '"><input type="hidden" name="csrf" value="' . e($_SESSION['spmi_csrf']) . '"><input type="hidden" name="action" value="save"><input type="hidden" name="type" value="' . $type . '"><input type="hidden" name="id" id="' . $id . '-id" value="0">';
}
function modal_end()
{
  echo '<div class="modal-actions"><button type="button" class="btn soft modal-close">Batal</button><button type="submit" class="btn primary">Simpan</button></div></form></div></div>';
}
modal_start('info', 'Informasi SPMI', 'informasi'); ?>
<div class="form-grid">
  <div class="field full"><label>Judul</label><input name="judul" id="info-judul" required></div>
  <div class="field full"><label>Ringkasan</label><textarea name="ringkasan" id="info-ringkasan"></textarea></div>
  <div class="field full"><label>Isi Informasi</label><textarea name="isi" id="info-isi" required></textarea></div>
  <div class="field"><label>Urutan</label><input type="number" name="nomor_urut" id="info-urut" value="0"></div>
  <div class="field"><label>Status</label><select name="status" id="info-status">
      <option value="aktif">Aktif</option>
      <option value="nonaktif">Nonaktif</option>
    </select></div>
</div><?php modal_end();
      modal_start('siklus', 'Siklus PPEPP', 'siklus'); ?><div class="form-grid">
  <div class="field"><label>Kode</label><input name="kode" id="siklus-kode" placeholder="P"></div>
  <div class="field"><label>Urutan</label><input type="number" name="nomor_urut" id="siklus-urut" value="0"></div>
  <div class="field full"><label>Tahap</label><input name="tahap" id="siklus-tahap" required placeholder="Penetapan">
  </div>
  <div class="field full"><label>Deskripsi</label><textarea name="deskripsi" id="siklus-deskripsi" required></textarea>
  </div>
  <div class="field"><label>Status</label><select name="status" id="siklus-status">
      <option value="aktif">Aktif</option>
      <option value="nonaktif">Nonaktif</option>
    </select></div>
</div><?php modal_end();
      modal_start('dokumen', 'Dokumen SPMI', 'dokumen', 'multipart/form-data'); ?><div class="form-grid">
  <div class="field full"><label>Judul Dokumen</label><input name="judul" id="dokumen-judul" required></div>
  <div class="field"><label>Kategori</label><input name="kategori" id="dokumen-kategori" placeholder="Kebijakan SPMI">
  </div>
  <div class="field"><label>Tahun</label><input name="tahun" id="dokumen-tahun" placeholder="2026"></div>
  <div class="field full"><label>Deskripsi</label><textarea name="deskripsi" id="dokumen-deskripsi"></textarea></div>
  <div class="field full"><label>File Dokumen</label><input type="file" name="file_dokumen"
      accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
    <div class="hint">Maksimal 10 MB. Saat edit, kosongkan jika ingin mempertahankan file lama.</div>
  </div>
  <div class="field"><label>Urutan</label><input type="number" name="nomor_urut" id="dokumen-urut" value="0"></div>
  <div class="field"><label>Status</label><select name="status" id="dokumen-status">
      <option value="aktif">Aktif</option>
      <option value="nonaktif">Nonaktif</option>
    </select></div>
</div><?php modal_end();
      modal_start('kegiatan', 'Kegiatan SPMI', 'kegiatan', 'multipart/form-data'); ?><div class="form-grid">
  <div class="field full"><label>Judul Kegiatan</label><input name="judul" id="kegiatan-judul" required></div>
  <div class="field"><label>Tanggal</label><input type="date" name="tanggal" id="kegiatan-tanggal"></div>
  <div class="field"><label>Lokasi</label><input name="lokasi" id="kegiatan-lokasi"></div>
  <div class="field full"><label>Ringkasan</label><textarea name="ringkasan" id="kegiatan-ringkasan"></textarea></div>
  <div class="field full"><label>Isi</label><textarea name="isi" id="kegiatan-isi"></textarea></div>
  <div class="field full"><label>Gambar</label><input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp">
    <div class="hint">Maksimal 5 MB. Saat edit, kosongkan jika ingin mempertahankan gambar lama.</div>
  </div>
  <div class="field"><label>Status</label><select name="status" id="kegiatan-status">
      <option value="aktif">Aktif</option>
      <option value="nonaktif">Nonaktif</option>
    </select></div>
</div><?php modal_end(); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const csrf = <?= json_encode($csrf) ?>;

function openModal(id) {
  document.getElementById('modal-' + id)?.classList.add('show')
}

function closeAll() {
  document.querySelectorAll('.modal').forEach(m => m.classList.remove('show'))
}
document.querySelectorAll('[data-open]').forEach(b => b.addEventListener('click', () => {
  resetForm(b.dataset.open);
  openModal(b.dataset.open)
}));
document.querySelectorAll('.modal-close').forEach(b => b.addEventListener('click', closeAll));
document.querySelectorAll('.modal').forEach(m => m.addEventListener('click', e => {
  if (e.target === m) m.classList.remove('show')
}));

function resetForm(type) {
  const f = document.querySelector('#modal-' + type + ' form');
  if (!f) return;
  f.reset();
  const id = f.querySelector('input[name=id]');
  if (id) id.value = 0;
}

function fill(type, d) {
  resetForm(type);
  Object.keys(d).forEach(k => {
    const el = document.getElementById(type + '-' + k.replaceAll('_', '-'));
    if (el) el.value = d[k] ?? ''
  });
  document.querySelector('#modal-' + type + ' input[name=id]').value = d.id;
  openModal(type)
}
document.querySelectorAll('.edit-info').forEach(b => b.onclick = () => fill('info', JSON.parse(b.dataset.json)));
document.querySelectorAll('.edit-siklus').forEach(b => b.onclick = () => fill('siklus', JSON.parse(b.dataset.json)));
document.querySelectorAll('.edit-dokumen').forEach(b => b.onclick = () => fill('dokumen', JSON.parse(b.dataset.json)));
document.querySelectorAll('.edit-kegiatan').forEach(b => b.onclick = () => fill('kegiatan', JSON.parse(b.dataset
  .json)));
document.querySelectorAll('.del').forEach(b => b.onclick = async () => {
  const r = await Swal.fire({
    icon: 'warning',
    title: 'Hapus data?',
    text: 'Data yang dihapus tidak dapat dikembalikan.',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus',
    cancelButtonText: 'Batal',
    confirmButtonColor: '#b91c1c'
  });
  if (!r.isConfirmed) return;
  const f = document.createElement('form');
  f.method = 'post';
  f.innerHTML =
    `<input name="csrf" value="${csrf}"><input name="action" value="delete"><input name="type" value="${b.dataset.type}"><input name="id" value="${b.dataset.id}">`;
  document.body.appendChild(f);
  f.submit()
});
document.querySelectorAll('[data-search]').forEach(input => input.addEventListener('input', () => {
  const q = input.value.toLowerCase();
  const table = document.querySelector(`[data-table="${input.dataset.search}"]`);
  table.querySelectorAll('tbody tr').forEach(tr => tr.style.display = tr.innerText.toLowerCase().includes(q) ?
    '' : 'none')
}));
<?php if ($flash): ?>Swal.fire({
  icon: <?= json_encode($flash['type']) ?>,
  title: <?= json_encode($flash['type'] === 'success' ? 'Berhasil' : 'Gagal') ?>,
  text: <?= json_encode($flash['text']) ?>,
  timer: 1800,
  showConfirmButton: false
});
<?php endif; ?>
</script>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
