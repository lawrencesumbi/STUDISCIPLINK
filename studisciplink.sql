-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2025 at 04:19 PM
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
(88, 6, 'Deleted student ID 2: Daniel Padilla', '2025-09-03 22:18:42');

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
(1, 'BSIT', 'Bachelor of Science in Information Technology');

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
(2, '2025-2026', 1);

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
(2, 'A');

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
(5, 'Coco', 'Martin', 2, 1, 2, 2, 'Manilla Philippines', '09123456789', '', 6);

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
(1, 'lawrencesumbi', '$2y$10$J6OtiLmeKknSeAsAb7yddehn1ZFlCuS4ybOvjqRXU/hazPJznKvXi', 'admin', 'guiansumbi@gmail.com', '09753140724', 'active', '../studisciplink/userUploads/homeIMG.jpg'),
(2, 'patriciaobaob', '$2y$10$AWfFB3AaX0oflt9PmLOGBeomgKpLGlL5ez6lednMoafVYmqZURIlm', 'sao', '', '', 'pending', ''),
(3, 'lawrenceguian', '$2y$10$PNQ.mHdPylHsdTWcJxjkoOWjdwFs4jnVEFpQXoAzRvVuBDkxUoavO', 'guidance', '', '', 'pending', ''),
(4, 'davidvergara', '$2y$10$NskSedV6lqQnqBvSVVJXj.cG7Y4rhntTKxc0icydSwvKyXdV8mN0S', 'faculty', '', '', 'pending', ''),
(5, 'jaymaicanarvasa', '$2y$10$IFv1MHhxKCqhtUskH3w0tez3x9.yC6i9UqybW8Rf6LA3paRdQ/dve', 'admin', 'jaymaica@gmail.com', '09987654321', 'active', ''),
(6, 'draymisa', '$2y$10$Uegpk.88TaBNKRLoy.bd.OIGaWyBTqhf8u0V.E5TNwBXuD6FOcj66', 'registrar', '', '', 'active', ''),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `school_years`
--
ALTER TABLE `school_years`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
