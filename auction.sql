-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2024 at 10:33 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `auction`
--

-- --------------------------------------------------------

--
-- Table structure for table `auction`
--

CREATE TABLE `auction` (
  `auction_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `starting_price` decimal(10,2) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expire_number` int(11) NOT NULL,
  `expire_date` datetime DEFAULT NULL,
  `img_path` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `img_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `name`, `img_path`) VALUES
(24, 'Antikviteti', 'Antikviteti66449be5036866.71257760.jpg'),
(25, 'Slike', 'Slike66449bf686e156.64300080.jpg'),
(26, 'Alat', 'Alat66449bff1028a5.59216441.jpg'),
(27, 'Basta', 'Basta66449c05b70111.89652293.jpg'),
(28, 'Kompjuteri', 'Kompjuteri66449c1728ef66.69963096.jpg'),
(29, 'Kuhinja', 'Kuhinja66449c22a3efc9.54835327.jpg'),
(30, 'Kancelarija', 'Kancelarija66449c2b0a3623.61712601.jpg'),
(31, 'Sport', 'Sport66449c3a2494a9.48391845.jpg'),
(32, 'Gaming', 'Gaming66449c5fa5de97.17907634.jpg'),
(33, 'Drveni predmeti', 'Drveni predmeti66449cdcf05983.28854207.jpg'),
(34, 'Kucne stvari', 'Kucne stvari66449d8bbb4f87.51793250.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `offer`
--

CREATE TABLE `offer` (
  `offer_id` int(10) UNSIGNED NOT NULL,
  `auction_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `price` decimal(10,2) UNSIGNED NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `forename` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_moderator` tinyint(1) NOT NULL DEFAULT 0,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `forename`, `surname`, `phone`, `email`, `username`, `password_hash`, `is_admin`, `is_moderator`, `creation_date`) VALUES
(7, 'admin', 'admin', 'admin', 'admin', 'admin', '$2y$10$XOPYiOomP3HP99VRogvGY.H54HFhnhWREXV.YWjvt.ChkIOUTYnqe', 1, 0, '2024-01-14 20:13:21'),
(20, 'Pera', 'Peric', '+381 0673213122', 'pera11@gmail.com', 'Pera11', '$2y$10$g.CbjgD5WKG4thu0aTt/xOnaWkATGYOH8B4Np000O.vwRo15mpZSK', 0, 0, '2024-05-15 11:34:48'),
(21, 'Mika', 'Mikic', '+381 0623213122', 'mika22@gmail.com', 'Mika22', '$2y$10$fGMStYD9RdP9BV89lXEi0uX.pl4M6GILThSFGf6UR.e2UzN3IfGcG', 0, 0, '2024-05-15 11:35:21'),
(22, 'Zika', 'Zikic', '+381 0634123132', 'zika33@gmail.com', 'Zika33', '$2y$10$ppz3x4vJaR57/6a82H.iouMxSrZ0UeKEuH66ujYIWxstmNm9PimcW', 0, 0, '2024-05-15 11:35:57'),
(23, 'Ana', 'Anic', '+381 0632312321', 'ana444@gamil.com', 'Ana444', '$2y$10$BJAAA8002N6zEanMomcR6.tJeHQ6jzyBVz4V.WEdN48vpmzYJQ12.', 0, 0, '2024-05-15 11:37:03'),
(24, 'Filip', 'Filipovic', '+381 065421341', 'fiki@gmail.com', 'Fiki10', '$2y$10$b4Zd26snNH61Lj1JIGm/9OY7BVzUHQ8xgmTf9IeYxwNCP/b0cZRpK', 0, 0, '2024-05-15 11:37:44'),
(25, 'Moderator', 'Moderator', '+381 0642141232', 'moderator01@gmail.com', 'Moderator01', '$2y$10$sVkwqY9RujGFvI1Q4d6X1O5SBOC7H.5wsZPvYgp9SzQtsLY.0ic02', 0, 1, '2024-05-15 12:01:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auction`
--
ALTER TABLE `auction`
  ADD PRIMARY KEY (`auction_id`),
  ADD KEY `fk_auction_category_id` (`category_id`),
  ADD KEY `fk_auction_user_id` (`user_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `uq_category_name` (`name`);

--
-- Indexes for table `offer`
--
ALTER TABLE `offer`
  ADD PRIMARY KEY (`offer_id`),
  ADD KEY `fk-offer_auction_id` (`auction_id`),
  ADD KEY `fk_offer_user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `uq_user_username` (`username`),
  ADD UNIQUE KEY `uq_user_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auction`
--
ALTER TABLE `auction`
  MODIFY `auction_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `offer`
--
ALTER TABLE `offer`
  MODIFY `offer_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auction`
--
ALTER TABLE `auction`
  ADD CONSTRAINT `fk_auction_category_id` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_auction_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `offer`
--
ALTER TABLE `offer`
  ADD CONSTRAINT `fk-offer_auction_id` FOREIGN KEY (`auction_id`) REFERENCES `auction` (`auction_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_offer_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
