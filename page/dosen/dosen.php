<?php
require_once __DIR__ . '/../../admin/config/database.php';
if (!function_exists('e')) {
  function e($value)
  {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
  }
}
$programs = ['Keperawatan', 'Kebidanan', 'Farmasi', 'K3'];

// Normalisasi parameter prodi dari URL.
// .htaccess menggunakan [NC], sehingga /dosen/kebidanan menjadi
// prodi=kebidanan. Database menyimpan nilai dengan huruf kapital awal
// (mis. Kebidanan), jadi kita ubah kembali ke nilai kanonik sebelum query.
$prodiMap = [
  'keperawatan' => 'Keperawatan',
  'kebidanan'   => 'Kebidanan',
  'farmasi'     => 'Farmasi',
  'k3'          => 'K3',
];

$prodiInput = trim($_GET['prodi'] ?? '');
$prodiKey = strtolower($prodiInput);
$prodi = $prodiMap[$prodiKey] ?? '';
$search = trim($_GET['search'] ?? '');
$where = [];
$params = [];

if ($prodi !== '') {
  $where[] = 'program_studi=?';
  $params[] = $prodi;
}
if ($search !== '') {
  $where[] = '(nama LIKE ? OR nidn LIKE ? OR jabatan LIKE ? OR email LIKE ?)';
  $like = '%' . $search . '%';
  array_push($params, $like, $like, $like, $like);
}
$sql = 'SELECT id,nidn,nama,program_studi,jabatan,email,foto,status FROM dosen';
if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' ORDER BY nama ASC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$dosen = $stmt->fetchAll();
$total = count($dosen);
?>
<!doctype html>
<html lang="id">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Daftar Dosen | FIKES</title>
    <meta name="description" content="Daftar dosen Fakultas Ilmu Kesehatan FIKES.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap"
      rel="stylesheet">
    <link rel="stylesheet" href="/fikes/assets/css/style.css">
    <style>
    /* =========================================================
   ROOT
========================================================= */


    /* =========================================================
   NAV CTA
========================================================= */

    .nav-cta {
      padding: 12px 19px;

      border-radius: 10px;

      background: var(--primary);

      color: white;

      font-size: 13px;

      font-weight: 700;

      white-space: nowrap;

      transition: var(--transition);
    }

    .nav-cta:hover {
      background: var(--primary-dark);

      transform: translateY(-2px);
    }

    /* =========================================================
   MOBILE MENU
========================================================= */

    .menu-toggle {
      display: none;

      width: 44px;

      height: 44px;

      border: none;

      border-radius: 10px;

      background: var(--primary-light);

      color: var(--primary);

      font-size: 21px;

      cursor: pointer;
    }

    /* =========================================================
   HERO
========================================================= */

    .hero {
      position: relative;

      overflow: hidden;

      padding: 100px 0 105px;

      background:
        radial-gradient(circle at 85% 20%,
          rgba(8, 127, 91, 0.15),
          transparent 30%),
        linear-gradient(135deg, #f4fcf8, #ffffff 60%, #edf8f4);
    }

    .hero::before {
      content: "";

      position: absolute;

      width: 420px;

      height: 420px;

      right: -150px;

      top: -150px;

      border-radius: 50%;

      border: 70px solid rgba(8, 127, 91, 0.04);
    }

    .hero-content {
      position: relative;

      z-index: 2;

      text-align: center;

      max-width: 850px;

      margin: auto;
    }

    .breadcrumb {
      display: flex;

      align-items: center;

      justify-content: center;

      flex-wrap: wrap;

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

    .hero-label {
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

    .hero-dot {
      width: 7px;

      height: 7px;

      border-radius: 50%;

      background: var(--secondary);
    }

    .hero h1 {
      font-size: clamp(40px, 5vw, 60px);

      letter-spacing: -1.5px;

      margin-bottom: 18px;
    }

    .hero h1 span {
      color: var(--primary);
    }

    .hero p {
      max-width: 700px;

      margin: auto;

      font-size: 17px;
    }

    /* =========================================================
   SECTION
========================================================= */

    .section {
      padding: 95px 0;
    }

    .section-header {
      text-align: center;

      max-width: 720px;

      margin: 0 auto 50px;
    }

    .section-label {
      display: inline-flex;

      padding: 8px 15px;

      border-radius: 50px;

      background: var(--primary-light);

      color: var(--primary);

      font-size: 12px;

      font-weight: 700;

      margin-bottom: 15px;
    }

    .section-title {
      font-size: clamp(30px, 4vw, 44px);

      margin-bottom: 15px;
    }

    .section-description {
      font-size: 15px;
    }

    /* =========================================================
   LOGO GRID
========================================================= */

    .logo-grid {
      display: grid;

      grid-template-columns: repeat(3, 1fr);

      gap: 25px;
    }

    /* =========================================================
   LOGO CARD
========================================================= */

    .logo-card {
      position: relative;

      background: white;

      border: 1px solid var(--border);

      border-radius: 22px;

      overflow: hidden;

      box-shadow: 0 10px 35px rgba(18, 55, 42, 0.05);

      transition: var(--transition);
    }

    .logo-card:hover {
      transform: translateY(-8px);

      box-shadow: var(--shadow-hover);

      border-color: transparent;
    }

    /* =========================================================
   LOGO PREVIEW
========================================================= */

    .logo-preview {
      height: 270px;

      display: flex;

      align-items: center;

      justify-content: center;

      padding: 35px;

      background: linear-gradient(135deg, #f7faf9, #edf8f4);

      border-bottom: 1px solid var(--border);
    }

    .logo-preview img {
      max-width: 190px;

      max-height: 190px;

      object-fit: contain;

      transition: transform 0.4s ease;
    }

    .logo-card:hover .logo-preview img {
      transform: scale(1.07);
    }

    /* =========================================================
   FORMAT BADGE
========================================================= */

    .format-badge {
      position: absolute;

      top: 15px;

      left: 15px;

      padding: 7px 11px;

      border-radius: 50px;

      background: white;

      color: var(--primary);

      font-size: 10px;

      font-weight: 800;

      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.07);
    }

    /* =========================================================
   CARD CONTENT
========================================================= */

    .logo-content {
      padding: 25px;
    }

    .logo-content h3 {
      font-size: 19px;

      margin-bottom: 8px;
    }

    .logo-content p {
      font-size: 13px;

      color: var(--muted);

      margin-bottom: 18px;
    }

    /* =========================================================
   META
========================================================= */

    .logo-meta {
      display: flex;

      flex-wrap: wrap;

      gap: 8px;

      margin-bottom: 20px;
    }

    .meta {
      padding: 6px 10px;

      border-radius: 8px;

      background: var(--light);

      color: #66766f;

      font-size: 10px;

      font-weight: 700;
    }

    /* =========================================================
   BUTTONS
========================================================= */

    .card-buttons {
      display: grid;

      grid-template-columns: 1fr 1fr;

      gap: 9px;
    }

    .btn {
      min-height: 43px;

      display: flex;

      align-items: center;

      justify-content: center;

      gap: 6px;

      border-radius: 10px;

      font-size: 11px;

      font-weight: 800;

      cursor: pointer;

      transition: var(--transition);
    }

    .btn-preview {
      background: white;

      color: var(--primary);

      border: 1px solid var(--primary);
    }

    .btn-preview:hover {
      background: var(--primary);

      color: white;
    }

    .btn-download {
      border: 1px solid var(--primary);

      background: var(--primary);

      color: white;
    }

    .btn-download:hover {
      background: var(--primary-dark);

      transform: translateY(-2px);

      box-shadow: 0 10px 25px rgba(8, 127, 91, 0.18);
    }

    /* =========================================================
   DOWNLOAD ALL
========================================================= */

    .download-section {
      background: var(--light);
    }

    .download-box {
      position: relative;

      overflow: hidden;

      max-width: 900px;

      margin: auto;

      padding: 55px;

      border-radius: 28px;

      background: linear-gradient(135deg, var(--dark), var(--primary-dark));

      color: white;

      text-align: center;

      box-shadow: var(--shadow);
    }

    .download-box::before {
      content: "";

      position: absolute;

      width: 280px;

      height: 280px;

      border-radius: 50%;

      border: 60px solid rgba(255, 255, 255, 0.04);

      left: -120px;

      bottom: -150px;
    }

    .download-box::after {
      content: "";

      position: absolute;

      width: 200px;

      height: 200px;

      border-radius: 50%;

      border: 40px solid rgba(244, 185, 66, 0.05);

      right: -70px;

      top: -100px;
    }

    .download-box-content {
      position: relative;

      z-index: 2;
    }

    .download-icon {
      width: 65px;

      height: 65px;

      margin: 0 auto 18px;

      border-radius: 18px;

      display: flex;

      align-items: center;

      justify-content: center;

      background: rgba(255, 255, 255, 0.1);

      font-size: 28px;
    }

    .download-box h2 {
      color: white;

      font-size: 30px;

      margin-bottom: 10px;
    }

    .download-box p {
      max-width: 620px;

      margin: 0 auto 25px;

      color: #d5e7df;

      font-size: 14px;
    }

    .download-all {
      display: inline-flex;

      align-items: center;

      justify-content: center;

      gap: 8px;

      padding: 14px 23px;

      border-radius: 11px;

      background: var(--secondary);

      color: var(--dark);

      font-size: 13px;

      font-weight: 800;

      border: none;

      cursor: pointer;

      transition: var(--transition);
    }

    .download-all:hover {
      transform: translateY(-3px);

      box-shadow: 0 12px 30px rgba(244, 185, 66, 0.2);
    }

    /* =========================================================
   INFORMATION
========================================================= */

    .info-grid {
      display: grid;

      grid-template-columns: repeat(3, 1fr);

      gap: 20px;
    }

    .info-card {
      padding: 30px;

      border: 1px solid var(--border);

      border-radius: 20px;

      background: white;

      transition: var(--transition);
    }

    .info-card:hover {
      transform: translateY(-5px);

      box-shadow: var(--shadow);
    }

    .info-icon {
      width: 55px;

      height: 55px;

      display: flex;

      align-items: center;

      justify-content: center;

      border-radius: 15px;

      background: var(--primary-light);

      color: var(--primary);

      font-size: 23px;

      margin-bottom: 17px;
    }

    .info-card h3 {
      font-size: 17px;

      margin-bottom: 8px;
    }

    .info-card p {
      font-size: 13px;
    }

    /* =========================================================
   FOOTER
========================================================= */

    footer {
      background: #0d2b21;

      color: #b7cec5;
    }

    .footer-main {
      padding: 70px 0 45px;

      display: grid;

      grid-template-columns: 1.4fr 1fr 1fr 1fr;

      gap: 45px;
    }

    .footer-brand p {
      max-width: 320px;

      margin-top: 17px;

      font-size: 13px;
    }

    .footer-title {
      color: white;

      font-size: 14px;

      margin-bottom: 17px;
    }

    .footer-links {
      display: grid;

      gap: 10px;
    }

    .footer-links a {
      font-size: 12px;

      transition: var(--transition);
    }

    .footer-links a:hover {
      color: var(--secondary);

      transform: translateX(3px);
    }

    .footer-bottom {
      padding: 20px 0;

      border-top: 1px solid rgba(255, 255, 255, 0.08);

      display: flex;

      align-items: center;

      justify-content: space-between;

      font-size: 11px;
    }

    /* =========================================================
   LIGHTBOX
========================================================= */

    .lightbox {
      position: fixed;

      inset: 0;

      z-index: 2000;

      display: flex;

      align-items: center;

      justify-content: center;

      padding: 30px;

      background: rgba(8, 20, 15, 0.93);

      opacity: 0;

      visibility: hidden;

      transition: var(--transition);
    }

    .lightbox.active {
      opacity: 1;

      visibility: visible;
    }

    .lightbox-content {
      position: relative;

      max-width: 900px;

      width: 100%;
    }

    .lightbox-image {
      width: 100%;

      max-height: 80vh;

      object-fit: contain;

      border-radius: 14px;

      background: white;

      padding: 20px;
    }

    .lightbox-close {
      position: absolute;

      right: 0;

      top: -50px;

      width: 40px;

      height: 40px;

      border: none;

      border-radius: 50%;

      background: rgba(255, 255, 255, 0.12);

      color: white;

      font-size: 20px;

      cursor: pointer;
    }

    .lightbox-close:hover {
      background: var(--primary);
    }

    /* =========================================================
   RESPONSIVE
========================================================= */

    @media (max-width: 1100px) {
      .nav-link {
        padding: 0 7px;

        font-size: 12px;
      }

      .nav-cta {
        display: none;
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

        box-shadow: 0 20px 30px rgba(0, 0, 0, 0.08);
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

      .nav-item.open>.dropdown,
      .dropdown-item.open>.dropdown {
        display: block;
      }

      .logo-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .info-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .footer-main {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 650px) {
      .container {
        width: calc(100% - 28px);
      }

      .hero {
        padding: 70px 0 75px;
      }

      .hero h1 {
        font-size: 40px;
      }

      .hero p {
        font-size: 15px;
      }

      .section {
        padding: 70px 0;
      }

      .logo-grid {
        grid-template-columns: 1fr;
      }

      .logo-preview {
        height: 290px;
      }

      .info-grid {
        grid-template-columns: 1fr;
      }

      .download-box {
        padding: 45px 25px;
      }

      .download-box h2 {
        font-size: 27px;
      }

      .footer-main {
        grid-template-columns: 1fr;

        gap: 30px;
      }

      .footer-bottom {
        flex-direction: column;

        gap: 8px;

        text-align: center;
      }
    }

    /*MAP PETA*/
    /* =========================================================
   FOOTER LOCATION
========================================================== */

    /* =========================================================
   LOCATION CONTACT
========================================================== */

    .location-contact {
      margin-top: 5px;
    }

    .footer-container {
      position: relative;
      z-index: 2;

      max-width: 1250px;
      margin: auto;

      padding: 60px 30px 50px;

      display: grid;

      grid-template-columns:
        1.6fr 1fr 1fr 1fr 1.6fr;

      gap: 30px;

      align-items: start;
    }

    @media (max-width: 600px) {
      .footer-container {
        grid-template-columns:
          1.5fr 1fr 1fr;

        gap: 30px;
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

    /*MAP PETA*/

    /* ================= DOSEN PAGE ================= */
    .dosen-section {
      padding: 90px 0 95px;
      background: #fff
    }

    .dosen-toolbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
      margin-bottom: 18px;
      padding: 18px;
      border: 1px solid var(--border);
      border-radius: 18px;
      background: #fff;
      box-shadow: 0 10px 35px rgba(18, 55, 42, .05)
    }

    .dosen-filters {
      display: flex;
      flex-wrap: wrap;
      gap: 8px
    }

    .dosen-filter {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 42px;
      padding: 0 17px;
      border: 1px solid var(--border);
      border-radius: 10px;
      background: #fff;
      color: #52635d;
      font-size: 12px;
      font-weight: 700;
      transition: var(--transition)
    }

    .dosen-filter:hover {
      color: var(--primary);
      border-color: #b8ddcf;
      background: var(--primary-light);
      transform: translateY(-2px)
    }

    .dosen-filter.active {
      background: var(--primary);
      border-color: var(--primary);
      color: #fff;
      box-shadow: 0 8px 22px rgba(8, 127, 91, .17)
    }

    .dosen-search {
      height: 44px;
      min-width: 315px;
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 0 7px 0 13px;
      border: 1px solid var(--border);
      border-radius: 11px;
      background: #fff
    }

    .dosen-search span {
      font-size: 22px;
      color: var(--primary);
      transform: rotate(-15deg)
    }

    .dosen-search input {
      min-width: 0;
      flex: 1;
      border: 0;
      outline: 0;
      background: transparent;
      color: var(--dark);
      font-size: 12px
    }

    .dosen-search input::placeholder {
      color: #9aa7a2
    }

    .dosen-search button {
      border: 0;
      border-radius: 8px;
      padding: 9px 14px;
      background: var(--primary);
      color: #fff;
      font-size: 11px;
      font-weight: 800;
      cursor: pointer
    }

    .dosen-summary {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
      color: var(--muted);
      font-size: 12px
    }

    .dosen-summary strong {
      color: var(--dark)
    }

    .dosen-summary a {
      color: var(--primary);
      font-weight: 700
    }

    .dosen-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 25px
    }

    .dosen-card {
      overflow: hidden;
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 22px;
      box-shadow: 0 10px 35px rgba(18, 55, 42, .055);
      transition: var(--transition)
    }

    .dosen-card:hover {
      transform: translateY(-8px);
      border-color: transparent;
      box-shadow: var(--shadow-hover)
    }

    .dosen-photo {
      height: 300px;
      position: relative;
      overflow: hidden;
      background: linear-gradient(135deg, #f4fbf8, #e0f2eb)
    }

    .dosen-photo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform .45s ease
    }

    .dosen-card:hover .dosen-photo img {
      transform: scale(1.045)
    }

    .dosen-photo-shade {
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, transparent 50%, rgba(10, 51, 39, .26));
      pointer-events: none
    }

    .dosen-avatar {
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      font: 800 78px "Plus Jakarta Sans", sans-serif;
      color: var(--primary);
      background: radial-gradient(circle at 50% 30%, #fff, #d9eee7)
    }

    .dosen-program,
    .dosen-status {
      position: absolute;
      top: 15px;
      padding: 8px 12px;
      border-radius: 50px;
      background: rgba(255, 255, 255, .97);
      box-shadow: 0 7px 18px rgba(0, 0, 0, .08);
      font-size: 10px;
      font-weight: 800
    }

    .dosen-program {
      left: 15px;
      color: var(--primary)
    }

    .dosen-status {
      right: 15px;
      display: flex;
      align-items: center;
      gap: 6px;
      color: #52635d
    }

    .dosen-status i {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #1aa974
    }

    .dosen-card-body {
      padding: 24px
    }

    .dosen-role {
      display: block;
      margin-bottom: 7px;
      color: var(--primary);
      font-size: 11px;
      font-weight: 800
    }

    .dosen-card h3 {
      margin: 0 0 18px;
      font-size: 20px;
      letter-spacing: -.4px;
      line-height: 1.4
    }

    .dosen-info {
      display: grid;
      gap: 10px;
      padding: 14px 0;
      border-top: 1px solid #edf2f0;
      border-bottom: 1px solid #edf2f0
    }

    .dosen-info div {
      display: grid;
      grid-template-columns: 55px minmax(0, 1fr);
      gap: 10px;
      font-size: 11px;
      color: #71817a
    }

    .dosen-info div span:last-child {
      overflow-wrap: anywhere
    }

    .info-key {
      color: var(--primary);
      font-size: 9px;
      font-weight: 800;
      letter-spacing: .3px
    }

    .dosen-actions {
      display: flex;
      gap: 9px;
      margin-top: 16px
    }

    .dosen-profile-btn {
      flex: 1;
      min-height: 43px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
      border: 1px solid var(--primary);
      border-radius: 10px;
      color: var(--primary);
      font-size: 11px;
      font-weight: 800;
      transition: var(--transition)
    }

    .dosen-profile-btn:hover {
      background: var(--primary);
      color: #fff
    }

    .dosen-mail-btn {
      width: 43px;
      min-height: 43px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 1px solid var(--border);
      border-radius: 10px;
      color: var(--primary);
      font-size: 16px
    }

    .dosen-mail-btn:hover {
      background: var(--primary-light)
    }

    .dosen-empty {
      padding: 70px 20px;
      text-align: center;
      border: 1px dashed #d6e5df;
      border-radius: 22px;
      background: #fbfdfc
    }

    .dosen-empty .empty-icon {
      font-size: 36px;
      color: var(--primary)
    }

    .dosen-empty h3 {
      margin: 10px 0 5px;
      font-size: 21px
    }

    .dosen-empty p {
      margin: 0 0 18px;
      font-size: 13px;
      color: var(--muted)
    }

    .dosen-empty a {
      display: inline-flex;
      padding: 11px 16px;
      border-radius: 10px;
      background: var(--primary);
      color: #fff;
      font-size: 11px;
      font-weight: 800
    }

    @media(max-width:1000px) {
      .dosen-grid {
        grid-template-columns: repeat(2, 1fr)
      }

      .dosen-toolbar {
        flex-direction: column;
        align-items: stretch
      }

      .dosen-search {
        min-width: 0;
        width: 100%
      }
    }

    @media(max-width:650px) {
      .dosen-section {
        padding: 65px 0 70px
      }

      .dosen-grid {
        grid-template-columns: 1fr
      }

      .dosen-filters {
        overflow-x: auto;
        flex-wrap: nowrap;
        padding-bottom: 3px
      }

      .dosen-filter {
        flex: none
      }

      .dosen-photo {
        height: 320px
      }

      .dosen-card-body {
        padding: 22px
      }

      .dosen-summary {
        align-items: flex-start;
        flex-direction: column;
        gap: 8px
      }
    }
    </style>
  </head>

  <body>
    <!-- TOPBAR REUSABLE -->
    <?php require_once __DIR__ . '/../menu/topbar.php'; ?>

    <!-- NAVBAR REUSABLE -->
    <?php require_once __DIR__ . '/../menu/navbar.php'; ?>

    <main>
      <section class="hero">
        <div class="container">
          <div class="hero-content">
            <div class="breadcrumb"><a href="/fikes/">Beranda</a><span>›</span><span>Tentang
                FIKES</span><span>›</span><span>Daftar Dosen</span></div>
            <div class="hero-label"><span class="hero-dot"></span>TENAGA PENGAJAR FIKES</div>
            <h1>Daftar Dosen <span>FIKES</span></h1>
            <p>Kenali dosen Fakultas Ilmu Kesehatan yang mendampingi mahasiswa dalam proses pendidikan dan pengembangan
              keilmuan.</p>
          </div>
        </div>
      </section>
      <section class="section dosen-section">
        <div class="container">
          <div class="section-header"><span class="section-label">TENAGA PENGAJAR</span>
            <h2 class="section-title"><?= $prodi ? 'Dosen ' . e($prodi) : 'Dosen FIKES' ?></h2>
            <p class="section-description">Temukan dosen berdasarkan program studi atau nama.</p>
          </div>
          <div class="dosen-toolbar">
            <div class="dosen-filters"><a class="dosen-filter <?= $prodi === '' ? 'active' : '' ?>"
                href="/fikes/dosen">Semua</a><?php foreach ($programs as $program): ?><a
                class="dosen-filter <?= $prodi === $program ? 'active' : '' ?>"
                href="/fikes/dosen/<?= strtolower(rawurlencode($program)) ?>"><?= e($program) ?></a><?php endforeach; ?>
            </div>
            <form class="dosen-search" method="get"><?php if ($prodi): ?><input type="hidden" name="prodi"
                value="<?= e($prodi) ?>"><?php endif; ?><span>⌕</span><input type="search" name="search"
                value="<?= e($search) ?>" placeholder="Cari nama dosen..."><button type="submit">Cari</button></form>
          </div>
          <div class="dosen-summary"><span>Menampilkan <strong><?= $total ?></strong> profil
              dosen</span><?php if ($prodi || $search): ?><a href="/fikes/dosen">Reset filter ×</a><?php endif; ?></div>
          <?php if (!$dosen): ?><div class="dosen-empty">
            <div class="empty-icon">⌕</div>
            <h3>Dosen tidak ditemukan</h3>
            <p>Silakan coba kata kunci atau program studi lainnya.</p><a href="/fikes/dosen">Tampilkan semua dosen</a>
          </div><?php else: ?><div class="dosen-grid">
            <?php foreach ($dosen as $d): $foto = trim($d['foto'] ?? '');
                  $fotoUrl = $foto !== '' ? '/fikes/admin/uploads/dosen/' . rawurlencode($foto) : '';
                  $initial = strtoupper(substr(trim($d['nama'] ?? 'D'), 0, 1)); ?>
            <article class="dosen-card">
              <div class="dosen-photo"><?php if ($fotoUrl): ?><img src="<?= e($fotoUrl) ?>" alt="<?= e($d['nama']) ?>"
                  loading="lazy"><?php else: ?><div class="dosen-avatar"><?= e($initial) ?></div><?php endif; ?><div
                  class="dosen-photo-shade"></div><span
                  class="dosen-program"><?= e($d['program_studi']) ?></span><?php if ($d['status'] !== ''): ?><span
                  class="dosen-status"><i></i><?= e(ucfirst($d['status'])) ?></span><?php endif; ?></div>
              <div class="dosen-card-body"><span class="dosen-role"><?= e($d['jabatan'] ?: 'Dosen') ?></span>
                <h3><?= e($d['nama']) ?></h3>
                <div class="dosen-info">
                  <div><span class="info-key">NIDN</span><span><?= e($d['nidn'] ?: '—') ?></span></div>
                  <div><span class="info-key">EMAIL</span><span><?= e($d['email'] ?: '—') ?></span></div>
                </div>
                <div class="dosen-actions"><a class="dosen-profile-btn"
                    href="/fikes/dosen/profil/<?= (int)$d['id'] ?>">Lihat Profil
                    <span>→</span></a><?php if (!empty($d['email'])): ?><a class="dosen-mail-btn"
                    href="mailto:<?= e($d['email']) ?>" title="Kirim email">✉</a><?php endif; ?></div>
              </div>
            </article><?php endforeach; ?>
          </div><?php endif; ?>
        </div>
      </section>
      <section class="cta-section">
        <div class="container">
          <div class="cta">
            <div class="cta-content">
              <h2>Kenali Tenaga Pengajar FIKES</h2>
              <p>Temukan informasi dosen berdasarkan program studi untuk membantu Anda mengenal lingkungan akademik
                FIKES.</p><a href="/fikes/dosen" class="btn">Lihat Semua Dosen →</a>
            </div>
          </div>
        </div>
      </section>
    </main>

    <?php require_once __DIR__ . '/../menu/footer.php'; ?>

    <script>
    (function() {
      const n = document.getElementById('navMenu'),
        b = document.getElementById('menuToggle'),
        bar = document.getElementById('navbar');
      if (b && n) b.addEventListener('click', () => {
        n.classList.toggle('active');
        b.textContent = n.classList.contains('active') ? '✕' : '☰'
      });
      document.querySelectorAll('.has-dropdown>.nav-link,.has-dropdown>.dropdown-link').forEach(a => a
        .addEventListener('click', function(e) {
          if (innerWidth <= 900) {
            e.preventDefault();
            this.parentElement.classList.toggle('open')
          }
        }));
      addEventListener('scroll', () => bar && bar.classList.toggle('scrolled', scrollY > 20));
      const y = document.getElementById('year');
    })();
    </script>
  </body>

</html>
