-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2025 at 04:50 AM
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
(32, 5, 'Logged in', '2025-09-03 10:50:05');

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
(1, 'lawrencesumbi', '$2y$10$J6OtiLmeKknSeAsAb7yddehn1ZFlCuS4ybOvjqRXU/hazPJznKvXi', 'guidance', '', '', 'pending', ''),
(2, 'patriciaobaob', '$2y$10$AWfFB3AaX0oflt9PmLOGBeomgKpLGlL5ez6lednMoafVYmqZURIlm', 'sao', '', '', 'pending', ''),
(3, 'lawrenceguian', '$2y$10$PNQ.mHdPylHsdTWcJxjkoOWjdwFs4jnVEFpQXoAzRvVuBDkxUoavO', 'registrar', '', '', 'pending', ''),
(4, 'davidvergara', '$2y$10$NskSedV6lqQnqBvSVVJXj.cG7Y4rhntTKxc0icydSwvKyXdV8mN0S', 'faculty', '', '', 'pending', ''),
(5, 'jaymaicanarvasa', '$2y$10$IFv1MHhxKCqhtUskH3w0tez3x9.yC6i9UqybW8Rf6LA3paRdQ/dve', 'admin', 'jaymaica@gmail.com', '09987654321', 'active', ''),
(6, 'draymisa', '$2y$10$Uegpk.88TaBNKRLoy.bd.OIGaWyBTqhf8u0V.E5TNwBXuD6FOcj66', 'admin', '', '', 'pending', ''),
(7, 'jaylonmantillas', '$2y$10$ieINs2o2zZcC/bi3N50hbOsYDoy6jtCN8AUbnGS8sL3juNhQQDmoK', 'admin', 'jaylon@gmail.com', '09987654321', 'active', '');

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_user_id_fr` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
