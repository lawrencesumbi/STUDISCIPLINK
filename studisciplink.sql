-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2025 at 05:22 PM
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
-- Table structure for table `class_enrollments`
--

CREATE TABLE `class_enrollments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `year_level_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `enrolled_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_enrollments`
--

INSERT INTO `class_enrollments` (`id`, `user_id`, `program_id`, `year_level_id`, `section_id`, `school_year_id`, `enrolled_at`) VALUES
(3, 4, 2, 2, 3, 2, 0),
(6, 4, 2, 1, 2, 1, 0),
(7, 4, 1, 1, 2, 2, 0);

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
(245, 6, 'Logged in', '2025-09-06 20:46:29'),
(246, 6, 'Updated student ID 21', '2025-09-06 20:50:20'),
(247, 6, 'Updated student ID 21', '2025-09-06 20:50:28'),
(248, 6, 'Logged out', '2025-09-06 20:50:33'),
(249, 1, 'Logged in', '2025-09-06 21:15:51'),
(250, 1, 'Logged out', '2025-09-06 21:16:00'),
(251, 1, 'Logged in', '2025-09-06 21:34:45'),
(252, 1, 'Logged out', '2025-09-06 21:34:46'),
(253, 1, 'Logged in', '2025-09-06 22:20:35'),
(254, 1, 'Logged out', '2025-09-06 22:20:38'),
(255, 1, 'Logged in', '2025-09-06 22:32:27'),
(256, 1, 'Logged out', '2025-09-06 22:32:29'),
(257, 1, 'Logged in', '2025-09-06 22:35:42'),
(258, 1, 'Logged out', '2025-09-06 22:35:44'),
(259, 1, 'Logged in', '2025-09-06 22:36:26'),
(260, 1, 'Changed password', '2025-09-06 22:38:11'),
(261, 1, 'Logged out', '2025-09-06 22:38:15'),
(262, 1, 'Logged in', '2025-09-06 22:38:23'),
(263, 1, 'Updated account info', '2025-09-06 22:38:28'),
(264, 1, 'Updated account info', '2025-09-06 22:39:00'),
(265, 1, 'Logged out', '2025-09-06 22:39:35'),
(266, 6, 'Logged in', '2025-09-06 22:39:39'),
(267, 6, 'Logged out', '2025-09-06 22:40:13'),
(268, 1, 'Logged in', '2025-09-06 22:40:17'),
(269, 1, 'Logged out', '2025-09-06 22:41:52'),
(270, 6, 'Logged in', '2025-09-06 22:41:56'),
(271, 6, 'Updated student ID 5', '2025-09-06 22:49:55'),
(272, 6, 'Deleted student ID 25: Angel Amaro', '2025-09-06 22:50:35'),
(273, 6, 'Updated student ID 5', '2025-09-06 22:51:06'),
(274, 6, 'Logged out', '2025-09-06 22:52:00'),
(275, 1, 'Logged in', '2025-09-06 22:52:20'),
(276, 1, 'Logged out', '2025-09-06 22:58:02'),
(277, 1, 'Logged in', '2025-09-06 22:58:48'),
(278, 1, 'Logged out', '2025-09-06 23:03:49'),
(279, 1, 'Logged in', '2025-09-06 23:03:53'),
(280, 1, 'Logged in', '2025-09-06 23:40:15'),
(281, 1, 'Logged out', '2025-09-06 23:41:44'),
(282, 6, 'Logged in', '2025-09-06 23:41:49'),
(283, 6, 'Logged out', '2025-09-06 23:45:32'),
(284, 1, 'Logged in', '2025-09-06 23:45:36'),
(285, 1, 'Logged out', '2025-09-06 23:50:26'),
(286, 6, 'Logged in', '2025-09-06 23:50:31'),
(287, 6, 'Logged out', '2025-09-06 23:50:40'),
(288, 1, 'Logged in', '2025-09-06 23:53:49'),
(289, 1, 'Logged out', '2025-09-07 00:07:56'),
(290, 6, 'Logged in', '2025-09-07 00:14:47'),
(291, 6, 'Logged out', '2025-09-07 00:26:32'),
(292, 1, 'Logged in', '2025-09-08 13:53:06'),
(293, 1, 'Logged out', '2025-09-08 13:54:15'),
(294, 6, 'Logged in', '2025-09-08 13:54:20'),
(295, 6, 'Logged out', '2025-09-08 13:55:50'),
(296, 1, 'Logged in', '2025-09-08 14:02:42'),
(297, 1, 'Logged out', '2025-09-08 14:03:32'),
(298, 6, 'Logged in', '2025-09-08 14:03:46'),
(299, 6, 'Logged out', '2025-09-08 14:04:07'),
(300, 6, 'Logged in', '2025-09-08 14:05:13'),
(301, 6, 'Logged out', '2025-09-08 14:05:36'),
(302, 6, 'Logged in', '2025-09-08 14:20:30'),
(303, 6, 'Logged out', '2025-09-08 14:37:31'),
(304, 1, 'Logged in', '2025-09-08 14:37:35'),
(305, 1, 'Logged out', '2025-09-08 14:57:58'),
(306, 1, 'Logged in', '2025-09-08 14:58:08'),
(307, 1, 'Logged out', '2025-09-08 14:58:20'),
(308, 4, 'Logged in', '2025-09-08 14:58:26'),
(309, 4, 'Logged out', '2025-09-08 14:58:41'),
(310, 4, 'Logged in', '2025-09-08 14:58:48'),
(311, 4, 'Logged out', '2025-09-08 15:00:00'),
(312, 6, 'Logged in', '2025-09-08 15:00:04'),
(313, 6, 'Logged out', '2025-09-08 15:00:25'),
(314, 4, 'Logged in', '2025-09-08 15:01:01'),
(315, 4, 'Logged out', '2025-09-08 15:09:30'),
(316, 6, 'Logged in', '2025-09-08 15:09:38'),
(317, 6, 'Logged out', '2025-09-08 15:18:02'),
(318, 4, 'Logged in', '2025-09-08 15:18:08'),
(319, 4, 'Logged in', '2025-09-08 15:19:04'),
(320, 4, 'Logged in', '2025-09-08 22:41:45'),
(321, 4, 'Logged out', '2025-09-08 23:04:31'),
(322, 6, 'Logged in', '2025-09-08 23:04:40'),
(323, 6, 'Logged out', '2025-09-08 23:05:23'),
(324, 4, 'Logged in', '2025-09-08 23:05:29'),
(325, 4, 'Logged out', '2025-09-08 23:13:47'),
(326, 6, 'Logged in', '2025-09-08 23:13:55'),
(327, 6, 'Logged out', '2025-09-08 23:14:11'),
(328, 4, 'Logged in', '2025-09-08 23:14:18'),
(329, 4, 'Logged out', '2025-09-08 23:36:18'),
(330, 6, 'Logged in', '2025-09-08 23:36:29'),
(331, 6, 'Logged out', '2025-09-08 23:37:48'),
(332, 4, 'Logged in', '2025-09-08 23:37:57'),
(333, 4, 'Updated account info', '2025-09-08 23:39:22'),
(334, 4, 'Logged in', '2025-09-08 23:39:30'),
(335, 4, 'Updated account info', '2025-09-08 23:39:44'),
(336, 4, 'Logged in', '2025-09-08 23:40:39'),
(337, 4, 'Updated account info', '2025-09-08 23:41:19'),
(338, 6, 'Logged in', '2025-09-08 23:41:58'),
(339, 6, 'Updated account info', '2025-09-08 23:42:05'),
(340, 1, 'Logged in', '2025-09-08 23:42:21'),
(341, 1, 'Updated account info', '2025-09-08 23:42:28'),
(342, 1, 'Logged out', '2025-09-08 23:55:08'),
(343, 6, 'Logged in', '2025-09-08 23:55:13'),
(344, 6, 'Updated account info', '2025-09-08 23:55:19'),
(345, 6, 'Logged in', '2025-09-08 23:56:04'),
(346, 6, 'Updated account info', '2025-09-08 23:56:24'),
(347, 6, 'Logged out', '2025-09-08 23:56:31'),
(348, 4, 'Logged in', '2025-09-08 23:56:37'),
(349, 4, 'Updated account info', '2025-09-08 23:56:48'),
(350, 4, 'Logged in', '2025-09-09 10:03:44'),
(351, 4, 'Logged in', '2025-09-09 10:03:51'),
(352, 1, 'Logged in', '2025-09-09 10:04:05'),
(353, 1, 'Logged out', '2025-09-09 10:04:19'),
(354, 4, 'Logged in', '2025-09-09 10:04:23'),
(355, 6, 'Logged in', '2025-09-09 10:05:34'),
(356, 6, 'Logged out', '2025-09-09 10:05:41'),
(357, 4, 'Logged in', '2025-09-09 10:05:48'),
(358, 4, 'Logged in', '2025-09-09 10:05:54'),
(359, 4, 'Logged in', '2025-09-09 10:06:13'),
(360, 4, 'Logged out', '2025-09-09 10:06:39'),
(361, 4, 'Logged in', '2025-09-09 10:06:43'),
(362, 4, 'Logged in', '2025-09-09 10:07:08'),
(363, 4, 'Logged in', '2025-09-09 10:08:24'),
(364, 4, 'Logged in', '2025-09-09 10:08:54'),
(365, 4, 'Logged in', '2025-09-09 10:31:40'),
(366, 4, 'Logged out', '2025-09-09 12:26:23'),
(367, 6, 'Logged in', '2025-09-09 12:26:29'),
(368, 6, 'Logged out', '2025-09-09 13:55:05'),
(369, 4, 'Logged in', '2025-09-09 13:58:42'),
(370, 4, 'Logged out', '2025-09-09 15:34:00'),
(371, 1, 'Logged in', '2025-09-09 15:34:04'),
(372, 1, 'Logged out', '2025-09-09 15:35:18'),
(373, 6, 'Logged in', '2025-09-09 15:35:23'),
(374, 6, 'Logged out', '2025-09-09 15:37:20'),
(375, 4, 'Logged in', '2025-09-09 15:37:25'),
(376, 4, 'Logged out', '2025-09-09 17:01:14'),
(377, 6, 'Logged in', '2025-09-09 17:01:19'),
(378, 6, 'Logged out', '2025-09-09 17:01:34'),
(379, 4, 'Logged in', '2025-09-09 17:01:43'),
(380, 4, 'Logged out', '2025-09-09 20:09:55'),
(381, 6, 'Logged in', '2025-09-09 20:10:00'),
(382, 6, 'Logged out', '2025-09-09 20:10:11'),
(383, 4, 'Logged in', '2025-09-09 20:10:16'),
(384, 4, 'Logged out', '2025-09-09 20:11:31'),
(385, 1, 'Logged in', '2025-09-09 21:44:40'),
(386, 1, 'Updated account info', '2025-09-09 21:45:10'),
(387, 1, 'Updated account info', '2025-09-09 21:45:17'),
(388, 1, 'Changed password', '2025-09-09 21:45:31'),
(389, 1, 'Logged out', '2025-09-09 21:45:33'),
(390, 1, 'Logged in', '2025-09-09 21:45:36'),
(391, 1, 'Logged out', '2025-09-09 21:45:49'),
(392, 6, 'Logged in', '2025-09-09 21:45:55'),
(393, 6, 'Updated account info', '2025-09-09 21:46:08'),
(394, 6, 'Changed password', '2025-09-09 21:46:19'),
(395, 6, 'Logged out', '2025-09-09 21:46:21'),
(396, 4, 'Logged in', '2025-09-09 21:46:27'),
(397, 4, 'Updated account info', '2025-09-09 21:47:48'),
(398, 4, 'Changed password', '2025-09-09 21:47:56'),
(399, 4, 'Logged out', '2025-09-09 21:48:05'),
(400, 4, 'Logged in', '2025-09-09 21:48:13'),
(401, 4, 'Logged out', '2025-09-09 21:48:30'),
(402, 4, 'Logged in', '2025-09-09 21:54:15'),
(403, 4, 'Logged out', '2025-09-09 22:26:25'),
(404, 6, 'Logged in', '2025-09-09 22:26:36'),
(405, 6, 'Logged out', '2025-09-09 22:31:12'),
(406, 1, 'Logged in', '2025-09-09 22:31:19'),
(407, 1, 'Logged out', '2025-09-09 22:31:27'),
(408, 6, 'Logged in', '2025-09-09 22:31:37'),
(409, 6, 'Logged out', '2025-09-09 22:33:33'),
(410, 4, 'Logged in', '2025-09-09 22:33:38'),
(411, 4, 'Logged out', '2025-09-09 23:01:17'),
(412, 6, 'Logged in', '2025-09-09 23:01:25'),
(413, 6, 'Updated student ID 21', '2025-09-09 23:01:43'),
(414, 6, 'Logged out', '2025-09-09 23:01:45'),
(415, 4, 'Logged in', '2025-09-09 23:01:50'),
(416, 4, 'Logged out', '2025-09-10 10:25:05'),
(417, 1, 'Logged in', '2025-09-10 10:25:14'),
(418, 1, 'Logged out', '2025-09-10 10:25:28'),
(419, 6, 'Logged in', '2025-09-10 10:25:33'),
(420, 6, 'Logged out', '2025-09-10 11:08:52'),
(421, 4, 'Logged in', '2025-09-10 11:08:59'),
(422, 1, 'Logged in', '2025-09-17 11:21:18'),
(423, 1, 'Logged out', '2025-09-17 11:26:55'),
(424, 6, 'Logged in', '2025-09-17 11:27:29'),
(425, 6, 'Logged out', '2025-09-17 15:49:36'),
(426, 4, 'Logged in', '2025-09-17 15:49:41'),
(427, 4, 'Logged out', '2025-09-17 17:48:11'),
(428, 8, 'Logged in', '2025-09-17 17:50:32'),
(429, 8, 'Logged out', '2025-09-17 17:51:58'),
(430, 4, 'Logged in', '2025-09-17 17:52:09'),
(431, 4, 'Logged out', '2025-09-17 19:41:28'),
(432, 6, 'Logged in', '2025-09-17 19:41:37'),
(433, 6, 'Logged out', '2025-09-17 19:42:06'),
(434, 4, 'Logged in', '2025-09-17 19:42:15'),
(435, 4, 'Logged out', '2025-09-17 19:47:08'),
(436, 6, 'Logged in', '2025-09-17 19:47:14'),
(437, 6, 'Added student: Roxy Roller', '2025-09-17 19:47:56'),
(438, 6, 'Logged out', '2025-09-17 19:49:23'),
(439, 4, 'Logged in', '2025-09-17 19:49:28'),
(440, 4, 'Logged out', '2025-09-17 20:17:05'),
(441, 1, 'Logged in', '2025-09-17 20:17:08'),
(442, 1, 'Logged out', '2025-09-17 20:17:37'),
(443, 3, 'Logged in', '2025-09-17 20:25:06'),
(444, 3, 'Logged out', '2025-09-17 20:27:56'),
(445, 6, 'Logged in', '2025-09-17 20:28:05'),
(446, 6, 'Logged out', '2025-09-17 20:32:21'),
(447, 3, 'Logged in', '2025-09-17 20:32:25'),
(448, 3, 'Updated account info', '2025-09-17 20:36:15'),
(449, 3, 'Logged in', '2025-09-17 20:36:22'),
(450, 3, 'Updated account info', '2025-09-17 20:38:14'),
(451, 3, 'Changed password', '2025-09-17 20:38:37'),
(452, 3, 'Changed password', '2025-09-17 20:38:44'),
(453, 3, 'Logged out', '2025-09-17 20:46:06'),
(454, 4, 'Logged in', '2025-09-17 20:46:11'),
(455, 4, 'Logged out', '2025-09-17 20:49:53'),
(456, 1, 'Logged in', '2025-09-17 20:49:59'),
(457, 1, 'Logged out', '2025-09-17 20:50:13'),
(458, 6, 'Logged in', '2025-09-17 20:50:22'),
(459, 6, 'Logged out', '2025-09-17 21:03:36'),
(460, 4, 'Logged in', '2025-09-17 21:03:40'),
(461, 4, 'Logged out', '2025-09-17 21:10:38'),
(462, 6, 'Logged in', '2025-09-17 21:10:42'),
(463, 6, 'Logged out', '2025-09-17 21:22:06'),
(464, 4, 'Logged in', '2025-09-17 21:22:12'),
(465, 4, 'Logged out', '2025-09-17 22:28:08'),
(466, 3, 'Logged in', '2025-09-17 22:28:12'),
(467, 3, 'Logged out', '2025-09-17 22:42:44'),
(468, 4, 'Logged in', '2025-09-17 22:42:50'),
(469, 4, 'Logged out', '2025-09-17 22:43:00'),
(470, 3, 'Logged in', '2025-09-17 22:43:08'),
(471, 3, 'Logged out', '2025-09-17 22:53:48'),
(472, 4, 'Logged in', '2025-09-17 22:54:00'),
(473, 4, 'Logged out', '2025-09-17 22:54:06'),
(474, 3, 'Logged in', '2025-09-17 22:54:12'),
(475, 3, 'Logged out', '2025-09-17 22:54:36'),
(476, 4, 'Logged in', '2025-09-17 22:54:45'),
(477, 4, 'Logged out', '2025-09-17 22:56:09'),
(478, 3, 'Logged in', '2025-09-17 22:56:13'),
(479, 3, 'Logged out', '2025-09-17 23:03:58'),
(480, 4, 'Logged in', '2025-09-17 23:04:15'),
(481, 4, 'Logged out', '2025-09-17 23:07:07'),
(482, 3, 'Logged in', '2025-09-17 23:07:14'),
(483, 3, 'Logged out', '2025-09-17 23:14:40'),
(484, 4, 'Logged in', '2025-09-17 23:14:47'),
(485, 4, 'Logged out', '2025-09-17 23:15:07'),
(486, 3, 'Logged in', '2025-09-17 23:15:11'),
(487, 3, 'Logged out', '2025-09-17 23:32:13'),
(488, 4, 'Logged in', '2025-09-17 23:32:27'),
(489, 4, 'Logged out', '2025-09-17 23:32:53'),
(490, 3, 'Logged in', '2025-09-17 23:33:14'),
(491, 3, 'Logged out', '2025-09-17 23:54:22'),
(492, 4, 'Logged in', '2025-09-17 23:54:27'),
(493, 4, 'Logged out', '2025-09-17 23:54:37'),
(494, 3, 'Logged in', '2025-09-17 23:54:44'),
(495, 3, 'Logged out', '2025-09-18 00:01:54'),
(496, 4, 'Logged in', '2025-09-18 00:02:00'),
(497, 4, 'Logged out', '2025-09-18 00:02:08'),
(498, 3, 'Logged in', '2025-09-18 00:02:13'),
(499, 3, 'Logged out', '2025-09-18 00:26:52'),
(500, 4, 'Logged in', '2025-09-18 00:26:58'),
(501, 4, 'Logged out', '2025-09-18 00:27:05'),
(502, 3, 'Logged in', '2025-09-18 00:27:11'),
(503, 3, 'Logged out', '2025-09-18 00:28:14'),
(504, 4, 'Logged in', '2025-09-18 00:28:20'),
(505, 4, 'Logged out', '2025-09-18 00:28:34'),
(506, 3, 'Logged in', '2025-09-18 00:28:46'),
(507, 3, 'Logged out', '2025-09-18 00:45:44'),
(508, 4, 'Logged in', '2025-09-18 00:45:49'),
(509, 4, 'Logged out', '2025-09-18 00:46:18'),
(510, 3, 'Logged in', '2025-09-18 00:46:32'),
(511, 3, 'Logged out', '2025-09-18 00:58:12'),
(512, 4, 'Logged in', '2025-09-18 00:58:18'),
(513, 4, 'Logged out', '2025-09-18 01:00:38'),
(514, 3, 'Logged in', '2025-09-18 01:00:43'),
(515, 3, 'Logged in', '2025-09-19 13:12:01'),
(516, 3, 'Logged out', '2025-09-19 13:34:40'),
(517, 4, 'Logged in', '2025-09-19 13:56:10'),
(518, 4, 'Logged out', '2025-09-19 13:56:51'),
(519, 3, 'Logged in', '2025-09-19 13:56:59'),
(520, 3, 'Logged out', '2025-09-19 14:56:05'),
(521, 4, 'Logged in', '2025-09-19 14:56:15'),
(522, 4, 'Logged out', '2025-09-19 14:57:22'),
(523, 3, 'Logged in', '2025-09-19 14:57:27'),
(524, 3, 'Logged out', '2025-09-19 15:15:20'),
(525, 4, 'Logged in', '2025-09-19 15:15:33'),
(526, 4, 'Logged out', '2025-09-19 15:17:26'),
(527, 3, 'Logged in', '2025-09-19 15:17:36'),
(528, 3, 'Logged out', '2025-09-19 15:52:55'),
(529, 4, 'Logged in', '2025-09-19 15:52:59'),
(530, 4, 'Logged out', '2025-09-19 15:53:30'),
(531, 3, 'Logged in', '2025-09-19 15:53:43'),
(532, 3, 'Logged out', '2025-09-19 16:05:26'),
(533, 4, 'Logged in', '2025-09-19 16:05:31'),
(534, 4, 'Logged out', '2025-09-19 16:05:49'),
(535, 3, 'Logged in', '2025-09-19 16:05:56'),
(536, 3, 'Logged out', '2025-09-19 16:07:55'),
(537, 4, 'Logged in', '2025-09-19 16:08:02'),
(538, 4, 'Logged out', '2025-09-19 16:10:10'),
(539, 3, 'Logged in', '2025-09-19 16:10:15'),
(540, 3, 'Logged out', '2025-09-19 16:27:53'),
(541, 4, 'Logged in', '2025-09-19 16:27:59'),
(542, 4, 'Logged out', '2025-09-19 16:29:04'),
(543, 3, 'Logged in', '2025-09-19 16:29:07'),
(544, 3, 'Logged out', '2025-09-19 16:49:33'),
(545, 1, 'Logged in', '2025-09-19 16:52:26'),
(546, 1, 'Logged out', '2025-09-19 16:53:27'),
(547, 6, 'Logged in', '2025-09-19 16:53:32'),
(548, 6, 'Logged out', '2025-09-19 16:54:30'),
(549, 4, 'Logged in', '2025-09-19 16:54:35'),
(550, 4, 'Logged out', '2025-09-19 16:55:46'),
(551, 3, 'Logged in', '2025-09-19 16:55:50'),
(552, 3, 'Logged out', '2025-09-19 16:57:58'),
(553, 1, 'Logged in', '2025-09-21 22:01:36'),
(554, 1, 'Logged out', '2025-09-21 22:01:58'),
(555, 6, 'Logged in', '2025-09-21 22:02:06'),
(556, 6, 'Logged out', '2025-09-21 22:03:45'),
(557, 4, 'Logged in', '2025-09-21 22:03:51'),
(558, 4, 'Logged out', '2025-09-21 22:07:39'),
(559, 8, 'Logged in', '2025-09-21 22:08:21'),
(560, 8, 'Logged out', '2025-09-21 22:08:46'),
(561, 3, 'Logged in', '2025-09-21 22:08:51'),
(562, 3, 'Logged out', '2025-09-21 22:14:25'),
(563, 4, 'Logged in', '2025-09-21 22:14:29'),
(564, 4, 'Logged out', '2025-09-21 22:14:57'),
(565, 3, 'Logged in', '2025-09-21 22:15:02'),
(566, 3, 'Logged out', '2025-09-21 23:04:39'),
(567, 1, 'Logged in', '2025-09-21 23:05:14'),
(568, 1, 'Logged out', '2025-09-21 23:05:39'),
(569, 9, 'Logged in', '2025-09-21 23:05:41'),
(570, 9, 'Logged in', '2025-09-21 23:05:45'),
(571, 9, 'Logged in', '2025-09-21 23:06:04'),
(572, 9, 'Logged in', '2025-09-21 23:06:41'),
(573, 9, 'Logged out', '2025-09-22 00:03:06'),
(574, 4, 'Logged in', '2025-09-22 00:03:24'),
(575, 4, 'Logged out', '2025-09-22 00:03:29'),
(576, 9, 'Logged in', '2025-09-22 08:22:52'),
(577, 9, 'Logged out', '2025-09-24 21:23:35'),
(578, 3, 'Logged in', '2025-09-24 21:25:29'),
(579, 3, 'Logged out', '2025-09-24 22:00:42'),
(580, 1, 'Logged in', '2025-09-24 22:00:46'),
(581, 1, 'Logged out', '2025-09-24 22:01:45'),
(582, 6, 'Logged in', '2025-09-24 22:01:51'),
(583, 6, 'Logged out', '2025-09-24 22:03:06'),
(584, 4, 'Logged in', '2025-09-24 22:03:10'),
(585, 4, 'Logged out', '2025-09-24 22:05:57'),
(586, 3, 'Logged in', '2025-09-24 22:06:01'),
(587, 3, 'Logged out', '2025-09-24 22:07:37'),
(588, 4, 'Logged in', '2025-09-24 22:07:43'),
(589, 4, 'Logged out', '2025-09-24 22:08:27'),
(590, 3, 'Logged in', '2025-09-24 22:08:31'),
(591, 3, 'Logged out', '2025-09-24 22:09:59'),
(592, 4, 'Logged in', '2025-09-24 22:10:12'),
(593, 4, 'Logged out', '2025-09-24 22:10:31'),
(594, 3, 'Logged in', '2025-09-24 22:10:37'),
(595, 3, 'Logged out', '2025-09-24 22:27:58'),
(596, 9, 'Logged in', '2025-09-24 22:28:00'),
(597, 9, 'Logged out', '2025-09-24 22:28:26'),
(598, 9, 'Logged in', '2025-09-24 22:28:29'),
(599, 9, 'Updated account info', '2025-09-24 22:30:06'),
(600, 9, 'Logged in', '2025-09-24 22:30:46'),
(601, 9, 'Updated account info', '2025-09-24 22:30:58'),
(602, 9, 'Changed password', '2025-09-24 22:31:22'),
(603, 9, 'Changed password', '2025-09-24 22:31:30'),
(604, 9, 'Logged out', '2025-09-24 22:32:37'),
(605, 3, 'Logged in', '2025-09-24 22:32:41'),
(606, 3, 'Logged out', '2025-09-24 22:39:35'),
(607, 9, 'Logged in', '2025-09-24 22:39:38'),
(608, 9, 'Logged out', '2025-09-24 22:42:52'),
(609, 3, 'Logged in', '2025-09-24 22:42:56'),
(610, 3, 'Logged out', '2025-09-24 22:54:46'),
(611, 9, 'Logged in', '2025-09-24 22:54:51'),
(612, 9, 'Logged out', '2025-09-24 22:57:44'),
(613, 3, 'Logged in', '2025-09-24 22:57:48'),
(614, 3, 'Logged out', '2025-09-24 22:57:56'),
(615, 9, 'Logged in', '2025-09-24 22:57:59'),
(616, 9, 'Logged out', '2025-09-24 22:58:07'),
(617, 3, 'Logged in', '2025-09-24 22:58:14'),
(618, 3, 'Logged out', '2025-09-24 22:58:29'),
(619, 4, 'Logged in', '2025-09-24 22:58:34'),
(620, 4, 'Logged out', '2025-09-24 22:59:01'),
(621, 9, 'Logged in', '2025-09-24 22:59:04'),
(622, 9, 'Logged out', '2025-09-24 22:59:10'),
(623, 3, 'Logged in', '2025-09-24 23:01:59'),
(624, 3, 'Logged out', '2025-09-24 23:02:41'),
(625, 9, 'Logged in', '2025-09-24 23:02:43'),
(626, 9, 'Updated account info', '2025-09-24 23:21:04'),
(627, 9, 'Logged out', '2025-09-24 23:21:23'),
(628, 9, 'Logged in', '2025-09-24 23:21:29');

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
(2, 'BSED', 'Bachelor of Science in Secondary Education'),
(3, 'BSCRIM', 'Bachelor of Science in Criminology'),
(4, 'BSTM', 'Bachelor of Science in Tourism Management'),
(5, 'BSN', 'Bachelor of Science in Nursing');

-- --------------------------------------------------------

--
-- Table structure for table `record_violations`
--

CREATE TABLE `record_violations` (
  `id` int(11) NOT NULL,
  `student_violations_id` int(11) NOT NULL,
  `action_taken` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `date_recorded` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `record_violations`
--

INSERT INTO `record_violations` (`id`, `student_violations_id`, `action_taken`, `remarks`, `date_recorded`, `user_id`, `school_year_id`, `status`) VALUES
(4, 37, 'Suspension', '1 day only', '2025-09-24 14:11:02', 3, 2, 'Resolved');

-- --------------------------------------------------------

--
-- Table structure for table `resolved_cases`
--

CREATE TABLE `resolved_cases` (
  `id` int(11) NOT NULL,
  `record_violation_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `date_resolved` timestamp NOT NULL DEFAULT current_timestamp(),
  `school_year_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resolved_cases`
--

INSERT INTO `resolved_cases` (`id`, `record_violation_id`, `status`, `date_resolved`, `school_year_id`) VALUES
(5, 4, 'Resolved', '2025-09-24 15:16:09', 2);

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
(3, 'B'),
(5, 'C'),
(6, 'D');

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
(5, 'Hello', 'World', 2, 1, 2, 2, 'Manila Philippines', '09303172724', '', 6),
(6, 'Daniel', 'Padilla', 2, 2, 1, 3, 'Quezon City', '09123456789', '', 6),
(8, 'Ronald', 'Rosales', 2, 1, 1, 2, 'Toledo Cebu', '09123456789', '', 6),
(9, 'Mark', 'Saragosa', 2, 1, 1, 2, 'Naga Cebu', '09123456789', '', 6),
(17, 'Emman', 'Bas', 2, 1, 2, 2, 'Poblacion Minglanilla Cebu', '09123456789', '', 6),
(20, 'Robin', 'Padilla', 1, 2, 1, 2, 'Manila Philippines', '09123456789', '', 6),
(21, 'John', 'Doe', 2, 2, 2, 3, 'Naga Cebu', '09123456789', '', 6),
(23, 'John', 'Dave', 2, 2, 1, 3, 'Minglanilla Cebu', '09123456789', '', 6),
(28, 'Roxy', 'Roller', 1, 2, 1, 2, 'Cebu Philippines', '09123456789', '', 6);

-- --------------------------------------------------------

--
-- Table structure for table `student_enrollments`
--

CREATE TABLE `student_enrollments` (
  `id` int(11) NOT NULL,
  `class_enrollment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_enrollments`
--

INSERT INTO `student_enrollments` (`id`, `class_enrollment_id`, `student_id`, `enrolled_at`) VALUES
(4, 3, 21, '2025-09-08 15:28:06'),
(8, 6, 20, '2025-09-17 12:03:40'),
(9, 6, 28, '2025-09-17 12:03:40'),
(10, 7, 8, '2025-09-17 12:06:01'),
(11, 7, 9, '2025-09-17 12:06:01');

-- --------------------------------------------------------

--
-- Table structure for table `student_violations`
--

CREATE TABLE `student_violations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `violation_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(50) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `school_year_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_violations`
--

INSERT INTO `student_violations` (`id`, `student_id`, `violation_id`, `description`, `location`, `date_time`, `status`, `user_id`, `school_year_id`) VALUES
(35, 28, 1, 'Cheating', 'Room 101', '2025-09-17 20:08:19', 'Pending', 4, 1),
(37, 9, 4, 'No uniform on Monday', 'Campus', '2025-09-19 15:53:22', 'Recorded', 4, 2);

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
(1, 'admin', '$2y$10$ov6ePzMX3b96jDzNgEXGFuuHHAvKRJ6E7BPyhb6505aYKbHkmsZAW', 'admin', 'administrator@gmail.com', '09303172724', 'active', '../studisciplink/userUploads/download (2).jpg'),
(2, 'patriciaobaob', '$2y$10$AWfFB3AaX0oflt9PmLOGBeomgKpLGlL5ez6lednMoafVYmqZURIlm', 'sao', 'patobaob@gmail.com', '09123456789', 'pending', ''),
(3, 'guidance', '$2y$10$ql2NM0Hd3kZXf.5ML.Ch7ute/nGm9qDckx0PJ5P2Xqjj/iCs0KE7y', 'guidance', 'guidance@gmail.com', '09987654321', 'active', '../studisciplink/userUploads/92700584-e219-4775-b0b1-1b75cd3ee529.jpg'),
(4, 'faculty', '$2y$10$GToR8dPdBzjm9mnaZGx9peH7R10B6lF7pUVZpjSBa8FmK5.yqCEDy', 'faculty', 'faculty@gmail.com', '09123456789', 'active', '../studisciplink/userUploads/f56486c5427dc5a7ed81252862d87c96.jpg'),
(5, 'jaymaicanarvasa', '$2y$10$IFv1MHhxKCqhtUskH3w0tez3x9.yC6i9UqybW8Rf6LA3paRdQ/dve', 'admin', 'jaymaica@gmail.com', '09987654321', 'active', ''),
(6, 'registrar', '$2y$10$Pej8sUm0a396ljujUBphwuk09/TkrT4Aml42V7HAnqyDb3rxugk9m', 'registrar', 'registrar@gmail.com', '09123456789', 'active', '../studisciplink/userUploads/6843dc0b5e4341f168aac30144c56418.jpg'),
(7, 'jaylonmantillas', '$2y$10$ieINs2o2zZcC/bi3N50hbOsYDoy6jtCN8AUbnGS8sL3juNhQQDmoK', 'admin', 'jaylon@gmail.com', '09987654321', 'pending', ''),
(8, 'johndoe', '$2y$10$Z3V1iXmOxKJT6DAOFksm3.IkZuJXHYJ77uDR3tU1HOWzJ44UDPD7W', 'faculty', 'johndoe@gmail.com', '09123456789', 'active', ''),
(9, 'sao', '$2y$10$XWtIE.mkkS2u1kUfBv4oNuESZkXN.YwxokO3efx/WGxYsdH7Gj4Uy', 'sao', 'sao@gmail.com', '09123456789', 'active', '../studisciplink/userUploads/office-cat-scaled.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `violations`
--

CREATE TABLE `violations` (
  `id` int(11) NOT NULL,
  `violation` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `violations`
--

INSERT INTO `violations` (`id`, `violation`) VALUES
(1, 'Cheating'),
(2, 'Vandalism'),
(3, 'No ID'),
(4, 'Improper wearing of uniform');

-- --------------------------------------------------------

--
-- Table structure for table `year_levels`
--

CREATE TABLE `year_levels` (
  `id` int(11) NOT NULL,
  `year_level` varchar(50) NOT NULL,
  `year_code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `year_levels`
--

INSERT INTO `year_levels` (`id`, `year_level`, `year_code`) VALUES
(1, 'First Year', '1'),
(2, 'Second Year', '2'),
(3, 'Third Year', '3'),
(4, 'Fourth Year', '4');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class_enrollments`
--
ALTER TABLE `class_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `year_level_id` (`year_level_id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `school_year_id` (`school_year_id`);

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
-- Indexes for table `record_violations`
--
ALTER TABLE `record_violations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_violations_id` (`student_violations_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `school_year_id` (`school_year_id`);

--
-- Indexes for table `resolved_cases`
--
ALTER TABLE `resolved_cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `record_violation_id` (`record_violation_id`),
  ADD KEY `resolved_cases_school_year_id_fr` (`school_year_id`);

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
-- Indexes for table `student_enrollments`
--
ALTER TABLE `student_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_enrollment_id` (`class_enrollment_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_violations`
--
ALTER TABLE `student_violations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `violation_id` (`violation_id`),
  ADD KEY `timestamp` (`date_time`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `school_year_id` (`school_year_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `violations`
--
ALTER TABLE `violations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `year_levels`
--
ALTER TABLE `year_levels`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class_enrollments`
--
ALTER TABLE `class_enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=629;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `record_violations`
--
ALTER TABLE `record_violations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `resolved_cases`
--
ALTER TABLE `resolved_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `school_years`
--
ALTER TABLE `school_years`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `student_enrollments`
--
ALTER TABLE `student_enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `student_violations`
--
ALTER TABLE `student_violations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `violations`
--
ALTER TABLE `violations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `year_levels`
--
ALTER TABLE `year_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_enrollments`
--
ALTER TABLE `class_enrollments`
  ADD CONSTRAINT `class_enrollments_program_id_fr` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`),
  ADD CONSTRAINT `class_enrollments_school_year_id_fr` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`),
  ADD CONSTRAINT `class_enrollments_section_id_fr` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`),
  ADD CONSTRAINT `class_enrollments_user_id_fr` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `class_enrollments_year_level_id_fr` FOREIGN KEY (`year_level_id`) REFERENCES `year_levels` (`id`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_user_id_fr` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `record_violations`
--
ALTER TABLE `record_violations`
  ADD CONSTRAINT `record_violations_school_year_id_fr` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`),
  ADD CONSTRAINT `record_violations_student_violations_id_fr` FOREIGN KEY (`student_violations_id`) REFERENCES `student_violations` (`id`),
  ADD CONSTRAINT `record_violations_user_id_fr` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `resolved_cases`
--
ALTER TABLE `resolved_cases`
  ADD CONSTRAINT `resolved_cases_record_violation_id_fr` FOREIGN KEY (`record_violation_id`) REFERENCES `record_violations` (`id`),
  ADD CONSTRAINT `resolved_cases_school_year_id_fr` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_program_id_fr` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`),
  ADD CONSTRAINT `students_school_year_id_fr` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`),
  ADD CONSTRAINT `students_section_id` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`),
  ADD CONSTRAINT `students_user_id_fr` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `students_year_level_id_fr` FOREIGN KEY (`year_level_id`) REFERENCES `year_levels` (`id`);

--
-- Constraints for table `student_enrollments`
--
ALTER TABLE `student_enrollments`
  ADD CONSTRAINT `student_enrollments_class_enrollment_id_fr` FOREIGN KEY (`class_enrollment_id`) REFERENCES `class_enrollments` (`id`),
  ADD CONSTRAINT `student_enrollments_student_id_fr` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `student_violations`
--
ALTER TABLE `student_violations`
  ADD CONSTRAINT `student_violations_school_year_id_fr` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`),
  ADD CONSTRAINT `student_violations_student_id_fr` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `student_violations_user_id_fr` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `student_violations_violation_id_fr` FOREIGN KEY (`violation_id`) REFERENCES `violations` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
