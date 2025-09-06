-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2025 at 02:47 PM
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
-- Database: `studisciplink`
--

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `action`, `date_time`) VALUES
(1, 5, 'Logged in', '2025-09-01 01:21:30'),
(2, 5, 'Logged in', '2025-09-01 01:25:45'),
(3, 6, 'Logged in', '2025-09-01 01:28:30'),
(4, 7, 'Logged in', '2025-09-01 10:12:06'),
(5, 5, 'Logged in', '2025-09-01 10:55:51'),
(6, 7, 'Logged in', '2025-09-01 10:57:41'),
(7, 7, 'Logged out', '2025-09-01 11:00:11'),
(8, 5, 'Logged in', '2025-09-01 11:00:17'),
(9, 5, 'Logged out', '2025-09-01 22:30:57'),
(10, 5, 'Logged in', '2025-09-03 09:06:21'),
(11, 5, 'Updated account details', '2025-09-03 10:25:05'),
(12, 5, 'Logged out', '2025-09-03 10:25:27'),
(13, 7, 'Logged in', '2025-09-03 10:25:31'),
(14, 7, 'Updated account details', '2025-09-03 10:25:57'),
(15, 7, 'Logged out', '2025-09-03 10:31:52'),
(16, 7, 'Logged in', '2025-09-03 10:31:57'),
(17, 7, 'Updated account details', '2025-09-03 10:39:56'),
(18, 7, 'Updated account details', '2025-09-03 10:40:16'),
(19, 7, 'Logged in', '2025-09-03 10:40:38'),
(20, 7, 'Updated account details', '2025-09-03 10:41:21'),
(21, 7, 'Updated account details', '2025-09-03 10:42:16'),
(22, 7, 'Updated account details', '2025-09-03 10:43:16'),
(23, 7, 'Updated account details', '2025-09-03 10:43:39'),
(24, 7, 'Updated account details', '2025-09-03 10:45:22'),
(25, 7, 'Logged out', '2025-09-03 10:45:59'),
(26, 5, 'Logged in', '2025-09-03 10:46:11'),
(27, 5, 'Logged out', '2025-09-03 10:49:25'),
(28, 5, 'Logged in', '2025-09-03 10:49:36'),
(29, 5, 'Updated account details', '2025-09-03 10:49:41'),
(30, 5, 'Updated account details', '2025-09-03 10:49:51'),
(31, 5, 'Logged out', '2025-09-03 10:49:55'),
(32, 5, 'Logged in', '2025-09-03 10:50:05'),
(33, 5, 'Logged out', '2025-09-03 17:32:26'),
(34, 1, 'Logged in', '2025-09-03 17:33:04'),
(35, 1, 'Updated account details', '2025-09-03 17:33:20'),
(36, 1, 'Updated account details', '2025-09-03 18:01:05'),
(37, 1, 'Logged out', '2025-09-03 18:03:26'),
(38, 5, 'Logged in', '2025-09-03 18:03:31'),
(39, 5, 'Logged out', '2025-09-03 18:03:36'),
(40, 1, 'Logged in', '2025-09-03 18:03:40'),
(41, 1, 'Logged out', '2025-09-03 18:04:28'),
(42, 5, 'Logged in', '2025-09-03 18:04:38'),
(43, 5, 'Logged out', '2025-09-03 18:04:39'),
(44, 1, 'Logged in', '2025-09-03 18:04:44'),
(45, 1, 'Updated account details', '2025-09-03 18:12:23'),
(46, 1, 'Logged in', '2025-09-03 18:31:51'),
(47, 1, 'Updated account details', '2025-09-03 18:32:30'),
(48, 1, 'Updated account details', '2025-09-03 18:36:42'),
(49, 1, 'Updated account details', '2025-09-03 18:38:39'),
(50, 1, 'Updated account details', '2025-09-03 18:38:59'),
(51, 1, 'Logged out', '2025-09-03 18:40:13'),
(52, 8, 'Logged in', '2025-09-03 18:40:27'),
(53, 8, 'Updated account details', '2025-09-03 18:40:34'),
(54, 8, 'Logged out', '2025-09-03 18:40:38'),
(55, 1, 'Logged in', '2025-09-03 18:40:47'),
(56, 1, 'Logged out', '2025-09-03 18:43:53'),
(57, 1, 'Logged in', '2025-09-03 18:57:38'),
(58, 1, 'Logged out', '2025-09-03 18:57:50'),
(59, 6, 'Logged in', '2025-09-03 18:57:55'),
(60, 1, 'Logged in', '2025-09-03 19:34:06'),
(61, 1, 'Logged out', '2025-09-03 19:47:58'),
(62, 6, 'Logged in', '2025-09-03 19:48:15'),
(63, 6, 'Logged in', '2025-09-03 19:49:05'),
(64, 6, 'Logged out', '2025-09-03 20:32:24'),
(65, 6, 'Logged in', '2025-09-03 20:32:29'),
(66, 6, 'Logged out', '2025-09-03 20:55:26'),
(67, 6, 'Logged in', '2025-09-03 20:55:31'),
(68, 6, 'Logged out', '2025-09-03 20:56:16'),
(69, 6, 'Logged in', '2025-09-03 20:56:21'),
(70, 6, 'Logged out', '2025-09-03 20:58:38'),
(71, 1, 'Logged in', '2025-09-03 21:02:04'),
(72, 1, 'Logged out', '2025-09-03 21:02:12'),
(73, 6, 'Logged in', '2025-09-03 21:02:17'),
(74, 6, 'Logged out', '2025-09-03 21:25:17'),
(75, 1, 'Logged in', '2025-09-03 21:25:24'),
(76, 1, 'Logged out', '2025-09-03 21:25:44'),
(77, 6, 'Logged in', '2025-09-03 21:25:50'),
(78, 6, 'Added student: Daniel Padilla for School Year 2025-2026', '2025-09-03 22:14:45'),
(79, 6, 'Deleted student ID 4: Daniel Padilla', '2025-09-03 22:15:07'),
(80, 6, 'Deleted student ID 3: Daniel Padilla', '2025-09-03 22:15:09'),
(81, 6, 'Deleted student ID 3:  ', '2025-09-03 22:15:12'),
(82, 6, 'Deleted student ID 3:  ', '2025-09-03 22:15:16'),
(83, 6, 'Logged out', '2025-09-03 22:16:15'),
(84, 1, 'Logged in', '2025-09-03 22:16:19'),
(85, 1, 'Logged out', '2025-09-03 22:16:50'),
(86, 6, 'Logged in', '2025-09-03 22:17:00'),
(87, 6, 'Added student: Coco Martin for School Year 2025-2026', '2025-09-03 22:17:24'),
(88, 6, 'Deleted student ID 2: Daniel Padilla', '2025-09-03 22:18:42'),
(89, 6, 'Logged out', '2025-09-03 22:22:41'),
(90, 1, 'Logged in', '2025-09-03 22:22:47'),
(91, 1, 'Logged in', '2025-09-05 08:30:53'),
(92, 1, 'Logged out', '2025-09-05 08:56:54'),
(93, 1, 'Logged in', '2025-09-05 08:56:58'),
(94, 1, 'Logged in', '2025-09-05 09:40:56'),
(95, 1, 'Updated account details', '2025-09-05 11:48:27'),
(96, 1, 'Updated account details', '2025-09-05 11:49:15'),
(97, 1, 'Updated account details', '2025-09-05 11:49:35'),
(98, 1, 'Updated account details', '2025-09-05 11:50:18'),
(99, 1, 'Logged out', '2025-09-05 11:50:19'),
(100, 1, 'Logged in', '2025-09-05 11:50:27'),
(101, 1, 'Updated account details', '2025-09-05 22:55:11'),
(102, 1, 'Logged out', '2025-09-05 22:55:16'),
(103, 1, 'Logged in', '2025-09-05 22:55:54'),
(104, 1, 'Logged out', '2025-09-05 23:06:07'),
(105, 1, 'Logged in', '2025-09-05 23:06:16'),
(106, 1, 'Changed password', '2025-09-05 23:36:07'),
(107, 1, 'Logged out', '2025-09-05 23:36:12'),
(108, 1, 'Logged in', '2025-09-05 23:36:26'),
(109, 1, 'Updated account details', '2025-09-05 23:37:52'),
(110, 1, 'Logged out', '2025-09-05 23:37:58'),
(111, 1, 'Logged in', '2025-09-05 23:39:38'),
(112, 1, 'Updated account details', '2025-09-05 23:39:57'),
(113, 1, 'Updated account info', '2025-09-05 23:44:28'),
(114, 1, 'Updated account info', '2025-09-05 23:45:05'),
(115, 1, 'Updated account info', '2025-09-05 23:45:17'),
(116, 1, 'Updated account info', '2025-09-05 23:45:56'),
(117, 1, 'Updated account info', '2025-09-05 23:48:33'),
(118, 1, 'Updated account info', '2025-09-05 23:48:54'),
(119, 1, 'Changed password', '2025-09-05 23:49:42'),
(120, 1, 'Logged out', '2025-09-05 23:50:18'),
(121, 1, 'Logged in', '2025-09-05 23:50:26'),
(122, 1, 'Changed password', '2025-09-05 23:51:10'),
(123, 1, 'Updated account info', '2025-09-05 23:53:56'),
(124, 1, 'Updated account info', '2025-09-05 23:54:17'),
(125, 1, 'Logged out', '2025-09-06 00:00:08'),
(126, 1, 'Logged in', '2025-09-06 00:00:35'),
(127, 1, 'Updated account info', '2025-09-06 00:00:57'),
(128, 1, 'Changed password', '2025-09-06 00:01:11'),
(129, 1, 'Changed password', '2025-09-06 00:01:46'),
(130, 1, 'Logged in', '2025-09-06 00:02:02'),
(131, 1, 'Logged in', '2025-09-06 00:02:22'),
(132, 1, 'Logged in', '2025-09-06 00:03:09'),
(133, 1, 'Changed password', '2025-09-06 00:03:18'),
(134, 1, 'Logged out', '2025-09-06 00:03:24'),
(135, 1, 'Logged in', '2025-09-06 00:03:32'),
(136, 1, 'Changed password', '2025-09-06 00:04:09'),
(137, 1, 'Logged out', '2025-09-06 00:04:21'),
(138, 1, 'Logged in', '2025-09-06 00:04:27'),
(139, 1, 'Updated account info', '2025-09-06 00:05:35'),
(140, 1, 'Updated account info', '2025-09-06 00:06:47'),
(141, 1, 'Logged in', '2025-09-06 00:08:07'),
(142, 1, 'Logged in', '2025-09-06 00:08:27'),
(143, 1, 'Logged in', '2025-09-06 00:08:46'),
(144, 1, 'Updated account info', '2025-09-06 00:08:52'),
(145, 1, 'Updated account info', '2025-09-06 00:15:28'),
(146, 1, 'Logged in', '2025-09-06 00:16:27'),
(147, 1, 'Logged in', '2025-09-06 00:16:42'),
(148, 1, 'Updated account info', '2025-09-06 00:16:47'),
(149, 1, 'Logged out', '2025-09-06 00:20:57'),
(150, 1, 'Logged in', '2025-09-06 00:21:15'),
(151, 1, 'Logged in', '2025-09-06 00:22:00'),
(152, 1, 'Logged in', '2025-09-06 00:23:27'),
(153, 1, 'Updated account info', '2025-09-06 00:23:41'),
(154, 1, 'Updated account info', '2025-09-06 00:28:54'),
(155, 1, 'Changed password', '2025-09-06 00:29:35'),
(156, 1, 'Logged in', '2025-09-06 00:34:10'),
(157, 1, 'Logged in', '2025-09-06 00:35:37'),
(158, 1, 'Updated account info', '2025-09-06 00:38:35'),
(159, 1, 'Updated account info', '2025-09-06 00:38:55'),
(160, 1, 'Changed password', '2025-09-06 00:39:12'),
(161, 1, 'Updated account info', '2025-09-06 00:39:25'),
(162, 1, 'Logged out', '2025-09-06 00:39:29'),
(163, 1, 'Logged in', '2025-09-06 00:39:34'),
(164, 1, 'Updated account info', '2025-09-06 00:48:00'),
(165, 1, 'Updated account info', '2025-09-06 00:54:18'),
(166, 1, 'Changed password', '2025-09-06 00:54:53'),
(167, 1, 'Logged out', '2025-09-06 00:54:55'),
(168, 1, 'Logged in', '2025-09-06 00:55:03'),
(169, 1, 'Changed password', '2025-09-06 00:55:17'),
(170, 1, 'Logged out', '2025-09-06 00:55:21'),
(171, 1, 'Logged in', '2025-09-06 00:55:25'),
(172, 1, 'Logged out', '2025-09-06 08:38:04'),
(173, 6, 'Logged in', '2025-09-06 08:38:11'),
(174, 6, 'Logged out', '2025-09-06 09:05:10'),
(175, 1, 'Logged in', '2025-09-06 09:05:14'),
(176, 1, 'Logged out', '2025-09-06 09:05:23'),
(177, 6, 'Logged in', '2025-09-06 09:05:28'),
(178, 6, 'Logged out', '2025-09-06 09:07:02'),
(179, 6, 'Logged in', '2025-09-06 09:07:29'),
(180, 6, 'Added student: Daniel Padilla', '2025-09-06 12:38:14'),
(181, 6, 'Added student: Daniel Padilla', '2025-09-06 12:38:17'),
(182, 6, 'Deleted student ID 7: Daniel Padilla', '2025-09-06 12:38:21'),
(183, 6, 'Logged out', '2025-09-06 12:44:41'),
(184, 1, 'Logged in', '2025-09-06 12:44:45'),
(185, 1, 'Logged out', '2025-09-06 12:45:09'),
(186, 6, 'Logged in', '2025-09-06 12:45:14'),
(187, 6, 'Added student: Ronald Rosales', '2025-09-06 12:56:06'),
(188, 6, 'Added student: Mark Zaragosa', '2025-09-06 12:56:40'),
(189, 6, 'Updated student ID 8', '2025-09-06 12:59:16'),
(190, 6, 'Updated student ID 8', '2025-09-06 13:05:23'),
(191, 6, 'Updated student ID 8', '2025-09-06 13:05:41'),
(192, 6, 'Updated student ID 8', '2025-09-06 13:05:46'),
(193, 6, 'Logged out', '2025-09-06 13:35:22'),
(194, 1, 'Logged in', '2025-09-06 13:35:26'),
(195, 1, 'Logged out', '2025-09-06 13:36:51'),
(196, 1, 'Logged in', '2025-09-06 13:36:59'),
(197, 1, 'Logged out', '2025-09-06 13:37:02'),
(198, 6, 'Logged in', '2025-09-06 13:37:06'),
(199, 6, 'Logged out', '2025-09-06 14:00:38'),
(200, 6, 'Logged in', '2025-09-06 14:00:48'),
(201, 6, 'Updated student ID 9', '2025-09-06 14:02:04'),
(202, 6, 'Updated student ID 9', '2025-09-06 14:03:22'),
(203, 6, 'Updated student ID 5', '2025-09-06 19:54:50'),
(204, 6, 'Added student: Emman Bas', '2025-09-06 19:57:42'),
(205, 6, 'Added student: Emman Bas', '2025-09-06 19:58:31'),
(206, 6, 'Added student: Emman Bas', '2025-09-06 19:58:40'),
(207, 6, 'Added student: Emman Bas', '2025-09-06 19:58:51'),
(208, 6, 'Added student: Emman Bas', '2025-09-06 19:59:01'),
(209, 6, 'Added student: Emman Bas', '2025-09-06 19:59:06'),
(210, 6, 'Added student: Emman Bas', '2025-09-06 19:59:33'),
(211, 6, 'Added student: Emman Bas', '2025-09-06 19:59:43'),
(212, 6, 'Added student: Emman Bas', '2025-09-06 19:59:58'),
(213, 6, 'Added student: Emman Bas', '2025-09-06 20:00:19'),
(214, 6, 'Logged out', '2025-09-06 20:01:59'),
(215, 1, 'Logged in', '2025-09-06 20:02:05'),
(216, 1, 'Logged out', '2025-09-06 20:02:22'),
(217, 6, 'Logged in', '2025-09-06 20:02:27'),
(218, 6, 'Deleted student ID 19: Emman Bas', '2025-09-06 20:04:04'),
(219, 6, 'Deleted student ID 18: Emman Bas', '2025-09-06 20:04:19'),
(220, 6, 'Deleted student ID 10: Emman Bas', '2025-09-06 20:04:21'),
(221, 6, 'Deleted student ID 11: Emman Bas', '2025-09-06 20:04:22'),
(222, 6, 'Deleted student ID 12: Emman Bas', '2025-09-06 20:04:22'),
(223, 6, 'Deleted student ID 13: Emman Bas', '2025-09-06 20:04:23'),
(224, 6, 'Deleted student ID 14: Emman Bas', '2025-09-06 20:04:23'),
(225, 6, 'Deleted student ID 15: Emman Bas', '2025-09-06 20:04:24'),
(226, 6, 'Deleted student ID 16: Emman Bas', '2025-09-06 20:04:25'),
(227, 6, 'Added student: Robin Padilla', '2025-09-06 20:09:53'),
(228, 6, 'Added student: John Doe', '2025-09-06 20:15:14'),
(229, 6, 'Added student: John Doe', '2025-09-06 20:15:14'),
(230, 6, 'Deleted student ID 22: John Doe', '2025-09-06 20:15:30'),
(231, 6, 'Added student: John Dave', '2025-09-06 20:15:57'),
(232, 6, 'Added student: John Dave', '2025-09-06 20:15:57'),
(233, 6, 'Deleted student ID 24: John Dave', '2025-09-06 20:16:20'),
(234, 6, 'Updated student ID 21', '2025-09-06 20:16:32'),
(235, 6, 'Updated student ID 21', '2025-09-06 20:34:53'),
(236, 6, 'Updated student ID 21', '2025-09-06 20:34:57'),
(237, 6, 'Updated student ID 23', '2025-09-06 20:35:05'),
(238, 6, 'Updated student ID 23', '2025-09-06 20:35:11'),
(239, 6, 'Added student: Angel Amaro', '2025-09-06 20:35:45'),
(240, 6, 'Added student: Angel Amaro', '2025-09-06 20:35:54'),
(241, 6, 'Added student: Angel Amaro', '2025-09-06 20:36:01'),
(242, 6, 'Deleted student ID 27: Angel Amaro', '2025-09-06 20:36:06'),
(243, 6, 'Deleted student ID 26: Angel Amaro', '2025-09-06 20:36:09'),
(244, 6, 'Updated account info', '2025-09-06 20:46:22'),
(245, 6, 'Logged in', '2025-09-06 20:46:29');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `program_code` varchar(50) NOT NULL,
  `program_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `program_code`, `program_name`) VALUES
(1, 'BSIT', 'Bachelor of Science in Information Technology'),
(2, 'BSED', 'Bachelor of Science in Secondary Education');

-- --------------------------------------------------------

--
-- Table structure for table `school_years`
--

CREATE TABLE `school_years` (
  `id` int(11) NOT NULL,
  `school_year` varchar(50) NOT NULL,
  `is_current` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `school_years`
--

INSERT INTO `school_years` (`id`, `school_year`, `is_current`) VALUES
(1, '2024-2025', 0),
(2, '2025-2026', 1),
(3, '2026-2027', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `section_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `section_name`) VALUES
(2, 'A'),
(3, 'B');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `year_level_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `address` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `img` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `school_year_id`, `program_id`, `year_level_id`, `section_id`, `address`, `contact`, `img`, `user_id`) VALUES
(5, 'Hello', 'Martin', 2, 1, 2, 2, 'Manilla Philippines', '09123456789', '', 6),
(6, 'Daniel', 'Padilla', 2, 2, 1, 3, 'Quezon City', '09123456789', '', 6),
(8, 'Ronald', 'Rosales', 2, 1, 1, 2, 'Toledo Cebu', '09123456789', '', 6),
(9, 'Mark', 'Saragosa', 2, 1, 1, 2, 'Naga Cebu', '09123456789', '', 6),
(17, 'Emman', 'Bas', 2, 1, 2, 2, 'Poblacion Minglanilla Cebu', '09123456789', '', 6),
(20, 'Robin', 'Padilla', 1, 2, 1, 2, 'Manila Philippines', '09123456789', '', 6),
(21, 'John', 'Does', 2, 2, 2, 3, 'No Address', '09123456789', '', 6),
(23, 'John', 'Dave', 2, 2, 1, 3, 'Minglanilla Cebu', '09123456789', '', 6),
(25, 'Angel', 'Amaro', 2, 1, 2, 2, 'Minglanilla Cebu', '09123456789', '', 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `email`, `contact`, `status`, `img`) VALUES
(1, 'lawrencesumbi', '$2y$10$RgwJiQxgLqPkbV0I.BMdzuihz3MTEvJ94pUkx2sD87MOkRo1fq66K', 'admin', 'guiansumbi@gmail.com', '09303172724', 'active', '../studisciplink/userUploads/download (2).jpg'),
(2, 'patriciaobaob', '$2y$10$AWfFB3AaX0oflt9PmLOGBeomgKpLGlL5ez6lednMoafVYmqZURIlm', 'sao', '', '', 'pending', ''),
(3, 'lawrenceguian', '$2y$10$PNQ.mHdPylHsdTWcJxjkoOWjdwFs4jnVEFpQXoAzRvVuBDkxUoavO', 'guidance', '', '', 'pending', ''),
(4, 'davidvergara', '$2y$10$NskSedV6lqQnqBvSVVJXj.cG7Y4rhntTKxc0icydSwvKyXdV8mN0S', 'faculty', '', '', 'pending', ''),
(5, 'jaymaicanarvasa', '$2y$10$IFv1MHhxKCqhtUskH3w0tez3x9.yC6i9UqybW8Rf6LA3paRdQ/dve', 'admin', 'jaymaica@gmail.com', '09987654321', 'active', ''),
(6, 'draymisa', '$2y$10$Uegpk.88TaBNKRLoy.bd.OIGaWyBTqhf8u0V.E5TNwBXuD6FOcj66', 'registrar', 'draymisa@gmail.com', '09123456789', 'active', '../studisciplink/userUploads/6843dc0b5e4341f168aac30144c56418.jpg'),
(7, 'jaylonmantillas', '$2y$10$ieINs2o2zZcC/bi3N50hbOsYDoy6jtCN8AUbnGS8sL3juNhQQDmoK', 'admin', 'jaylon@gmail.com', '09987654321', 'pending', ''),
(8, 'johndoe', '$2y$10$dYo05XtAvt0yTqgFiex6VOnPqrlSDlO6Tbc55tVVrUFd26s5KWogq', 'admin', '', '', 'active', '');

-- --------------------------------------------------------

--
-- Table structure for table `year_levels`
--

CREATE TABLE `year_levels` (
  `id` int(11) NOT NULL,
  `year_level` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `year_levels`
--

INSERT INTO `year_levels` (`id`, `year_level`) VALUES
(1, 'First Year'),
(2, 'Second Year');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_years`
--
ALTER TABLE `school_years`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_year_id` (`school_year_id`),
  ADD KEY `year_level_id` (`year_level_id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `year_levels`
--
ALTER TABLE `year_levels`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `school_years`
--
ALTER TABLE `school_years`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `year_levels`
--
ALTER TABLE `year_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_user_id_fr` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_program_id_fr` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`),
  ADD CONSTRAINT `students_school_year_id_fr` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`),
  ADD CONSTRAINT `students_section_id` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`),
  ADD CONSTRAINT `students_user_id_fr` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `students_year_level_id_fr` FOREIGN KEY (`year_level_id`) REFERENCES `year_levels` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
