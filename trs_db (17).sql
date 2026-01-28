-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2026 at 09:34 AM
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
-- Database: `trs_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id_ct` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id_ct`, `name`, `email`, `password`, `created_at`) VALUES
(5, 'jin', 'thanthipmingkwan@gmail.com', '18091996', '2026-01-22 03:33:10');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id_booking` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `table_id` int(11) NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `slip_image` varchar(255) DEFAULT NULL,
  `status` enum('pending_payment','waiting_confirm','confirmed','using','completed','cancelled') DEFAULT 'pending_payment',
  `slip` varchar(255) DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id_booking`, `customer_name`, `phone`, `table_id`, `reservation_date`, `reservation_time`, `slip_image`, `status`, `slip`, `completed_at`, `created_by`, `created_at`) VALUES
(102, 'เต้', '0837541235', 1, '2026-01-28', '18:30:00', '1769587616_mp4.mp4', '', NULL, NULL, NULL, '2026-01-28 08:06:43'),
(103, '', NULL, 3, '2026-01-28', '00:00:00', NULL, '', NULL, NULL, NULL, '2026-01-28 08:17:40'),
(104, '', NULL, 1, '2026-01-28', '00:00:00', NULL, '', NULL, NULL, NULL, '2026-01-28 08:21:53'),
(105, '', NULL, 1, '2026-01-28', '00:00:00', NULL, '', NULL, NULL, NULL, '2026-01-28 08:23:04'),
(106, 'เต้', '0837541235', 5, '2026-01-28', '16:30:00', NULL, '', NULL, NULL, NULL, '2026-01-28 08:23:20'),
(107, '', NULL, 1, '2026-01-28', '00:00:00', NULL, '', NULL, NULL, NULL, '2026-01-28 08:26:02'),
(108, '', NULL, 2, '2026-01-28', '00:00:00', NULL, '', NULL, NULL, NULL, '2026-01-28 08:27:04'),
(109, '', NULL, 2, '2026-01-28', '00:00:00', NULL, 'using', NULL, NULL, NULL, '2026-01-28 08:31:11'),
(110, 'เต้', '0837541235', 3, '2026-01-28', '16:30:00', '1769589117_mp4 (2).mp4', 'confirmed', NULL, NULL, NULL, '2026-01-28 08:31:38'),
(111, 'เต้', '0837541235', 3, '2026-01-28', '16:30:00', '1769589201_mp4 (2).mp4', 'waiting_confirm', NULL, NULL, NULL, '2026-01-28 08:33:12');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id_show` int(11) NOT NULL,
  `table_number` varchar(10) NOT NULL,
  `seat` int(11) NOT NULL,
  `status` enum('available','reserved','using') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id_show`, `table_number`, `seat`, `status`) VALUES
(1, '1', 2, 'available'),
(2, '2', 2, 'available'),
(3, '3', 2, 'available'),
(4, '4', 4, 'available'),
(5, '5', 4, 'available'),
(6, '6', 4, 'available'),
(7, '7', 4, 'available'),
(8, '8', 2, 'available'),
(9, '9', 4, 'available'),
(10, '10', 4, 'available'),
(11, '11', 2, 'available'),
(12, '12', 6, 'available'),
(13, '13', 4, 'available'),
(14, '14', 4, 'available'),
(15, '15', 4, 'available'),
(16, '16', 4, 'available'),
(17, '17', 6, 'available'),
(18, '18', 6, 'available'),
(19, '19', 4, 'available'),
(20, '20', 4, 'available'),
(21, '21', 6, 'available'),
(22, '22', 8, 'available'),
(23, '23', 8, 'available'),
(24, '24', 8, 'available'),
(25, '25', 8, 'available'),
(26, '26', 4, 'available'),
(27, '27', 2, 'available'),
(28, '28', 2, 'available'),
(29, '29', 4, 'available'),
(30, '30', 4, 'available'),
(31, '31', 4, 'available'),
(32, '32', 4, 'available'),
(33, '33', 2, 'available'),
(34, '34', 8, 'available'),
(35, '35', 8, 'available'),
(36, '36', 4, 'available'),
(37, '37', 4, 'available'),
(38, '38', 4, 'available'),
(39, '39', 2, 'available'),
(40, '40', 2, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_ser` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('store_owner','staff') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_ser`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'staff', '1234', 'staff', '2025-12-24 06:41:21'),
(5, 'admin', '1234', 'store_owner', '2025-12-24 06:42:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_ct`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_booking`),
  ADD KEY `table_id` (`table_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id_show`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_ser`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id_ct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id_show` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_ser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id_show`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id_ser`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
