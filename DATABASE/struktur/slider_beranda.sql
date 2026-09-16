-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2026 at 11:02 AM
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
-- Table structure for table `slider_beranda`
--

CREATE TABLE `slider_beranda` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `highlight` varchar(255) DEFAULT NULL,
  `label` varchar(150) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) NOT NULL,
  `link_utama` varchar(255) DEFAULT NULL,
  `teks_tombol_utama` varchar(100) DEFAULT NULL,
  `link_kedua` varchar(255) DEFAULT NULL,
  `teks_tombol_kedua` varchar(100) DEFAULT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slider_beranda`
--

INSERT INTO `slider_beranda` (`id`, `judul`, `highlight`, `label`, `deskripsi`, `gambar`, `link_utama`, `teks_tombol_utama`, `link_kedua`, `teks_tombol_kedua`, `nomor_urut`, `status`, `created_at`) VALUES
(1, 'Membangun Generasi', 'Tenaga Kesehatan Profesional', 'FAKULTAS ILMU KESEHATAN', 'Mewujudkan pendidikan kesehatan yang unggul, profesional, inovatif, dan berintegritas untuk masa depan yang lebih baik.', '20260914033930_14b336cb.jpeg', 'page/program-studi/program-studi.php', 'Lihat Program Studi', '#', 'Pendaftaran', 4, 'aktif', '2026-09-12 07:40:43'),
(2, 'Pendidikan Kesehatan', 'Untuk Masa Depan', 'PENDIDIKAN BERKUALITAS', 'Mengembangkan kompetensi mahasiswa melalui pembelajaran berkualitas, teknologi, penelitian, dan pengalaman praktik.', '20260914034004_1db88d50.jpeg', 'page/program-studi/program-studi.php', 'Lihat Program Studi', '#', 'Pendaftaran', 3, 'aktif', '2026-09-12 07:40:43'),
(4, 'Membangun Generasii', 'Tenaga Kesehatan Profesional', 'FAKULTAS ILMU KESEHATAN', 'Mewujudkan pendidikan kesehatan yang unggul, profesional, inovatif, dan berintegritas untuk masa depan yang lebih baik.', '20260914033939_865a2e52.jpeg', 'page/program-studi/program-studi.php', 'Lihat Program Studi', '#', 'Pendaftaran', 1, 'aktif', '2026-09-12 07:48:41'),
(5, 'Pendidikan Kesehatan', 'Untuk Masa Depan', 'PENDIDIKAN BERKUALITAS', 'Mengembangkan kompetensi mahasiswa melalui pembelajaran berkualitas, teknologi, penelitian, dan pengalaman praktik.', '20260914033949_f75787cb.jpeg', 'page/program-studi/program-studi.php', 'Lihat Program Studi', '#', 'Pendaftaran', 2, 'aktif', '2026-09-12 07:48:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `slider_beranda`
--
ALTER TABLE `slider_beranda`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `slider_beranda`
--
ALTER TABLE `slider_beranda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
