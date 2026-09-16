-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2026 at 10:59 AM
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
-- Table structure for table `prodi_fasilitas`
--

CREATE TABLE `prodi_fasilitas` (
  `id` int(11) NOT NULL,
  `prodi_id` int(11) NOT NULL,
  `nama_fasilitas` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prodi_fasilitas`
--

INSERT INTO `prodi_fasilitas` (`id`, `prodi_id`, `nama_fasilitas`, `deskripsi`, `gambar`, `nomor_urut`, `created_at`) VALUES
(19, 1, 'Laboratorium Keperawatan', 'Laboratorium untuk praktik mahasiswa keperawatan.', NULL, 1, '2026-09-14 05:58:54'),
(20, 1, 'Laboratorium Komputer', 'Laboratorium komputer untuk mendukung kegiatan pembelajaran.', NULL, 2, '2026-09-14 05:58:54'),
(21, 1, 'Perpustakaan', 'Perpustakaan dengan koleksi buku dan referensi kesehatan.', NULL, 3, '2026-09-14 05:58:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prodi_fasilitas`
--
ALTER TABLE `prodi_fasilitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prodi_fasilitas` (`prodi_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prodi_fasilitas`
--
ALTER TABLE `prodi_fasilitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
