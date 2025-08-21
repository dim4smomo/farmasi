-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Agu 2025 pada 00.35
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `farmasi_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelompok`
--

CREATE TABLE `kelompok` (
  `id_kelompok` int(11) NOT NULL,
  `id_sumber` int(11) NOT NULL,
  `nama_kelompok` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelompok`
--

INSERT INTO `kelompok` (`id_kelompok`, `id_sumber`, `nama_kelompok`, `created_at`, `updated_at`) VALUES
(37, 70, 'Semua', '2025-08-22 05:10:39', '2025-08-22 05:10:39'),
(38, 71, '1', '2025-08-22 05:11:09', '2025-08-22 05:11:09');

--
-- Trigger `kelompok`
--
DELIMITER $$
CREATE TRIGGER `kelompok_after_delete` AFTER DELETE ON `kelompok` FOR EACH ROW BEGIN
    INSERT INTO log_kelompok (aksi, kelompok_id, id_sumber_lama, id_sumber_baru, nama_lama, nama_baru, user)
    VALUES ('DELETE', OLD.id_kelompok, OLD.id_sumber, NULL, OLD.nama_kelompok, NULL, USER());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `kelompok_after_insert` AFTER INSERT ON `kelompok` FOR EACH ROW BEGIN
    INSERT INTO log_kelompok (aksi, kelompok_id, id_sumber_lama, id_sumber_baru, nama_lama, nama_baru, user)
    VALUES ('INSERT', NEW.id_kelompok, NULL, NEW.id_sumber, NULL, NEW.nama_kelompok, USER());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `kelompok_after_update` AFTER UPDATE ON `kelompok` FOR EACH ROW BEGIN
    INSERT INTO log_kelompok (aksi, kelompok_id, id_sumber_lama, id_sumber_baru, nama_lama, nama_baru, user)
    VALUES ('UPDATE', NEW.id_kelompok, OLD.id_sumber, NEW.id_sumber, OLD.nama_kelompok, NEW.nama_kelompok, USER());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `kelompok_before_insert` BEFORE INSERT ON `kelompok` FOR EACH ROW BEGIN
    SET NEW.created_at = NOW();
    SET NEW.updated_at = NOW();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `kelompok_before_update` BEFORE UPDATE ON `kelompok` FOR EACH ROW BEGIN
    SET NEW.updated_at = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_bulanan`
--

CREATE TABLE `laporan_bulanan` (
  `id_laporan` int(11) NOT NULL,
  `periode` date NOT NULL,
  `kode_provinsi` varchar(50) DEFAULT NULL,
  `nama_obat_provinsi` varchar(255) DEFAULT NULL,
  `satuan_provinsi` varchar(100) DEFAULT NULL,
  `program_provinsi` varchar(100) DEFAULT NULL,
  `nama_obat_gudang` varchar(255) DEFAULT NULL,
  `no_batch` varchar(100) DEFAULT NULL,
  `kadaluarsa` date DEFAULT NULL,
  `stok_akhir` int(11) DEFAULT NULL,
  `rata_rata_keluar` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_path` varchar(255) DEFAULT NULL,
  `jenis_laporan` varchar(50) DEFAULT 'stok_bulanan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan_bulanan`
--

INSERT INTO `laporan_bulanan` (`id_laporan`, `periode`, `kode_provinsi`, `nama_obat_provinsi`, `satuan_provinsi`, `program_provinsi`, `nama_obat_gudang`, `no_batch`, `kadaluarsa`, `stok_akhir`, `rata_rata_keluar`, `created_at`, `file_path`, `jenis_laporan`) VALUES
(1, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-19 21:11:20', 'arsip/laporan_stok_2025-08.xlsx', 'stok_bulanan'),
(2, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-19 21:11:27', 'arsip/laporan_stok_2025-08.xlsx', 'stok_bulanan'),
(3, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-19 21:15:07', 'arsip/laporan_stok_2025-08.xlsx', 'stok_bulanan'),
(4, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-19 21:15:11', 'arsip/laporan_stok_2025-08.xlsx', 'stok_bulanan'),
(5, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-19 21:15:15', 'arsip/laporan_stok_2025-08.xlsx', 'stok_bulanan'),
(6, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-19 21:48:46', 'arsip/laporan_stok_2025-08.xlsx', 'stok_bulanan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_kelompok`
--

CREATE TABLE `log_kelompok` (
  `id_log` int(11) NOT NULL,
  `aksi` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `kelompok_id` int(11) DEFAULT NULL,
  `id_sumber_lama` int(11) DEFAULT NULL,
  `id_sumber_baru` int(11) DEFAULT NULL,
  `nama_lama` varchar(255) DEFAULT NULL,
  `nama_baru` varchar(255) DEFAULT NULL,
  `user` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_kelompok`
--

INSERT INTO `log_kelompok` (`id_log`, `aksi`, `kelompok_id`, `id_sumber_lama`, `id_sumber_baru`, `nama_lama`, `nama_baru`, `user`, `created_at`) VALUES
(1, 'INSERT', 20, NULL, 16, NULL, 'A', 'root@localhost', '2025-08-21 00:04:22'),
(2, 'INSERT', 20, NULL, 16, NULL, 'A', 'administrator', '2025-08-21 00:04:22'),
(3, 'UPDATE', 20, 16, 16, 'A', 'Aa', 'root@localhost', '2025-08-21 00:04:35'),
(4, 'UPDATE', 20, 16, 16, 'A', 'Aa', 'administrator', '2025-08-21 00:04:35'),
(5, 'INSERT', 21, NULL, 16, NULL, '1213123', 'root@localhost', '2025-08-21 00:17:26'),
(6, 'INSERT', 21, NULL, 16, NULL, '1213123', 'administrator', '2025-08-21 00:17:27'),
(7, 'INSERT', 22, NULL, 17, NULL, 'a', 'root@localhost', '2025-08-21 10:46:06'),
(8, 'INSERT', 22, NULL, 17, NULL, 'a', 'administrator', '2025-08-21 10:46:06'),
(9, 'INSERT', 23, NULL, 20, NULL, 'OBAT TB', 'root@localhost', '2025-08-21 11:00:17'),
(10, 'INSERT', 23, NULL, 20, NULL, 'OBAT TB', 'administrator', '2025-08-21 11:00:17'),
(11, 'UPDATE', 23, 20, 20, 'OBAT TB', 'OBAT TBs', 'root@localhost', '2025-08-21 12:08:37'),
(12, 'UPDATE', 23, 20, 20, 'OBAT TB', 'OBAT TBs', 'administrator', '2025-08-21 12:08:37'),
(13, 'DELETE', 22, 17, NULL, 'a', NULL, 'root@localhost', '2025-08-21 12:14:00'),
(14, 'DELETE', 22, 17, NULL, 'a', NULL, 'administrator', '2025-08-21 12:14:00'),
(15, 'UPDATE', 23, 20, 20, 'OBAT TBs', 'OBAT TBsz', 'root@localhost', '2025-08-21 12:18:43'),
(16, 'UPDATE', 23, 20, 20, 'OBAT TBs', 'OBAT TBsz', 'administrator', '2025-08-21 12:18:43'),
(17, 'UPDATE', 23, 20, 20, 'OBAT TBsz', 'OBAT TBszeee', 'root@localhost', '2025-08-21 12:18:54'),
(18, 'UPDATE', 23, 20, 20, 'OBAT TBsz', 'OBAT TBszeee', 'administrator', '2025-08-21 12:18:54'),
(19, 'DELETE', 23, 20, NULL, 'OBAT TBszeee', NULL, 'root@localhost', '2025-08-21 12:20:53'),
(20, 'DELETE', 23, 20, NULL, 'OBAT TBszeee', NULL, 'administrator', '2025-08-21 12:20:53'),
(21, 'UPDATE', 21, 16, 16, '1213123', '1213123-', 'root@localhost', '2025-08-21 12:21:41'),
(22, 'UPDATE', 21, 16, 16, '1213123', '1213123-', 'administrator', '2025-08-21 12:21:41'),
(23, 'UPDATE', 21, 16, 16, '1213123-', '1213123-0', 'root@localhost', '2025-08-21 12:22:28'),
(24, 'UPDATE', 21, 16, 16, '1213123-', '1213123-0', 'administrator', '2025-08-21 12:22:28'),
(25, 'DELETE', 21, 16, NULL, '1213123-0', NULL, 'root@localhost', '2025-08-21 12:22:32'),
(26, 'DELETE', 21, 16, NULL, '1213123-0', NULL, 'administrator', '2025-08-21 12:22:32'),
(27, 'INSERT', 24, NULL, 35, NULL, 'aq1', 'root@localhost', '2025-08-21 12:22:40'),
(28, 'INSERT', 24, NULL, 35, NULL, 'aq1', 'administrator', '2025-08-21 12:22:40'),
(29, 'DELETE', 24, 35, NULL, 'aq1', NULL, 'root@localhost', '2025-08-21 12:50:32'),
(30, 'DELETE', 24, 35, NULL, 'aq1', NULL, 'administrator', '2025-08-21 12:50:33'),
(31, 'DELETE', 17, 16, NULL, '1', NULL, 'root@localhost', '2025-08-21 13:06:06'),
(32, 'DELETE', 19, 16, NULL, '2', NULL, 'root@localhost', '2025-08-21 13:06:06'),
(33, 'DELETE', 20, 16, NULL, 'Aa', NULL, 'root@localhost', '2025-08-21 13:06:06'),
(34, 'DELETE', 16, 17, NULL, '1', NULL, 'root@localhost', '2025-08-21 13:07:29'),
(35, 'DELETE', 18, 17, NULL, '2', NULL, 'root@localhost', '2025-08-21 13:07:29'),
(36, 'INSERT', 25, NULL, 41, NULL, '1', 'root@localhost', '2025-08-21 13:16:08'),
(37, 'INSERT', 25, NULL, 41, NULL, '1', 'administrator', '2025-08-21 13:16:08'),
(38, 'DELETE', 25, 41, NULL, '1', NULL, 'root@localhost', '2025-08-21 13:16:15'),
(39, 'INSERT', 26, NULL, 45, NULL, 'rrr', 'root@localhost', '2025-08-21 13:20:44'),
(40, 'INSERT', 26, NULL, 45, NULL, 'rrr', 'administrator', '2025-08-21 13:20:44'),
(41, 'DELETE', 26, 45, NULL, 'rrr', NULL, 'root@localhost', '2025-08-21 13:20:50'),
(42, 'INSERT', 27, NULL, 46, NULL, 'OBAT TB', 'root@localhost', '2025-08-21 13:22:14'),
(43, 'INSERT', 27, NULL, 46, NULL, 'OBAT TB', 'administrator', '2025-08-21 13:22:14'),
(44, 'INSERT', 28, NULL, 47, NULL, 'afsf', 'root@localhost', '2025-08-21 13:22:24'),
(45, 'INSERT', 28, NULL, 47, NULL, 'afsf', 'administrator', '2025-08-21 13:22:24'),
(46, 'DELETE', 27, 46, NULL, 'OBAT TB', NULL, 'root@localhost', '2025-08-21 13:26:50'),
(47, 'DELETE', 27, 46, NULL, 'OBAT TB', NULL, 'administrator', '2025-08-21 13:26:50'),
(48, 'DELETE', 28, 47, NULL, 'afsf', NULL, 'root@localhost', '2025-08-21 13:27:46'),
(49, 'DELETE', 28, 47, NULL, 'afsf', NULL, 'administrator', '2025-08-21 13:27:46'),
(50, 'INSERT', 29, NULL, 47, NULL, '1', 'root@localhost', '2025-08-21 13:28:37'),
(51, 'INSERT', 29, NULL, 47, NULL, '1', 'administrator', '2025-08-21 13:28:37'),
(52, 'INSERT', 30, NULL, 48, NULL, '2', 'root@localhost', '2025-08-21 13:28:42'),
(53, 'INSERT', 30, NULL, 48, NULL, '2', 'administrator', '2025-08-21 13:28:42'),
(54, 'INSERT', 31, NULL, 46, NULL, '3', 'root@localhost', '2025-08-21 13:28:46'),
(55, 'INSERT', 31, NULL, 46, NULL, '3', 'administrator', '2025-08-21 13:28:46'),
(56, 'DELETE', 29, 47, NULL, '1', NULL, 'root@localhost', '2025-08-21 13:29:15'),
(57, 'DELETE', 29, 47, NULL, '1', NULL, 'administrator', '2025-08-21 13:29:15'),
(58, 'INSERT', 32, NULL, 49, NULL, '1', 'root@localhost', '2025-08-21 13:32:59'),
(59, 'INSERT', 32, NULL, 49, NULL, '1', 'administrator', '2025-08-21 13:32:59'),
(60, 'DELETE', 32, 49, NULL, '1', NULL, 'root@localhost', '2025-08-21 13:33:13'),
(61, 'DELETE', 32, 49, NULL, '1', NULL, 'administrator', '2025-08-21 13:33:13'),
(62, 'INSERT', 33, NULL, 50, NULL, 'a', 'root@localhost', '2025-08-21 14:35:31'),
(63, 'INSERT', 33, NULL, 50, NULL, 'a', 'administrator', '2025-08-21 14:35:31'),
(64, 'DELETE', 33, 50, NULL, 'a', NULL, 'root@localhost', '2025-08-21 14:35:43'),
(65, 'DELETE', 33, 50, NULL, 'a', NULL, 'administrator', '2025-08-21 14:35:43'),
(66, 'INSERT', 34, NULL, 46, NULL, 'qqq', 'root@localhost', '2025-08-21 15:29:30'),
(67, 'INSERT', 34, NULL, 46, NULL, 'qqq', 'administrator', '2025-08-21 15:29:30'),
(68, 'DELETE', 34, 46, NULL, 'qqq', NULL, 'root@localhost', '2025-08-21 15:43:47'),
(69, 'DELETE', 34, 46, NULL, 'qqq', NULL, 'administrator', '2025-08-21 15:43:47'),
(70, 'DELETE', 30, 48, NULL, '2', NULL, 'root@localhost', '2025-08-21 15:44:46'),
(71, 'DELETE', 30, 48, NULL, '2', NULL, 'administrator', '2025-08-21 15:44:46'),
(72, 'INSERT', 35, NULL, 46, NULL, 'g', 'root@localhost', '2025-08-21 16:52:05'),
(73, 'INSERT', 35, NULL, 46, NULL, 'g', 'administrator', '2025-08-21 16:52:05'),
(74, 'INSERT', 36, NULL, 46, NULL, 's', 'root@localhost', '2025-08-21 17:01:36'),
(75, 'INSERT', 36, NULL, 46, NULL, 's', 'administrator', '2025-08-21 17:01:36'),
(76, 'DELETE', 31, 46, NULL, '3', NULL, 'root@localhost', '2025-08-21 17:05:31'),
(77, 'DELETE', 31, 46, NULL, '3', NULL, 'administrator', '2025-08-21 17:05:31'),
(78, 'UPDATE', 36, 46, 46, 's', 'sss', 'root@localhost', '2025-08-21 17:09:52'),
(79, 'UPDATE', 36, 46, 46, 's', 'sss', 'administrator', '2025-08-21 17:09:52'),
(80, 'UPDATE', 35, 46, 46, 'g', 'gssssssssss', 'root@localhost', '2025-08-21 17:09:56'),
(81, 'UPDATE', 35, 46, 46, 'g', 'gssssssssss', 'administrator', '2025-08-21 17:09:56'),
(82, 'DELETE', 35, 46, NULL, 'gssssssssss', NULL, 'root@localhost', '2025-08-21 17:09:59'),
(83, 'DELETE', 35, 46, NULL, 'gssssssssss', NULL, 'administrator', '2025-08-21 17:09:59'),
(84, 'DELETE', 36, 46, NULL, 'sss', NULL, 'root@localhost', '2025-08-21 21:59:42'),
(85, 'DELETE', 36, 46, NULL, 'sss', NULL, 'administrator', '2025-08-21 21:59:42'),
(86, 'INSERT', 37, NULL, 70, NULL, 'Semua', 'root@localhost', '2025-08-21 22:10:39'),
(87, 'INSERT', 38, NULL, 71, NULL, '1', 'root@localhost', '2025-08-21 22:11:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_sumber`
--

CREATE TABLE `log_sumber` (
  `id_log` int(11) NOT NULL,
  `aksi` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `sumber_id` int(11) DEFAULT NULL,
  `nama_lama` varchar(255) DEFAULT NULL,
  `nama_baru` varchar(255) DEFAULT NULL,
  `user` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_sumber`
--

INSERT INTO `log_sumber` (`id_log`, `aksi`, `sumber_id`, `nama_lama`, `nama_baru`, `user`, `created_at`) VALUES
(1, 'INSERT', 20, NULL, 'COVID-19', 'root@localhost', '2025-08-20 22:47:12'),
(2, 'UPDATE', 20, 'COVID-19', 'COVID-19', 'root@localhost', '2025-08-20 22:48:55'),
(3, 'INSERT', 21, NULL, 'ZA', 'root@localhost', '2025-08-20 23:49:42'),
(4, 'UPDATE', 21, 'ZA', 'ZAa', 'root@localhost', '2025-08-20 23:49:46'),
(5, 'DELETE', 21, 'ZAa', NULL, 'root@localhost', '2025-08-20 23:49:54'),
(6, 'INSERT', 22, NULL, 's', 'root@localhost', '2025-08-21 11:31:45'),
(7, 'INSERT', 31, NULL, 'ss', 'root@localhost', '2025-08-21 11:41:16'),
(8, 'INSERT', 32, NULL, 'sss', 'root@localhost', '2025-08-21 11:43:30'),
(9, 'INSERT', 33, NULL, 'd', 'root@localhost', '2025-08-21 11:46:26'),
(10, 'INSERT', 34, NULL, 'dd', 'root@localhost', '2025-08-21 11:46:59'),
(11, 'INSERT', 35, NULL, 'a', 'root@localhost', '2025-08-21 11:59:31'),
(12, 'INSERT', 36, NULL, 'aaaaaaaa', 'root@localhost', '2025-08-21 12:08:57'),
(13, 'UPDATE', 36, 'aaaaaaaa', 'aaaaaaaaa', 'root@localhost', '2025-08-21 12:17:26'),
(14, 'UPDATE', 36, 'aaaaaaaaa', 'aaaaaaaaaz', 'root@localhost', '2025-08-21 12:17:29'),
(15, 'INSERT', 37, NULL, 'ccc', 'root@localhost', '2025-08-21 12:43:22'),
(16, 'UPDATE', 37, 'ccc', 'cccx', 'root@localhost', '2025-08-21 12:43:27'),
(17, 'UPDATE', 37, 'cccx', 'cccxxxx', 'root@localhost', '2025-08-21 12:43:58'),
(18, 'UPDATE', 36, 'aaaaaaaaaz', 'aaaaaaaaaz11', 'root@localhost', '2025-08-21 12:44:09'),
(19, 'UPDATE', 37, 'cccxxxx', 'cccxxxxd', 'root@localhost', '2025-08-21 12:53:01'),
(20, 'INSERT', 38, NULL, 'ttttttttt', 'root@localhost', '2025-08-21 12:54:38'),
(21, 'UPDATE', 38, 'ttttttttt', 'tttttttttw', 'root@localhost', '2025-08-21 12:54:42'),
(22, 'INSERT', 39, NULL, 'Obat', 'root@localhost', '2025-08-21 12:55:41'),
(23, 'DELETE', 38, 'tttttttttw', NULL, 'root@localhost', '2025-08-21 12:56:40'),
(24, 'DELETE', 37, 'cccxxxxd', NULL, 'root@localhost', '2025-08-21 12:56:42'),
(25, 'UPDATE', 16, 'PKD', 'PKDc', 'root@localhost', '2025-08-21 12:58:01'),
(26, 'DELETE', 16, 'PKDc', NULL, 'root@localhost', '2025-08-21 13:06:06'),
(27, 'DELETE', 22, 's', NULL, 'root@localhost', '2025-08-21 13:06:09'),
(28, 'DELETE', 35, 'a', NULL, 'root@localhost', '2025-08-21 13:06:36'),
(29, 'INSERT', 40, NULL, 'xzzzzzzzzzzzzzzz', 'root@localhost', '2025-08-21 13:06:42'),
(30, 'DELETE', 40, 'xzzzzzzzzzzzzzzz', NULL, 'root@localhost', '2025-08-21 13:06:45'),
(31, 'UPDATE', 39, 'Obat', 'Obatc', 'root@localhost', '2025-08-21 13:06:49'),
(32, 'DELETE', 17, 'APBN Program', NULL, 'root@localhost', '2025-08-21 13:07:29'),
(33, 'DELETE', 20, 'COVID-19', NULL, 'root@localhost', '2025-08-21 13:07:30'),
(34, 'DELETE', 31, 'ss', NULL, 'root@localhost', '2025-08-21 13:07:32'),
(35, 'DELETE', 32, 'sss', NULL, 'root@localhost', '2025-08-21 13:07:34'),
(36, 'DELETE', 33, 'd', NULL, 'root@localhost', '2025-08-21 13:07:35'),
(37, 'DELETE', 34, 'dd', NULL, 'root@localhost', '2025-08-21 13:07:36'),
(38, 'DELETE', 36, 'aaaaaaaaaz11', NULL, 'root@localhost', '2025-08-21 13:07:38'),
(39, 'DELETE', 39, 'Obatc', NULL, 'root@localhost', '2025-08-21 13:07:40'),
(40, 'INSERT', 41, NULL, 'de', 'root@localhost', '2025-08-21 13:11:19'),
(41, 'INSERT', 42, NULL, 'qr', 'root@localhost', '2025-08-21 13:11:43'),
(42, 'INSERT', 43, NULL, 'sssss', 'root@localhost', '2025-08-21 13:11:49'),
(43, 'DELETE', 43, 'sssss', NULL, 'root@localhost', '2025-08-21 13:15:28'),
(44, 'DELETE', 42, 'qr', NULL, 'root@localhost', '2025-08-21 13:15:35'),
(45, 'INSERT', 44, NULL, 'zjjjjjjjjjjj', 'root@localhost', '2025-08-21 13:15:44'),
(46, 'UPDATE', 44, 'zjjjjjjjjjjj', 'zjjjjjjjjjjjf', 'root@localhost', '2025-08-21 13:15:47'),
(47, 'DELETE', 44, 'zjjjjjjjjjjjf', NULL, 'root@localhost', '2025-08-21 13:15:51'),
(48, 'DELETE', 41, 'de', NULL, 'root@localhost', '2025-08-21 13:16:15'),
(49, 'INSERT', 45, NULL, '1', 'root@localhost', '2025-08-21 13:18:04'),
(50, 'DELETE', 45, '1', NULL, 'root@localhost', '2025-08-21 13:20:50'),
(51, 'INSERT', 46, NULL, 'ddd', 'root@localhost', '2025-08-21 13:22:04'),
(52, 'INSERT', 47, NULL, 'fffff', 'root@localhost', '2025-08-21 13:22:07'),
(53, 'UPDATE', 46, 'ddd', 'ddds', 'root@localhost', '2025-08-21 13:26:40'),
(54, 'UPDATE', 46, 'ddds', 'ddds', 'root@localhost', '2025-08-21 13:27:31'),
(55, 'UPDATE', 46, 'ddds', 'dddszzzzzzz', 'root@localhost', '2025-08-21 13:28:18'),
(56, 'INSERT', 48, NULL, 'asdsadas', 'root@localhost', '2025-08-21 13:28:28'),
(57, 'DELETE', 47, 'fffff', NULL, 'root@localhost', '2025-08-21 13:32:40'),
(58, 'INSERT', 49, NULL, 'wahhhhhhhhh', 'root@localhost', '2025-08-21 13:32:52'),
(59, 'DELETE', 49, 'wahhhhhhhhh', NULL, 'root@localhost', '2025-08-21 13:33:16'),
(60, 'INSERT', 50, NULL, '123', 'root@localhost', '2025-08-21 14:33:41'),
(61, 'DELETE', 50, '123', NULL, 'root@localhost', '2025-08-21 14:35:46'),
(62, 'INSERT', 51, NULL, 'ssssssssssssssssssssssss', 'root@localhost', '2025-08-21 14:39:28'),
(63, 'DELETE', 51, 'ssssssssssssssssssssssss', NULL, 'root@localhost', '2025-08-21 14:39:34'),
(64, 'INSERT', 52, NULL, 'qqqq', 'root@localhost', '2025-08-21 14:40:03'),
(65, 'INSERT', 53, NULL, 's', 'root@localhost', '2025-08-21 14:40:51'),
(66, 'INSERT', 54, NULL, 'sssssssssss', 'root@localhost', '2025-08-21 14:40:57'),
(67, 'UPDATE', 46, 'dddszzzzzzz', 'dddszzzzzzzs', 'root@localhost', '2025-08-21 14:41:14'),
(68, 'UPDATE', 54, 'sssssssssss', 'ssssssssssssssssssa', 'root@localhost', '2025-08-21 14:41:18'),
(69, 'UPDATE', 46, 'dddszzzzzzzs', 'dddszzzzzzzss', 'root@localhost', '2025-08-21 14:41:39'),
(70, 'UPDATE', 53, 's', 's1111111111', 'root@localhost', '2025-08-21 14:41:44'),
(71, 'DELETE', 54, 'ssssssssssssssssssa', NULL, 'root@localhost', '2025-08-21 14:42:01'),
(72, 'UPDATE', 48, 'asdsadas', 'asdsadasc', 'root@localhost', '2025-08-21 14:43:03'),
(73, 'UPDATE', 46, 'dddszzzzzzzss', 'dddszzzzzzzssvvv', 'root@localhost', '2025-08-21 14:43:07'),
(74, 'DELETE', 53, 's1111111111', NULL, 'root@localhost', '2025-08-21 14:43:08'),
(75, 'DELETE', 52, 'qqqq', NULL, 'root@localhost', '2025-08-21 14:43:09'),
(76, 'INSERT', 55, NULL, 'aww', 'root@localhost', '2025-08-21 15:28:49'),
(77, 'DELETE', 55, 'aww', NULL, 'root@localhost', '2025-08-21 15:28:51'),
(78, 'INSERT', 56, NULL, 'wdw', 'root@localhost', '2025-08-21 15:54:07'),
(79, 'UPDATE', 56, 'wdw', 'wdwfef', 'root@localhost', '2025-08-21 15:54:12'),
(80, 'DELETE', 56, 'wdwfef', NULL, 'root@localhost', '2025-08-21 15:57:10'),
(81, 'INSERT', 57, NULL, 'hftshjsfg', 'root@localhost', '2025-08-21 15:57:15'),
(82, 'INSERT', 58, NULL, 'dfhgfdh', 'root@localhost', '2025-08-21 15:57:20'),
(83, 'DELETE', 58, 'dfhgfdh', NULL, 'root@localhost', '2025-08-21 15:57:23'),
(84, 'DELETE', 48, 'asdsadasc', NULL, 'root@localhost', '2025-08-21 15:57:24'),
(85, 'DELETE', 57, 'hftshjsfg', NULL, 'root@localhost', '2025-08-21 16:19:22'),
(86, 'INSERT', 59, NULL, '123', 'root@localhost', '2025-08-21 16:20:24'),
(87, 'INSERT', 62, NULL, 'sss', 'root@localhost', '2025-08-21 16:22:14'),
(88, 'INSERT', 64, NULL, 'ssss', 'root@localhost', '2025-08-21 16:22:26'),
(89, 'DELETE', 64, 'ssss', NULL, 'root@localhost', '2025-08-21 16:23:06'),
(90, 'DELETE', 59, '123', NULL, 'root@localhost', '2025-08-21 16:23:11'),
(91, 'INSERT', 65, NULL, '1234', 'root@localhost', '2025-08-21 16:23:18'),
(92, 'DELETE', 62, 'sss', NULL, 'root@localhost', '2025-08-21 16:27:54'),
(93, 'DELETE', 65, '1234', NULL, 'root@localhost', '2025-08-21 16:35:38'),
(94, 'DELETE', 65, '1234', NULL, 'administrator', '2025-08-21 16:35:38'),
(95, 'INSERT', 66, NULL, 'sss', 'root@localhost', '2025-08-21 16:42:12'),
(96, 'DELETE', 66, 'sss', NULL, 'root@localhost', '2025-08-21 16:48:31'),
(97, 'INSERT', 67, NULL, 'ssssssssssssssss', 'root@localhost', '2025-08-21 16:50:34'),
(98, 'DELETE', 67, 'ssssssssssssssss', NULL, 'root@localhost', '2025-08-21 16:50:37'),
(99, 'INSERT', 68, NULL, 'sss', 'root@localhost', '2025-08-21 16:52:29'),
(100, 'DELETE', 68, 'sss', NULL, 'root@localhost', '2025-08-21 16:52:34'),
(101, 'INSERT', 69, NULL, 'sss', 'root@localhost', '2025-08-21 16:52:37'),
(102, 'DELETE', 46, 'dddszzzzzzzssvvv', NULL, 'root@localhost', '2025-08-21 21:59:46'),
(103, 'DELETE', 69, 'sss', NULL, 'root@localhost', '2025-08-21 21:59:48'),
(104, 'INSERT', 70, NULL, 'PKD', 'root@localhost', '2025-08-21 22:10:18'),
(105, 'INSERT', 71, NULL, 'APBN Program', 'root@localhost', '2025-08-21 22:10:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mapping_obat`
--

CREATE TABLE `mapping_obat` (
  `id_mapping` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL,
  `kode_provinsi` varchar(50) DEFAULT NULL,
  `nama_obat_provinsi` varchar(150) DEFAULT NULL,
  `satuan_provinsi` varchar(50) DEFAULT NULL,
  `program_provinsi` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `obat`
--

CREATE TABLE `obat` (
  `id_obat` int(11) NOT NULL,
  `kode_obat` varchar(50) NOT NULL,
  `nama_obat` varchar(150) NOT NULL,
  `bentuk` varchar(50) DEFAULT NULL,
  `dosis` varchar(50) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `pabrikan` varchar(100) DEFAULT NULL,
  `stok_minimal` int(11) DEFAULT 0,
  `id_sumber` int(11) DEFAULT NULL,
  `id_kelompok` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `obat_keluar`
--

CREATE TABLE `obat_keluar` (
  `id_keluar` int(11) NOT NULL,
  `id_obat` int(11) DEFAULT NULL,
  `id_obat_masuk` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `penerima` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `obat_masuk`
--

CREATE TABLE `obat_masuk` (
  `id_masuk` int(11) NOT NULL,
  `id_obat` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `no_batch` varchar(100) DEFAULT NULL,
  `kadaluarsa` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sumber`
--

CREATE TABLE `sumber` (
  `id_sumber` int(11) NOT NULL,
  `nama_sumber` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sumber`
--

INSERT INTO `sumber` (`id_sumber`, `nama_sumber`, `created_at`, `updated_at`) VALUES
(70, 'PKD', '2025-08-22 05:10:18', '2025-08-22 05:10:18'),
(71, 'APBN Program', '2025-08-22 05:10:56', '2025-08-22 05:10:56');

--
-- Trigger `sumber`
--
DELIMITER $$
CREATE TRIGGER `sumber_after_delete` AFTER DELETE ON `sumber` FOR EACH ROW BEGIN
    INSERT INTO log_sumber (aksi, sumber_id, nama_lama, nama_baru, user)
    VALUES ('DELETE', OLD.id_sumber, OLD.nama_sumber, NULL, USER());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sumber_after_insert` AFTER INSERT ON `sumber` FOR EACH ROW BEGIN
    INSERT INTO log_sumber (aksi, sumber_id, nama_lama, nama_baru, user)
    VALUES ('INSERT', NEW.id_sumber, NULL, NEW.nama_sumber, USER());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sumber_after_update` AFTER UPDATE ON `sumber` FOR EACH ROW BEGIN
    INSERT INTO log_sumber (aksi, sumber_id, nama_lama, nama_baru, user)
    VALUES ('UPDATE', NEW.id_sumber, OLD.nama_sumber, NEW.nama_sumber, USER());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sumber_before_insert` BEFORE INSERT ON `sumber` FOR EACH ROW BEGIN
    SET NEW.created_at = NOW();
    SET NEW.updated_at = NOW();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sumber_before_update` BEFORE UPDATE ON `sumber` FOR EACH ROW BEGIN
    SET NEW.updated_at = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `kontak` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama`) VALUES
(1, 'admin', 'admin123', 'Admin Farmasi'),
(3, 'administrator', '$2y$10$4y0Kb0QDRPiCWJ0cTA7eouwD21sdbkPDbpKbQfnRvOJ28Z/Q6lZ8.', 'Admin Farmasi');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kelompok`
--
ALTER TABLE `kelompok`
  ADD PRIMARY KEY (`id_kelompok`),
  ADD UNIQUE KEY `uk_kelompok_per_sumber` (`id_sumber`,`nama_kelompok`);

--
-- Indeks untuk tabel `laporan_bulanan`
--
ALTER TABLE `laporan_bulanan`
  ADD PRIMARY KEY (`id_laporan`);

--
-- Indeks untuk tabel `log_kelompok`
--
ALTER TABLE `log_kelompok`
  ADD PRIMARY KEY (`id_log`);

--
-- Indeks untuk tabel `log_sumber`
--
ALTER TABLE `log_sumber`
  ADD PRIMARY KEY (`id_log`);

--
-- Indeks untuk tabel `mapping_obat`
--
ALTER TABLE `mapping_obat`
  ADD PRIMARY KEY (`id_mapping`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indeks untuk tabel `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id_obat`),
  ADD UNIQUE KEY `kode_obat` (`kode_obat`),
  ADD KEY `fk_obat_sumber` (`id_sumber`),
  ADD KEY `fk_obat_kelompok` (`id_kelompok`);

--
-- Indeks untuk tabel `obat_keluar`
--
ALTER TABLE `obat_keluar`
  ADD PRIMARY KEY (`id_keluar`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indeks untuk tabel `obat_masuk`
--
ALTER TABLE `obat_masuk`
  ADD PRIMARY KEY (`id_masuk`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indeks untuk tabel `sumber`
--
ALTER TABLE `sumber`
  ADD PRIMARY KEY (`id_sumber`),
  ADD UNIQUE KEY `uk_nama_sumber` (`nama_sumber`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kelompok`
--
ALTER TABLE `kelompok`
  MODIFY `id_kelompok` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `laporan_bulanan`
--
ALTER TABLE `laporan_bulanan`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `log_kelompok`
--
ALTER TABLE `log_kelompok`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT untuk tabel `log_sumber`
--
ALTER TABLE `log_sumber`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT untuk tabel `mapping_obat`
--
ALTER TABLE `mapping_obat`
  MODIFY `id_mapping` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `obat`
--
ALTER TABLE `obat`
  MODIFY `id_obat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `obat_keluar`
--
ALTER TABLE `obat_keluar`
  MODIFY `id_keluar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `obat_masuk`
--
ALTER TABLE `obat_masuk`
  MODIFY `id_masuk` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sumber`
--
ALTER TABLE `sumber`
  MODIFY `id_sumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kelompok`
--
ALTER TABLE `kelompok`
  ADD CONSTRAINT `fk_kelompok_sumber` FOREIGN KEY (`id_sumber`) REFERENCES `sumber` (`id_sumber`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mapping_obat`
--
ALTER TABLE `mapping_obat`
  ADD CONSTRAINT `mapping_obat_ibfk_1` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id_obat`);

--
-- Ketidakleluasaan untuk tabel `obat`
--
ALTER TABLE `obat`
  ADD CONSTRAINT `fk_obat_kelompok` FOREIGN KEY (`id_kelompok`) REFERENCES `kelompok` (`id_kelompok`),
  ADD CONSTRAINT `fk_obat_sumber` FOREIGN KEY (`id_sumber`) REFERENCES `sumber` (`id_sumber`);

--
-- Ketidakleluasaan untuk tabel `obat_keluar`
--
ALTER TABLE `obat_keluar`
  ADD CONSTRAINT `obat_keluar_ibfk_1` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id_obat`);

--
-- Ketidakleluasaan untuk tabel `obat_masuk`
--
ALTER TABLE `obat_masuk`
  ADD CONSTRAINT `obat_masuk_ibfk_1` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id_obat`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
