<?php
// Shared FIKES topbar. Tidak membutuhkan koneksi database.
if (!function_exists('e')) {
  function e($value)
  {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
  }
}

$base_path = '/fikes/page';
$base = '../../';
if (!isset($pdo) || !($pdo instanceof PDO)) {
  require_once __DIR__ . '/../../admin/config/database.php';
}

/* Pengaturan website */
require_once __DIR__ . '/../includes/site-settings.php';

$site = fikes_site_settings($pdo);

$siteName = fikes_setting_value($site, ['nama_website', 'site_name', 'website_name', 'nama', 'title'], 'FIKES');
$siteTagline = fikes_setting_value($site, ['tagline', 'slogan', 'subjudul', 'site_tagline'], 'FAKULTAS ILMU KESEHATAN');
$siteEmail = fikes_setting_value($site, ['email', 'email_website', 'email_kampus', 'email_fikes'], 'info@fikes.ac.id');
$sitePhone = fikes_setting_value($site, ['telepon', 'no_telepon', 'nomor_telepon', 'phone', 'telephone', 'telp'], '(021)
1234567');
$siteAddress = fikes_setting_value($site, ['alamat', 'alamat_kampus', 'alamat_website', 'address'], 'Alamat Fakultas
Ilmu Kesehatan');
$siteDescription = fikes_setting_value($site, [
  'deskripsi',
  'deskripsi_website',
  'description',
  'tentang_singkat',
  'footer_deskripsi'
], 'Fakultas Ilmu Kesehatan yang profesional, inovatif, berintegritas, dan berorientasi kepada
masyarakat.');
$siteInstagram = fikes_setting_value($site, ['instagram', 'instagram_url', 'link_instagram'], '#');
$siteFacebook = fikes_setting_value($site, ['facebook', 'facebook_url', 'link_facebook'], '#');
$siteYoutube = fikes_setting_value($site, ['youtube', 'youtube_url', 'link_youtube'], '#');
$siteMaps = fikes_setting_value($site, ['google_maps', 'maps_embed', 'map_embed', 'maps', 'google_map'], '');
$siteMapDirection = fikes_setting_value(
  $site,
  ['map_direction', 'maps_direction', 'link_maps', 'google_maps_url'],
  '#'
);
$siteLogo = fikes_setting_value($site, ['logo', 'logo_website', 'logo_url', 'logo_file'], '');
$siteCopyright = fikes_setting_value($site, ['copyright', 'copyright_text', 'footer_copyright'], '© ' . date('Y') . ' '
  . $siteName . '. All Rights Reserved.');

?>
<div class="topbar">
  <div class="container topbar-inner">
    <div class="topbar-info">
      <span>📍 <?= e($siteName) ?></span>
      <span>✉️ <?= e($siteEmail) ?></span>
      <span>📞 <?= e($sitePhone) ?></span>
    </div>

    <div class="topbar-social">
      <?php if ($siteInstagram !== '#'): ?><a href="<?= e($siteInstagram) ?>" target="_blank"
          rel="noopener">Instagram</a><?php endif; ?>
      <?php if ($siteFacebook !== '#'): ?><a href="<?= e($siteFacebook) ?>" target="_blank"
          rel="noopener">Facebook</a><?php endif; ?>
      <?php if ($siteYoutube !== '#'): ?><a href="<?= e($siteYoutube) ?>" target="_blank"
          rel="noopener">YouTube</a><?php endif; ?>
    </div>
  </div>
</div>
