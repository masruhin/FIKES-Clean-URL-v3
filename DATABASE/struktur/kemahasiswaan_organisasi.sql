-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2026 at 08:34 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fikes`
--

-- --------------------------------------------------------

--
-- Table structure for table `kemahasiswaan_organisasi`
--

CREATE TABLE `kemahasiswaan_organisasi` (
  `id` int(11) NOT NULL,
  `jenis` enum('himpunan','ukm') NOT NULL,
  `nama` varchar(150) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `fokus` text DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `ketua_nama` varchar(150) DEFAULT NULL,
  `sekretariat` varchar(255) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `instagram` varchar(150) DEFAULT NULL,
  `facebook` varchar(150) DEFAULT NULL,
  `youtube` varchar(150) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kemahasiswaan_organisasi`
--

INSERT INTO `kemahasiswaan_organisasi` (`id`, `jenis`, `nama`, `slug`, `kategori`, `deskripsi`, `fokus`, `visi`, `misi`, `ketua_nama`, `sekretariat`, `email`, `telepon`, `instagram`, `facebook`, `youtube`, `logo`, `status`, `nomor_urut`, `created_at`, `updated_at`) VALUES
(1, 'himpunan', 'HIMAFARDA', 'himafarda', 'Himpunan Mahasiswa Farmasi', 'Wadah mahasiswa Farmasi untuk mengembangkan potensi, kreativitas, kepemimpinan, dan kebersamaan di lingkungan FIKES.', 'Pengembangan organisasi, keilmuan, kreativitas, pengabdian, dan kebersamaan mahasiswa Farmasi.', '', '', '', '', '', '', '', '', '', 'org_20260917041355_6b70b2.jpg', 'aktif', 1, '2026-09-16 09:30:25', '2026-09-17 02:13:55'),
(2, 'himpunan', 'HIMASADA HIMASADA', 'himasada', 'Himpunan Mahasiswa', 'Organisasi mahasiswa sebagai ruang pengembangan potensi, aspirasi, komunikasi, dan kegiatan kemahasiswaan.', 'Pengembangan potensi mahasiswa, kepemimpinan, kebersamaan, dan kegiatan kemahasiswaan.', '', '', '', '', '', '', '', '', '', 'org_20260917042601_4f03bb.jpg', 'aktif', 2, '2026-09-16 09:30:25', '2026-09-17 02:26:01'),
(3, 'himpunan', 'HIMADIKA', 'himadika', 'Himpunan Mahasiswa Keperawatan', 'Wadah mahasiswa untuk kegiatan organisasi, pengembangan diri, kolaborasi, dan kontribusi bagi lingkungan kampus.', 'Keilmuan keperawatan, kepemimpinan, pengembangan diri, pengabdian, dan kegiatan mahasiswa.', '', '', '', '', '', '', '', '', '', 'org_20260917041437_6c511a.png', 'aktif', 3, '2026-09-16 09:30:25', '2026-09-17 02:14:37'),
(4, 'himpunan', 'HIMIKA', 'himika', 'Himpunan Mahasiswa', 'Organisasi mahasiswa yang mendukung aktivitas kemahasiswaan dan pengembangan kemampuan kepemimpinan.', 'Organisasi, aspirasi mahasiswa, kepemimpinan, kreativitas, dan kolaborasi.', '', '', '', '', '', '', '', '', '', 'org_20260917041453_ca245e.png', 'aktif', 4, '2026-09-16 09:30:25', '2026-09-17 02:14:53'),
(5, 'himpunan', 'HIMADAN', 'himadan', 'Himpunan Mahasiswa', 'Wadah mahasiswa untuk mengembangkan kreativitas, komunikasi, solidaritas, dan kegiatan sosial.', 'Kreativitas, komunikasi, solidaritas, kegiatan sosial, dan pengembangan mahasiswa.', '', '', '', '', '', '', '', '', '', 'org_20260917041510_e1601e.jpg', 'aktif', 5, '2026-09-16 09:30:25', '2026-09-17 02:15:10'),
(6, 'himpunan', 'BEM FIKES', 'bem-fikes', 'Organisasi Mahasiswa Tingkat Fakultas', 'Badan Eksekutif Mahasiswa sebagai wadah pelaksanaan program dan kegiatan mahasiswa di tingkat fakultas.', 'Koordinasi program kerja, pelayanan mahasiswa, pengembangan kegiatan, dan kolaborasi.', '', '', '', '', '', '', '', '', '', 'org_20260917041525_9dacca.jpg', 'aktif', 6, '2026-09-16 09:30:25', '2026-09-17 02:15:25'),
(7, 'himpunan', 'DPM FIKES', 'dpm-fikes', 'Organisasi Mahasiswa Tingkat Fakultas', 'Dewan Perwakilan Mahasiswa sebagai ruang perwakilan dan penyampaian aspirasi mahasiswa.', 'Perwakilan mahasiswa, aspirasi, pengawasan organisasi, dan komunikasi kelembagaan.', '', '', '', '', '', '', '', '', '', 'org_20260917041541_77047d.png', 'aktif', 7, '2026-09-16 09:30:25', '2026-09-17 02:15:41'),
(8, 'ukm', 'Karate', 'karate', 'Olahraga & Bela Diri', 'Wadah pengembangan bela diri, disiplin, kebugaran, karakter, dan prestasi mahasiswa.', 'Pengembangan bela diri, disiplin, kebugaran, karakter, dan prestasi mahasiswa.', '', '', '', '', '', '', '', '', '', 'org_20260917041611_3b1125.png', 'aktif', 1, '2026-09-16 09:30:25', '2026-09-17 02:16:11'),
(9, 'ukm', 'Basket', 'basket', 'Olahraga', 'Wadah pengembangan kemampuan bola basket, sportivitas, kebugaran, dan kerja sama tim.', 'Pengembangan kemampuan bola basket, sportivitas, kebugaran, dan kerja sama tim.', '', '', '', '', '', '', '', '', '', 'org_20260917041624_105f3c.png', 'aktif', 2, '2026-09-16 09:30:25', '2026-09-17 02:16:24'),
(10, 'ukm', 'Futsal', 'futsal', 'Olahraga', 'Wadah pengembangan teknik futsal, kekompakan, kebugaran, dan pengalaman kompetisi.', 'Pengembangan teknik futsal, kekompakan, kebugaran, dan pengalaman kompetisi.', '', '', '', '', '', '', '', '', '', 'org_20260917041637_96d83c.png', 'aktif', 3, '2026-09-16 09:30:25', '2026-09-17 02:16:37'),
(11, 'ukm', 'Badminton', 'badminton', 'Olahraga', 'Wadah pengembangan keterampilan badminton, kebugaran, sportivitas, dan prestasi mahasiswa.', 'Pengembangan keterampilan badminton, kebugaran, sportivitas, dan prestasi mahasiswa.', '', '', '', '', '', '', '', '', '', 'org_20260917041653_4b0416.png', 'aktif', 4, '2026-09-16 09:30:25', '2026-09-17 02:16:53'),
(12, 'ukm', 'Voli', 'voli', 'Olahraga', 'Wadah pengembangan kemampuan bola voli, kekompakan tim, kebugaran, dan prestasi.', 'Pengembangan kemampuan bola voli, kekompakan tim, kebugaran, dan prestasi.', '', '', '', '', '', '', '', '', '', 'org_20260917041707_dfcdf3.jpg', 'aktif', 5, '2026-09-16 09:30:25', '2026-09-17 02:17:07'),
(13, 'ukm', 'Silat', 'silat', 'Olahraga & Bela Diri', 'Wadah pengembangan seni bela diri, disiplin, karakter, kebugaran, dan prestasi.', 'Pengembangan seni bela diri, disiplin, karakter, kebugaran, dan prestasi.', '', '', '', '', '', '', '', '', '', 'org_20260917041723_8a777f.jpeg', 'aktif', 6, '2026-09-16 09:30:25', '2026-09-17 02:17:23'),
(14, 'ukm', 'BMB', 'bmb', 'Minat & Bakat', 'Ruang bagi mahasiswa untuk mengembangkan minat, bakat, kreativitas, dan pengalaman berorganisasi.', 'Pengembangan minat, bakat, kreativitas, dan pengalaman mahasiswa.', '', '', '', '', '', '', '', '', '', 'org_20260917041738_c43759.png', 'aktif', 7, '2026-09-16 09:30:25', '2026-09-17 02:17:38'),
(15, 'ukm', 'PIK', 'pik', 'Pengembangan Mahasiswa', 'Ruang pengembangan edukasi, komunikasi, kreativitas, dan kepedulian mahasiswa.', 'Pengembangan edukasi, komunikasi, kreativitas, dan kepedulian mahasiswa.', '', '', '', '', '', '', '', '', '', 'org_20260917041755_4ca497.jpg', 'aktif', 8, '2026-09-16 09:30:25', '2026-09-17 02:17:55'),
(16, 'ukm', 'BHAPALA', 'bhapala', 'Kepencintaalaman', 'Wadah kegiatan alam bebas, kepedulian lingkungan, kebersamaan, dan ketangguhan mahasiswa.', 'Pengembangan kegiatan alam bebas, kepedulian lingkungan, kebersamaan, dan ketangguhan mahasiswa.', '', '', '', '', '', '', '', '', '', 'org_20260917041811_a1d86a.jpg', 'aktif', 9, '2026-09-16 09:30:25', '2026-09-17 02:18:11'),
(17, 'ukm', 'Sentramada', 'sentramada', 'Seni & Kreativitas', 'Ruang ekspresi seni, kreativitas, kolaborasi, dan pengembangan potensi mahasiswa.', 'Pengembangan seni, kreativitas, ekspresi, dan kolaborasi mahasiswa.', '', '', '', '', '', '', '', '', '', 'org_20260917041829_961e71.png', 'aktif', 10, '2026-09-16 09:30:25', '2026-09-17 02:18:29'),
(18, 'ukm', 'Voice', 'voice', 'Seni & Musik', 'Wadah pengembangan vokal, musik, penampilan, kepercayaan diri, dan kreativitas seni.', 'Pengembangan vokal, musik, penampilan, kepercayaan diri, dan kreativitas seni.', '', '', '', '', '', '', '', '', '', 'org_20260917041842_6afc99.jpeg', 'aktif', 11, '2026-09-16 09:30:25', '2026-09-17 02:18:42'),
(19, 'ukm', 'Pramuka', 'pramuka', 'Kepanduan', 'Wadah pengembangan kepemimpinan, kedisiplinan, kemandirian, dan kegiatan sosial.', 'Pengembangan kepemimpinan, kedisiplinan, kemandirian, dan kegiatan sosial.', '', '', '', '', '', '', '', '', '', 'org_20260917041857_b0a70b.jpg', 'aktif', 12, '2026-09-16 09:30:25', '2026-09-17 02:18:57'),
(20, 'ukm', 'Bakti', 'bakti', 'Sosial & Pengabdian', 'Ruang pengembangan kepedulian sosial, pengabdian, dan kegiatan kemasyarakatan.', 'Pengembangan kepedulian sosial, pengabdian, dan kegiatan kemasyarakatan.', '', '', '', '', '', '', '', '', '', 'org_20260917041911_f53ff8.jpg', 'aktif', 13, '2026-09-16 09:30:25', '2026-09-17 02:19:11'),
(21, 'ukm', 'Jurnalika', 'jurnalika', 'Media & Jurnalistik', 'Wadah pengembangan jurnalistik, penulisan, dokumentasi, media, dan komunikasi.', 'Pengembangan jurnalistik, penulisan, dokumentasi, media, dan komunikasi.', '', '', '', '', '', '', '', '', '', 'org_20260917041926_8566bd.jpg', 'aktif', 14, '2026-09-16 09:30:25', '2026-09-17 02:19:26'),
(22, 'ukm', 'KSR', 'ksr', 'Kemanusiaan', 'Wadah pengembangan kepedulian kemanusiaan, kesiapsiagaan, dan kegiatan sosial.', 'Pengembangan kepedulian kemanusiaan, kesiapsiagaan, dan kegiatan sosial.', '', '', '', '', '', '', '', '', '', 'org_20260917041939_20c2f3.jpg', 'aktif', 15, '2026-09-16 09:30:25', '2026-09-17 02:19:39'),
(23, 'ukm', 'BEC', 'bec', 'Bahasa & Komunikasi', 'Ruang pengembangan kemampuan bahasa, komunikasi, kepercayaan diri, dan kreativitas.', 'Pengembangan kemampuan bahasa, komunikasi, kepercayaan diri, dan kreativitas.', '', '', '', '', '', '', '', '', '', 'org_20260917041953_4ecdb9.png', 'aktif', 16, '2026-09-16 09:30:25', '2026-09-17 02:19:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kemahasiswaan_organisasi`
--
ALTER TABLE `kemahasiswaan_organisasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_ko_jenis_status` (`jenis`,`status`),
  ADD KEY `idx_ko_urut` (`jenis`,`nomor_urut`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kemahasiswaan_organisasi`
--
ALTER TABLE `kemahasiswaan_organisasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
