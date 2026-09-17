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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_org') {
    $id = (int)($_POST['id'] ?? 0);
    try {
        $st = $pdo->prepare('SELECT slug,logo FROM kemahasiswaan_organisasi WHERE id=? AND jenis=?');
        $st->execute([$id, $jenis]);
        $old = $st->fetch();
        if (!$old) throw new RuntimeException('Data organisasi tidak ditemukan.');
        $pdo->beginTransaction();
        foreach (['kemahasiswaan_pengurus','kemahasiswaan_anggota','kemahasiswaan_kegiatan','kemahasiswaan_galeri'] as $t) {
            $pdo->prepare("DELETE FROM $t WHERE organisasi_slug=?")->execute([$old['slug']]);
        }
        $pdo->prepare('DELETE FROM kemahasiswaan_organisasi WHERE id=? AND jenis=?')->execute([$id,$jenis]);
        $pdo->commit();
        if (!empty($old['logo']) && is_file($uploadDir . basename($old['logo']))) @unlink($uploadDir . basename($old['logo']));
        header('Location:' . $baseUrl . '&ok=' . urlencode('Organisasi dan seluruh detailnya berhasil dihapus.')); exit;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        header('Location:' . $baseUrl . '&err=' . urlencode($e->getMessage())); exit;
    }
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
            /*
             * Saat EDIT, logo lama selalu diambil dari database.
             * Hidden input old_logo hanya untuk kebutuhan tampilan, bukan
             * sebagai sumber data utama. Jika user tidak memilih file baru,
             * logo lama wajib dipertahankan.
             */
            $oldLogo = '';
            if ($id) {
                $cekLogo = $pdo->prepare('SELECT logo FROM kemahasiswaan_organisasi WHERE id=? AND jenis=? LIMIT 1');
                $cekLogo->execute([$id, $jenis]);
                $rowLogo = $cekLogo->fetch(PDO::FETCH_ASSOC);
                if (!$rowLogo) throw new RuntimeException('Data organisasi tidak ditemukan.');
                $oldLogo = trim((string)($rowLogo['logo'] ?? ''));
            } else {
                $oldLogo = trim($_POST['old_logo'] ?? '');
            }

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

$data=$pdo->prepare('SELECT * FROM kemahasiswaan_organisasi WHERE jenis=? ORDER BY nomor_urut ASC,id DESC'); $data->execute([$jenis]); $data=$data->fetchAll();

include __DIR__.'/../../includes/header.php';
?>
<style>
.km-wrap{padding:24px;max-width:none}.km-tabs{display:flex;gap:8px;margin-bottom:18px}.km-tabs a{padding:10px 15px;border-radius:10px;text-decoration:none;border:1px solid #dce7e3;color:#36534a;background:#fff;font-weight:700;font-size:13px}.km-tabs a.active{background:#087f5b;color:#fff;border-color:#087f5b}.km-card{background:#fff;border:1px solid #e3ebe8;border-radius:16px;padding:20px;box-shadow:0 8px 25px rgba(18,55,42,.05)}.km-table-wrap{overflow:auto}.km-table{width:100%;border-collapse:collapse}.km-table th,.km-table td{padding:11px 10px;border-bottom:1px solid #edf2f0;text-align:left;font-size:12px;vertical-align:middle}.km-table th{color:#567169;background:#f8fbfa}.km-logo{width:48px;height:48px;border-radius:10px;object-fit:contain;background:#f4f8f6;border:1px solid #e1ebe7}.km-mini{font-size:11px;color:#70817b}.km-title{font-size:15px;color:#173b36;font-weight:800;margin:0 0 4px}.km-actions{display:flex;gap:7px;flex-wrap:wrap}.km-btn{display:inline-flex;align-items:center;justify-content:center;padding:9px 13px;border-radius:9px;border:0;text-decoration:none;font-weight:700;font-size:12px;cursor:pointer;background:#eef4f1;color:#26463b}.km-btn.primary{background:#087f5b;color:#fff}.km-btn.danger{background:#fff0f0;color:#b42318}.km-status{display:inline-block;padding:5px 9px;border-radius:999px;font-size:11px;font-weight:800}.km-status.aktif{background:#e9f8f1;color:#087f5b}.km-status.nonaktif{background:#f2f4f3;color:#66756f}
.km-dt-toolbar{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:14px;flex-wrap:wrap}.km-search-wrap{position:relative;flex:1;min-width:260px}.km-search{width:100%;box-sizing:border-box;padding:11px 13px 11px 38px;border:1px solid #dce7e3;border-radius:10px;font:inherit;font-size:12px;outline:none;background:#fff;transition:.2s}.km-search:focus{border-color:#087f5b;box-shadow:0 0 0 3px rgba(8,127,91,.09)}.km-search-icon{position:absolute;left:13px;top:50%;transform:translateY(-52%);font-size:19px;color:#71827c;pointer-events:none}.km-page-size{display:flex;align-items:center;gap:7px;color:#64766f;font-size:12px;white-space:nowrap}.km-page-size select{border:1px solid #dce7e3;border-radius:9px;padding:9px 25px 9px 10px;background:#fff;color:#36534a;font-weight:700}.km-dt-footer{display:flex;align-items:center;justify-content:space-between;gap:15px;margin-top:15px;padding-top:14px;border-top:1px solid #edf2f0;flex-wrap:wrap}.km-table-info{font-size:12px;color:#71827c}.km-pagination{display:flex;gap:5px;align-items:center;flex-wrap:wrap}.km-page-btn{min-width:34px;height:34px;padding:0 9px;border:1px solid #dce7e3;background:#fff;color:#36534a;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer}.km-page-btn:hover:not(:disabled){border-color:#087f5b;color:#087f5b}.km-page-btn.active{background:#087f5b;border-color:#087f5b;color:#fff}.km-page-btn:disabled{opacity:.45;cursor:not-allowed}.km-page-dots{padding:0 3px;color:#71827c;font-size:12px}

.km-modal{position:fixed;inset:0;background:rgba(10,28,23,.58);display:none;align-items:center;justify-content:center;padding:18px;z-index:9999}.km-modal.show{display:flex}.km-modal-box{width:min(980px,100%);max-height:92vh;overflow:auto;background:#fff;border-radius:18px;box-shadow:0 25px 80px rgba(0,0,0,.25);animation:kmIn .18s ease}.km-modal.small .km-modal-box{width:min(480px,100%)}@keyframes kmIn{from{opacity:0;transform:translateY(12px) scale(.98)}to{opacity:1;transform:none}}.km-modal-head{display:flex;align-items:center;justify-content:space-between;gap:15px;padding:18px 22px;border-bottom:1px solid #edf2f0;position:sticky;top:0;background:#fff;z-index:2}.km-modal-head h2{margin:0;color:#173b36;font-size:18px}.km-close{width:34px;height:34px;border:0;border-radius:9px;background:#f1f5f3;color:#4d625b;font-size:21px;cursor:pointer}.km-modal-body{padding:22px;grid-column:1/-1}.km-modal-foot{display:flex;justify-content:flex-end;gap:8px;padding:15px 22px;border-top:1px solid #edf2f0;position:sticky;bottom:0;background:#fff}.km-form{display:grid;grid-template-columns:1fr 1fr;gap:14px}.km-form .full{grid-column:1/-1}.km-form label{display:block;font-size:12px;font-weight:700;color:#38564d;margin-bottom:6px}.km-form input,.km-form select,.km-form textarea{width:100%;box-sizing:border-box;border:1px solid #dce7e3;border-radius:10px;padding:10px 12px;font:inherit;background:#fff}.km-form textarea{min-height:90px;resize:vertical}.km-help{padding:12px;border-radius:10px;background:#f3faf7;color:#4e6960;font-size:12px;line-height:1.7;margin-bottom:15px}.km-current-logo{display:flex;align-items:center;gap:10px;margin-top:8px;font-size:11px;color:#70817b}.km-current-logo img{width:45px;height:45px;object-fit:contain;border:1px solid #e1ebe7;border-radius:8px}.km-delete-icon{font-size:28px;width:58px;height:58px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#fff0f0;color:#b42318;margin:0 auto 12px}.km-delete-text{text-align:center;color:#526760;font-size:13px;line-height:1.6}.km-delete-name{text-align:center;font-size:17px;font-weight:800;color:#173b36;margin:5px 0 8px}.km-empty{text-align:center;padding:30px;color:#70817b}@media(max-width:650px){.km-wrap{padding:14px}.km-form{grid-template-columns:1fr}.km-form .full{grid-column:auto}.km-modal-body{padding:16px}.km-modal-head{padding:15px 16px}.km-modal-foot{padding:12px 16px}}
</style>
<div class="km-wrap">
  <div class="page-head">
    <div><span class="eyebrow">KEMAHASISWAAN</span><h1><?=e($labelJenis)?></h1><p>Kelola profil organisasi dan seluruh informasi yang ditampilkan pada halaman frontend.</p></div>
    <button type="button" class="btn primary" onclick="openOrgModal('add')">+ Organisasi Baru</button>
  </div>
  <div class="km-tabs"><a class="<?= $jenis==='himpunan'?'active':'' ?>" href="index.php?jenis=himpunan">Himpunan Mahasiswa</a><a class="<?= $jenis==='ukm'?'active':'' ?>" href="index.php?jenis=ukm">UKM Kemahasiswaan</a></div>
  <div class="km-card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:15px"><div><h2 style="margin:0;color:#173b36">Daftar <?=e($labelJenis)?></h2><div class="km-mini">Data terhubung langsung dengan frontend.</div></div><button type="button" class="km-btn primary" onclick="openOrgModal('add')">+ Tambah Data</button></div>
    <div class="km-dt-toolbar">
      <div class="km-search-wrap"><span class="km-search-icon">⌕</span><input type="search" id="kmSearch" class="km-search" placeholder="Cari nama, kategori, ketua, atau status..."></div>
      <div class="km-page-size"><label for="kmPageSize">Tampilkan</label><select id="kmPageSize"><option value="5">5</option><option value="10" selected>10</option><option value="25">25</option><option value="50">50</option></select><span>data</span></div>
    </div>
    <div class="km-table-wrap"><table class="km-table" id="kmDataTable"><thead><tr><th>Logo</th><th>Organisasi</th><th>Kategori</th><th>Ketua</th><th>Status</th><th style="min-width:230px">Aksi</th></tr></thead><tbody>
    <?php if(!$data):?><tr><td colspan="6" class="km-empty">Belum ada data <?=e(strtolower($labelJenis))?>.</td></tr><?php else: foreach($data as $d):
      $json = htmlspecialchars(json_encode($d, JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8');
    ?><tr>
      <td><?php if($d['logo']):?><img class="km-logo" src="../../uploads/kemahasiswaan/logo/<?=rawurlencode(basename($d['logo']))?>" alt="<?=e($d['nama'])?>"><?php else:?><div class="km-logo"></div><?php endif;?></td>
      <td><div class="km-title"><?=e($d['nama'])?></div><div class="km-mini">/<?=$jenis?>/<?=e($d['slug'])?></div></td>
      <td><?=e($d['kategori']?:'-')?></td><td><?=e($d['ketua_nama']?:'-')?></td>
      <td><span class="km-status <?=e($d['status'])?>"><?=e(ucfirst($d['status']))?></span></td>
      <td><div class="km-actions"><button type="button" class="km-btn" data-org='<?=$json?>' onclick="openOrgModal('edit', this)">Edit</button><a class="km-btn primary" href="detail.php?jenis=<?=$jenis?>&slug=<?=rawurlencode($d['slug'])?>">Kelola Detail</a><button type="button" class="km-btn danger" data-id="<?=e($d['id'])?>" data-name="<?=e($d['nama'])?>" onclick="openDeleteModal(this)">Hapus</button></div></td>
    </tr><?php endforeach; endif;?></tbody></table></div>
    <div class="km-dt-footer">
      <div id="kmTableInfo" class="km-table-info"></div>
      <div id="kmPagination" class="km-pagination"></div>
    </div>
  </div>
</div>

<!-- Modal Tambah/Edit -->
<div class="km-modal" id="orgModal" aria-hidden="true">
  <div class="km-modal-box">
    <div class="km-modal-head"><h2 id="orgModalTitle">Tambah Organisasi</h2><button type="button" class="km-close" onclick="closeModal('orgModal')">&times;</button></div>
    <form class="km-form" id="orgForm" method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="save_org"><input type="hidden" name="id" id="f_id" value="0"><input type="hidden" name="old_logo" id="f_old_logo" value="">
      <div class="km-modal-body"><div class="km-form">
        <div><label>Nama Organisasi *</label><input id="f_nama" name="nama" required></div><div><label>Slug URL *</label><input id="f_slug" name="slug" required placeholder="contoh: himafarda"></div>
        <div><label>Kategori</label><input id="f_kategori" name="kategori" placeholder="Olahraga, Seni, Sosial..."></div><div><label>Urutan</label><input id="f_nomor_urut" type="number" min="1" name="nomor_urut" value="1"></div>
        <div class="full"><label>Deskripsi</label><textarea id="f_deskripsi" name="deskripsi"></textarea></div><div class="full"><label>Fokus / Bidang Kegiatan</label><textarea id="f_fokus" name="fokus"></textarea></div>
        <div><label>Visi</label><textarea id="f_visi" name="visi"></textarea></div><div><label>Misi</label><textarea id="f_misi" name="misi"></textarea></div>
        <div><label>Ketua Saat Ini</label><input id="f_ketua_nama" name="ketua_nama"></div><div><label>Sekretariat</label><input id="f_sekretariat" name="sekretariat"></div>
        <div><label>Email</label><input id="f_email" type="email" name="email"></div><div><label>Telepon</label><input id="f_telepon" name="telepon"></div>
        <div><label>Instagram</label><input id="f_instagram" name="instagram"></div><div><label>Facebook</label><input id="f_facebook" name="facebook"></div>
        <div><label>YouTube</label><input id="f_youtube" name="youtube"></div><div><label>Status</label><select id="f_status" name="status"><option value="aktif">Aktif</option><option value="nonaktif">Nonaktif</option></select></div>
        <div class="full"><label>Logo (JPG, PNG, WEBP, SVG — maks. 3 MB)</label><input id="f_logo" type="file" name="logo" accept=".jpg,.jpeg,.png,.webp,.svg"><div class="km-current-logo" id="currentLogo" style="display:none"></div></div>
      </div></div>
      <div class="km-modal-foot"><button type="button" class="km-btn" onclick="closeModal('orgModal')">Batal</button><button class="km-btn primary" id="orgSubmit" type="submit">Simpan</button></div>
    </form>
  </div>
</div>

<!-- Modal Hapus -->
<div class="km-modal small" id="deleteModal" aria-hidden="true">
  <div class="km-modal-box">
    <div class="km-modal-head"><h2>Konfirmasi Hapus</h2><button type="button" class="km-close" onclick="closeModal('deleteModal')">&times;</button></div>
    <div class="km-modal-body"><div class="km-delete-icon">!</div><div class="km-delete-name" id="deleteName"></div><div class="km-delete-text">Data organisasi beserta <b>pengurus, anggota, kegiatan, dan galeri</b> yang terhubung akan ikut dihapus. Tindakan ini tidak dapat dibatalkan.</div></div>
    <form method="post"><input type="hidden" name="action" value="delete_org"><input type="hidden" name="id" id="deleteId"><div class="km-modal-foot"><button type="button" class="km-btn" onclick="closeModal('deleteModal')">Batal</button><button class="km-btn danger" type="submit">Ya, Hapus</button></div></form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function(){
  const fields=['id','nama','slug','kategori','nomor_urut','deskripsi','fokus','visi','misi','ketua_nama','sekretariat','email','telepon','instagram','facebook','youtube','status','old_logo'];

  // DataTable: pencarian + pagination tanpa reload halaman
  const table=document.getElementById('kmDataTable');
  if(table){
    const tbody=table.querySelector('tbody');
    const allRows=Array.from(tbody.querySelectorAll('tr')).filter(r=>!r.querySelector('.km-empty'));
    const search=document.getElementById('kmSearch');
    const pageSize=document.getElementById('kmPageSize');
    const pagination=document.getElementById('kmPagination');
    const info=document.getElementById('kmTableInfo');
    let currentPage=1;
    function filteredRows(){
      const q=(search.value||'').trim().toLowerCase();
      if(!q)return allRows;
      return allRows.filter(r=>r.textContent.toLowerCase().includes(q));
    }
    function render(){
      const rows=filteredRows(), size=parseInt(pageSize.value,10), total=rows.length;
      const pages=Math.max(1,Math.ceil(total/size));
      if(currentPage>pages)currentPage=pages;
      allRows.forEach(r=>r.style.display='none');
      rows.slice((currentPage-1)*size,currentPage*size).forEach(r=>r.style.display='');
      if(total===0){
        let empty=tbody.querySelector('.km-dt-empty');
        if(!empty){empty=document.createElement('tr');empty.className='km-dt-empty';empty.innerHTML='<td colspan="6" class="km-empty">Data yang dicari tidak ditemukan.</td>';tbody.appendChild(empty)}
        empty.style.display='';
      }else{const empty=tbody.querySelector('.km-dt-empty');if(empty)empty.style.display='none';}
      const start=total?((currentPage-1)*size)+1:0, end=Math.min(currentPage*size,total);
      info.textContent=`Menampilkan ${start}–${end} dari ${total} data`;
      pagination.innerHTML='';
      const prev=document.createElement('button');prev.className='km-page-btn';prev.type='button';prev.textContent='‹';prev.title='Sebelumnya';prev.disabled=currentPage===1;prev.onclick=()=>{currentPage--;render()};pagination.appendChild(prev);
      const nums=[];for(let i=1;i<=pages;i++){if(pages<=7||i===1||i===pages||Math.abs(i-currentPage)<=1)nums.push(i);else if(nums[nums.length-1]!=='dots')nums.push('dots')}
      nums.forEach(n=>{if(n==='dots'){const d=document.createElement('span');d.className='km-page-dots';d.textContent='…';pagination.appendChild(d);return}const b=document.createElement('button');b.type='button';b.className='km-page-btn'+(n===currentPage?' active':'');b.textContent=n;b.onclick=()=>{currentPage=n;render()};pagination.appendChild(b)});
      const next=document.createElement('button');next.className='km-page-btn';next.type='button';next.textContent='›';next.title='Berikutnya';next.disabled=currentPage===pages;next.onclick=()=>{currentPage++;render()};pagination.appendChild(next);
    }
    search.addEventListener('input',()=>{currentPage=1;render()});
    pageSize.addEventListener('change',()=>{currentPage=1;render()});
    render();
  }
  window.openOrgModal=function(mode,btn){
    document.getElementById('orgModal').classList.add('show'); document.getElementById('orgModal').setAttribute('aria-hidden','false');
    const d=mode==='edit' ? JSON.parse(btn.getAttribute('data-org')) : {};
    fields.forEach(k=>{const el=document.getElementById('f_'+k); if(el) el.value=d[k]??(k==='id'?'0':k==='nomor_urut'?'1':k==='status'?'aktif':'');});
    document.getElementById('f_logo').value='';
    document.getElementById('orgModalTitle').textContent=mode==='edit'?'Edit Organisasi':'Tambah Organisasi';
    document.getElementById('orgSubmit').textContent=mode==='edit'?'Simpan Perubahan':'Simpan';
    const cl=document.getElementById('currentLogo');
    if(mode==='edit' && d.logo){cl.style.display='flex'; cl.innerHTML='<img src="../../uploads/kemahasiswaan/logo/'+encodeURIComponent(String(d.logo).split('/').pop())+'" alt="Logo"> <span>Logo saat ini</span>';}else{cl.style.display='none';cl.innerHTML='';}
    setTimeout(()=>document.getElementById('f_nama').focus(),50);
  };
  window.openDeleteModal=function(btn){document.getElementById('deleteId').value=btn.dataset.id;document.getElementById('deleteName').textContent=btn.dataset.name;document.getElementById('deleteModal').classList.add('show');document.getElementById('deleteModal').setAttribute('aria-hidden','false');};
  window.closeModal=function(id){const el=document.getElementById(id);el.classList.remove('show');el.setAttribute('aria-hidden','true');};
  document.querySelectorAll('.km-modal').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)closeModal(m.id);}));
  document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeModal('orgModal');closeModal('deleteModal');}});
})();
<?php if(isset($_GET['ok'])):?>
Swal.fire({icon:'success',title:'Berhasil',text:<?=json_encode($_GET['ok'])?>,timer:1800,showConfirmButton:false});
<?php endif;?>
<?php if(isset($_GET['err'])):?>
Swal.fire({icon:'error',title:'Gagal',text:<?=json_encode($_GET['err'])?>});
<?php endif;?>
</script>
<?php require __DIR__.'/../../includes/footer.php'; ?>
