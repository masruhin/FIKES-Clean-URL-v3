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
-- Table structure for table `prodi_kurikulum`
--

CREATE TABLE `prodi_kurikulum` (
  `id` int(11) NOT NULL,
  `prodi_id` int(11) NOT NULL,
  `kode_mk` varchar(30) DEFAULT NULL,
  `nama_mk` varchar(150) NOT NULL,
  `semester` varchar(30) DEFAULT NULL,
  `sks` decimal(4,1) DEFAULT 0.0,
  `jenis` varchar(50) DEFAULT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prodi_kurikulum`
--

INSERT INTO `prodi_kurikulum` (`id`, `prodi_id`, `kode_mk`, `nama_mk`, `semester`, `sks`, `jenis`, `nomor_urut`, `created_at`) VALUES
(25, 1, 'KEP101', 'Dasar-Dasar Keperawatan', '1', 3.0, NULL, 1, '2026-09-14 05:58:54'),
(26, 1, 'KEP102', 'Anatomi dan Fisiologi', '1', 4.0, NULL, 2, '2026-09-14 05:58:54'),
(27, 1, 'KEP201', 'Keperawatan Medikal Bedah', '2', 4.0, NULL, 3, '2026-09-14 05:58:54'),
(28, 1, 'KEP301', 'Keperawatan Anak', '3', 3.0, NULL, 4, '2026-09-14 05:58:54'),
(29, 6, 'K3', 'K3', '4', 2.0, 'Wajib', 5, '2026-09-15 02:25:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prodi_kurikulum`
--
ALTER TABLE `prodi_kurikulum`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prodi_kurikulum` (`prodi_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prodi_kurikulum`
--
ALTER TABLE `prodi_kurikulum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
