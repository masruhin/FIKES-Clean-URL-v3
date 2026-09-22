<?php
require_once __DIR__ . '/../../admin/config/database.php';
if (!function_exists('e')) { function e($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); } }

function spmi_date_id($date){
  if (!$date) return '-';
  $bulan=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
  $t=strtotime($date); return $t ? date('d',$t).' '.$bulan[(int)date('m',$t)-1].' '.date('Y',$t) : $date;
}
function spmi_table_exists(PDO $pdo, $table){
  $s=$pdo->prepare("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=?");
  $s->execute([$table]); return (bool)$s->fetchColumn();
}
$informasi=[];$siklus=[];$dokumen=[];$kegiatan=[];
try {
  if(spmi_table_exists($pdo,'spmi_informasi')) $informasi=$pdo->query("SELECT * FROM spmi_informasi WHERE status='aktif' ORDER BY nomor_urut ASC,id DESC")->fetchAll();
  if(spmi_table_exists($pdo,'spmi_siklus')) $siklus=$pdo->query("SELECT * FROM spmi_siklus WHERE status='aktif' ORDER BY nomor_urut ASC,id ASC")->fetchAll();
  if(spmi_table_exists($pdo,'spmi_dokumen')) $dokumen=$pdo->query("SELECT * FROM spmi_dokumen WHERE status='aktif' ORDER BY kategori ASC,nomor_urut ASC,id DESC")->fetchAll();
  if(spmi_table_exists($pdo,'spmi_kegiatan')) $kegiatan=$pdo->query("SELECT * FROM spmi_kegiatan WHERE status='aktif' ORDER BY tanggal DESC,id DESC")->fetchAll();
} catch(Throwable $e) { $spmi_error=$e->getMessage(); }
$jumlahTahap=count($siklus); $jumlahDokumen=count($dokumen); $jumlahKegiatan=count($kegiatan);
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>SPMI | FIKES</title>
<meta name="description" content="Sistem Penjaminan Mutu Internal FIKES.">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/fikes/assets/css/style.css">
<style>
.spmi-page{background:#f7faf8;color:#183b35}.spmi-hero{padding:80px 0 55px;background:linear-gradient(135deg,#083f36,#0b6d5b);color:#fff;position:relative;overflow:hidden}.spmi-hero:after{content:"";position:absolute;width:420px;height:420px;border-radius:50%;right:-140px;top:-160px;background:rgba(255,255,255,.08)}.spmi-hero .container{position:relative;z-index:1}.spmi-eyebrow{display:inline-flex;padding:8px 13px;border-radius:999px;background:rgba(255,255,255,.13);font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.spmi-hero h1{font:800 44px/1.12 'Plus Jakarta Sans',sans-serif;margin:18px 0 14px}.spmi-hero p{max-width:760px;color:#dceee9;line-height:1.8;margin:0}.spmi-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:28px}.spmi-stat{padding:20px;border:1px solid rgba(255,255,255,.15);border-radius:16px;background:rgba(255,255,255,.08);backdrop-filter:blur(6px)}.spmi-stat strong{display:block;font-size:28px}.spmi-stat span{font-size:12px;color:#d9ebe7}.spmi-section{padding:65px 0}.spmi-section.alt{background:#fff}.spmi-heading{text-align:center;max-width:760px;margin:0 auto 32px}.spmi-heading .tag{color:#0b806b;font-weight:800;font-size:12px;text-transform:uppercase;letter-spacing:.08em}.spmi-heading h2{font:800 30px/1.25 'Plus Jakarta Sans',sans-serif;margin:9px 0}.spmi-heading p{color:#6b7d78;line-height:1.75}.spmi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}.spmi-card{background:#fff;border:1px solid #e3ece8;border-radius:18px;padding:24px;box-shadow:0 10px 30px rgba(16,72,60,.05)}.spmi-card h3{margin:0 0 10px;font-size:18px;color:#16483f}.spmi-card p{margin:0;color:#667a75;line-height:1.75;font-size:14px}.spmi-number{width:42px;height:42px;border-radius:13px;display:grid;place-items:center;background:#e9f7f2;color:#0a806b;font-weight:800;margin-bottom:16px}.ppepp{display:grid;grid-template-columns:repeat(5,1fr);gap:14px}.ppepp-item{position:relative;padding:22px 18px;background:#fff;border:1px solid #e3ece8;border-radius:18px;text-align:center}.ppepp-item .circle{width:52px;height:52px;border-radius:50%;display:grid;place-items:center;margin:0 auto 13px;background:#0a806b;color:#fff;font-weight:800}.ppepp-item h3{font-size:16px;margin:0 0 7px}.ppepp-item p{font-size:13px;line-height:1.6;color:#6c7d79;margin:0}.doc-list{display:grid;gap:12px}.doc-item{display:flex;gap:16px;align-items:center;padding:18px;background:#fff;border:1px solid #e3ece8;border-radius:15px}.doc-icon{width:46px;height:46px;border-radius:12px;background:#eef8f5;color:#087762;display:grid;place-items:center;font-weight:800;flex:none}.doc-content{min-width:0;flex:1}.doc-content h3{font-size:15px;margin:0 0 5px}.doc-content p{font-size:13px;color:#71817d;margin:0;line-height:1.55}.doc-meta{font-size:11px;color:#8a9894;margin-top:5px}.doc-btn{display:inline-flex;padding:9px 13px;border-radius:9px;background:#087c68;color:#fff;text-decoration:none;font-size:12px;font-weight:700}.activity-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}.activity-card{background:#fff;border:1px solid #e3ece8;border-radius:18px;overflow:hidden}.activity-card img{width:100%;height:190px;object-fit:cover;display:block}.activity-body{padding:19px}.activity-date{font-size:11px;color:#087c68;font-weight:800;text-transform:uppercase}.activity-body h3{font-size:17px;margin:8px 0}.activity-body p{font-size:13px;color:#687a75;line-height:1.65;margin:0}.empty-spmi{padding:30px;text-align:center;color:#788782;background:#fff;border:1px dashed #cbdad5;border-radius:16px}.spmi-error{margin:20px auto;max-width:900px;padding:15px;border-radius:12px;background:#fff1f1;color:#a22;border:1px solid #f2cccc;font-size:13px}
@media(max-width:900px){.spmi-grid,.activity-grid{grid-template-columns:repeat(2,1fr)}.ppepp{grid-template-columns:repeat(2,1fr)}.spmi-hero h1{font-size:36px}}
@media(max-width:600px){.spmi-hero{padding:55px 0 40px}.spmi-hero h1{font-size:30px}.spmi-stats,.spmi-grid,.activity-grid,.ppepp{grid-template-columns:1fr}.spmi-section{padding:45px 0}.doc-item{align-items:flex-start;flex-wrap:wrap}.doc-btn{margin-left:62px}}
</style>
</head>
<body class="spmi-page">
<?php require_once __DIR__.'/../menu/topbar.php'; ?>
<?php require_once __DIR__.'/../menu/navbar.php'; ?>
<main>
<section class="spmi-hero"><div class="container">
<span class="spmi-eyebrow">Penjaminan Mutu Internal</span><h1>Sistem Penjaminan Mutu Internal (SPMI)</h1>
<p>SPMI FIKES merupakan sistem terstruktur untuk memastikan penyelenggaraan pendidikan, penelitian, pengabdian kepada masyarakat, dan tata kelola berjalan sesuai standar mutu serta terus mengalami peningkatan.</p>
<div class="spmi-stats"><div class="spmi-stat"><strong><?= $jumlahTahap ?></strong><span>Tahap siklus PPEPP</span></div><div class="spmi-stat"><strong><?= $jumlahDokumen ?></strong><span>Dokumen mutu aktif</span></div><div class="spmi-stat"><strong><?= $jumlahKegiatan ?></strong><span>Kegiatan mutu terdokumentasi</span></div></div>
</div></section>
<?php if(!empty($spmi_error)): ?><div class="container spmi-error">Data SPMI belum dapat dimuat. Silakan pastikan migration database SPMI sudah dijalankan.</div><?php endif; ?>
<section class="spmi-section"><div class="container"><div class="spmi-heading"><div class="tag">Informasi SPMI</div><h2>Penjaminan Mutu sebagai Budaya FIKES</h2><p>Informasi berikut dikelola melalui Dashboard Admin sehingga dapat diperbarui tanpa mengubah kode halaman.</p></div>
<div class="spmi-grid">
<?php if($informasi): foreach($informasi as $i): ?><article class="spmi-card"><div class="spmi-number">✓</div><h3><?=e($i['judul'])?></h3><p><?=nl2br(e($i['isi']))?></p></article><?php endforeach; else: ?><div class="empty-spmi" style="grid-column:1/-1">Belum ada informasi SPMI yang dipublikasikan.</div><?php endif; ?>
</div></div></section>
<section class="spmi-section alt"><div class="container"><div class="spmi-heading"><div class="tag">Siklus Mutu</div><h2>PPEPP</h2><p>Pelaksanaan penjaminan mutu dilakukan melalui siklus Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan.</p></div>
<div class="ppepp"><?php if($siklus): foreach($siklus as $idx=>$s): ?><article class="ppepp-item"><div class="circle"><?=e($s['kode'] ?: ($idx+1))?></div><h3><?=e($s['tahap'])?></h3><p><?=e($s['deskripsi'])?></p></article><?php endforeach; else: ?><div class="empty-spmi" style="grid-column:1/-1">Siklus PPEPP belum tersedia.</div><?php endif; ?></div></div></section>
<section class="spmi-section"><div class="container"><div class="spmi-heading"><div class="tag">Dokumen Mutu</div><h2>Dokumen SPMI</h2><p>Dokumen yang dipublikasikan oleh pengelola SPMI dapat diakses sesuai kategori dan statusnya.</p></div>
<div class="doc-list"><?php if($dokumen): foreach($dokumen as $d): ?><div class="doc-item"><div class="doc-icon">PDF</div><div class="doc-content"><h3><?=e($d['judul'])?></h3><p><?=e($d['deskripsi'])?></p><div class="doc-meta"><?=e($d['kategori'])?> · <?=e($d['tahun'])?></div></div><?php if(!empty($d['file_dokumen'])): ?><a class="doc-btn" target="_blank" href="/fikes/admin/uploads/spmi/<?=rawurlencode($d['file_dokumen'])?>">Lihat Dokumen</a><?php endif; ?></div><?php endforeach; else: ?><div class="empty-spmi">Belum ada dokumen SPMI yang dipublikasikan.</div><?php endif; ?></div></div></section>
<section class="spmi-section alt"><div class="container"><div class="spmi-heading"><div class="tag">Kegiatan</div><h2>Kegiatan Penjaminan Mutu</h2><p>Dokumentasi kegiatan mutu dan pelaksanaan SPMI di lingkungan FIKES.</p></div>
<div class="activity-grid"><?php if($kegiatan): foreach($kegiatan as $k): ?><article class="activity-card"><?php if($k['gambar']): ?><img src="/fikes/admin/uploads/spmi/<?=rawurlencode($k['gambar'])?>" alt="<?=e($k['judul'])?>"><?php endif; ?><div class="activity-body"><div class="activity-date"><?=e(spmi_date_id($k['tanggal']))?><?= $k['lokasi']?' · '.e($k['lokasi']):'' ?></div><h3><?=e($k['judul'])?></h3><p><?=nl2br(e($k['ringkasan'] ?: $k['isi']))?></p></div></article><?php endforeach; else: ?><div class="empty-spmi" style="grid-column:1/-1">Belum ada kegiatan SPMI yang dipublikasikan.</div><?php endif; ?></div></div></section>
</main>
<?php require_once __DIR__.'/../menu/footer.php'; ?>
</body></html>
