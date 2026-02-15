-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Feb 2026 pada 15.09
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
-- Database: `db_itl_shipping`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kapal`
--

CREATE TABLE `kapal` (
  `id` int(11) NOT NULL,
  `nama_kapal` varchar(100) NOT NULL,
  `kode_kapal` varchar(30) NOT NULL,
  `jenis_kapal` varchar(50) DEFAULT NULL,
  `bendera` varchar(50) DEFAULT NULL,
  `kapasitas_ton` int(11) DEFAULT 0,
  `pemilik` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kapal`
--

INSERT INTO `kapal` (`id`, `nama_kapal`, `kode_kapal`, `jenis_kapal`, `bendera`, `kapasitas_ton`, `pemilik`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(1, 'MV Nusantara Jaya', 'ITL-001', 'Cargo', 'Indonesia', 15000, 'PT. ITL Shipping Agency', NULL, 'aktif', '2026-02-14 21:26:01', '2026-02-14 21:26:01'),
(2, 'MV Pacific Star', 'ITL-002', 'Container', 'Indonesia', 25000, 'PT. ITL Shipping Agency', NULL, 'aktif', '2026-02-14 21:26:01', '2026-02-14 21:26:01'),
(3, 'MV Samudra Sakti', 'ITL-003', 'Tanker', 'Indonesia', 30000, 'PT. ITL Shipping Agency', NULL, 'aktif', '2026-02-14 21:26:01', '2026-02-14 21:26:01'),
(4, 'MV Bahari Express', 'ITL-004', 'Bulk Carrier', 'Singapore', 20000, 'Partner Shipping Co.', NULL, 'aktif', '2026-02-14 21:26:01', '2026-02-14 21:26:01'),
(5, 'MV Laut Biru', 'ITL-005', 'General Cargo', 'Indonesia', 10000, 'PT. ITL Shipping Agency', NULL, 'nonaktif', '2026-02-14 21:26:01', '2026-02-14 21:26:01'),
(6, 'MV Garuda Laut', 'ITL-006', 'Container', 'Indonesia', 18000, 'PT. ITL Shipping Agency', NULL, 'aktif', '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(7, 'MV Sinar Jaya', 'ITL-007', 'Cargo', 'Indonesia', 12000, 'PT. ITL Shipping Agency', NULL, 'aktif', '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(8, 'MV Ocean Glory', 'ITL-008', 'Tanker', 'Singapore', 35000, 'Ocean Line Pte Ltd', NULL, 'aktif', '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(9, 'MV Bintang Timur', 'ITL-009', 'Bulk Carrier', 'Indonesia', 22000, 'PT. ITL Shipping Agency', NULL, 'aktif', '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(10, 'MV Cakrawala', 'ITL-010', 'General Cargo', 'Indonesia', 9500, 'PT. Cakrawala Maritime', NULL, 'aktif', '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(11, 'MV Pertiwi', 'ITL-011', 'Cargo', 'Indonesia', 14000, 'PT. ITL Shipping Agency', NULL, 'aktif', '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(12, 'MV Asia Star', 'ITL-012', 'Container', 'Malaysia', 28000, 'Asia Star Shipping Sdn Bhd', NULL, 'aktif', '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(13, 'MV Krakatau', 'ITL-013', 'Tanker', 'Indonesia', 40000, 'PT. ITL Shipping Agency', NULL, 'aktif', '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(14, 'MV Derawan', 'ITL-014', 'General Cargo', 'Indonesia', 8500, 'PT. Derawan Logistik', NULL, 'aktif', '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(15, 'MV Raja Ampat', 'ITL-015', 'Bulk Carrier', 'Indonesia', 26000, 'PT. ITL Shipping Agency', NULL, 'aktif', '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(16, 'MV Selat Sunda', 'ITL-016', 'Cargo', 'Indonesia', 11000, 'PT. Selat Maritime', NULL, 'aktif', '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(17, 'MV Karimun', 'ITL-017', 'Container', 'Indonesia', 19000, 'PT. ITL Shipping Agency', NULL, 'aktif', '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(18, 'MV Coral Sea', 'ITL-018', 'Tanker', 'Singapore', 32000, 'Coral Shipping Pte Ltd', NULL, 'nonaktif', '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(19, 'MV Flores', 'ITL-019', 'Passenger', 'Indonesia', 5000, 'PT. Pelni', NULL, 'aktif', '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(20, 'MV Banda Neira', 'ITL-020', 'General Cargo', 'Indonesia', 7500, 'PT. ITL Shipping Agency', NULL, 'aktif', '2026-02-14 21:35:14', '2026-02-14 21:35:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keberangkatan`
--

CREATE TABLE `keberangkatan` (
  `id` int(11) NOT NULL,
  `id_kapal` int(11) NOT NULL,
  `pelabuhan_asal` varchar(100) NOT NULL,
  `pelabuhan_tujuan` varchar(100) NOT NULL,
  `tanggal_berangkat` datetime NOT NULL,
  `muatan` varchar(100) DEFAULT NULL,
  `berat_muatan` int(11) DEFAULT 0,
  `status` enum('dijadwalkan','berlayar','selesai','batal') NOT NULL DEFAULT 'dijadwalkan',
  `catatan` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `keberangkatan`
--

INSERT INTO `keberangkatan` (`id`, `id_kapal`, `pelabuhan_asal`, `pelabuhan_tujuan`, `tanggal_berangkat`, `muatan`, `berat_muatan`, `status`, `catatan`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 4, 'Jakarta', 'Surabaya', '2026-02-14 21:28:00', 'CPO', 1, 'dijadwalkan', '', 1, '2026-02-14 21:29:10', '2026-02-14 21:29:10'),
(2, 1, 'Tanjung Priok, Jakarta', 'Tanjung Perak, Surabaya', '2026-02-10 08:00:00', 'Batu Bara', 12000, 'selesai', 'Pengiriman rutin bulanan', 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(3, 2, 'Tanjung Perak, Surabaya', 'Makassar', '2026-02-11 06:30:00', 'Container Mixed', 20000, 'selesai', NULL, 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(4, 3, 'Belawan, Medan', 'Tanjung Priok, Jakarta', '2026-02-12 10:00:00', 'CPO (Crude Palm Oil)', 28000, 'berlayar', 'Kapal tanker penuh', 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(5, 4, 'Balikpapan', 'Tanjung Priok, Jakarta', '2026-02-13 07:00:00', 'Batu Bara', 18000, 'berlayar', NULL, 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(6, 1, 'Tanjung Priok, Jakarta', 'Banjarmasin', '2026-02-14 09:00:00', 'Semen', 10000, 'dijadwalkan', 'Pengiriman semen untuk proyek', 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(7, 5, 'Makassar', 'Sorong', '2026-02-14 14:00:00', 'Bahan Pokok', 8000, 'dijadwalkan', NULL, 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(8, 6, 'Tanjung Priok, Jakarta', 'Pontianak', '2026-02-15 06:00:00', 'Container Mixed', 15000, 'dijadwalkan', 'Rute baru mingguan', 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(9, 7, 'Tanjung Perak, Surabaya', 'Bitung', '2026-02-15 08:30:00', 'Gula & Tepung', 10000, 'dijadwalkan', NULL, 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(10, 8, 'Singapore', 'Tanjung Priok, Jakarta', '2026-02-09 22:00:00', 'Minyak Mentah', 33000, 'selesai', 'Impor minyak mentah', 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(11, 9, 'Tanjung Priok, Jakarta', 'Jayapura', '2026-02-16 05:00:00', 'Besi & Baja', 20000, 'dijadwalkan', 'Pengiriman material konstruksi', 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(12, 10, 'Belawan, Medan', 'Batam', '2026-02-08 11:00:00', 'Karet', 7000, 'selesai', NULL, 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(13, 11, 'Tanjung Priok, Jakarta', 'Kupang', '2026-02-13 16:00:00', 'Beras', 12000, 'berlayar', 'Kebutuhan pokok NTT', 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(14, 12, 'Port Klang, Malaysia', 'Tanjung Priok, Jakarta', '2026-02-14 20:00:00', 'Container Electronics', 25000, 'dijadwalkan', NULL, 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(15, 13, 'Cilacap', 'Singapore', '2026-02-07 04:00:00', 'Minyak Olahan', 38000, 'selesai', 'Ekspor minyak olahan', 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(16, 14, 'Tanjung Priok, Jakarta', 'Ambon', '2026-02-17 07:00:00', 'Bahan Pokok', 6000, 'dijadwalkan', NULL, 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(17, 15, 'Makassar', 'Tanjung Priok, Jakarta', '2026-02-12 13:00:00', 'Nikel', 24000, 'berlayar', 'Nickel ore shipment', 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(18, 16, 'Tanjung Perak, Surabaya', 'Makassar', '2026-02-11 15:00:00', 'Pupuk', 9000, 'selesai', NULL, 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(19, 17, 'Tanjung Priok, Jakarta', 'Manado', '2026-02-18 06:00:00', 'Container Mixed', 17000, 'dijadwalkan', 'Rute via Makassar', 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(20, 2, 'Tanjung Priok, Jakarta', 'Belawan, Medan', '2026-02-16 10:00:00', 'Mesin Industri', 22000, 'dijadwalkan', NULL, 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(21, 20, 'Tanjung Priok, Jakarta', 'Ternate', '2026-02-19 08:00:00', 'Bahan Bangunan', 6500, 'dijadwalkan', 'Proyek pembangunan pelabuhan', 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kedatangan`
--

CREATE TABLE `kedatangan` (
  `id` int(11) NOT NULL,
  `id_kapal` int(11) NOT NULL,
  `id_keberangkatan` int(11) DEFAULT NULL,
  `pelabuhan_asal` varchar(100) NOT NULL,
  `pelabuhan_tiba` varchar(100) NOT NULL,
  `tanggal_tiba` datetime NOT NULL,
  `tanggal_aktual_tiba` datetime DEFAULT NULL,
  `status` enum('menunggu','tiba','terlambat','batal') NOT NULL DEFAULT 'menunggu',
  `catatan` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kedatangan`
--

INSERT INTO `kedatangan` (`id`, `id_kapal`, `id_keberangkatan`, `pelabuhan_asal`, `pelabuhan_tiba`, `tanggal_tiba`, `tanggal_aktual_tiba`, `status`, `catatan`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Tanjung Priok, Jakarta', 'Tanjung Perak, Surabaya', '2026-02-12 14:00:00', '2026-02-12 13:45:00', 'tiba', 'Tiba lebih awal 15 menit', 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(2, 2, 2, 'Tanjung Perak, Surabaya', 'Makassar', '2026-02-13 18:00:00', '2026-02-13 19:30:00', 'terlambat', 'Terlambat karena cuaca buruk', 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(3, 8, 9, 'Singapore', 'Tanjung Priok, Jakarta', '2026-02-11 08:00:00', '2026-02-11 08:10:00', 'tiba', NULL, 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(4, 10, 11, 'Belawan, Medan', 'Batam', '2026-02-09 16:00:00', '2026-02-09 15:50:00', 'tiba', 'Lancar tanpa hambatan', 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(5, 13, 14, 'Cilacap', 'Singapore', '2026-02-09 10:00:00', '2026-02-09 10:00:00', 'tiba', 'Tepat waktu', 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(6, 16, 17, 'Tanjung Perak, Surabaya', 'Makassar', '2026-02-13 06:00:00', '2026-02-13 05:45:00', 'tiba', NULL, 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(7, 3, 3, 'Belawan, Medan', 'Tanjung Priok, Jakarta', '2026-02-15 20:00:00', NULL, 'menunggu', 'Estimasi tiba malam', 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(8, 4, 4, 'Balikpapan', 'Tanjung Priok, Jakarta', '2026-02-16 12:00:00', NULL, 'menunggu', NULL, 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(9, 11, 12, 'Tanjung Priok, Jakarta', 'Kupang', '2026-02-17 08:00:00', NULL, 'menunggu', 'Rute via Surabaya', 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(10, 15, 16, 'Makassar', 'Tanjung Priok, Jakarta', '2026-02-16 06:00:00', NULL, 'menunggu', 'Membawa nikel 24000 ton', 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(11, 1, 5, 'Tanjung Priok, Jakarta', 'Banjarmasin', '2026-02-17 15:00:00', NULL, 'menunggu', NULL, 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(12, 5, 6, 'Makassar', 'Sorong', '2026-02-17 22:00:00', NULL, 'menunggu', NULL, 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(13, 6, 7, 'Tanjung Priok, Jakarta', 'Pontianak', '2026-02-18 10:00:00', NULL, 'menunggu', NULL, 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(14, 7, 8, 'Tanjung Perak, Surabaya', 'Bitung', '2026-02-18 20:00:00', NULL, 'menunggu', 'Estimasi cuaca baik', 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(15, 9, 10, 'Tanjung Priok, Jakarta', 'Jayapura', '2026-02-21 12:00:00', NULL, 'menunggu', 'Perjalanan panjang 5 hari', 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(16, 12, 13, 'Port Klang, Malaysia', 'Tanjung Priok, Jakarta', '2026-02-17 08:00:00', NULL, 'menunggu', NULL, 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(17, 14, 15, 'Tanjung Priok, Jakarta', 'Ambon', '2026-02-21 14:00:00', NULL, 'menunggu', NULL, 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(18, 17, 18, 'Tanjung Priok, Jakarta', 'Manado', '2026-02-22 06:00:00', NULL, 'menunggu', 'Via Makassar transit', 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(19, 2, 19, 'Tanjung Priok, Jakarta', 'Belawan, Medan', '2026-02-19 18:00:00', NULL, 'menunggu', NULL, 3, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(20, 20, 20, 'Tanjung Priok, Jakarta', 'Ternate', '2026-02-23 10:00:00', NULL, 'menunggu', 'Proyek pelabuhan batch 1', 1, '2026-02-14 21:35:14', '2026-02-14 21:35:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `level` enum('admin','pimpinan','petugas') NOT NULL DEFAULT 'petugas',
  `foto` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `email`, `no_telepon`, `level`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$liJJ2q4wEfrTv1d/DudTfetthrsSsXRjWpZXbBGe3r061V2KSslV2', 'Administrator', 'admin@itlshipping.com', '', 'admin', NULL, '2026-02-14 21:26:01', '2026-02-14 22:02:13'),
(2, 'pimpinan', '$2y$10$a7D4ydr7hjpnRfJvCujDi.ae/fjoA55zJW3bckfHWm01bGWC57MUK', 'Direktur Utama', 'pimpinan@itlshipping.com', '', 'pimpinan', NULL, '2026-02-14 21:26:01', '2026-02-14 22:01:50'),
(3, 'petugas', '$2y$10$w.k4D81v9Rz9w5spExJqQuwlj2xQF7ka0/CDCM5mtuLEpY1AWgBW6', 'Petugas Operasional', 'petugas@itlshipping.com', '', 'petugas', NULL, '2026-02-14 21:26:01', '2026-02-14 22:01:40'),
(4, 'budi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Budi Santoso', 'budi@itlshipping.com', '081234567001', 'petugas', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(5, 'siti', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Rahayu', 'siti@itlshipping.com', '081234567002', 'petugas', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(6, 'agus', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Agus Hermawan', 'agus@itlshipping.com', '081234567003', 'petugas', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(7, 'dewi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dewi Lestari', 'dewi@itlshipping.com', '081234567004', 'petugas', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(8, 'rizky', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Rizky Pratama', 'rizky@itlshipping.com', '081234567005', 'petugas', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(9, 'diana', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Diana Putri', 'diana@itlshipping.com', '081234567006', 'pimpinan', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(10, 'hendra', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Hendra Wijaya', 'hendra@itlshipping.com', '081234567007', 'admin', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(11, 'nina', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nina Marlina', 'nina@itlshipping.com', '081234567008', 'petugas', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(12, 'wahyu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Wahyu Hidayat', 'wahyu@itlshipping.com', '081234567009', 'petugas', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(13, 'rina', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Rina Susanti', 'rina@itlshipping.com', '081234567010', 'petugas', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(14, 'anton', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Anton Sugiarto', 'anton@itlshipping.com', '081234567011', 'petugas', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(15, 'maya', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Maya Anggraeni', 'maya@itlshipping.com', '081234567012', 'pimpinan', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(16, 'joko', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Joko Widodo', 'joko@itlshipping.com', '081234567013', 'petugas', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(17, 'fitri', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Fitri Handayani', 'fitri@itlshipping.com', '081234567014', 'petugas', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(18, 'dani', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dani Firmansyah', 'dani@itlshipping.com', '081234567015', 'petugas', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(19, 'lina', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lina Marliana', 'lina@itlshipping.com', '081234567016', 'petugas', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14'),
(20, 'rudi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Rudi Setiawan', 'rudi@itlshipping.com', '081234567017', 'petugas', NULL, '2026-02-14 21:35:14', '2026-02-14 21:35:14');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kapal`
--
ALTER TABLE `kapal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_kapal` (`kode_kapal`);

--
-- Indeks untuk tabel `keberangkatan`
--
ALTER TABLE `keberangkatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_keberangkatan_kapal` (`id_kapal`),
  ADD KEY `fk_keberangkatan_user` (`created_by`);

--
-- Indeks untuk tabel `kedatangan`
--
ALTER TABLE `kedatangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kedatangan_kapal` (`id_kapal`),
  ADD KEY `fk_kedatangan_keberangkatan` (`id_keberangkatan`),
  ADD KEY `fk_kedatangan_user` (`created_by`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kapal`
--
ALTER TABLE `kapal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `keberangkatan`
--
ALTER TABLE `keberangkatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `kedatangan`
--
ALTER TABLE `kedatangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `keberangkatan`
--
ALTER TABLE `keberangkatan`
  ADD CONSTRAINT `fk_keberangkatan_kapal` FOREIGN KEY (`id_kapal`) REFERENCES `kapal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_keberangkatan_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kedatangan`
--
ALTER TABLE `kedatangan`
  ADD CONSTRAINT `fk_kedatangan_kapal` FOREIGN KEY (`id_kapal`) REFERENCES `kapal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kedatangan_keberangkatan` FOREIGN KEY (`id_keberangkatan`) REFERENCES `keberangkatan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kedatangan_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
