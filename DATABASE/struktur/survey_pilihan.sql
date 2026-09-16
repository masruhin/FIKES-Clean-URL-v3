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
-- Table structure for table `survey_pilihan`
--

CREATE TABLE `survey_pilihan` (
  `id` int(11) NOT NULL,
  `pertanyaan_id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `nilai` decimal(8,2) DEFAULT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey_pilihan`
--

INSERT INTO `survey_pilihan` (`id`, `pertanyaan_id`, `label`, `nilai`, `nomor_urut`) VALUES
(1, 1, 'Sangat Tidak Puas', 1.00, 1),
(2, 1, 'Tidak Puas', 2.00, 2),
(3, 1, 'Cukup', 3.00, 3),
(4, 1, 'Puas', 4.00, 4),
(5, 1, 'Sangat Puas', 5.00, 5),
(6, 2, 'Sangat Tidak Puas', 1.00, 1),
(7, 2, 'Tidak Puas', 2.00, 2),
(8, 2, 'Cukup', 3.00, 3),
(9, 2, 'Puas', 4.00, 4),
(10, 2, 'Sangat Puas', 5.00, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `survey_pilihan`
--
ALTER TABLE `survey_pilihan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_spl_pertanyaan` (`pertanyaan_id`),
  ADD KEY `idx_spl_urut` (`pertanyaan_id`,`nomor_urut`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `survey_pilihan`
--
ALTER TABLE `survey_pilihan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
