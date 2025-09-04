-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2025 at 10:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `loomify`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `phone_number`, `email`) VALUES
(1, 'sasi', 'sasi', '89523', ''),
(2, 'sai', 'sai', '7013085590', 'abc@gmail.com\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`) VALUES
(10, 1, 9, 1),
(12, 3, 3, 4),
(27, 5, 3, 1),
(28, 5, 7, 1),
(49, 4, 3, 1),
(52, 4, 1, 1),
(53, 50, 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `Image`, `description`) VALUES
(3, 'Home Decor', '67347238d1224.jpeg', ' Discover unique, handcrafted artwork and crafts created with passion and skill. From vibrant paintings and intricate sculptures to charming pottery and delicate needlework, find one-of-a-kind pieces to add personality to your home or gift to someone spec'),
(4, 'Art & Craft', '6734728b2dcba.jpeg', ' Discover unique, handcrafted artwork and crafts created with passion and skill. '),
(5, 'Jewelry', '673472a3842b9.jpeg', ' Discover unique, handcrafted artwork and crafts created with passion and skill. ');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` varchar(550) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`name`, `email`, `message`) VALUES
('sashank', 'sashank8885@gmail.com', 'I want to add my products to your website'),
('sashank', 'sashank8885@gmail.com', 'asdfgcvh');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','completed','canceled') DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `product_id`, `order_date`, `status`, `total_amount`) VALUES
(5, 4, 2, '2025-04-17 13:48:59', 'completed', 540.00),
(6, 4, 1, '2025-04-17 13:48:59', 'completed', 150.00),
(7, 5, 13, '2025-04-17 18:34:16', 'pending', 180.00),
(8, 5, 2, '2025-04-17 18:34:16', 'pending', 180.00),
(9, 7, 3, '2025-04-20 10:25:26', 'pending', 50.00),
(10, 7, 7, '2025-04-20 10:27:07', 'pending', 150.00),
(11, 4, 1, '2025-04-21 05:51:42', 'pending', 150.00),
(12, 4, 2, '2025-04-21 05:51:42', 'pending', 180.00),
(13, 4, 7, '2025-04-21 05:51:42', 'pending', 150.00),
(14, 14, 3, '2025-04-21 16:18:12', 'pending', 100.00),
(15, 16, 7, '2025-04-21 17:13:12', 'completed', 150.00),
(16, 16, 8, '2025-04-21 17:13:12', 'pending', 180.00),
(17, 17, 18, '2025-04-21 17:42:11', 'completed', 198.00),
(18, 17, 12, '2025-04-21 17:42:11', 'pending', 80.00),
(20, 21, 12, '2025-04-21 18:44:32', 'completed', 160.00),
(21, 4, 7, '2025-04-22 14:13:43', 'canceled', 150.00),
(22, 4, 13, '2025-04-22 14:13:43', 'completed', 180.00),
(23, 45, 20, '2025-05-01 07:50:48', 'completed', 500.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) NOT NULL,
  `payment_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `payment_date`, `amount`, `payment_type`) VALUES
(5, 5, '2025-04-17 13:48:59', 540.00, 'Online Payment'),
(6, 6, '2025-04-17 13:48:59', 150.00, 'Online Payment'),
(7, 7, '2025-04-17 18:34:16', 180.00, 'Cash on Delivery'),
(8, 8, '2025-04-17 18:34:16', 180.00, 'Cash on Delivery'),
(9, 9, '2025-04-20 10:25:26', 50.00, 'Cash on Delivery'),
(10, 10, '2025-04-20 10:27:07', 150.00, 'Cash on Delivery'),
(11, 11, '2025-04-21 05:51:42', 150.00, 'Cash on Delivery'),
(12, 12, '2025-04-21 05:51:42', 180.00, 'Cash on Delivery'),
(13, 13, '2025-04-21 05:51:42', 150.00, 'Cash on Delivery'),
(14, 14, '2025-04-21 16:18:12', 100.00, 'Cash on Delivery'),
(15, 15, '2025-04-21 17:13:12', 150.00, 'Cash on Delivery'),
(16, 16, '2025-04-21 17:13:12', 180.00, 'Cash on Delivery'),
(17, 17, '2025-04-21 17:42:11', 198.00, 'Cash on Delivery'),
(18, 18, '2025-04-21 17:42:11', 80.00, 'Cash on Delivery'),
(20, 20, '2025-04-21 18:44:32', 160.00, 'Cash on Delivery'),
(21, 21, '2025-04-22 14:13:43', 150.00, 'Cash on Delivery'),
(22, 22, '2025-04-22 14:13:43', 180.00, 'Cash on Delivery'),
(23, 23, '2025-05-01 07:50:48', 500.00, 'Cash on Delivery');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `seller_id`, `name`, `description`, `price`, `stock`, `Image`, `category_id`) VALUES
(1, 44, 'Paper Flower Frame', 'Modern 3D paper art featuring elegant fan-folded orange flowers against a black frame. This contemporary piece includes 6 handcrafted paper flowers with curved stems, creating a stunning dimensional effect. Perfect for modern home decor. Frame size: 12\" x 18\".', 150.00, 11, 'uploads/products/6734762dcde03_1731491373.jpeg', 3),
(2, NULL, 'Navrang Matka', 'Yellow matka with blue rim and temple motif medallion.', 180.00, 5, 'uploads/products/673476ce2baea_1731491534.jpg', 4),
(3, NULL, 'Pink Chain Necklace', 'Delicate gold chain necklace with royal blue thread wrap and pink pom poms. Perfect for casual wear. Length: 18 inches.', 50.00, 5, 'uploads/products/673476f7d883b_1731491575.jpg', 5),
(7, NULL, 'Welcome Sign Wall Decor', 'cheerful three-tiered welcome sign reading \"Welcome to Our Happy Place\". Features hand-painted designs including birds, flowers, and bees with colorful tassels. Made with high-quality wood and weather-resistant paint. Perfect for entryways, porches, or living rooms.', 150.00, 7, 'uploads/products/6734789eb41ce_1731491998.jpeg', 3),
(8, NULL, 'Mandala Wall Hanging', 'Handcrafted geometric mandala wall hanging featuring intricate traditional designs in vibrant colors of blue, green, yellow, and pink. Adorned with colorful tassels in blue, pink, and yellow. Perfect for adding a bohemian touch to any room.', 180.00, 19, 'uploads/products/673478cf99d86_1731492047.jpeg', 3),
(9, NULL, 'Toran', 'Elegant rope art featuring handwoven leaf designs in natural earth tones and green. This unique piece combines traditional rope weaving techniques with modern design. Mounted in a light wooden frame, perfect for adding natural texture to any room. Size: 16\" x 24\".', 200.00, 14, 'uploads/products/673478ed77b56_1731492077.jpeg', 3),
(10, NULL, ' Necklace Set', 'Bohemian style necklace with colorful layered tassels in navy, red, and mustard. Includes matching earrings. Adorned with silver beads. Adjustable length.', 150.00, 10, 'uploads/products/67347920d6aef_1731492128.jpg', 5),
(11, NULL, 'Multi-Gemstone Bracelet', 'Adjustable macrame bracelet featuring mixed pastel gemstones and silver accents. Includes rose quartz, amazonite, and garnet beads.', 50.00, 4, 'uploads/products/6734794b9e9fb_1731492171.jpeg', 5),
(12, NULL, 'Earrings', 'Handcrafted ceramic tile design earrings with blue and turquoise patterns. Features golden-banded navy silk tassels. Length: 3 inches.', 80.00, 8, 'uploads/products/673479af63559_1731492271.jpg', 5),
(13, NULL, 'Kalash', 'Yellow hand-painted matka with meenakari style floral design.', 180.00, 0, 'uploads/products/673479e20a9f6_1731492322.jpeg', 4),
(14, NULL, 'Sanskritik Kalash', 'Cream-colored kalash set with black tribal art, ideal for traditional decor', 500.00, 21, 'uploads/products/67347a0f3f121_1731492367.jpg', 4),
(15, NULL, 'Neelam Gharha', 'Deep blue gharha with white paisley patterns.', 280.00, 8, 'uploads/products/67347a5659fcb_1731492438.jpg', 4),
(16, NULL, 'Ear Rings', 'This is unqiue product from my store', 200.00, 10, 'uploads/products/6804db7c8e82c_1745148796.jpg', 5),
(17, NULL, 'Mother WallFrame', 'In this frame it define the love between the mother and child', 250.00, 15, 'uploads/products/6804dc563bc4e_1745149014.jpg', 3),
(18, NULL, 'Flower Design', 'This is flower Design for the wall', 99.00, 10, 'uploads/products/6804dd9e0a102_1745149342.jpg', 3),
(20, 44, 'Wall Poster', 'Unique product', 500.00, 19, 'uploads/products/68126f580b7ed_1746038616.jpg', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('buyer','seller','admin') DEFAULT 'buyer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `Address`, `phone_number`, `created_at`, `role`) VALUES
(4, 'user1', 'abc@gmail.com', '$2y$10$lQ17MI2QjyHJLcUy4H4i1Oik5MKNip9fc0RpTddN6Rib5BUH05qk2', '', '888577179', '2025-04-17 11:43:20', 'buyer'),
(5, 'user2', 'abc2@gmail.com', '$2y$10$nQjbesIwnsVi/CV5h0h94.arq213rC1pYRJCG/4/y9nYB2o687Tlq', '12', '88857799', '2025-04-17 18:30:42', 'buyer'),
(7, 'sai', 'sai@gmail.com', '$2y$10$/y7ZhJeYvkb8oBYQcv3x6O9.i2xKJbzU5sgcWaiMZCxYTnUh1.3PW', 'lpu', '7894561231', '2025-04-20 10:19:58', 'buyer'),
(14, 'sashan', 'sash@gmail.com', '$2y$10$/.FQTnG.EQWsGmiB/Y87jewbbtM8b3r11XNgzYJJJaEOAPwZ0ELgO', 'lpu', '1234567891', '2025-04-21 16:15:27', 'buyer'),
(16, 'sa', 'sashank@jknjkgmail.com', '$2y$10$ecpCf3WCfJYpQai/zODbS.4m0f4/xnc8n/R6JP.M1Rk/7aAVgGNxW', 'lpu', '1234567895', '2025-04-21 17:09:53', 'buyer'),
(17, 'sa', 'shank@gmail.com', '$2y$10$iIBt.GRrQyxFkJzCqW4SOOXLIH3u5nRn29f4UdVog4kFzVNOAJ82u', 'lpu', '2345678910', '2025-04-21 17:39:00', 'buyer'),
(21, 'sasnk', 'sasha@gmail.com', '$2y$10$UHtw./XSN.O/QKahsY/N6./vapPsVk4.ssC5F67mCfEDzSDIUWMs6', 'lpu', '1235489672', '2025-04-21 18:41:38', 'buyer'),
(43, 'buyer1', 'buyer1@gmail.com', '$2y$10$pVr1VLHmVimAg.HflbFQtu3COKBlzr/8.cPDeII8i3FIKTJc5PLmG', 'lpu', '07780352658', '2025-04-30 17:57:45', 'buyer'),
(44, 'seller', 'seller@gmail.com', '$2y$10$cpdIc1VzYSW6ft9IXBThGuilUDVfrz8S/yXVmOwqvlaYk6SNBo4/y', 'lpu', '7895641231', '2025-04-30 18:05:12', 'seller'),
(45, 'sashank', 'sashank@gmail.com', '$2y$10$UoF81QubW1qZq./RONc7MuFDrx79RX8Z3xupXqoiYjD9Y20tZibHW', 'lpu', '8885771799', '2025-05-01 07:49:43', 'buyer'),
(47, 'sashank', 'sashak@gmail.com', '$2y$10$lTFc1pzzL6GOIPdyl8buqu2sgIklEYrzdo5rXlawEiD/oB2CpS4c2', 'lpu', '8885771795', '2025-06-06 11:42:51', 'buyer'),
(50, 'sashank5', 'sashak5@gmail.com', '$2y$10$8ZcJIifNahnFx7lsnX0rquHkqqCDFjKpmugPPW4dkT50QbmdDt4QG', 'lpu', '8885771899', '2025-09-04 08:38:07', 'buyer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `Foreign Key` (`product_id`),
  ADD KEY `FK` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `foreign key` (`category_id`) USING BTREE,
  ADD KEY `fk_seller` (`seller_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_number` (`phone_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `Foreign Key` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_seller` FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
