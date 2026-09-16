-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2026 at 11:03 AM
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
-- Table structure for table `survey`
--

CREATE TABLE `survey` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `target_responden` varchar(100) NOT NULL DEFAULT 'Umum',
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'nonaktif',
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey`
--

INSERT INTO `survey` (`id`, `judul`, `slug`, `deskripsi`, `target_responden`, `tanggal_mulai`, `tanggal_selesai`, `status`, `nomor_urut`, `created_at`, `updated_at`) VALUES
(1, 'Penilaian Pelayanan FIKES', 'penilaian-pelayanan-fikes', 'Survey kepuasan terhadap pelayanan Fakultas Ilmu Kesehatan.', 'Mahasiswa', '2026-09-15', NULL, 'aktif', 1, '2026-09-15 07:38:21', '2026-09-15 07:38:21'),
(2, 'Penilaian Pelayanan Sarpras Mahasiswa', 'penilaian-pelayanan-sarpras-mahasiswa', 'Survey penilaian pelayanan sarana dan prasarana untuk mahasiswa/mahasiswi.', 'Mahasiswa', '2026-09-15', NULL, 'aktif', 2, '2026-09-15 07:38:21', '2026-09-15 07:38:21'),
(3, 'Penilaian Pelayanan Sarpras Karyawan', 'penilaian-pelayanan-sarpras-karyawan', 'Survey penilaian pelayanan sarana dan prasarana untuk karyawan/pegawai.', 'Karyawan/Pegawai', '2026-09-15', NULL, 'aktif', 3, '2026-09-15 07:38:21', '2026-09-15 07:38:21'),
(4, 'PELAYANAN FEB', 'survey-1789458006', '', 'Masyarakat', '2026-09-15', '2027-09-30', 'aktif', 1, '2026-09-15 07:40:06', '2026-09-15 07:40:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `survey`
--
ALTER TABLE `survey`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_survey_status` (`status`),
  ADD KEY `idx_survey_urut` (`nomor_urut`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `survey`
--
ALTER TABLE `survey`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
