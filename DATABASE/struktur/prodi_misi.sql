-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2026 at 11:00 AM
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
-- Table structure for table `prodi_misi`
--

CREATE TABLE `prodi_misi` (
  `id` int(11) NOT NULL,
  `prodi_id` int(11) NOT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `isi` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prodi_misi`
--

INSERT INTO `prodi_misi` (`id`, `prodi_id`, `nomor_urut`, `isi`, `created_at`) VALUES
(2, 2, 1, 'Menyelenggarakan pendidikan yang berkualitas dan relevan dengan kebutuhan masyarakat.', '2026-09-10 04:27:29'),
(3, 3, 1, 'Menyelenggarakan pendidikan yang berkualitas dan relevan dengan kebutuhan masyarakat.', '2026-09-10 04:27:29'),
(4, 4, 1, 'Menyelenggarakan pendidikan yang berkualitas dan relevan dengan kebutuhan masyarakat.', '2026-09-10 04:27:29'),
(5, 5, 1, 'Menyelenggarakan pendidikan yang berkualitas dan relevan dengan kebutuhan masyarakat.', '2026-09-10 04:27:29'),
(6, 6, 1, 'Menyelenggarakan pendidikan yang berkualitas dan relevan dengan kebutuhan masyarakat.', '2026-09-10 04:27:29'),
(9, 2, 2, 'Melaksanakan penelitian dan pengabdian kepada masyarakat sesuai bidang keilmuan program studi.', '2026-09-10 04:27:29'),
(10, 3, 2, 'Melaksanakan penelitian dan pengabdian kepada masyarakat sesuai bidang keilmuan program studi.', '2026-09-10 04:27:29'),
(11, 4, 2, 'Melaksanakan penelitian dan pengabdian kepada masyarakat sesuai bidang keilmuan program studi.', '2026-09-10 04:27:29'),
(12, 5, 2, 'Melaksanakan penelitian dan pengabdian kepada masyarakat sesuai bidang keilmuan program studi.', '2026-09-10 04:27:29'),
(13, 6, 2, 'Melaksanakan penelitian dan pengabdian kepada masyarakat sesuai bidang keilmuan program studi.', '2026-09-10 04:27:29'),
(16, 2, 3, 'Menghasilkan lulusan yang kompeten, profesional, beretika, adaptif, dan mampu berkolaborasi.', '2026-09-10 04:27:29'),
(17, 3, 3, 'Menghasilkan lulusan yang kompeten, profesional, beretika, adaptif, dan mampu berkolaborasi.', '2026-09-10 04:27:29'),
(18, 4, 3, 'Menghasilkan lulusan yang kompeten, profesional, beretika, adaptif, dan mampu berkolaborasi.', '2026-09-10 04:27:29'),
(19, 5, 3, 'Menghasilkan lulusan yang kompeten, profesional, beretika, adaptif, dan mampu berkolaborasi.', '2026-09-10 04:27:29'),
(20, 6, 3, 'Menghasilkan lulusan yang kompeten, profesional, beretika, adaptif, dan mampu berkolaborasi.', '2026-09-10 04:27:29'),
(41, 1, 1, 'Menyelenggarakan pendidikan yang berkualitas dan relevan dengan kebutuhan masyarakat.', '2026-09-14 05:58:54'),
(42, 1, 2, 'Melaksanakan penelitian dan pengabdian kepada masyarakat sesuai bidang keilmuan program studi.', '2026-09-14 05:58:54'),
(43, 1, 3, 'Menghasilkan lulusan yang kompeten, profesional, beretika, adaptif, dan mampu berkolaborasi.', '2026-09-14 05:58:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prodi_misi`
--
ALTER TABLE `prodi_misi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prodi_misi` (`prodi_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prodi_misi`
--
ALTER TABLE `prodi_misi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
