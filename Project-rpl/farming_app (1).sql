-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Jun 2025 pada 05.06
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
-- Database: `farming_app`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `appsetting`
--

CREATE TABLE `appsetting` (
  `id_setting` int(11) NOT NULL DEFAULT 1,
  `app_name` varchar(100) NOT NULL,
  `timezone` enum('WIB','WITA','WIT') NOT NULL DEFAULT 'WIB',
  `language` enum('Indonesia','English') NOT NULL DEFAULT 'Indonesia',
  `date_format` enum('DD/MM/YYYY','MM/DD/YYYY','YYYY-MM-DD') NOT NULL DEFAULT 'DD/MM/YYYY',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `employee`
--

CREATE TABLE `employee` (
  `id_employee` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `position` enum('Petani','Teknisi','Admin','Manager') NOT NULL,
  `status` enum('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
  `joined_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `harvestprediction`
--

CREATE TABLE `harvestprediction` (
  `id_prediction` int(11) NOT NULL,
  `lahan_id` int(11) NOT NULL,
  `tanaman` varchar(50) NOT NULL,
  `tanggal_tanam` date NOT NULL,
  `prediksi_panen` date NOT NULL,
  `estimasi_hasil` varchar(50) NOT NULL,
  `faktor_kondisi_tanaman` int(11) DEFAULT NULL,
  `faktor_cuaca` int(11) DEFAULT NULL,
  `faktor_air` int(11) DEFAULT NULL,
  `faktor_tanah` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `land`
--

CREATE TABLE `land` (
  `id_lahan` int(11) NOT NULL,
  `nama_lahan` varchar(100) NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `luas` decimal(8,2) NOT NULL,
  `jenis_tanaman` varchar(50) NOT NULL,
  `jenis_tanah` varchar(50) NOT NULL,
  `status` enum('Aktif','Maintenance','Nonaktif') NOT NULL DEFAULT 'Aktif',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `land`
--

INSERT INTO `land` (`id_lahan`, `nama_lahan`, `lokasi`, `luas`, `jenis_tanaman`, `jenis_tanah`, `status`, `created_at`, `updated_at`, `user_id`) VALUES
(1, '1', '1, 1', 1.00, 'Padi', 'Latosol', 'Aktif', '2025-06-08 03:46:33', '2025-06-08 08:46:33', 3),
(2, '1', '1, 1', 1.00, 'Padi', 'Latosol', 'Aktif', '2025-06-08 03:47:10', '2025-06-08 08:47:10', 3),
(3, '1', '1, 1', 1.00, 'Padi', 'Latosol', 'Aktif', '2025-06-08 03:47:24', '2025-06-08 08:47:24', 3),
(4, '1', '1, 1', 1.00, 'Padi', 'Latosol', 'Aktif', '2025-06-08 03:48:48', '2025-06-08 08:48:48', 3),
(5, '1', '1, 1', 1.00, 'Padi', 'Latosol', 'Aktif', '2025-06-08 03:49:04', '2025-06-08 08:49:04', 3),
(6, '21', ', ', 21.00, 'Jagung', 'Pilih Jenis Tanah', 'Aktif', '2025-06-08 07:45:34', '2025-06-08 12:45:34', 4),
(9, 'ABCDEFG', '-7.242111, 109.124421', 1.00, 'Padi', 'Lainnya', 'Aktif', '2025-06-14 04:05:41', '2025-06-14 09:05:41', 3),
(10, '121', ', ', 1.00, 'Pilih Jenis Tanaman', 'Pilih Jenis Tanah', 'Aktif', '2025-06-14 09:18:24', '2025-06-14 14:18:24', 2),
(11, '', ', ', 0.00, 'Pilih Jenis Tanaman', 'Pilih Jenis Tanah', 'Aktif', '2025-06-14 16:01:52', '2025-06-14 21:01:52', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `payroll`
--

CREATE TABLE `payroll` (
  `id_payroll` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `payroll_month` int(11) NOT NULL CHECK (`payroll_month` between 1 and 12),
  `payroll_year` year(4) NOT NULL,
  `gaji_pokok` bigint(20) NOT NULL,
  `overtime_pay` bigint(20) NOT NULL,
  `total_pay` bigint(20) NOT NULL,
  `status` enum('Dibayar','Proses','Belum') NOT NULL DEFAULT 'Belum',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `paid_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pestdetection`
--

CREATE TABLE `pestdetection` (
  `id_detection` int(11) NOT NULL,
  `lahan_id` int(11) NOT NULL,
  `detected_type` varchar(100) NOT NULL,
  `confidence` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `detected_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `plant_infos`
--

CREATE TABLE `plant_infos` (
  `id_tanaman` int(11) NOT NULL,
  `land_id` int(11) NOT NULL,
  `land_id_id` int(11) NOT NULL,
  `nama_tanaman` varchar(100) NOT NULL,
  `tanggal_tanam` date NOT NULL,
  `target_panen` int(11) NOT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `plant_infos`
--

INSERT INTO `plant_infos` (`id_tanaman`, `land_id`, `land_id_id`, `nama_tanaman`, `tanggal_tanam`, `target_panen`, `catatan`, `created_at`, `user_id`) VALUES
(6, 10, 0, '1', '2025-06-03', 1, '1', '2025-06-14 21:57:56', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `productivity`
--

CREATE TABLE `productivity` (
  `id_productivity` int(11) NOT NULL,
  `bulan` enum('Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des') NOT NULL,
  `tahun` year(4) NOT NULL,
  `target_ton` decimal(8,2) NOT NULL,
  `hasil_ton` decimal(8,2) NOT NULL,
  `pencapaian` decimal(5,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `recommendationfertilization`
--

CREATE TABLE `recommendationfertilization` (
  `id_rekomendasi_pupuk` int(11) NOT NULL,
  `lahan_id` int(11) NOT NULL,
  `ph_value` decimal(3,2) NOT NULL,
  `nutrisi` text NOT NULL,
  `rekomendasi_text` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `recommendationirrigation`
--

CREATE TABLE `recommendationirrigation` (
  `id_rekomendasi_irigasi` int(11) NOT NULL,
  `lahan_id` int(11) NOT NULL,
  `humidity_value` int(11) NOT NULL,
  `rekomendasi_text` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `report`
--

CREATE TABLE `report` (
  `id_report` int(11) NOT NULL,
  `report_type` enum('Harian','Mingguan','Bulanan') NOT NULL,
  `report_date` date NOT NULL,
  `title` varchar(200) NOT NULL,
  `summary` text DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sensordevice`
--

CREATE TABLE `sensordevice` (
  `id_sensor` int(11) NOT NULL,
  `sensor_name` varchar(100) NOT NULL,
  `lahan_id` int(11) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL DEFAULT 'Aktif',
  `battery_level` int(11) DEFAULT 100,
  `last_communication` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sensordevice`
--

INSERT INTO `sensordevice` (`id_sensor`, `sensor_name`, `lahan_id`, `ip_address`, `status`, `battery_level`, `last_communication`) VALUES
(111, '111', 1, '11111111', 'Aktif', 100, NULL),
(118, '', 1, NULL, 'Aktif', 100, NULL),
(119, '', 1, NULL, 'Aktif', 100, NULL),
(121, '', 9, NULL, 'Aktif', 100, NULL),
(123, '', 1, NULL, 'Aktif', 100, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sensorreading`
--

CREATE TABLE `sensorreading` (
  `id_reading` int(11) NOT NULL,
  `sensor_id` int(11) NOT NULL,
  `lahan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `temperature` decimal(5,2) DEFAULT NULL,
  `humidity` int(11) DEFAULT NULL,
  `ph` decimal(3,2) DEFAULT NULL,
  `light` int(11) DEFAULT NULL,
  `soil_moisture` int(11) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sensorreading`
--

INSERT INTO `sensorreading` (`id_reading`, `sensor_id`, `lahan_id`, `user_id`, `timestamp`, `temperature`, `humidity`, `ph`, `light`, `soil_moisture`, `photo_path`) VALUES
(79, 119, 3, 3, '2025-06-14 06:38:57', 29.60, 75, 7.00, 742, 68, NULL),
(80, 118, 4, 3, '2025-06-14 06:38:57', 22.00, 69, 5.10, 122, 56, NULL),
(81, 118, 5, 3, '2025-06-14 06:38:57', 29.30, 62, 7.70, 501, 57, NULL),
(84, 111, 9, 3, '2025-06-14 06:38:57', 30.70, 72, 7.40, 909, 40, NULL),
(85, 118, 6, 4, '2025-06-14 06:38:57', 34.50, 46, 5.90, 823, 62, NULL),
(86, 111, 1, 3, '2025-06-14 06:38:59', 27.10, 47, 5.70, 538, 56, NULL),
(87, 119, 2, 3, '2025-06-14 06:38:59', 29.00, 63, 7.80, 183, 67, NULL),
(88, 119, 3, 3, '2025-06-14 06:38:59', 29.80, 41, 7.30, 299, 49, NULL),
(89, 119, 4, 3, '2025-06-14 06:38:59', 28.00, 78, 7.50, 322, 70, NULL),
(90, 118, 5, 3, '2025-06-14 06:38:59', 22.30, 62, 6.20, 108, 42, NULL),
(93, 111, 9, 3, '2025-06-14 06:38:59', 27.60, 73, 5.70, 558, 67, NULL),
(94, 111, 6, 4, '2025-06-14 06:38:59', 29.10, 75, 7.60, 422, 62, NULL),
(95, 119, 1, 3, '2025-06-14 06:39:02', 28.90, 63, 5.50, 105, 35, NULL),
(96, 118, 2, 3, '2025-06-14 06:39:02', 25.10, 65, 6.30, 785, 43, NULL),
(97, 111, 3, 3, '2025-06-14 06:39:02', 33.90, 64, 7.40, 288, 70, NULL),
(98, 111, 4, 3, '2025-06-14 06:39:02', 26.50, 70, 6.10, 692, 35, NULL),
(99, 119, 5, 3, '2025-06-14 06:39:02', 30.40, 69, 6.50, 853, 56, NULL),
(102, 119, 9, 3, '2025-06-14 06:39:02', 33.10, 42, 6.90, 767, 46, NULL),
(103, 119, 6, 4, '2025-06-14 06:39:02', 21.70, 79, 5.70, 351, 46, NULL),
(104, 119, 1, 3, '2025-06-14 06:39:08', 33.40, 43, 6.30, 487, 67, NULL),
(105, 118, 2, 3, '2025-06-14 06:39:08', 26.40, 67, 6.30, 774, 55, NULL),
(106, 118, 3, 3, '2025-06-14 06:39:08', 33.00, 71, 8.00, 170, 40, NULL),
(107, 111, 4, 3, '2025-06-14 06:39:08', 25.00, 64, 5.70, 299, 41, NULL),
(108, 119, 5, 3, '2025-06-14 06:39:08', 25.70, 53, 5.60, 287, 58, NULL),
(111, 118, 9, 3, '2025-06-14 06:39:08', 25.20, 53, 5.90, 752, 32, NULL),
(112, 118, 6, 4, '2025-06-14 06:39:08', 30.30, 76, 5.00, 966, 74, NULL),
(113, 119, 1, 3, '2025-06-14 06:39:13', 30.60, 43, 6.30, 315, 31, NULL),
(114, 119, 2, 3, '2025-06-14 06:39:13', 28.90, 41, 5.80, 868, 54, NULL),
(115, 118, 3, 3, '2025-06-14 06:39:13', 33.00, 64, 5.50, 394, 35, NULL),
(116, 118, 4, 3, '2025-06-14 06:39:13', 24.40, 51, 6.60, 117, 35, NULL),
(117, 119, 5, 3, '2025-06-14 06:39:13', 23.00, 73, 6.00, 233, 37, NULL),
(120, 118, 9, 3, '2025-06-14 06:39:13', 34.90, 68, 8.00, 281, 53, NULL),
(121, 119, 6, 4, '2025-06-14 06:39:13', 31.10, 71, 5.60, 682, 70, NULL),
(122, 111, 1, 3, '2025-06-14 06:39:18', 20.10, 70, 6.30, 882, 77, NULL),
(123, 111, 2, 3, '2025-06-14 06:39:18', 21.10, 67, 5.70, 548, 75, NULL),
(124, 119, 3, 3, '2025-06-14 06:39:18', 29.80, 66, 7.30, 990, 32, NULL),
(125, 111, 4, 3, '2025-06-14 06:39:18', 21.10, 76, 7.60, 556, 77, NULL),
(126, 118, 5, 3, '2025-06-14 06:39:18', 29.10, 78, 7.20, 211, 68, NULL),
(129, 111, 9, 3, '2025-06-14 06:39:18', 21.00, 61, 5.10, 187, 75, NULL),
(130, 111, 6, 4, '2025-06-14 06:39:18', 34.80, 58, 5.10, 889, 39, NULL),
(131, 119, 1, 3, '2025-06-14 06:39:23', 24.20, 74, 7.00, 267, 72, NULL),
(132, 118, 2, 3, '2025-06-14 06:39:23', 26.90, 53, 8.00, 256, 69, NULL),
(133, 118, 3, 3, '2025-06-14 06:39:23', 25.00, 57, 8.00, 674, 43, NULL),
(134, 118, 4, 3, '2025-06-14 06:39:23', 32.80, 43, 6.80, 823, 69, NULL),
(135, 119, 5, 3, '2025-06-14 06:39:23', 32.00, 66, 7.80, 267, 35, NULL),
(138, 111, 9, 3, '2025-06-14 06:39:23', 20.90, 43, 7.10, 394, 65, NULL),
(139, 118, 6, 4, '2025-06-14 06:39:23', 29.80, 44, 7.80, 953, 61, NULL),
(140, 118, 1, 3, '2025-06-14 06:39:27', 34.60, 79, 5.60, 997, 32, NULL),
(141, 111, 2, 3, '2025-06-14 06:39:27', 31.90, 54, 6.10, 611, 64, NULL),
(142, 111, 3, 3, '2025-06-14 06:39:27', 20.90, 67, 6.00, 644, 33, NULL),
(143, 119, 4, 3, '2025-06-14 06:39:27', 34.00, 68, 7.60, 155, 51, NULL),
(144, 119, 5, 3, '2025-06-14 06:39:27', 33.40, 64, 8.00, 140, 74, NULL),
(147, 119, 9, 3, '2025-06-14 06:39:27', 34.70, 67, 7.10, 664, 37, NULL),
(148, 111, 6, 4, '2025-06-14 06:39:27', 27.80, 56, 6.90, 706, 74, NULL),
(149, 119, 1, 3, '2025-06-14 06:39:29', 32.90, 59, 8.00, 391, 46, NULL),
(150, 118, 2, 3, '2025-06-14 06:39:29', 31.10, 51, 7.20, 654, 36, NULL),
(151, 119, 3, 3, '2025-06-14 06:39:29', 33.60, 72, 8.00, 626, 67, NULL),
(152, 119, 4, 3, '2025-06-14 06:39:29', 32.90, 60, 5.30, 498, 69, NULL),
(153, 111, 5, 3, '2025-06-14 06:39:29', 23.50, 58, 5.00, 673, 57, NULL),
(156, 119, 9, 3, '2025-06-14 06:39:29', 34.90, 72, 5.00, 205, 73, NULL),
(157, 119, 6, 4, '2025-06-14 06:39:29', 31.20, 69, 7.40, 293, 62, NULL),
(158, 119, 1, 3, '2025-06-14 06:39:35', 31.50, 42, 5.30, 382, 63, NULL),
(159, 111, 2, 3, '2025-06-14 06:39:35', 24.20, 59, 5.40, 155, 80, NULL),
(160, 118, 3, 3, '2025-06-14 06:39:35', 31.20, 65, 6.40, 894, 48, NULL),
(161, 119, 4, 3, '2025-06-14 06:39:35', 31.40, 76, 6.90, 552, 38, NULL),
(162, 119, 5, 3, '2025-06-14 06:39:35', 20.90, 58, 7.00, 944, 30, NULL),
(165, 119, 9, 3, '2025-06-14 06:39:35', 23.00, 43, 6.10, 727, 68, NULL),
(166, 118, 6, 4, '2025-06-14 06:39:35', 30.10, 63, 6.40, 862, 37, NULL),
(167, 119, 1, 3, '2025-06-14 06:39:40', 24.80, 79, 6.50, 921, 57, NULL),
(168, 111, 2, 3, '2025-06-14 06:39:40', 23.30, 58, 7.20, 886, 74, NULL),
(169, 119, 3, 3, '2025-06-14 06:39:40', 21.20, 52, 6.50, 953, 41, NULL),
(170, 119, 4, 3, '2025-06-14 06:39:40', 25.80, 45, 5.60, 761, 47, NULL),
(171, 118, 5, 3, '2025-06-14 06:39:40', 28.90, 61, 5.60, 645, 69, NULL),
(174, 119, 9, 3, '2025-06-14 06:39:40', 26.10, 78, 7.60, 119, 70, NULL),
(175, 119, 6, 4, '2025-06-14 06:39:40', 29.20, 67, 6.10, 944, 72, NULL),
(176, 119, 1, 3, '2025-06-14 06:39:45', 24.20, 73, 5.70, 174, 79, NULL),
(177, 111, 2, 3, '2025-06-14 06:39:45', 33.10, 44, 5.30, 400, 53, NULL),
(178, 118, 3, 3, '2025-06-14 06:39:45', 23.80, 78, 6.60, 449, 56, NULL),
(179, 118, 4, 3, '2025-06-14 06:39:45', 20.50, 40, 5.30, 824, 62, NULL),
(180, 118, 5, 3, '2025-06-14 06:39:45', 21.70, 61, 5.60, 944, 30, NULL),
(183, 119, 9, 3, '2025-06-14 06:39:45', 21.30, 71, 5.10, 943, 60, NULL),
(184, 111, 6, 4, '2025-06-14 06:39:45', 23.40, 54, 6.20, 149, 49, NULL),
(185, 119, 1, 3, '2025-06-14 06:39:50', 29.40, 74, 6.60, 183, 48, NULL),
(186, 111, 2, 3, '2025-06-14 06:39:50', 25.10, 67, 6.00, 683, 67, NULL),
(187, 119, 3, 3, '2025-06-14 06:39:50', 27.40, 73, 5.10, 992, 56, NULL),
(188, 118, 4, 3, '2025-06-14 06:39:50', 27.30, 52, 7.00, 738, 78, NULL),
(189, 119, 5, 3, '2025-06-14 06:39:50', 22.60, 80, 5.20, 464, 65, NULL),
(192, 118, 9, 3, '2025-06-14 06:39:50', 28.70, 61, 5.10, 547, 62, NULL),
(193, 118, 6, 4, '2025-06-14 06:39:50', 32.80, 76, 7.40, 416, 74, NULL),
(194, 111, 1, 3, '2025-06-14 06:39:55', 32.30, 68, 6.10, 467, 68, NULL),
(195, 118, 2, 3, '2025-06-14 06:39:55', 27.30, 51, 5.30, 744, 79, NULL),
(196, 118, 3, 3, '2025-06-14 06:39:55', 24.70, 55, 7.30, 212, 77, NULL),
(197, 118, 4, 3, '2025-06-14 06:39:55', 23.20, 57, 5.70, 246, 79, NULL),
(198, 111, 5, 3, '2025-06-14 06:39:55', 26.10, 77, 6.80, 941, 72, NULL),
(201, 118, 9, 3, '2025-06-14 06:39:55', 22.20, 64, 6.30, 539, 48, NULL),
(202, 118, 6, 4, '2025-06-14 06:39:55', 25.20, 43, 6.70, 695, 43, NULL),
(203, 111, 10, 2, '2025-06-14 09:20:51', 20.80, 58, 5.60, 161, 62, NULL),
(204, 123, 1, 3, '2025-06-14 09:20:51', 35.00, 59, 7.40, 600, 65, NULL),
(205, 119, 2, 3, '2025-06-14 09:20:51', 27.20, 71, 5.40, 593, 54, NULL),
(206, 118, 3, 3, '2025-06-14 09:20:51', 29.40, 53, 5.10, 643, 61, NULL),
(207, 119, 4, 3, '2025-06-14 09:20:51', 21.40, 43, 6.10, 489, 31, NULL),
(208, 118, 5, 3, '2025-06-14 09:20:51', 21.30, 59, 6.10, 874, 43, NULL),
(211, 123, 9, 3, '2025-06-14 09:20:51', 32.30, 49, 5.00, 666, 50, NULL),
(212, 111, 6, 4, '2025-06-14 09:20:51', 33.00, 56, 5.70, 689, 66, NULL),
(213, 118, 10, 2, '2025-06-14 09:20:56', 33.20, 52, 6.10, 856, 78, NULL),
(214, 111, 1, 3, '2025-06-14 09:20:56', 24.80, 42, 7.80, 145, 79, NULL),
(215, 111, 2, 3, '2025-06-14 09:20:56', 30.80, 60, 7.50, 683, 69, NULL),
(216, 123, 3, 3, '2025-06-14 09:20:56', 25.50, 73, 6.40, 651, 66, NULL),
(217, 119, 4, 3, '2025-06-14 09:20:56', 30.30, 48, 5.00, 622, 74, NULL),
(218, 123, 5, 3, '2025-06-14 09:20:56', 25.80, 74, 5.10, 453, 69, NULL),
(221, 111, 9, 3, '2025-06-14 09:20:56', 32.50, 54, 7.70, 212, 80, NULL),
(222, 121, 6, 4, '2025-06-14 09:20:56', 28.40, 46, 6.50, 842, 36, NULL),
(223, 111, 10, 2, '2025-06-14 09:21:01', 33.10, 77, 7.80, 169, 53, NULL),
(224, 123, 1, 3, '2025-06-14 09:21:01', 22.80, 45, 5.90, 479, 40, NULL),
(225, 118, 2, 3, '2025-06-14 09:21:01', 28.30, 74, 5.30, 137, 62, NULL),
(226, 111, 3, 3, '2025-06-14 09:21:01', 27.20, 72, 7.70, 325, 50, NULL),
(227, 119, 4, 3, '2025-06-14 09:21:01', 29.20, 73, 7.60, 343, 60, NULL),
(228, 123, 5, 3, '2025-06-14 09:21:01', 24.80, 76, 5.50, 428, 50, NULL),
(231, 123, 9, 3, '2025-06-14 09:21:01', 22.10, 61, 5.50, 169, 53, NULL),
(232, 119, 6, 4, '2025-06-14 09:21:01', 33.00, 59, 5.00, 629, 34, NULL),
(233, 111, 10, 2, '2025-06-14 09:21:07', 21.80, 63, 6.10, 293, 50, NULL),
(234, 118, 1, 3, '2025-06-14 09:21:07', 34.70, 53, 5.00, 397, 45, NULL),
(235, 119, 2, 3, '2025-06-14 09:21:07', 20.60, 53, 7.80, 258, 47, NULL),
(236, 121, 3, 3, '2025-06-14 09:21:07', 24.50, 67, 6.00, 224, 35, NULL),
(237, 123, 4, 3, '2025-06-14 09:21:07', 31.90, 47, 6.60, 670, 55, NULL),
(238, 121, 5, 3, '2025-06-14 09:21:07', 34.80, 57, 6.90, 535, 56, NULL),
(241, 121, 9, 3, '2025-06-14 09:21:07', 30.70, 73, 6.10, 887, 43, NULL),
(242, 121, 6, 4, '2025-06-14 09:21:07', 28.10, 51, 5.70, 527, 57, NULL),
(243, 111, 10, 2, '2025-06-14 09:21:12', 22.50, 40, 7.40, 411, 64, NULL),
(244, 119, 1, 3, '2025-06-14 09:21:12', 28.30, 57, 7.40, 748, 34, NULL),
(245, 119, 2, 3, '2025-06-14 09:21:12', 26.00, 61, 6.70, 438, 78, NULL),
(246, 118, 3, 3, '2025-06-14 09:21:12', 34.70, 59, 6.10, 728, 31, NULL),
(247, 111, 4, 3, '2025-06-14 09:21:12', 30.10, 69, 5.10, 990, 62, NULL),
(248, 123, 5, 3, '2025-06-14 09:21:12', 28.60, 54, 6.60, 983, 61, NULL),
(251, 121, 9, 3, '2025-06-14 09:21:12', 29.20, 52, 5.00, 729, 38, NULL),
(252, 119, 6, 4, '2025-06-14 09:21:12', 25.40, 79, 5.30, 287, 59, NULL),
(253, 111, 10, 2, '2025-06-14 09:21:16', 28.00, 68, 6.50, 423, 76, NULL),
(254, 118, 1, 3, '2025-06-14 09:21:16', 25.50, 71, 7.70, 110, 39, NULL),
(255, 111, 2, 3, '2025-06-14 09:21:16', 34.30, 43, 6.20, 268, 40, NULL),
(256, 111, 3, 3, '2025-06-14 09:21:16', 21.70, 78, 5.00, 146, 34, NULL),
(257, 123, 4, 3, '2025-06-14 09:21:16', 34.50, 55, 6.80, 487, 55, NULL),
(258, 119, 5, 3, '2025-06-14 09:21:16', 32.40, 62, 6.20, 148, 55, NULL),
(261, 123, 9, 3, '2025-06-14 09:21:16', 21.30, 74, 7.30, 917, 61, NULL),
(262, 119, 6, 4, '2025-06-14 09:21:16', 33.10, 72, 6.50, 697, 37, NULL),
(263, 111, 10, 2, '2025-06-14 09:21:22', 33.70, 66, 6.40, 642, 65, NULL),
(264, 118, 1, 3, '2025-06-14 09:21:22', 30.30, 56, 7.60, 674, 50, NULL),
(265, 118, 2, 3, '2025-06-14 09:21:22', 20.70, 68, 6.40, 187, 54, NULL),
(266, 111, 3, 3, '2025-06-14 09:21:22', 29.90, 52, 5.00, 146, 39, NULL),
(267, 119, 4, 3, '2025-06-14 09:21:22', 34.40, 52, 6.20, 748, 39, NULL),
(268, 121, 5, 3, '2025-06-14 09:21:22', 25.20, 66, 5.00, 316, 38, NULL),
(271, 121, 9, 3, '2025-06-14 09:21:22', 23.90, 49, 6.60, 544, 55, NULL),
(272, 121, 6, 4, '2025-06-14 09:21:22', 26.00, 59, 5.10, 339, 60, NULL),
(273, 111, 10, 2, '2025-06-14 09:21:27', 28.00, 75, 7.70, 718, 70, NULL),
(274, 123, 1, 3, '2025-06-14 09:21:27', 23.50, 46, 7.10, 359, 69, NULL),
(275, 119, 2, 3, '2025-06-14 09:21:27', 27.00, 49, 6.30, 909, 50, NULL),
(276, 121, 3, 3, '2025-06-14 09:21:27', 29.80, 53, 5.90, 888, 50, NULL),
(277, 111, 4, 3, '2025-06-14 09:21:27', 32.80, 44, 7.10, 229, 79, NULL),
(278, 123, 5, 3, '2025-06-14 09:21:27', 21.90, 66, 6.10, 473, 60, NULL),
(281, 118, 9, 3, '2025-06-14 09:21:27', 33.90, 80, 5.90, 265, 61, NULL),
(282, 111, 6, 4, '2025-06-14 09:21:27', 32.40, 65, 5.20, 499, 67, NULL),
(283, 121, 10, 2, '2025-06-14 09:21:32', 32.30, 43, 6.40, 335, 67, NULL),
(284, 123, 1, 3, '2025-06-14 09:21:32', 32.30, 63, 7.20, 363, 48, NULL),
(285, 119, 2, 3, '2025-06-14 09:21:32', 20.30, 67, 7.30, 977, 45, NULL),
(286, 121, 3, 3, '2025-06-14 09:21:32', 23.50, 51, 6.10, 464, 68, NULL),
(287, 123, 4, 3, '2025-06-14 09:21:32', 27.50, 51, 5.10, 692, 79, NULL),
(288, 123, 5, 3, '2025-06-14 09:21:32', 34.90, 79, 5.70, 137, 58, NULL),
(291, 123, 9, 3, '2025-06-14 09:21:32', 20.20, 60, 5.00, 545, 39, NULL),
(292, 123, 6, 4, '2025-06-14 09:21:32', 33.60, 74, 6.10, 187, 32, NULL),
(293, 119, 10, 2, '2025-06-14 09:21:37', 21.10, 46, 6.70, 397, 42, NULL),
(294, 118, 1, 3, '2025-06-14 09:21:37', 30.50, 57, 7.90, 244, 68, NULL),
(295, 118, 2, 3, '2025-06-14 09:21:37', 29.00, 48, 6.80, 698, 37, NULL),
(296, 111, 3, 3, '2025-06-14 09:21:37', 28.60, 40, 6.60, 207, 69, NULL),
(297, 119, 4, 3, '2025-06-14 09:21:37', 33.70, 76, 5.30, 999, 69, NULL),
(298, 119, 5, 3, '2025-06-14 09:21:37', 23.00, 43, 6.80, 715, 45, NULL),
(301, 123, 9, 3, '2025-06-14 09:21:37', 28.60, 67, 5.00, 135, 56, NULL),
(302, 123, 6, 4, '2025-06-14 09:21:37', 23.50, 79, 6.00, 699, 60, NULL),
(303, 119, 10, 2, '2025-06-14 09:21:42', 31.30, 74, 6.10, 476, 69, NULL),
(304, 123, 1, 3, '2025-06-14 09:21:42', 26.80, 57, 5.10, 106, 36, NULL),
(305, 119, 2, 3, '2025-06-14 09:21:42', 25.00, 77, 5.00, 218, 72, NULL),
(306, 121, 3, 3, '2025-06-14 09:21:42', 34.50, 51, 5.30, 411, 39, NULL),
(307, 111, 4, 3, '2025-06-14 09:21:42', 29.70, 67, 7.20, 842, 34, NULL),
(308, 111, 5, 3, '2025-06-14 09:21:42', 20.70, 72, 7.80, 664, 39, NULL),
(311, 121, 9, 3, '2025-06-14 09:21:42', 31.40, 40, 5.20, 290, 31, NULL),
(312, 123, 6, 4, '2025-06-14 09:21:42', 23.60, 46, 5.60, 774, 53, NULL),
(313, 119, 10, 2, '2025-06-14 09:21:46', 29.60, 40, 7.40, 754, 48, NULL),
(314, 123, 1, 3, '2025-06-14 09:21:46', 20.20, 52, 5.20, 383, 77, NULL),
(315, 119, 2, 3, '2025-06-14 09:21:46', 20.10, 57, 6.80, 804, 35, NULL),
(316, 118, 3, 3, '2025-06-14 09:21:46', 21.00, 58, 7.40, 725, 45, NULL),
(317, 123, 4, 3, '2025-06-14 09:21:46', 31.90, 49, 5.40, 121, 58, NULL),
(318, 119, 5, 3, '2025-06-14 09:21:46', 20.90, 75, 7.70, 125, 58, NULL),
(321, 119, 9, 3, '2025-06-14 09:21:46', 27.00, 52, 6.20, 816, 80, NULL),
(322, 119, 6, 4, '2025-06-14 09:21:46', 32.70, 50, 6.80, 114, 56, NULL),
(323, 121, 10, 2, '2025-06-14 09:21:47', 25.90, 48, 7.40, 516, 46, NULL),
(324, 123, 1, 3, '2025-06-14 09:21:47', 24.30, 77, 7.40, 116, 34, NULL),
(325, 118, 2, 3, '2025-06-14 09:21:47', 26.20, 62, 6.70, 967, 76, NULL),
(326, 121, 3, 3, '2025-06-14 09:21:47', 22.20, 42, 7.60, 912, 72, NULL),
(327, 119, 4, 3, '2025-06-14 09:21:47', 30.90, 56, 6.70, 223, 36, NULL),
(328, 123, 5, 3, '2025-06-14 09:21:47', 33.60, 64, 7.80, 248, 37, NULL),
(331, 123, 9, 3, '2025-06-14 09:21:47', 33.30, 50, 5.90, 625, 67, NULL),
(332, 111, 6, 4, '2025-06-14 09:21:47', 22.40, 53, 8.00, 854, 48, NULL),
(333, 121, 10, 2, '2025-06-14 12:39:56', 30.80, 54, 5.10, 414, 74, NULL),
(334, 119, 1, 3, '2025-06-14 12:39:56', 27.30, 73, 6.00, 777, 34, NULL),
(335, 119, 2, 3, '2025-06-14 12:39:56', 25.90, 45, 6.50, 994, 72, NULL),
(336, 118, 3, 3, '2025-06-14 12:39:56', 30.90, 48, 8.00, 273, 50, NULL),
(337, 111, 4, 3, '2025-06-14 12:39:56', 32.00, 58, 7.00, 934, 65, NULL),
(338, 119, 5, 3, '2025-06-14 12:39:56', 25.40, 62, 5.60, 704, 46, NULL),
(341, 119, 9, 3, '2025-06-14 12:39:56', 21.60, 59, 6.30, 155, 73, NULL),
(342, 119, 6, 4, '2025-06-14 12:39:56', 29.20, 56, 5.70, 111, 51, NULL),
(343, 118, 10, 2, '2025-06-14 12:40:01', 33.00, 47, 7.60, 484, 63, NULL),
(344, 121, 1, 3, '2025-06-14 12:40:01', 21.30, 62, 7.30, 669, 31, NULL),
(345, 119, 2, 3, '2025-06-14 12:40:01', 22.50, 64, 6.70, 901, 79, NULL),
(346, 121, 3, 3, '2025-06-14 12:40:01', 24.20, 77, 6.60, 826, 47, NULL),
(347, 123, 4, 3, '2025-06-14 12:40:01', 22.40, 70, 7.00, 507, 36, NULL),
(348, 118, 5, 3, '2025-06-14 12:40:01', 23.60, 63, 7.30, 636, 48, NULL),
(351, 118, 9, 3, '2025-06-14 12:40:01', 35.00, 48, 6.80, 137, 55, NULL),
(352, 111, 6, 4, '2025-06-14 12:40:01', 21.90, 69, 7.20, 533, 58, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('Admin','Petani','Teknisi','Viewer') NOT NULL DEFAULT 'Viewer',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `first_name`, `last_name`, `email`, `password_hash`, `role`, `created_at`, `updated_at`) VALUES
(1, 'php', 'kontol', 'm@gmail.com', '$2y$10$z7T6tV0m2T1yznnBpPINHuRRnapd2B5y92ZPVK5kLJZrQEpsmx6Uy', 'Viewer', '2025-06-04 14:46:11', '2025-06-04 14:46:11'),
(2, 'Bintang', 'Maulana', 'bintangmaulana2m@gmail.com', '$2y$10$j.rbmz0gp7wGNARk.z9eL.jHCIXlikzpGqQU2XjmG5Wqwy3.a7N8G', 'Viewer', '2025-06-05 07:00:03', '2025-06-05 07:00:03'),
(3, 'Bintang', 'Maulana', '2m@gmail.com', '$2y$10$r7KphyHFOCPL9ZbX3UZF7e/A6E/VlkFdBL56B/uVFKVRzxgHxWvKu', 'Viewer', '2025-06-08 08:34:42', '2025-06-08 08:34:42'),
(4, 'aggugng', 'muhammad', 'agungmuhammad537@gmail.com', '$2y$10$08Uqf6umXidU7032PQWEo.4QbF/1RBLtO1rIc/TA.hwgyCuX1NmJ.', 'Viewer', '2025-06-08 12:44:58', '2025-06-08 12:44:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `workhour`
--

CREATE TABLE `workhour` (
  `id_workhour` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `month` int(11) NOT NULL CHECK (`month` between 1 and 12),
  `year` year(4) NOT NULL,
  `week_number` tinyint(4) NOT NULL CHECK (`week_number` between 1 and 4),
  `hours` int(11) NOT NULL DEFAULT 0,
  `overtime_hours` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `appsetting`
--
ALTER TABLE `appsetting`
  ADD PRIMARY KEY (`id_setting`);

--
-- Indeks untuk tabel `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id_employee`);

--
-- Indeks untuk tabel `harvestprediction`
--
ALTER TABLE `harvestprediction`
  ADD PRIMARY KEY (`id_prediction`),
  ADD KEY `lahan_id` (`lahan_id`);

--
-- Indeks untuk tabel `land`
--
ALTER TABLE `land`
  ADD PRIMARY KEY (`id_lahan`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indeks untuk tabel `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id_payroll`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indeks untuk tabel `pestdetection`
--
ALTER TABLE `pestdetection`
  ADD PRIMARY KEY (`id_detection`),
  ADD KEY `lahan_id` (`lahan_id`);

--
-- Indeks untuk tabel `plant_infos`
--
ALTER TABLE `plant_infos`
  ADD PRIMARY KEY (`id_tanaman`),
  ADD KEY `id_lahan` (`land_id_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `plant_infos_ibfk_3` (`land_id`);

--
-- Indeks untuk tabel `productivity`
--
ALTER TABLE `productivity`
  ADD PRIMARY KEY (`id_productivity`);

--
-- Indeks untuk tabel `recommendationfertilization`
--
ALTER TABLE `recommendationfertilization`
  ADD PRIMARY KEY (`id_rekomendasi_pupuk`),
  ADD KEY `lahan_id` (`lahan_id`);

--
-- Indeks untuk tabel `recommendationirrigation`
--
ALTER TABLE `recommendationirrigation`
  ADD PRIMARY KEY (`id_rekomendasi_irigasi`),
  ADD KEY `lahan_id` (`lahan_id`);

--
-- Indeks untuk tabel `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id_report`),
  ADD KEY `author_id` (`author_id`);

--
-- Indeks untuk tabel `sensordevice`
--
ALTER TABLE `sensordevice`
  ADD PRIMARY KEY (`id_sensor`),
  ADD KEY `sensordevice_ibfk_1` (`lahan_id`);

--
-- Indeks untuk tabel `sensorreading`
--
ALTER TABLE `sensorreading`
  ADD PRIMARY KEY (`id_reading`),
  ADD KEY `sensor_id` (`sensor_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `sensorreading_ibfk_2` (`lahan_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `workhour`
--
ALTER TABLE `workhour`
  ADD PRIMARY KEY (`id_workhour`),
  ADD KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `employee`
--
ALTER TABLE `employee`
  MODIFY `id_employee` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `harvestprediction`
--
ALTER TABLE `harvestprediction`
  MODIFY `id_prediction` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `land`
--
ALTER TABLE `land`
  MODIFY `id_lahan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id_payroll` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pestdetection`
--
ALTER TABLE `pestdetection`
  MODIFY `id_detection` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `plant_infos`
--
ALTER TABLE `plant_infos`
  MODIFY `id_tanaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `productivity`
--
ALTER TABLE `productivity`
  MODIFY `id_productivity` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `recommendationfertilization`
--
ALTER TABLE `recommendationfertilization`
  MODIFY `id_rekomendasi_pupuk` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `recommendationirrigation`
--
ALTER TABLE `recommendationirrigation`
  MODIFY `id_rekomendasi_irigasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `report`
--
ALTER TABLE `report`
  MODIFY `id_report` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sensordevice`
--
ALTER TABLE `sensordevice`
  MODIFY `id_sensor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT untuk tabel `sensorreading`
--
ALTER TABLE `sensorreading`
  MODIFY `id_reading` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=353;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `workhour`
--
ALTER TABLE `workhour`
  MODIFY `id_workhour` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `harvestprediction`
--
ALTER TABLE `harvestprediction`
  ADD CONSTRAINT `harvestprediction_ibfk_1` FOREIGN KEY (`lahan_id`) REFERENCES `land` (`id_lahan`);

--
-- Ketidakleluasaan untuk tabel `land`
--
ALTER TABLE `land`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `payroll_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id_employee`);

--
-- Ketidakleluasaan untuk tabel `pestdetection`
--
ALTER TABLE `pestdetection`
  ADD CONSTRAINT `pestdetection_ibfk_1` FOREIGN KEY (`lahan_id`) REFERENCES `land` (`id_lahan`);

--
-- Ketidakleluasaan untuk tabel `plant_infos`
--
ALTER TABLE `plant_infos`
  ADD CONSTRAINT `plant_infos_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `plant_infos_ibfk_3` FOREIGN KEY (`land_id`) REFERENCES `land` (`id_lahan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `recommendationfertilization`
--
ALTER TABLE `recommendationfertilization`
  ADD CONSTRAINT `recommendationfertilization_ibfk_1` FOREIGN KEY (`lahan_id`) REFERENCES `land` (`id_lahan`);

--
-- Ketidakleluasaan untuk tabel `recommendationirrigation`
--
ALTER TABLE `recommendationirrigation`
  ADD CONSTRAINT `recommendationirrigation_ibfk_1` FOREIGN KEY (`lahan_id`) REFERENCES `land` (`id_lahan`);

--
-- Ketidakleluasaan untuk tabel `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `sensordevice`
--
ALTER TABLE `sensordevice`
  ADD CONSTRAINT `sensordevice_ibfk_1` FOREIGN KEY (`lahan_id`) REFERENCES `land` (`id_lahan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sensorreading`
--
ALTER TABLE `sensorreading`
  ADD CONSTRAINT `sensorreading_ibfk_1` FOREIGN KEY (`sensor_id`) REFERENCES `sensordevice` (`id_sensor`),
  ADD CONSTRAINT `sensorreading_ibfk_2` FOREIGN KEY (`lahan_id`) REFERENCES `land` (`id_lahan`) ON DELETE CASCADE,
  ADD CONSTRAINT `sensorreading_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `workhour`
--
ALTER TABLE `workhour`
  ADD CONSTRAINT `workhour_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id_employee`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
