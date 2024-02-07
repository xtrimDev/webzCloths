-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 07, 2024 at 06:18 PM
-- Server version: 8.0.27
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

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
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
                                                    (4, 'Girl', 0);

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
    ) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `featured`
--

INSERT INTO `featured` (`id`, `product_category`, `text`, `poster_type`, `description`, `poster`) VALUES
                                                                                                      (1, '{\"0\": \"1\", \"1\": \"3\"}', 'style and comfort', 'big', 'Be the best-dressed man in the room.', 'mensfashionbaner.webp'),
                                                                                                      (2, '{\"0\": \"1\", \"1\": \"3\"}', 'Checkout for your kids, here are all for kids.', 'small', 'Checkout for your kids, here are all for kids.', 'Kids-Program-Introduction.jpg'),
                                                                                                      (3, '{\"0\": \"1\", \"1\": \"3\"}', 'World of Kids Fashion, checkout once.', 'extra small', 'World of Kids Fashion, checkout once.', 'kidz.jpg');

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
    `poster` varchar(200) NOT NULL,
    `removed` enum('0','1','','') NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
    `user_type` enum('-1','0','1','') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`),
    UNIQUE KEY `unique_name` (`unique_name`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;
