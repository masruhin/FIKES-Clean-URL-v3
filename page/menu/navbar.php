<?php
// Komponen reusable Navbar FIKES.
// Variabel $base_path dapat diatur dari halaman pemanggil.
// Default: '..' untuk halaman yang berada di dalam folder page/.
// $base_path = $base_path ?? '..';
$base_path = '/fikes/page';
$base = '../../';

/*
 * Active menu frontend FIKES.
 * Menggunakan REQUEST_URI agar menu otomatis aktif sesuai halaman yang sedang dibuka.
 */
$current_path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$current_path = rtrim($current_path, '/');
if ($current_path === '') {
    $current_path = '/';
}

function frontend_active($paths, $exact = false) {
    global $current_path;
    $paths = (array) $paths;

    foreach ($paths as $path) {
        $path = rtrim($path, '/');
        if ($path === '') $path = '/';

        if ($exact) {
            if ($current_path === $path) {
                return 'active';
            }
        } else {
            if ($current_path === $path || strpos($current_path, $path . '/') === 0) {
                return 'active';
            }
        }
    }

    return '';
}

function frontend_parent_active($paths) {
    return frontend_active($paths);
}
?>

<!-- =========================================================
     NAVBAR
========================================================= -->

<header class="navbar" id="navbar">
  <div class="container nav-inner">
    <!-- LOGO -->

    <a href="/fikes/" class="logo <?= frontend_active('/fikes', true) ?>">
      <div class="logo-icon">F</div>

      <div class="logo-text">
        <strong>FIKES</strong>
        <small>FAKULTAS ILMU KESEHATAN</small>
      </div>
    </a>

    <!-- MOBILE BUTTON -->

    <button class="menu-toggle" id="menuToggle">☰</button>

    <!-- NAVIGATION -->

    <nav class="nav-menu" id="navMenu">
      <!-- TENTANG FIKES -->

      <div class="nav-item has-dropdown">
        <a href="/fikes/" class="dropdown-link">
          Tentang FIKES
          <span class="arrow">▾</span>
        </a>

        <div class="dropdown">
          <div class="dropdown-item">
            <a href="/fikes/tentang/visi-misi"
              class="dropdown-link <?= frontend_active('/fikes/tentang/visi-misi', true) ?>"> Visi Misi </a>
          </div>

          <div class="dropdown-item">
            <a href="/fikes/tentang/struktur-organisasi"
              class="dropdown-link <?= frontend_active('/fikes/tentang/struktur-organisasi', true) ?>">
              Struktur Organisasi
            </a>
          </div>

          <div class="dropdown-item">
            <a href="/fikes/tentang/sertifikat-akreditasi"
              class="dropdown-link <?= frontend_active('/fikes/tentang/sertifikat-akreditasi', true) ?>">
              Sertifikat Akreditasi
            </a>
          </div>

          <div class="dropdown-item">
            <a href="/fikes/tentang/unduh-logo"
              class="dropdown-link <?= frontend_active('/fikes/tentang/unduh-logo', true) ?>"> Unduh Logo </a>
          </div>

          <div class="dropdown-item">
            <a href="/fikes/dosen" class="dropdown-link <?= frontend_active('/fikes/dosen') ?>"> Daftar Dosen </a>
          </div>
          <!-- DAFTAR DOSEN -->

          <!-- <div class="dropdown-item has-dropdown">
            <a href="#" class="dropdown-link">
              Daftar Dosen
              <span>›</span>
            </a>

            <div class="dropdown">
              <div class="dropdown-item">
                <a href="/fikes/dosen" class="dropdown-link <?= frontend_active('/fikes/dosen') ?>"> Keperawatan </a>
              </div>

              <div class="dropdown-item">
                <a href="#" class="dropdown-link"> Kebidanan </a>
              </div>

              <div class="dropdown-item">
                <a href="#" class="dropdown-link"> Farmasi </a>
              </div>

              <div class="dropdown-item">
                <a href="#" class="dropdown-link"> K3 </a>
              </div>
            </div>
          </div> -->
        </div>
      </div>

      <!-- KEMAHASISWAAN -->

      <div class="nav-item has-dropdown">
        <a href="#" class="nav-link">
          Kemahasiswaan
          <span class="arrow">▾</span>
        </a>

        <div class="dropdown">
          <div class="dropdown-item">
            <a href="/fikes/kemahasiswaan/himpunan-mahasiswa"
              class="dropdown-link <?= frontend_active('/fikes/kemahasiswaan/himpunan-mahasiswa', true) ?>">
              Himpunan Mahasiswa
            </a>
          </div>

          <div class="dropdown-item">
            <a href="/fikes/kemahasiswaan/ukm"
              class="dropdown-link <?= frontend_active('/fikes/kemahasiswaan/ukm', true) ?>">
              Unit Kegiatan Mahasiswa
            </a>
          </div>
        </div>
      </div>

      <!-- PROGRAM VOKASI -->
      <div class="nav-item">
        <a href="/fikes/program-studi" class="nav-link <?= frontend_active('/fikes/program-studi') ?>"> Program </a>
      </div>

      <!-- <div class="nav-item has-dropdown">
        <a href="/fikes/program-studi" class="nav-link">
          Program Vokasi
          <span class="arrow">▾</span>
        </a>

        <div class="dropdown">

          <div class="dropdown-item has-dropdown">
            <a href="#" class="dropdown-link">
              Program Profesi
              <span>›</span>
            </a>

            <div class="dropdown">
              <div class="dropdown-item">
                <a href="/fikes/program-studi" class="dropdown-link">
                  Profesi Ners
                </a>
              </div>
            </div>
          </div>


          <div class="dropdown-item has-dropdown">
            <a href="#" class="dropdown-link">
              Program Sarjana
              <span>›</span>
            </a>

            <div class="dropdown">
              <div class="dropdown-item">
                <a href="/fikes/program-studi" class="dropdown-link">
                  Ilmu Keperawatan (S.Kep)
                </a>
              </div>

              <div class="dropdown-item">
                <a href="/fikes/program-studi" class="dropdown-link">
                  Farmasi (S.Farm)
                </a>
              </div>
            </div>
          </div>


          <div class="dropdown-item has-dropdown">
            <a href="#" class="dropdown-link">
              Program Diploma
              <span>›</span>
            </a>

            <div class="dropdown">
              <div class="dropdown-item">
                <a href="#" class="dropdown-link">
                  Keperawatan (A.Md.Kep.)
                </a>
              </div>

              <div class="dropdown-item">
                <a href="#" class="dropdown-link">
                  Kebidanan (A.Md.Keb.)
                </a>
              </div>

              <div class="dropdown-item">
                <a href="#" class="dropdown-link">
                  Keselamatan dan Kesehatan Kerja (S.Tr.KKK.)
                </a>
              </div>
            </div>
          </div>
        </div>
      </div> -->

      <!-- AKADEMIK -->

      <div class="nav-item">
        <a href="/fikes/akademik" class="nav-link <?= frontend_active('/fikes/akademik') ?>"> Akademik </a>
      </div>

      <!-- PELAYANAN -->

      <!-- <div class="nav-item">
        <a href="#pelayanan" class="nav-link"> Pelayanan FIKES </a>
      </div> -->

      <!-- SURVEY -->

      <div class="nav-item">
        <a href="/fikes/spmi" class="nav-link <?= frontend_active('/fikes/spmi') ?>"> SPMI </a>
      </div>

      <div class="nav-item">
        <a href="/fikes/survey" class="nav-link <?= frontend_active('/fikes/survey') ?>"> Survey </a>
      </div>
    </nav>

    <a href="/fikes/program-studi" class="nav-cta"> Jelajahi Program </a>
  </div>
</header>
