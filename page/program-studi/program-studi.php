<?php
require_once __DIR__ . '/../../admin/config/database.php';

if (!function_exists('e')) {
  function e($value)
  {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
  }
}

/* =========================================================
   FILTER & SEARCH
   ========================================================= */
$filter = trim($_GET['jenjang'] ?? '');
$search = trim($_GET['search'] ?? '');

$where = ["status = 'aktif'"];
$params = [];

if ($filter !== '') {
  $where[] = "jenjang = :jenjang";
  $params[':jenjang'] = $filter;
}

if ($search !== '') {
  $where[] = "(nama LIKE :search_nama OR kode_prodi LIKE :search_kode)";
  $params[':search_nama'] = '%' . $search . '%';
  $params[':search_kode'] = '%' . $search . '%';
}

/* =========================================================
   QUERY PROGRAM STUDI
   ========================================================= */
$sql = "
    SELECT
        id,
        kode_prodi,
        nama,
        jenjang,
        gelar,
        deskripsi,
        akreditasi,
        foto,
        brosur
    FROM program_studi
    WHERE " . implode(' AND ', $where) . "
    ORDER BY nama ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$programs = $stmt->fetchAll();

/* =========================================================
   DAFTAR JENJANG
   ========================================================= */
$jenjangList = $pdo->query("
    SELECT DISTINCT jenjang
    FROM program_studi
    WHERE status = 'aktif'
      AND jenjang IS NOT NULL
      AND jenjang <> ''
    ORDER BY jenjang
")->fetchAll(PDO::FETCH_COLUMN);

/* Jumlah program */
$totalProgram = count($programs);
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Program Studi | FIKES</title>

  <meta name="description"
    content="Daftar Program Studi Fakultas Ilmu Kesehatan. Temukan program pendidikan yang sesuai dengan minat dan tujuan karier Anda.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="/fikes/assets/css/style.css">

  <style>
    /* =========================================================
           PAGE HERO
        ========================================================= */
    .page-hero {
      position: relative;
      padding: 100px 0 105px;
      overflow: hidden;
      background:
        radial-gradient(circle at 85% 20%, rgba(8, 127, 91, 0.14), transparent 30%),
        linear-gradient(135deg, #f5fcf9, #ffffff 60%, #edf8f4);
    }

    .page-hero::before {
      content: "";
      position: absolute;
      width: 400px;
      height: 400px;
      border-radius: 50%;
      border: 70px solid rgba(8, 127, 91, 0.04);
      right: -130px;
      top: -130px;
    }

    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 800px;
    }

    .breadcrumb {
      display: flex;
      align-items: center;
      gap: 9px;
      margin-bottom: 25px;
      font-size: 13px;
    }

    .breadcrumb a {
      color: var(--primary);
      font-weight: 600;
    }

    .breadcrumb span {
      color: #9aa9a3;
    }

    .hero-label,
    .eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 15px;
      border-radius: 50px;
      background: var(--primary-light);
      color: var(--primary);
      font-size: 12px;
      font-weight: 700;
      margin-bottom: 18px;
    }

    .hero-label::before,
    .eyebrow::before {
      content: "";
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: var(--secondary);
    }

    .page-hero h1 {
      font-size: clamp(40px, 5vw, 62px);
      letter-spacing: -1.5px;
      margin-bottom: 18px;
    }

    .page-hero h1 span {
      color: var(--primary);
    }

    .page-hero p {
      max-width: 690px;
      font-size: 17px;
    }

    /* =========================================================
           PROGRAM SECTION
        ========================================================= */
    .program-section {
      padding: 95px 0;
      background: white;
    }

    .section-heading {
      max-width: 720px;
      margin: 0 auto 40px;
      text-align: center;
    }

    .section-heading h2 {
      font-size: clamp(32px, 4vw, 45px);
      margin-bottom: 15px;
    }

    .section-heading p {
      font-size: 15px;
    }

    /* =========================================================
           FILTER
        ========================================================= */
    .filter-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 20px;
      margin-bottom: 35px;
      padding: 14px;
      border: 1px solid var(--border);
      border-radius: 18px;
      background: var(--light);
    }

    .filter-tabs {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .filter-tabs a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-height: 40px;
      padding: 0 15px;
      border-radius: 10px;
      color: var(--text);
      font-size: 12px;
      font-weight: 700;
      transition: var(--transition);
    }

    .filter-tabs a:hover {
      color: var(--primary);
      background: var(--primary-light);
    }

    .filter-tabs a.active {
      color: white;
      background: var(--primary);
      box-shadow: 0 8px 20px rgba(8, 127, 91, 0.18);
    }

    .search-box {
      width: 280px;
      flex-shrink: 0;
      position: relative;
    }

    .search-box span {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--primary);
      font-size: 18px;
      pointer-events: none;
    }

    .search-box input {
      width: 100%;
      height: 42px;
      padding: 0 14px 0 42px;
      border: 1px solid var(--border);
      border-radius: 10px;
      outline: none;
      background: white;
      color: var(--dark);
      font-size: 12px;
      transition: var(--transition);
    }

    .search-box input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(8, 127, 91, 0.08);
    }

    /* =========================================================
           PROGRAM GRID / CARD
        ========================================================= */
    .program-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 22px;
    }

    /* =========================================================
           PROGRAM CARD - MODERN / LEBIH HIDUP
        ========================================================= */
    .program-card {
      position: relative;
      overflow: hidden;
      border: 1px solid rgba(229, 236, 233, .95);
      border-radius: 24px;
      background: #fff;
      box-shadow: 0 12px 35px rgba(18, 55, 42, .055);
      transition: transform .35s ease, box-shadow .35s ease, border-color .35s ease;
    }

    .program-card::before {
      content: "";
      position: absolute;
      left: 0;
      right: 0;
      top: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--primary), #21b887, var(--secondary));
      transform: scaleX(0);
      transform-origin: left;
      transition: transform .35s ease;
      z-index: 8;
    }

    .program-card:hover {
      transform: translateY(-10px);
      border-color: rgba(8, 127, 91, .18);
      box-shadow: 0 25px 60px rgba(18, 55, 42, .13);
    }

    .program-card:hover::before {
      transform: scaleX(1);
    }

    .program-image {
      position: relative;
      height: 235px;
      overflow: hidden;
      background:
        radial-gradient(circle at 15% 15%, rgba(244, 185, 66, .25), transparent 25%),
        radial-gradient(circle at 85% 90%, rgba(255, 255, 255, .13), transparent 30%),
        linear-gradient(135deg, #087f5b, #056044);
    }

    .program-image::after {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg,
          rgba(18, 55, 42, .02) 30%,
          rgba(18, 55, 42, .72) 100%);
      pointer-events: none;
    }

    .program-image::before {
      content: "";
      position: absolute;
      width: 170px;
      height: 170px;
      border: 35px solid rgba(255, 255, 255, .055);
      border-radius: 50%;
      right: -70px;
      top: -85px;
      z-index: 2;
      transition: transform .5s ease;
    }

    .program-card:hover .program-image::before {
      transform: scale(1.15) rotate(12deg);
    }

    .program-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform .6s ease, filter .4s ease;
    }

    .program-card:hover .program-image img {
      transform: scale(1.07);
      filter: saturate(1.08);
    }

    .image-placeholder {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: rgba(255, 255, 255, .96);
      font-family: "Plus Jakarta Sans", sans-serif;
      font-size: 74px;
      font-weight: 800;
      text-shadow: 0 10px 30px rgba(0, 0, 0, .15);
    }

    .jenjang-badge {
      position: absolute;
      top: 16px;
      right: 16px;
      z-index: 5;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 12px;
      border: 1px solid rgba(255, 255, 255, .2);
      border-radius: 999px;
      background: rgba(18, 55, 42, .84);
      color: white;
      font-size: 10px;
      font-weight: 800;
      backdrop-filter: blur(10px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, .12);
    }

    .jenjang-badge::before {
      content: "●";
      color: var(--secondary);
      font-size: 7px;
    }

    .program-body {
      position: relative;
      padding: 26px 25px 23px;
    }

    .program-body .code {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 9px;
      color: var(--primary);
      font-size: 10px;
      font-weight: 800;
      letter-spacing: 1.35px;
      text-transform: uppercase;
    }

    .program-body .code::before {
      content: "";
      width: 20px;
      height: 2px;
      border-radius: 99px;
      background: var(--secondary);
    }

    .program-body h3 {
      min-height: 50px;
      font-size: 19px;
      margin-bottom: 9px;
      letter-spacing: -.25px;
    }

    .program-body>p {
      min-height: 68px;
      margin-bottom: 17px;
      color: var(--text);
      font-size: 12px;
      line-height: 1.75;
      display: -webkit-box;
      -webkit-line-clamp: 4;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .meta-row {
      display: flex;
      flex-wrap: wrap;
      gap: 7px;
      min-height: 30px;
      margin-bottom: 20px;
    }

    .meta-row span {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 7px 10px;
      border: 1px solid #dcefe9;
      border-radius: 9px;
      background: linear-gradient(135deg, #f0faf7, #e7f7f1);
      color: var(--primary);
      font-size: 10px;
      font-weight: 800;
    }

    .detail-btn {
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      padding: 12px 14px;
      border-radius: 11px;
      background: var(--dark);
      color: white;
      font-size: 12px;
      font-weight: 800;
      transition: var(--transition);
    }

    .detail-btn::before {
      content: "";
      position: absolute;
      width: 0;
      height: 100%;
      left: 0;
      top: 0;
      background: linear-gradient(90deg, var(--primary), #13a878);
      transition: width .35s ease;
      z-index: 0;
    }

    .detail-btn:hover::before {
      width: 100%;
    }

    .detail-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(8, 127, 91, .18);
    }

    .detail-btn span {
      position: relative;
      z-index: 1;
      font-size: 16px;
    }

    .detail-btn span:first-child {
      font-size: 12px;
    }


    .program-card {
      animation: cardReveal .55s ease both;
    }

    .program-card:nth-child(2) {
      animation-delay: .06s;
    }

    .program-card:nth-child(3) {
      animation-delay: .12s;
    }

    .program-card:nth-child(4) {
      animation-delay: .18s;
    }

    .program-card:nth-child(5) {
      animation-delay: .24s;
    }

    .program-card:nth-child(6) {
      animation-delay: .30s;
    }

    @keyframes cardReveal {
      from {
        opacity: 0;
        transform: translateY(18px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .result-info {
      margin: 0 0 18px;
      color: var(--text);
      font-size: 12px;
    }

    .empty-state {
      padding: 55px 25px;
      border: 1px dashed var(--border);
      border-radius: 18px;
      background: var(--light);
      text-align: center;
      color: var(--text);
      font-size: 14px;
    }

    /* =========================================================
           CTA
        ========================================================= */
    .cta-section {
      padding: 0 0 95px;
    }

    .cta {
      position: relative;
      overflow: hidden;
      padding: 65px;
      border-radius: 30px;
      background: linear-gradient(135deg, var(--dark), var(--primary-dark));
      color: white;
      text-align: center;
    }

    .cta::before {
      content: "";
      position: absolute;
      width: 350px;
      height: 350px;
      border: 70px solid rgba(255, 255, 255, 0.03);
      border-radius: 50%;
      left: -130px;
      bottom: -190px;
    }

    .cta::after {
      content: "";
      position: absolute;
      width: 220px;
      height: 220px;
      border: 45px solid rgba(244, 185, 66, 0.05);
      border-radius: 50%;
      right: -80px;
      top: -100px;
    }

    .cta-content {
      position: relative;
      z-index: 2;
    }

    .cta .eyebrow {
      background: rgba(255, 255, 255, .1);
      color: #d9eee5;
    }

    .cta h2 {
      color: white;
      font-size: clamp(30px, 4vw, 42px);
      margin-bottom: 12px;
    }

    .cta p {
      max-width: 620px;
      margin: 0 auto 25px;
      color: #d6e9e2;
    }

    .cta .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 14px 23px;
      border-radius: 11px;
      background: var(--secondary);
      color: var(--dark);
      font-size: 13px;
      font-weight: 800;
      transition: var(--transition);
    }

    .cta .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 30px rgba(244, 185, 66, 0.2);
    }

    /* =========================================================
           RESPONSIVE
        ========================================================= */
    @media (max-width: 1100px) {
      .nav-link {
        padding: 0 8px;
        font-size: 12px;
      }

      .nav-cta {
        display: none;
      }

      .program-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 900px) {
      .topbar {
        display: none;
      }

      .nav-inner {
        min-height: 72px;
      }

      .menu-toggle {
        display: block;
      }

      .nav-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        display: none;
        background: white;
        border-top: 1px solid var(--border);
        padding: 10px 20px 25px;
        max-height: calc(100vh - 72px);
        overflow-y: auto;
        box-shadow: 0 20px 30px rgba(0, 0, 0, .08);
      }

      .nav-menu.active {
        display: block;
      }

      .nav-item {
        border-bottom: 1px solid #edf2f0;
      }

      .nav-link {
        min-height: 48px;
        padding: 0;
        justify-content: space-between;
        width: 100%;
      }

      .dropdown {
        position: static;
        width: 100%;
        display: none;
        opacity: 1;
        visibility: visible;
        transform: none;
        box-shadow: none;
        border: 0;
        padding: 0 0 8px 15px;
      }

      .nav-item.open>.dropdown {
        display: block;
      }

      .dropdown-item.open>.dropdown {
        display: block;
      }

      .dropdown-link {
        min-height: 42px;
      }

      .page-hero {
        padding: 80px 0 90px;
      }

      .filter-bar {
        align-items: stretch;
        flex-direction: column;
      }

      .search-box {
        width: 100%;
      }

      .footer-main {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 650px) {
      .container {
        width: min(100% - 28px, 1180px);
      }

      .page-hero {
        padding: 60px 0 75px;
      }

      .page-hero h1 {
        font-size: 40px;
        letter-spacing: -1px;
      }

      .page-hero p {
        font-size: 15px;
      }

      .program-section {
        padding: 70px 0;
      }

      .program-grid {
        grid-template-columns: 1fr;
      }

      .filter-tabs {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
      }

      .filter-tabs a {
        width: 100%;
      }

      .program-image {
        height: 210px;
      }

      .cta-section {
        padding-bottom: 70px;
      }

      .cta {
        padding: 45px 25px;
      }

      .footer-main {
        grid-template-columns: 1fr;
        gap: 30px;
      }

      .footer-bottom {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
      }

      .map-card {
        height: 240px;
      }

      .map-overlay {
        flex-direction: column;
        align-items: stretch;
      }

      .map-info {
        width: 100%;
      }

      .map-direction {
        width: 100%;
      }
    }
  </style>
</head>

<body>

  <!-- TOPBAR REUSABLE -->
  <?php require_once __DIR__ . '/../menu/topbar.php'; ?>

  <!-- NAVBAR REUSABLE -->
  <?php require_once __DIR__ . '/../menu/navbar.php'; ?>
  <!-- =========================================================
     HERO
========================================================= -->
  <section class="page-hero">
    <div class="container hero-content">

      <div class="breadcrumb">
        <a href="/fikes/">⌂ Beranda</a>
        <span>›</span>
        <span>Program Studi</span>
      </div>

      <span class="hero-label">PENDIDIKAN FIKES</span>

      <h1>
        Program <span>Studi</span>
      </h1>

      <p>
        Kenali program pendidikan FIKES dan temukan program studi
        yang sesuai dengan minat serta tujuan karier Anda.
      </p>

    </div>
  </section>

  <!-- =========================================================
     DAFTAR PROGRAM STUDI
========================================================= -->
  <main class="program-section" id="daftar-prodi">
    <div class="container">

      <div class="section-heading">
        <span class="eyebrow">PILIH PROGRAM ANDA</span>

        <h2>Program Studi</h2>

        <p>
          Pilih program studi untuk melihat informasi lengkap mengenai
          profil, kurikulum, capaian pembelajaran, fasilitas,
          dan informasi akademik lainnya.
        </p>
      </div>

      <form class="filter-bar" method="get" action="/fikes/program-studi">

        <div class="filter-tabs">
          <a class="<?= $filter === '' ? 'active' : '' ?>" href="/fikes/program-studi#daftar-prodi">
            Semua
          </a>

          <?php foreach ($jenjangList as $j): ?>
            <a class="<?= $filter === $j ? 'active' : '' ?>" href="?jenjang=<?= urlencode($j) ?>#daftar-prodi">
              <?= e($j) ?>
            </a>
          <?php endforeach; ?>
        </div>

        <div class="search-box">
          <span>⌕</span>

          <input type="text" name="search" value="<?= e($search) ?>" placeholder="Cari program studi..."
            autocomplete="off">

          <?php if ($filter !== ''): ?>
            <input type="hidden" name="jenjang" value="<?= e($filter) ?>">
          <?php endif; ?>
        </div>

      </form>

      <p class="result-info">
        Menampilkan <strong><?= $totalProgram ?></strong> program studi
        <?= $filter !== '' ? 'pada jenjang <strong>' . e($filter) . '</strong>' : '' ?>
        <?= $search !== '' ? ' untuk pencarian <strong>"' . e($search) . '"</strong>' : '' ?>.
      </p>

      <?php if (!$programs): ?>

        <div class="empty-state">
          Program studi yang Anda cari belum tersedia.
        </div>

      <?php else: ?>

        <div class="program-grid">

          <?php foreach ($programs as $p): ?>

            <?php
            $foto = !empty($p['foto'])
              ? '/fikes/admin/uploads/program-studi/' . rawurlencode($p['foto'])
              : '';
            ?>

            <article class="program-card">

              <div class="program-image">

                <?php if ($foto): ?>

                  <img src="<?= e($foto) ?>" alt="Foto <?= e($p['nama']) ?>" loading="lazy">

                <?php else: ?>

                  <div class="image-placeholder">
                    F
                  </div>

                <?php endif; ?>

                <span class="jenjang-badge">
                  <?= e($p['jenjang']) ?>
                </span>

              </div>

              <div class="program-body">

                <div class="code">
                  <?= e($p['kode_prodi']) ?>
                </div>

                <h3>
                  <?= e($p['nama']) ?>
                </h3>

                <p>
                  <?= e($p['deskripsi']) ?>
                </p>

                <div class="meta-row">

                  <?php if (!empty($p['gelar'])): ?>
                    <span>🎓 <?= e($p['gelar']) ?></span>
                  <?php endif; ?>

                  <?php if (!empty($p['akreditasi'])): ?>
                    <span>✓ <?= e($p['akreditasi']) ?></span>
                  <?php endif; ?>

                </div>

                <a class="detail-btn" href="/fikes/program-studi/<?= rawurlencode($p['kode_prodi']) ?>">
                  <span>Lihat Detail</span>
                  <span>→</span>
                </a>

              </div>

            </article>

          <?php endforeach; ?>

        </div>

      <?php endif; ?>

    </div>
  </main>

  <!-- =========================================================
     CTA
========================================================= -->
  <section class="cta-section">
    <div class="container">

      <div class="cta">
        <div class="cta-content">

          <span class="eyebrow">BERSAMA FIKES</span>

          <h2>
            Bangun Masa Depan Bersama Kami
          </h2>

          <p>
            Temukan program studi yang mendukung perjalanan
            akademik dan profesional Anda.
          </p>

          <a href="#daftar-prodi" class="btn">
            Lihat Program Studi →
          </a>

        </div>
      </div>

    </div>
  </section>

  <!-- =========================================================
     FOOTER
========================================================= -->
  <?php require_once __DIR__ . '/../menu/footer.php'; ?>


</body>

</html>
