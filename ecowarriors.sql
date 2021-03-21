-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 16, 2020 at 04:20 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecowarriors`
--
CREATE DATABASE IF NOT EXISTS `ecowarriors` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ecowarriors`;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `address_Id` int(11) NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipcode` int(11) NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`address_Id`, `street`, `number`, `city`, `zipcode`, `country`) VALUES
(1, 'In Anleiten', '2', 'Bogenneusiedl', 2125, 'Austria'),
(2, 'Kremser Straße', '63', 'Absdorf', 3462, 'Austria'),
(3, 'Rappolz', '6', 'Waldkirchen', 3844, 'Austria'),
(7, 'Einsiedlergasse 21/7', '123', 'Wien', 1050, 'Austria'),
(8, 'test', '123', 'vienna', 1030, 'Austria'),
(9, 'test', '12', 'vienna', 1030, 'Austria'),
(10, 'test', '111', 'vienna', 1030, 'Austria'),
(11, 'test', '111', 'vienna', 1030, 'Austria'),
(12, 'test', '111', 'vienna', 1030, 'Austria');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_Id` int(11) NOT NULL,
  `fk_user_Id` int(11) NOT NULL,
  `fk_product_Id` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_Id`, `fk_user_Id`, `fk_product_Id`, `qty`) VALUES
(16, 2, 33, 2),
(17, 2, 9, 1),
(18, 1, 6, 1),
(19, 2, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orders_Id` int(11) NOT NULL,
  `orderDate` date NOT NULL,
  `deliveryDate` date DEFAULT NULL,
  `users_Id` int(11) DEFAULT NULL,
  `product_Id` int(11) DEFAULT NULL,
  `address_Id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orders_Id`, `orderDate`, `deliveryDate`, `users_Id`, `product_Id`, `address_Id`) VALUES
(3, '2020-12-15', '2020-01-01', 3, 26, 7),
(4, '2020-12-15', '2020-01-01', 3, 6, 7),
(5, '2020-12-16', '2021-01-01', 2, 6, 8),
(6, '2020-12-16', '2021-01-01', 2, 5, 9),
(7, '2020-12-16', '2021-01-01', 2, 8, 10),
(8, '2020-12-16', '2021-01-01', 2, 6, 11),
(9, '2020-12-16', '2021-01-01', 2, 9, 12);

-- --------------------------------------------------------

--
-- Table structure for table `producer`
--

CREATE TABLE `producer` (
  `producer_Id` int(11) NOT NULL,
  `producerName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_Id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `producer`
--

INSERT INTO `producer` (`producer_Id`, `producerName`, `website`, `address_Id`) VALUES
(1, 'Bio Ostbau Filipp', 'https://bioobstbau-filipp.at/', 1),
(2, 'Grand Farm', 'https://grandfarm.at/grand-garten-home/?lang=en', 2),
(3, 'Waldviertler Biohof', 'https://www.waldviertlerbiohof.at', 3);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_Id` int(11) NOT NULL,
  `productName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `img` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `producer_Id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_Id`, `productName`, `category`, `price`, `img`, `description`, `producer_Id`) VALUES
(5, 'Apfel (fresco)', 'fruit', 1, 'asset/img/Apfel-Fresco.png', 'a fresco apple', 1),
(6, 'Baguette', 'bread', 2, 'asset/img/baguette1.png', 'A baguette', 3),
(7, 'Pear', 'fruit', 1, 'asset/img/birne.jpg', 'A pear', 2),
(8, 'Blue Cheese', 'cheese', 200, 'asset/img/blauschimmelkäse.png', 'A stinky cheese', 1),
(9, 'Brie', 'cheese', 3, 'asset/img/brie.png', 'Another stinky cheese', 3),
(10, 'Broccoli', 'vegetable', 2, 'asset/img/brokkoli.jpg', 'Very healthy!', 2),
(11, 'Ciabatta', 'bread', 3, 'asset/img/ciabatta.png', 'Ciabatta made with organic flour milled from the farm', 1),
(12, 'Iceberg lettuce', 'vegetable', 2, 'asset/img/eisbergsalat.jpg', 'A head of salad, great for burgers', 3),
(13, 'Emmentaler cheese', 'cheese', 3, 'asset/img/emmentaler.jpg', 'Not so stinky cheese and great to eat', 2),
(14, 'Strawberries', 'fruit', 3, 'asset/img/erdbeeren1.png', 'Yummy red berries', 1),
(15, 'Cucumber', 'vegetable', 1, 'asset/img/gurke.jpg', 'Watery gurken', 3),
(16, 'Blueberries', 'fruit', 3, 'asset/img/heidelbeeren.jpg', 'Blueberries straight from the field', 2),
(17, 'Apple (Jonagold)', 'fruit', 1, 'asset/img/jonagold.jpg', 'Yummy apples that children love to eat', 1),
(18, 'Horseradish', 'vegetable', 3, 'asset/img/kren.jpg', 'Freshly grated horseradish can be quite spicy, but delicious', 3),
(19, 'Landbrot', 'bread', 2, 'asset/img/landbrot.png', 'Great bread for breakfast or Jause', 2),
(20, 'Mehrkornbrot', 'bread', 2, 'asset/img/mehrkornbrot.jpg', 'Healthy bread with different grains', 1),
(21, 'Eggplant', 'vegetable', 2, 'asset/img/melanzani.jpg', 'Weird looking vegetables', 3),
(22, 'Mozzarella', 'cheese', 2, 'asset/img/mozarella.jpg', 'Fresh mozzarella made at the farm', 2),
(23, 'Fruitbasket 1', 'basket', 15, 'asset/img/obstkorb.jpg', 'Lots of different vegetables', 1),
(24, 'Fruitbasket 1', 'basket', 15, 'asset/img/obstkorb1.jpg', 'Lots of different vegetables', 3),
(25, 'Bell pepper', 'vegetable', 1, 'asset/img/paprikarot.jpg', 'Lots of vitamin C and great alone or in salads', 2),
(26, 'Peach', 'Fruits', 1, 'asset/img/pfirsich.jpg', 'Fuzzy fruit.', 1),
(27, 'Radishes', 'Vegetables', 1, 'asset/img/radischien.jpg', 'Little vegetable balls.', 3),
(28, 'Romansco', 'Vegetables', 2, 'asset/img/romansco.png', 'FRACTAL VEGETABLES!!!', 2),
(29, 'Vampire Apple', 'Fruit', 2, 'asset/img/VampiraMalus.jpg', 'Blood red apple.', 1),
(30, 'Tomatoes', 'Vegetables', 2, 'asset/img/tomaten.jpg', 'Yummy red balls.', 2),
(31, 'Vegetable basket 1', 'Basket', 15, 'asset/img/vegetablebox.jpg', '', 3),
(32, 'Vegetable basket 1', 'Basket', 15, 'asset/img/vegetablebox1.jpg', 'Many different vegetables.', 1),
(33, 'Whole grain bread', 'bread', 2, 'asset/img/vollkornbrot.jpg', 'Healthy bread', 2),
(34, 'Grapes (white)', 'fruit', 2, 'asset/img/weintrauben.jpg', 'Used to make wine', 3),
(35, 'Plums', 'fruit', 3, 'asset/img/zwetschge.jpg', 'Delicious plums that are ready to eat', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `first_name`, `last_name`) VALUES
(1, 'user@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$i6JJ1KR.EiCMtlc38uc2uO74wzdJa.O9QY8jvOqq0oC2FtpcNBFiq', 'ghiath', 'serri'),
(2, 'k@mail.at', '[\"ROLE_USER\"]', '$2y$13$sAKheQDzOFE13Es0bzBn7u8snfOgqIKZxt5Qwtn/P0PkAvVno.F06', 'Katharina', 'simma'),
(3, 'kim@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$x4lXQtpBTdhtY7Kj3t3mGuHyYrnRUAnpxwgYn0IGWYELJYeQD45kW', 'ghiath', 'serri');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_Id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_Id`),
  ADD KEY `fk_user_Id` (`fk_user_Id`),
  ADD KEY `fk_product_Id` (`fk_product_Id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orders_Id`),
  ADD KEY `address_Id` (`address_Id`),
  ADD KEY `users_Id` (`users_Id`),
  ADD KEY `product_Id` (`product_Id`);

--
-- Indexes for table `producer`
--
ALTER TABLE `producer`
  ADD PRIMARY KEY (`producer_Id`),
  ADD KEY `address_Id` (`address_Id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_Id`),
  ADD KEY `producer_Id` (`producer_Id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `address_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orders_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `producer`
--
ALTER TABLE `producer`
  MODIFY `producer_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`fk_user_Id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`fk_product_Id`) REFERENCES `product` (`product_Id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_E52FFDEE60338BD7` FOREIGN KEY (`address_Id`) REFERENCES `address` (`address_Id`),
  ADD CONSTRAINT `FK_E52FFDEED00042F8` FOREIGN KEY (`product_Id`) REFERENCES `product` (`product_Id`),
  ADD CONSTRAINT `FK_E52FFDEEF237909F` FOREIGN KEY (`users_Id`) REFERENCES `user` (`id`);

--
-- Constraints for table `producer`
--
ALTER TABLE `producer`
  ADD CONSTRAINT `FK_976449DC60338BD7` FOREIGN KEY (`address_Id`) REFERENCES `address` (`address_Id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD1C327C5C` FOREIGN KEY (`producer_Id`) REFERENCES `producer` (`producer_Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
