-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2026 at 05:39 AM
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
-- Database: `autopulse`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emergency_tows`
--

CREATE TABLE `emergency_tows` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `pickup_location` text NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `is_immediate` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `home_services`
--

CREATE TABLE `home_services` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `scheduled_date` date NOT NULL,
  `scheduled_time` time NOT NULL,
  `address` text NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home_services`
--

INSERT INTO `home_services` (`id`, `service_id`, `vehicle_id`, `scheduled_date`, `scheduled_time`, `address`, `contact_number`, `notes`, `created_at`) VALUES
(1, 3, 2, '2026-01-17', '16:15:00', 'lkmk', '01309983442', '', '2026-01-15 07:15:39'),
(2, 4, 1, '2026-01-30', '15:36:00', 'Home', '01309983442', '', '2026-01-18 04:32:15');

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE `parts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 100,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`id`, `name`, `description`, `price`, `stock`, `image_url`, `created_at`) VALUES
(1, 'Synthetic Engine Oil', 'Premium 5W-30 synthetic motor oil for better performance.', 1200.00, 50, NULL, '2026-01-16 11:40:41'),
(2, 'Oil Filter', 'High-quality oil filter for engine protection.', 450.00, 100, NULL, '2026-01-16 11:40:41'),
(3, 'Brake Pads (Front)', 'Ceramic brake pads for front wheels.', 3500.00, 30, NULL, '2026-01-16 11:40:41'),
(4, 'Air Filter', 'Clean air filter for improved fuel efficiency.', 800.00, 70, NULL, '2026-01-16 11:40:41'),
(5, 'Spark Plug', 'Iridium spark plug for durability.', 300.00, 200, NULL, '2026-01-16 11:40:41'),
(6, 'Car Battery', 'Maintenance-free 12V battery.', 8500.00, 15, NULL, '2026-01-16 11:40:41');

-- --------------------------------------------------------

--
-- Table structure for table `parts_orders`
--

CREATE TABLE `parts_orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `part_name` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parts_orders`
--

INSERT INTO `parts_orders` (`id`, `user_id`, `part_name`, `quantity`, `total_amount`, `created_at`) VALUES
(1, 1, 'Engine Oil', 1, 2500.00, '2026-01-07 18:42:08');

-- --------------------------------------------------------

--
-- Table structure for table `part_orders`
--

CREATE TABLE `part_orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `delivery_address` text NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `status` enum('Pending','Confirmed','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `part_orders`
--

INSERT INTO `part_orders` (`id`, `user_id`, `part_id`, `quantity`, `total_price`, `delivery_address`, `contact_number`, `status`, `created_at`) VALUES
(1, 1, 4, 1, 800.00, 'aaa', '01309983442', 'Pending', '2026-01-16 12:15:52'),
(2, 1, 4, 1, 800.00, 'sw', '123', 'Pending', '2026-01-16 12:27:19'),
(3, 1, 4, 3, 2400.00, '1dfd', '01309983442', 'Pending', '2026-01-16 12:40:31');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_type` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `user_id`, `service_type`, `description`, `status`, `created_at`) VALUES
(1, 1, 'Engine Check', 'General engine diagnostic', 'In Progress', '2026-01-07 18:42:08'),
(2, 1, 'Oil Change', 'Synthetic oil replacement', 'Completed', '2026-01-07 18:42:08'),
(3, 1, 'Oil Change', NULL, 'Pending', '2026-01-15 07:15:39'),
(4, 1, 'Car Wash', NULL, 'Pending', '2026-01-18 04:32:15'),
(5, 1, 'Test Booking', 'Test Description', 'Pending', '2026-01-19 17:59:39');

-- --------------------------------------------------------

--
-- Table structure for table `service_catalog`
--

CREATE TABLE `service_catalog` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_catalog`
--

INSERT INTO `service_catalog` (`id`, `name`, `price`, `description`, `created_at`) VALUES
(1, 'Oil Change - Synthetic', 79.99, NULL, '2026-01-19 17:38:31'),
(2, 'Brake Pad Replacement', 150.00, NULL, '2026-01-19 17:38:31'),
(3, 'Engine Diagnostic', 45.00, NULL, '2026-01-19 17:38:31'),
(4, 'Tire Rotation', 25.00, NULL, '2026-01-19 17:38:31'),
(5, 'Test Service 6443', 99.99, NULL, '2026-01-19 17:41:43'),
(6, 'Nothing', 1000.00, NULL, '2026-01-19 17:54:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('CarOwner','Admin','RepairShop') DEFAULT 'CarOwner',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `created_at`, `profile_picture`) VALUES
(1, 'John Doe', 'john@example.com', NULL, NULL, 'CarOwner', '2026-01-07 18:42:08', NULL),
(2, 'Arafat', 'arafat123@gmail.com', '01309983442', '$2y$10$CUEW2fAGXz.qqTSTsIFljOZUAfzoe/FhJEj3BuJtumgBDWEkAzAS6', 'CarOwner', '2026-01-18 05:36:40', 'assets/uploads/profile_pictures/user_2_1768843035.jpeg'),
(3, 'diganta', 'diganta@gmail.com', '01311111111', '*500DA9B59734E03C4AC32F759331F98996BA3D2E', 'Admin', '2026-01-19 17:44:54', NULL),
(4, 'Arafat Helal', '22-46666-1@student.aiub.edu', '015445565445', '$2y$10$.PdSAOQrOqYOG6fxD5IClutMsAHKMhTNIB9Y6FgED0AwbIAEN05my', 'CarOwner', '2026-01-19 17:51:22', NULL),
(6, 'Admin User', 'admin@example.com', '0000000000', '$2y$10$petwm0f1YoDi8fHAcMoDt.2v3U.UUdDPjvIxxjk/r3JPjg2TDf7/.', 'Admin', '2026-01-20 04:11:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `plate_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `user_id`, `brand`, `model`, `year`, `plate_number`, `created_at`) VALUES
(1, 1, 'Toyota', 'Axio', 2016, 'DH-1234', '2026-01-07 18:42:08'),
(2, 1, 'Toyota', 'Allion', 2007, '32323', '2026-01-11 08:44:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `emergency_tows`
--
ALTER TABLE `emergency_tows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `home_services`
--
ALTER TABLE `home_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parts_orders`
--
ALTER TABLE `parts_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `part_orders`
--
ALTER TABLE `part_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `part_id` (`part_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `service_catalog`
--
ALTER TABLE `service_catalog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `emergency_tows`
--
ALTER TABLE `emergency_tows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `home_services`
--
ALTER TABLE `home_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `parts`
--
ALTER TABLE `parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `parts_orders`
--
ALTER TABLE `parts_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `part_orders`
--
ALTER TABLE `part_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service_catalog`
--
ALTER TABLE `service_catalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `emergency_tows`
--
ALTER TABLE `emergency_tows`
  ADD CONSTRAINT `emergency_tows_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emergency_tows_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `home_services`
--
ALTER TABLE `home_services`
  ADD CONSTRAINT `home_services_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `home_services_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);

--
-- Constraints for table `parts_orders`
--
ALTER TABLE `parts_orders`
  ADD CONSTRAINT `parts_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `part_orders`
--
ALTER TABLE `part_orders`
  ADD CONSTRAINT `part_orders_ibfk_1` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
