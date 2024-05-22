-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2024 at 10:19 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_penjualan_kerupuk_2601`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `activityID` int(11) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `name_user` varchar(255) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`activityID`, `activity`, `name_user`, `nama_barang`, `created_at`) VALUES
(1, 'Add Master Barang', 'admin', 'Kerupuk Singkong', '2024-01-25 15:54:47'),
(2, 'Add Master Barang', 'admin', 'test kerupuk', '2024-01-30 02:14:38'),
(3, 'Add Master Barang', 'admin', 'kerupuk koin', '2024-03-07 07:04:51'),
(4, 'Add Master Barang', 'admin', 'kerupuk Kecipir', '2024-03-07 07:07:37'),
(5, 'Add Master Barang', 'admin', 'Bapasit', '2024-05-21 03:33:28'),
(6, 'Add Master Barang', 'admin', 'Bapasit', '2024-05-21 03:35:14'),
(7, 'Add Master Barang', 'admin', 'Bapasit', '2024-05-21 03:39:17'),
(8, 'Add Master Barang', 'admin', 'Bapasit', '2024-05-21 03:40:44'),
(9, 'Update Master Barang', 'admin', 'Kerupuk 3', '2024-05-21 03:44:14'),
(10, 'Add Master Barang', 'admin', 'Bapasit', '2024-05-21 03:44:35'),
(11, 'Add Master Barang', 'admin', 'Bapasit', '2024-05-21 03:50:26'),
(12, 'Add Master Barang', 'admin', 'tes', '2024-05-21 06:02:19'),
(13, 'Update Master Barang', 'admin', 'Kerupuk Kulit', '2024-05-21 06:02:52');

-- --------------------------------------------------------

--
-- Table structure for table `master_barang`
--

CREATE TABLE `master_barang` (
  `kerupukID` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `stok` int(20) DEFAULT NULL,
  `harga_beli` int(20) NOT NULL,
  `harga_jual` int(20) NOT NULL,
  `gambar_barang` varchar(255) DEFAULT NULL,
  `kategori_barang` int(11) NOT NULL,
  `created_user_id` int(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_barang`
--

INSERT INTO `master_barang` (`kerupukID`, `nama_barang`, `stok`, `harga_beli`, `harga_jual`, `gambar_barang`, `kategori_barang`, `created_user_id`, `created_at`, `updated_at`) VALUES
(7, 'Kerupuk Kulit', 35, 1250, 1438, 'img1705487662.jpg', 0, 3, '2024-01-17 10:34:22', '2024-05-21 06:02:52'),
(11, 'Kerupuk udang', 16, 1500, 1650, 'img1705566036.jpg', 0, 3, '2024-01-18 08:20:07', '2024-03-07 06:29:31'),
(13, 'kerupuk Palembang', 176, 1000, 1100, 'img1705942652.jpg', 0, 3, '2024-01-22 16:57:32', '2024-01-25 14:21:24'),
(27, 'Kerupuk 3', 123, 1250, 1375, 'img1716263054.png', 0, 3, '2024-01-24 11:52:32', '2024-05-21 03:44:14'),
(31, 'Kerupuk Singkong', 121, 1250, 1375, 'img1706198087.jpg', 0, 3, '2024-01-25 15:54:47', '2024-03-07 06:31:37'),
(32, 'test kerupuk', 196, 3000, 3360, 'img1706580878.jpg', 0, 3, '2024-01-30 02:14:38', '2024-01-30 02:28:58'),
(33, 'kerupuk koin', 30, 4000, 4800, 'gambar-default.png', 0, 2, '2024-03-07 07:04:51', '2024-03-07 07:04:51'),
(34, 'kerupuk Kecipir', 37, 4000, 6000, 'img1709795257.jpg', 0, 3, '2024-03-07 07:07:37', '2024-03-07 07:08:35'),
(40, 'Bapasit', 5, 7200, 9000, 'gambar-default.png', 0, 3, '2024-05-21 03:50:26', '2024-05-21 03:50:26'),
(41, 'tes', 5, 4000, 6000, 'gambar-default.png', 0, 3, '2024-05-21 06:02:19', '2024-05-21 06:02:19');

-- --------------------------------------------------------

--
-- Table structure for table `master_vendor`
--

CREATE TABLE `master_vendor` (
  `vendor_id` int(11) NOT NULL,
  `kode_vendor` varchar(15) NOT NULL,
  `nama_vendor` varchar(200) NOT NULL,
  `alamat` varchar(250) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `created_by` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_vendor`
--

INSERT INTO `master_vendor` (`vendor_id`, `kode_vendor`, `nama_vendor`, `alamat`, `no_telp`, `created_date`, `created_by`) VALUES
(1, '898', '898 Bangka', 'Graha Raya', NULL, '2024-05-22 05:34:54', '3');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_01_26_034608_add_verification_token_to_users', 2);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `transaksiID` int(11) NOT NULL,
  `kerupukID` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `modal` int(11) NOT NULL,
  `satuan` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`transaksiID`, `kerupukID`, `nama_barang`, `qty`, `modal`, `satuan`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 7, 'Kerupuk Kulit', 10, 1250, 1375, 13750, '2024-01-24 15:15:14', NULL),
(2, 7, 'Kerupuk Kulit', 10, 1250, 1375, 13750, '2024-01-25 15:48:41', NULL),
(3, 7, 'Kerupuk Kulit', 12, 1250, 1375, 16500, '2024-01-23 16:22:29', NULL),
(4, 7, 'Kerupuk Kulit', 13, 1250, 1375, 17875, '2024-01-25 17:50:08', NULL),
(5, 32, 'test kerupuk', 4, 3000, 3360, 13440, '2024-01-30 02:28:58', NULL),
(6, 11, 'Kerupuk udang', 4, 1500, 1650, 6600, '2024-03-07 06:29:31', NULL),
(7, 31, 'Kerupuk Singkong', 2, 1250, 1375, 2750, '2024-03-07 06:29:45', NULL),
(8, 31, 'Kerupuk Singkong', 4, 1250, 1375, 5500, '2024-03-07 06:31:37', NULL),
(9, 11, 'Kerupuk udang', 4, 1500, 1650, 6600, '2024-03-03 06:29:31', NULL),
(10, 7, 'Kerupuk Kulit', 13, 1250, 1375, 17875, '2024-03-03 17:50:08', NULL),
(11, 32, 'test kerupuk', 12, 3000, 3360, 13440, '2024-03-05 02:28:58', NULL),
(12, 34, 'kerupuk Kecipir', 3, 4000, 6000, 18000, '2024-03-06 07:08:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `verification_token`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin123', 'admin@example.com', NULL, '$2y$12$hzPbbdBSj5JcHfmoXYkuTent.S36AyAEvZr.txh90Y5tIH0dZ8J/.', NULL, NULL, NULL, NULL),
(3, 'admin', 'admin', 'admin@contoh.com', NULL, '$2y$12$4padLm7pwHAP0Iy.vZQ8PefTwA/YKffbdEezvnb3p7d.yP125JMg2', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`activityID`);

--
-- Indexes for table `master_barang`
--
ALTER TABLE `master_barang`
  ADD PRIMARY KEY (`kerupukID`),
  ADD UNIQUE KEY `nama_barang_primary` (`nama_barang`);

--
-- Indexes for table `master_vendor`
--
ALTER TABLE `master_vendor`
  ADD PRIMARY KEY (`vendor_id`),
  ADD UNIQUE KEY `kode_vendor` (`kode_vendor`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`transaksiID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `activityID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `master_barang`
--
ALTER TABLE `master_barang`
  MODIFY `kerupukID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `master_vendor`
--
ALTER TABLE `master_vendor`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `transaksiID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
