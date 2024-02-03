-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 03, 2024 at 05:58 AM
-- Server version: 8.0.27
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webzcloths`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `p_id` int NOT NULL,
  `u_id` int NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `p_id`, `u_id`, `added_on`) VALUES
(23, 1, 1, '2024-02-03 05:56:37'),
(21, 2, 1, '2024-02-03 05:54:57');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(200) NOT NULL,
  `parent` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `type`, `parent`) VALUES
(1, 'men', 0),
(2, 'women', 0),
(3, 'boy', 0),
(4, 'Girl', 0),
(5, 'T-shirt', 3),
(6, 'hoodie', 3);

-- --------------------------------------------------------

--
-- Table structure for table `featured`
--

DROP TABLE IF EXISTS `featured`;
CREATE TABLE IF NOT EXISTS `featured` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_category` json NOT NULL,
  `text` varchar(200) NOT NULL,
  `poster_type` enum('big','small','extra small','') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` varchar(300) NOT NULL,
  `poster` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `featured`
--

INSERT INTO `featured` (`id`, `product_category`, `text`, `poster_type`, `description`, `poster`) VALUES
(1, '{\"0\": \"1\", \"1\": \"3\"}', 'style and comfort', 'big', 'Be the best-dressed man in the room.', 'mensfashionbaner.webp');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `desc` json NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `added_by` int NOT NULL,
  `category_id` json NOT NULL,
  `price` int NOT NULL,
  `age_from` int NOT NULL,
  `age_to` int NOT NULL,
  `poster` varchar(200) NOT NULL,
  `removed` enum('0','1','','') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `desc`, `added_at`, `added_by`, `category_id`, `price`, `age_from`, `age_to`, `poster`, `removed`) VALUES
(1, 'Spider man - T Shirt', '{\"brand\": \"Nike\", \"color\": \"red\", \"style\": \"casual\", \"made in\": \"India\", \"material type\": \"Knitted Textile Mesh upper\"}', '2024-02-01 08:05:14', 1, '{\"category\": [\"1\", \"3\"]}', 550, 10, 15, '2024_02_01.jpg', '0'),
(2, 'Redwolf Premium Hoodie', '{\"color\": \"black\", \"fabric\": \" 300 GSM Brushed Fleece\", \"made in\": \"India\", \"Printing\": \"Silk Screen Printed\"}', '2024-02-01 08:05:15', 1, '{\"category\": [\"2\", \"3\"]}', 990, 10, 15, '2024_02_01_9_30.webp', '0');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `unique_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_img` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `registered_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('-1','0','1','') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `filter` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` enum('-1','0','1','') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `unique_name` (`unique_name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `unique_name`, `name`, `email`, `password`, `profile_img`, `registered_on`, `status`, `filter`, `user_type`) VALUES
(1, 'bhandarisameer', 'Sameer singh bhandari', 'bhandarisameer@gmail.com', 'a9b685a9e8c2047d4b9faac6217d7b4a', '/assets/img/user/avatar-3.png', '2024-02-01 00:22:53', '1', '2:^$a||>@y', '0'),
(2, 'pixebo8067', 'pixebo8067', 'pixebo8067@gosarlar.com', 'ce5c590d1b9dbd384b00a461cdb3cbf4', '/assets/img/user/avatar-1.png', '2024-02-03 09:41:20', '1', 'z{v4&8u1h-', '-1'),
(3, 'gigob23180', 'gigob23180', 'gigob23180@flexvio.com', 'd12590a0bb3a010c02949dcafe5d1f85', '/assets/img/user/avatar-1.png', '2024-02-03 09:45:32', '1', '0^o+<[w`v3', '-1');

-- --------------------------------------------------------

--
-- Table structure for table `user_temp`
--

DROP TABLE IF EXISTS `user_temp`;
CREATE TABLE IF NOT EXISTS `user_temp` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `temp_code` int NOT NULL,
  `temp_verify` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_temp`
--

INSERT INTO `user_temp` (`id`, `user_id`, `temp_code`, `temp_verify`) VALUES
(1, 1, 386373, 'mji-u84j-@'),
(2, 2, 245477, 'vx!7@8%_pm'),
(3, 3, 944995, 'zp84n3@*s_');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
