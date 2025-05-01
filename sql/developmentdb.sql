-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Apr 19, 2025 at 10:58 PM
-- Server version: 11.7.2-MariaDB-ubu2404
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `developmentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `approvedAppointments`
--

CREATE TABLE `approvedAppointments` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `dog_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dogs`
--

CREATE TABLE `dogs` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `breed` varchar(255) NOT NULL,
  `age` int(3) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `dogs`
--

INSERT INTO `dogs` (`id`, `owner_id`, `name`, `breed`, `age`, `size`, `photo`) VALUES
(1, 38, 'Buddy', 'Golden Retriever', 3, 'Large', NULL),
(2, 38, 'Luna', 'Poodle', 2, 'Medium', NULL),
(3, 39, 'Max', 'Labrador', 4, 'Large', NULL),
(4, 39, 'Bella', 'Beagle', 2, 'Small', NULL),
(5, 39, 'Charlie', 'Cocker Spaniel', 1, 'Medium', NULL),
(6, 40, 'Daisy', 'Bulldog', 3, 'Medium', NULL),
(7, 41, 'Milo', 'Shih Tzu', 2, 'Small', NULL),
(8, 41, 'Sadie', 'Dachshund', 4, 'Small', NULL),
(9, 42, 'Rocky', 'Boxer', 5, 'Large', NULL),
(10, 42, 'Chloe', 'German Shepherd', 3, 'Large', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `dog_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `walker_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('owner','walker') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `price` varchar(100) DEFAULT NULL,
  `experience` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `role`, `profile_picture`, `price`, `experience`) VALUES
(33, 'Ava Carter', 'ava.walker@example.com', '$2y$12$IzKXVwy9.KX/0i0eKRtGYukI0/1uaSjEF0ZSa1vWeunJ.WkfzQP/G', 'walker', NULL, NULL, NULL),
(34, 'Noah Griffin', 'noah.walker@example.com', '$2y$12$LqokY.Jowtxlo1n9DO8yBuWcSj67Ec0vFK4rQxWAaqGphUlh6sBDS', 'walker', NULL, NULL, NULL),
(35, 'Mia Bennett', 'mia.walker@example.com', '$2y$12$BX8mFKI7AneKW4skMc6lJO195kVWB0AIRufXakQ1p./5HkIdwg8N.', 'walker', NULL, NULL, NULL),
(36, 'Liam Foster', 'liam.walker@example.com', '$2y$12$iabUhhnXOU1gRf11aUI/Z..BfBPQB0N3a6ZyMR6Y/tEdHv9QJ4etW', 'walker', NULL, NULL, NULL),
(37, 'Sophia Hayes', 'sophia.walker@example.com', '$2y$12$oHCV3PxaRJQ7ACYy3l61u.q6folyB1V/uNXto3xdyAQ6bcsn.diOe', 'walker', NULL, NULL, NULL),
(38, 'Oliver Reed', 'oliver.owner@example.com', '$2y$12$5GzokxXDJuIBL86ZY4VvyugCEPdoOnMhhYCGzzjzKeh/B4f31cOmm', 'owner', NULL, NULL, NULL),
(39, 'Emma Lane', 'emma.owner@example.com', '$2y$12$hn/kAspfPq4GWnPjEBiqD.OepA456M8i57zHWeFLwJYbwsmYxHIii', 'owner', NULL, NULL, NULL),
(40, 'James Harper', 'james.owner@example.com', '$2y$12$vcCIpwBjGhqV1ixjytkZFO2bdJDEU2prPa270M6f8cvuFMYnA94Me', 'owner', NULL, NULL, NULL),
(41, 'Lily Brooks', 'lily.owner@example.com', '$2y$12$qsA7f4lnAFf0EsVN6vb/wuIziUHv.b07OqTFwsyo6HUa0DcIoCZoa', 'owner', NULL, NULL, NULL),
(42, 'Elijah Stone', 'elijah.owner@example.com', '$2y$12$xPE3HKqRCl49CSaB02rvJOLU4nqyOD0LyRglog6VBrUdGX.QMrboO', 'owner', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approvedAppointments`
--
ALTER TABLE `approvedAppointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `dog_id` (`dog_id`);

--
-- Indexes for table `dogs`
--
ALTER TABLE `dogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dog_id` (`dog_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `walker_id` (`walker_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approvedAppointments`
--
ALTER TABLE `approvedAppointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dogs`
--
ALTER TABLE `dogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approvedAppointments`
--
ALTER TABLE `approvedAppointments`
  ADD CONSTRAINT `approvedAppointments_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`),
  ADD CONSTRAINT `approvedAppointments_ibfk_2` FOREIGN KEY (`dog_id`) REFERENCES `dogs` (`id`);

--
-- Constraints for table `dogs`
--
ALTER TABLE `dogs`
  ADD CONSTRAINT `dogs_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`dog_id`) REFERENCES `dogs` (`id`),
  ADD CONSTRAINT `request_ibfk_2` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `request_ibfk_3` FOREIGN KEY (`walker_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
