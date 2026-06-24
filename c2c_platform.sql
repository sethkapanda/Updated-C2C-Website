-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 23, 2026 at 10:25 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `c2c_platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `delivery_method` varchar(50) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `buyer_id`, `order_date`, `total_amount`, `payment_method`, `payment_status`, `delivery_method`, `delivery_address`, `status`) VALUES
(1, 5, '2026-06-14 19:29:35', 500.00, 'EFT', 'paid', 'Pargo', '311 Vorster Avenue', 'processing'),
(2, 2, '2026-06-14 23:38:38', 2150.00, 'EFT', 'paid', 'The Courier Guy', '311 Vorster Avenue', 'processing'),
(3, 10, '2026-06-15 14:06:41', 180.00, 'EFT', 'paid', 'Pargo', '311 Vorsteer Avenue', 'processing'),
(4, 12, '2026-06-23 18:14:20', 250.00, 'EFT', 'paid', 'The Courier Guy', '311 Vorster Avenue', 'processing');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(2, 2, 3, 3, 350.00),
(3, 2, 5, 2, 550.00),
(4, 3, 11, 1, 180.00),
(5, 4, 1, 1, 250.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 1,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `seller_id`, `name`, `description`, `price`, `stock`, `category`, `image`, `location`, `status`, `created_at`) VALUES
(1, 5, 'Handmade Wooden Bowl', 'Beautiful hand-carved bowl made from recycled pine wood. Each piece is unique with natural grain patterns. Perfect for serving fruit, salad, or as a decorative centerpiece. Sustainably sourced and crafted by local artisans in Soweto. Food-safe finish.', 250.00, 5, 'Home', 'assets/uploads/products/1.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(2, 5, 'Traditional Beaded Necklace', 'Beautifully handcrafted beaded necklace inspired by traditional Zulu designs. Made with high-quality glass beads in vibrant colors (red, yellow, blue, and white). Each piece is unique and tells a story. Perfect for both everyday wear and special occasions.\r\n\r\n', 180.00, 10, 'Fashion', 'assets/uploads/products/2.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(3, 5, 'Wire Car Sculpture', 'Incredible wire art car sculpture handcrafted using recycled wire. Every detail is meticulously shaped to create a realistic 3D miniature car. A unique work of art, perfect for desk display, collectors, or as a memorable gift. Stands approximately 20cm long.', 350.00, 3, 'Collectibles', 'assets/uploads/products/3.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(4, 5, 'Woven Basket Set', 'Set of 3 traditional woven baskets in different sizes (small, medium, large). Handcrafted using sustainable palm leaves with natural dyes. Perfect for storage, home organization, or as gift baskets. Each basket is uniquely patterned and durable.', 400.00, 4, 'Home', 'assets/uploads/products/4.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(5, 5, 'African Print Dress', 'Stunning African print dress in bold Ankara patterns. Made from 100% cotton fabric – breathable and comfortable for all-day wear. Size M, featuring a flattering A-line cut with a high-low hem. Perfect for casual outings, parties, or cultural events.', 550.00, 2, 'Fashion', 'assets/uploads/products/5.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(6, 5, 'Wooden Mask', 'Stunning hand-painted wooden wall mask inspired by African tribal traditions. Intricately carved and decorated with natural pigments and beads. A statement piece for any wall – perfect for adding cultural character to your living space. Includes wall-hanging hardware.', 890.00, 1, 'Home', 'assets/uploads/products/6.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(7, 5, 'Clay Pot', 'Traditional clay cooking pot with lid, made using age-old methods passed down through generations. Perfect for slow-cooking stews, beans, or potjie. Retains heat evenly and adds authentic flavor to traditional South African dishes. Handcrafted with natural clay.', 320.00, 6, 'Home', 'assets/uploads/products/7.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(8, 5, 'Used Laptop - Dell Inspiron', 'Refurbished Dell Inspiron laptop in excellent condition. Intel Core i5 processor, 8GB RAM, 256GB SSD for fast boot times. 15.6-inch HD display, Windows 11 pre-installed, WiFi and Bluetooth. Great for students, remote work, or everyday use. Comes with charger.', 3500.00, 1, 'Technology', 'assets/uploads/products/8.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(9, 5, 'Smartphone Holder', 'Adjustable car smartphone holder with suction mount and 360-degree rotation. Fits all smartphones (4-7 inches). Secure grip even on bumpy roads. Dashboard or windshield mount included. Make hands-free calls and GPS navigation safe while driving.', 90.00, 15, 'Electronics', 'assets/uploads/products/9.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(10, 5, 'Wireless Mouse', 'Ergonomic wireless mouse with USB-A receiver. Silent click technology, adjustable DPI (800/1200/1600), and auto power-saving mode. Works with Windows, Mac, and Linux. Smooth scrolling and comfortable grip – ideal for home office or travel.', 250.00, 8, 'Technology', 'assets/uploads/products/10.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(11, 5, 'USB-C Charger', 'Fast-charging USB-C wall charger with 20W output. Compatible with smartphones, tablets, laptops, and other USB-C devices. Lightweight and compact, perfect for travel. Includes a 1.5m USB-C to USB-C cable. Over-current and over-temperature protection built-in.', 180.00, 12, 'Electronics', 'assets/uploads/products/11.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(12, 5, 'Bluetooth Speaker', 'Portable Bluetooth speaker with IPX5 waterproof rating – perfect for outdoor adventures. Crisp 360° sound with deep bass, 10-hour battery life, and built-in microphone for calls. Connects to any Bluetooth device. Compact and lightweight design.\r\n\r\n', 450.00, 5, 'Electronics', 'assets/uploads/products/12.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(13, 5, 'Mens Sneakers', 'Classic men\'s sneakers in excellent condition – near new. Size 42, perfect for casual wear or light sports. Breathable mesh upper, cushioned sole, and lace-up closure. Versatile white and gray color scheme that pairs well with any outfit.', 600.00, 2, 'Fashion', 'assets/uploads/products/13.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(14, 5, 'Handbag', 'Elegant women\'s handbag with a leather-like finish. Spacious interior with multiple pockets for organization. Trendy design with durable zipper closure and comfortable shoulder straps. Perfect for work, shopping, or a night out.', 350.00, 4, 'Fashion', 'assets/uploads/products/14.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(15, 5, 'Novel - \"Born a Crime\"', 'Born a Crime – the bestselling memoir by Trevor Noah, South Africa\'s beloved comedian. This copy is in good condition with minimal wear. A humorous yet poignant story about growing up in apartheid South Africa. Essential reading for any South African bookshelf.', 120.00, 1, 'Collectibles', 'assets/uploads/products/15.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(16, 5, 'Sunglasses', 'Polarized unisex sunglasses with classic aviator design. UV400 protection to shield your eyes from harmful rays. Lightweight metal frame with adjustable nose pads. Perfect for driving, outdoor activities, or adding style to any outfit.', 200.00, 7, 'Fashion', 'assets/uploads/products/16.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(17, 5, 'Power Bank', 'High-capacity 10000mAh power bank with dual USB-A output ports. Fast-charge your phone up to 3 times on a single charge. LED battery indicator, slim design, and built-in safety protection. Perfect for travel, camping, or daily emergencies.', 300.00, 6, 'Electronics', 'assets/uploads/products/17.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(18, 5, 'Desk Lamp', 'Modern LED adjustable desk lamp with 3 brightness levels and a flexible neck. Energy-efficient and eye-friendly, perfect for late-night studying, office work, or bedside reading. Sleek black finish with a sturdy base.', 220.00, 4, 'Home', 'assets/uploads/products/18.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(19, 5, 'Yoga Mat', 'Premium non-slip yoga mat with 6mm thickness for joint comfort. Made from eco-friendly TPE material, odor-free and durable. Perfect for yoga, Pilates, or floor exercises. Includes a carry strap for easy transportation.', 280.00, 3, 'Home', 'assets/uploads/products/19.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(20, 5, 'Kids Toy Car', 'Remote-controlled toy car with rechargeable battery. Reaches speeds up to 15km/h, fully functional with forward/reverse and turning controls. Bright red finish, suitable for indoor and outdoor fun. Great gift for kids 5+ years. Includes USB charging cable and remote control.', 450.00, 2, 'Collectibles', 'assets/uploads/products/20.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(21, 5, 'Coffee Mug Set', 'Set of 4 ceramic coffee mugs with matching designs. Microwave and dishwasher safe. Each mug holds 350ml of your favorite hot beverage. Beautifully crafted with a smooth, glossy finish. Makes a great gift or daily home essential.', 190.00, 10, 'Home', 'assets/uploads/products/21.webp', 'Johannesburg', 'active', '2026-06-14 23:08:48'),
(22, 13, 'Premier Munchen Finale Soccer Ball', 'The Premier Munchen Finale Thermo Bonded Match Ball is a high-performance Size 5 soccer ball designed for both competitive matches and advanced training sessions. It features a thermo-bonded seamless construction that helps reduce water absorption while improving aerodynamics and flight consistency.', 500.00, 20, 'Home', 'assets/uploads/products/1782239163_5472-premier-munchen-finale-soccer-ball-a.jpg', 'Durban', 'active', '2026-06-23 18:26:03');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `order_id`, `buyer_id`, `rating`, `comment`, `created_at`) VALUES
(1, 11, 3, 10, 5, 'Really love how quicky it charges my phone', '2026-06-15 14:07:30'),
(2, 1, 4, 12, 5, 'Really amazing! Love keeping my side dishes in this bowl', '2026-06-23 18:16:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `is_seller` tinyint(1) DEFAULT 0,
  `is_admin` tinyint(1) DEFAULT 0,
  `verification_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `id_document` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `fullname`, `phone`, `address`, `location`, `is_seller`, `is_admin`, `verification_status`, `id_document`, `created_at`) VALUES
(1, 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', NULL, NULL, NULL, 0, 1, 'approved', NULL, '2026-06-14 18:10:04'),
(2, 'sethgraphicstudio@gmail.com', '$2y$10$twW46r1gUUk7g2tjV0TQx.1ljIFY.HfwnMgRpuVNUg4Q6OxSCaZyq', 'Seth', '0659441816', NULL, 'Johannesburg', 0, 1, 'pending', NULL, '2026-06-14 18:48:53'),
(5, 'kapandaseth069@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Seth Kapanda', '0659441816', NULL, 'Johannesburg', 1, 0, 'approved', NULL, '2026-06-14 19:23:34'),
(10, 'bob@fisher.com', '$2y$10$c74xZ9P97aoTdJKfj4RGD.N7tyLW.9trf0f7BpMGFN6jQgEyNqnSC', 'Bob Fisher', '0733002692', NULL, 'Durban', 0, 1, 'pending', NULL, '2026-06-14 23:58:59'),
(12, 'sethkapanda.dev@gmail.com', '$2y$10$gejX2247vUAUSx1Cv.XQ2Ot94YNJuAG60LptW26LiUSV3ciRuz7sO', 'Seth', '0659441816', '', 'Johannesburg', 0, 0, 'pending', NULL, '2026-06-23 17:58:57'),
(13, 'thabo@gmail.com', '$2y$10$odQ7wJlPM5C2ZDdwUE0JQ.9xvhJ2xgtpJkn.6YLibLQGo6kWvJ99i', 'Thabo', '0711243465', NULL, 'Durban', 1, 0, 'pending', NULL, '2026-06-23 18:18:18'),
(14, 'joseph@gmail.com', '$2y$10$Ur4nJqtZ4vMK/OFK/WVsjuN8hz9iBDTGDc5Wi/FLOE5dMiOeMGVfe', 'Joseph', '082 239 2393', NULL, 'Capetown', 0, 1, 'approved', NULL, '2026-06-23 18:31:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `buyer_id` (`buyer_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
