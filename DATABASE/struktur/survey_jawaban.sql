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
-- Table structure for table `survey_jawaban`
--

CREATE TABLE `survey_jawaban` (
  `id` bigint(20) NOT NULL,
  `responden_id` bigint(20) NOT NULL,
  `pertanyaan_id` int(11) NOT NULL,
  `pilihan_id` int(11) DEFAULT NULL,
  `jawaban_text` text DEFAULT NULL,
  `nilai` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey_jawaban`
--

INSERT INTO `survey_jawaban` (`id`, `responden_id`, `pertanyaan_id`, `pilihan_id`, `jawaban_text`, `nilai`) VALUES
(1, 1, 1, 3, NULL, 3.00),
(2, 1, 2, 8, NULL, 3.00),
(3, 2, 1, 2, NULL, 2.00),
(4, 2, 2, 7, NULL, 2.00),
(5, 3, 1, 5, NULL, 5.00),
(6, 3, 2, 10, NULL, 5.00),
(7, 4, 1, 2, NULL, 2.00),
(8, 4, 2, 7, NULL, 2.00),
(9, 4, 3, NULL, 'kurang', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `survey_jawaban`
--
ALTER TABLE `survey_jawaban`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sj_responden` (`responden_id`),
  ADD KEY `idx_sj_pertanyaan` (`pertanyaan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `survey_jawaban`
--
ALTER TABLE `survey_jawaban`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
