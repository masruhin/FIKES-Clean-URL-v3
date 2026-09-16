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
-- Table structure for table `program_studi`
--

CREATE TABLE `program_studi` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_prodi` varchar(30) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `jenjang` varchar(50) NOT NULL,
  `gelar` varchar(80) DEFAULT NULL,
  `kaprodi_nama` varchar(150) DEFAULT NULL,
  `kaprodi_nidn` varchar(50) DEFAULT NULL,
  `kaprodi_email` varchar(150) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `akreditasi` varchar(100) DEFAULT NULL,
  `nomor_akreditasi` varchar(150) DEFAULT NULL,
  `tanggal_akreditasi` date DEFAULT NULL,
  `sekretaris_nama` varchar(150) DEFAULT NULL,
  `sekretaris_nidn` varchar(50) DEFAULT NULL,
  `kontak_telepon` varchar(50) DEFAULT NULL,
  `kontak_email` varchar(150) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `durasi_studi` varchar(50) DEFAULT NULL,
  `sks_lulus` int(11) DEFAULT NULL,
  `jumlah_tenaga_kependidikan` int(11) DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL,
  `brosur` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `program_studi`
--

INSERT INTO `program_studi` (`id`, `kode_prodi`, `nama`, `jenjang`, `gelar`, `kaprodi_nama`, `kaprodi_nidn`, `kaprodi_email`, `deskripsi`, `foto`, `visi`, `akreditasi`, `nomor_akreditasi`, `tanggal_akreditasi`, `sekretaris_nama`, `sekretaris_nidn`, `kontak_telepon`, `kontak_email`, `alamat`, `durasi_studi`, `sks_lulus`, `jumlah_tenaga_kependidikan`, `gambar`, `brosur`, `status`, `created_at`) VALUES
(1, 'S1-NERS', 'Profesi Ners', 'Profesi', 'Ns.', 'Khodijah, M.Kep', '2222222222', '', '', '', 'Menjadi Program Studi Keperawatan yang unggul dalam pendidikan, penelitian, dan pengabdian kepada masyarakat.', 'Baik Sekali', '', NULL, 'Susi Muryani, MNS', '01234567891111', '081234567890', 'keperawatan@fikes.ac.id', 'Fakultas Ilmu Kesehatan', '4 Tahun', 144, 0, NULL, '', 'aktif', '2026-09-08 05:17:15'),
(2, 'S1-KEP', 'Ilmu Keperawatan', 'Sarjana', 'S.Kep', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'aktif', '2026-09-08 05:17:15'),
(3, 'D3-FAR', 'Farmasi', 'Sarjana', 'S.Farm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'aktif', '2026-09-08 05:17:15'),
(4, 'D3-KEP', 'Keperawatan', 'Diploma', 'A.Md.Kep.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'aktif', '2026-09-08 05:17:15'),
(5, 'D3-KEB', 'Kebidanan', 'Diploma', 'A.Md.Keb.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'aktif', '2026-09-08 05:17:15'),
(6, 'D3-K3', 'Keselamatan dan Kesehatan Kerja', 'Diploma', 'S.Tr.KKK.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'aktif', '2026-09-08 05:17:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `program_studi`
--
ALTER TABLE `program_studi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kode_prodi` (`kode_prodi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `program_studi`
--
ALTER TABLE `program_studi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
