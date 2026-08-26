-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2026 at 08:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET
SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET
time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tms`
--

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms`
(
    `id`            int(11) NOT NULL,
    `name`          text NOT NULL,
    `capacity`      int(11) NOT NULL,
    `current_load`  int(11) NOT NULL DEFAULT 0,
    `supervisor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `capacity`, `current_load`, `supervisor_id`)
VALUES (1, 'DN0901', 300, 0, 7),
       (2, 'DN0902', 300, 0, 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users`
(
    `id`            int(11) NOT NULL,
    `uni_id`        text     NOT NULL,
    `fullname`      text     NOT NULL,
    `password`      text     NOT NULL,
    `created_at`    datetime NOT NULL DEFAULT current_timestamp(),
    `role`          text     NOT NULL,
    `assigned_room` text              DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uni_id`, `fullname`, `password`, `created_at`, `role`, `assigned_room`)
VALUES (5, '24-57775-2', 'arijit', '123', '2026-08-26 00:56:31', 'student', NULL),
       (6, '2412-2509-2', 'Sajid Uddin', 'abc', '2026-08-26 00:57:33', 'teacher', NULL),
       (7, '3333-3333-2', 'Dr. Mahfuza Khatun', 'abc', '2026-08-26 00:57:33', 'supervisor', NULL),
       (8, '3333-4444-2', 'Md. Asaduzzaman Khan', 'abc', '2026-08-26 00:57:33', 'supervisor', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supervisor_id` (`supervisor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
    ADD CONSTRAINT `fk_supervisor_id` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
