-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2024 at 12:35 AM
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
-- Database: `powerhuff_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_on` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`, `created_on`, `updated_on`) VALUES
(1, 'Pods', 'Pods for vaping devices', '2024-11-24 22:18:02', NULL),
(2, 'Batteries', 'Replacement and high-capacity batteries for vapes', '2024-11-24 22:18:02', NULL),
(3, 'Devices', 'Vape devices including mods, pods, and starter kits', '2024-11-24 22:18:02', NULL),
(4, 'Vapes', 'Vape devices, including mods, pods, and starter kits', '2024-12-08 22:13:01', NULL),
(5, 'Accessories', 'Vape accessories like coils, tanks, and chargers', '2024-12-08 22:13:01', NULL),
(6, 'E-Liquids', 'Liquid for vaping devices', '2024-12-08 22:47:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `login_activity`
--

CREATE TABLE `login_activity` (
  `login_activityID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_type` enum('Login','Logout') NOT NULL,
  `logged_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_activity`
--

INSERT INTO `login_activity` (`login_activityID`, `user_id`, `activity_type`, `logged_time`) VALUES
(1, 68, 'Logout', '2024-12-09 02:25:33'),
(2, 69, 'Login', '2024-12-09 02:25:38'),
(3, 69, 'Logout', '2024-12-09 02:27:00'),
(4, 68, 'Login', '2024-12-09 02:27:03'),
(5, 68, 'Logout', '2024-12-09 05:04:52'),
(6, 68, 'Login', '2024-12-09 05:04:54'),
(7, 68, 'Logout', '2024-12-09 07:28:45'),
(8, 2, 'Login', '2024-12-09 07:29:09');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notification_type` enum('info','warning','error') NOT NULL,
  `message` text NOT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('unread','read','resolved') NOT NULL,
  `resolved_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `order_date` datetime NOT NULL,
  `status` enum('Pending','Cancelled','Received') NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `updated_on` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_name`, `description`, `order_date`, `status`, `supplier_id`, `user_id`, `updated_on`) VALUES
(1, 'Mamba 15Kpuffs Pod and Accessories Order', 'Order for Mamba 15Kpuffs Pod and accessories', '2024-12-09 02:56:02', 'Pending', 1, 1, '2024-12-08 18:56:02'),
(2, 'Toha S10000 Device and Velo Recharge Battery Order', NULL, '2024-12-09 02:56:02', 'Received', 2, 2, '2024-12-08 18:56:02'),
(3, 'Nova 1500 Device and Minty Fresh E-Liquid Order', 'Order for Nova 1500 Device and Minty Fresh E-Liquid', '2024-12-09 02:56:02', 'Pending', 1, 68, '2024-12-08 18:56:02'),
(4, 'Berry Blast E-Liquid and Black Elite V2 Pod Order', 'Order for Berry Blast E-Liquid and Black Elite V2 Pod', '2024-12-09 02:56:02', 'Cancelled', 3, 4, '2024-12-08 18:56:02'),
(5, 'PowerMax 5000mAh Battery and Coils for Vapes Order', NULL, '2024-12-09 02:56:02', 'Received', 1, 69, '2024-12-08 18:56:02'),
(6, 'Toha X3000 Device and Charging Cable for Vapes Order', 'Order for Toha X3000 Device and Charging Cable for Vapes', '2024-12-09 02:56:02', 'Pending', 2, 1, '2024-12-08 18:56:02');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `order_itemID` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`order_itemID`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 10, 400.00),
(2, 1, 5, 5, 400.00),
(3, 2, 4, 15, 250.00),
(4, 2, 7, 10, 150.00),
(5, 3, 9, 20, 200.00),
(6, 3, 14, 5, 20.00),
(7, 4, 13, 30, 50.00),
(8, 4, 3, 5, 400.00),
(9, 5, 6, 25, 180.00),
(10, 5, 11, 10, 30.00),
(11, 6, 8, 15, 350.00),
(12, 6, 13, 10, 15.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `lowstock_threshold` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `stock_quantity`, `lowstock_threshold`, `supplier_id`, `created_on`, `updated_on`) VALUES
(1, 'Mamba 15Kpuffs Pod', 'A long-lasting pod offering 15,000 puffs', 400.00, 50, 10, 1, '2024-12-09 02:42:44', NULL),
(2, 'Mamba Elite 15K Battery', 'Designed to pair with the Mamba 15Kpuffs Pod for extended use', 250.00, 50, 10, 2, '2024-12-09 02:42:44', NULL),
(3, 'Black Elite V2 Pod', 'A sleek, premium pod system', 400.00, 45, 10, 3, '2024-12-09 02:42:44', NULL),
(4, 'Toha S10000 Device', 'A powerful, ergonomic vape mod with a 10,000-puff capacity', 250.00, 40, 10, 1, '2024-12-09 02:42:44', NULL),
(5, 'Black Pod Formula', 'A compact, high-quality pod', 400.00, 30, 10, 2, '2024-12-09 02:42:44', NULL),
(6, 'PowerMax 5000mAh Battery', 'High-capacity battery for long-lasting power for vapes', 180.00, 60, 15, 3, '2024-12-09 02:42:44', NULL),
(7, 'Velo Recharge Battery', 'Rechargeable vape battery with durable and reliable performance', 150.00, 70, 15, 1, '2024-12-09 02:42:44', NULL),
(8, 'Toha X3000 Device', 'A sleek and powerful vape mod', 350.00, 40, 10, 2, '2024-12-09 02:42:44', NULL),
(9, 'Nova 1500 Device', 'Compact and lightweight mod with 1500 puffs capacity', 200.00, 60, 10, 3, '2024-12-09 02:42:44', NULL),
(10, 'Nova 1500 Device', 'Compact and lightweight mod with 1500 puffs capacity', 200.00, 45, 10, 1, '2024-12-09 02:42:44', NULL),
(11, 'Coils for Vapes', 'Replacement coils for various vape devices', 30.00, 100, 20, 2, '2024-12-09 02:42:44', NULL),
(12, 'Vape Tank', 'Replacement tanks for vaping devices', 50.00, 80, 20, 3, '2024-12-09 02:42:44', NULL),
(13, 'Charging Cable for Vapes', 'USB charging cable compatible with most vape devices', 15.00, 200, 50, 1, '2024-12-09 02:42:44', NULL),
(14, 'Tropical Breeze E-Liquid', 'Refreshing tropical fruit blend e-liquid', 20.00, 150, 30, 2, '2024-12-09 02:42:44', NULL),
(15, 'Minty Fresh E-Liquid', 'Cool mint flavor e-liquid', 20.00, 200, 30, 3, '2024-12-09 02:42:44', NULL),
(16, 'Berry Blast E-Liquid', 'Berry-flavored e-liquid with a sweet taste', 20.00, 180, 30, 1, '2024-12-09 02:42:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_activity`
--

CREATE TABLE `product_activity` (
  `product_activityID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `activity_type` enum('Add','Update','Delete') NOT NULL,
  `logged_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_activity`
--

INSERT INTO `product_activity` (`product_activityID`, `user_id`, `product_id`, `category_id`, `activity_type`, `logged_on`) VALUES
(1, 1, 1, 1, 'Add', '2024-12-09 02:49:24'),
(2, 2, 2, 2, 'Add', '2024-12-09 02:49:24'),
(3, 68, 3, 1, 'Update', '2024-12-09 02:49:24'),
(4, 4, 4, 3, 'Add', '2024-12-09 02:49:24'),
(5, 68, 5, 4, 'Delete', '2024-12-09 02:49:24'),
(6, 4, 6, 5, 'Add', '2024-12-09 02:49:24'),
(7, 69, 7, 6, 'Update', '2024-12-09 02:49:24'),
(8, 68, 8, 1, 'Add', '2024-12-09 02:49:24'),
(9, 3, 9, 2, 'Update', '2024-12-09 02:49:24'),
(10, 69, 10, 3, 'Delete', '2024-12-09 02:49:24');

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `product_categoryID` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_category`
--

INSERT INTO `product_category` (`product_categoryID`, `category_id`, `product_id`, `name`, `description`) VALUES
(1, 1, 1, 'Mamba 15Kpuffs Pod', 'A long-lasting pod offering 15,000 puffs with smooth flavor and consistent vapor'),
(2, 1, 3, 'Black Elite V2 Pod', 'A sleek, premium pod system offering rich flavor and smooth vapor for a high-quality experience'),
(3, 1, 5, 'Black Pod Formula', 'A compact, high-quality pod delivering consistent flavor and smooth vapor for on-the-go vaping'),
(4, 2, 2, 'Mamba Elite 15K Battery', 'A powerful, durable battery designed to pair with the Mamba 15Kpuffs Pod for extended use'),
(5, 2, 6, 'PowerMax 5000mAh Battery', 'High-capacity battery for long-lasting power for vapes'),
(6, 2, 7, 'Velo Recharge Battery', 'Rechargeable vape battery with durable and reliable performance'),
(7, 3, 4, 'Toha S10000 Device', 'A powerful, ergonomic vape mod with a 10,000-puff capacity for both beginner and advanced vapers'),
(8, 3, 8, 'Toha X3000 Device', 'A sleek and powerful vape mod designed for serious vapers'),
(9, 3, 9, 'Nova 1500 Device', 'Compact and lightweight mod with 1500 puffs capacity for casual users'),
(10, 4, 4, 'Toha S10000 Device', 'A powerful, ergonomic vape mod with a 10,000-puff capacity for both beginner and advanced vapers'),
(11, 4, 5, 'Black Pod Formula', 'A compact, high-quality pod delivering consistent flavor and smooth vapor for on-the-go vaping'),
(12, 4, 10, 'Nova 1500 Device', 'Compact and lightweight mod with 1500 puffs capacity for casual users'),
(13, 5, 11, 'Coils for Vapes', 'Replacement coils for various vape devices'),
(14, 5, 12, 'Vape Tank', 'Replacement tanks for vaping devices'),
(15, 5, 13, 'Charging Cable for Vapes', 'USB charging cable compatible with most vape devices'),
(16, 6, 14, 'Tropical Breeze E-Liquid', 'Refreshing tropical fruit blend e-liquid'),
(17, 6, 15, 'Minty Fresh E-Liquid', 'Cool mint flavor e-liquid'),
(18, 6, 16, 'Berry Blast E-Liquid', 'Berry-flavored e-liquid with a sweet taste');

-- --------------------------------------------------------

--
-- Table structure for table `product_items`
--

CREATE TABLE `product_items` (
  `product_item_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_items`
--

INSERT INTO `product_items` (`product_item_id`, `product_id`, `size`, `color`, `price`, `stock_quantity`, `created_on`, `updated_on`) VALUES
(1, 1, NULL, 'Gold', 400.00, 50, '2024-12-09 02:44:31', '2024-12-09 02:44:31'),
(2, 2, NULL, 'White', 250.00, 50, '2024-12-09 02:44:31', '2024-12-09 02:44:31'),
(3, 3, 'Large', 'Black', 400.00, 45, '2024-12-09 02:44:31', '2024-12-09 02:44:31'),
(4, 4, 'Standard', 'Silver', 250.00, 40, '2024-12-09 02:44:31', '2024-12-09 02:44:31'),
(5, 5, 'Small', 'Black', 400.00, 30, '2024-12-09 02:44:31', '2024-12-09 02:44:31'),
(6, 1, 'Mini', 'Red', 11.00, 10, '2024-12-09 02:44:31', '2024-12-09 02:44:31'),
(7, 2, 'Large', 'Black', 250.00, 20, '2024-12-09 02:44:31', '2024-12-09 02:44:31'),
(8, 3, 'Small', 'Gold', 400.00, 35, '2024-12-09 02:44:31', '2024-12-09 02:44:31'),
(9, 4, 'Medium', 'Silver', 300.00, 40, '2024-12-09 02:44:31', '2024-12-09 02:44:31'),
(10, 5, 'Small', 'White', 350.00, 25, '2024-12-09 02:44:31', '2024-12-09 02:44:31');

-- --------------------------------------------------------

--
-- Table structure for table `product_supplier`
--

CREATE TABLE `product_supplier` (
  `product_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_supplier`
--

INSERT INTO `product_supplier` (`product_id`, `supplier_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 1),
(5, 2),
(6, 3),
(7, 1),
(8, 2),
(9, 3),
(10, 1),
(11, 2),
(12, 3),
(13, 1),
(14, 2),
(15, 3),
(16, 1);

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `report_id` int(11) NOT NULL,
  `generated_by_userID` int(11) NOT NULL,
  `report_type` enum('sales','inventory','activity') NOT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_activity`
--

CREATE TABLE `stock_activity` (
  `stock_activityID` int(11) NOT NULL,
  `product_activityID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `change_type` enum('Increase','Decrease') NOT NULL,
  `change_quantity` int(11) NOT NULL,
  `logged_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_activity`
--

INSERT INTO `stock_activity` (`stock_activityID`, `product_activityID`, `user_id`, `product_id`, `change_type`, `change_quantity`, `logged_on`) VALUES
(1, 1, 1, 1, 'Increase', 50, '2024-12-09 02:51:52'),
(2, 2, 2, 2, 'Increase', 50, '2024-12-09 02:51:52'),
(3, 3, 68, 3, 'Increase', 5, '2024-12-09 02:51:52'),
(4, 4, 4, 4, 'Increase', 40, '2024-12-09 02:51:52'),
(5, 5, 68, 5, 'Decrease', 30, '2024-12-09 02:51:52'),
(6, 6, 69, 6, 'Increase', 60, '2024-12-09 02:51:52'),
(7, 7, 4, 7, 'Increase', 10, '2024-12-09 02:51:52'),
(8, 8, 68, 8, 'Increase', 40, '2024-12-09 02:51:52'),
(9, 9, 68, 9, 'Increase', 15, '2024-12-09 02:51:52'),
(10, 10, 69, 10, 'Decrease', 45, '2024-12-09 02:51:52');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `product_categoryID` int(11) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_on` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `product_categoryID`, `contact_number`, `email`, `address`, `created_on`, `updated_on`) VALUES
(1, 'PNHAM SANTOS', 1, '9207882734', 'p.santos@gmail.com', NULL, '2024-12-09 01:26:47', NULL),
(2, 'JONEL MALLARI', 2, '9071608492', 'jmallari@gmail.com', NULL, '2024-11-24 22:18:23', NULL),
(3, 'MYLEEN MAYUGA', 3, '9916848030', NULL, 'Angeles University Foundation', '2024-11-24 22:18:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('Admin','Inventory_Manager','Procurement_Manager') NOT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `username`, `password_hash`, `role`, `created_on`, `updated_on`) VALUES
(1, 'Maris', 'Racal', 'admin123', '$2y$10$GSWWVi.qjjVEFtSGkM6DCO5ZAtlTw2pfen2v.17lawImXbPFzlvEe', 'Admin', '2024-11-24 22:18:14', '2024-12-05 17:24:04'),
(2, 'Samantha', 'Ticsay', 'STicsay', '$2y$10$CR7l3wdB0yw1VxJ/ftbnoOaXPnRXUZ1H5C285AMiAwHv.ivW9JKWG', 'Admin', '2024-11-24 22:18:14', '2024-12-05 15:54:26'),
(3, 'Janeil', 'Gonzales', 'JGonzales', '$2y$10$Jv.1o3VvWHRd3IxDldNG7eYsC1ardq3k10bujNOdq4svHg7qoT7zu', 'Inventory_Manager', '2024-11-24 22:18:14', '2024-12-05 15:58:49'),
(4, 'Patricia', 'Santos', 'PSantos', '$2y$10$1xMmSNBWVlYIAuMXqfOGpOMoDjgVrgKMESR3pMEZ59LErro118O5y', 'Procurement_Manager', '2024-11-28 20:22:19', '2024-12-06 17:00:28'),
(68, 'Mumei', 'Nanashi', 'Mooming', '$2y$10$7XIN/00qJft1gVbxPP4aXerlmoCUBUWW2K.W9Mq0GABIFMGEhw/B6', 'Admin', '2024-12-04 14:29:11', NULL),
(69, 'Fauna', 'Ceres', 'FaunaMart', '$2y$10$H/n/ElwnAFoosdO6bnX72uciHals/XMjHi//2TY7CzSNQ9cv8qabK', 'Admin', '2024-12-04 14:44:38', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `login_activity`
--
ALTER TABLE `login_activity`
  ADD PRIMARY KEY (`login_activityID`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`order_itemID`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `product_activity`
--
ALTER TABLE `product_activity`
  ADD PRIMARY KEY (`product_activityID`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`product_categoryID`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_items`
--
ALTER TABLE `product_items`
  ADD PRIMARY KEY (`product_item_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_supplier`
--
ALTER TABLE `product_supplier`
  ADD PRIMARY KEY (`product_id`,`supplier_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `generated_by_userID` (`generated_by_userID`);

--
-- Indexes for table `stock_activity`
--
ALTER TABLE `stock_activity`
  ADD PRIMARY KEY (`stock_activityID`),
  ADD KEY `product_activityID` (`product_activityID`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `login_activity`
--
ALTER TABLE `login_activity`
  MODIFY `login_activityID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `order_itemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product_activity`
--
ALTER TABLE `product_activity`
  MODIFY `product_activityID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `product_category`
--
ALTER TABLE `product_category`
  MODIFY `product_categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `product_items`
--
ALTER TABLE `product_items`
  MODIFY `product_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_activity`
--
ALTER TABLE `stock_activity`
  MODIFY `stock_activityID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login_activity`
--
ALTER TABLE `login_activity`
  ADD CONSTRAINT `login_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);

--
-- Constraints for table `product_activity`
--
ALTER TABLE `product_activity`
  ADD CONSTRAINT `product_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `product_activity_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `product_activity_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `product_category_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_category_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_items`
--
ALTER TABLE `product_items`
  ADD CONSTRAINT `product_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `product_supplier`
--
ALTER TABLE `product_supplier`
  ADD CONSTRAINT `product_supplier_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `product_supplier_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`generated_by_userID`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `stock_activity`
--
ALTER TABLE `stock_activity`
  ADD CONSTRAINT `stock_activity_ibfk_1` FOREIGN KEY (`product_activityID`) REFERENCES `product_activity` (`product_activityID`),
  ADD CONSTRAINT `stock_activity_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `stock_activity_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
