-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2024 at 10:28 AM
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
-- Database: `livestox_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `chat_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `status` enum('request','accepted','blocked') NOT NULL,
  `gochat_id` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`chat_id`, `sender`, `receiver`, `status`, `gochat_id`, `created`) VALUES
(2, 363655065, 830436447, 'accepted', '699641549', '2024-11-22 13:52:02');

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE `favorite` (
  `fav_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorite`
--

INSERT INTO `favorite` (`fav_id`, `user_id`, `stock_id`) VALUES
(1, 363655065, 8),
(2, 363655065, 3),
(3, 969119547, 8),
(4, 969119547, 3),
(5, 969119547, 3);

-- --------------------------------------------------------

--
-- Table structure for table `forum`
--

CREATE TABLE `forum` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum`
--

INSERT INTO `forum` (`id`, `user_id`, `title`, `description`, `image`, `created_at`) VALUES
(1, 830436447, 'Looking for potential buyers for my pigs', 'My location is in Gairan, Bogo City, Cebu Willing to negotiate for the price message me or contact this number 09551824610', '', '2024-10-27 13:22:24'),
(51, 363655065, 'Looking for Cow', 'I need a male cow for breeding ', 'cow.jpg', '2024-11-14 12:46:37'),
(52, 363655065, 'help', 'looking pigs', '', '2024-11-14 13:08:19'),
(53, 161711261, 'cds', 'bh', '', '2024-11-22 23:25:52');

-- --------------------------------------------------------

--
-- Table structure for table `livestock_auctions`
--

CREATE TABLE `livestock_auctions` (
  `id` int(11) NOT NULL,
  `start_time` datetime(6) NOT NULL,
  `end_time` datetime(6) NOT NULL,
  `starting_price` decimal(11,2) NOT NULL,
  `current_highest_bid` decimal(11,2) NOT NULL,
  `highest_bidder_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `livestock_type` enum('Cow','Sheep','Goat','Chicken','Pig') NOT NULL,
  `breed` varchar(100) NOT NULL,
  `age` decimal(5,2) NOT NULL,
  `weight` decimal(10,2) NOT NULL,
  `health_status` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image_posts` varchar(255) NOT NULL,
  `date_posted` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `availability` enum('Available','Sold') NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `livestock_auctions`
--

INSERT INTO `livestock_auctions` (`id`, `start_time`, `end_time`, `starting_price`, `current_highest_bid`, `highest_bidder_id`, `status`, `farmer_id`, `title`, `description`, `livestock_type`, `breed`, `age`, `weight`, `health_status`, `location`, `price`, `quantity`, `image_posts`, `date_posted`, `updated_at`, `availability`) VALUES
(6, '2024-11-02 01:33:00.000000', '2024-11-26 01:33:00.000000', 7.00, 7.00, 0, 'active', 161711261, 'cds', 'bjh', '', 'hk', 0.00, 87.00, 'y', 'yu', 0.00, 1, '6740c08db273b_rooster.jpg', '2024-11-22 17:34:05', '2024-11-22 17:34:05', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `livestock_posts`
--

CREATE TABLE `livestock_posts` (
  `post_id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `livestock_type` enum('COW','SHEEP','GOAT','CHICKEN','PIG') NOT NULL,
  `breed` varchar(100) DEFAULT NULL,
  `age` decimal(5,2) DEFAULT NULL COMMENT 'Age in years, e.g., 1.5 years',
  `weight` decimal(10,2) DEFAULT NULL COMMENT 'Weight in kilograms',
  `health_status` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `image_posts` varchar(255) DEFAULT NULL,
  `date_posted` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `availability` enum('Available','Sold') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `livestock_posts`
--

INSERT INTO `livestock_posts` (`post_id`, `farmer_id`, `title`, `description`, `livestock_type`, `breed`, `age`, `weight`, `health_status`, `location`, `price`, `quantity`, `image_posts`, `date_posted`, `updated_at`, `availability`) VALUES
(1, 830436447, 'IMPORTED COW', 'Gekan gawas red angus ang breed', 'COW', 'Angus', 2.00, 130.00, 'Vaccinated', 'Gairan, Bogo City, Cebu', 200000.00, 4, 'group-cows-inside-dairy-barn-with-hay.jpg', '2024-11-13 04:04:28', '2024-11-22 05:19:44', 'Available'),
(2, 946645673, 'BISAYA MANOK', 'naa koy baligya gwapo kaayo manok para away', 'CHICKEN', 'Hen', 1.00, 20.00, 'Healthy', 'Gairan, Bogo City, Cebu', 15000.00, 1, 'rooster.jpg', '2024-11-13 04:06:37', '2024-11-22 05:09:16', 'Available'),
(3, 517341225, 'BALIGYA BABOY', 'pamalit namo mga fresh na baboy chat lang sa ganahan mo palit', 'PIG', 'PINK PIG', 1.00, 40.00, 'Healthy', 'Nailon, Bogo CIty, Cebu', 6500.00, 1, 'pig.jpg', '2024-11-13 04:08:24', '2024-11-14 03:24:36', 'Available'),
(8, 946645673, 'manok', 'manok', 'CHICKEN', 'talisayon', 2.00, 2.00, 'ds', 'gairan', 4000.00, 1, 'rooster.jpg', '2024-11-14 05:05:02', '2024-11-21 06:29:43', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `gochat_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `gochat_id`, `user_id`, `content`, `image_url`, `created`) VALUES
(1, '699641549', 363655065, 'Hello World', NULL, '2024-11-22 13:52:02'),
(2, '699641549', 363655065, 'hi', '', '2024-11-22 13:52:19');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `user_type`, `first_name`, `last_name`, `username`, `phone`, `email`, `password`, `profile_picture`) VALUES
(1, 'admin', 'Super', 'Admin', 'Admin', '', '', '123', ''),
(161711261, 'farmer', 'Dhaniel', 'Malinao', 'dhaniel', '8374923', 'dhaniel@gmail.com', '123', ''),
(363655065, 'buyer', 'Telen', 'Cose', 'Telen', '09523822980', 'Telen@gmail.com', '123', 'Livestock.jpg'),
(517341225, 'farmer', 'piya', 'alfafara', 'piya', '99', 'piya@gmail.com', '123', 'Cow-Gang.jpg'),
(830436447, 'farmer', 'Lawrenz ', 'Carisusa', 'LawX', '09551824610', 'lawrenzxavier22@gmail.com', '123', 'lawrenz.png'),
(946645673, 'farmer', 'Ruter', 'Gulane', 'ruter', '63249234', 'test2@gmail.com', '123', 'Cow-Gang.jpg'),
(969119547, 'buyer', 'Clyde', 'Gullem', 'cy gullem', '639123123123', 'cygullem@gmail.com', '12345678', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`chat_id`);

--
-- Indexes for table `favorite`
--
ALTER TABLE `favorite`
  ADD PRIMARY KEY (`fav_id`);

--
-- Indexes for table `forum`
--
ALTER TABLE `forum`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `livestock_auctions`
--
ALTER TABLE `livestock_auctions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_id` (`farmer_id`);

--
-- Indexes for table `livestock_posts`
--
ALTER TABLE `livestock_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `fk_farmer` (`farmer_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `favorite`
--
ALTER TABLE `favorite`
  MODIFY `fav_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `forum`
--
ALTER TABLE `forum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `livestock_auctions`
--
ALTER TABLE `livestock_auctions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `livestock_posts`
--
ALTER TABLE `livestock_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=969119548;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `forum`
--
ALTER TABLE `forum`
  ADD CONSTRAINT `forum_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`);

--
-- Constraints for table `livestock_auctions`
--
ALTER TABLE `livestock_auctions`
  ADD CONSTRAINT `livestock_auctions_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `livestock_posts`
--
ALTER TABLE `livestock_posts`
  ADD CONSTRAINT `fk_farmer` FOREIGN KEY (`farmer_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
