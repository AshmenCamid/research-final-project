-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2025 at 01:58 AM
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
-- Database: `project_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `worker_id` varchar(50) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `shift_period` enum('AM','PM') NOT NULL DEFAULT 'AM',
  `time_out` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `worker_id`, `fullname`, `date`, `time_in`, `shift_period`, `time_out`) VALUES
(58, '2025001', 'Ashmen S. Camid', '2025-03-23', '14:46:14', 'PM', '14:59:53'),
(59, '2025002', 'Gabriel B. Pahaganas', '2025-03-23', '15:04:53', 'PM', '15:07:45'),
(60, '2025003', 'Wahida S. Camid', '2025-03-23', '15:12:36', 'PM', '15:13:29'),
(61, '2025004', 'Ashy Sultan', '2025-03-23', '15:15:14', 'PM', '18:56:04'),
(62, '2025001', 'Ashmen S. Camid', '2025-03-24', '13:25:58', 'PM', '13:28:33');

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `worker_id` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `salary_status` varchar(255) NOT NULL,
  `payment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `worker_id`, `fullname`, `role`, `salary_status`, `payment_date`) VALUES
(19, '2025002', 'Gabriel B. Pahaganas', 'N/A', 'Received', '2025-03-23'),
(20, '2025003', 'Wahida S. Camid', 'N/A', 'Not Yet Receive', '0000-00-00'),
(21, '2025004', 'Ashy Sultan', 'N/A', 'Not Yet Receive', '0000-00-00'),
(22, '2025005', 'Hurry Kim R. Semaña', 'N/A', 'Not Yet Receive', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `full_name`, `role`, `contact_number`, `password`) VALUES
(202506, 'admin', 'admin', 'admin', '09553556487', '$2y$10$WIS1cwt1XSMmA5fi9Q8vROjeKvUyo.RdPntNFyEmbiRRaV1ymffZy');

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE `workers` (
  `id` int(11) NOT NULL,
  `worker_id` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `schedule` varchar(255) NOT NULL,
  `salary_per_hour` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workers`
--

INSERT INTO `workers` (`id`, `worker_id`, `fullname`, `role`, `schedule`, `salary_per_hour`, `status`) VALUES
(21, '2025001', 'Ashmen S. Camid', 'N/A', '8 AM - 12 PM and 1 PM - 5 PM', 0.00, 'enabled'),
(22, '2025002', 'Gabriel B. Pahaganas', 'N/A', '8 AM - 12 PM and 1 PM - 5 PM', 0.00, 'enabled'),
(23, '2025003', 'Wahida S. Camid', 'N/A', '8 AM - 12 PM and 1 PM - 5 PM', 0.00, 'enabled'),
(24, '2025004', 'Ashy Sultan', 'N/A', '8 AM - 12 PM and 1 PM - 5 PM', 0.00, 'enabled'),
(25, '2025005', 'Hurry Kim R. Semaña', 'N/A', '8 AM - 12 PM and 1 PM - 5 PM', 0.00, 'enabled');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202507;

--
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
