-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2024 at 03:58 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `investment`
--

-- --------------------------------------------------------

--
-- Table structure for table `all_users`
--

CREATE TABLE `all_users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `full_name` varchar(30) NOT NULL,
  `cell_number` int(10) NOT NULL,
  `bank_name` varchar(20) NOT NULL,
  `account_number` int(15) NOT NULL,
  `referral_code` int(5) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `confirm_password` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `all_users`
--

INSERT INTO `all_users` (`id`, `username`, `full_name`, `cell_number`, `bank_name`, `account_number`, `referral_code`, `password`, `confirm_password`) VALUES
(1, '12255', 'jan', 74441222, 'capitec', 1408442890, 25512, '5261', 5261),
(2, '61094', 'jan', 744412567, 'capitec', 1408442890, 12255, '5261', 5261),
(3, '68144', 'Khothi', 680911271, 'capitec', 1408442890, 12255, '5261', 5261),
(4, '64214', 'jan', 744412511, 'capitec', 1408442890, 0, '5261', 5261),
(5, '32599', 'jannn', 722412567, 'capitec', 1408442890, 12255, '5261', 5261),
(6, '61195', 'jan', 744531262, 'capitec', 1408442890, 32599, '5261', 5261),
(7, '12485', 'mapula', 2147483647, 'capitec', 58245566, 12255, '5261', 5261),
(11, 'admin', '', 0, '', 0, 0, '5261Jan', 0),
(12, '61947', 'shimmy', 722412561, 'capitec', 1408442890, 12255, '5261', 5261);

-- --------------------------------------------------------

--
-- Table structure for table `office`
--

CREATE TABLE `office` (
  `id` int(11) NOT NULL,
  `username` varchar(10) NOT NULL,
  `password` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `office`
--

INSERT INTO `office` (`id`, `username`, `password`) VALUES
(1, 'admin', '5261Jan.');

-- --------------------------------------------------------

--
-- Table structure for table `queued_gh`
--

CREATE TABLE `queued_gh` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `amount_requested` decimal(10,2) NOT NULL,
  `cell_number` varchar(20) NOT NULL,
  `bank_account` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `order_date` datetime DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queued_gh`
--

INSERT INTO `queued_gh` (`id`, `username`, `amount_requested`, `cell_number`, `bank_account`, `bank_name`, `status`, `order_date`, `timestamp`) VALUES
(1, '12255', 2000.00, '74441222', '', '', 'Pending', NULL, '2024-01-31 14:49:09'),
(2, '12255', 2000.00, '74441222', '', '', 'approved', NULL, '2024-01-31 14:49:09'),
(3, '12255', 2000.00, '74441222', '', '', 'approved', NULL, '2024-01-31 14:49:09'),
(4, '12255', 3000.00, '74441222', '', '', 'approved', '2024-01-31 13:17:23', '2024-01-31 14:49:09'),
(5, '12255', 1200.00, '74441222', '', '', 'approved', '2024-01-31 13:49:44', '2024-01-31 14:49:09'),
(6, '12255', 1000.00, '74441222', '', '', 'approved', '2024-01-31 14:05:34', '2024-01-31 14:49:09'),
(8, '32599', 1250.00, '722412567', '', '', 'approved', '2024-01-31 14:15:15', '2024-01-31 14:49:09'),
(9, '12255', 1000.00, '74441222', '', '', 'approved', '2024-01-31 15:36:09', '2024-01-31 14:49:09'),
(10, '12255', 1000.00, '74441222', '', '', 'approved', '2024-01-31 15:36:55', '2024-01-31 14:49:09'),
(11, '32599', 2000.00, '722412567', '', '', 'approved', '2024-01-31 15:44:36', '2024-01-31 14:49:09'),
(12, '68144', 2000.00, '680911271', '', '', 'approved', '2024-01-31 15:57:50', '2024-01-31 14:49:09'),
(13, '61195', 2000.00, '744531262', '', '', 'approved', '2024-01-31 16:10:19', '2024-01-31 14:49:09'),
(14, '12485', 2000.00, '2147483647', '', '', 'approved', '2024-02-02 09:07:08', '2024-02-02 07:07:08');

-- --------------------------------------------------------

--
-- Table structure for table `queued_ph`
--

CREATE TABLE `queued_ph` (
  `id` int(11) NOT NULL,
  `username` int(5) NOT NULL,
  `amount_requested` int(5) NOT NULL,
  `cell_number` int(10) NOT NULL,
  `order_date` datetime DEFAULT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queued_ph`
--

INSERT INTO `queued_ph` (`id`, `username`, `amount_requested`, `cell_number`, `order_date`, `status`) VALUES
(22, 32599, 5000, 722412567, '2024-01-31 16:36:39', ''),
(24, 12255, 2000, 74441222, '2024-02-06 10:04:14', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `all_users`
--
ALTER TABLE `all_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `office`
--
ALTER TABLE `office`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `queued_gh`
--
ALTER TABLE `queued_gh`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `queued_ph`
--
ALTER TABLE `queued_ph`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `all_users`
--
ALTER TABLE `all_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `office`
--
ALTER TABLE `office`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `queued_gh`
--
ALTER TABLE `queued_gh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `queued_ph`
--
ALTER TABLE `queued_ph`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
