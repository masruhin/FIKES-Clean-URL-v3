-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2026 at 11:01 AM
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
-- Table structure for table `sertifikat_akreditasi`
--

CREATE TABLE `sertifikat_akreditasi` (
  `id_sertifikat` int(10) UNSIGNED NOT NULL,
  `id_prodi` varchar(100) NOT NULL,
  `id_institusi` varchar(100) NOT NULL,
  `id_lembaga` varchar(100) NOT NULL,
  `nomor_sk` varchar(255) NOT NULL,
  `peringkat` varchar(100) NOT NULL,
  `tanggal_sk` date NOT NULL,
  `tanggal_kadaluarsa` date NOT NULL,
  `file_sertifikat` varchar(255) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sertifikat_akreditasi`
--

INSERT INTO `sertifikat_akreditasi` (`id_sertifikat`, `id_prodi`, `id_institusi`, `id_lembaga`, `nomor_sk`, `peringkat`, `tanggal_sk`, `tanggal_kadaluarsa`, `file_sertifikat`, `status_aktif`, `created_at`, `updated_at`) VALUES
(3, '2', 'univ bhamada', 'LAM-PTKes', 'Contoh/0001/AKR/2024', 'Baik Sekali', '2026-09-14', '2026-09-30', '20260914_052216_d38e465b.docx', 1, '2026-09-14 03:21:33', '2026-09-14 03:22:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sertifikat_akreditasi`
--
ALTER TABLE `sertifikat_akreditasi`
  ADD PRIMARY KEY (`id_sertifikat`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sertifikat_akreditasi`
--
ALTER TABLE `sertifikat_akreditasi`
  MODIFY `id_sertifikat` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
