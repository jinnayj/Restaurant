-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2026 at 04:25 AM
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
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `table_id` int(11) NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `status` enum('confirmed','cancelled') DEFAULT 'confirmed',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` int(11) NOT NULL,
  `table_number` varchar(10) NOT NULL,
  `seat` int(11) NOT NULL,
  `position_x` int(11) NOT NULL,
  `position_y` int(11) NOT NULL,
  `status` enum('available','reserved','using') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `table_number`, `seat`, `position_x`, `position_y`, `status`) VALUES
(1, '1', 2, 0, 0, 'available'),
(2, '2', 2, 0, 0, 'available'),
(3, '3', 2, 0, 0, 'available'),
(4, '4', 2, 0, 0, 'using'),
(5, '5', 4, 0, 0, 'available'),
(6, '6', 4, 0, 0, 'available'),
(7, '7', 4, 0, 0, 'available'),
(8, '8', 4, 0, 0, 'using'),
(9, '9', 4, 0, 0, 'available'),
(10, '10', 4, 0, 0, 'available'),
(11, '11', 6, 0, 0, 'available'),
(12, '12', 6, 0, 0, 'available'),
(13, '13', 6, 0, 0, 'available'),
(14, '14', 6, 0, 0, 'available'),
(15, '15', 6, 0, 0, 'available'),
(16, '16', 8, 0, 0, 'available'),
(17, '17', 8, 0, 0, 'available'),
(18, '18', 8, 0, 0, 'available'),
(19, '19', 8, 0, 0, 'available'),
(20, '20', 8, 0, 0, 'available'),
(21, '21', 2, 0, 0, 'available'),
(22, '22', 2, 0, 0, 'available'),
(23, '23', 2, 0, 0, 'available'),
(24, '24', 4, 0, 0, 'available'),
(25, '25', 4, 0, 0, 'available'),
(26, '26', 4, 0, 0, 'available'),
(27, '27', 6, 0, 0, 'available'),
(28, '28', 6, 0, 0, 'available'),
(29, '29', 6, 0, 0, 'available'),
(30, '30', 8, 0, 0, 'available'),
(31, '31', 10, 0, 0, 'available'),
(32, '32', 10, 0, 0, 'available'),
(33, '33', 10, 0, 0, 'available'),
(34, '34', 10, 0, 0, 'available'),
(35, '35', 10, 0, 0, 'available'),
(36, '36', 12, 0, 0, 'available'),
(37, '37', 12, 0, 0, 'available'),
(38, '38', 12, 0, 0, 'available'),
(39, '39', 12, 0, 0, 'available'),
(40, '40', 12, 0, 0, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('store_owner','staff') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'staff', '8521', 'staff', '2025-12-24 06:41:21'),
(5, 'admin', '1234', 'store_owner', '2025-12-24 06:42:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `table_id` (`table_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
