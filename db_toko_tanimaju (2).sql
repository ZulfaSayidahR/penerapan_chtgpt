-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2025 at 01:51 PM
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
-- Database: `db_toko_tanimaju`
--

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `harga_satuan` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `kode_barang`, `nama_barang`, `harga_satuan`, `stok`) VALUES
(3, '0002', 'Bibit padi', 20000.00, -1),
(4, '0001', 'Pupuk Super', 25000.00, 10),
(5, '0003', 'Pupuk Original', 15000.00, 0),
(8, '0006', 'Pupuk Sp36', 30000.00, 7),
(9, '0005', 'Pupuk Urea', 20000.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_penjualan`
--

CREATE TABLE `transaksi_penjualan` (
  `id_transaksi` int(11) NOT NULL,
  `kode_barang` varchar(50) DEFAULT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` varchar(10) NOT NULL,
  `total_harga` varchar(10) NOT NULL,
  `tanggal_penjualan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_penjualan`
--

INSERT INTO `transaksi_penjualan` (`id_transaksi`, `kode_barang`, `nama_barang`, `jumlah`, `harga_satuan`, `total_harga`, `tanggal_penjualan`) VALUES
(8, '0002', 'Bibit padi', 12, '', '240000', '2024-11-14'),
(9, '0003', 'Pupuk Original', 3, '', '45000', '2024-11-15'),
(10, '0001', 'Pupuk Super', 5, '', '125000', '2024-11-15'),
(11, '0006', 'Pupuk Sp36', 1, '', '30000', '2024-11-15'),
(13, '0002', 'Bibit padi', 3, '', '60000', '2024-11-19'),
(14, '0002', 'Bibit padi', 6, '', '120000', '2024-11-19'),
(15, '0002', 'Bibit padi', 2, '', '40000', '2024-11-19'),
(16, '0003', 'Pupuk Original', 1, '', '15000', '2024-11-19'),
(17, '0002', 'Bibit padi', 1, '', '20000', '2024-11-19'),
(41, NULL, '', 0, '', '80000', '2024-11-24'),
(42, NULL, '', 0, '', '30000', '2024-11-24'),
(43, NULL, '', 0, '', '20000', '2024-11-24'),
(45, NULL, '', 0, '', '15000', '2024-11-24'),
(46, NULL, '', 0, '', '50000', '2024-11-24'),
(47, NULL, '', 0, '', '60000', '2024-11-24'),
(48, NULL, '', 0, '', '15000', '2024-11-26'),
(49, NULL, '', 0, '', '25000', '2024-11-29'),
(50, NULL, '', 0, '', '90000', '2024-11-29'),
(51, NULL, '', 0, '', '150000', '2024-11-29'),
(52, NULL, '', 0, '', '40000', '2024-12-13'),
(53, NULL, '', 0, '', '160000', '2024-12-13'),
(54, NULL, '', 0, '', '60000', '2025-01-01'),
(55, NULL, '', 0, '', '100000', '2025-01-01'),
(56, NULL, '', 0, '', '60000', '2025-01-06'),
(57, NULL, '', 0, '', '30000', '2025-01-13'),
(58, NULL, '', 0, '', '40000', '2025-01-13'),
(59, NULL, '', 0, '', '80000', '2025-05-15'),
(60, NULL, '', 0, '', '0', '2025-05-15'),
(61, NULL, '', 0, '', '0', '2025-05-15'),
(62, NULL, '', 0, '', '0', '2025-05-15'),
(63, NULL, '', 0, '', '0', '2025-05-15'),
(64, NULL, '', 0, '', '0', '2025-05-15'),
(65, NULL, '', 0, '', '75000', '2025-05-15'),
(66, NULL, '', 0, '', '0', '2025-05-15'),
(67, NULL, '', 0, '', '0', '2025-05-15'),
(68, NULL, '', 0, '', '0', '2025-05-15'),
(69, NULL, '', 0, '', '0', '2025-05-15'),
(70, NULL, '', 0, '', '0', '2025-05-15'),
(71, NULL, '', 0, '', '0', '2025-05-15'),
(72, NULL, '', 0, '', '0', '2025-05-15'),
(73, NULL, '', 0, '', '75000', '2025-05-15'),
(74, NULL, '', 0, '', '30000', '2025-05-15'),
(75, NULL, '', 0, '', '0', '2025-05-15'),
(76, NULL, '', 0, '', '40000', '2025-05-15'),
(77, NULL, '', 0, '', '0', '2025-05-15'),
(78, NULL, '', 0, '', '20000', '2025-05-15'),
(79, NULL, '', 0, '', '0', '2025-05-15'),
(80, NULL, '', 0, '', '0', '2025-05-15'),
(81, NULL, '', 0, '', '40000', '2025-05-15'),
(82, NULL, '', 0, '', '0', '2025-05-15'),
(83, NULL, '', 0, '', '0', '2025-05-15'),
(84, NULL, '', 0, '', '0', '2025-05-15'),
(85, NULL, '', 0, '', '50000', '2025-05-15'),
(86, NULL, '', 0, '', '0', '2025-05-15'),
(87, NULL, '', 0, '', '0', '2025-05-15'),
(88, NULL, '', 0, '', '0', '2025-05-15'),
(89, NULL, '', 0, '', '0', '2025-05-15'),
(90, NULL, '', 0, '', '0', '2025-05-15'),
(91, NULL, '', 0, '', '0', '2025-05-15'),
(92, NULL, '', 0, '', '0', '2025-05-15'),
(93, NULL, '', 0, '', '100000', '2025-05-16'),
(94, NULL, '', 0, '', '0', '2025-05-16');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_penjualan_detail`
--

CREATE TABLE `transaksi_penjualan_detail` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `kode_barang` varchar(50) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `subtotal` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_penjualan_detail`
--

INSERT INTO `transaksi_penjualan_detail` (`id_detail`, `id_transaksi`, `kode_barang`, `jumlah`, `subtotal`) VALUES
(1, 43, '0002', 1, 20000.00),
(2, 45, '0003', 1, 15000.00),
(3, 46, '0001', 2, 50000.00),
(4, 47, '0002', 1, 20000.00),
(5, 47, '0005', 2, 40000.00),
(6, 48, '0003', 1, 15000.00),
(7, 49, '0001', 1, 25000.00),
(8, 50, '0003', 6, 90000.00),
(9, 51, '0003', 10, 150000.00),
(10, 52, '0002', 2, 40000.00),
(11, 53, '0002', 5, 100000.00),
(12, 53, '0006', 2, 60000.00),
(13, 54, '0002', 3, 60000.00),
(14, 55, '0002', 5, 100000.00),
(15, 56, '0006', 2, 60000.00),
(16, 57, '0003', 2, 30000.00),
(17, 58, '0002', 2, 40000.00),
(18, 59, '0002', 4, 80000.00),
(19, 65, '0003', 5, 75000.00),
(20, 73, '0001', 3, 75000.00),
(21, 74, '0003', 2, 30000.00),
(22, 76, '0002', 2, 40000.00),
(23, 78, '0002', 1, 20000.00),
(24, 81, '0002', 2, 40000.00),
(25, 85, '0001', 2, 50000.00),
(26, 93, '0002', 5, 100000.00);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Kasir') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$HJ0WH9bKp/4yD8M3pnbWqueFP8wcq7sOFBOODQFa8PToJM3AzB18.', 'Admin'),
(2, 'kasir', '$2y$10$q2l5NJOFtPjbk0PuaRJcMO.sYiPuCgNZ4BA87Oj3P4tgc1FCV2fey', 'Kasir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`);

--
-- Indexes for table `transaksi_penjualan`
--
ALTER TABLE `transaksi_penjualan`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `kode_barang` (`kode_barang`);

--
-- Indexes for table `transaksi_penjualan_detail`
--
ALTER TABLE `transaksi_penjualan_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `kode_barang` (`kode_barang`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transaksi_penjualan`
--
ALTER TABLE `transaksi_penjualan`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `transaksi_penjualan_detail`
--
ALTER TABLE `transaksi_penjualan_detail`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaksi_penjualan`
--
ALTER TABLE `transaksi_penjualan`
  ADD CONSTRAINT `transaksi_penjualan_ibfk_1` FOREIGN KEY (`kode_barang`) REFERENCES `produk` (`kode_barang`);

--
-- Constraints for table `transaksi_penjualan_detail`
--
ALTER TABLE `transaksi_penjualan_detail`
  ADD CONSTRAINT `transaksi_penjualan_detail_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi_penjualan` (`id_transaksi`),
  ADD CONSTRAINT `transaksi_penjualan_detail_ibfk_2` FOREIGN KEY (`kode_barang`) REFERENCES `produk` (`kode_barang`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
