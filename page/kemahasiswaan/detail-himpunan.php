<?php
$page_title = 'Detail Himpunan Mahasiswa';

if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$orgs = [
    'himafarda' => [
        'nama' => 'HIMAFARDA',
        'kategori' => 'Himpunan Mahasiswa Farmasi',
        'logo' => '/fikes/vendor/kemahasiswaan/himpunan/himafarda.jpg',
        'deskripsi' => 'Wadah mahasiswa Farmasi untuk mengembangkan organisasi, aspirasi, kreativitas, dan kegiatan kemahasiswaan.',
        'fokus' => 'Pengembangan organisasi, keilmuan, kreativitas, pengabdian, dan kebersamaan mahasiswa Farmasi.'
    ],
    'himasada' => [
        'nama' => 'HIMASADA',
        'kategori' => 'Himpunan Mahasiswa',
        'logo' => '/fikes/vendor/kemahasiswaan/himpunan/himasada.jpg',
        'deskripsi' => 'Ruang pengembangan potensi, kebersamaan, kepemimpinan, dan kolaborasi mahasiswa di lingkungan FIKES.',
        'fokus' => 'Pengembangan potensi mahasiswa, kepemimpinan, kebersamaan, dan kegiatan kemahasiswaan.'
    ],
    'himadika' => [
        'nama' => 'HIMADIKA',
        'kategori' => 'Himpunan Mahasiswa Keperawatan',
        'logo' => '/fikes/vendor/kemahasiswaan/himpunan/himadika.png',
        'deskripsi' => 'Wadah mahasiswa Keperawatan dalam kegiatan organisasi, pengembangan diri, dan kontribusi kepada lingkungan kampus.',
        'fokus' => 'Keilmuan keperawatan, kepemimpinan, pengembangan diri, pengabdian, dan kegiatan mahasiswa.'
    ],
    'himika' => [
        'nama' => 'HIMIKA',
        'kategori' => 'Himpunan Mahasiswa',
        'logo' => '/fikes/vendor/kemahasiswaan/himpunan/himika.png',
        'deskripsi' => 'Organisasi mahasiswa yang mendukung aktivitas kemahasiswaan, aspirasi, dan pengembangan kepemimpinan.',
        'fokus' => 'Organisasi, aspirasi mahasiswa, kepemimpinan, kreativitas, dan kolaborasi.'
    ],
    'himadan' => [
        'nama' => 'HIMADAN',
        'kategori' => 'Himpunan Mahasiswa',
        'logo' => '/fikes/vendor/kemahasiswaan/himpunan/himadan.jpg',
        'deskripsi' => 'Wadah mahasiswa untuk mengembangkan kreativitas, komunikasi, solidaritas, dan kegiatan sosial.',
        'fokus' => 'Kreativitas, komunikasi, solidaritas, kegiatan sosial, dan pengembangan mahasiswa.'
    ],
    'bem-fikes' => [
        'nama' => 'BEM FIKES',
        'kategori' => 'Organisasi Mahasiswa Tingkat Fakultas',
        'logo' => '/fikes/vendor/kemahasiswaan/himpunan/bem.jpg',
        'deskripsi' => 'Badan Eksekutif Mahasiswa sebagai wadah pelaksanaan program dan kegiatan mahasiswa di tingkat fakultas.',
        'fokus' => 'Koordinasi program kerja, pelayanan mahasiswa, pengembangan kegiatan, dan kolaborasi.'
    ],
    'dpm-fikes' => [
        'nama' => 'DPM FIKES',
        'kategori' => 'Organisasi Mahasiswa Tingkat Fakultas',
        'logo' => '/fikes/vendor/kemahasiswaan/himpunan/dpm.png',
        'deskripsi' => 'Dewan Perwakilan Mahasiswa sebagai ruang perwakilan dan penyampaian aspirasi mahasiswa.',
        'fokus' => 'Perwakilan mahasiswa, aspirasi, pengawasan organisasi, dan komunikasi kelembagaan.'
    ],
];

$slug = trim((string)($_GET['slug'] ?? ''));
if (!isset($orgs[$slug])) {
    http_response_code(404);
    $org = null;
} else {
    $org = $orgs[$slug];
    $page_title = $org['nama'];
}

function table_exists(PDO $pdo, string $table): bool {
    $st = $pdo->prepare("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?");
    $st->execute([$table]);
    return (bool)$st->fetchColumn();
}

$pdo = null;
$pengurus = [];
$anggota_count = null;
$kegiatan = [];
$galeri = [];

if ($org) {
    try {
        require_once __DIR__ . '/../../admin/config/database.php';
        if ($pdo instanceof PDO) {
            if (table_exists($pdo, 'kemahasiswaan_pengurus')) {
                $st = $pdo->prepare('SELECT nama, jabatan, foto FROM kemahasiswaan_pengurus WHERE organisasi_slug = ? AND status = "aktif" ORDER BY nomor_urut, id');
                $st->execute([$slug]);
                $pengurus = $st->fetchAll();
            }
            if (table_exists($pdo, 'kemahasiswaan_anggota')) {
                $st = $pdo->prepare('SELECT COUNT(*) FROM kemahasiswaan_anggota WHERE organisasi_slug = ? AND status = "aktif"');
                $st->execute([$slug]);
                $anggota_count = (int)$st->fetchColumn();
            }
            if (table_exists($pdo, 'kemahasiswaan_kegiatan')) {
                $st = $pdo->prepare('SELECT judul, tanggal_kegiatan, deskripsi, foto FROM kemahasiswaan_kegiatan WHERE organisasi_slug = ? AND status = "publish" ORDER BY tanggal_kegiatan DESC, nomor_urut, id');
                $st->execute([$slug]);
                $kegiatan = $st->fetchAll();
            }
            if (table_exists($pdo, 'kemahasiswaan_galeri')) {
                $st = $pdo->prepare('SELECT judul, foto, keterangan FROM kemahasiswaan_galeri WHERE organisasi_slug = ? AND status = "publish" ORDER BY nomor_urut, id DESC');
                $st->execute([$slug]);
                $galeri = $st->fetchAll();
            }
        }
    } catch (Throwable $e) {
        // Halaman tetap tampil meskipun tabel detail belum dibuat.
    }
}
?>
<!doctype html>
<html lang="id">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?> | FIKES - Fakultas Ilmu Kesehatan</title>
    <meta name="description" content="Informasi detail <?= e($page_title) ?> Fakultas Ilmu Kesehatan.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap"
      rel="stylesheet">
    <link rel="stylesheet" href="/fikes/assets/css/style.css">
    <style>
    :root {
      --hm-primary: #087f5b;
      --hm-dark: #12372a;
      --hm-text: #52635d;
      --hm-soft: #eef8f4;
      --hm-border: #e2ece8;
      --hm-gold: #f4b942;
    }

    .hm-hero {
      position: relative;
      overflow: hidden;
      padding: 54px 0 64px;
      background: linear-gradient(120deg, #00685a 0%, #008f78 58%, #8ad0c1 150%);
      color: #fff
    }

    .hm-hero:before {
      content: "";
      position: absolute;
      width: 420px;
      height: 420px;
      right: -170px;
      top: -220px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .08)
    }

    .hm-breadcrumb {
      display: flex;
      gap: 9px;
      align-items: center;
      flex-wrap: wrap;
      font-size: 13px;
      color: rgba(255, 255, 255, .8);
      margin-bottom: 25px;
      position: relative;
      z-index: 1
    }

    .hm-breadcrumb a {
      color: #fff;
      text-decoration: none;
      font-weight: 700
    }

    .hm-hero-grid {
      display: grid;
      grid-template-columns: 180px 1fr;
      gap: 30px;
      align-items: center;
      position: relative;
      z-index: 1
    }

    .hm-logo {
      width: 180px;
      height: 180px;
      border-radius: 28px;
      background: #fff;
      padding: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 18px 50px rgba(0, 0, 0, .14)
    }

    .hm-logo img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
      border-radius: 12px
    }

    .hm-kicker {
      font-size: 11px;
      letter-spacing: 1.8px;
      font-weight: 800;
      color: #dff7ef;
      text-transform: uppercase
    }

    .hm-hero h1 {
      font-family: "Plus Jakarta Sans", sans-serif;
      color: #fff;
      font-size: clamp(34px, 5vw, 58px);
      margin: 10px 0 12px;
      line-height: 1.08
    }

    .hm-hero p {
      max-width: 800px;
      color: rgba(255, 255, 255, .88);
      line-height: 1.8;
      font-size: 15px;
      margin: 0
    }

    .hm-back {
      display: inline-flex;
      margin-top: 20px;
      padding: 10px 15px;
      border: 1px solid rgba(255, 255, 255, .35);
      border-radius: 12px;
      color: #fff;
      text-decoration: none;
      font-size: 12px;
      font-weight: 800
    }

    .hm-content {
      padding: 72px 0 90px;
      background: #fff
    }

    .hm-section {
      margin-bottom: 28px
    }

    .hm-section-head {
      margin-bottom: 20px
    }

    .hm-label {
      display: block;
      color: var(--hm-primary);
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      margin-bottom: 8px
    }

    .hm-section h2 {
      font-family: "Plus Jakarta Sans", sans-serif;
      color: var(--hm-dark);
      font-size: clamp(24px, 3vw, 34px);
      margin: 0 0 8px
    }

    .hm-section-head p {
      color: #6b7c76;
      font-size: 14px;
      line-height: 1.8;
      margin: 0;
      max-width: 760px
    }

    .hm-stats {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 14px;
      margin-bottom: 40px
    }

    .hm-stat {
      padding: 20px;
      border: 1px solid var(--hm-border);
      border-radius: 16px;
      background: #fff;
      box-shadow: 0 10px 30px rgba(18, 55, 42, .06)
    }

    .hm-stat strong {
      display: block;
      font-family: "Plus Jakarta Sans", sans-serif;
      color: var(--hm-dark);
      font-size: 20px;
      margin-bottom: 4px
    }

    .hm-stat span {
      font-size: 12px;
      color: #74847f
    }

    .hm-about {
      display: grid;
      grid-template-columns: 1.15fr .85fr;
      gap: 24px;
      margin-bottom: 42px
    }

    .hm-panel {
      border: 1px solid var(--hm-border);
      border-radius: 20px;
      padding: 28px;
      background: #fff;
      box-shadow: 0 10px 30px rgba(18, 55, 42, .05)
    }

    .hm-panel.soft {
      background: #f7fbf9
    }

    .hm-panel p {
      font-size: 14px;
      color: #62736d;
      line-height: 1.9;
      margin: 0 0 14px
    }

    .hm-focus {
      display: flex;
      gap: 10px;
      align-items: flex-start;
      padding: 14px;
      border-radius: 13px;
      background: var(--hm-soft);
      color: #36584d;
      font-size: 13px;
      line-height: 1.7
    }

    .hm-focus i {
      color: var(--hm-primary);
      font-style: normal;
      font-weight: 900
    }

    .hm-people {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px
    }

    .hm-person {
      border: 1px solid var(--hm-border);
      border-radius: 16px;
      overflow: hidden;
      background: #fff
    }

    .hm-person-photo {
      height: 170px;
      background: #f1f7f4;
      display: flex;
      align-items: center;
      justify-content: center
    }

    .hm-person-photo img {
      width: 100%;
      height: 100%;
      object-fit: cover
    }

    .hm-person-empty {
      font-size: 12px;
      color: #83928d;
      text-align: center;
      padding: 20px
    }

    .hm-person-body {
      padding: 16px
    }

    .hm-person-body strong {
      display: block;
      color: var(--hm-dark);
      font-size: 14px;
      margin-bottom: 4px
    }

    .hm-person-body span {
      font-size: 12px;
      color: #73827d
    }

    .hm-list {
      display: grid;
      gap: 12px
    }

    .hm-item {
      display: grid;
      grid-template-columns: 1fr auto;
      gap: 18px;
      padding: 18px;
      border: 1px solid var(--hm-border);
      border-radius: 15px;
      background: #fff
    }

    .hm-item h3 {
      margin: 0 0 5px;
      color: var(--hm-dark);
      font-size: 15px
    }

    .hm-item p {
      margin: 0;
      color: #71817c;
      font-size: 13px;
      line-height: 1.7
    }

    .hm-date {
      font-size: 11px;
      font-weight: 800;
      color: var(--hm-primary);
      white-space: nowrap
    }

    .hm-gallery {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 14px
    }

    .hm-gallery figure {
      margin: 0;
      border-radius: 15px;
      overflow: hidden;
      background: #f2f7f5;
      border: 1px solid var(--hm-border)
    }

    .hm-gallery img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      display: block
    }

    .hm-gallery figcaption {
      padding: 10px 12px;
      font-size: 11px;
      color: #6c7b76
    }

    .hm-empty {
      padding: 28px;
      text-align: center;
      border: 1px dashed #cfded8;
      border-radius: 16px;
      color: #7b8a85;
      background: #fafcfb;
      font-size: 13px;
      line-height: 1.7
    }

    .hm-cta {
      margin-top: 35px;
      padding: 30px;
      border-radius: 20px;
      background: var(--hm-dark);
      color: #fff;
      display: flex;
      justify-content: space-between;
      gap: 20px;
      align-items: center
    }

    .hm-cta h3 {
      margin: 0 0 5px;
      color: #fff;
      font-family: "Plus Jakarta Sans", sans-serif;
      font-size: 23px
    }

    .hm-cta p {
      margin: 0;
      color: #d8e8e1;
      font-size: 13px;
      line-height: 1.7
    }

    .hm-cta a {
      flex: none;
      padding: 11px 15px;
      border-radius: 12px;
      background: #fff;
      color: var(--hm-dark);
      font-size: 12px;
      font-weight: 800;
      text-decoration: none
    }

    @media(max-width:950px) {
      .hm-stats {
        grid-template-columns: repeat(2, 1fr)
      }

      .hm-about {
        grid-template-columns: 1fr
      }

      .hm-people {
        grid-template-columns: repeat(2, 1fr)
      }

      .hm-gallery {
        grid-template-columns: repeat(2, 1fr)
      }
    }

    @media(max-width:650px) {
      .hm-hero {
        padding: 45px 0 52px
      }

      .hm-hero-grid {
        grid-template-columns: 1fr;
        text-align: center
      }

      .hm-logo {
        margin: auto;
        width: 150px;
        height: 150px
      }

      .hm-hero p {
        font-size: 14px
      }

      .hm-stats {
        grid-template-columns: 1fr 1fr
      }

      .hm-content {
        padding: 58px 0 68px
      }

      .hm-people,
      .hm-gallery {
        grid-template-columns: 1fr
      }

      .hm-gallery img {
        height: 220px
      }

      .hm-item {
        grid-template-columns: 1fr
      }

      .hm-date {
        white-space: normal
      }

      .hm-cta {
        display: block
      }

      .hm-cta a {
        display: inline-flex;
        margin-top: 18px
      }
    }
    </style>
  </head>

  <body>
    <?php require_once __DIR__ . '/../menu/topbar.php'; ?>
    <?php require_once __DIR__ . '/../menu/navbar.php'; ?>

    <?php if (!$org): ?>
    <main>
      <section class="hm-content">
        <div class="container">
          <div class="hm-empty">Data organisasi tidak ditemukan. <a
              href="/fikes/kemahasiswaan/himpunan-kemahasiswaan">Kembali ke Himpunan Mahasiswa</a></div>
        </div>
      </section>
    </main>
    <?php else: ?>
    <main>
      <section class="hm-hero">
        <div class="container">
          <div class="hm-breadcrumb"><a href="/fikes/">Beranda</a><span>›</span><a
              href="/fikes/kemahasiswaan/himpunan-kemahasiswaan">Kemahasiswaan</a><span>›</span><span><?= e($org['nama']) ?></span>
          </div>
          <div class="hm-hero-grid">
            <div class="hm-logo"><img src="<?= e($org['logo']) ?>" alt="<?= e($org['nama']) ?>"></div>
            <div>
              <div class="hm-kicker"><?= e($org['kategori']) ?></div>
              <h1><?= e($org['nama']) ?></h1>
              <p><?= e($org['deskripsi']) ?></p><a class="hm-back" href="/fikes/kemahasiswaan/himpunan-kemahasiswaan">←
                Kembali ke Himpunan</a>
            </div>
          </div>
        </div>
      </section>

      <section class="hm-content">
        <div class="container">
          <div class="hm-stats">
            <div class="hm-stat">
              <strong><?= $anggota_count !== null ? number_format($anggota_count) : '—' ?></strong><span>Anggota
                aktif</span>
            </div>
            <div class="hm-stat"><strong><?= count($pengurus) ?: '—' ?></strong><span>Pengurus aktif</span></div>
            <div class="hm-stat"><strong><?= count($kegiatan) ?: '—' ?></strong><span>Kegiatan terdokumentasi</span>
            </div>
            <div class="hm-stat"><strong><?= count($galeri) ?: '—' ?></strong><span>Dokumentasi foto</span></div>
          </div>

          <div class="hm-about">
            <div class="hm-panel"><span class="hm-label">Profil Organisasi</span>
              <h2>Tentang <?= e($org['nama']) ?></h2>
              <p><?= e($org['deskripsi']) ?></p>
              <div class="hm-focus"><i>✓</i><span><strong>Fokus kegiatan:</strong> <?= e($org['fokus']) ?></span></div>
            </div>
            <div class="hm-panel soft"><span class="hm-label">Informasi Organisasi</span>
              <h2>Identitas</h2>
              <p><strong>Nama organisasi</strong><br><?= e($org['nama']) ?></p>
              <p><strong>Kategori</strong><br><?= e($org['kategori']) ?></p>
              <p><strong>Status data</strong><br>Informasi detail dapat dikelola melalui data organisasi.</p>
            </div>
          </div>

          <div class="hm-section">
            <div class="hm-section-head"><span class="hm-label">Kepengurusan</span>
              <h2>Pengurus Organisasi</h2>
              <p>Daftar pejabat/pengurus akan tampil otomatis apabila data kepengurusan sudah dimasukkan.</p>
            </div>
            <?php if ($pengurus): ?><div class="hm-people"><?php foreach ($pengurus as $p): ?><article
                class="hm-person">
                <div class="hm-person-photo"><?php if (!empty($p['foto'])): ?><img
                    src="/fikes/vendor/kemahasiswaan/pengurus/<?= e($p['foto']) ?>"
                    alt="<?= e($p['nama']) ?>"><?php else: ?><div class="hm-person-empty">Foto belum tersedia</div>
                  <?php endif; ?></div>
                <div class="hm-person-body"><strong><?= e($p['nama']) ?></strong><span><?= e($p['jabatan']) ?></span>
                </div>
              </article><?php endforeach; ?></div><?php else: ?><div class="hm-empty">Data pengurus belum tersedia.
              Struktur ini sudah disiapkan untuk menampilkan <strong>Ketua, Wakil Ketua, Sekretaris, Bendahara,
                bidang/divisi, dan pengurus lainnya</strong> setelah data dimasukkan.</div><?php endif; ?>
          </div>

          <div class="hm-section">
            <div class="hm-section-head"><span class="hm-label">Keanggotaan</span>
              <h2>Anggota</h2>
              <p>Informasi anggota dapat dikembangkan menjadi daftar anggota, angkatan, bidang/divisi, dan status
                keanggotaan.</p>
            </div>
            <div class="hm-empty"><?php if ($anggota_count !== null): ?>Terdapat
              <strong><?= number_format($anggota_count) ?></strong> anggota aktif.<?php else: ?>Data anggota belum
              tersedia. Bagian ini siap menampilkan daftar anggota secara lengkap setelah data
              dimasukkan.<?php endif; ?>
            </div>
          </div>

          <div class="hm-section">
            <div class="hm-section-head"><span class="hm-label">Program & Aktivitas</span>
              <h2>Kegiatan Organisasi</h2>
              <p>Daftar kegiatan dapat berisi nama kegiatan, tanggal, deskripsi, dokumentasi, dan informasi hasil
                kegiatan.</p>
            </div><?php if ($kegiatan): ?><div class="hm-list"><?php foreach ($kegiatan as $k): ?><article
                class="hm-item">
                <div>
                  <h3><?= e($k['judul']) ?></h3>
                  <p><?= e($k['deskripsi']) ?></p>
                </div>
                <div class="hm-date"><?= e($k['tanggal_kegiatan']) ?></div>
              </article><?php endforeach; ?></div><?php else: ?><div class="hm-empty">Belum ada kegiatan yang
              dipublikasikan.</div><?php endif; ?>
          </div>

          <div class="hm-section">
            <div class="hm-section-head"><span class="hm-label">Dokumentasi</span>
              <h2>Galeri Kegiatan</h2>
              <p>Foto kegiatan organisasi dapat ditampilkan dalam bentuk galeri visual.</p>
            </div><?php if ($galeri): ?><div class="hm-gallery"><?php foreach ($galeri as $g): ?><figure><img
                  src="/fikes/vendor/kemahasiswaan/galeri/<?= e($g['foto']) ?>" alt="<?= e($g['judul']) ?>"
                  loading="lazy">
                <figcaption>
                  <strong><?= e($g['judul']) ?></strong><?= !empty($g['keterangan']) ? ' — '.e($g['keterangan']) : '' ?>
                </figcaption>
              </figure><?php endforeach; ?></div><?php else: ?><div class="hm-empty">Belum ada foto kegiatan yang
              dipublikasikan. Setelah foto dimasukkan, galeri akan tampil otomatis di bagian ini.</div><?php endif; ?>
          </div>

          <div class="hm-cta">
            <div>
              <h3>Ingin mengenal lebih jauh?</h3>
              <p>Kembali ke daftar Himpunan Mahasiswa FIKES untuk melihat organisasi lainnya.</p>
            </div><a href="/fikes/kemahasiswaan/himpunan-kemahasiswaan">Lihat Semua Himpunan</a>
          </div>
        </div>
      </section>
    </main>
    <?php endif; ?>

    <footer>
      <div class="container footer-main">
        <div class="footer-brand">
          <div class="logo footer-logo">
            <div class="logo-icon">F</div>
            <div class="logo-text"><strong style="color:white">FIKES</strong><small>FAKULTAS ILMU KESEHATAN</small>
            </div>
          </div>
          <p>Membangun generasi kesehatan yang profesional, berintegritas, inovatif, dan berorientasi kepada masyarakat.
          </p>
        </div>
        <div>
          <h4 class="footer-title">Tentang FIKES</h4>
          <div class="footer-links"><a href="/fikes/tentang/visi-misi">Visi Misi</a><a
              href="/fikes/tentang/struktur-organisasi">Struktur Organisasi</a><a
              href="/fikes/tentang/sertifikat-akreditasi">Akreditasi</a><a href="/fikes/dosen">Daftar Dosen</a></div>
        </div>
        <div>
          <h4 class="footer-title">Program Studi</h4>
          <div class="footer-links"><a href="/fikes/program-studi">Profesi Ners</a><a href="/fikes/program-studi">Ilmu
              Keperawatan</a><a href="/fikes/program-studi">Farmasi</a><a href="/fikes/program-studi">Kebidanan</a><a
              href="/fikes/program-studi">K3</a></div>
        </div>
        <div>
          <h4 class="footer-title">Informasi</h4>
          <div class="footer-links"><a href="/fikes/akademik">Akademik</a><a
              href="/fikes/kemahasiswaan/himpunan-kemahasiswaan">Kemahasiswaan</a><a href="/fikes/survey">Survey</a>
          </div>
        </div>
        <div class="footer-location">
          <div class="location-header">
            <div class="location-icon"><i class="fa-solid fa-location-dot"></i></div>
            <div>
              <h3>Lokasi Kampus</h3>
              <p>Fakultas Ilmu Kesehatan</p>
            </div>
          </div>
          <div class="map-card"><iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.1515727139526!2d109.11806027499709!3d-6.991421893009626!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6fbef42471658d%3A0x883656d1325ef066!2sUniversitas%20Bhamada%20Slawi!5e0!3m2!1sid!2sid!4v1787544396003!5m2!1sid!2sid"
              width="600" height="450" style="border:0" allowfullscreen loading="lazy"
              title="Lokasi Fakultas Ilmu Kesehatan"></iframe></div>
        </div>
      </div>
      <div class="container footer-bottom"><span>© <span id="year"></span> Fakultas Ilmu Kesehatan. All Rights
          Reserved.</span><span>Website FIKES</span></div>
    </footer>
    <script src="/fikes/assets/js/main.js"></script>
  </body>

</html>
