-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2025 at 01:03 PM
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
-- Database: `collage_connect`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_representatives`
--

CREATE TABLE IF NOT EXISTS `class_representatives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `class` varchar(50) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lost_items`
--

CREATE TABLE IF NOT EXISTS `lost_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `location_found` varchar(255) DEFAULT NULL,
  `reported_by` varchar(255) NOT NULL,
  `reported_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('lost','found') DEFAULT 'lost',
  `resolved` tinyint(1) DEFAULT 0,
  `photo_path` varchar(255) DEFAULT NULL,
  `claimed_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `claimed_by` (`claimed_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lost_items`
--

INSERT INTO `lost_items` (`id`, `item_name`, `description`, `location_found`, `reported_by`, `reported_at`, `status`, `resolved`, `photo_path`, `claimed_by`) VALUES
(1, 'item1', 'scscsc', 'cxcs', '22', '2025-03-02 06:59:28', 'found', 0, '../uploads/WIN_20230122_19_29_55_Pro.jpg', NULL),
(2, 'dgdg', 'fdgdg', 'gmrit', '22', '2025-03-02 07:04:03', 'found', 0, '', NULL),
(3, 'dsfsff', 'dvdsdsfs', 'fddgdfv', '22', '2025-03-02 07:10:38', 'found', 0, '../uploads/WIN_20230529_18_55_03_Pro.jpg', NULL),
(4, 'rgrg', 'fgrgrgrger', 'dvdsvds', '22', '2025-03-02 07:54:42', 'lost', 0, '../uploads/WIN_20230122_19_30_13_Pro.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE IF NOT EXISTS `notices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` enum('official','unofficial') NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  FULLTEXT KEY `title_content` (`title`, `content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `title`, `content`, `type`, `created_by`, `created_at`) VALUES
(1, 'Holiday Announcement', 'The college will be closed on Friday.', 'official', 1, '2025-02-20 06:05:53'),
(2, 'Lost Wallet', 'I lost my wallet near the library.', 'unofficial', 2, '2025-02-20 06:05:53');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','class_representative','regular_user') NOT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `contact`, `created_at`) VALUES
(1, 'admin1', 'adminpassword', 'admin', '1234567890', '2025-02-20 06:05:53'),
(2, 'cr1', 'crpassword', 'class_representative', '9876543210', '2025-02-20 06:05:53'),
(3, 'user1', 'userpassword', 'regular_user', '1112223333', '2025-02-20 06:05:53'),
(14, 'root', '', 'regular_user', NULL, '2025-03-02 05:35:48'),
(15, 'root', '', 'regular_user', NULL, '2025-03-02 05:36:12'),
(16, 'root', '', 'regular_user', NULL, '2025-03-02 05:39:58'),
(17, '11111', '', 'regular_user', NULL, '2025-03-02 05:54:24'),
(18, 'bhagi1231', '', 'regular_user', NULL, '2025-03-02 05:58:26'),
(19, '22', '2', 'regular_user', NULL, '2025-03-02 06:04:46');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_representatives`
--
ALTER TABLE `class_representatives`
  ADD CONSTRAINT `class_representatives_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `lost_items`
--
ALTER TABLE `lost_items`
  ADD CONSTRAINT `fk_claimed_by` FOREIGN KEY (`claimed_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `notices`
--
ALTER TABLE `notices`
  ADD CONSTRAINT `notices_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

-- Insert into admins table
INSERT INTO `admins` (`username`, `password`, `created_at`) 
VALUES 
('admin3', 'hashed_password3', NOW()),
('admin2', 'hashed_password2', NOW());
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;