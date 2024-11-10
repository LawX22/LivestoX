-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2024 at 04:22 AM
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
(4, 830436447, 'fds', 'fds', 'Screenshot (411).png', '2024-10-30 11:12:38'),
(7, 830436447, 'bhhjBJH', 'BKK\r\n', '', '2024-11-04 09:32:33'),
(26, 830436447, 'cd', 'cc', '', '2024-11-06 02:24:32'),
(31, 830436447, 'cds', 'cds', '', '2024-11-06 02:29:44'),
(35, 830436447, 'asdad', 'xsa', '', '2024-11-06 02:32:31');

-- --------------------------------------------------------

--
-- Table structure for table `livestock_posts`
--

CREATE TABLE `livestock_posts` (
  `post_id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `livestock_type` enum('Cow','Sheep','Goat','Chicken','Pig') NOT NULL,
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
(35, 830436447, 'pig', 'pig', 'Pig', 'pug', 2.00, 100.00, 'healthy', 'gairan', 12.00, 5, 'Screenshot (14).png', '2024-10-30 06:27:50', '2024-10-30 06:27:50', 'Available'),
(41, 830436447, 'vdvs', 'mbm', 'Cow', 'bjb', 57.00, 68.00, 'bmnb', 'bkhb', 8.00, 1, 'Screenshot (384).png', '2024-10-30 14:08:40', '2024-11-01 10:01:40', 'Available'),
(43, 830436447, 'YESffw', 'hbk', 'Cow', 'bhb', 7.00, 6.00, 'hk', 'nk', 987.00, 1, 'Screenshot (62).png', '2024-11-01 08:26:38', '2024-11-04 01:05:50', 'Available'),
(51, 830436447, 'XSA', 'gj', '', 'hhj', 67.00, 68.00, 'g', 'hk', 879.00, 1, NULL, '2024-11-04 01:32:54', '2024-11-04 01:32:54', 'Available');

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
(363655065, 'buyer', 'Telen', 'Cose', 'Telen', '09523822980', 'Telen@gmail.com', '123', ''),
(830436447, 'farmer', 'Lawrenz ', 'Carisusa', 'LawX', '09551824610', 'lawrenzxavier22@gmail.com', '123', 'lawrenz.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `forum`
--
ALTER TABLE `forum`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `livestock_posts`
--
ALTER TABLE `livestock_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `fk_farmer` (`farmer_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `forum`
--
ALTER TABLE `forum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `livestock_posts`
--
ALTER TABLE `livestock_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=830436448;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `forum`
--
ALTER TABLE `forum`
  ADD CONSTRAINT `forum_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`);

--
-- Constraints for table `livestock_posts`
--
ALTER TABLE `livestock_posts`
  ADD CONSTRAINT `fk_farmer` FOREIGN KEY (`farmer_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
