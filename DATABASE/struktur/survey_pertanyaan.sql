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
-- Table structure for table `survey_pertanyaan`
--

CREATE TABLE `survey_pertanyaan` (
  `id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `pertanyaan` text NOT NULL,
  `tipe` enum('skala','pilihan_ganda','ya_tidak','isian','textarea') NOT NULL DEFAULT 'skala',
  `wajib` tinyint(1) NOT NULL DEFAULT 1,
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey_pertanyaan`
--

INSERT INTO `survey_pertanyaan` (`id`, `survey_id`, `pertanyaan`, `tipe`, `wajib`, `nomor_urut`, `status`, `created_at`) VALUES
(1, 4, 'APAKAH PUAS?', 'skala', 1, 1, 'aktif', '2026-09-15 07:40:27'),
(2, 4, 'apakah fasilitas mendukung?', 'skala', 1, 1, 'aktif', '2026-09-15 08:04:34'),
(3, 4, 'berikan komentar', 'isian', 1, 1, 'aktif', '2026-09-15 08:40:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `survey_pertanyaan`
--
ALTER TABLE `survey_pertanyaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sp_survey` (`survey_id`),
  ADD KEY `idx_sp_urut` (`survey_id`,`nomor_urut`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `survey_pertanyaan`
--
ALTER TABLE `survey_pertanyaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
