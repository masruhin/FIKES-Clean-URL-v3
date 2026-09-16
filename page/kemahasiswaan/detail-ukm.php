<?php
$page_title = 'Detail UKM';

if (!function_exists('e')) {
  function e($value)
  {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
  }
}

if (!isset($pdo) || !($pdo instanceof PDO)) {
  require_once __DIR__ . '/../../admin/config/database.php';
}

$orgs = [
  'karate' => ['nama' => 'Karate', 'kategori' => 'Olahraga & Bela Diri', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/karate.png', 'fokus' => 'Pengembangan bela diri, disiplin, kebugaran, karakter, dan prestasi mahasiswa.'],
  'basket' => ['nama' => 'Basket', 'kategori' => 'Olahraga', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/basket.png', 'fokus' => 'Pengembangan kemampuan bola basket, sportivitas, kebugaran, dan kerja sama tim.'],
  'futsal' => ['nama' => 'Futsal', 'kategori' => 'Olahraga', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/futsal.png', 'fokus' => 'Pengembangan teknik futsal, kekompakan, kebugaran, dan pengalaman kompetisi.'],
  'badminton' => ['nama' => 'Badminton', 'kategori' => 'Olahraga', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/badminton.png', 'fokus' => 'Pengembangan keterampilan badminton, kebugaran, sportivitas, dan prestasi mahasiswa.'],
  'voli' => ['nama' => 'Voli', 'kategori' => 'Olahraga', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/voli.jpg', 'fokus' => 'Pengembangan kemampuan bola voli, kekompakan tim, kebugaran, dan prestasi.'],
  'silat' => ['nama' => 'Silat', 'kategori' => 'Olahraga & Bela Diri', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/silat.jpeg', 'fokus' => 'Pengembangan seni bela diri, disiplin, karakter, kebugaran, dan prestasi.'],
  'bmb' => ['nama' => 'BMB', 'kategori' => 'Minat & Bakat', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/bmb.png', 'fokus' => 'Pengembangan minat, bakat, kreativitas, dan pengalaman mahasiswa.'],
  'pik' => ['nama' => 'PIK', 'kategori' => 'Pengembangan Mahasiswa', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/pik.jpg', 'fokus' => 'Pengembangan edukasi, komunikasi, kreativitas, dan kepedulian mahasiswa.'],
  'bhapala' => ['nama' => 'BHAPALA', 'kategori' => 'Kepencintaalaman', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/bhapala.jpg', 'fokus' => 'Pengembangan kegiatan alam bebas, kepedulian lingkungan, kebersamaan, dan ketangguhan mahasiswa.'],
  'sentramada' => ['nama' => 'Sentramada', 'kategori' => 'Seni & Kreativitas', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/sentramada.png', 'fokus' => 'Pengembangan seni, kreativitas, ekspresi, dan kolaborasi mahasiswa.'],
  'voice' => ['nama' => 'Voice', 'kategori' => 'Seni & Musik', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/voice.jpeg', 'fokus' => 'Pengembangan vokal, musik, penampilan, kepercayaan diri, dan kreativitas seni.'],
  'pramuka' => ['nama' => 'Pramuka', 'kategori' => 'Kepanduan', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/pramuka.jpg', 'fokus' => 'Pengembangan kepemimpinan, kedisiplinan, kemandirian, dan kegiatan sosial.'],
  'bakti' => ['nama' => 'Bakti', 'kategori' => 'Sosial & Pengabdian', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/bhakti.jpg', 'fokus' => 'Pengembangan kepedulian sosial, pengabdian, dan kegiatan kemasyarakatan.'],
  'jurnalika' => ['nama' => 'Jurnalika', 'kategori' => 'Media & Jurnalistik', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/jurnalika.jpg', 'fokus' => 'Pengembangan jurnalistik, penulisan, dokumentasi, media, dan komunikasi.'],
  'ksr' => ['nama' => 'KSR', 'kategori' => 'Kemanusiaan', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/ksr.jpg', 'fokus' => 'Pengembangan kepedulian kemanusiaan, kesiapsiagaan, dan kegiatan sosial.'],
  'bec' => ['nama' => 'BEC', 'kategori' => 'Bahasa & Komunikasi', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/bec.png', 'fokus' => 'Pengembangan kemampuan bahasa, komunikasi, kepercayaan diri, dan kreativitas.'],
];

$slug = trim((string)($_GET['slug'] ?? ''));
if (!isset($orgs[$slug])) {
  http_response_code(404);
  $org = ['nama' => 'UKM Tidak Ditemukan', 'kategori' => 'Kemahasiswaan', 'logo' => '', 'fokus' => 'Data UKM tidak ditemukan.'];
} else {
  $org = $orgs[$slug];
}

function ukm_table_exists(PDO $pdo, string $table): bool
{
  try {
    $st = $pdo->query('SHOW TABLES LIKE ' . $pdo->quote($table));
    return (bool)$st->fetchColumn();
  } catch (Throwable $e) {
    return false;
  }
}

$pengurus = [];
$anggota = [];
$anggota_count = null;
$kegiatan = [];
$galeri = [];
if (isset($orgs[$slug])) {
  try {
    if (ukm_table_exists($pdo, 'kemahasiswaan_pengurus')) {
      $st = $pdo->prepare('SELECT nama,jabatan,foto FROM kemahasiswaan_pengurus WHERE organisasi_slug=? AND status="aktif" ORDER BY nomor_urut,id');
      $st->execute([$slug]);
      $pengurus = $st->fetchAll(PDO::FETCH_ASSOC);
    }
    if (ukm_table_exists($pdo, 'kemahasiswaan_anggota')) {
      $st = $pdo->prepare('SELECT nama,prodi,angkatan,foto FROM kemahasiswaan_anggota WHERE organisasi_slug=? AND status="aktif" ORDER BY nama');
      $st->execute([$slug]);
      $anggota = $st->fetchAll(PDO::FETCH_ASSOC);
      $anggota_count = count($anggota);
    }
    if (ukm_table_exists($pdo, 'kemahasiswaan_kegiatan')) {
      $st = $pdo->prepare('SELECT judul,tanggal_kegiatan,deskripsi,foto FROM kemahasiswaan_kegiatan WHERE organisasi_slug=? AND status="publish" ORDER BY tanggal_kegiatan DESC,nomor_urut,id');
      $st->execute([$slug]);
      $kegiatan = $st->fetchAll(PDO::FETCH_ASSOC);
    }
    if (ukm_table_exists($pdo, 'kemahasiswaan_galeri')) {
      $st = $pdo->prepare('SELECT judul,foto,keterangan FROM kemahasiswaan_galeri WHERE organisasi_slug=? AND status="publish" ORDER BY nomor_urut,id DESC');
      $st->execute([$slug]);
      $galeri = $st->fetchAll(PDO::FETCH_ASSOC);
    }
  } catch (Throwable $e) {
  }
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= e($org['nama']) ?> | FIKES</title>
  <meta name="description" content="Informasi lengkap <?= e($org['nama']) ?> sebagai Unit Kegiatan Mahasiswa FIKES.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="/fikes/assets/css/style.css">
  <style>
    :root {
      --ud-green: #087f5b;
      --ud-dark: #12372a;
      --ud-text: #60726c;
      --ud-border: #e0ebe6;
      --ud-soft: #eef8f4;
      --ud-gold: #f4b942
    }

    .ud-hero {
      position: relative;
      overflow: hidden;
      padding: 55px 0 70px;
      color: #fff;
      background: radial-gradient(circle at 92% 15%, rgba(255, 255, 255, .18), transparent 28%), linear-gradient(120deg, #00685a, #008f78 58%, #79c7b8 150%)
    }

    .ud-hero:after {
      content: "";
      position: absolute;
      width: 400px;
      height: 400px;
      right: -220px;
      bottom: -300px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .08)
    }

    .ud-bread {
      position: relative;
      z-index: 2;
      display: flex;
      gap: 9px;
      flex-wrap: wrap;
      font-size: 13px;
      color: rgba(255, 255, 255, .82);
      margin-bottom: 28px
    }

    .ud-bread a {
      color: #fff;
      text-decoration: none;
      font-weight: 700
    }

    .ud-layout {
      position: relative;
      z-index: 2;
      display: grid;
      grid-template-columns: 260px 1fr;
      gap: 40px;
      align-items: center
    }

    .ud-logo {
      height: 260px;
      padding: 28px;
      border-radius: 28px;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 22px 55px rgba(0, 0, 0, .16)
    }

    .ud-logo img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain
    }

    .ud-kicker {
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 1.7px;
      text-transform: uppercase;
      color: #ddf7ef
    }

    .ud-hero h1 {
      font-family: "Plus Jakarta Sans", sans-serif;
      color: #fff;
      font-size: clamp(34px, 5vw, 58px);
      line-height: 1.08;
      margin: 10px 0 13px
    }

    .ud-hero p {
      max-width: 760px;
      color: rgba(255, 255, 255, .9);
      line-height: 1.85;
      margin: 0;
      font-size: 15px
    }

    .ud-back {
      display: inline-flex;
      margin-top: 22px;
      padding: 11px 15px;
      border: 1px solid rgba(255, 255, 255, .22);
      border-radius: 11px;
      color: #fff;
      text-decoration: none;
      font-size: 12px;
      font-weight: 800;
      background: rgba(255, 255, 255, .08)
    }

    .ud-main {
      padding: 72px 0 90px
    }

    .ud-stats {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-top: -2px
    }

    .ud-stat {
      padding: 22px;
      border: 1px solid var(--ud-border);
      border-radius: 18px;
      background: #fff;
      box-shadow: 0 12px 32px rgba(18, 55, 42, .06)
    }

    .ud-stat strong {
      display: block;
      color: var(--ud-green);
      font-family: "Plus Jakarta Sans", sans-serif;
      font-size: 28px
    }

    .ud-stat span {
      font-size: 11px;
      color: #71817c
    }

    .ud-section {
      margin-top: 48px
    }

    .ud-section-head {
      margin-bottom: 20px
    }

    .ud-label {
      display: block;
      color: var(--ud-green);
      font-size: 10px;
      font-weight: 800;
      letter-spacing: 1.6px;
      text-transform: uppercase;
      margin-bottom: 7px
    }

    .ud-section h2 {
      font-family: "Plus Jakarta Sans", sans-serif;
      color: var(--ud-dark);
      font-size: 28px;
      margin: 0 0 8px
    }

    .ud-section-intro {
      margin: 0;
      color: var(--ud-text);
      font-size: 14px;
      line-height: 1.8;
      max-width: 820px
    }

    .ud-about {
      display: grid;
      grid-template-columns: 1.2fr .8fr;
      gap: 20px
    }

    .ud-panel {
      padding: 26px;
      border: 1px solid var(--ud-border);
      border-radius: 20px;
      background: #fff;
      box-shadow: 0 10px 28px rgba(18, 55, 42, .045)
    }

    .ud-panel.soft {
      background: linear-gradient(145deg, #f8fbfa, #eef8f4)
    }

    .ud-panel h3 {
      font-family: "Plus Jakarta Sans", sans-serif;
      color: var(--ud-dark);
      font-size: 18px;
      margin: 0 0 10px
    }

    .ud-panel p {
      margin: 0;
      color: var(--ud-text);
      font-size: 13px;
      line-height: 1.8
    }

    .ud-facts {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px
    }

    .ud-fact {
      padding: 15px;
      border: 1px solid var(--ud-border);
      border-radius: 14px;
      background: #fff
    }

    .ud-fact strong {
      display: block;
      color: var(--ud-dark);
      font-size: 12px;
      margin-bottom: 4px
    }

    .ud-fact span {
      font-size: 11px;
      color: #74847f;
      line-height: 1.5
    }

    .ud-leader-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px
    }

    .ud-leader {
      border: 1px solid var(--ud-border);
      border-radius: 18px;
      overflow: hidden;
      background: #fff;
      box-shadow: 0 10px 26px rgba(18, 55, 42, .05)
    }

    .ud-leader-photo {
      height: 185px;
      background: linear-gradient(145deg, #f7fbf9, #eaf6f1);
      display: flex;
      align-items: center;
      justify-content: center
    }

    .ud-leader-photo img {
      width: 100%;
      height: 100%;
      object-fit: cover
    }

    .ud-leader-photo.no-photo:before {
      content: "👤";
      font-size: 42px;
      opacity: .45
    }

    .ud-leader-body {
      padding: 17px
    }

    .ud-leader-body small {
      display: block;
      color: var(--ud-green);
      font-size: 10px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .7px;
      margin-bottom: 5px
    }

    .ud-leader-body strong {
      display: block;
      color: var(--ud-dark);
      font-size: 14px
    }

    .ud-leader-body span {
      font-size: 11px;
      color: #71817c
    }

    .ud-table-wrap {
      overflow: auto;
      border: 1px solid var(--ud-border);
      border-radius: 18px;
      background: #fff
    }

    .ud-table {
      width: 100%;
      min-width: 620px;
      border-collapse: collapse
    }

    .ud-table th,
    .ud-table td {
      padding: 14px 16px;
      text-align: left;
      border-bottom: 1px solid #eaf0ed
    }

    .ud-table th {
      background: #f5faf8;
      color: var(--ud-green);
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: .6px
    }

    .ud-table td {
      color: #53655f;
      font-size: 12px
    }

    .ud-member {
      display: flex;
      align-items: center;
      gap: 10px
    }

    .ud-member img {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      object-fit: cover;
      background: #edf6f2
    }

    .ud-member .avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: grid;
      place-items: center;
      background: #e7f5ef;
      font-size: 16px
    }

    .ud-cards {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px
    }

    .ud-card {
      overflow: hidden;
      border: 1px solid var(--ud-border);
      border-radius: 18px;
      background: #fff;
      box-shadow: 0 10px 25px rgba(18, 55, 42, .05)
    }

    .ud-card-image {
      height: 195px;
      background: #edf6f2
    }

    .ud-card-image img {
      width: 100%;
      height: 100%;
      object-fit: cover
    }

    .ud-card-body {
      padding: 18px
    }

    .ud-card-body h3 {
      font-family: "Plus Jakarta Sans", sans-serif;
      color: var(--ud-dark);
      font-size: 16px;
      margin: 0 0 7px
    }

    .ud-date {
      display: inline-block;
      color: var(--ud-green);
      font-size: 10px;
      font-weight: 800;
      margin-bottom: 7px
    }

    .ud-card-body p {
      margin: 0;
      color: var(--ud-text);
      font-size: 12px;
      line-height: 1.7
    }

    .ud-gallery {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 14px
    }

    .ud-gallery figure {
      margin: 0;
      overflow: hidden;
      border: 1px solid var(--ud-border);
      border-radius: 16px;
      background: #fff
    }

    .ud-gallery img {
      display: block;
      width: 100%;
      height: 180px;
      object-fit: cover
    }

    .ud-gallery figcaption {
      padding: 12px
    }

    .ud-gallery strong {
      display: block;
      color: var(--ud-dark);
      font-size: 12px;
      margin-bottom: 4px
    }

    .ud-gallery span {
      color: #71817c;
      font-size: 10px;
      line-height: 1.5
    }

    .ud-empty {
      padding: 22px;
      border: 1px dashed #cfded8;
      border-radius: 16px;
      background: #f8fbfa;
      color: #6b7c76;
      font-size: 13px
    }

    .ud-cta {
      margin-top: 52px;
      padding: 32px;
      border-radius: 22px;
      background: var(--ud-dark);
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 20px;
      color: #fff
    }

    .ud-cta h3 {
      font-family: "Plus Jakarta Sans", sans-serif;
      color: #fff;
      margin: 0 0 7px;
      font-size: 22px
    }

    .ud-cta p {
      margin: 0;
      color: #dce9e4;
      font-size: 12px
    }

    .ud-cta a {
      padding: 12px 16px;
      border-radius: 11px;
      background: var(--ud-gold);
      color: var(--ud-dark);
      text-decoration: none;
      font-weight: 800;
      font-size: 12px;
      white-space: nowrap
    }

    @media(max-width:1000px) {
      .ud-leader-grid {
        grid-template-columns: repeat(2, 1fr)
      }

      .ud-gallery {
        grid-template-columns: repeat(3, 1fr)
      }
    }

    @media(max-width:820px) {
      .ud-layout {
        grid-template-columns: 1fr
      }

      .ud-logo {
        height: 220px
      }

      .ud-stats {
        grid-template-columns: repeat(2, 1fr)
      }

      .ud-about {
        grid-template-columns: 1fr
      }

      .ud-cards {
        grid-template-columns: repeat(2, 1fr)
      }

      .ud-gallery {
        grid-template-columns: repeat(2, 1fr)
      }

      .ud-cta {
        display: block
      }

      .ud-cta a {
        display: inline-block;
        margin-top: 18px
      }
    }

    @media(max-width:560px) {
      .ud-main {
        padding: 52px 0 70px
      }

      .ud-stats,
      .ud-leader-grid,
      .ud-cards,
      .ud-gallery {
        grid-template-columns: 1fr
      }

      .ud-facts {
        grid-template-columns: 1fr
      }

      .ud-logo {
        height: 190px
      }

      .ud-hero {
        padding: 48px 0 56px
      }
    }
  </style>
</head>

<body>
  <?php require_once __DIR__ . '/../menu/topbar.php'; ?>
  <?php require_once __DIR__ . '/../menu/navbar.php'; ?>

  <main>
    <section class="ud-hero">
      <div class="container">
        <div class="ud-bread"><a href="/fikes/">Beranda</a><span>›</span><a
            href="/fikes/kemahasiswaan/ukm">UKM</a><span>›</span><span><?= e($org['nama']) ?></span></div>
        <div class="ud-layout">
          <div class="ud-logo"><?php if ($org['logo']): ?><img src="<?= e($org['logo']) ?>"
                alt="<?= e($org['nama']) ?>"><?php endif; ?></div>
          <div>
            <div class="ud-kicker"><?= e($org['kategori']) ?></div>
            <h1><?= e($org['nama']) ?></h1>
            <p><?= e($org['fokus']) ?></p><a class="ud-back" href="/fikes/kemahasiswaan/ukm">← Kembali ke Daftar UKM</a>
          </div>
        </div>
      </div>
    </section>

    <section class="ud-main">
      <div class="container">
        <div class="ud-stats">
          <div class="ud-stat">
            <strong><?= $anggota_count !== null ? number_format($anggota_count) : '—' ?></strong><span>Anggota
              aktif</span>
          </div>
          <div class="ud-stat">
            <strong><?= count($pengurus) ? number_format(count($pengurus)) : '—' ?></strong><span>Pengurus</span>
          </div>
          <div class="ud-stat">
            <strong><?= count($kegiatan) ? number_format(count($kegiatan)) : '—' ?></strong><span>Kegiatan</span>
          </div>
          <div class="ud-stat">
            <strong><?= count($galeri) ? number_format(count($galeri)) : '—' ?></strong><span>Dokumentasi</span>
          </div>
        </div>

        <div class="ud-section">
          <div class="ud-section-head"><span class="ud-label">PROFIL ORGANISASI</span>
            <h2>Tentang <?= e($org['nama']) ?></h2>
            <p class="ud-section-intro">Halaman ini menyajikan informasi lengkap Unit Kegiatan Mahasiswa
              <?= e($org['nama']) ?>. Data kepengurusan, anggota, kegiatan, dan dokumentasi akan tampil otomatis ketika
              data telah dikelola melalui sistem kemahasiswaan.</p>
          </div>
          <div class="ud-about">
            <div class="ud-panel">
              <h3>Fokus Kegiatan</h3>
              <p><?= e($org['fokus']) ?></p>
            </div>
            <div class="ud-panel soft">
              <div class="ud-facts">
                <div class="ud-fact"><strong>Kategori</strong><span><?= e($org['kategori']) ?></span></div>
                <div class="ud-fact"><strong>Status informasi</strong><span>Aktif pada halaman FIKES</span></div>
                <div class="ud-fact">
                  <strong>Anggota</strong><span><?= $anggota_count !== null ? number_format($anggota_count) . ' anggota aktif' : 'Belum tersedia' ?></span>
                </div>
                <div class="ud-fact">
                  <strong>Dokumentasi</strong><span><?= count($galeri) ? number_format(count($galeri)) . ' foto' : 'Belum tersedia' ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="ud-section">
          <div class="ud-section-head"><span class="ud-label">STRUKTUR ORGANISASI</span>
            <h2>Kepengurusan</h2>
            <p class="ud-section-intro">Susunan pengurus <?= e($org['nama']) ?> yang sedang aktif.</p>
          </div><?php if ($pengurus): ?><div class="ud-leader-grid"><?php foreach ($pengurus as $p): ?><article
                  class="ud-leader">
                  <div class="ud-leader-photo <?= empty($p['foto']) ? 'no-photo' : '' ?>"><?php if (!empty($p['foto'])): ?><img
                        src="/fikes/vendor/kemahasiswaan/pengurus/<?= e($p['foto']) ?>" alt="<?= e($p['nama']) ?>"
                        loading="lazy"><?php endif; ?></div>
                  <div class="ud-leader-body">
                    <small><?= e($p['jabatan']) ?></small><strong><?= e($p['nama']) ?></strong><span>Pengurus
                      <?= e($org['nama']) ?></span>
                  </div>
                </article><?php endforeach; ?></div><?php else: ?><div class="ud-empty">Data kepengurusan belum tersedia.
            </div><?php endif; ?>
        </div>

        <div class="ud-section">
          <div class="ud-section-head"><span class="ud-label">DATA ANGGOTA</span>
            <h2>Anggota Aktif</h2>
            <p class="ud-section-intro">Daftar anggota dapat ditampilkan lengkap beserta program studi dan angkatan.
            </p>
          </div><?php if ($anggota): ?><div class="ud-table-wrap">
              <table class="ud-table">
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th>Program Studi</th>
                    <th>Angkatan</th>
                  </tr>
                </thead>
                <tbody><?php foreach ($anggota as $a): ?><tr>
                      <td>
                        <div class="ud-member"><?php if (!empty($a['foto'])): ?><img
                              src="/fikes/vendor/kemahasiswaan/anggota/<?= e($a['foto']) ?>"
                              alt="<?= e($a['nama']) ?>"><?php else: ?><span
                              class="avatar">👤</span><?php endif; ?><span><?= e($a['nama']) ?></span></div>
                      </td>
                      <td><?= e($a['prodi'] ?? '-') ?></td>
                      <td><?= e($a['angkatan'] ?? '-') ?></td>
                    </tr><?php endforeach; ?></tbody>
              </table>
            </div><?php else: ?><div class="ud-empty">Data anggota belum tersedia.</div><?php endif; ?>
        </div>

        <div class="ud-section">
          <div class="ud-section-head"><span class="ud-label">PROGRAM & AKTIVITAS</span>
            <h2>Kegiatan <?= e($org['nama']) ?></h2>
            <p class="ud-section-intro">Dokumentasi kegiatan dan program kerja yang telah dipublikasikan.</p>
          </div><?php if ($kegiatan): ?><div class="ud-cards"><?php foreach ($kegiatan as $k): ?><article
                  class="ud-card">
                  <div class="ud-card-image"><?php if (!empty($k['foto'])): ?><img
                        src="/fikes/vendor/kemahasiswaan/kegiatan/<?= e($k['foto']) ?>" alt="<?= e($k['judul']) ?>"
                        loading="lazy"><?php endif; ?></div>
                  <div class="ud-card-body"><?php if (!empty($k['tanggal_kegiatan'])): ?><span
                        class="ud-date"><?= e($k['tanggal_kegiatan']) ?></span><?php endif; ?><h3><?= e($k['judul']) ?></h3>
                    <p><?= e($k['deskripsi']) ?></p>
                  </div>
                </article><?php endforeach; ?></div><?php else: ?><div class="ud-empty">Belum ada kegiatan yang
              dipublikasikan.</div><?php endif; ?>
        </div>

        <div class="ud-section">
          <div class="ud-section-head"><span class="ud-label">DOKUMENTASI</span>
            <h2>Galeri Foto</h2>
            <p class="ud-section-intro">Kumpulan dokumentasi kegiatan <?= e($org['nama']) ?>.</p>
          </div><?php if ($galeri): ?><div class="ud-gallery"><?php foreach ($galeri as $g): ?><figure><img
                    src="/fikes/vendor/kemahasiswaan/galeri/<?= e($g['foto']) ?>" alt="<?= e($g['judul']) ?>" loading="lazy">
                  <figcaption><strong><?= e($g['judul']) ?></strong><span><?= e($g['keterangan']) ?></span></figcaption>
                </figure><?php endforeach; ?></div><?php else: ?><div class="ud-empty">Belum ada dokumentasi foto yang
              dipublikasikan.</div><?php endif; ?>
        </div>

        <div class="ud-cta">
          <div>
            <h3>Jelajahi UKM lainnya</h3>
            <p>Temukan organisasi mahasiswa lain dan lihat informasi lengkapnya.</p>
          </div><a href="/fikes/kemahasiswaan/ukm">Lihat Semua UKM →</a>
        </div>
      </div>
    </section>
  </main>

  <?php require_once __DIR__ . '/../menu/footer.php'; ?>
  <script src="/fikes/assets/js/main.js"></script>
</body>

</html>
