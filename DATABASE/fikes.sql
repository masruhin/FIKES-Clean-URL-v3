-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Sep 2026 pada 06.48
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

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
-- Struktur dari tabel `akademik_dokumen`
--

CREATE TABLE `akademik_dokumen` (
  `id` int(11) NOT NULL,
  `kategori` enum('panduan','formulir','kelulusan') NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `isi` longtext DEFAULT NULL,
  `file_dokumen` varchar(255) DEFAULT NULL,
  `link_url` varchar(500) DEFAULT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `akademik_jadwal`
--

CREATE TABLE `akademik_jadwal` (
  `id` int(11) NOT NULL,
  `prodi_id` int(11) DEFAULT NULL,
  `jenis` enum('Kuliah','UTS','UAS') NOT NULL DEFAULT 'Kuliah',
  `kode_mk` varchar(30) DEFAULT NULL,
  `nama_kegiatan` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `hari` varchar(20) DEFAULT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruang` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `akademik_kalender`
--

CREATE TABLE `akademik_kalender` (
  `id` int(11) NOT NULL,
  `tahun_ajaran` varchar(30) NOT NULL,
  `kategori` varchar(60) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `akademik_kalender`
--

INSERT INTO `akademik_kalender` (`id`, `tahun_ajaran`, `kategori`, `judul`, `tanggal_mulai`, `tanggal_selesai`, `keterangan`, `nomor_urut`, `status`, `created_at`) VALUES
(1, '2026/2027', 'Perkuliahan', 'Awal Perkuliahan Semester Ganjil', '2026-09-01', '2026-09-01', 'Contoh data awal; silakan sesuaikan dengan kalender resmi FIKES..', 1, 'aktif', '2026-09-14 06:30:01'),
(2, '2026/2027', 'Ujian', 'Ujian Tengah Semester (UTS)', '2026-11-02', '2026-11-07', 'Contoh data awal.', 2, 'aktif', '2026-09-14 06:30:01'),
(3, '2026/2027', 'Ujian', 'Ujian Akhir Semester (UAS)', '2027-01-04', '2027-01-16', 'Contoh data awal.', 3, 'aktif', '2026-09-14 06:30:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `akademik_penilaian`
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
-- Dumping data untuk tabel `akademik_penilaian`
--

INSERT INTO `akademik_penilaian` (`id`, `komponen`, `bobot`, `keterangan`, `nomor_urut`, `status`, `created_at`) VALUES
(1, 'Tugas / Proyek', 20.00, 'Contoh komponen penilaian.', 2, 'aktif', '2026-09-14 06:30:01'),
(2, 'UTS', 30.00, 'Contoh komponen penilaian.', 2, 'aktif', '2026-09-14 06:30:01'),
(3, 'UAS', 40.00, 'Contoh komponen penilaian.', 3, 'aktif', '2026-09-14 06:30:01'),
(5, 'kehadiran', 10.00, 'Contoh komponen penilaian.', 4, 'aktif', '2026-09-14 06:33:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `akademik_registrasi`
--

CREATE TABLE `akademik_registrasi` (
  `id` int(11) NOT NULL,
  `tahun_ajaran` varchar(30) NOT NULL,
  `jenis` enum('UKT/SPP','KRS','Persetujuan KRS','Registrasi') NOT NULL DEFAULT 'Registrasi',
  `judul` varchar(255) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `akademik_registrasi`
--

INSERT INTO `akademik_registrasi` (`id`, `tahun_ajaran`, `jenis`, `judul`, `tanggal_mulai`, `tanggal_selesai`, `keterangan`, `status`, `created_at`) VALUES
(1, '2026/2027', 'UKT/SPP', 'Pembayaran UKT/SPP Semester Ganjil', '2026-08-10', '2026-08-28', 'ASDASD', 'aktif', '2026-09-14 06:30:01'),
(2, '2026/2027', 'UKT/SPP', 'Pengisian KRS', '2026-08-24', '2026-09-05', 'SDFSFSDFSDFDSF', 'aktif', '2026-09-14 06:30:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `akademik_silabus`
--

CREATE TABLE `akademik_silabus` (
  `id` int(11) NOT NULL,
  `kurikulum_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file_dokumen` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `akademik_silabus`
--

INSERT INTO `akademik_silabus` (`id`, `kurikulum_id`, `judul`, `deskripsi`, `file_dokumen`, `status`, `created_at`) VALUES
(1, 29, 'Awal Perkuliahan Semester Ganjilllll', 'admin', '20260915042602_1a43e24a.docx', 'aktif', '2026-09-15 02:26:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `akademik_skala_nilai`
--

CREATE TABLE `akademik_skala_nilai` (
  `id` int(11) NOT NULL,
  `kode_nilai` varchar(10) NOT NULL,
  `nilai_min` decimal(5,2) NOT NULL DEFAULT 0.00,
  `nilai_max` decimal(5,2) NOT NULL DEFAULT 0.00,
  `nilai_mutu` decimal(4,2) NOT NULL DEFAULT 0.00,
  `keterangan` varchar(255) DEFAULT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `akademik_skala_nilai`
--

INSERT INTO `akademik_skala_nilai` (`id`, `kode_nilai`, `nilai_min`, `nilai_max`, `nilai_mutu`, `keterangan`, `nomor_urut`, `status`, `created_at`) VALUES
(1, 'A', 85.00, 100.00, 4.00, 'Sangat Baik', 1, 'aktif', '2026-09-21 03:30:17'),
(2, 'A-', 80.00, 84.99, 3.75, 'Sangat Baik', 2, 'aktif', '2026-09-21 03:30:17'),
(3, 'B+', 75.00, 79.99, 3.50, 'Baik', 3, 'aktif', '2026-09-21 03:30:17'),
(4, 'B', 70.00, 74.99, 3.00, 'Baik', 4, 'aktif', '2026-09-21 03:30:17'),
(5, 'B-', 65.00, 69.99, 2.75, 'Cukup Baik', 5, 'aktif', '2026-09-21 03:30:17'),
(6, 'C+', 60.00, 64.99, 2.50, 'Cukup', 6, 'aktif', '2026-09-21 03:30:17'),
(7, 'C', 55.00, 59.99, 2.00, 'Cukup', 7, 'aktif', '2026-09-21 03:30:17'),
(8, 'C-', 50.00, 54.99, 1.75, 'Kurang', 8, 'aktif', '2026-09-21 03:30:17'),
(9, 'D', 40.00, 49.99, 1.00, 'Kurang', 9, 'aktif', '2026-09-21 03:30:17'),
(10, 'E', 0.00, 39.99, 0.00, 'Tidak Lulus', 10, 'aktif', '2026-09-21 03:30:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `berita`
--

CREATE TABLE `berita` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL DEFAULT 'Berita',
  `ringkasan` text DEFAULT NULL,
  `isi` longtext NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `penulis` varchar(150) DEFAULT NULL,
  `tanggal_terbit` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('draft','terbit') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `berita`
--

INSERT INTO `berita` (`id`, `judul`, `slug`, `kategori`, `ringkasan`, `isi`, `gambar`, `penulis`, `tanggal_terbit`, `status`, `created_at`, `updated_at`) VALUES
(1, 'asdasdd', 'asdasdd', 'Berita', 'asdasdasd', 'asdasdasd', '20260914034106_7a9ea462.jpeg', 'Admin FIKES', '2026-09-12 16:06:00', 'terbit', '2026-09-12 09:06:42', '2026-09-14 01:41:06'),
(2, 'okeaaa', 'oke', 'Berita', 'asdakjfadfjkbaas\r\nasdajsdajdn', 'asdasdhlnkajsld<div>askdjabskjdbha sd</div><div>asbd asbd</div><div>lasndlasd</div>', '20260914042413_9c52ca0e.jpeg', 'Admin FIKES', '2026-09-14 09:23:00', 'terbit', '2026-09-14 02:24:13', '2026-09-17 08:34:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen`
--

CREATE TABLE `dosen` (
  `id` int(10) UNSIGNED NOT NULL,
  `nidn` varchar(30) DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `program_studi` varchar(100) NOT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dosen`
--

INSERT INTO `dosen` (`id`, `nidn`, `nama`, `program_studi`, `jabatan`, `email`, `foto`, `status`, `created_at`) VALUES
(2, 'ds231233', 'adssss', 'Keperawatan', 'sdasd', 'furqonkamal9@gmail.com', 'dosen_20260909101621_5eb8169b.jpg', 'aktif', '2026-09-09 08:16:21'),
(3, '213123', 'aaaa', 'Keperawatan', 'aaaa', 'furqonkamal9@gmail.com', 'dosen_20260909102521_1aaa54ee.jpg', 'aktif', '2026-09-09 08:25:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen_bidang_ajar`
--

CREATE TABLE `dosen_bidang_ajar` (
  `id` int(11) NOT NULL,
  `dosen_id` int(11) NOT NULL,
  `nilai` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dosen_bidang_ajar`
--

INSERT INTO `dosen_bidang_ajar` (`id`, `dosen_id`, `nilai`, `created_at`) VALUES
(3, 2, 'k3', '2026-09-09 08:16:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen_keilmuan`
--

CREATE TABLE `dosen_keilmuan` (
  `id` int(11) NOT NULL,
  `dosen_id` int(11) NOT NULL,
  `nilai` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dosen_keilmuan`
--

INSERT INTO `dosen_keilmuan` (`id`, `dosen_id`, `nilai`, `created_at`) VALUES
(2, 2, 'gadar', '2026-09-09 08:16:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen_pendidikan`
--

CREATE TABLE `dosen_pendidikan` (
  `id` int(11) NOT NULL,
  `dosen_id` int(11) NOT NULL,
  `nilai` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dosen_pendidikan`
--

INSERT INTO `dosen_pendidikan` (`id`, `dosen_id`, `nilai`, `created_at`) VALUES
(3, 2, 'S1', '2026-09-09 08:16:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kemahasiswaan`
--

CREATE TABLE `kemahasiswaan` (
  `id` int(10) UNSIGNED NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `judul` varchar(180) NOT NULL,
  `isi` text DEFAULT NULL,
  `status` enum('publish','draft') DEFAULT 'publish',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kemahasiswaan_anggota`
--

CREATE TABLE `kemahasiswaan_anggota` (
  `id` int(11) NOT NULL,
  `organisasi_slug` varchar(100) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `nim` varchar(50) DEFAULT NULL,
  `prodi` varchar(150) DEFAULT NULL,
  `angkatan` varchar(20) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kemahasiswaan_galeri`
--

CREATE TABLE `kemahasiswaan_galeri` (
  `id` int(11) NOT NULL,
  `organisasi_slug` varchar(100) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `status` enum('publish','draft') NOT NULL DEFAULT 'publish',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kemahasiswaan_kegiatan`
--

CREATE TABLE `kemahasiswaan_kegiatan` (
  `id` int(11) NOT NULL,
  `organisasi_slug` varchar(100) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `tanggal_kegiatan` date DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `status` enum('publish','draft') NOT NULL DEFAULT 'publish',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kemahasiswaan_organisasi`
--

CREATE TABLE `kemahasiswaan_organisasi` (
  `id` int(11) NOT NULL,
  `jenis` enum('himpunan','ukm') NOT NULL,
  `nama` varchar(150) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `fokus` text DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `ketua_nama` varchar(150) DEFAULT NULL,
  `sekretariat` varchar(255) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `instagram` varchar(150) DEFAULT NULL,
  `facebook` varchar(150) DEFAULT NULL,
  `youtube` varchar(150) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kemahasiswaan_organisasi`
--

INSERT INTO `kemahasiswaan_organisasi` (`id`, `jenis`, `nama`, `slug`, `kategori`, `deskripsi`, `fokus`, `visi`, `misi`, `ketua_nama`, `sekretariat`, `email`, `telepon`, `instagram`, `facebook`, `youtube`, `logo`, `status`, `nomor_urut`, `created_at`, `updated_at`) VALUES
(1, 'himpunan', 'HIMAFARDA', 'himafarda', 'Himpunan Mahasiswa Farmasi', 'Wadah mahasiswa Farmasi untuk mengembangkan potensi, kreativitas, kepemimpinan, dan kebersamaan di lingkungan FIKES.', 'Pengembangan organisasi, keilmuan, kreativitas, pengabdian, dan kebersamaan mahasiswa Farmasi.', '', '', '', '', '', '', '', '', '', 'org_20260917041355_6b70b2.jpg', 'aktif', 1, '2026-09-16 09:30:25', '2026-09-17 02:13:55'),
(2, 'himpunan', 'HIMASADA HIMASADA', 'himasada', 'Himpunan Mahasiswa', 'Organisasi mahasiswa sebagai ruang pengembangan potensi, aspirasi, komunikasi, dan kegiatan kemahasiswaan.', 'Pengembangan potensi mahasiswa, kepemimpinan, kebersamaan, dan kegiatan kemahasiswaan.', '', '', '', '', '', '', '', '', '', 'org_20260917042601_4f03bb.jpg', 'aktif', 2, '2026-09-16 09:30:25', '2026-09-17 02:26:01'),
(3, 'himpunan', 'HIMADIKA', 'himadika', 'Himpunan Mahasiswa Keperawatan', 'Wadah mahasiswa untuk kegiatan organisasi, pengembangan diri, kolaborasi, dan kontribusi bagi lingkungan kampus.', 'Keilmuan keperawatan, kepemimpinan, pengembangan diri, pengabdian, dan kegiatan mahasiswa.', '', '', '', '', '', '', '', '', '', 'org_20260917041437_6c511a.png', 'aktif', 3, '2026-09-16 09:30:25', '2026-09-17 02:14:37'),
(4, 'himpunan', 'HIMIKA', 'himika', 'Himpunan Mahasiswa', 'Organisasi mahasiswa yang mendukung aktivitas kemahasiswaan dan pengembangan kemampuan kepemimpinan.', 'Organisasi, aspirasi mahasiswa, kepemimpinan, kreativitas, dan kolaborasi.', '', '', '', '', '', '', '', '', '', 'org_20260917041453_ca245e.png', 'aktif', 4, '2026-09-16 09:30:25', '2026-09-17 02:14:53'),
(5, 'himpunan', 'HIMADAN', 'himadan', 'Himpunan Mahasiswa', 'Wadah mahasiswa untuk mengembangkan kreativitas, komunikasi, solidaritas, dan kegiatan sosial.', 'Kreativitas, komunikasi, solidaritas, kegiatan sosial, dan pengembangan mahasiswa.', '', '', '', '', '', '', '', '', '', 'org_20260917041510_e1601e.jpg', 'aktif', 5, '2026-09-16 09:30:25', '2026-09-17 02:15:10'),
(6, 'himpunan', 'BEM FIKES', 'bem-fikes', 'Organisasi Mahasiswa Tingkat Fakultas', 'Badan Eksekutif Mahasiswa sebagai wadah pelaksanaan program dan kegiatan mahasiswa di tingkat fakultas.', 'Koordinasi program kerja, pelayanan mahasiswa, pengembangan kegiatan, dan kolaborasi.', '', '', '', '', '', '', '', '', '', 'org_20260917041525_9dacca.jpg', 'aktif', 6, '2026-09-16 09:30:25', '2026-09-17 02:15:25'),
(7, 'himpunan', 'DPM FIKES', 'dpm-fikes', 'Organisasi Mahasiswa Tingkat Fakultas', 'Dewan Perwakilan Mahasiswa sebagai ruang perwakilan dan penyampaian aspirasi mahasiswa.', 'Perwakilan mahasiswa, aspirasi, pengawasan organisasi, dan komunikasi kelembagaan.', '', '', '', '', '', '', '', '', '', 'org_20260917041541_77047d.png', 'aktif', 7, '2026-09-16 09:30:25', '2026-09-17 02:15:41'),
(8, 'ukm', 'Karate', 'karate', 'Olahraga & Bela Diri', 'Wadah pengembangan bela diri, disiplin, kebugaran, karakter, dan prestasi mahasiswa.', 'Pengembangan bela diri, disiplin, kebugaran, karakter, dan prestasi mahasiswa.', '', '', '', '', '', '', '', '', '', 'org_20260917041611_3b1125.png', 'aktif', 1, '2026-09-16 09:30:25', '2026-09-17 02:16:11'),
(9, 'ukm', 'Basket', 'basket', 'Olahraga', 'Wadah pengembangan kemampuan bola basket, sportivitas, kebugaran, dan kerja sama tim.', 'Pengembangan kemampuan bola basket, sportivitas, kebugaran, dan kerja sama tim.', '', '', '', '', '', '', '', '', '', 'org_20260917041624_105f3c.png', 'aktif', 2, '2026-09-16 09:30:25', '2026-09-17 02:16:24'),
(10, 'ukm', 'Futsal', 'futsal', 'Olahraga', 'Wadah pengembangan teknik futsal, kekompakan, kebugaran, dan pengalaman kompetisi.', 'Pengembangan teknik futsal, kekompakan, kebugaran, dan pengalaman kompetisi.', '', '', '', '', '', '', '', '', '', 'org_20260917041637_96d83c.png', 'aktif', 3, '2026-09-16 09:30:25', '2026-09-17 02:16:37'),
(11, 'ukm', 'Badminton', 'badminton', 'Olahraga', 'Wadah pengembangan keterampilan badminton, kebugaran, sportivitas, dan prestasi mahasiswa.', 'Pengembangan keterampilan badminton, kebugaran, sportivitas, dan prestasi mahasiswa.', '', '', '', '', '', '', '', '', '', 'org_20260917041653_4b0416.png', 'aktif', 4, '2026-09-16 09:30:25', '2026-09-17 02:16:53'),
(12, 'ukm', 'Voli', 'voli', 'Olahraga', 'Wadah pengembangan kemampuan bola voli, kekompakan tim, kebugaran, dan prestasi.', 'Pengembangan kemampuan bola voli, kekompakan tim, kebugaran, dan prestasi.', '', '', '', '', '', '', '', '', '', 'org_20260917041707_dfcdf3.jpg', 'aktif', 5, '2026-09-16 09:30:25', '2026-09-17 02:17:07'),
(13, 'ukm', 'Silat', 'silat', 'Olahraga & Bela Diri', 'Wadah pengembangan seni bela diri, disiplin, karakter, kebugaran, dan prestasi.', 'Pengembangan seni bela diri, disiplin, karakter, kebugaran, dan prestasi.', '', '', '', '', '', '', '', '', '', 'org_20260917041723_8a777f.jpeg', 'aktif', 6, '2026-09-16 09:30:25', '2026-09-17 02:17:23'),
(14, 'ukm', 'BMB', 'bmb', 'Minat & Bakat', 'Ruang bagi mahasiswa untuk mengembangkan minat, bakat, kreativitas, dan pengalaman berorganisasi.', 'Pengembangan minat, bakat, kreativitas, dan pengalaman mahasiswa.', '', '', '', '', '', '', '', '', '', 'org_20260917041738_c43759.png', 'aktif', 7, '2026-09-16 09:30:25', '2026-09-17 02:17:38'),
(15, 'ukm', 'PIK', 'pik', 'Pengembangan Mahasiswa', 'Ruang pengembangan edukasi, komunikasi, kreativitas, dan kepedulian mahasiswa.', 'Pengembangan edukasi, komunikasi, kreativitas, dan kepedulian mahasiswa.', '', '', '', '', '', '', '', '', '', 'org_20260917041755_4ca497.jpg', 'aktif', 8, '2026-09-16 09:30:25', '2026-09-17 02:17:55'),
(16, 'ukm', 'BHAPALA', 'bhapala', 'Kepencintaalaman', 'Wadah kegiatan alam bebas, kepedulian lingkungan, kebersamaan, dan ketangguhan mahasiswa.', 'Pengembangan kegiatan alam bebas, kepedulian lingkungan, kebersamaan, dan ketangguhan mahasiswa.', '', '', '', '', '', '', '', '', '', 'org_20260917041811_a1d86a.jpg', 'aktif', 9, '2026-09-16 09:30:25', '2026-09-17 02:18:11'),
(17, 'ukm', 'Sentramada', 'sentramada', 'Seni & Kreativitas', 'Ruang ekspresi seni, kreativitas, kolaborasi, dan pengembangan potensi mahasiswa.', 'Pengembangan seni, kreativitas, ekspresi, dan kolaborasi mahasiswa.', '', '', '', '', '', '', '', '', '', 'org_20260917041829_961e71.png', 'aktif', 10, '2026-09-16 09:30:25', '2026-09-17 02:18:29'),
(18, 'ukm', 'Voice', 'voice', 'Seni & Musik', 'Wadah pengembangan vokal, musik, penampilan, kepercayaan diri, dan kreativitas seni.', 'Pengembangan vokal, musik, penampilan, kepercayaan diri, dan kreativitas seni.', '', '', '', '', '', '', '', '', '', 'org_20260917041842_6afc99.jpeg', 'aktif', 11, '2026-09-16 09:30:25', '2026-09-17 02:18:42'),
(19, 'ukm', 'Pramuka', 'pramuka', 'Kepanduan', 'Wadah pengembangan kepemimpinan, kedisiplinan, kemandirian, dan kegiatan sosial.', 'Pengembangan kepemimpinan, kedisiplinan, kemandirian, dan kegiatan sosial.', '', '', '', '', '', '', '', '', '', 'org_20260917041857_b0a70b.jpg', 'aktif', 12, '2026-09-16 09:30:25', '2026-09-17 02:18:57'),
(20, 'ukm', 'Bakti', 'bakti', 'Sosial & Pengabdian', 'Ruang pengembangan kepedulian sosial, pengabdian, dan kegiatan kemasyarakatan.', 'Pengembangan kepedulian sosial, pengabdian, dan kegiatan kemasyarakatan.', '', '', '', '', '', '', '', '', '', 'org_20260917041911_f53ff8.jpg', 'aktif', 13, '2026-09-16 09:30:25', '2026-09-17 02:19:11'),
(21, 'ukm', 'Jurnalika', 'jurnalika', 'Media & Jurnalistik', 'Wadah pengembangan jurnalistik, penulisan, dokumentasi, media, dan komunikasi.', 'Pengembangan jurnalistik, penulisan, dokumentasi, media, dan komunikasi.', '', '', '', '', '', '', '', '', '', 'org_20260917041926_8566bd.jpg', 'aktif', 14, '2026-09-16 09:30:25', '2026-09-17 02:19:26'),
(22, 'ukm', 'KSR', 'ksr', 'Kemanusiaan', 'Wadah pengembangan kepedulian kemanusiaan, kesiapsiagaan, dan kegiatan sosial.', 'Pengembangan kepedulian kemanusiaan, kesiapsiagaan, dan kegiatan sosial.', '', '', '', '', '', '', '', '', '', 'org_20260917041939_20c2f3.jpg', 'aktif', 15, '2026-09-16 09:30:25', '2026-09-17 02:19:39'),
(23, 'ukm', 'BEC', 'bec', 'Bahasa & Komunikasi', 'Ruang pengembangan kemampuan bahasa, komunikasi, kepercayaan diri, dan kreativitas.', 'Pengembangan kemampuan bahasa, komunikasi, kepercayaan diri, dan kreativitas.', '', '', '', '', '', '', '', '', '', 'org_20260917041953_4ecdb9.png', 'aktif', 16, '2026-09-16 09:30:25', '2026-09-17 02:19:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kemahasiswaan_pengurus`
--

CREATE TABLE `kemahasiswaan_pengurus` (
  `id` int(11) NOT NULL,
  `organisasi_slug` varchar(100) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `misi`
--

CREATE TABLE `misi` (
  `id` int(10) UNSIGNED NOT NULL,
  `nomor` int(11) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `isi` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `misi`
--

INSERT INTO `misi` (`id`, `nomor`, `judul`, `isi`, `created_at`) VALUES
(1, 1, 'Pendidikan', 'Menyelenggarakan pendidikan dan pengajaran di bidang ilmu kesehatan mengacu kepada Kurikulum Kerangka Kualifikasi Nasional Indonesia.', '2026-09-08 07:46:57'),
(2, 2, 'Pengembangan Keilmuan', 'Menyelenggarakan proses pendidikan dan menghasilkan lulusan yang berakhlak mulia, berkemampuan IPTEKs dan berjiwa wirausaha.', '2026-09-08 07:46:57'),
(3, 3, 'Pengabdian Masyarakat', 'Menyelenggarakan dan mengembangkan ilmu pengetahuan dan riset di bidang kesehatan.', '2026-09-08 07:46:57'),
(4, 4, 'Pengembangan Sumber Daya', 'Menyelenggarakan dan mengembangkan pengabdian kepada masyarakat di bidang kesehatan.', '2026-09-08 07:46:57'),
(5, 5, 'Kerja Sama Strategis', 'Membangun dan memperluas kerja sama dengan berbagai pihak untuk mendukung pengembangan pendidikan, penelitian, dan pengabdian kepada masyarakat.', '2026-09-08 07:46:57');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama_kampus` varchar(180) DEFAULT 'Fakultas Ilmu Kesehatan',
  `email` varchar(150) DEFAULT 'info@fikes.ac.id',
  `telepon` varchar(50) DEFAULT '(021) 1234567',
  `alamat` text DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `maps_embed` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `nama_kampus`, `email`, `telepon`, `alamat`, `instagram`, `facebook`, `youtube`, `maps_embed`) VALUES
(1, 'Fakultas Ilmu Kesehatan', 'fikes.bhamada@gmail.ac.id', '(021) 1234567', 'Alamat Fakultas Ilmu Kesehatan, Universitas Bhamada Slawi', 'fikes', 'fbfikes', 'ytfikes', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.1515727139526!2d109.11806027499709!3d-6.991421893009626!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6fbef42471658d%3A0x883656d1325ef066!2sUniversitas%20Bhamada%20Slawi!5e0!3m2!1sid!2sid!4v1787544396003!5m2!1sid!2sid');

-- --------------------------------------------------------

--
-- Struktur dari tabel `prodi_capaian_pembelajaran`
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
-- Dumping data untuk tabel `prodi_capaian_pembelajaran`
--

INSERT INTO `prodi_capaian_pembelajaran` (`id`, `prodi_id`, `kategori`, `isi`, `nomor_urut`, `created_at`) VALUES
(30, 1, 'Sikap', 'Mampu menunjukkan sikap profesional dan bertanggung jawab.', 1, '2026-09-16 09:19:22'),
(31, 1, 'Pengetahuan', 'Menguasai konsep dan teori ilmu keperawatan.', 2, '2026-09-16 09:19:22'),
(32, 1, 'Keterampilan Umum', 'Mampu menerapkan komunikasi efektif dalam pelayanan kesehatan.', 3, '2026-09-16 09:19:22'),
(33, 1, 'Keterampilan Khusus', 'Mampu memberikan asuhan keperawatan secara profesional.', 4, '2026-09-16 09:19:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `prodi_fasilitas`
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
-- Dumping data untuk tabel `prodi_fasilitas`
--

INSERT INTO `prodi_fasilitas` (`id`, `prodi_id`, `nama_fasilitas`, `deskripsi`, `gambar`, `nomor_urut`, `created_at`) VALUES
(22, 1, 'Laboratorium Keperawatan', 'Laboratorium untuk praktik mahasiswa keperawatan.', NULL, 1, '2026-09-16 09:19:22'),
(23, 1, 'Laboratorium Komputer', 'Laboratorium komputer untuk mendukung kegiatan pembelajaran.', NULL, 2, '2026-09-16 09:19:22'),
(24, 1, 'Perpustakaan', 'Perpustakaan dengan koleksi buku dan referensi kesehatan.', NULL, 3, '2026-09-16 09:19:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `prodi_kurikulum`
--

CREATE TABLE `prodi_kurikulum` (
  `id` int(11) NOT NULL,
  `prodi_id` int(11) NOT NULL,
  `kode_mk` varchar(30) DEFAULT NULL,
  `nama_mk` varchar(150) NOT NULL,
  `semester` varchar(30) DEFAULT NULL,
  `sks` decimal(4,1) DEFAULT 0.0,
  `jenis` varchar(50) DEFAULT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `prodi_kurikulum`
--

INSERT INTO `prodi_kurikulum` (`id`, `prodi_id`, `kode_mk`, `nama_mk`, `semester`, `sks`, `jenis`, `nomor_urut`, `created_at`) VALUES
(29, 6, 'K3', 'K3', '4', 2.0, 'Wajib', 5, '2026-09-15 02:25:18'),
(30, 1, 'KEP101', 'Dasar-Dasar Keperawatan', '1', 3.0, NULL, 1, '2026-09-16 09:19:22'),
(31, 1, 'KEP102', 'Anatomi dan Fisiologi', '1', 4.0, NULL, 2, '2026-09-16 09:19:22'),
(32, 1, 'KEP201', 'Keperawatan Medikal Bedah', '2', 4.0, NULL, 3, '2026-09-16 09:19:22'),
(33, 1, 'KEP301', 'Keperawatan Anak', '3', 3.0, NULL, 4, '2026-09-16 09:19:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `prodi_misi`
--

CREATE TABLE `prodi_misi` (
  `id` int(11) NOT NULL,
  `prodi_id` int(11) NOT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `isi` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `prodi_misi`
--

INSERT INTO `prodi_misi` (`id`, `prodi_id`, `nomor_urut`, `isi`, `created_at`) VALUES
(2, 2, 1, 'Menyelenggarakan pendidikan yang berkualitas dan relevan dengan kebutuhan masyarakat.', '2026-09-10 04:27:29'),
(3, 3, 1, 'Menyelenggarakan pendidikan yang berkualitas dan relevan dengan kebutuhan masyarakat.', '2026-09-10 04:27:29'),
(4, 4, 1, 'Menyelenggarakan pendidikan yang berkualitas dan relevan dengan kebutuhan masyarakat.', '2026-09-10 04:27:29'),
(5, 5, 1, 'Menyelenggarakan pendidikan yang berkualitas dan relevan dengan kebutuhan masyarakat.', '2026-09-10 04:27:29'),
(6, 6, 1, 'Menyelenggarakan pendidikan yang berkualitas dan relevan dengan kebutuhan masyarakat.', '2026-09-10 04:27:29'),
(9, 2, 2, 'Melaksanakan penelitian dan pengabdian kepada masyarakat sesuai bidang keilmuan program studi.', '2026-09-10 04:27:29'),
(10, 3, 2, 'Melaksanakan penelitian dan pengabdian kepada masyarakat sesuai bidang keilmuan program studi.', '2026-09-10 04:27:29'),
(11, 4, 2, 'Melaksanakan penelitian dan pengabdian kepada masyarakat sesuai bidang keilmuan program studi.', '2026-09-10 04:27:29'),
(12, 5, 2, 'Melaksanakan penelitian dan pengabdian kepada masyarakat sesuai bidang keilmuan program studi.', '2026-09-10 04:27:29'),
(13, 6, 2, 'Melaksanakan penelitian dan pengabdian kepada masyarakat sesuai bidang keilmuan program studi.', '2026-09-10 04:27:29'),
(16, 2, 3, 'Menghasilkan lulusan yang kompeten, profesional, beretika, adaptif, dan mampu berkolaborasi.', '2026-09-10 04:27:29'),
(17, 3, 3, 'Menghasilkan lulusan yang kompeten, profesional, beretika, adaptif, dan mampu berkolaborasi.', '2026-09-10 04:27:29'),
(18, 4, 3, 'Menghasilkan lulusan yang kompeten, profesional, beretika, adaptif, dan mampu berkolaborasi.', '2026-09-10 04:27:29'),
(19, 5, 3, 'Menghasilkan lulusan yang kompeten, profesional, beretika, adaptif, dan mampu berkolaborasi.', '2026-09-10 04:27:29'),
(20, 6, 3, 'Menghasilkan lulusan yang kompeten, profesional, beretika, adaptif, dan mampu berkolaborasi.', '2026-09-10 04:27:29'),
(44, 1, 1, 'Menyelenggarakan pendidikan yang berkualitas dan relevan dengan kebutuhan masyarakat.', '2026-09-16 09:19:22'),
(45, 1, 2, 'Melaksanakan penelitian dan pengabdian kepada masyarakat sesuai bidang keilmuan program studi.', '2026-09-16 09:19:22'),
(46, 1, 3, 'Menghasilkan lulusan yang kompeten, profesional, beretika, adaptif, dan mampu berkolaborasi.', '2026-09-16 09:19:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `program_studi`
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
-- Dumping data untuk tabel `program_studi`
--

INSERT INTO `program_studi` (`id`, `kode_prodi`, `nama`, `jenjang`, `gelar`, `kaprodi_nama`, `kaprodi_nidn`, `kaprodi_email`, `deskripsi`, `foto`, `visi`, `akreditasi`, `nomor_akreditasi`, `tanggal_akreditasi`, `sekretaris_nama`, `sekretaris_nidn`, `kontak_telepon`, `kontak_email`, `alamat`, `durasi_studi`, `sks_lulus`, `jumlah_tenaga_kependidikan`, `gambar`, `brosur`, `status`, `created_at`) VALUES
(1, 'S1-NERS', 'Profesi Ners', 'Profesi', 'Ns.', 'Khodijah, M.Kep', '2222222222', '', '', '', 'Menjadi Program Studi Keperawatan yang unggul dalam pendidikan, penelitian, dan pengabdian kepada masyarakat.', 'Baik Sekali', '', NULL, 'Susi Muryani, MNS', '01234567891111', '081234567890', 'keperawatan@fikes.ac.id', 'Fakultas Ilmu Kesehatan', '4 Tahun', 144, 0, NULL, '', 'aktif', '2026-09-08 05:17:15'),
(2, 'S1-KEP', 'Ilmu Keperawatan', 'Sarjana', 'S.Kep', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'aktif', '2026-09-08 05:17:15'),
(3, 'D3-FAR', 'Farmasi', 'Sarjana', 'S.Farm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'aktif', '2026-09-08 05:17:15'),
(4, 'D3-KEP', 'Keperawatan', 'Diploma', 'A.Md.Kep.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'aktif', '2026-09-08 05:17:15'),
(5, 'D3-KEB', 'Kebidanan', 'Diploma', 'A.Md.Keb.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'aktif', '2026-09-08 05:17:15'),
(6, 'D3-K3', 'Keselamatan dan Kesehatan Kerja', 'Diploma', 'S.Tr.KKK.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'aktif', '2026-09-08 05:17:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sertifikat_akreditasi`
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
-- Dumping data untuk tabel `sertifikat_akreditasi`
--

INSERT INTO `sertifikat_akreditasi` (`id_sertifikat`, `id_prodi`, `id_institusi`, `id_lembaga`, `nomor_sk`, `peringkat`, `tanggal_sk`, `tanggal_kadaluarsa`, `file_sertifikat`, `status_aktif`, `created_at`, `updated_at`) VALUES
(3, '2', 'univ bhamada', 'LAM-PTKes', 'Contoh/0001/AKR/2024', 'Baik Sekali', '2026-09-14', '2026-09-30', '20260914_052216_d38e465b.docx', 1, '2026-09-14 03:21:33', '2026-09-14 03:22:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `slider_beranda`
--

CREATE TABLE `slider_beranda` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `highlight` varchar(255) DEFAULT NULL,
  `label` varchar(150) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) NOT NULL,
  `link_utama` varchar(255) DEFAULT NULL,
  `teks_tombol_utama` varchar(100) DEFAULT NULL,
  `link_kedua` varchar(255) DEFAULT NULL,
  `teks_tombol_kedua` varchar(100) DEFAULT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `slider_beranda`
--

INSERT INTO `slider_beranda` (`id`, `judul`, `highlight`, `label`, `deskripsi`, `gambar`, `link_utama`, `teks_tombol_utama`, `link_kedua`, `teks_tombol_kedua`, `nomor_urut`, `status`, `created_at`) VALUES
(1, 'Membangun Generasi', 'Tenaga Kesehatan Profesional', 'FAKULTAS ILMU KESEHATAN', 'Mewujudkan pendidikan kesehatan yang unggul, profesional, inovatif, dan berintegritas untuk masa depan yang lebih baik.', '20260914033930_14b336cb.jpeg', 'page/program-studi/program-studi.php', 'Lihat Program Studi', '#', 'Pendaftaran', 4, 'aktif', '2026-09-12 07:40:43'),
(2, 'Pendidikan Kesehatan', 'Untuk Masa Depan', 'PENDIDIKAN BERKUALITAS', 'Mengembangkan kompetensi mahasiswa melalui pembelajaran berkualitas, teknologi, penelitian, dan pengalaman praktik.', '20260914034004_1db88d50.jpeg', 'page/program-studi/program-studi.php', 'Lihat Program Studi', '#', 'Pendaftaran', 3, 'aktif', '2026-09-12 07:40:43'),
(4, 'Membangun Generasi', 'Tenaga Kesehatan Profesional', 'FAKULTAS ILMU KESEHATAN', 'Mewujudkan pendidikan kesehatan yang unggul, profesional, inovatif, dan berintegritas untuk masa depan yang lebih baik.', '20260914033939_865a2e52.jpeg', 'page/program-studi/program-studi.php', 'Lihat Program Studi', '#', 'Pendaftaran', 1, 'aktif', '2026-09-12 07:48:41'),
(5, 'Pendidikan Kesehatan', 'Untuk Masa Depan', 'PENDIDIKAN BERKUALITAS', 'Mengembangkan kompetensi mahasiswa melalui pembelajaran berkualitas, teknologi, penelitian, dan pengalaman praktik.', '20260914033949_f75787cb.jpeg', 'page/program-studi/program-studi.php', 'Lihat Program Studi', '#', 'Pendaftaran', 2, 'aktif', '2026-09-12 07:48:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `struktur_organisasi`
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
-- Dumping data untuk tabel `struktur_organisasi`
--

INSERT INTO `struktur_organisasi` (`id`, `periode`, `sk_rektor`, `dekan`, `wakil_dekan_akademik`, `wakil_dekan_adum_keu`, `wakil_dekan_kemahasiswaan`, `gambar`, `created_at`, `updated_at`) VALUES
(1, '2024 - 2026', 'Nomor 030/Univ.BHAMADA/KEP/V/2024', 'Rosmalia, S.T.,M.Kes. MOH', 'Siswati, S.Si.T.,Bdn.,M.Kes.', 'Sri Hidayati, Ns.,M.Kep.,Sp.Kep.MB.', 'Deni Irawan, Ns.,M.Kep.', 'struktur_20260908111729_e58cb6b9.png', '2026-09-08 07:07:10', '2026-09-17 09:00:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `survey`
--

CREATE TABLE `survey` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `target_responden` varchar(100) NOT NULL DEFAULT 'Umum',
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'nonaktif',
  `nomor_urut` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `survey`
--

INSERT INTO `survey` (`id`, `judul`, `slug`, `deskripsi`, `target_responden`, `tanggal_mulai`, `tanggal_selesai`, `status`, `nomor_urut`, `created_at`, `updated_at`) VALUES
(1, 'Penilaian Pelayanan FIKES', 'penilaian-pelayanan-fikes', 'Survey kepuasan terhadap pelayanan Fakultas Ilmu Kesehatan.', 'Mahasiswa', '2026-09-15', NULL, 'aktif', 1, '2026-09-15 07:38:21', '2026-09-15 07:38:21'),
(2, 'Penilaian Pelayanan Sarpras Mahasiswa', 'penilaian-pelayanan-sarpras-mahasiswa', 'Survey penilaian pelayanan sarana dan prasarana untuk mahasiswa/mahasiswi.', 'Mahasiswa', '2026-09-15', NULL, 'aktif', 2, '2026-09-15 07:38:21', '2026-09-15 07:38:21'),
(3, 'Penilaian Pelayanan Sarpras Karyawan', 'penilaian-pelayanan-sarpras-karyawan', 'Survey penilaian pelayanan sarana dan prasarana untuk karyawan/pegawai.', 'Karyawan/Pegawai', '2026-09-15', NULL, 'aktif', 3, '2026-09-15 07:38:21', '2026-09-15 07:38:21'),
(4, 'PELAYANAN FEB', 'survey-1789458006', '', 'Masyarakat', '2026-09-15', '2027-09-30', 'aktif', 1, '2026-09-15 07:40:06', '2026-09-15 07:40:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `survey_jawaban`
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
-- Dumping data untuk tabel `survey_jawaban`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `survey_pertanyaan`
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
-- Dumping data untuk tabel `survey_pertanyaan`
--

INSERT INTO `survey_pertanyaan` (`id`, `survey_id`, `pertanyaan`, `tipe`, `wajib`, `nomor_urut`, `status`, `created_at`) VALUES
(1, 4, 'APAKAH PUAS?', 'skala', 1, 1, 'aktif', '2026-09-15 07:40:27'),
(2, 4, 'apakah fasilitas mendukung?', 'skala', 1, 1, 'aktif', '2026-09-15 08:04:34'),
(3, 4, 'berikan komentar', 'isian', 1, 1, 'aktif', '2026-09-15 08:40:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `survey_pilihan`
--

CREATE TABLE `survey_pilihan` (
  `id` int(11) NOT NULL,
  `pertanyaan_id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `nilai` decimal(8,2) DEFAULT NULL,
  `nomor_urut` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `survey_pilihan`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `survey_responden`
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
-- Dumping data untuk tabel `survey_responden`
--

INSERT INTO `survey_responden` (`id`, `survey_id`, `nama`, `email`, `kategori_responden`, `identitas`, `tanggal_isi`, `ip_address`) VALUES
(1, 4, 'Kamal Furqon', 'furqonkamal9@gmail.com', 'Mahasiswa', '554541351351', '2026-09-15 15:05:36', '::1'),
(2, 4, 'Kamal', 'furqonkal9@gmail.com', 'Karyawan/Pegawai', '', '2026-09-15 15:12:17', '::1'),
(3, 4, 'masruhin', 'mas@gmail.com', 'Masyarakat', '3328545151', '2026-09-15 15:38:44', '::1'),
(4, 4, 'jaka', 'jaka@gmail.com', 'Mahasiswa', '5265115', '2026-09-16 11:40:11', '::1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor') NOT NULL DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator FIKES', 'admin', '0192023a7bbd73250516f069df18b500', 'admin', '2026-09-08 05:17:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `visi_misi`
--

CREATE TABLE `visi_misi` (
  `id` int(10) UNSIGNED NOT NULL,
  `visi` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `visi_misi`
--

INSERT INTO `visi_misi` (`id`, `visi`, `created_at`, `updated_at`) VALUES
(1, 'Menjadi institusi pendidikan tinggi kesehatan yang unggul, profesional, inovatif, berintegritas, dan mampu memberikan kontribusi nyata bagi peningkatan derajat kesehatan masyarakat.sdsdsd', '2026-09-08 05:17:15', '2026-09-08 07:46:57');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `akademik_dokumen`
--
ALTER TABLE `akademik_dokumen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dokumen` (`kategori`,`nomor_urut`);

--
-- Indeks untuk tabel `akademik_jadwal`
--
ALTER TABLE `akademik_jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_jadwal` (`tanggal`,`jam_mulai`),
  ADD KEY `idx_jadwal_prodi` (`prodi_id`);

--
-- Indeks untuk tabel `akademik_kalender`
--
ALTER TABLE `akademik_kalender`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kalender` (`tahun_ajaran`,`tanggal_mulai`);

--
-- Indeks untuk tabel `akademik_penilaian`
--
ALTER TABLE `akademik_penilaian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `akademik_registrasi`
--
ALTER TABLE `akademik_registrasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_registrasi` (`tahun_ajaran`,`tanggal_mulai`);

--
-- Indeks untuk tabel `akademik_silabus`
--
ALTER TABLE `akademik_silabus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_silabus_kurikulum` (`kurikulum_id`);

--
-- Indeks untuk tabel `akademik_skala_nilai`
--
ALTER TABLE `akademik_skala_nilai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_nilai_huruf` (`kode_nilai`),
  ADD UNIQUE KEY `uq_skala_kode` (`kode_nilai`);

--
-- Indeks untuk tabel `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_berita_status_tanggal` (`status`,`tanggal_terbit`);

--
-- Indeks untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `dosen_bidang_ajar`
--
ALTER TABLE `dosen_bidang_ajar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dosen_id` (`dosen_id`);

--
-- Indeks untuk tabel `dosen_keilmuan`
--
ALTER TABLE `dosen_keilmuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dosen_id` (`dosen_id`);

--
-- Indeks untuk tabel `dosen_pendidikan`
--
ALTER TABLE `dosen_pendidikan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dosen_id` (`dosen_id`);

--
-- Indeks untuk tabel `kemahasiswaan`
--
ALTER TABLE `kemahasiswaan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kemahasiswaan_anggota`
--
ALTER TABLE `kemahasiswaan_anggota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ka_org` (`organisasi_slug`),
  ADD KEY `idx_ka_status` (`organisasi_slug`,`status`);

--
-- Indeks untuk tabel `kemahasiswaan_galeri`
--
ALTER TABLE `kemahasiswaan_galeri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kg_org` (`organisasi_slug`),
  ADD KEY `idx_kg_status` (`organisasi_slug`,`status`);

--
-- Indeks untuk tabel `kemahasiswaan_kegiatan`
--
ALTER TABLE `kemahasiswaan_kegiatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kk_org` (`organisasi_slug`),
  ADD KEY `idx_kk_status` (`organisasi_slug`,`status`);

--
-- Indeks untuk tabel `kemahasiswaan_organisasi`
--
ALTER TABLE `kemahasiswaan_organisasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_ko_jenis_status` (`jenis`,`status`),
  ADD KEY `idx_ko_urut` (`jenis`,`nomor_urut`);

--
-- Indeks untuk tabel `kemahasiswaan_pengurus`
--
ALTER TABLE `kemahasiswaan_pengurus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kp_org` (`organisasi_slug`),
  ADD KEY `idx_kp_status` (`organisasi_slug`,`status`);

--
-- Indeks untuk tabel `misi`
--
ALTER TABLE `misi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `prodi_capaian_pembelajaran`
--
ALTER TABLE `prodi_capaian_pembelajaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prodi_cpl` (`prodi_id`);

--
-- Indeks untuk tabel `prodi_fasilitas`
--
ALTER TABLE `prodi_fasilitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prodi_fasilitas` (`prodi_id`);

--
-- Indeks untuk tabel `prodi_kurikulum`
--
ALTER TABLE `prodi_kurikulum`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prodi_kurikulum` (`prodi_id`);

--
-- Indeks untuk tabel `prodi_misi`
--
ALTER TABLE `prodi_misi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prodi_misi` (`prodi_id`);

--
-- Indeks untuk tabel `program_studi`
--
ALTER TABLE `program_studi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kode_prodi` (`kode_prodi`);

--
-- Indeks untuk tabel `sertifikat_akreditasi`
--
ALTER TABLE `sertifikat_akreditasi`
  ADD PRIMARY KEY (`id_sertifikat`);

--
-- Indeks untuk tabel `slider_beranda`
--
ALTER TABLE `slider_beranda`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `survey`
--
ALTER TABLE `survey`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_survey_status` (`status`),
  ADD KEY `idx_survey_urut` (`nomor_urut`);

--
-- Indeks untuk tabel `survey_jawaban`
--
ALTER TABLE `survey_jawaban`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sj_responden` (`responden_id`),
  ADD KEY `idx_sj_pertanyaan` (`pertanyaan_id`);

--
-- Indeks untuk tabel `survey_pertanyaan`
--
ALTER TABLE `survey_pertanyaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sp_survey` (`survey_id`),
  ADD KEY `idx_sp_urut` (`survey_id`,`nomor_urut`);

--
-- Indeks untuk tabel `survey_pilihan`
--
ALTER TABLE `survey_pilihan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_spl_pertanyaan` (`pertanyaan_id`),
  ADD KEY `idx_spl_urut` (`pertanyaan_id`,`nomor_urut`);

--
-- Indeks untuk tabel `survey_responden`
--
ALTER TABLE `survey_responden`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sr_survey` (`survey_id`),
  ADD KEY `idx_sr_tanggal` (`survey_id`,`tanggal_isi`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `visi_misi`
--
ALTER TABLE `visi_misi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `akademik_dokumen`
--
ALTER TABLE `akademik_dokumen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `akademik_jadwal`
--
ALTER TABLE `akademik_jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `akademik_kalender`
--
ALTER TABLE `akademik_kalender`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `akademik_penilaian`
--
ALTER TABLE `akademik_penilaian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `akademik_registrasi`
--
ALTER TABLE `akademik_registrasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `akademik_silabus`
--
ALTER TABLE `akademik_silabus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `akademik_skala_nilai`
--
ALTER TABLE `akademik_skala_nilai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `dosen_bidang_ajar`
--
ALTER TABLE `dosen_bidang_ajar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `dosen_keilmuan`
--
ALTER TABLE `dosen_keilmuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `dosen_pendidikan`
--
ALTER TABLE `dosen_pendidikan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `kemahasiswaan`
--
ALTER TABLE `kemahasiswaan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kemahasiswaan_anggota`
--
ALTER TABLE `kemahasiswaan_anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kemahasiswaan_galeri`
--
ALTER TABLE `kemahasiswaan_galeri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kemahasiswaan_kegiatan`
--
ALTER TABLE `kemahasiswaan_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kemahasiswaan_organisasi`
--
ALTER TABLE `kemahasiswaan_organisasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `kemahasiswaan_pengurus`
--
ALTER TABLE `kemahasiswaan_pengurus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `misi`
--
ALTER TABLE `misi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `prodi_capaian_pembelajaran`
--
ALTER TABLE `prodi_capaian_pembelajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `prodi_fasilitas`
--
ALTER TABLE `prodi_fasilitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `prodi_kurikulum`
--
ALTER TABLE `prodi_kurikulum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `prodi_misi`
--
ALTER TABLE `prodi_misi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `program_studi`
--
ALTER TABLE `program_studi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `sertifikat_akreditasi`
--
ALTER TABLE `sertifikat_akreditasi`
  MODIFY `id_sertifikat` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `slider_beranda`
--
ALTER TABLE `slider_beranda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `survey`
--
ALTER TABLE `survey`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `survey_jawaban`
--
ALTER TABLE `survey_jawaban`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `survey_pertanyaan`
--
ALTER TABLE `survey_pertanyaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `survey_pilihan`
--
ALTER TABLE `survey_pilihan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `survey_responden`
--
ALTER TABLE `survey_responden`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `visi_misi`
--
ALTER TABLE `visi_misi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
