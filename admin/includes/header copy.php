<?php
require_once __DIR__ . '/../config/auth.php';
wajib_login();

$page_title = $page_title ?? 'Dashboard';
$pos_admin = strpos($_SERVER['SCRIPT_NAME'], '/admin/');
$project_url = ($pos_admin !== false) ? substr($_SERVER['SCRIPT_NAME'], 0, $pos_admin) : '';
$current_url = $_SERVER['REQUEST_URI'] ?? '';
$current_path = parse_url($current_url, PHP_URL_PATH) ?: '';
$current_query = $_GET;

if (!function_exists('menu_active')) {
  function menu_active($path)
  {
    global $current_path;
    return strpos($current_path, $path) !== false ? 'active' : '';
  }
}

if (!function_exists('query_active')) {
  function query_active($key, $value)
  {
    global $current_query;
    return isset($current_query[$key]) && (string)$current_query[$key] === (string)$value ? 'active' : '';
  }
}

if (!function_exists('any_active')) {
  function any_active(array $checks)
  {
    foreach ($checks as $check) {
      if ($check === true) return 'active';
    }
    return '';
  }
}

/* =====================================================
   STATUS MENU UTAMA
   ===================================================== */
$active_dashboard = ($current_path === rtrim($project_url, '/') . '/admin/' || $current_path === rtrim($project_url, '/') . '/admin/index.php');

$active_slider = strpos($current_path, '/admin/modules/slider/') !== false;
$active_berita = strpos($current_path, '/admin/modules/berita/') !== false;
$active_konten = $active_slider || $active_berita;

$active_visi = strpos($current_path, '/admin/modules/tentang/visi-misi.php') !== false;
$active_struktur = strpos($current_path, '/admin/modules/tentang/struktur.php') !== false;
$active_gambar_struktur = strpos($current_path, '/admin/modules/tentang/gambar-struktur.php') !== false;
$active_sertifikat = strpos($current_path, '/admin/modules/sertifikat/') !== false;
$active_logo = strpos($current_path, '/admin/modules/tentang/logo.php') !== false;
$active_tentang = $active_visi || $active_struktur || $active_sertifikat || $active_logo;

$active_dosen_keperawatan = query_active('prodi', 'Keperawatan') === 'active';
$active_dosen_kebidanan = query_active('prodi', 'Kebidanan') === 'active';
$active_dosen_farmasi = query_active('prodi', 'Farmasi') === 'active';
$active_dosen_k3 = query_active('prodi', 'K3') === 'active';
$active_dosen = strpos($current_path, '/admin/modules/dosen/') !== false;

$active_himpunan = strpos($current_path, '/admin/modules/kemahasiswaan/himpunan.php') !== false;
$active_ukm = strpos($current_path, '/admin/modules/kemahasiswaan/ukm.php') !== false;
$active_kemahasiswaan = strpos($current_path, '/admin/modules/kemahasiswaan/') !== false;

$active_program_studi = strpos($current_path, '/admin/modules/program-studi/') !== false;
$active_akademik = strpos($current_path, '/admin/modules/akademik/') !== false;
$active_survey = strpos($current_path, '/admin/modules/survey/') !== false;

$active_pengaturan_website = strpos($current_path, '/admin/modules/pengaturan/') !== false;
$active_profil_admin = strpos($current_path, '/admin/modules/akun/') !== false;
$active_pengaturan = $active_pengaturan_website || $active_profil_admin;

/* =====================================================
   HELPER KELAS AKTIF
   ===================================================== */
function parent_class($active)
{
  return $active ? ' active' : '';
}

function submenu_style($active)
{
  return $active ? ' style="display:block"' : '';
}
?>
<!doctype html>
<html lang="id">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= e($page_title) ?> | Admin FIKES</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
      rel="stylesheet">
    <link rel="stylesheet" href="<?= $project_url ?>/admin/assets/css/admin.css">

    <?php if (!empty($page_styles) && is_array($page_styles)): ?>
    <?php foreach ($page_styles as $style): ?>
    <link rel="stylesheet"
      href="<?= $project_url . '/' . ltrim($style, '/') ?>?v=<?= file_exists(dirname(__DIR__, 2) . '/' . ltrim($style, '/')) ? filemtime(dirname(__DIR__, 2) . '/' . ltrim($style, '/')) : time() ?>">
    <?php endforeach; ?>
    <?php endif; ?>

    <style>
    /* =====================================================
       ACTIVE SIDEBAR
       ===================================================== */
    .sidebar .menu-link.active,
    .sidebar .menu-parent.active {
      color: #1677ed !important;
      background: #eaf4ff !important;
      font-weight: 700;
    }

    .sidebar .menu-link.active span,
    .sidebar .menu-parent.active span,
    .sidebar .menu-parent.active b {
      color: #1677ed !important;
    }

    .sidebar .menu-parent.active i {
      color: #1677ed !important;
      transform: rotate(180deg);
    }

    .sidebar .submenu {
      overflow: hidden;
    }

    .sidebar .submenu-link.active {
      color: #1677ed !important;
      background: #f2f8ff !important;
      font-weight: 700;
    }

    .sidebar .submenu-link.active::before {
      opacity: 1;
      background: #1677ed;
    }
    </style>
  </head>

  <body>
    <div class="app">
      <aside class="sidebar" id="sidebar">
        <div class="brand">
          <div class="brand-mark">F</div>
          <div><strong>FIKES</strong><small>ADMIN PANEL</small></div>
        </div>

        <nav class="sidebar-nav">
          <a href="<?= $project_url ?>/admin/index.php" class="menu-link<?= parent_class($active_dashboard) ?>">
            <span>▦</span><b>Dashboard</b>
          </a>

          <button type="button" class="menu-parent<?= parent_class($active_konten) ?>" data-menu="konten-website"
            aria-expanded="<?= $active_konten ? 'true' : 'false' ?>">
            <span>▣</span><b>Konten Website</b><i>⌄</i>
          </button>
          <div class="submenu<?= $active_konten ? ' is-active' : '' ?>" id="konten-website"
            <?= submenu_style($active_konten) ?>>
            <a href="<?= $project_url ?>/admin/modules/slider/index.php"
              class="submenu-link<?= parent_class($active_slider) ?>">Slider Beranda</a>
            <a href="<?= $project_url ?>/admin/modules/berita/index.php"
              class="submenu-link<?= parent_class($active_berita) ?>">Berita</a>
          </div>

          <button type="button" class="menu-parent<?= parent_class($active_tentang) ?>" data-menu="tentang"
            aria-expanded="<?= $active_tentang ? 'true' : 'false' ?>">
            <span>◈</span><b>Tentang FIKES</b><i>⌄</i>
          </button>
          <div class="submenu<?= $active_tentang ? ' is-active' : '' ?>" id="tentang"
            <?= submenu_style($active_tentang) ?>>
            <a href="<?= $project_url ?>/admin/modules/tentang/visi-misi.php"
              class="submenu-link<?= parent_class($active_visi) ?>">Visi-Misi</a>
            <a href="<?= $project_url ?>/admin/modules/tentang/struktur.php"
              class="submenu-link<?= parent_class($active_struktur) ?>">Struktur Organisasi</a>
            <a href="<?= $project_url ?>/admin/modules/tentang/gambar-struktur.php"
              class="submenu-link<?= parent_class($active_gambar_struktur) ?>">Gambar Struktur Organisasi</a>
            <a href="<?= $project_url ?>/admin/modules/sertifikat/index.php"
              class="submenu-link<?= parent_class($active_sertifikat) ?>">Sertifikat Akreditasi</a>
            <a href="<?= $project_url ?>/admin/modules/tentang/logo.php"
              class="submenu-link<?= parent_class($active_logo) ?>">Unduh Logo</a>
          </div>

          <button type="button" class="menu-parent<?= parent_class($active_dosen) ?>" data-menu="dosen"
            aria-expanded="<?= $active_dosen ? 'true' : 'false' ?>">
            <span>♙</span><b>Daftar Dosen</b><i>⌄</i>
          </button>
          <div class="submenu<?= $active_dosen ? ' is-active' : '' ?>" id="dosen" <?= submenu_style($active_dosen) ?>>
            <a href="<?= $project_url ?>/admin/modules/dosen/index.php?prodi=Keperawatan"
              class="submenu-link<?= parent_class($active_dosen_keperawatan) ?>">Dosen Keperawatan</a>
            <a href="<?= $project_url ?>/admin/modules/dosen/index.php?prodi=Kebidanan"
              class="submenu-link<?= parent_class($active_dosen_kebidanan) ?>">Dosen Kebidanan</a>
            <a href="<?= $project_url ?>/admin/modules/dosen/index.php?prodi=Farmasi"
              class="submenu-link<?= parent_class($active_dosen_farmasi) ?>">Dosen Farmasi</a>
            <a href="<?= $project_url ?>/admin/modules/dosen/index.php?prodi=K3"
              class="submenu-link<?= parent_class($active_dosen_k3) ?>">Dosen K3</a>
          </div>

          <button type="button" class="menu-parent<?= parent_class($active_kemahasiswaan) ?>"
            data-menu="kemahasiswaan-data" aria-expanded="<?= $active_kemahasiswaan ? 'true' : 'false' ?>">
            <span>♧</span><b>Kemahasiswaan</b><i>⌄</i>
          </button>
          <div class="submenu<?= $active_kemahasiswaan ? ' is-active' : '' ?>" id="kemahasiswaan-data"
            <?= submenu_style($active_kemahasiswaan) ?>>
            <a href="<?= $project_url ?>/admin/modules/kemahasiswaan/himpunan.php"
              class="submenu-link<?= parent_class($active_himpunan) ?>">Unit Himpunan Mahasiswa</a>
            <a href="<?= $project_url ?>/admin/modules/kemahasiswaan/ukm.php"
              class="submenu-link<?= parent_class($active_ukm) ?>">UKM Kemahasiswaan</a>
          </div>

          <a href="<?= $project_url ?>/admin/modules/program-studi/index.php"
            class="menu-link<?= parent_class($active_program_studi) ?>">
            <span>▤</span><b>Program Studi</b>
          </a>

          <a href="<?= $project_url ?>/admin/modules/akademik/index.php"
            class="menu-link<?= parent_class($active_akademik) ?>">
            <span>▥</span><b>Akademik</b>
          </a>

          <a href="<?= $project_url ?>/admin/modules/survey/index.php"
            class="menu-link<?= parent_class($active_survey) ?>">
            <span>◉</span><b>Survey</b>
          </a>

          <button type="button" class="menu-parent<?= parent_class($active_pengaturan) ?>" data-menu="akun"
            aria-expanded="<?= $active_pengaturan ? 'true' : 'false' ?>">
            <span>⚙</span><b>Pengaturan</b><i>⌄</i>
          </button>
          <div class="submenu<?= $active_pengaturan ? ' is-active' : '' ?>" id="akun"
            <?= submenu_style($active_pengaturan) ?>>
            <a href="<?= $project_url ?>/admin/modules/pengaturan/index.php"
              class="submenu-link<?= parent_class($active_pengaturan_website) ?>">Pengaturan Website</a>
            <a href="<?= $project_url ?>/admin/modules/akun/index.php"
              class="submenu-link<?= parent_class($active_profil_admin) ?>">Profil Admin</a>
          </div>
        </nav>

        <a href="<?= $project_url ?>/admin/logout.php" class="logout"><span>↪</span> Keluar</a>
      </aside>

      <div class="overlay" id="overlay"></div>

      <main class="main">
        <header class="topbar">
          <button type="button" id="sidebarToggle" class="icon-btn" aria-label="Buka menu">☰</button>
          <div class="top-title">
            <span>Panel Administrasi</span>
            <strong><?= e($page_title) ?></strong>
          </div>
          <div class="user">
            <div class="avatar"><?= strtoupper(substr($_SESSION['admin_nama'], 0, 1)) ?></div>
            <div>
              <strong><?= e($_SESSION['admin_nama']) ?></strong>
              <small><?= e($_SESSION['admin_role']) ?></small>
            </div>
          </div>
        </header>

        <section class="content">

          <script>
          (function() {
            'use strict';

            function initSidebarMenus() {
              document.querySelectorAll('.menu-parent[data-menu]').forEach(function(button) {
                var menuId = button.getAttribute('data-menu');
                var submenu = document.getElementById(menuId);
                if (!submenu) return;

                button.addEventListener('click', function() {
                  var isOpen = submenu.style.display === 'block' || submenu.classList.contains('is-open');

                  document.querySelectorAll('.menu-parent[data-menu]').forEach(function(otherButton) {
                    var otherId = otherButton.getAttribute('data-menu');
                    var otherSubmenu = document.getElementById(otherId);
                    if (!otherSubmenu || otherButton === button) return;
                    otherButton.classList.remove('is-open');
                    otherButton.setAttribute('aria-expanded', 'false');
                    if (!otherButton.classList.contains('active')) {
                      otherSubmenu.style.display = '';
                    }
                    otherSubmenu.classList.remove('is-open');
                  });

                  if (isOpen) {
                    if (!button.classList.contains('active')) {
                      submenu.style.display = '';
                      submenu.classList.remove('is-open');
                      button.classList.remove('is-open');
                      button.setAttribute('aria-expanded', 'false');
                    }
                  } else {
                    submenu.style.display = 'block';
                    submenu.classList.add('is-open');
                    button.classList.add('is-open');
                    button.setAttribute('aria-expanded', 'true');
                  }
                });
              });

              var sidebarToggle = document.getElementById('sidebarToggle');
              var sidebar = document.getElementById('sidebar');
              var overlay = document.getElementById('overlay');

              if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                  sidebar.classList.toggle('open');
                  if (overlay) overlay.classList.toggle('show');
                });
              }

              if (overlay && sidebar) {
                overlay.addEventListener('click', function() {
                  sidebar.classList.remove('open');
                  overlay.classList.remove('show');
                });
              }
            }

            if (document.readyState === 'loading') {
              document.addEventListener('DOMContentLoaded', initSidebarMenus);
            } else {
              initSidebarMenus();
            }
          })();
          </script>
