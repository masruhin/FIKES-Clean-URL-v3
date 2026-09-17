<?php
require_once __DIR__ . '/../../admin/config/database.php';

if (!function_exists('e')) {
  function e($value)
  {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
  }
}

function ukm_public_image($value, $folder = 'logo')
{
  $value = trim((string)$value);
  if ($value === '') return '';

  if (preg_match('~^https?://~i', $value) || str_starts_with($value, '/')) {
    return $value;
  }

  if (str_starts_with($value, 'vendor/')) {
    return '/fikes/' . ltrim($value, '/');
  }

  return '/fikes/admin/uploads/kemahasiswaan/' . $folder . '/' . rawurlencode(basename($value));
}

function ukm_excerpt($value, $limit = 180)
{
  $text = trim(strip_tags((string)$value));
  if (mb_strlen($text) <= $limit) return $text;
  return mb_substr($text, 0, $limit - 1) . '…';
}

function ukm_date($value)
{
  if (!$value) return '-';
  $bulan = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
  ];
  $time = strtotime($value);
  return $time
    ? date('d', $time) . ' ' . $bulan[(int)date('m', $time)] . ' ' . date('Y', $time)
    : $value;
}

$slug = trim($_GET['slug'] ?? '');

if ($slug === '') {
  http_response_code(404);
  exit('Data UKM tidak ditemukan.');
}

$st = $pdo->prepare("
    SELECT *
    FROM kemahasiswaan_organisasi
    WHERE jenis = 'ukm'
      AND slug = ?
      AND status = 'aktif'
    LIMIT 1
");
$st->execute([$slug]);
$org = $st->fetch(PDO::FETCH_ASSOC);

if (!$org) {
  http_response_code(404);
  $page_title = 'UKM Tidak Ditemukan';
} else {
  $page_title = $org['nama'];
}

$pengurus = [];
$anggota = [];
$kegiatan = [];
$galeri = [];

if ($org) {
  $q = $pdo->prepare("
        SELECT *
        FROM kemahasiswaan_pengurus
        WHERE organisasi_slug = ?
          AND status = 'aktif'
        ORDER BY nomor_urut ASC, id ASC
    ");
  $q->execute([$org['slug']]);
  $pengurus = $q->fetchAll(PDO::FETCH_ASSOC);

  $q = $pdo->prepare("
        SELECT *
        FROM kemahasiswaan_anggota
        WHERE organisasi_slug = ?
          AND status = 'aktif'
        ORDER BY nama ASC, id ASC
    ");
  $q->execute([$org['slug']]);
  $anggota = $q->fetchAll(PDO::FETCH_ASSOC);

  $q = $pdo->prepare("
        SELECT *
        FROM kemahasiswaan_kegiatan
        WHERE organisasi_slug = ?
          AND status = 'publish'
        ORDER BY tanggal_kegiatan DESC, nomor_urut ASC, id DESC
    ");
  $q->execute([$org['slug']]);
  $kegiatan = $q->fetchAll(PDO::FETCH_ASSOC);

  $q = $pdo->prepare("
        SELECT *
        FROM kemahasiswaan_galeri
        WHERE organisasi_slug = ?
          AND status = 'publish'
        ORDER BY nomor_urut ASC, id DESC
    ");
  $q->execute([$org['slug']]);
  $galeri = $q->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!doctype html>
<html lang="id">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?> | FIKES - Fakultas Ilmu Kesehatan</title>
    <meta name="description"
      content="<?= e($org ? ukm_excerpt($org['deskripsi'], 155) : 'Informasi Unit Kegiatan Mahasiswa FIKES.') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap"
      rel="stylesheet">
    <link rel="stylesheet" href="/fikes/assets/css/style.css">
  </head>
  <style>
  :root {
    --ukm-primary: #087f5b;
    --ukm-dark: #12372a;
    --ukm-text: #566a63;
    --ukm-muted: #74847e;
    --ukm-bg: #f6faf8;
    --ukm-border: #e1ebe7;
    --ukm-soft: #e7f7f1;
    --ukm-white: #fff;
  }

  .ukm-hero,
  .ukm-detail-hero {
    position: relative;
    overflow: hidden;
    color: #fff;
    background:
      radial-gradient(circle at 90% 15%, rgba(255, 255, 255, .18), transparent 28%),
      linear-gradient(120deg, #00685a 0%, #008f78 55%, #8acfc0 150%);
  }

  .ukm-hero {
    padding: 64px 0 70px
  }

  .ukm-detail-hero {
    padding: 48px 0 62px
  }

  .ukm-hero:after,
  .ukm-detail-hero:after {
    content: "";
    position: absolute;
    width: 390px;
    height: 390px;
    right: -150px;
    bottom: -250px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .07);
    pointer-events: none
  }

  .ukm-breadcrumb {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-wrap: wrap;
    margin-bottom: 22px;
    font-size: 13px;
    color: rgba(255, 255, 255, .82);
    position: relative;
    z-index: 1
  }

  .ukm-breadcrumb a {
    color: #fff;
    text-decoration: none;
    font-weight: 700
  }

  .ukm-eyebrow,
  .ukm-label {
    display: inline-block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1.6px;
    text-transform: uppercase
  }

  .ukm-eyebrow {
    color: #dff7ef;
    position: relative;
    z-index: 1
  }

  .ukm-label {
    color: var(--ukm-primary)
  }

  .ukm-hero h1,
  .ukm-detail-hero h1 {
    position: relative;
    z-index: 1;
    margin: 10px 0 14px;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-weight: 800;
    line-height: 1.08
  }

  .ukm-hero h1 {
    font-size: clamp(34px, 5vw, 58px)
  }

  .ukm-detail-hero h1 {
    font-size: clamp(32px, 4vw, 50px)
  }

  .ukm-hero h1 span {
    color: #c9f2e6
  }

  .ukm-hero>.container>p {
    position: relative;
    z-index: 1;
    max-width: 760px;
    margin: 0;
    color: rgba(255, 255, 255, .9);
    font-size: 16px;
    line-height: 1.8
  }

  .ukm-stats {
    position: relative;
    z-index: 1;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 30px
  }

  .ukm-stats>div {
    min-width: 135px;
    padding: 13px 16px;
    border: 1px solid rgba(255, 255, 255, .18);
    border-radius: 14px;
    background: rgba(255, 255, 255, .09);
    backdrop-filter: blur(5px)
  }

  .ukm-stats strong {
    display: block;
    font: 800 21px "Plus Jakarta Sans", sans-serif
  }

  .ukm-stats span {
    display: block;
    margin-top: 2px;
    font-size: 11px;
    color: rgba(255, 255, 255, .75)
  }

  .ukm-section,
  .ukm-detail-section {
    padding: 72px 0 82px;
    background: #fff
  }

  .ukm-heading {
    text-align: center;
    max-width: 760px;
    margin: 0 auto 38px
  }

  .ukm-heading h2,
  .ukm-section-head h2 {
    margin: 7px 0 8px;
    color: var(--ukm-dark);
    font: 800 30px "Plus Jakarta Sans", sans-serif
  }

  .ukm-heading p,
  .ukm-section-head p {
    margin: 0;
    color: var(--ukm-muted);
    font-size: 14px;
    line-height: 1.75
  }

  .ukm-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 20px
  }

  .ukm-card {
    min-width: 0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    border: 1px solid var(--ukm-border);
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 9px 28px rgba(18, 55, 42, .06);
    transition: .22s ease
  }

  .ukm-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 18px 42px rgba(18, 55, 42, .12)
  }

  .ukm-card-image {
    height: 205px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 22px;
    background: linear-gradient(180deg, #f5faf8, #edf6f2);
    border-bottom: 1px solid #edf2f0;
    text-decoration: none
  }

  .ukm-card-image img {
    width: 100%;
    height: 100%;
    object-fit: contain
  }

  .ukm-image-fallback {
    width: 90px;
    height: 90px;
    display: none;
    align-items: center;
    justify-content: center;
    border-radius: 22px;
    background: var(--ukm-soft);
    color: var(--ukm-primary);
    font: 800 22px "Plus Jakarta Sans", sans-serif
  }

  .ukm-card-body {
    display: flex;
    flex: 1;
    flex-direction: column;
    padding: 21px
  }

  .ukm-badge {
    display: inline-flex;
    width: max-content;
    max-width: 100%;
    padding: 5px 9px;
    border-radius: 999px;
    background: var(--ukm-soft);
    color: var(--ukm-primary);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .5px;
    text-transform: uppercase
  }

  .ukm-badge-light {
    background: rgba(255, 255, 255, .14);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, .18)
  }

  .ukm-card h3 {
    margin: 11px 0 7px;
    color: var(--ukm-dark);
    font: 800 19px "Plus Jakarta Sans", sans-serif
  }

  .ukm-card p {
    margin: 0;
    color: #687a74;
    font-size: 13px;
    line-height: 1.7
  }

  .ukm-focus {
    margin-top: 14px;
    padding: 10px 11px;
    border-radius: 11px;
    background: #f6faf8
  }

  .ukm-focus span {
    display: block;
    color: var(--ukm-primary);
    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .8px
  }

  .ukm-focus strong {
    display: block;
    margin-top: 3px;
    color: #53655f;
    font-size: 11px;
    line-height: 1.5
  }

  .ukm-detail-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-top: auto;
    padding: 11px 13px;
    border-radius: 12px;
    background: var(--ukm-dark);
    color: #fff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 800;
    transition: .2s ease
  }

  .ukm-detail-btn:hover {
    background: var(--ukm-primary);
    transform: translateY(-1px)
  }

  .ukm-detail-btn b {
    font-size: 17px
  }

  .ukm-inline-btn {
    display: inline-flex;
    margin-top: 15px
  }

  .ukm-empty,
  .ukm-no-data {
    border: 1px dashed #d6e4df;
    border-radius: 18px;
    background: #f9fcfb;
    text-align: center;
    color: var(--ukm-muted)
  }

  .ukm-empty {
    padding: 55px 24px
  }

  .ukm-no-data {
    padding: 28px 18px;
    font-size: 13px
  }

  .ukm-empty-icon {
    width: 54px;
    height: 54px;
    margin: 0 auto 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: var(--ukm-soft);
    color: var(--ukm-primary);
    font: 800 23px "Plus Jakarta Sans", sans-serif
  }

  .ukm-empty h2,
  .ukm-empty h3 {
    margin: 0 0 7px;
    color: var(--ukm-dark);
    font-family: "Plus Jakarta Sans", sans-serif
  }

  .ukm-empty p {
    margin: 0;
    font-size: 13px;
    line-height: 1.7
  }

  .ukm-cta {
    margin-top: 32px;
    padding: 28px 30px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    background: var(--ukm-dark);
    color: #fff
  }

  .ukm-cta .ukm-label {
    color: #bfeadd
  }

  .ukm-cta h3 {
    margin: 6px 0 6px;
    color: #fff;
    font: 800 23px "Plus Jakarta Sans", sans-serif
  }

  .ukm-cta p {
    margin: 0;
    color: #d7e8e2;
    font-size: 13px;
    line-height: 1.6
  }

  .ukm-cta>a {
    flex: 0 0 auto;
    padding: 12px 15px;
    border-radius: 11px;
    background: #fff;
    color: var(--ukm-dark);
    text-decoration: none;
    font-size: 12px;
    font-weight: 800
  }

  .ukm-cta>a span {
    margin-left: 8px
  }

  .ukm-profile {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 34px;
    align-items: center
  }

  .ukm-profile-logo {
    height: 240px;
    padding: 24px;
    border-radius: 25px;
    background: rgba(255, 255, 255, .96);
    box-shadow: 0 20px 45px rgba(0, 0, 0, .12);
    display: flex;
    align-items: center;
    justify-content: center
  }

  .ukm-profile-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain
  }

  .ukm-profile-fallback {
    font: 800 40px "Plus Jakarta Sans", sans-serif;
    color: var(--ukm-primary)
  }

  .ukm-profile-content h1 {
    margin-top: 11px
  }

  .ukm-profile-description {
    margin: 0;
    max-width: 800px;
    color: rgba(255, 255, 255, .9);
    font-size: 15px;
    line-height: 1.8
  }

  .ukm-profile-focus {
    margin-top: 18px;
    max-width: 780px;
    padding: 14px 16px;
    border-radius: 13px;
    border: 1px solid rgba(255, 255, 255, .18);
    background: rgba(255, 255, 255, .08)
  }

  .ukm-profile-focus span {
    display: block;
    color: #c9f2e6;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px
  }

  .ukm-profile-focus strong {
    display: block;
    margin-top: 4px;
    color: #fff;
    font-size: 13px;
    line-height: 1.65
  }

  .ukm-overview-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
    margin-bottom: 55px
  }

  .ukm-panel {
    min-width: 0;
    padding: 27px;
    border: 1px solid var(--ukm-border);
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 8px 25px rgba(18, 55, 42, .05)
  }

  .ukm-panel-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 22px
  }

  .ukm-panel-title h2 {
    margin: 4px 0 0;
    color: var(--ukm-dark);
    font: 800 21px "Plus Jakarta Sans", sans-serif
  }

  .ukm-panel-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: var(--ukm-soft);
    color: var(--ukm-primary);
    font-weight: 800
  }

  .ukm-info-list {
    display: grid;
    gap: 0
  }

  .ukm-info-row {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    padding: 12px 0;
    border-bottom: 1px solid #edf2f0
  }

  .ukm-info-row:last-child {
    border-bottom: 0
  }

  .ukm-info-row span {
    color: #778780;
    font-size: 12px
  }

  .ukm-info-row strong {
    max-width: 65%;
    text-align: right;
    color: #304b42;
    font-size: 12px;
    line-height: 1.5
  }

  .ukm-socials {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 16px
  }

  .ukm-socials a {
    padding: 8px 10px;
    border-radius: 9px;
    background: #f1f7f4;
    color: var(--ukm-primary);
    text-decoration: none;
    font-size: 11px;
    font-weight: 800
  }

  .ukm-text-block {
    margin-bottom: 18px
  }

  .ukm-text-block:last-child {
    margin-bottom: 0
  }

  .ukm-text-block h3 {
    margin: 0 0 5px;
    color: var(--ukm-dark);
    font-size: 14px
  }

  .ukm-text-block p {
    margin: 0;
    color: #64766f;
    font-size: 13px;
    line-height: 1.8
  }

  .ukm-muted {
    margin: 0;
    color: #7a8984;
    font-size: 13px;
    line-height: 1.7
  }

  .ukm-content-block {
    margin-top: 52px
  }

  .ukm-section-head {
    display: flex;
    align-items: end;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 22px
  }

  .ukm-count {
    flex: 0 0 auto;
    padding: 7px 10px;
    border-radius: 999px;
    background: var(--ukm-soft);
    color: var(--ukm-primary);
    font-size: 10px;
    font-weight: 800
  }

  .ukm-pengurus-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px
  }

  .ukm-person-card {
    overflow: hidden;
    border: 1px solid var(--ukm-border);
    border-radius: 17px;
    background: #fff;
    box-shadow: 0 6px 20px rgba(18, 55, 42, .05)
  }

  .ukm-person-photo {
    height: 190px;
    background: #eef6f2;
    display: flex;
    align-items: center;
    justify-content: center
  }

  .ukm-person-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover
  }

  .ukm-person-photo span,
  .ukm-member-avatar span {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: var(--ukm-soft);
    color: var(--ukm-primary);
    font: 800 27px "Plus Jakarta Sans", sans-serif
  }

  .ukm-person-photo span {
    width: 78px;
    height: 78px
  }

  .ukm-person-body {
    padding: 16px
  }

  .ukm-person-body span {
    color: var(--ukm-primary);
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .7px
  }

  .ukm-person-body h3 {
    margin: 5px 0 0;
    color: var(--ukm-dark);
    font: 800 15px "Plus Jakarta Sans", sans-serif
  }

  .ukm-member-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 13px
  }

  .ukm-member-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px;
    border: 1px solid var(--ukm-border);
    border-radius: 15px;
    background: #fff
  }

  .ukm-member-avatar {
    flex: 0 0 auto;
    width: 52px;
    height: 52px;
    border-radius: 50%;
    overflow: hidden;
    background: #eef6f2;
    display: flex;
    align-items: center;
    justify-content: center
  }

  .ukm-member-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover
  }

  .ukm-member-avatar span {
    width: 100%;
    height: 100%;
    font-size: 18px
  }

  .ukm-member-card h3 {
    margin: 0;
    color: var(--ukm-dark);
    font-size: 13px
  }

  .ukm-member-role {
    display: inline-block;
    margin-top: 3px;
    color: var(--ukm-primary);
    font-size: 10px;
    font-weight: 800
  }

  .ukm-member-meta {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 5px
  }

  .ukm-member-meta span {
    font-size: 9px;
    color: #71817b
  }

  .ukm-activity-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px
  }

  .ukm-activity-card {
    overflow: hidden;
    border: 1px solid var(--ukm-border);
    border-radius: 17px;
    background: #fff
  }

  .ukm-activity-image {
    height: 185px;
    background: #edf5f1
  }

  .ukm-activity-image img {
    width: 100%;
    height: 100%;
    object-fit: cover
  }

  .ukm-activity-body {
    padding: 17px
  }

  .ukm-date {
    display: inline-block;
    color: var(--ukm-primary);
    font-size: 10px;
    font-weight: 800
  }

  .ukm-activity-body h3 {
    margin: 6px 0 7px;
    color: var(--ukm-dark);
    font: 800 16px "Plus Jakarta Sans", sans-serif
  }

  .ukm-activity-body p {
    margin: 0;
    color: #687a74;
    font-size: 12px;
    line-height: 1.7
  }

  .ukm-gallery {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 15px
  }

  .ukm-gallery-item {
    position: relative;
    overflow: hidden;
    min-width: 0;
    height: 230px;
    margin: 0;
    border-radius: 16px;
    background: #edf5f1
  }

  .ukm-gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: .3s ease
  }

  .ukm-gallery-item:hover img {
    transform: scale(1.04)
  }

  .ukm-gallery-item figcaption {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    padding: 28px 13px 13px;
    display: flex;
    flex-direction: column;
    gap: 3px;
    color: #fff;
    background: linear-gradient(transparent, rgba(0, 0, 0, .72))
  }

  .ukm-gallery-item figcaption strong {
    font-size: 12px
  }

  .ukm-gallery-item figcaption span {
    font-size: 10px;
    color: rgba(255, 255, 255, .8)
  }

  .ukm-back-wrap {
    text-align: center;
    margin-top: 52px
  }

  .ukm-back-btn {
    display: inline-flex;
    padding: 12px 16px;
    border-radius: 11px;
    background: var(--ukm-dark);
    color: #fff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 800
  }

  .ukm-back-btn:hover {
    background: var(--ukm-primary)
  }

  @media(max-width:1100px) {
    .ukm-grid {
      grid-template-columns: repeat(3, minmax(0, 1fr))
    }

    .ukm-pengurus-grid {
      grid-template-columns: repeat(3, minmax(0, 1fr))
    }

    .ukm-gallery {
      grid-template-columns: repeat(3, minmax(0, 1fr))
    }
  }

  @media(max-width:900px) {
    .ukm-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr))
    }

    .ukm-overview-grid {
      grid-template-columns: 1fr
    }

    .ukm-pengurus-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr))
    }

    .ukm-member-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr))
    }

    .ukm-activity-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr))
    }

    .ukm-gallery {
      grid-template-columns: repeat(2, minmax(0, 1fr))
    }

    .ukm-profile {
      grid-template-columns: 180px 1fr;
      gap: 24px
    }

    .ukm-profile-logo {
      height: 180px
    }
  }

  @media(max-width:600px) {
    .ukm-hero {
      padding: 50px 0 58px
    }

    .ukm-detail-hero {
      padding: 38px 0 50px
    }

    .ukm-section,
    .ukm-detail-section {
      padding: 55px 0 65px
    }

    .ukm-grid,
    .ukm-pengurus-grid,
    .ukm-member-grid,
    .ukm-activity-grid,
    .ukm-gallery {
      grid-template-columns: 1fr
    }

    .ukm-card-image {
      height: 220px
    }

    .ukm-section-head {
      display: block
    }

    .ukm-count {
      display: inline-flex;
      margin-top: 12px
    }

    .ukm-cta {
      display: block;
      padding: 25px
    }

    .ukm-cta>a {
      display: inline-flex;
      margin-top: 18px
    }

    .ukm-profile {
      grid-template-columns: 1fr
    }

    .ukm-profile-logo {
      width: 190px;
      height: 190px;
      margin: 0 auto
    }

    .ukm-profile-content {
      text-align: center
    }

    .ukm-profile-focus {
      text-align: left
    }

    .ukm-badge {
      margin-left: 0
    }

    .ukm-info-row {
      display: block
    }

    .ukm-info-row strong {
      display: block;
      max-width: none;
      text-align: left;
      margin-top: 3px
    }

    .ukm-panel {
      padding: 21px
    }
  }
  </style>

  <body>

    <?php require_once __DIR__ . '/../menu/topbar.php'; ?>
    <?php require_once __DIR__ . '/../menu/navbar.php'; ?>

    <main>
      <?php if (!$org): ?>
      <section class="ukm-detail-section">
        <div class="container">
          <div class="ukm-empty">
            <div class="ukm-empty-icon">!</div>
            <h2>UKM Tidak Ditemukan</h2>
            <p>Data mungkin sudah dinonaktifkan atau alamat UKM tidak tersedia.</p>
            <a class="ukm-detail-btn ukm-inline-btn" href="/fikes/kemahasiswaan/ukm">
              ← Kembali ke UKM
            </a>
          </div>
        </div>
      </section>
      <?php else: ?>

      <section class="ukm-detail-hero">
        <div class="container">
          <div class="ukm-breadcrumb">
            <a href="/fikes/">Beranda</a>
            <span>›</span>
            <a href="/fikes/kemahasiswaan/ukm">UKM</a>
            <span>›</span>
            <span><?= e($org['nama']) ?></span>
          </div>

          <div class="ukm-profile">
            <div class="ukm-profile-logo">
              <?php $logo = ukm_public_image($org['logo'] ?? '', 'logo'); ?>
              <?php if ($logo): ?>
              <img src="<?= e($logo) ?>" alt="Logo <?= e($org['nama']) ?>">
              <?php else: ?>
              <div class="ukm-profile-fallback">UKM</div>
              <?php endif; ?>
            </div>

            <div class="ukm-profile-content">
              <span class="ukm-badge ukm-badge-light">
                <?= e($org['kategori'] ?: 'UKM FIKES') ?>
              </span>

              <h1><?= e($org['nama']) ?></h1>

              <p class="ukm-profile-description">
                <?= nl2br(e($org['deskripsi'] ?: 'Informasi Unit Kegiatan Mahasiswa FIKES.')) ?>
              </p>

              <?php if (!empty($org['fokus'])): ?>
              <div class="ukm-profile-focus">
                <span>Fokus / Bidang Kegiatan</span>
                <strong><?= nl2br(e($org['fokus'])) ?></strong>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </section>

      <section class="ukm-detail-section">
        <div class="container">

          <div class="ukm-overview-grid">
            <div class="ukm-panel">
              <div class="ukm-panel-title">
                <span class="ukm-panel-icon">i</span>
                <div>
                  <span class="ukm-label">PROFIL</span>
                  <h2>Informasi UKM</h2>
                </div>
              </div>

              <div class="ukm-info-list">
                <?php
                $info = [
                  'Ketua' => $org['ketua_nama'] ?? '',
                  'Sekretariat' => $org['sekretariat'] ?? '',
                  'Email' => $org['email'] ?? '',
                  'Telepon' => $org['telepon'] ?? ''
                ];
                foreach ($info as $label => $value):
                  if (trim((string)$value) === '') continue;
                ?>
                <div class="ukm-info-row">
                  <span><?= e($label) ?></span>
                  <strong><?= e($value) ?></strong>
                </div>
                <?php endforeach; ?>
              </div>

              <?php
              $socials = [
                'Instagram' => $org['instagram'] ?? '',
                'Facebook' => $org['facebook'] ?? '',
                'YouTube' => $org['youtube'] ?? ''
              ];
              $hasSocial = false;
              foreach ($socials as $value) {
                if (trim((string)$value) !== '') {
                  $hasSocial = true;
                  break;
                }
              }
              ?>
              <?php if ($hasSocial): ?>
              <div class="ukm-socials">
                <?php foreach ($socials as $label => $value): ?>
                <?php if (trim((string)$value) !== ''): ?>
                <?php
                      $socialUrl = trim((string)$value);
                      if (!preg_match('~^https?://~i', $socialUrl)) {
                        $socialUrl = 'https://' . ltrim($socialUrl, '/');
                      }
                      ?>
                <a href="<?= e($socialUrl) ?>" target="_blank" rel="noopener noreferrer">
                  <?= e($label) ?>
                </a>
                <?php endif; ?>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
            </div>

            <div class="ukm-panel">
              <div class="ukm-panel-title">
                <span class="ukm-panel-icon">◎</span>
                <div>
                  <span class="ukm-label">VISI & MISI</span>
                  <h2>Arah Organisasi</h2>
                </div>
              </div>

              <?php if (!empty($org['visi'])): ?>
              <div class="ukm-text-block">
                <h3>Visi</h3>
                <p><?= nl2br(e($org['visi'])) ?></p>
              </div>
              <?php endif; ?>

              <?php if (!empty($org['misi'])): ?>
              <div class="ukm-text-block">
                <h3>Misi</h3>
                <p><?= nl2br(e($org['misi'])) ?></p>
              </div>
              <?php endif; ?>

              <?php if (empty($org['visi']) && empty($org['misi'])): ?>
              <p class="ukm-muted">Visi dan misi belum diisi pada Dashboard Admin.</p>
              <?php endif; ?>
            </div>
          </div>

          <section class="ukm-content-block">
            <div class="ukm-section-head">
              <div>
                <span class="ukm-label">STRUKTUR ORGANISASI</span>
                <h2>Pengurus UKM</h2>
                <p>Daftar pengurus yang berstatus aktif pada Dashboard Admin.</p>
              </div>
              <span class="ukm-count"><?= count($pengurus) ?> Pengurus</span>
            </div>

            <?php if ($pengurus): ?>
            <div class="ukm-pengurus-grid">
              <?php foreach ($pengurus as $person): ?>
              <article class="ukm-person-card">
                <?php
                    $foto = ukm_public_image($person['foto'] ?? '', 'pengurus');
                    ?>
                <div class="ukm-person-photo">
                  <?php if ($foto): ?>
                  <img src="<?= e($foto) ?>" alt="<?= e($person['nama']) ?>" loading="lazy">
                  <?php else: ?>
                  <span><?= e(mb_strtoupper(mb_substr($person['nama'], 0, 1))) ?></span>
                  <?php endif; ?>
                </div>
                <div class="ukm-person-body">
                  <span><?= e($person['jabatan']) ?></span>
                  <h3><?= e($person['nama']) ?></h3>
                </div>
              </article>
              <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="ukm-no-data">Belum ada data pengurus aktif.</div>
            <?php endif; ?>
          </section>

          <section class="ukm-content-block">
            <div class="ukm-section-head">
              <div>
                <span class="ukm-label">KEANGGOTAAN</span>
                <h2>Anggota UKM</h2>
                <p>Data anggota aktif yang dikelola melalui Dashboard Admin.</p>
              </div>
              <span class="ukm-count"><?= count($anggota) ?> Anggota</span>
            </div>

            <?php if ($anggota): ?>
            <div class="ukm-member-grid">
              <?php foreach ($anggota as $member): ?>
              <article class="ukm-member-card">
                <div class="ukm-member-avatar">
                  <?php
                      $fotoAnggota = ukm_public_image($member['foto'] ?? '', 'anggota');
                      ?>
                  <?php if ($fotoAnggota): ?>
                  <img src="<?= e($fotoAnggota) ?>" alt="<?= e($member['nama']) ?>" loading="lazy">
                  <?php else: ?>
                  <span><?= e(mb_strtoupper(mb_substr($member['nama'], 0, 1))) ?></span>
                  <?php endif; ?>
                </div>
                <div>
                  <h3><?= e($member['nama']) ?></h3>
                  <?php if (!empty($member['jabatan'])): ?>
                  <span class="ukm-member-role"><?= e($member['jabatan']) ?></span>
                  <?php endif; ?>
                  <div class="ukm-member-meta">
                    <?php if (!empty($member['nim'])): ?><span>NIM: <?= e($member['nim']) ?></span><?php endif; ?>
                    <?php if (!empty($member['prodi'])): ?><span><?= e($member['prodi']) ?></span><?php endif; ?>
                    <?php if (!empty($member['angkatan'])): ?><span>Angkatan
                      <?= e($member['angkatan']) ?></span><?php endif; ?>
                  </div>
                </div>
              </article>
              <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="ukm-no-data">Belum ada data anggota aktif.</div>
            <?php endif; ?>
          </section>

          <section class="ukm-content-block">
            <div class="ukm-section-head">
              <div>
                <span class="ukm-label">PROGRAM & AKTIVITAS</span>
                <h2>Kegiatan UKM</h2>
                <p>Kegiatan yang berstatus publish pada Dashboard Admin.</p>
              </div>
              <span class="ukm-count"><?= count($kegiatan) ?> Kegiatan</span>
            </div>

            <?php if ($kegiatan): ?>
            <div class="ukm-activity-grid">
              <?php foreach ($kegiatan as $activity): ?>
              <article class="ukm-activity-card">
                <?php $fotoKegiatan = ukm_public_image($activity['foto'] ?? '', 'kegiatan'); ?>
                <?php if ($fotoKegiatan): ?>
                <div class="ukm-activity-image">
                  <img src="<?= e($fotoKegiatan) ?>" alt="<?= e($activity['judul']) ?>" loading="lazy">
                </div>
                <?php endif; ?>

                <div class="ukm-activity-body">
                  <span class="ukm-date"><?= e(ukm_date($activity['tanggal_kegiatan'] ?? '')) ?></span>
                  <h3><?= e($activity['judul']) ?></h3>
                  <?php if (!empty($activity['deskripsi'])): ?>
                  <p><?= nl2br(e($activity['deskripsi'])) ?></p>
                  <?php endif; ?>
                </div>
              </article>
              <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="ukm-no-data">Belum ada kegiatan yang dipublikasikan.</div>
            <?php endif; ?>
          </section>

          <section class="ukm-content-block">
            <div class="ukm-section-head">
              <div>
                <span class="ukm-label">DOKUMENTASI</span>
                <h2>Galeri UKM</h2>
                <p>Dokumentasi foto yang berstatus publish pada Dashboard Admin.</p>
              </div>
              <span class="ukm-count"><?= count($galeri) ?> Foto</span>
            </div>

            <?php if ($galeri): ?>
            <div class="ukm-gallery">
              <?php foreach ($galeri as $photo): ?>
              <?php $fotoGaleri = ukm_public_image($photo['foto'] ?? '', 'galeri'); ?>
              <?php if ($fotoGaleri): ?>
              <figure class="ukm-gallery-item">
                <img src="<?= e($fotoGaleri) ?>" alt="<?= e($photo['judul']) ?>" loading="lazy">
                <figcaption>
                  <strong><?= e($photo['judul']) ?></strong>
                  <?php if (!empty($photo['keterangan'])): ?>
                  <span><?= e($photo['keterangan']) ?></span>
                  <?php endif; ?>
                </figcaption>
              </figure>
              <?php endif; ?>
              <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="ukm-no-data">Belum ada dokumentasi galeri yang dipublikasikan.</div>
            <?php endif; ?>
          </section>

          <div class="ukm-back-wrap">
            <a class="ukm-back-btn" href="/fikes/kemahasiswaan/ukm">← Kembali ke Daftar UKM</a>
          </div>
        </div>
      </section>

      <?php endif; ?>
    </main>
    <?php require_once __DIR__ . '/../menu/footer.php'; ?>
  </body>

</html>
