<?php
require_once __DIR__ . '/../../admin/config/database.php';

$page_title = 'Himpunan Mahasiswa';

if (!function_exists('e')) {
  function e($value)
  {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
  }
}

function hm_image_url($value)
{
  $value = trim((string)$value);
  if ($value === '') return '';
  if (preg_match('~^https?://~i', $value)) return $value;
  if (str_starts_with($value, '/')) return $value;
  if (str_starts_with($value, 'vendor/')) return '/fikes/' . ltrim($value, '/');
  return '/fikes/admin/uploads/kemahasiswaan/logo/' . rawurlencode(basename($value));
}

function hm_excerpt($value, $limit = 165)
{
  $text = trim(preg_replace('/\s+/', ' ', strip_tags((string)$value)));
  if ($text === '') return 'Informasi himpunan mahasiswa FIKES.';
  if (mb_strlen($text) <= $limit) return $text;
  return mb_substr($text, 0, $limit - 1) . '…';
}

$stmt = $pdo->prepare("SELECT * FROM kemahasiswaan_organisasi
                       WHERE jenis = 'himpunan' AND status = 'aktif'
                       ORDER BY nomor_urut ASC, id ASC");
$stmt->execute();
$himpunan = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total = count($himpunan);
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($page_title) ?> | FIKES</title>
  <meta name="description" content="Informasi Himpunan Mahasiswa Fakultas Ilmu Kesehatan.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="/fikes/assets/css/style.css">
  <style>
    .hm-page {
      --hm-primary: #087f5b;
      --hm-dark: #12372a;
      --hm-text: #52635d;
      --hm-muted: #7b8b85;
      --hm-light: #f6faf8;
      --hm-border: #e2ebe7;
      --hm-gold: #f4b942;
      color: var(--hm-text)
    }

    .hm-container {
      width: min(1180px, calc(100% - 40px));
      margin: 0 auto
    }

    .hm-page * {
      box-sizing: border-box
    }

    .hm-page a {
      text-decoration: none
    }

    .hm-page img {
      max-width: 100%;
      display: block
    }

    .hm-hero {
      position: relative;
      overflow: hidden;
      padding: 68px 0 76px;
      background: linear-gradient(120deg, #005e51 0%, #087f5b 58%, #70c4ae 150%);
      color: #fff
    }

    .hm-hero:before,
    .hm-hero:after {
      content: "";
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, .08)
    }

    .hm-hero:before {
      width: 430px;
      height: 430px;
      right: -190px;
      top: -250px
    }

    .hm-hero:after {
      width: 250px;
      height: 250px;
      left: -140px;
      bottom: -170px
    }

    .hm-breadcrumb {
      position: relative;
      z-index: 2;
      display: flex;
      align-items: center;
      gap: 9px;
      flex-wrap: wrap;
      font-size: 13px;
      margin-bottom: 28px;
      color: rgba(255, 255, 255, .78)
    }

    .hm-breadcrumb a {
      color: #fff;
      font-weight: 700
    }

    .hm-hero-grid {
      position: relative;
      z-index: 2;
      display: grid;
      grid-template-columns: 1fr 270px;
      gap: 50px;
      align-items: center
    }

    .hm-eyebrow,
    .hm-label {
      display: inline-flex;
      font-size: 11px;
      letter-spacing: 1.6px;
      font-weight: 800;
      text-transform: uppercase
    }

    .hm-eyebrow {
      color: #dff7ef
    }

    .hm-hero h1 {
      margin: 10px 0 16px;
      color: #fff;
      font: 800 clamp(38px, 5vw, 58px)/1.12 "Plus Jakarta Sans", sans-serif;
      letter-spacing: -1.7px
    }

    .hm-hero h1 span {
      color: #bff0df
    }

    .hm-hero p {
      max-width: 760px;
      margin: 0;
      color: rgba(255, 255, 255, .88);
      font-size: 16px;
      line-height: 1.85
    }

    .hm-hero-actions {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-top: 28px
    }

    .hm-btn {
      display: inline-flex;
      align-items: center;
      gap: 13px;
      padding: 12px 17px;
      border-radius: 11px;
      font-size: 12px;
      font-weight: 800;
      transition: .2s
    }

    .hm-btn-light {
      background: #fff;
      color: var(--hm-dark)
    }

    .hm-btn-outline {
      border: 1px solid rgba(255, 255, 255, .45);
      color: #fff
    }

    .hm-btn:hover {
      transform: translateY(-2px)
    }

    .hm-hero-stat {
      justify-self: end;
      width: 230px;
      min-height: 190px;
      padding: 30px;
      border: 1px solid rgba(255, 255, 255, .2);
      border-radius: 25px;
      background: rgba(255, 255, 255, .1);
      backdrop-filter: blur(12px);
      display: flex;
      flex-direction: column;
      justify-content: center
    }

    .hm-hero-stat strong {
      font: 800 60px/1 "Plus Jakarta Sans", sans-serif;
      color: #fff
    }

    .hm-hero-stat span {
      margin-top: 10px;
      color: rgba(255, 255, 255, .76);
      font-size: 13px;
      line-height: 1.6
    }

    .hm-section {
      padding: 86px 0;
      background: #fff
    }

    .hm-section-head {
      display: flex;
      align-items: end;
      justify-content: space-between;
      gap: 30px;
      margin-bottom: 35px
    }

    .hm-label {
      color: var(--hm-primary);
      margin-bottom: 8px
    }

    .hm-section-head h2,
    .hd-heading h2,
    .hd-text-card h2,
    .hd-social h2 {
      margin: 0;
      color: var(--hm-dark);
      font: 800 clamp(27px, 3vw, 36px)/1.25 "Plus Jakarta Sans", sans-serif;
      letter-spacing: -.7px
    }

    .hm-section-head p {
      max-width: 750px;
      margin: 9px 0 0;
      font-size: 14px;
      line-height: 1.75
    }

    .hm-total {
      min-width: 100px;
      text-align: center;
      padding: 14px 18px;
      border: 1px solid var(--hm-border);
      border-radius: 15px;
      background: var(--hm-light)
    }

    .hm-total b {
      display: block;
      color: var(--hm-primary);
      font: 800 25px "Plus Jakarta Sans", sans-serif
    }

    .hm-total span {
      font-size: 11px;
      color: var(--hm-muted)
    }

    .hm-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 22px
    }

    .hm-card {
      overflow: hidden;
      border: 1px solid var(--hm-border);
      border-radius: 21px;
      background: #fff;
      box-shadow: 0 12px 35px rgba(18, 55, 42, .055);
      transition: .25s
    }

    .hm-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 22px 50px rgba(18, 55, 42, .12)
    }

    .hm-card-image {
      height: 235px;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #f2faf7, #e6f3ee);
      overflow: hidden
    }

    .hm-card-image img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      padding: 25px;
      transition: .3s
    }

    .hm-card:hover .hm-card-image img {
      transform: scale(1.04)
    }

    .hm-card-arrow {
      position: absolute;
      right: 14px;
      top: 14px;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: grid;
      place-items: center;
      background: #fff;
      color: var(--hm-dark);
      box-shadow: 0 7px 18px rgba(0, 0, 0, .1);
      font-weight: 800
    }

    .hm-fallback {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: var(--hm-primary)
    }

    .hm-fallback span {
      width: 70px;
      height: 70px;
      border-radius: 20px;
      display: grid;
      place-items: center;
      background: #fff;
      font: 800 30px "Plus Jakarta Sans", sans-serif;
      box-shadow: 0 12px 30px rgba(8, 127, 91, .12)
    }

    .hm-fallback small {
      margin-top: 8px;
      font-weight: 800;
      letter-spacing: 2px
    }

    .hm-card-body {
      padding: 22px;
      display: flex;
      flex-direction: column;
      min-height: 310px
    }

    .hm-badge {
      display: inline-flex;
      width: max-content;
      max-width: 100%;
      padding: 6px 10px;
      border-radius: 50px;
      background: #e9f7f1;
      color: var(--hm-primary);
      font-size: 10px;
      font-weight: 800
    }

    .hm-card h3 {
      margin: 13px 0 7px;
      color: var(--hm-dark);
      font: 800 22px/1.25 "Plus Jakarta Sans", sans-serif
    }

    .hm-card p {
      margin: 0;
      color: #687a74;
      font-size: 13px;
      line-height: 1.75
    }

    .hm-card-meta {
      display: grid;
      grid-template-columns: 1fr;
      gap: 8px;
      margin-top: 17px;
      padding-top: 14px;
      border-top: 1px solid #edf2f0
    }

    .hm-card-meta span {
      display: flex;
      flex-direction: column
    }

    .hm-card-meta small {
      font-size: 9px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #94a19c;
      font-weight: 800
    }

    .hm-card-meta b {
      font-size: 11px;
      color: #486059;
      margin-top: 2px
    }

    .hm-detail-btn {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      margin-top: auto;
      padding: 11px 14px;
      border-radius: 11px;
      background: var(--hm-dark);
      color: #fff !important;
      font-size: 12px;
      font-weight: 800;
      transition: .2s
    }

    .hm-detail-btn:hover {
      background: var(--hm-primary);
      transform: translateY(-1px)
    }

    .hm-detail-btn span {
      font-size: 17px
    }

    .hm-detail-btn-inline {
      display: inline-flex !important;
      width: max-content;
      margin-top: 20px !important
    }

    .hm-empty {
      padding: 55px 25px;
      border: 1px dashed #cfded8;
      border-radius: 20px;
      background: var(--hm-light);
      text-align: center
    }

    .hm-empty-icon {
      width: 55px;
      height: 55px;
      margin: 0 auto 13px;
      border-radius: 17px;
      display: grid;
      place-items: center;
      background: #dff3eb;
      color: var(--hm-primary);
      font-size: 25px;
      font-weight: 800
    }

    .hm-empty h3,
    .hm-empty h2 {
      margin: 0;
      color: var(--hm-dark);
      font: 800 23px "Plus Jakarta Sans", sans-serif
    }

    .hm-empty p {
      margin: 8px auto 0;
      max-width: 520px;
      font-size: 13px;
      line-height: 1.7
    }

    .hm-info-panel {
      margin-top: 42px;
      padding: 29px 32px;
      border-radius: 20px;
      background: var(--hm-dark);
      color: #fff;
      display: grid;
      grid-template-columns: 1.4fr .9fr;
      gap: 30px;
      align-items: center
    }

    .hm-info-panel .hm-label {
      color: #9ee1cb
    }

    .hm-info-panel h3 {
      margin: 4px 0 8px;
      color: #fff;
      font: 800 24px "Plus Jakarta Sans", sans-serif
    }

    .hm-info-panel p {
      margin: 0;
      color: #d4e5df;
      font-size: 13px;
      line-height: 1.75
    }

    .hm-info-points {
      display: grid;
      gap: 9px
    }

    .hm-info-points span {
      padding: 10px 12px;
      border: 1px solid rgba(255, 255, 255, .13);
      border-radius: 10px;
      color: #e6f2ee;
      font-size: 12px;
      background: rgba(255, 255, 255, .05)
    }

    /* DETAIL */
    .hd-hero {
      padding: 38px 0 55px;
      background: linear-gradient(135deg, #f4fbf8, #fff 65%, #eaf7f2);
      border-bottom: 1px solid var(--hm-border)
    }

    .hd-breadcrumb {
      color: #74857f;
      margin-bottom: 30px
    }

    .hd-breadcrumb a {
      color: var(--hm-primary)
    }

    .hd-profile {
      display: grid;
      grid-template-columns: 260px 1fr;
      gap: 38px;
      align-items: center
    }

    .hd-logo-box {
      height: 260px;
      border: 1px solid #dfeae5;
      border-radius: 28px;
      background: #fff;
      display: grid;
      place-items: center;
      box-shadow: 0 20px 50px rgba(18, 55, 42, .08);
      overflow: hidden
    }

    .hd-logo-box img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      padding: 30px
    }

    .hd-logo-fallback {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: var(--hm-primary)
    }

    .hd-logo-fallback strong {
      width: 85px;
      height: 85px;
      border-radius: 25px;
      display: grid;
      place-items: center;
      background: #e7f7f1;
      font: 800 38px "Plus Jakarta Sans", sans-serif
    }

    .hd-logo-fallback span {
      margin-top: 9px;
      font-weight: 800;
      letter-spacing: 2px
    }

    .hd-badge {
      margin-bottom: 12px
    }

    .hd-profile-content h1 {
      margin: 0 0 12px;
      color: var(--hm-dark);
      font: 800 clamp(35px, 5vw, 54px)/1.1 "Plus Jakarta Sans", sans-serif;
      letter-spacing: -1.3px
    }

    .hd-profile-content>p {
      max-width: 820px;
      margin: 0;
      color: #61746d;
      font-size: 15px;
      line-height: 1.85
    }

    .hd-focus {
      margin-top: 18px;
      padding: 14px 16px;
      border-left: 4px solid var(--hm-primary);
      border-radius: 0 12px 12px 0;
      background: #eaf7f2
    }

    .hd-focus span {
      display: block;
      color: #71837c;
      font-size: 10px;
      text-transform: uppercase;
      letter-spacing: 1.1px;
      font-weight: 800
    }

    .hd-focus strong {
      display: block;
      margin-top: 4px;
      color: var(--hm-dark);
      font-size: 13px;
      line-height: 1.6
    }

    .hd-contact-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      margin-top: 32px
    }

    .hd-contact-grid>div {
      padding: 16px;
      border: 1px solid #dfeae5;
      border-radius: 14px;
      background: #fff
    }

    .hd-contact-grid small {
      display: block;
      color: #8a9893;
      font-size: 9px;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: 800
    }

    .hd-contact-grid strong,
    .hd-contact-grid a {
      display: block;
      margin-top: 5px;
      color: var(--hm-dark);
      font-size: 12px;
      font-weight: 800;
      overflow-wrap: anywhere
    }

    .hd-section {
      padding-top: 65px
    }

    .hd-stats {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 14px;
      margin-bottom: 38px
    }

    .hd-stats>div {
      padding: 20px;
      border: 1px solid var(--hm-border);
      border-radius: 16px;
      background: var(--hm-light);
      text-align: center
    }

    .hd-stats strong {
      display: block;
      color: var(--hm-primary);
      font: 800 30px "Plus Jakarta Sans", sans-serif
    }

    .hd-stats span {
      font-size: 11px;
      color: #71817b
    }

    .hd-vision-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
      margin-bottom: 62px
    }

    .hd-text-card {
      padding: 27px;
      border: 1px solid var(--hm-border);
      border-radius: 20px;
      background: #fff;
      box-shadow: 0 12px 35px rgba(18, 55, 42, .045)
    }

    .hd-text-card p {
      margin: 12px 0 0;
      color: #667972;
      font-size: 14px;
      line-height: 1.9
    }

    .hd-block {
      padding-top: 55px;
      margin-top: 55px;
      border-top: 1px solid #eaf0ed
    }

    .hd-heading {
      margin-bottom: 24px
    }

    .hd-heading p {
      margin: 7px 0 0;
      color: #7a8984;
      font-size: 13px
    }

    .hd-people-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px
    }

    .hd-person {
      padding: 15px;
      border: 1px solid var(--hm-border);
      border-radius: 17px;
      background: #fff;
      box-shadow: 0 8px 25px rgba(18, 55, 42, .04)
    }

    .hd-person-photo {
      height: 205px;
      border-radius: 12px;
      overflow: hidden;
      background: #edf6f2;
      display: grid;
      place-items: center;
      color: var(--hm-primary)
    }

    .hd-person-photo img {
      width: 100%;
      height: 100%;
      object-fit: cover
    }

    .hd-person-photo span {
      width: 60px;
      height: 60px;
      border-radius: 18px;
      background: #fff;
      display: grid;
      place-items: center;
      font: 800 25px "Plus Jakarta Sans", sans-serif
    }

    .hd-person h3 {
      margin: 13px 0 3px;
      color: var(--hm-dark);
      font: 800 14px/1.4 "Plus Jakarta Sans", sans-serif
    }

    .hd-person p {
      margin: 0;
      color: var(--hm-primary);
      font-size: 11px;
      font-weight: 700
    }

    .hd-table-wrap {
      overflow: auto;
      border: 1px solid var(--hm-border);
      border-radius: 17px
    }

    .hd-table {
      width: 100%;
      min-width: 760px;
      border-collapse: collapse;
      background: #fff
    }

    .hd-table th,
    .hd-table td {
      padding: 13px 14px;
      border-bottom: 1px solid #edf2f0;
      text-align: left;
      font-size: 12px
    }

    .hd-table th {
      background: #f5faf8;
      color: #61746d;
      font-size: 10px;
      text-transform: uppercase;
      letter-spacing: .6px
    }

    .hd-table td strong {
      color: var(--hm-dark)
    }

    .hd-activity-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px
    }

    .hd-activity {
      overflow: hidden;
      border: 1px solid var(--hm-border);
      border-radius: 18px;
      background: #fff
    }

    .hd-activity-photo {
      height: 205px;
      background: #edf6f2
    }

    .hd-activity-photo img {
      width: 100%;
      height: 100%;
      object-fit: cover
    }

    .hd-activity-body {
      padding: 19px
    }

    .hd-activity-body>span {
      color: var(--hm-primary);
      font-size: 10px;
      font-weight: 800
    }

    .hd-activity-body h3 {
      margin: 7px 0;
      color: var(--hm-dark);
      font: 800 16px/1.4 "Plus Jakarta Sans", sans-serif
    }

    .hd-activity-body p {
      margin: 0;
      color: #6c7d77;
      font-size: 12px;
      line-height: 1.75
    }

    .hd-gallery {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 14px
    }

    .hd-gallery figure {
      margin: 0;
      overflow: hidden;
      border: 1px solid var(--hm-border);
      border-radius: 16px;
      background: #fff
    }

    .hd-gallery img {
      width: 100%;
      height: 190px;
      object-fit: cover
    }

    .hd-gallery figcaption {
      padding: 11px 12px
    }

    .hd-gallery figcaption strong,
    .hd-gallery figcaption span {
      display: block
    }

    .hd-gallery figcaption strong {
      color: var(--hm-dark);
      font-size: 11px
    }

    .hd-gallery figcaption span {
      margin-top: 3px;
      color: #7b8b85;
      font-size: 10px;
      line-height: 1.5
    }

    .hd-social {
      margin-top: 55px;
      padding: 28px;
      border-radius: 20px;
      background: var(--hm-dark);
      color: #fff;
      display: grid;
      grid-template-columns: 1fr auto;
      gap: 30px;
      align-items: center
    }

    .hd-social .hm-label {
      color: #9ee1cb
    }

    .hd-social h2 {
      color: #fff;
      margin-top: 4px
    }

    .hd-social p {
      margin: 8px 0 0;
      color: #d2e4dd;
      font-size: 12px
    }

    .hd-social-links {
      display: flex;
      gap: 9px;
      flex-wrap: wrap;
      justify-content: flex-end
    }

    .hd-social-links a {
      padding: 10px 13px;
      border: 1px solid rgba(255, 255, 255, .16);
      border-radius: 10px;
      background: rgba(255, 255, 255, .06);
      color: #fff;
      font-size: 11px;
      font-weight: 800
    }

    .hd-social-links a:hover {
      background: var(--hm-primary)
    }

    .hd-social-links span {
      margin-left: 8px
    }

    .hd-back {
      text-align: center;
      padding-top: 42px
    }

    .hd-back a {
      display: inline-flex;
      padding: 12px 17px;
      border: 1px solid #d9e6e1;
      border-radius: 11px;
      color: var(--hm-dark);
      font-size: 12px;
      font-weight: 800;
      background: #fff
    }

    .hd-back a:hover {
      border-color: var(--hm-primary);
      color: var(--hm-primary)
    }

    .hm-not-found {
      min-height: 50vh;
      display: grid;
      place-items: center
    }

    @media(max-width:1000px) {
      .hm-hero-grid {
        grid-template-columns: 1fr
      }

      .hm-hero-stat {
        justify-self: start;
        width: 220px;
        min-height: auto
      }

      .hm-grid {
        grid-template-columns: repeat(2, 1fr)
      }

      .hm-info-panel {
        grid-template-columns: 1fr
      }

      .hd-profile {
        grid-template-columns: 210px 1fr
      }

      .hd-logo-box {
        height: 210px
      }

      .hd-contact-grid {
        grid-template-columns: repeat(2, 1fr)
      }

      .hd-stats {
        grid-template-columns: repeat(2, 1fr)
      }

      .hd-people-grid {
        grid-template-columns: repeat(2, 1fr)
      }

      .hd-activity-grid {
        grid-template-columns: repeat(2, 1fr)
      }

      .hd-gallery {
        grid-template-columns: repeat(3, 1fr)
      }
    }

    @media(max-width:650px) {
      .hm-container {
        width: calc(100% - 28px)
      }

      .hm-hero {
        padding: 52px 0 60px
      }

      .hm-hero h1 {
        font-size: 38px
      }

      .hm-hero p {
        font-size: 14px
      }

      .hm-section {
        padding: 62px 0
      }

      .hm-section-head {
        display: block
      }

      .hm-total {
        margin-top: 18px;
        width: max-content
      }

      .hm-grid {
        grid-template-columns: 1fr
      }

      .hm-card-image {
        height: 220px
      }

      .hm-card-body {
        min-height: 290px
      }

      .hm-info-panel {
        padding: 23px
      }

      .hd-hero {
        padding-top: 25px
      }

      .hd-profile {
        grid-template-columns: 1fr;
        gap: 22px
      }

      .hd-logo-box {
        height: 250px
      }

      .hd-profile-content h1 {
        font-size: 35px
      }

      .hd-contact-grid {
        grid-template-columns: 1fr
      }

      .hd-stats {
        grid-template-columns: repeat(2, 1fr)
      }

      .hd-vision-grid {
        grid-template-columns: 1fr
      }

      .hd-people-grid,
      .hd-activity-grid {
        grid-template-columns: 1fr
      }

      .hd-gallery {
        grid-template-columns: repeat(2, 1fr)
      }

      .hd-gallery img {
        height: 150px
      }

      .hd-social {
        grid-template-columns: 1fr;
        padding: 23px
      }

      .hd-social-links {
        justify-content: flex-start
      }

      .hd-heading h2 {
        font-size: 27px
      }
    }
  </style>
</head>

<body>
  <!-- TOPBAR REUSABLE -->
  <?php require_once __DIR__ . '/../menu/topbar.php'; ?>

  <!-- NAVBAR REUSABLE -->
  <?php require_once __DIR__ . '/../menu/navbar.php'; ?>

  <main class="hm-page">
    <section class="hm-hero">
      <div class="hm-container">
        <div class="hm-breadcrumb">
          <a href="/fikes/">Beranda</a><span>›</span><span>Kemahasiswaan</span><span>›</span><span>Himpunan
            Mahasiswa</span>
        </div>
        <div class="hm-hero-grid">
          <div>
            <span class="hm-eyebrow">KEMAHASISWAAN FIKES</span>
            <h1>Himpunan <span>Mahasiswa FIKES</span></h1>
            <p>Kenali himpunan mahasiswa yang menjadi wadah pengembangan potensi, kepemimpinan, aspirasi, dan
              kolaborasi mahasiswa di lingkungan Fakultas Ilmu Kesehatan.</p>
            <div class="hm-hero-actions">
              <a class="hm-btn hm-btn-light" href="#daftar-himpunan">Lihat Himpunan <b>↓</b></a>
              <a class="hm-btn hm-btn-outline" href="/fikes/kemahasiswaan/ukm">Lihat UKM <b>→</b></a>
            </div>
          </div>
          <div class="hm-hero-stat">
            <strong><?= $total ?></strong>
            <span>Himpunan aktif<br>terdata di FIKES</span>
          </div>
        </div>
      </div>
    </section>

    <section class="hm-section" id="daftar-himpunan">
      <div class="hm-container">
        <div class="hm-section-head">
          <div>
            <span class="hm-label">ORGANISASI MAHASISWA</span>
            <h2>Daftar Himpunan Mahasiswa</h2>
            <p>Seluruh data di bawah ini diambil langsung dari tabel <b>kemahasiswaan_organisasi</b> dan hanya
              menampilkan data dengan status <b>aktif</b>.</p>
          </div>
          <div class="hm-total"><b><?= $total ?></b><span>Organisasi</span></div>
        </div>

        <?php if ($himpunan): ?>
          <div class="hm-grid">
            <?php foreach ($himpunan as $item): ?>
              <?php $img = hm_image_url($item['logo'] ?? ''); ?>
              <article class="hm-card">
                <a class="hm-card-image" href="/fikes/kemahasiswaan/himpunan/<?= e($item['slug']) ?>">
                  <?php if ($img): ?>
                    <img src="<?= e($img) ?>" alt="Logo <?= e($item['nama']) ?>" loading="lazy">
                  <?php else: ?>
                    <div class="hm-fallback"><span>F</span><small>FIKES</small></div>
                  <?php endif; ?>
                  <span class="hm-card-arrow">↗</span>
                </a>
                <div class="hm-card-body">
                  <span class="hm-badge"><?= e($item['kategori'] ?: 'Himpunan Mahasiswa') ?></span>
                  <h3><?= e($item['nama']) ?></h3>
                  <p><?= e(hm_excerpt($item['deskripsi'])) ?></p>
                  <div class="hm-card-meta">
                    <?php if (!empty($item['ketua_nama'])): ?>
                      <span><small>Ketua</small><b><?= e($item['ketua_nama']) ?></b></span>
                    <?php endif; ?>
                    <?php if (!empty($item['fokus'])): ?>
                      <span><small>Fokus</small><b><?= e(hm_excerpt($item['fokus'], 60)) ?></b></span>
                    <?php endif; ?>
                  </div>
                  <a class="hm-detail-btn" href="/fikes/kemahasiswaan/himpunan/<?= e($item['slug']) ?>">Lihat Detail
                    <span>→</span></a>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="hm-empty">
            <div class="hm-empty-icon">⌁</div>
            <h3>Belum ada data himpunan</h3>
            <p>Belum ada organisasi jenis <b>himpunan</b> dengan status <b>aktif</b> pada database.</p>
          </div>
        <?php endif; ?>

        <div class="hm-info-panel">
          <div>
            <span class="hm-label">INFORMASI</span>
            <h3>Kelola dari Dashboard Admin</h3>
            <p>Nama, slug, kategori, deskripsi, fokus, visi, misi, ketua, sekretariat, kontak, media sosial, logo,
              status, dan urutan tampil mengikuti data yang dikelola melalui menu Kemahasiswaan pada Dashboard Admin.
            </p>
          </div>
          <div class="hm-info-points">
            <span>✓ Profil organisasi</span>
            <span>✓ Pengurus & anggota</span>
            <span>✓ Kegiatan & galeri</span>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php require_once __DIR__ . '/../menu/footer.php'; ?>
</body>

</html>
