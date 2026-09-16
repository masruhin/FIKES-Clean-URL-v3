-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2026 at 10:52 AM
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
-- Table structure for table `akademik_penilaian`
--

CREATE TABLE `akademik_penilaian` (
  `id` int(11) NOT NULL,
  `komponen` varchar(150) NOT NULL,
  `bobot` decimal(5,2) NOT NULL DEFAULT 0.00,
  `keterangan` varchar(255) DEFAULT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `akademik_penilaian`
--

INSERT INTO `akademik_penilaian` (`id`, `komponen`, `bobot`, `keterangan`, `nomor_urut`, `status`, `created_at`) VALUES
(1, 'Tugas / Proyek', 20.00, 'Contoh komponen penilaian.', 2, 'aktif', '2026-09-14 06:30:01'),
(2, 'UTS', 30.00, 'Contoh komponen penilaian.', 2, 'aktif', '2026-09-14 06:30:01'),
(3, 'UAS', 40.00, 'Contoh komponen penilaian.', 3, 'aktif', '2026-09-14 06:30:01'),
(5, 'kehadiran', 10.00, 'Contoh komponen penilaian.', 4, 'aktif', '2026-09-14 06:33:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akademik_penilaian`
--
ALTER TABLE `akademik_penilaian`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akademik_penilaian`
--
ALTER TABLE `akademik_penilaian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
