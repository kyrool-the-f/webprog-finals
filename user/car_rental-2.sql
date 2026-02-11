-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2026 at 04:16 PM
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
-- Database: `car_rental-2`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `logs_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `username` int(11) NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `cars_id` int(11) NOT NULL,
  `model_name` int(11) NOT NULL,
  `model_type` int(11) NOT NULL,
  `seater` varchar(11) NOT NULL,
  `plate_number` varchar(7) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`cars_id`, `model_name`, `model_type`, `seater`, `plate_number`, `status`) VALUES
(1, 1, 1, '2', 'LMQ-001', 'Available'),
(2, 2, 2, '4', 'VIO-001', 'Available'),
(3, 2, 2, '4', 'VIO-002', 'Available'),
(4, 2, 2, '4', 'VIO-003', 'Rented'),
(5, 2, 2, '4', 'VIO-004', 'Available'),
(6, 2, 2, '4', 'VIO-005', 'Available'),
(7, 2, 2, '4', 'VIO-006', 'Maintenance'),
(8, 2, 2, '4', 'VIO-007', 'Available'),
(9, 2, 2, '4', 'VIO-008', 'Available'),
(10, 2, 2, '4', 'VIO-009', 'Rented'),
(11, 2, 2, '4', 'VIO-010', 'Available'),
(12, 3, 3, '7', 'CRV-001', 'Available'),
(13, 3, 3, '7', 'CRV-002', 'Available'),
(14, 3, 3, '7', 'CRV-003', 'Available'),
(15, 3, 3, '7', 'CRV-004', 'Rented'),
(16, 3, 3, '7', 'CRV-005', 'Available'),
(17, 3, 3, '7', 'CRV-006', 'Available'),
(18, 3, 3, '7', 'CRV-007', 'Maintenance'),
(19, 3, 3, '7', 'CRV-008', 'Available'),
(20, 3, 3, '7', 'CRV-009', 'Available'),
(21, 3, 3, '7', 'CRV-010', 'Rented'),
(22, 4, 4, '10', 'HIA-001', 'Available'),
(23, 4, 4, '10', 'HIA-002', 'Available'),
(24, 4, 4, '10', 'HIA-003', 'Rented'),
(25, 4, 4, '10', 'HIA-004', 'Available'),
(26, 4, 4, '10', 'HIA-005', 'Available'),
(27, 4, 4, '10', 'HIA-006', 'Available'),
(28, 4, 4, '10', 'HIA-007', 'Maintenance'),
(29, 4, 4, '10', 'HIA-008', 'Rented'),
(30, 4, 4, '10', 'HIA-009', 'Available'),
(31, 4, 4, '10', 'HIA-010', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `car_model`
--

CREATE TABLE `car_model` (
  `model_id` int(11) NOT NULL,
  `car_model` varchar(100) NOT NULL,
  `type_model` varchar(100) NOT NULL,
  `seater_model` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_model`
--

INSERT INTO `car_model` (`model_id`, `car_model`, `type_model`, `seater_model`) VALUES
(1, 'Lightning McQueen', 'SPORTS', '2 seater'),
(2, 'VIOS', 'SEDAN', '3-4 seater'),
(3, 'CR-V', 'SUV', '5-7 seater'),
(4, 'Hiace', 'VAN', '8-10 seater');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us_forms`
--

CREATE TABLE `contact_us_forms` (
  `contact_us_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `service` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us_forms`
--

INSERT INTO `contact_us_forms` (`contact_us_id`, `full_name`, `email`, `contact_number`, `service`, `message`, `submitted_at`) VALUES
(1, 'Kyroll Vallester', 'kyroll@sample.com', '09923652379', 'Chauffeur Service', 'Hello I had a proper ride with your chauffeur, needless to say I will recommend this app with other people I know. Thanks. ', '2026-02-09 16:53:39');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_initial` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `first_name`, `middle_initial`, `last_name`, `address`, `position`, `status`) VALUES
(1, 'John', 'M.', 'Smith', '123 Main Street, Manila', 'Driver', 'Active'),
(2, 'Maria', 'R.', 'Garcia', '456 Oak Avenue, Quezon City', 'Mechanic', 'Active'),
(3, 'Carlos', NULL, 'Reyes', '789 Maple Drive, Makati', 'Manager', 'Active'),
(4, 'Angela', 'P.', 'Santos', '321 Pine Road, Pasig', 'Dispatcher', 'Inactive'),
(5, 'Michael', 'D.', 'Lopez', '654 Cedar Lane, Taguig', 'Driver', 'Active'),
(6, 'Rosa', 'A.', 'Cruz', '987 Birch Street, Caloocan', 'Accountant', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `orders_forms`
--

CREATE TABLE `orders_forms` (
  `form_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `vehicle_type` int(11) NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `pickup_location` varchar(100) NOT NULL,
  `pickup_datetime` datetime NOT NULL,
  `return_location` varchar(100) NOT NULL,
  `return_datetime` datetime NOT NULL,
  `drivers_license` mediumblob DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders_forms`
--

INSERT INTO `orders_forms` (`form_id`, `full_name`, `email`, `contact_number`, `vehicle_type`, `service_type`, `pickup_location`, `pickup_datetime`, `return_location`, `return_datetime`, `drivers_license`, `status`) VALUES
(1, 'Kyroll Vallester', 'kyroll@sample.com', '2147483647', 0, 'self-drive', 'Kadautan St. Manila', '2026-02-19 22:44:00', 'Kadautan St. Manila', '2026-02-21 22:44:00', 0x75706c6f6164732f6c6963656e7365732f363938396632626264663437335f6472697665722773206c6963656e73652e706e67, ''),
(2, 'Kyroll Vallester', 'kyroll@sample.com', '2147483647', 0, 'self-drive', 'Kadautan St. Manila', '2026-02-19 23:14:00', 'Kadautan St. Manila', '2026-02-21 23:14:00', 0x75706c6f6164732f6c6963656e7365732f363938396639653963373336665f6472697665722773206c6963656e73652e706e67, ''),
(3, 'Kyroll Vallester', 'kyroll@sample.com', '2147483647', 0, 'self-drive', 'Kadautan St. Manila', '2026-02-19 23:24:00', 'Kadautan St. Manila', '2026-02-21 23:24:00', 0x75706c6f6164732f6c6963656e7365732f363938396663323032653737345f6472697665722773206c6963656e73652e706e67, ''),
(4, 'Kyroll Vallester', 'kyroll@sample.com', '2147483647', 1, 'self-drive', 'Kadautan St. Manila', '2026-02-19 23:29:00', 'Kadautan St. Manila', '2026-02-21 23:29:00', 0x75706c6f6164732f6c6963656e7365732f363938396664373339643366335f6472697665722773206c6963656e73652e706e67, ''),
(5, 'Kyroll Vallester', 'kyroll@sample.com', '9923652379', 4, 'self-drive', 'Kadautan St. Manila', '2026-02-19 23:33:00', 'Kadautan St. Manila', '2026-02-21 23:33:00', 0x75706c6f6164732f6c6963656e7365732f363938396665356464373234645f6472697665722773206c6963656e73652e706e67, ''),
(6, 'Pransisko Bins', 'olaola76@gmail.com', '0911102848', 1, 'self-drive', 'Kadautan St. Manila', '2026-02-19 23:24:00', 'Kadautan St. Manila', '2026-05-19 23:24:00', '', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `orders_history`
--

CREATE TABLE `orders_history` (
  `form_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `vehicle_type` varchar(100) NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `pickup_location` varchar(100) NOT NULL,
  `pickup_datetime` datetime NOT NULL,
  `return_location` varchar(100) NOT NULL,
  `return_datetime` datetime NOT NULL,
  `drivers_license` mediumblob DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders_history`
--

INSERT INTO `orders_history` (`form_id`, `full_name`, `email`, `contact_number`, `vehicle_type`, `service_type`, `pickup_location`, `pickup_datetime`, `return_location`, `return_datetime`, `drivers_license`, `status`) VALUES
(1, 'Hell No', 'buyaka@gmail.com', '0928883841', '4', 'self-drive', 'Kadautan St. Louie', '2026-02-19 23:29:00', 'Kadautan St. Louie', '2026-02-21 23:29:00', '', 'Cancelled'),
(2, 'Yesh No', 'buyaka11@gmail.com', '0928883841', '4', 'self-drive', 'Kadautan St. Louie', '2026-02-19 23:29:00', 'Kadautan St. Louie', '2026-02-21 23:29:00', '', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_initial` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `middle_initial`, `last_name`, `email`, `password`) VALUES
(1, 'Chistron', 'B.', 'Besieger', 'chist@sample.com', '$2y$10$7s3EFopqYgElh1f5vtYk7uBSiz69g4HaljH/Cx4D8LB8qw19bRaK6'),
(2, 'Frigarland', 'G.', 'Gorosei', 'frigar@sample.com', '$2y$10$JFLnfpsuTDY3mdI6qHe6Vusc1YhB1tv6zaTSbmeY3vvkDliRH/aLy'),
(3, 'Ghonery', 'H.', 'Falfuentues', 'ghon@sample.com', '$2y$10$.EsoYf4qfb1W2PUZ6d4u4uunsze5v4hWJDbWG7xk6YK5Urj/7eTFu'),
(4, 'Kyroll', '', 'Vallester', 'kval@sample.com', '$2y$10$NFSEejvTQFn7Vye73mAHL.k4/pUneBnQjbl8EEUEGN9aKTMZc.ppy'),
(5, 'Phillip', '', 'Sahrento', 'phil@sample.com', '$2y$10$X3srk5LYSPtVzLCnb.x8i.LH7TlTnywG2b7zY4kQ3GlMu5qXnhcOa'),
(6, 'Tyrvaltous', 'P.', 'Trasdoneous', 'tyr@sampl.com', '$2y$10$NGnJtWwufO./G5dOytEwpOVjfnk91UN/9pMGg7.7gNPAX4JVA4W2i'),
(7, 'Quentin', 'K.', 'Tarantino', 'quent@sample.com', '$2y$10$iQHdbp1HotQzIlOP5gqh6OapmaonF6fkDlSw3qRrwIyHTfmwVC3aa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`logs_id`),
  ADD KEY `admin_id` (`admin_id`,`username`),
  ADD KEY `audit_fk_2` (`username`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`cars_id`);

--
-- Indexes for table `car_model`
--
ALTER TABLE `car_model`
  ADD PRIMARY KEY (`model_id`);

--
-- Indexes for table `contact_us_forms`
--
ALTER TABLE `contact_us_forms`
  ADD PRIMARY KEY (`contact_us_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `orders_forms`
--
ALTER TABLE `orders_forms`
  ADD PRIMARY KEY (`form_id`);

--
-- Indexes for table `orders_history`
--
ALTER TABLE `orders_history`
  ADD PRIMARY KEY (`form_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `logs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `cars_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `car_model`
--
ALTER TABLE `car_model`
  MODIFY `model_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_us_forms`
--
ALTER TABLE `contact_us_forms`
  MODIFY `contact_us_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders_forms`
--
ALTER TABLE `orders_forms`
  MODIFY `form_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders_history`
--
ALTER TABLE `orders_history`
  MODIFY `form_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_fk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`),
  ADD CONSTRAINT `audit_fk_2` FOREIGN KEY (`username`) REFERENCES `admins` (`admin_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
