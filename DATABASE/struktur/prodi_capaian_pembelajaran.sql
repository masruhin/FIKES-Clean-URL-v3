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
-- Table structure for table `prodi_capaian_pembelajaran`
--

CREATE TABLE `prodi_capaian_pembelajaran` (
  `id` int(11) NOT NULL,
  `prodi_id` int(11) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `isi` text NOT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prodi_capaian_pembelajaran`
--

INSERT INTO `prodi_capaian_pembelajaran` (`id`, `prodi_id`, `kategori`, `isi`, `nomor_urut`, `created_at`) VALUES
(26, 1, 'Sikap', 'Mampu menunjukkan sikap profesional dan bertanggung jawab.', 1, '2026-09-14 05:58:54'),
(27, 1, 'Pengetahuan', 'Menguasai konsep dan teori ilmu keperawatan.', 2, '2026-09-14 05:58:54'),
(28, 1, 'Keterampilan Umum', 'Mampu menerapkan komunikasi efektif dalam pelayanan kesehatan.', 3, '2026-09-14 05:58:54'),
(29, 1, 'Keterampilan Khusus', 'Mampu memberikan asuhan keperawatan secara profesional.', 4, '2026-09-14 05:58:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prodi_capaian_pembelajaran`
--
ALTER TABLE `prodi_capaian_pembelajaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prodi_cpl` (`prodi_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prodi_capaian_pembelajaran`
--
ALTER TABLE `prodi_capaian_pembelajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
