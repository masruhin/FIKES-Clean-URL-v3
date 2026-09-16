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
-- Table structure for table `struktur_organisasi`
--

CREATE TABLE `struktur_organisasi` (
  `id` int(11) NOT NULL,
  `periode` varchar(50) NOT NULL,
  `sk_rektor` varchar(200) NOT NULL,
  `dekan` varchar(200) NOT NULL,
  `wakil_dekan_akademik` varchar(200) NOT NULL,
  `wakil_dekan_adum_keu` varchar(200) NOT NULL,
  `wakil_dekan_kemahasiswaan` varchar(200) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `struktur_organisasi`
--

INSERT INTO `struktur_organisasi` (`id`, `periode`, `sk_rektor`, `dekan`, `wakil_dekan_akademik`, `wakil_dekan_adum_keu`, `wakil_dekan_kemahasiswaan`, `gambar`, `created_at`, `updated_at`) VALUES
(1, '2024 - 2026', 'Nomor 030/Univ.BHAMADA/KEP/V/2024', 'Rosmalia, S.T.,M.Kes.', 'Siswati, S.Si.T.,Bdn.,M.Kes.', 'Sri Hidayati, Ns.,M.Kep.,Sp.Kep.MB.', 'Deni Irawan, Ns.,M.Kep.', 'struktur_20260908111729_e58cb6b9.png', '2026-09-08 07:07:10', '2026-09-08 09:17:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
