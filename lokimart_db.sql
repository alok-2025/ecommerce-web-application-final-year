-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2026 at 04:35 PM
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
-- Database: `lokimart_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_address` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Processing',
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_address`, `total_price`, `status`, `created_by`, `created_at`) VALUES
(11, 'mayank verma', 'user1@example.com', 'east park, lusaka', 601.50, 'Processing', 'mayank', '2025-07-30 19:14:42'),
(25, 'Bright Mulenga', 'user2@example.com', 'ZCAS, Lusaka', 3000.98, 'Cancelled', 'mayank', '2025-10-03 17:22:29'),
(34, 'akhil kumar', 'user3@example.com', 'nipa area', 9404.89, 'Cancelled', 'alok', '2025-10-07 23:23:50'),
(35, 'Sambhavi Kumar', 'user4@example.com', 'area 87 lusaka', 476.09, 'Completed', 'alok', '2025-10-07 23:48:18'),
(36, 'Sajjad Munshi', 'user5@example.com', 'melissa lusaka', 349.19, 'Shipping', 'alok', '2025-10-08 00:03:40'),
(37, 'chris brown', 'user6@example.com', 'unza university', 9256.39, 'Processing', 'myk', '2025-10-08 20:58:30'),
(38, 'avinash gupta', 'user7@example.com', 'leopard hills', 159.30, 'Processing', 'myk', '2025-10-08 21:33:09'),
(39, 'Alok Verma', 'user8@example.com', 'eastpark roma', 623.10, 'Shipping', 'alok', '2025-12-08 13:12:54');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`) VALUES
(11, 11, 2, 'Skin Lotion', 2, 300.75),
(25, 25, 3, 'Headset', 2, 1500.49),
(38, 34, 4, 'iPhone 12', 1, 9250.99),
(39, 34, 8, 'Battery', 1, 5.40),
(40, 34, 10, 'Lipgloss', 1, 148.50),
(41, 35, 2, 'Skin Lotion', 1, 300.75),
(42, 35, 9, 'Blush', 1, 175.34),
(43, 36, 11, 'Eye Shadow', 1, 200.69),
(44, 36, 10, 'Lipgloss', 1, 148.50),
(45, 37, 4, 'iPhone 12', 1, 9250.99),
(46, 37, 8, 'Battery', 1, 5.40),
(47, 38, 8, 'Battery', 2, 5.40),
(48, 38, 10, 'Lipgloss', 1, 148.50),
(49, 39, 2, 'Skin Lotion', 2, 300.75),
(50, 39, 8, 'Battery', 4, 5.40);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `mobile_money_operator` varchar(50) NOT NULL,
  `mobile_money_number` varchar(50) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('Pending','Successful','Failed') NOT NULL DEFAULT 'Pending',
  `payment_date` datetime DEFAULT current_timestamp(),
  `product_names` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `first_name`, `last_name`, `email`, `mobile_money_operator`, `mobile_money_number`, `amount`, `payment_status`, `payment_date`, `product_names`) VALUES
(16, 36, 'mykie', 'versace', 'user1@example.com', 'Zamtel', '09727', 349.19, 'Pending', '2025-10-08 00:04:37', 'Eye Shadow, Lipgloss'),
(17, 37, 'Michael ', 'Keane', 'user2@example.com', 'MTN', '09711', 9256.39, 'Failed', '2025-10-08 21:07:03', 'iPhone 12, Battery'),
(18, 38, 'Michael ', 'Sesko', 'user3@example.com', 'Airtel', '09799', 159.30, 'Successful', '2025-10-08 21:40:25', 'Battery, Lipgloss'),
(20, 39, 'Alok ', 'Verma', 'user4@example.com', 'Airtel', '269898', 623.10, 'Successful', '2025-12-08 13:18:07', 'Skin Lotion, Battery');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT 'images/default-product.png',
  `quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image_url`, `quantity`, `created_at`) VALUES
(1, 'white Laptop', 6950.40, 'Windows 11, Avast Antivirus, Microsoft Office Suite 365, silver-black color', 'uploads/laptop.jpg', 98, '2025-08-10 11:48:24'),
(2, 'Skin Lotion', 300.75, 'Applied to the body to hydrate, soften, and protect the skin', 'uploads/skin_lotion.jpg', 95, '2025-08-10 11:48:24'),
(3, 'Headset', 1500.49, 'Bluetooth enabled, 10m range, only speaker, black color', 'uploads/headphones.jpg', 98, '2025-08-10 11:48:24'),
(4, 'iPhone 12', 9250.99, 'iOS 14, dual camera, pre-installed apps, navy blue color', 'uploads/smartphone.jpg', 99, '2025-08-10 11:48:24'),
(8, 'Battery', 5.40, 'Rechargeable battery, set of three cells, black-yellow color', 'uploads/battery.jpg', 92, '2025-08-10 11:48:24'),
(9, 'Blush', 175.34, 'Adds a natural flush of color and enhance the complexion', 'uploads/blush.jpg', 99, '2025-08-10 11:48:24'),
(10, 'Lipgloss', 148.50, 'Adds shine and subtle color to the lips for a glossy, polished look', 'uploads/lipgloss.jpg', 95, '2025-08-06 11:48:24'),
(11, 'Eye Shadow', 200.69, 'Applied to the eyelids to enhance and define the eyes with color and texture', 'uploads/eye_shadow.jpg', 102, '2025-08-06 11:48:24'),
(13, 'Teddy Bear', 1440.78, 'Used for comfort, or as a gift for children and loved ones', 'uploads/teddy_bear.jpg', 99, '2025-08-10 11:48:24'),
(14, 'joystick', 349.54, 'PS4 controller, white colour', 'uploads/joystick.jpg', 100, '2025-12-08 13:24:28');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(8, 9, 13, 1, 'it does not add a natural flush of color and i did not get the parcel was already torn', '2025-09-27 16:16:27'),
(9, 4, 2, 5, 'the packing of the parcel is done in a good way and the phone looks exactly like it is in the picture shown', '2025-09-27 16:30:10'),
(10, 3, 2, 3, 'a good quality product', '2025-10-03 15:19:31'),
(11, 2, 1, 3, 'good product, gives smooth skin', '2025-12-08 11:11:12');

-- --------------------------------------------------------

--
-- Table structure for table `support`
--

CREATE TABLE `support` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support`
--

INSERT INTO `support` (`id`, `fullname`, `phone`, `address`, `email`, `message`, `created_by`, `created_at`) VALUES
(1, 'loki odinson', '+260 96 211447', '1578, Lusaka', 'user1@example.com', 'add cutlery products', 'mayank', '2025-07-29 17:02:36'),
(2, 'mayank verma', '+260 96 27988', 'hotel sarovar', 'user2@example.com', 'are you available on other social media apps apart from the ones seen here?', 'mayank', '2025-10-09 23:00:03'),
(4, 'alok verma', '+260 96 29714', 'roma park', 'user3@example.com', 'will you planning to add game consoles?', 'alok', '2025-11-21 20:35:45'),
(5, 'Alok Verma', '+2608989147', 'chaisa, lusaka', 'user4@example.com', 'are you planning to add electric machines?', 'alok', '2025-12-08 12:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Visitor','Administrator','Customer','Vendor') NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'default_profile.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `profile_pic`, `created_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$oZToHGjegRDomLCjx5BsvepZfJKThhkxCSyGCgfmA4nnGWhgFB3DW', 'Administrator', '1750242763_admin_img.png', '2025-06-12 17:23:43'),
(2, 'vendor', 'vendor@example.com', '$2y$10$qN8rFFMJ94NDq5zE93Htjexaj36.liOYaXulCuNYA0MHmnFr.tjBa', 'Vendor', '1753723082_profile_img2.jpg', '2025-06-12 17:29:32'),
(4, 'customer ', 'customer@example.com', '$2y$10$M7BBe9uDzHhKghVk5/4Eu.UF7UQJHneXZpa3qwYKJxALEiRcPOx0i', 'Customer', '1765192853_profile_img3.jpg', '2025-06-15 19:37:39'),
(13, 'visitor', 'visitor@example.com', '$2y$10$2NRw3PC1EjiPm7tMcDno/Oyxvx0IhyxDBoF5NYvLWvPK3yaKBPdVK', 'Visitor', '1753722693_profile_img5.png', '2025-07-28 17:11:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `fk_product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `support`
--
ALTER TABLE `support`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `support`
--
ALTER TABLE `support`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
