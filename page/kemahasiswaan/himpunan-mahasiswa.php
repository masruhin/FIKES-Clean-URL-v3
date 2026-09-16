<?php
$page_title = 'Himpunan Mahasiswa';

if (!function_exists('e')) {
  function e($value)
  {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
  }
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($page_title) ?> | FIKES - Fakultas Ilmu Kesehatan</title>
  <meta name="description"
    content="Informasi kemahasiswaan Fakultas Ilmu Kesehatan, himpunan mahasiswa, organisasi mahasiswa, dan unit kegiatan mahasiswa FIKES.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="/fikes/assets/css/style.css">
  <style>
    :root {
      --km-primary: #087f5b;
      --km-dark: #12372a;
      --km-text: #52635d;
      --km-light: #f7faf9;
      --km-border: #e5ece9;
      --km-soft: #e7f7f1;
      --km-gold: #f4b942;
    }

    .km-hero {
      position: relative;
      overflow: hidden;
      padding: 64px 0 72px;
      background: radial-gradient(circle at 90% 18%, rgba(255, 255, 255, .20), transparent 28%), linear-gradient(120deg, #00685a 0%, #008f78 55%, #8ad0c1 150%);
      color: #fff;
    }

    .km-hero:after {
      content: "";
      position: absolute;
      width: 360px;
      height: 360px;
      right: -130px;
      bottom: -220px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .08);
    }

    .km-breadcrumb {
      display: flex;
      align-items: center;
      gap: 9px;
      flex-wrap: wrap;
      font-size: 13px;
      margin-bottom: 22px;
      color: rgba(255, 255, 255, .82);
      position: relative;
      z-index: 1;
    }

    .km-breadcrumb a {
      color: #fff;
      text-decoration: none;
      font-weight: 700;
    }

    .km-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 1.7px;
      text-transform: uppercase;
      color: #dff7ef;
      position: relative;
      z-index: 1;
    }

    .km-hero h1 {
      position: relative;
      z-index: 1;
      font-family: "Plus Jakarta Sans", sans-serif;
      font-size: clamp(34px, 5vw, 58px);
      line-height: 1.1;
      color: #fff;
      margin: 12px 0 16px;
      max-width: 850px;
    }

    .km-hero h1 span {
      color: #f4b942;
    }

    .km-hero p {
      position: relative;
      z-index: 1;
      max-width: 760px;
      margin: 0;
      color: rgba(255, 255, 255, .88);
      font-size: 16px;
      line-height: 1.8;
    }

    .km-content {
      padding: 82px 0 90px;
      background: #fff;
    }

    .km-section-head {
      display: flex;
      justify-content: space-between;
      align-items: end;
      gap: 25px;
      margin-bottom: 30px;
    }

    .km-label {
      display: block;
      color: var(--km-primary);
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      margin-bottom: 8px;
    }

    .km-section-head h2 {
      font-family: "Plus Jakarta Sans", sans-serif;
      color: var(--km-dark);
      font-size: clamp(26px, 3.2vw, 36px);
      margin: 0 0 8px;
    }

    .km-section-head p {
      max-width: 720px;
      margin: 0;
      color: #687a74;
      font-size: 14px;
      line-height: 1.8;
    }

    .km-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 20px;
    }

    .km-card {
      background: #fff;
      border: 1px solid var(--km-border);
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 12px 35px rgba(18, 55, 42, .07);
      transition: .25s ease;
    }

    .km-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 45px rgba(18, 55, 42, .12);
    }

    .km-card-image {
      height: 215px;
      background: linear-gradient(180deg, #f5faf8, #edf6f2);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 22px;
      border-bottom: 1px solid #edf2f0;
    }

    .km-card-image img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .km-card-body {
      padding: 22px;
    }

    .km-badge {
      display: inline-flex;
      padding: 5px 9px;
      border-radius: 999px;
      background: var(--km-soft);
      color: var(--km-primary);
      font-size: 10px;
      font-weight: 800;
      letter-spacing: .7px;
      text-transform: uppercase;
      margin-bottom: 9px;
    }

    .km-card h3 {
      font-family: "Plus Jakarta Sans", sans-serif;
      font-size: 19px;
      color: var(--km-dark);
      margin: 0 0 8px;
    }

    .km-card p {
      margin: 0;
      color: #687a74;
      font-size: 13px;
      line-height: 1.7;
    }

    .km-card-body {
      display: flex;
      flex-direction: column;
      height: calc(100% - 215px);
    }

    .km-card-body p {
      flex: 1;
    }

    .km-detail-btn {
      display: inline-flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-top: 18px;
      padding: 11px 14px;
      border-radius: 12px;
      background: var(--km-dark);
      color: #fff;
      text-decoration: none;
      font-size: 12px;
      font-weight: 800;
      transition: .2s ease;
    }

    .km-detail-btn:hover {
      background: var(--km-primary);
      transform: translateY(-1px);
    }

    .km-detail-btn span {
      font-size: 16px;
      line-height: 1;
    }

    .km-mini {
      display: flex;
      flex-direction: column;
    }

    .km-mini-link {
      display: inline-block;
      margin-top: 10px;
      color: var(--km-primary);
      font-size: 12px;
      font-weight: 800;
      text-decoration: none;
    }

    .km-mini-link:hover {
      text-decoration: underline;
    }

    .km-info {
      margin-top: 55px;
      padding: 34px;
      border-radius: 20px;
      background: #f7faf9;
      border: 1px solid var(--km-border);
      display: grid;
      grid-template-columns: 1.1fr .9fr;
      gap: 28px;
      align-items: center;
    }

    .km-info h3 {
      font-family: "Plus Jakarta Sans", sans-serif;
      color: var(--km-dark);
      font-size: 25px;
      margin: 0 0 10px;
    }

    .km-info p {
      margin: 0;
      color: #687a74;
      line-height: 1.8;
      font-size: 14px;
    }

    .km-mini-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }

    .km-mini {
      padding: 17px;
      border: 1px solid #dfeae6;
      border-radius: 14px;
      background: #fff;
    }

    .km-mini strong {
      display: block;
      color: var(--km-dark);
      font-size: 14px;
      margin-bottom: 4px;
    }

    .km-mini span {
      color: #71817c;
      font-size: 12px;
      line-height: 1.5;
    }

    .km-cta {
      margin-top: 22px;
      background: var(--km-dark);
      border-radius: 20px;
      padding: 34px;
      color: #fff;
      text-align: center;
    }

    .km-cta h3 {
      font-family: "Plus Jakarta Sans", sans-serif;
      color: #fff;
      font-size: 25px;
      margin: 0 0 8px;
    }

    .km-cta p {
      margin: 0;
      color: #dce9e4;
      font-size: 14px;
      line-height: 1.7;
    }

    @media(max-width:900px) {
      .km-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .km-info {
        grid-template-columns: 1fr;
      }
    }

    @media(max-width:600px) {
      .km-hero {
        padding: 52px 0 58px;
      }

      .km-content {
        padding: 60px 0 65px;
      }

      .km-grid {
        grid-template-columns: 1fr;
      }

      .km-card-image {
        height: 225px;
      }

      .km-section-head {
        display: block;
      }

      .km-info {
        padding: 24px;
      }

      .km-mini-grid {
        grid-template-columns: 1fr;
      }

      .km-hero p {
        font-size: 15px;
      }
    }
  </style>
</head>

<body>
  <?php require_once __DIR__ . '/../menu/topbar.php'; ?>
  <?php require_once __DIR__ . '/../menu/navbar.php'; ?>

  <main>
    <section class="km-hero">
      <div class="container">
        <div class="km-breadcrumb"><a
            href="/fikes/">Beranda</a><span>›</span><span>Kemahasiswaan</span><span>›</span><span>Himpunan
            Mahasiswa</span></div>
        <div class="km-eyebrow">KEMAHASISWAAN FIKES</div>
        <h1>Himpunan <span>Mahasiswa FIKES</span></h1>
        <p>Himpunan mahasiswa menjadi ruang bagi mahasiswa untuk berorganisasi, menyampaikan aspirasi, mengembangkan
          potensi, dan membangun kolaborasi selama menempuh pendidikan di Fakultas Ilmu Kesehatan.</p>
      </div>
    </section>

    <section class="km-content">
      <div class="container">
        <div class="km-section-head">
          <div><span class="km-label">ORGANISASI MAHASISWA</span>
            <h2>Daftar Himpunan Mahasiswa</h2>
            <p>Kenali himpunan mahasiswa yang menjadi bagian dari kehidupan kemahasiswaan FIKES.</p>
          </div>
        </div>
        <div class="km-grid">
          <article class="km-card">
            <div class="km-card-image"><img src="/fikes/vendor/kemahasiswaan/himpunan/himafarda.jpg" alt="HIMAFARDA"
                loading="lazy"></div>
            <div class="km-card-body"><span class="km-badge">Himpunan Mahasiswa</span>
              <h3>HIMAFARDA</h3>
              <p>Wadah mahasiswa Farmasi untuk mengembangkan organisasi, aspirasi, kreativitas, dan kegiatan
                kemahasiswaan.</p><a class="km-detail-btn" href="/fikes/kemahasiswaan/himpunan/himafarda">Lihat Detail
                <span>→</span></a>
            </div>
          </article>
          <article class="km-card">
            <div class="km-card-image"><img src="/fikes/vendor/kemahasiswaan/himpunan/himasada.jpg" alt="HIMASADA"
                loading="lazy"></div>
            <div class="km-card-body"><span class="km-badge">Himpunan Mahasiswa</span>
              <h3>HIMASADA</h3>
              <p>Ruang pengembangan potensi, kebersamaan, kepemimpinan, dan kolaborasi mahasiswa di lingkungan FIKES.
              </p><a class="km-detail-btn" href="/fikes/kemahasiswaan/himpunan/himasada">Lihat Detail
                <span>→</span></a>
            </div>
          </article>
          <article class="km-card">
            <div class="km-card-image"><img src="/fikes/vendor/kemahasiswaan/himpunan/himadika.png" alt="HIMADIKA"
                loading="lazy"></div>
            <div class="km-card-body"><span class="km-badge">Himpunan Mahasiswa</span>
              <h3>HIMADIKA</h3>
              <p>Wadah mahasiswa Keperawatan dalam kegiatan organisasi, pengembangan diri, dan kontribusi kepada
                lingkungan kampus.</p><a class="km-detail-btn" href="/fikes/kemahasiswaan/himpunan/himadika">Lihat
                Detail <span>→</span></a>
            </div>
          </article>
          <article class="km-card">
            <div class="km-card-image"><img src="/fikes/vendor/kemahasiswaan/himpunan/himika.png" alt="HIMIKA"
                loading="lazy"></div>
            <div class="km-card-body"><span class="km-badge">Himpunan Mahasiswa</span>
              <h3>HIMIKA</h3>
              <p>Organisasi mahasiswa yang mendukung aktivitas kemahasiswaan, aspirasi, dan pengembangan kepemimpinan.
              </p><a class="km-detail-btn" href="/fikes/kemahasiswaan/himpunan/himika">Lihat Detail <span>→</span></a>
            </div>
          </article>
          <article class="km-card">
            <div class="km-card-image"><img src="/fikes/vendor/kemahasiswaan/himpunan/himadan.jpg" alt="HIMADAN"
                loading="lazy"></div>
            <div class="km-card-body"><span class="km-badge">Himpunan Mahasiswa</span>
              <h3>HIMADAN</h3>
              <p>Wadah mahasiswa untuk mengembangkan kreativitas, komunikasi, solidaritas, dan kegiatan sosial.</p><a
                class="km-detail-btn" href="/fikes/kemahasiswaan/himpunan/himadan">Lihat Detail <span>→</span></a>
            </div>
          </article>
        </div>

        <div class="km-info">
          <div><span class="km-label">ORGANISASI KAMPUS</span>
            <h3>BEM dan DPM FIKES</h3>
            <p>Selain himpunan mahasiswa, terdapat organisasi tingkat fakultas yang mendukung koordinasi kegiatan,
              penyampaian aspirasi, dan pengembangan kehidupan kemahasiswaan.</p>
          </div>
          <div class="km-mini-grid">
            <div class="km-mini"><strong>BEM FIKES</strong><span>Badan Eksekutif Mahasiswa sebagai wadah pelaksanaan
                program dan kegiatan mahasiswa.</span><a class="km-mini-link"
                href="/fikes/kemahasiswaan/himpunan/bem-fikes">Detail BEM →</a></div>
            <div class="km-mini"><strong>DPM</strong><span>Dewan Perwakilan Mahasiswa sebagai ruang perwakilan dan
                penyampaian aspirasi mahasiswa.</span><a class="km-mini-link"
                href="/fikes/kemahasiswaan/himpunan/dpm-fikes">Detail DPM →</a></div>
          </div>
        </div>

        <div class="km-cta">
          <h3>Aktif Berorganisasi, Berkarya, dan Berkembang</h3>
          <p>Partisipasi dalam organisasi membantu mahasiswa memperoleh pengalaman, membangun kepemimpinan, dan
            memperluas kolaborasi.</p>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <div class="container footer-main">
      <div class="footer-brand">
        <div class="logo footer-logo">
          <div class="logo-icon">F</div>

          <div class="logo-text">
            <strong style="color: white"> FIKES </strong>

            <small> FAKULTAS ILMU KESEHATAN </small>
          </div>
        </div>

        <p>
          Membangun generasi kesehatan yang profesional, berintegritas,
          inovatif, dan berorientasi kepada masyarakat.
        </p>
      </div>

      <div>
        <h4 class="footer-title">Tentang FIKES</h4>

        <div class="footer-links">
          <a href="#"> Visi Misi </a>

          <a href="#"> Struktur Organisasi </a>

          <a href="#"> Akreditasi </a>

          <a href="#"> Daftar Dosen </a>
        </div>
      </div>

      <div>
        <h4 class="footer-title">Program Studi</h4>

        <div class="footer-links">
          <a href="#"> Profesi Ners </a>

          <a href="#"> Ilmu Keperawatan </a>

          <a href="#"> Farmasi </a>

          <a href="#"> Kebidanan </a>

          <a href="#"> K3 </a>
        </div>
      </div>

      <div>
        <h4 class="footer-title">Informasi</h4>

        <div class="footer-links">
          <a href="/fikes/akademik"> Akademik </a>

          <a href="#"> Kemahasiswaan </a>

          <a href="#"> Pelayanan FIKES </a>

          <a href="#"> Survey </a>
        </div>
      </div>

      <!--MAP PETA-->
      <!-- =========================================================
     LOKASI & PETA
========================================================== -->

      <div class="footer-location">
        <div class="location-header">
          <div class="location-icon">
            <i class="fa-solid fa-location-dot"></i>
          </div>

          <div>
            <h3>Lokasi Kampus</h3>

            <p>Fakultas Ilmu Kesehatan</p>
          </div>
        </div>

        <!-- PETA -->

        <div class="map-card">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.1515727139526!2d109.11806027499709!3d-6.991421893009626!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6fbef42471658d%3A0x883656d1325ef066!2sUniversitas%20Bhamada%20Slawi!5e0!3m2!1sid!2sid!4v1787544396003!5m2!1sid!2sid"
            width="600" height="450" style="border: 0" allowfullscreen="" loading="lazy"
            referrerpolicy="strict-origin-when-cross-origin" title="Lokasi Fakultas Ilmu Kesehatan" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade" allowfullscreen>
          </iframe>

          <div class="map-overlay">
            <div class="map-info">
              <div class="map-info-icon">
                <i class="fa-solid fa-location-dot"></i>
              </div>

              <div>
                <strong> Fakultas Ilmu Kesehatan </strong>

                <span> Lihat lokasi kampus </span>
              </div>
            </div>

            <a href="#" target="_blank" class="map-direction">
              <i class="fa-solid fa-diamond-turn-right"></i>

              Petunjuk Arah
            </a>
          </div>
        </div>

        <!-- ALAMAT -->

        <div class="footer-contact location-contact">
          <i class="fa-solid fa-location-dot"></i>

          <span>
            Alamat Fakultas Ilmu Kesehatan, silakan sesuaikan dengan alamat
            kampus.
          </span>
        </div>

        <div class="footer-contact">
          <i class="fa-solid fa-phone"></i>

          <span> Nomor Telepon FIKES </span>
        </div>

        <div class="footer-contact">
          <i class="fa-solid fa-envelope"></i>

          <span> email@fikes.ac.id </span>
        </div>
      </div>
      <!--MAP PETA-->
    </div>

    <div class="container footer-bottom">
      <span>
        © <span id="year"></span> Fakultas Ilmu Kesehatan. All Rights
        Reserved.
      </span>

      <span> Website FIKES </span>
    </div>
  </footer>

  <script src="/fikes/assets/js/main.js"></script>
</body>

</html>
