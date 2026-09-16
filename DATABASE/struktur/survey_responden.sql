-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2026 at 11:04 AM
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
-- Table structure for table `survey_responden`
--

CREATE TABLE `survey_responden` (
  `id` bigint(20) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `nama` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `kategori_responden` varchar(100) DEFAULT NULL,
  `identitas` varchar(150) DEFAULT NULL,
  `tanggal_isi` datetime NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey_responden`
--

INSERT INTO `survey_responden` (`id`, `survey_id`, `nama`, `email`, `kategori_responden`, `identitas`, `tanggal_isi`, `ip_address`) VALUES
(1, 4, 'Kamal Furqon', 'furqonkamal9@gmail.com', 'Mahasiswa', '554541351351', '2026-09-15 15:05:36', '::1'),
(2, 4, 'Kamal', 'furqonkal9@gmail.com', 'Karyawan/Pegawai', '', '2026-09-15 15:12:17', '::1'),
(3, 4, 'masruhin', 'mas@gmail.com', 'Masyarakat', '3328545151', '2026-09-15 15:38:44', '::1'),
(4, 4, 'jaka', 'jaka@gmail.com', 'Mahasiswa', '5265115', '2026-09-16 11:40:11', '::1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `survey_responden`
--
ALTER TABLE `survey_responden`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sr_survey` (`survey_id`),
  ADD KEY `idx_sr_tanggal` (`survey_id`,`tanggal_isi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `survey_responden`
--
ALTER TABLE `survey_responden`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
