-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2024 at 05:57 PM
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
-- Database: `user_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile` varchar(10) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `mobile`, `gender`) VALUES
(1, 'John', 'john@gmail.com', '$2y$10$qQdRVDkm.sVTpe1ht/v28OLHZATQv26nF4u9pSHWmSx9cfqz9sa9K', '1234567890', 'Male'),
(2, 'Zill ', 'Zill@gmail.com', '$2y$10$dejhnbn23SuVF5M8QmwADORNUnh.r3rJOxRbS17pF7KucUMxDczu6', '9244567899', 'Female'),
(3, 'Sharukh', 'sharukh@gmail.com', '$2y$10$A73RpxdVfEsZAV1I4dFW2.S97ICG3iqhTL3sb9pp2oyUnDyADGqiG', '7894561230', 'Other'),
(4, 'abhishek', 'abhishek@bachan.com', '$2y$10$vtMpVpQILOYzqICo1d8t5uQqq5QZ.kOIvb8KOwiNPsyLnpquoVsfq', '7894562014', 'Other'),
(5, 'aishwarya', 'aishwarya@rai.ceom', '$2y$10$SXwpsVNRsH9bnyVsLWDAB.9/hzq3iH1Yc.QpmwTu1Gs4hUwszNDTO', '8882584500', 'Female'),
(6, 'Salman1', 'salman1@test.com', '$2y$10$W1eUZl/JzbkS1bRL8rDRe.6W9lcJtzOHO100N1CAgHr/F4nW8sET.', '9173596827', 'Male'),
(8, 'final', 'final@test.com', '$2y$10$K0fMAC8qdVPxtwA.Xjd17er/SEO0gO/ZBA08gxPH2qGAiGjE2i6hm', '8523697410', 'Other'),
(9, 'test1', 'final1@test.com', '$2y$10$AB.jI8jIAVE.p4l7miV4y.iOqN8rBLLNLeNljrDCOPE99y2tCy1da', '7418523691', 'Female'),
(10, 'findad2', 'findadl2@test.in', '$2y$10$6F92WUlpwMffsATEvmmA/e3eCufMiJ7u/EFFapPhYWO9E/eG2HMjC', '7894561231', 'Male');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
