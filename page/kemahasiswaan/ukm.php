<?php
$page_title = 'UKM Kemahasiswaan';

if (!function_exists('e')) {
  function e($value)
  {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
  }
}

$ukmList = [
  ['slug' => 'karate', 'nama' => 'Karate', 'kategori' => 'Olahraga & Bela Diri', 'icon' => '🥋', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/karate.png', 'deskripsi' => 'Wadah pengembangan bela diri, disiplin, kebugaran, karakter, dan prestasi mahasiswa.'],
  ['slug' => 'basket', 'nama' => 'Basket', 'kategori' => 'Olahraga', 'icon' => '🏀', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/basket.png', 'deskripsi' => 'Wadah pengembangan kemampuan bola basket, sportivitas, kebugaran, dan kerja sama tim.'],
  ['slug' => 'futsal', 'nama' => 'Futsal', 'kategori' => 'Olahraga', 'icon' => '⚽', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/futsal.png', 'deskripsi' => 'Wadah pengembangan teknik futsal, kekompakan, kebugaran, dan pengalaman kompetisi.'],
  ['slug' => 'badminton', 'nama' => 'Badminton', 'kategori' => 'Olahraga', 'icon' => '🏸', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/badminton.png', 'deskripsi' => 'Wadah pengembangan keterampilan badminton, kebugaran, sportivitas, dan prestasi mahasiswa.'],
  ['slug' => 'voli', 'nama' => 'Voli', 'kategori' => 'Olahraga', 'icon' => '🏐', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/voli.jpg', 'deskripsi' => 'Wadah pengembangan kemampuan bola voli, kekompakan tim, kebugaran, dan prestasi.'],
  ['slug' => 'silat', 'nama' => 'Silat', 'kategori' => 'Olahraga & Bela Diri', 'icon' => '🥋', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/silat.jpeg', 'deskripsi' => 'Wadah pengembangan seni bela diri, disiplin, karakter, kebugaran, dan prestasi.'],
  ['slug' => 'bmb', 'nama' => 'BMB', 'kategori' => 'Minat & Bakat', 'icon' => '✨', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/bmb.png', 'deskripsi' => 'Ruang bagi mahasiswa untuk mengembangkan minat, bakat, kreativitas, dan pengalaman berorganisasi.'],
  ['slug' => 'pik', 'nama' => 'PIK', 'kategori' => 'Pengembangan Mahasiswa', 'icon' => '💡', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/pik.jpg', 'deskripsi' => 'Ruang pengembangan edukasi, komunikasi, kreativitas, dan kepedulian mahasiswa.'],
  ['slug' => 'bhapala', 'nama' => 'BHAPALA', 'kategori' => 'Kepencintaalaman', 'icon' => '🏕️', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/bhapala.jpg', 'deskripsi' => 'Wadah kegiatan alam bebas, kepedulian lingkungan, kebersamaan, dan ketangguhan mahasiswa.'],
  ['slug' => 'sentramada', 'nama' => 'Sentramada', 'kategori' => 'Seni & Kreativitas', 'icon' => '🎨', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/sentramada.png', 'deskripsi' => 'Ruang ekspresi seni, kreativitas, kolaborasi, dan pengembangan potensi mahasiswa.'],
  ['slug' => 'voice', 'nama' => 'Voice', 'kategori' => 'Seni & Musik', 'icon' => '🎤', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/voice.jpeg', 'deskripsi' => 'Wadah pengembangan vokal, musik, penampilan, kepercayaan diri, dan kreativitas seni.'],
  ['slug' => 'pramuka', 'nama' => 'Pramuka', 'kategori' => 'Kepanduan', 'icon' => '⚜️', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/pramuka.jpg', 'deskripsi' => 'Wadah pengembangan kepemimpinan, kedisiplinan, kemandirian, dan kegiatan sosial.'],
  ['slug' => 'bakti', 'nama' => 'Bakti', 'kategori' => 'Sosial & Pengabdian', 'icon' => '🤝', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/bhakti.jpg', 'deskripsi' => 'Ruang pengembangan kepedulian sosial, pengabdian, dan kegiatan kemasyarakatan.'],
  ['slug' => 'jurnalika', 'nama' => 'Jurnalika', 'kategori' => 'Media & Jurnalistik', 'icon' => '📰', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/jurnalika.jpg', 'deskripsi' => 'Wadah pengembangan jurnalistik, penulisan, dokumentasi, media, dan komunikasi.'],
  ['slug' => 'ksr', 'nama' => 'KSR', 'kategori' => 'Kemanusiaan', 'icon' => '⛑️', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/ksr.jpg', 'deskripsi' => 'Wadah pengembangan kepedulian kemanusiaan, kesiapsiagaan, dan kegiatan sosial.'],
  ['slug' => 'bec', 'nama' => 'BEC', 'kategori' => 'Bahasa & Komunikasi', 'icon' => '🌐', 'logo' => '/fikes/vendor/kemahasiswaan/ukm/bec.png', 'deskripsi' => 'Ruang pengembangan kemampuan bahasa, komunikasi, kepercayaan diri, dan kreativitas.'],
];
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($page_title) ?> | FIKES</title>
  <meta name="description"
    content="Informasi Unit Kegiatan Mahasiswa FIKES dan berbagai kegiatan pengembangan minat, bakat, kreativitas, serta prestasi mahasiswa.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="/fikes/assets/css/style.css">
  <style>
    :root {
      --ukm-green: #087f5b;
      --ukm-dark: #12372a;
      --ukm-text: #5f716b;
      --ukm-soft: #eef8f4;
      --ukm-border: #e1ebe7;
      --ukm-gold: #f4b942
    }

    .ukm-hero {
      position: relative;
      overflow: hidden;
      padding: 64px 0 76px;
      color: #fff;
      background: radial-gradient(circle at 88% 15%, rgba(255, 255, 255, .22), transparent 27%), linear-gradient(120deg, #00685a 0%, #008f78 58%, #7fcbbd 150%)
    }

    .ukm-hero:before,
    .ukm-hero:after {
      content: "";
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, .07)
    }

    .ukm-hero:before {
      width: 280px;
      height: 280px;
      right: -80px;
      top: -130px
    }

    .ukm-hero:after {
      width: 390px;
      height: 390px;
      left: -220px;
      bottom: -300px
    }

    .ukm-breadcrumb {
      position: relative;
      z-index: 2;
      display: flex;
      gap: 9px;
      align-items: center;
      flex-wrap: wrap;
      font-size: 13px;
      color: rgba(255, 255, 255, .8);
      margin-bottom: 22px
    }

    .ukm-breadcrumb a {
      color: #fff;
      text-decoration: none;
      font-weight: 700
    }

    .ukm-kicker {
      position: relative;
      z-index: 2;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 1.8px;
      text-transform: uppercase;
      color: #dcf7ef
    }

    .ukm-hero h1 {
      position: relative;
      z-index: 2;
      font-family: "Plus Jakarta Sans", sans-serif;
      color: #fff;
      font-size: clamp(34px, 5vw, 58px);
      line-height: 1.08;
      margin: 12px 0 16px;
      max-width: 820px
    }

    .ukm-hero h1 em {
      font-style: normal;
      color: var(--ukm-gold)
    }

    .ukm-hero p {
      position: relative;
      z-index: 2;
      max-width: 760px;
      margin: 0;
      color: rgba(255, 255, 255, .9);
      font-size: 16px;
      line-height: 1.85
    }

    .ukm-toolbar {
      margin-top: 34px;
      position: relative;
      z-index: 2;
      display: flex;
      flex-wrap: wrap;
      gap: 12px
    }

    .ukm-pill {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 9px 13px;
      border: 1px solid rgba(255, 255, 255, .2);
      border-radius: 999px;
      background: rgba(255, 255, 255, .1);
      backdrop-filter: blur(8px);
      font-size: 12px;
      font-weight: 700
    }

    .ukm-main {
      padding: 78px 0 90px;
      background: #fff
    }

    .ukm-heading {
      text-align: center;
      max-width: 760px;
      margin: 0 auto 38px
    }

    .ukm-label {
      display: block;
      color: var(--ukm-green);
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 1.7px;
      text-transform: uppercase;
      margin-bottom: 9px
    }

    .ukm-heading h2 {
      font-family: "Plus Jakarta Sans", sans-serif;
      color: var(--ukm-dark);
      font-size: clamp(27px, 3.4vw, 38px);
      margin: 0 0 10px
    }

    .ukm-heading p {
      margin: 0;
      color: var(--ukm-text);
      font-size: 14px;
      line-height: 1.8
    }

    .ukm-grid {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 20px
    }

    .ukm-card {
      position: relative;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      border: 1px solid var(--ukm-border);
      border-radius: 22px;
      background: #fff;
      box-shadow: 0 12px 35px rgba(18, 55, 42, .065);
      transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease
    }

    .ukm-card:hover {
      transform: translateY(-7px);
      box-shadow: 0 24px 55px rgba(18, 55, 42, .13);
      border-color: #c9ded7
    }

    .ukm-card-image {
      height: 205px;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 25px;
      background: linear-gradient(145deg, #f8fcfa, #eaf6f1)
    }

    .ukm-card-image:after {
      content: "";
      position: absolute;
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background: rgba(8, 127, 91, .055)
    }

    .ukm-card-image img {
      position: relative;
      z-index: 1;
      width: 100%;
      height: 100%;
      object-fit: contain;
      transition: transform .3s ease
    }

    .ukm-card:hover .ukm-card-image img {
      transform: scale(1.05)
    }

    .ukm-icon {
      position: absolute;
      z-index: 2;
      right: 13px;
      top: 13px;
      width: 38px;
      height: 38px;
      display: grid;
      place-items: center;
      border-radius: 12px;
      background: #fff;
      box-shadow: 0 7px 20px rgba(18, 55, 42, .12);
      font-size: 18px
    }

    .ukm-card-body {
      display: flex;
      flex: 1;
      flex-direction: column;
      padding: 21px
    }

    .ukm-category {
      display: inline-flex;
      align-self: flex-start;
      padding: 6px 9px;
      border-radius: 999px;
      background: var(--ukm-soft);
      color: var(--ukm-green);
      font-size: 9px;
      font-weight: 800;
      letter-spacing: .8px;
      text-transform: uppercase;
      margin-bottom: 11px
    }

    .ukm-card h3 {
      font-family: "Plus Jakarta Sans", sans-serif;
      color: var(--ukm-dark);
      font-size: 19px;
      margin: 0 0 8px
    }

    .ukm-card p {
      margin: 0;
      color: var(--ukm-text);
      font-size: 12.5px;
      line-height: 1.75
    }

    .ukm-detail {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      margin-top: auto;
      padding-top: 18px
    }

    .ukm-detail a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      width: 100%;
      padding: 11px 14px;
      border-radius: 11px;
      background: var(--ukm-dark);
      color: #fff;
      text-decoration: none;
      font-size: 12px;
      font-weight: 800;
      transition: .2s
    }

    .ukm-detail a:hover {
      background: var(--ukm-green)
    }

    .ukm-info {
      margin-top: 52px;
      padding: 34px;
      border: 1px solid var(--ukm-border);
      border-radius: 24px;
      background: linear-gradient(135deg, #f8fbfa, #eef8f4);
      display: grid;
      grid-template-columns: 1.2fr .8fr;
      gap: 28px;
      align-items: center
    }

    .ukm-info h3 {
      font-family: "Plus Jakarta Sans", sans-serif;
      color: var(--ukm-dark);
      font-size: 25px;
      margin: 0 0 9px
    }

    .ukm-info p {
      margin: 0;
      color: var(--ukm-text);
      font-size: 14px;
      line-height: 1.8
    }

    .ukm-mini {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px
    }

    .ukm-mini-item {
      padding: 16px;
      border: 1px solid var(--ukm-border);
      border-radius: 15px;
      background: #fff
    }

    .ukm-mini-item strong {
      display: block;
      color: var(--ukm-dark);
      font-size: 14px;
      margin-bottom: 4px
    }

    .ukm-mini-item span {
      color: #71817c;
      font-size: 11px;
      line-height: 1.5
    }

    .ukm-cta {
      margin-top: 20px;
      padding: 34px;
      border-radius: 22px;
      background: var(--ukm-dark);
      text-align: center;
      color: #fff
    }

    .ukm-cta h3 {
      color: #fff;
      font-family: "Plus Jakarta Sans", sans-serif;
      margin: 0 0 8px;
      font-size: 25px
    }

    .ukm-cta p {
      margin: 0;
      color: #dce9e4;
      font-size: 14px;
      line-height: 1.7
    }

    @media(max-width:1100px) {
      .ukm-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr))
      }
    }

    @media(max-width:820px) {
      .ukm-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr))
      }

      .ukm-info {
        grid-template-columns: 1fr
      }
    }

    @media(max-width:560px) {
      .ukm-hero {
        padding: 50px 0 60px
      }

      .ukm-main {
        padding: 58px 0 70px
      }

      .ukm-grid {
        grid-template-columns: 1fr
      }

      .ukm-card-image {
        height: 220px
      }

      .ukm-mini {
        grid-template-columns: 1fr
      }

      .ukm-toolbar {
        gap: 8px
      }

      .ukm-pill {
        font-size: 11px
      }
    }
  </style>
</head>

<body>
  <?php require_once __DIR__ . '/../menu/topbar.php'; ?>
  <?php require_once __DIR__ . '/../menu/navbar.php'; ?>

  <main>
    <section class="ukm-hero">
      <div class="container">
        <div class="ukm-breadcrumb"><a
            href="/fikes/">Beranda</a><span>›</span><span>Kemahasiswaan</span><span>›</span><span>UKM</span></div>
        <div class="ukm-kicker">KEMAHASISWAAN FIKES</div>
        <h1>Temukan ruang untuk <em>berkarya & berkembang</em></h1>
        <p>Unit Kegiatan Mahasiswa menjadi ruang bagi mahasiswa FIKES untuk mengembangkan minat, bakat, kreativitas,
          kepemimpinan, keterampilan, jejaring, dan pengalaman di luar kegiatan akademik.</p>
        <div class="ukm-toolbar"><span class="ukm-pill">🎯 Minat & Bakat</span><span class="ukm-pill">🏆
            Prestasi</span><span class="ukm-pill">🤝 Organisasi</span><span class="ukm-pill">📸 Kegiatan &
            Dokumentasi</span></div>
      </div>
    </section>

    <section class="ukm-main">
      <div class="container">
        <div class="ukm-heading"><span class="ukm-label">UNIT KEGIATAN MAHASISWA</span>
          <h2>Beragam UKM untuk Mengembangkan Potensi</h2>
          <p>Pilih salah satu UKM untuk melihat profil, kepengurusan, anggota, kegiatan, dan dokumentasi secara lebih
            lengkap.</p>
        </div>
        <div class="ukm-grid">
          <?php foreach ($ukmList as $ukm): ?>
            <article class="ukm-card">
              <div class="ukm-card-image"><span class="ukm-icon"><?= e($ukm['icon']) ?></span><img
                  src="<?= e($ukm['logo']) ?>" alt="Logo <?= e($ukm['nama']) ?>" loading="lazy"></div>
              <div class="ukm-card-body">
                <span class="ukm-category"><?= e($ukm['kategori']) ?></span>
                <h3><?= e($ukm['nama']) ?></h3>
                <p><?= e($ukm['deskripsi']) ?></p>
                <div class="ukm-detail"><a href="/fikes/kemahasiswaan/ukm/<?= e($ukm['slug']) ?>">Lihat Detail
                    <span>→</span></a></div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <div class="ukm-info">
          <div><span class="ukm-label">KEHIDUPAN KAMPUS</span>
            <h3>Belajar tidak hanya berlangsung di ruang kelas</h3>
            <p>Kegiatan UKM membantu mahasiswa mendapatkan pengalaman kolaborasi, kepemimpinan, komunikasi,
              kreativitas, dan pengembangan diri melalui aktivitas yang sesuai dengan minat masing-masing.</p>
          </div>
          <div class="ukm-mini">
            <div class="ukm-mini-item"><strong>16 UKM</strong><span>Berbagai bidang kegiatan mahasiswa</span></div>
            <div class="ukm-mini-item"><strong>Minat & Bakat</strong><span>Olahraga, seni, sosial, media, dan
                lainnya</span></div>
            <div class="ukm-mini-item"><strong>Kegiatan</strong><span>Program rutin dan kegiatan pengembangan</span>
            </div>
            <div class="ukm-mini-item"><strong>Dokumentasi</strong><span>Galeri kegiatan setiap organisasi</span>
            </div>
          </div>
        </div>
        <div class="ukm-cta">
          <h3>Ingin mengenal kegiatan mahasiswa lebih dekat?</h3>
          <p>Pilih UKM di atas dan lihat informasi lengkap organisasi, kepengurusan, anggota, kegiatan, serta
            dokumentasinya.</p>
        </div>
      </div>
    </section>
  </main>

  <?php require_once __DIR__ . '/../menu/footer.php'; ?>
  <script src="/fikes/assets/js/main.js"></script>
</body>

</html>
