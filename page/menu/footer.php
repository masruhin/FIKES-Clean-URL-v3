<?php
// Shared FIKES topbar. Tidak membutuhkan koneksi database.
require_once __DIR__ . '/../includes/site-settings.php';

$base_path = '/fikes/page';
$base = '../../';
$site = fikes_site_settings($pdo);

$siteName = fikes_setting_value($site, ['nama_website', 'site_name', 'website_name', 'nama', 'title'], 'FIKES');
$siteTagline = fikes_setting_value($site, ['tagline', 'slogan', 'subjudul', 'site_tagline'], 'FAKULTAS ILMU KESEHATAN');
$siteEmail = fikes_setting_value($site, ['email', 'email_website', 'email_kampus', 'email_fikes'], 'info@fikes.ac.id');
$sitePhone = fikes_setting_value($site, ['telepon', 'no_telepon', 'nomor_telepon', 'phone', 'telephone', 'telp'], '(021) 1234567');
$siteAddress = fikes_setting_value($site, ['alamat', 'alamat_kampus', 'alamat_website', 'address'], 'Alamat Fakultas Ilmu Kesehatan');
$siteDescription = fikes_setting_value($site, ['deskripsi', 'deskripsi_website', 'description', 'tentang_singkat', 'footer_deskripsi'], 'Fakultas Ilmu Kesehatan yang profesional, inovatif, berintegritas, dan berorientasi kepada masyarakat.');
$siteInstagram = fikes_setting_value($site, ['instagram', 'instagram_url', 'link_instagram'], '#');
$siteFacebook = fikes_setting_value($site, ['facebook', 'facebook_url', 'link_facebook'], '#');
$siteYoutube = fikes_setting_value($site, ['youtube', 'youtube_url', 'link_youtube'], '#');
$siteMaps = fikes_setting_value($site, ['google_maps', 'maps_embed', 'map_embed', 'maps', 'google_map'], '');
$siteMapDirection = fikes_setting_value($site, ['map_direction', 'maps_direction', 'link_maps', 'google_maps_url'], '#');
$siteLogo = fikes_setting_value($site, ['logo', 'logo_website', 'logo_url', 'logo_file'], '');
$siteCopyright = fikes_setting_value($site, ['copyright', 'copyright_text', 'footer_copyright'], '© ' . date('Y') . ' ' . $siteName . '. All Rights Reserved.');
?>

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

        <a href="/fikes/spmi"> SPMI </a>

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
          src="<?= e($siteMaps ?: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.1515727139526!2d109.11806027499709!3d-6.991421893009626!2m3!1f0!2f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6fbef42471658d%3A0x883656d1325ef066!2sUniversitas%20Bhamada%20Slawi!5e0!3m2!1sid!2sid!4v1787544396003!5m2!1sid!2sid') ?>"
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
              <strong><?= e($siteName) ?></strong>

              <span> Lihat lokasi kampus </span>
            </div>
          </div>

          <a href="<?= e($siteMapDirection) ?>" target="_blank" rel="noopener" class="map-direction">
            <i class="fa-solid fa-diamond-turn-right"></i>

            Petunjuk Arah
          </a>
        </div>
      </div>

      <!-- ALAMAT -->

      <div class="footer-contact location-contact">
        <i class="fa-solid fa-location-dot"></i>

        <span><?= nl2br(e($siteAddress)) ?></span>
      </div>

      <div class="footer-contact">
        <i class="fa-solid fa-phone"></i>

        <span><?= e($sitePhone) ?></span>
      </div>

      <div class="footer-contact">
        <i class="fa-solid fa-envelope"></i>

        <span><?= e($siteEmail) ?></span>
      </div>
    </div>
    <!--MAP PETA-->
  </div>

  <div class="container footer-bottom">
    <span><?= e($siteCopyright) ?></span>
    <span><?= e($siteName) ?></span>
  </div>
</footer>

<!-- BACK TO TOP -->

<button class="back-top" id="backTop">↑</button>
<script>
  let current = 0;
  let slides = [];
  let dots = [];

  function showSlide(n) {
    if (!slides.length) return;
    current = (n + slides.length) % slides.length;
    slides.forEach((s, i) => s.classList.toggle("active", i === current));
    dots.forEach((d, i) => d.classList.toggle("active", i === current));
  }

  function changeSlide(n) {
    showSlide(current + n);
  }

  function currentSlide(n) {
    showSlide(n - 1);
  }
  document.addEventListener("DOMContentLoaded", () => {
    slides = [...document.querySelectorAll(".slide")];
    dots = [...document.querySelectorAll(".slider-dot")];
    showSlide(0);
    if (slides.length > 1) setInterval(() => changeSlide(1), 7000);
    const t = document.getElementById("menuToggle"),
      m = document.getElementById("navMenu");
    if (t && m) t.onclick = () => m.classList.toggle("active");
    const nav = document.getElementById("navbar");
    window.addEventListener("scroll", () => {
      if (nav) nav.classList.toggle("scrolled", scrollY > 20);
      const b = document.getElementById("backTop");
      if (b) b.classList.toggle("show", scrollY > 500)
    });
    const b = document.getElementById("backTop");
    if (b) b.onclick = () => scrollTo({
      top: 0,
      behavior: "smooth"
    });
  });
</script>
