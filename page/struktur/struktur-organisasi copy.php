<?php
$host = 'localhost';
$db   = 'fikes';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  die('Koneksi database gagal: ' . $e->getMessage());
}


$data = null;
$stmt = $pdo->query("SELECT * FROM struktur_organisasi ORDER BY id DESC LIMIT 1");
$data = $stmt->fetch(PDO::FETCH_ASSOC);

function h($text)
{
  return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

$gambar = 'struktur-organisasi.png';
if (!empty($data['gambar'])) {
  $file = __DIR__ . '/../../admin/uploads/struktur/' . basename($data['gambar']);
  if (file_exists($file)) {
    $gambar = '/fikes/admin/uploads/struktur/' . basename($data['gambar']);
  }
}
?>
<!doctype html>
<html lang="id">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Struktur Organisasi | FIKES</title>

    <meta name="description" content="Struktur Organisasi Fakultas Ilmu Kesehatan" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap"
      rel="stylesheet" />
    <link rel="stylesheet" href="/fikes/assets/css/style.css">
    <!-- <link rel="stylesheet" href="../assets/css/struktur-organisasi.css" /> -->
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

      box-shadow: 0 10px 25px rgba(8, 127, 91, 0.22);
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
           PAGE HERO
        ========================================================= */

    .page-hero {
      position: relative;

      padding: 100px 0 105px;

      overflow: hidden;

      background:
        radial-gradient(circle at 85% 20%,
          rgba(8, 127, 91, 0.14),
          transparent 30%),
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

    .hero-label span {
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
      max-width: 700px;

      font-size: 17px;
    }

    /* =========================================================
           GENERAL SECTION
        ========================================================= */

    .section {
      padding: 95px 0;
    }

    .section-header {
      max-width: 720px;

      margin: 0 auto 50px;

      text-align: center;
    }

    .section-label {
      display: inline-flex;

      align-items: center;

      padding: 8px 15px;

      border-radius: 50px;

      background: var(--primary-light);

      color: var(--primary);

      font-size: 12px;

      font-weight: 700;

      margin-bottom: 15px;
    }

    .section-title {
      font-size: clamp(30px, 4vw, 45px);

      margin-bottom: 15px;
    }

    .section-description {
      font-size: 15px;
    }

    /* =========================================================
           ORGANIZATION IMAGE SECTION
        ========================================================= */

    .organization-section {
      background: white;
    }

    .organization-wrapper {
      max-width: 1100px;

      margin: auto;
    }

    /* IMAGE CARD */

    .organization-card {
      position: relative;

      background: white;

      border: 1px solid var(--border);

      border-radius: 24px;

      padding: 18px;

      box-shadow: var(--shadow);

      overflow: hidden;

      transition: var(--transition);
    }

    .organization-card:hover {
      box-shadow: var(--shadow-hover);
    }

    /* IMAGE HEADER */

    .organization-card-header {
      display: flex;

      align-items: center;

      justify-content: space-between;

      gap: 20px;

      padding: 12px 12px 20px;
    }

    .organization-card-header-left {
      display: flex;

      align-items: center;

      gap: 13px;
    }

    .organization-card-icon {
      width: 48px;
      height: 48px;

      flex-shrink: 0;

      border-radius: 14px;

      background: var(--primary-light);

      color: var(--primary);

      display: flex;

      align-items: center;

      justify-content: center;

      font-size: 21px;
    }

    .organization-card-header h3 {
      font-size: 17px;
    }

    .organization-card-header p {
      font-size: 11px;

      margin-top: 2px;
    }

    /* ZOOM BUTTON */

    .zoom-btn {
      display: inline-flex;

      align-items: center;

      gap: 7px;

      padding: 10px 14px;

      border: 1px solid var(--border);

      border-radius: 10px;

      background: white;

      color: var(--primary);

      font-size: 12px;

      font-weight: 700;

      cursor: pointer;

      transition: var(--transition);
    }

    .zoom-btn:hover {
      background: var(--primary);

      color: white;

      border-color: var(--primary);
    }

    /* IMAGE */

    .organization-image-container {
      position: relative;

      width: 100%;

      overflow: hidden;

      border-radius: 16px;

      background: #f3f7f5;

      border: 1px solid var(--border);

      cursor: zoom-in;
    }

    .organization-image {
      width: 100%;

      height: auto;

      min-height: 350px;

      object-fit: contain;

      transition: transform 0.5s ease;
    }

    .organization-image-container:hover .organization-image {
      transform: scale(1.01);
    }

    /* IMAGE OVERLAY */

    .image-overlay {
      position: absolute;

      inset: 0;

      display: flex;

      align-items: center;

      justify-content: center;

      background: rgba(8, 127, 91, 0);

      opacity: 0;

      transition: var(--transition);

      pointer-events: none;
    }

    .organization-image-container:hover .image-overlay {
      opacity: 1;

      background: rgba(8, 127, 91, 0.08);
    }

    .image-overlay-icon {
      width: 55px;
      height: 55px;

      border-radius: 50%;

      background: white;

      color: var(--primary);

      display: flex;

      align-items: center;

      justify-content: center;

      font-size: 20px;

      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    /* CAPTION */

    .image-caption {
      padding: 18px 12px 7px;

      text-align: center;

      font-size: 12px;

      color: #7a8a84;
    }

    .image-caption strong {
      color: var(--dark);
    }

    /* =========================================================
           INFORMATION SECTION
        ========================================================= */

    .information-section {
      background: var(--light);
    }

    .information-grid {
      display: grid;

      grid-template-columns: repeat(3, 1fr);

      gap: 20px;
    }

    .information-card {
      padding: 30px;

      background: white;

      border: 1px solid var(--border);

      border-radius: 20px;

      transition: var(--transition);
    }

    .information-card:hover {
      transform: translateY(-7px);

      border-color: transparent;

      box-shadow: var(--shadow);
    }

    .information-icon {
      width: 55px;
      height: 55px;

      border-radius: 16px;

      background: var(--primary-light);

      color: var(--primary);

      display: flex;

      align-items: center;

      justify-content: center;

      font-size: 23px;

      margin-bottom: 18px;
    }

    .information-card h3 {
      font-size: 17px;

      margin-bottom: 9px;
    }

    .information-card p {
      font-size: 13px;
    }

    /* =========================================================
           ORGANIZATION DESCRIPTION
        ========================================================= */

    .description-section {
      background: white;
    }

    .description-grid {
      display: grid;

      grid-template-columns: 1fr 1fr;

      gap: 60px;

      align-items: center;
    }

    .description-content h2 {
      font-size: clamp(30px, 4vw, 43px);

      margin-bottom: 18px;
    }

    .description-content>p {
      margin-bottom: 22px;

      font-size: 14px;
    }

    .description-list {
      display: grid;

      gap: 14px;
    }

    .description-list li {
      display: flex;

      gap: 12px;

      align-items: flex-start;

      font-size: 13px;
    }

    .description-check {
      width: 24px;
      height: 24px;

      flex-shrink: 0;

      border-radius: 50%;

      background: var(--primary-light);

      color: var(--primary);

      display: flex;

      align-items: center;

      justify-content: center;

      font-size: 11px;

      font-weight: 800;
    }

    /* SIDE BOX */

    .description-side {
      position: relative;

      padding: 40px;

      min-height: 350px;

      border-radius: 25px;

      overflow: hidden;

      background: linear-gradient(145deg,
          var(--primary),
          var(--primary-dark));

      box-shadow: 0 25px 60px rgba(8, 127, 91, 0.18);
    }

    .description-side::before {
      content: "";

      position: absolute;

      width: 300px;
      height: 300px;

      border-radius: 50%;

      border: 55px solid rgba(255, 255, 255, 0.05);

      right: -130px;
      top: -100px;
    }

    .description-side::after {
      content: "";

      position: absolute;

      width: 200px;
      height: 200px;

      border-radius: 50%;

      border: 35px solid rgba(244, 185, 66, 0.06);

      left: -90px;
      bottom: -100px;
    }

    .description-side-content {
      position: relative;

      z-index: 2;
    }

    .description-side-icon {
      width: 65px;
      height: 65px;

      border-radius: 18px;

      background: rgba(255, 255, 255, 0.12);

      color: white;

      display: flex;

      align-items: center;

      justify-content: center;

      font-size: 28px;

      margin-bottom: 25px;
    }

    .description-side h3 {
      color: white;

      font-size: 25px;

      margin-bottom: 12px;
    }

    .description-side p {
      color: #d5e9e2;

      font-size: 13px;
    }

    /* =========================================================
           CTA
        ========================================================= */

    .cta-section {
      padding: 80px 0;
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

      border-radius: 50%;

      border: 70px solid rgba(255, 255, 255, 0.03);

      left: -130px;
      bottom: -190px;
    }

    .cta::after {
      content: "";

      position: absolute;

      width: 220px;
      height: 220px;

      border-radius: 50%;

      border: 45px solid rgba(244, 185, 66, 0.05);

      right: -80px;
      top: -100px;
    }

    .cta-content {
      position: relative;

      z-index: 2;
    }

    .cta h2 {
      color: white;

      font-size: clamp(30px, 4vw, 42px);

      margin-bottom: 12px;
    }

    .cta p {
      max-width: 650px;

      margin: 0 auto 25px;

      color: #d6e9e2;
    }

    .cta-button {
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

    .cta-button:hover {
      transform: translateY(-3px);

      box-shadow: 0 12px 30px rgba(244, 185, 66, 0.2);
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

      background: rgba(8, 20, 15, 0.92);

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

      max-width: 1200px;

      max-height: 90vh;

      width: 100%;
    }

    .lightbox-image {
      width: 100%;

      max-height: 85vh;

      object-fit: contain;

      border-radius: 12px;

      background: white;

      box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
    }

    .lightbox-close {
      position: absolute;

      top: -45px;

      right: 0;

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
           BACK TOP
        ========================================================= */

    .back-top {
      position: fixed;

      right: 25px;
      bottom: 25px;

      width: 45px;
      height: 45px;

      border: none;

      border-radius: 12px;

      background: var(--primary);

      color: white;

      cursor: pointer;

      opacity: 0;

      visibility: hidden;

      transform: translateY(10px);

      transition: var(--transition);

      z-index: 900;
    }

    .back-top.show {
      opacity: 1;

      visibility: visible;

      transform: translateY(0);
    }

    /* =========================================================
           RESPONSIVE TABLET
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

      .dropdown-link {
        min-height: 42px;
      }

      .information-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .description-grid {
        grid-template-columns: 1fr;

        gap: 40px;
      }

      .footer-main {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    /* =========================================================
           RESPONSIVE MOBILE
        ========================================================= */

    @media (max-width: 650px) {
      .container {
        width: min(100% - 28px, 1180px);
      }

      .page-hero {
        padding: 70px 0 75px;
      }

      .page-hero h1 {
        font-size: 40px;
      }

      .page-hero p {
        font-size: 15px;
      }

      .section {
        padding: 70px 0;
      }

      .organization-card {
        padding: 10px;
      }

      .organization-card-header {
        padding: 12px 8px 15px;

        align-items: flex-start;
      }

      .organization-card-header h3 {
        font-size: 15px;
      }

      .organization-card-header p {
        font-size: 10px;
      }

      .zoom-btn {
        padding: 9px 10px;

        font-size: 11px;
      }

      .zoom-btn span {
        display: none;
      }

      .organization-image {
        min-height: 250px;
      }

      .image-caption {
        padding: 15px 8px 5px;

        font-size: 11px;
      }

      .information-grid {
        grid-template-columns: 1fr;
      }

      .description-side {
        min-height: 300px;

        padding: 30px;
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

        gap: 8px;

        text-align: center;
      }

      .lightbox {
        padding: 15px;
      }

      .lightbox-close {
        top: -48px;
      }
    }

    /* =========================================================
   RIWAYAT STRUKTUR ORGANISASI
========================================================= */

    .riwayat-organisasi {
      max-width: 1000px;

      margin: 70px auto 0;
    }

    /* =========================================================
   SECTION HEADING
========================================================= */

    .section-heading {
      margin-bottom: 35px;

      text-align: center;
    }

    .section-label {
      display: inline-block;

      margin-bottom: 8px;

      padding: 6px 12px;

      border-radius: 30px;

      color: #087f5b;

      background: rgba(8, 127, 91, 0.09);

      font-size: 10px;

      font-weight: 800;

      letter-spacing: 1.5px;
    }

    .section-heading h2 {
      margin: 0 0 10px;

      color: #123c31;

      font-size: 28px;

      font-weight: 800;
    }

    .section-heading p {
      max-width: 600px;

      margin: auto;

      color: #6b7773;

      font-size: 13px;

      line-height: 1.7;
    }

    /* =========================================================
   PERIODE CARD
========================================================= */

    .periode-card {
      position: relative;

      margin-bottom: 25px;

      padding: 28px;

      overflow: hidden;

      border: 1px solid rgba(8, 127, 91, 0.1);

      border-radius: 18px;

      background: #ffffff;

      box-shadow: 0 10px 35px rgba(0, 0, 0, 0.06);

      transition:
        transform 0.3s ease,
        box-shadow 0.3s ease;
    }

    .periode-card:hover {
      transform: translateY(-4px);

      box-shadow: 0 18px 45px rgba(0, 0, 0, 0.1);
    }

    /* garis kiri */

    .periode-card::before {
      content: "";

      position: absolute;

      top: 0;

      bottom: 0;

      left: 0;

      width: 4px;

      background: linear-gradient(180deg, #087f5b, #0ca678);
    }

    /* =========================================================
   PERIODE HEADER
========================================================= */

    .periode-header {
      display: flex;

      align-items: center;

      gap: 14px;

      margin-bottom: 20px;
    }

    .periode-icon {
      width: 48px;

      height: 48px;

      display: flex;

      align-items: center;

      justify-content: center;

      flex-shrink: 0;

      border-radius: 13px;

      color: #ffffff;

      background: linear-gradient(135deg, #087f5b, #0ca678);

      box-shadow: 0 8px 20px rgba(8, 127, 91, 0.18);
    }

    .periode-label {
      display: block;

      margin-bottom: 3px;

      color: #89948f;

      font-size: 9px;

      font-weight: 700;

      letter-spacing: 1.2px;
    }

    .periode-header h3 {
      margin: 0;

      color: #123c31;

      font-size: 23px;

      font-weight: 800;
    }

    /* =========================================================
   SK INFO
========================================================= */

    .sk-info {
      margin-bottom: 20px;

      padding: 14px 16px;

      border-radius: 11px;

      background: #f5faf8;
    }

    .sk-info span {
      display: block;

      margin-bottom: 5px;

      color: #ff5a1f;

      font-size: 11px;

      font-weight: 700;
    }

    .sk-info strong {
      display: block;

      color: #ff5a1f;

      font-size: 14px;

      font-weight: 800;
    }

    /* =========================================================
   JABATAN
========================================================= */

    .jabatan-list {
      display: flex;

      flex-direction: column;
    }

    .jabatan-row {
      display: grid;

      grid-template-columns: 250px 1fr;

      gap: 20px;

      padding: 10px 0;

      border-bottom: 1px solid #edf1ef;
    }

    .jabatan-row:last-child {
      border-bottom: none;
    }

    .jabatan {
      color: #53635d;

      font-size: 11px;

      font-weight: 600;
    }

    .nama {
      color: #263c35;

      font-size: 11px;

      line-height: 1.5;
    }

    /* =========================================================
   RESPONSIVE TABLET
========================================================= */

    @media (max-width: 768px) {
      .riwayat-organisasi {
        margin-top: 50px;

        padding: 0 20px;
      }

      .periode-card {
        padding: 22px;
      }

      .jabatan-row {
        grid-template-columns: 190px 1fr;

        gap: 15px;
      }
    }

    /* =========================================================
   RESPONSIVE MOBILE
========================================================= */

    @media (max-width: 600px) {
      .section-heading h2 {
        font-size: 23px;
      }

      .section-heading p {
        font-size: 12px;
      }

      .periode-card {
        padding: 20px;

        border-radius: 15px;
      }

      .periode-header h3 {
        font-size: 20px;
      }

      .periode-icon {
        width: 42px;

        height: 42px;
      }

      .sk-info strong {
        font-size: 12px;

        line-height: 1.5;
      }

      .jabatan-row {
        display: block;

        padding: 11px 0;
      }

      .jabatan {
        display: block;

        margin-bottom: 4px;

        color: #087f5b;

        font-size: 10px;

        font-weight: 800;
      }

      .nama {
        font-size: 11px;
      }
    }

    /*MAP PETA*/
    /* =========================================================
   FOOTER LOCATION
========================================================== */

    .footer-location {
      grid-column: 1 / -1;
    }

    /* =========================================================
   LOCATION HEADER
========================================================== */

    .location-header {
      display: flex;

      align-items: center;

      gap: 12px;

      margin-bottom: 18px;
    }

    .location-icon {
      width: 42px;

      height: 42px;

      display: flex;

      align-items: center;

      justify-content: center;

      flex-shrink: 0;

      border-radius: 12px;

      color: var(--dark);

      background: var(--secondary);

      font-size: 16px;
    }

    .location-header h3 {
      margin: 0 0 3px;

      color: white;

      font-size: 13px;

      font-weight: 800;
    }

    .location-header p {
      margin: 0;

      color: rgba(255, 255, 255, 0.5);

      font-size: 9px;
    }

    /* =========================================================
   MAP CARD
========================================================== */

    .map-card {
      position: relative;

      width: 100%;

      height: 210px;

      overflow: hidden;

      margin-bottom: 20px;

      border: 1px solid rgba(255, 255, 255, 0.1);

      border-radius: 16px;

      background: rgba(255, 255, 255, 0.05);

      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    /* =========================================================
   GOOGLE MAP
========================================================== */

    .map-card iframe {
      position: absolute;

      inset: 0;

      width: 100%;

      height: 100%;

      border: 0;

      filter: saturate(0.85) contrast(1.02);
    }

    /* =========================================================
   MAP GRADIENT
========================================================== */

    .map-card::after {
      content: "";

      position: absolute;

      inset: 0;

      pointer-events: none;

      background: linear-gradient(180deg,
          rgba(3, 25, 18, 0.05) 35%,
          rgba(3, 25, 18, 0.75) 100%);
    }

    /* =========================================================
   MAP INFO
========================================================== */

    .map-overlay {
      position: absolute;

      left: 12px;

      right: 12px;

      bottom: 12px;

      z-index: 5;

      display: flex;

      align-items: center;

      justify-content: space-between;

      gap: 10px;
    }

    /* =========================================================
   MAP INFO CARD
========================================================== */

    .map-info {
      display: flex;

      align-items: center;

      gap: 9px;

      min-width: 0;

      padding: 9px 11px;

      border: 1px solid rgba(255, 255, 255, 0.16);

      border-radius: 10px;

      background: rgba(5, 35, 26, 0.78);

      backdrop-filter: blur(10px);
    }

    .map-info-icon {
      width: 30px;

      height: 30px;

      display: flex;

      align-items: center;

      justify-content: center;

      flex-shrink: 0;

      border-radius: 8px;

      color: var(--dark);

      background: var(--secondary);

      font-size: 11px;
    }

    .map-info strong {
      display: block;

      max-width: 150px;

      overflow: hidden;

      color: white;

      font-size: 9px;

      font-weight: 800;

      white-space: nowrap;

      text-overflow: ellipsis;
    }

    .map-info span {
      display: block;

      margin-top: 2px;

      color: rgba(255, 255, 255, 0.55);

      font-size: 7px;
    }

    /* =========================================================
   MAP DIRECTION BUTTON
========================================================== */

    .map-direction {
      display: inline-flex;

      align-items: center;

      justify-content: center;

      gap: 6px;

      flex-shrink: 0;

      padding: 10px 12px;

      border-radius: 9px;

      color: var(--dark);

      background: white;

      font-size: 8px;

      font-weight: 800;

      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);

      transition: all 0.3s ease;
    }

    .map-direction:hover {
      color: white;

      background: var(--primary);

      transform: translateY(-2px);
    }

    .map-direction i {
      font-size: 9px;
    }

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
      <div class="container">
        <div class="hero-content">
          <div class="breadcrumb">
            <a href="index.html"> Beranda </a>

            <span>›</span>

            <span> Tentang FIKES </span>

            <span>›</span>

            <span> Struktur Organisasi </span>
          </div>

          <div class="hero-label">
            <span></span>

            Tentang FIKES
          </div>

          <h1>
            Struktur

            <span>Organisasi</span>
          </h1>

          <p>
            Struktur organisasi Fakultas Ilmu Kesehatan sebagai landasan tata
            kelola dalam menjalankan pendidikan, penelitian, pengabdian kepada
            masyarakat, serta pelayanan akademik.
          </p>
        </div>
      </div>
    </section>

    <!-- =========================================================
     ORGANIZATION IMAGE
========================================================= -->

    <section class="section organization-section">
      <div class="container">
        <div class="section-header">
          <span class="section-label"> Universitas Bhamada Slawi </span>

          <h2 class="section-title">Struktur Organisasi FIKES</h2>

          <p class="section-description">
            Berikut merupakan struktur organisasi Fakultas Ilmu Kesehatan yang
            menggambarkan hubungan kerja, koordinasi, serta pembagian tugas dan
            tanggung jawab setiap unsur.
          </p>
        </div>

        <div class="organization-wrapper">
          <div class="organization-card">
            <!-- IMAGE HEADER -->

            <div class="organization-card-header">
              <div class="organization-card-header-left">
                <div class="organization-card-icon">🏛️</div>

                <div>
                  <h3>Struktur Organisasi Fakultas Ilmu Kesehatan</h3>

                  <p>Bagan organisasi dan tata kelola FIKES</p>
                </div>
              </div>

              <button class="zoom-btn" id="zoomButton">
                🔍

                <span> Perbesar Gambar </span>
              </button>
            </div>

            <!-- IMAGE -->

            <div class="organization-image-container" id="organizationImageContainer">


              <img src="<?= h($gambar) ?>" alt="Struktur Organisasi Fakultas Ilmu Kesehatan"
                alt="Struktur Organisasi Fakultas Ilmu Kesehatan" class="organization-image" id="organizationImage" />
              <div class="image-overlay">
                <div class="image-overlay-icon">🔍</div>
              </div>
            </div>

            <!-- CAPTION -->

            <div class="image-caption">
              <strong> Gambar Struktur Organisasi FIKES </strong>

              <br />

              Struktur organisasi Fakultas Ilmu Kesehatan yang menunjukkan
              hubungan koordinasi dan pembagian tugas setiap bagian.
            </div>
          </div>
        </div>

        <!-- =====================================================
         RIWAYAT STRUKTUR ORGANISASI
    ====================================================== -->

        <div class="riwayat-organisasi">
          <div class="section-heading">
            <span class="section-label"> RIWAYAT ORGANISASI </span>

            <h2>Periode Kepemimpinan FIKES</h2>

            <p>
              Daftar pimpinan Fakultas Ilmu Kesehatan berdasarkan periode
              kepemimpinan.
            </p>
          </div>

          <!-- PERIODE 2024 - 2025 -->
          <div class="periode-card">
            <div class="periode-header">
              <div class="periode-icon">
                <i class="fa-solid fa-building-columns"></i>
              </div>

              <div>
                <span class="periode-label"> PERIODE </span>

                <h3>2024 – 2025</h3>
              </div>
            </div>

            <div class="sk-info">
              <span> SK Rektor Universitas Bhamada Slawi </span>

              <strong> <?= h($data['sk_rektor']) ?></strong>
            </div>

            <div class="jabatan-list">
              <div class="jabatan-row">
                <span class="jabatan"> Dekan </span>

                <span class="nama"> <?= h($data['dekan']) ?></span>
              </div>

              <div class="jabatan-row">
                <span class="jabatan"> Wakil Dekan Bidang Akademik </span>

                <span class="nama"> <?= h($data['wakil_dekan_akademik']) ?></span>
              </div>

              <div class="jabatan-row">
                <span class="jabatan"> Wakil Dekan Bidang Adum&Keu </span>

                <span class="nama"> <?= h($data['wakil_dekan_adum_keu']) ?></span>
              </div>

              <div class="jabatan-row">
                <span class="jabatan"> Wakil Dekan Bidang Kemahasiswaan </span>

                <span class="nama"> <?= h($data['wakil_dekan_kemahasiswaan']) ?></span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- =========================================================
     KETERANGAN
========================================================= -->

    <section class="section information-section">
      <div class="container">
        <div class="section-header">
          <span class="section-label"> KETERANGAN </span>

          <h2 class="section-title">Unsur Organisasi</h2>

          <p class="section-description">
            Setiap unsur dalam struktur organisasi memiliki peran dan tanggung
            jawab untuk mendukung tercapainya tujuan Fakultas Ilmu Kesehatan.
          </p>
        </div>

        <div class="information-grid">
          <!-- DEKAN -->

          <div class="information-card">
            <div class="information-icon">👤</div>

            <h3>Dekan</h3>

            <p>
              Memimpin penyelenggaraan pendidikan, penelitian, pengabdian kepada
              masyarakat, serta tata kelola Fakultas Ilmu Kesehatan.
            </p>
          </div>

          <!-- WAKIL DEKAN -->

          <div class="information-card">
            <div class="information-icon">🤝</div>

            <h3>Wakil Dekan</h3>

            <p>
              Membantu Dekan dalam menjalankan tugas sesuai bidang pengelolaan
              akademik, kemahasiswaan, administrasi, dan pengembangan fakultas.
            </p>
          </div>

          <!-- PROGRAM STUDI -->

          <div class="information-card">
            <div class="information-icon">🎓</div>

            <h3>Program Studi</h3>

            <p>
              Menyelenggarakan proses pendidikan sesuai dengan bidang keilmuan
              dan program pendidikan masing-masing.
            </p>
          </div>

          <!-- DOSEN -->

          <div class="information-card">
            <div class="information-icon">👨‍🏫</div>

            <h3>Dosen</h3>

            <p>
              Melaksanakan pendidikan, penelitian, pengabdian kepada masyarakat,
              serta pembimbingan dan pengembangan mahasiswa.
            </p>
          </div>

          <!-- TATA USAHA -->

          <div class="information-card">
            <div class="information-icon">📁</div>

            <h3>Tata Usaha</h3>

            <p>
              Mendukung pelaksanaan administrasi, pelayanan akademik,
              kepegawaian, keuangan, dan administrasi umum.
            </p>
          </div>

          <!-- KEMAHASISWAAN -->

          <div class="information-card">
            <div class="information-icon">🎯</div>

            <h3>Kemahasiswaan</h3>

            <p>
              Mendukung pengembangan potensi mahasiswa melalui kegiatan
              organisasi, prestasi, kreativitas, dan kegiatan kemahasiswaan.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- =========================================================
     PENJELASAN
========================================================= -->

    <section class="section description-section">
      <div class="container description-grid">
        <div class="description-content">
          <span class="section-label"> TATA KELOLA </span>

          <h2>Tata Kelola yang Terarah dan Profesional</h2>

          <p>
            Struktur organisasi menjadi bagian penting dalam mendukung
            pelaksanaan tugas dan fungsi Fakultas Ilmu Kesehatan secara efektif,
            transparan, dan bertanggung jawab.
          </p>

          <ul class="description-list">
            <li>
              <span class="description-check"> ✓ </span>

              <span>
                Pembagian tugas dan tanggung jawab yang jelas pada setiap unsur
                organisasi.
              </span>
            </li>

            <li>
              <span class="description-check"> ✓ </span>

              <span>
                Koordinasi antarbagian untuk mendukung pencapaian tujuan
                fakultas.
              </span>
            </li>

            <li>
              <span class="description-check"> ✓ </span>

              <span>
                Pengelolaan akademik dan administrasi yang berorientasi pada
                mutu.
              </span>
            </li>

            <li>
              <span class="description-check"> ✓ </span>

              <span>
                Mendukung peningkatan kualitas pendidikan, penelitian, dan
                pengabdian masyarakat.
              </span>
            </li>
          </ul>
        </div>

        <div class="description-side">
          <div class="description-side-content">
            <div class="description-side-icon">🏛️</div>

            <h3>Bersama Membangun FIKES yang Unggul</h3>

            <p>
              Tata kelola yang baik menjadi fondasi untuk menciptakan lingkungan
              akademik yang profesional, inovatif, dan mampu memberikan
              pelayanan terbaik kepada seluruh sivitas akademika.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- =========================================================
     CTA
========================================================= -->

    <section class="cta-section">
      <div class="container">
        <div class="cta">
          <div class="cta-content">
            <h2>Kenali FIKES Lebih Dekat</h2>

            <p>
              Temukan informasi mengenai visi dan misi, program studi, dosen,
              kemahasiswaan, pelayanan, serta berbagai informasi akademik FIKES.
            </p>

            <a href="/fikes/tentang/visi-misi" class="cta-button">
              Lihat Visi & Misi →
            </a>
          </div>
        </div>
      </div>
    </section>
    <!-- =========================================================
     FOOTER
========================================================= -->

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
            <a href="#"> Akademik </a>

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

    <!-- =========================================================
     LIGHTBOX
========================================================= -->

    <div class="lightbox" id="lightbox" aria-hidden="true">
      <div class="lightbox-content">
        <button class="lightbox-close" id="lightboxClose" aria-label="Tutup gambar">
          ✕
        </button>

        <img src="/fikes/assets/images/strukturori.png" alt="Struktur Organisasi FIKES" class="lightbox-image"
          id="lightboxImage" />
      </div>
    </div>

    <!-- =========================================================
     BACK TO TOP
========================================================= -->

    <button class="back-top" id="backTop" aria-label="Kembali ke atas">
      ↑
    </button>

    <!-- =========================================================
     JAVASCRIPT
========================================================= -->
    <!-- <script src="../assets/js/struktur-organisasi.js"></script> -->
    <script>
    /* =====================================================
       NAVBAR SCROLL
    ===================================================== */

    const navbar = document.getElementById("navbar");

    window.addEventListener("scroll", () => {
      if (window.scrollY > 20) {
        navbar.classList.add("scrolled");
      } else {
        navbar.classList.remove("scrolled");
      }
    });

    /* =====================================================
       MOBILE MENU
    ===================================================== */

    const menuToggle = document.getElementById("menuToggle");

    const navMenu = document.getElementById("navMenu");

    menuToggle.addEventListener("click", () => {
      navMenu.classList.toggle("active");

      menuToggle.innerHTML = navMenu.classList.contains("active") ?
        "✕" :
        "☰";
    });

    /* =====================================================
       MOBILE DROPDOWN
    ===================================================== */

    document
      .querySelectorAll(
        ".has-dropdown > .nav-link, " + ".has-dropdown > .dropdown-link",
      )
      .forEach((link) => {
        link.addEventListener("click", function(event) {
          if (window.innerWidth <= 900) {
            event.preventDefault();

            const parent = this.parentElement;

            parent.classList.toggle("open");
          }
        });
      });

    /* =====================================================
       CLOSE MOBILE MENU
    ===================================================== */

    document.querySelectorAll(".nav-menu a").forEach((link) => {
      link.addEventListener("click", function() {
        if (
          window.innerWidth <= 900 &&
          !this.parentElement.classList.contains("has-dropdown")
        ) {
          navMenu.classList.remove("active");

          menuToggle.innerHTML = "☰";
        }
      });
    });

    /* =====================================================
       IMAGE LIGHTBOX
    ===================================================== */

    const lightbox = document.getElementById("lightbox");

    const lightboxImage = document.getElementById("lightboxImage");

    const organizationImage = document.getElementById("organizationImage");

    const zoomButton = document.getElementById("zoomButton");

    const imageContainer = document.getElementById(
      "organizationImageContainer",
    );

    const lightboxClose = document.getElementById("lightboxClose");

    function openLightbox() {
      lightboxImage.src = organizationImage.src;

      lightboxImage.alt = organizationImage.alt;

      lightbox.classList.add("active");

      lightbox.setAttribute("aria-hidden", "false");

      document.body.style.overflow = "hidden";
    }

    function closeLightbox() {
      lightbox.classList.remove("active");

      lightbox.setAttribute("aria-hidden", "true");

      document.body.style.overflow = "";
    }

    zoomButton.addEventListener("click", openLightbox);

    imageContainer.addEventListener("click", openLightbox);

    lightboxClose.addEventListener("click", closeLightbox);

    lightbox.addEventListener("click", (event) => {
      if (event.target === lightbox) {
        closeLightbox();
      }
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape" && lightbox.classList.contains("active")) {
        closeLightbox();
      }
    });

    /* =====================================================
       BACK TO TOP
    ===================================================== */

    const backTop = document.getElementById("backTop");

    window.addEventListener("scroll", () => {
      if (window.scrollY > 500) {
        backTop.classList.add("show");
      } else {
        backTop.classList.remove("show");
      }
    });

    backTop.addEventListener("click", () => {
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    });

    /* =====================================================
       CURRENT YEAR
    ===================================================== */

    document.getElementById("year").textContent = new Date().getFullYear();

    /* =====================================================
       CLOSE DROPDOWN OUTSIDE NAVBAR
    ===================================================== */

    document.addEventListener("click", (event) => {
      if (!event.target.closest(".navbar")) {
        document.querySelectorAll(".nav-item.open").forEach((item) => {
          item.classList.remove("open");
        });
      }
    });
    </script>
  </body>

</html>
