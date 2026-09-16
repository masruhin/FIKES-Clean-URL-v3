-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2026 at 10:58 AM
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
-- Table structure for table `misi`
--

CREATE TABLE `misi` (
  `id` int(10) UNSIGNED NOT NULL,
  `nomor` int(11) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `isi` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `misi`
--

INSERT INTO `misi` (`id`, `nomor`, `judul`, `isi`, `created_at`) VALUES
(1, 1, 'Pendidikan', 'Menyelenggarakan pendidikan dan pengajaran di bidang ilmu kesehatan mengacu kepada Kurikulum Kerangka Kualifikasi Nasional Indonesia.', '2026-09-08 07:46:57'),
(2, 2, 'Pengembangan Keilmuan', 'Menyelenggarakan proses pendidikan dan menghasilkan lulusan yang berakhlak mulia, berkemampuan IPTEKs dan berjiwa wirausaha.', '2026-09-08 07:46:57'),
(3, 3, 'Pengabdian Masyarakat', 'Menyelenggarakan dan mengembangkan ilmu pengetahuan dan riset di bidang kesehatan.', '2026-09-08 07:46:57'),
(4, 4, 'Pengembangan Sumber Daya', 'Menyelenggarakan dan mengembangkan pengabdian kepada masyarakat di bidang kesehatan.', '2026-09-08 07:46:57'),
(5, 5, 'Kerja Sama Strategis', 'Membangun dan memperluas kerja sama dengan berbagai pihak untuk mendukung pengembangan pendidikan, penelitian, dan pengabdian kepada masyarakat.', '2026-09-08 07:46:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `misi`
--
ALTER TABLE `misi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `misi`
--
ALTER TABLE `misi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
