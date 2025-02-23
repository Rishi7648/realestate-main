-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2025 at 04:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `realestatelogin`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Rishi', 'kalu@gmail.com', '$2y$10$WCU.4CYV8n6UgCrWKEDre.EUVmqBqQpAueemRO/zCRHB0OVNBFHRi', '2024-12-28 15:18:16'),
(2, 'rishi', 'rishisilwal19@gmail.com', '$2y$10$n0TF4bpzCn0LMo0WpmESCuIg8cQSf/.pJe/t44qtpsKRAKXHpYzLG', '2025-02-17 16:11:32');

-- --------------------------------------------------------

--
-- Table structure for table `form`
--

CREATE TABLE `form` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(25) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `houseproperties`
--

CREATE TABLE `houseproperties` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `floors` varchar(255) DEFAULT NULL,
  `bedrooms` varchar(255) DEFAULT NULL,
  `living_rooms` varchar(255) DEFAULT NULL,
  `area` varchar(255) NOT NULL,
  `kitchens` varchar(255) DEFAULT NULL,
  `washrooms` varchar(255) DEFAULT NULL,
  `attached_washrooms` varchar(255) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `map_image` varchar(255) NOT NULL,
  `property_images` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(20) DEFAULT 'pending',
  `is_featured` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `houseproperties`
--

INSERT INTO `houseproperties` (`id`, `user_id`, `floors`, `bedrooms`, `living_rooms`, `area`, `kitchens`, `washrooms`, `attached_washrooms`, `location`, `price`, `map_image`, `property_images`, `created_at`, `updated_at`, `status`, `is_featured`) VALUES
(1, 23, '3', '3', '3', '2 aana', '3', '3', '3', 'Manipal, jayapur', 3222.00, 'uploads/bg.png.png', '[\"uploads\\/bg.png.png\"]', '2025-01-07 17:52:46', '2025-01-09 19:10:27', 'approved', 0),
(4, 1, '0', '0', 'hdj', '0', '0', '0', '0', 'd', 0.00, 'uploads/Screenshot 2024-10-25 191937.png', '[]', '2025-02-20 16:07:50', '2025-02-20 16:08:54', 'pending', 0);

-- --------------------------------------------------------

--
-- Table structure for table `land_properties`
--

CREATE TABLE `land_properties` (
  `id` int(11) NOT NULL,
  `area` text DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `map_image` varchar(255) NOT NULL,
  `property_images` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `approval_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_featured` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `land_properties`
--

INSERT INTO `land_properties` (`id`, `area`, `location`, `price`, `map_image`, `property_images`, `user_id`, `status`, `approval_date`, `created_at`, `is_featured`, `updated_at`) VALUES
(6, '4', 'nuwakot ', 25678.00, 'uploads/Screenshot 2024-10-25 191937.png', '[\"uploads\\/Screenshot 2024-11-09 084252.png\"]', 0, 'approved', NULL, '2024-12-30 16:36:20', 0, '2025-02-20 17:39:00'),
(9, '2', 'kathmandu', 12.00, 'uploads/Screenshot 2024-11-09 084252.png', '[\"uploads\\/Screenshot 2024-11-09 084240.png\"]', 22, 'rejected', NULL, '2024-12-30 16:36:20', 0, '2025-02-20 17:39:00'),
(10, '2', 'kwekk', 23.00, 'uploads/Screenshot 2024-10-25 191937.png', '[\"uploads\\/Screenshot 2024-10-25 191937.png\"]', 0, 'approved', NULL, '2024-12-30 16:36:20', 0, '2025-02-20 17:39:00'),
(21, '2 aaana', 'Bhaktapur', 45.00, 'uploads/Screenshot 2025-01-02 204108.png', '[\"uploads\\/Screenshot 2025-01-02 204138.png\"]', 23, 'approved', NULL, '2024-12-30 16:47:02', 0, '2025-02-20 17:39:00'),
(32, '2 aanad', 'kwekk', 32.00, 'uploads/Screenshot 2024-10-25 191937.png', '[\"uploads\\/bg.png.png\"]', 1, 'pending', NULL, '2025-02-20 17:21:33', 0, '2025-02-20 17:39:41');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `user_id` int(11) NOT NULL,
  `Name` int(11) NOT NULL,
  `Location` int(11) NOT NULL,
  `Price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  `password` text NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `profile_picture`) VALUES
(1, 'Rishi', 'silwal', 'rishisilwal19@gmail.com', '9823167724', '$2y$10$xEAi5gs20KQEzzfBphdYluzQC4tNftEc5iQUapCoW0USH.RgxyKda', NULL),
(2, 'Albert', 'Belbase', 'belbase@gmail.com', '9823167724', '0909albert', NULL),
(3, 'Ashim', 'Panta', 'panta@gmail.com', '9823167724', '$2y$10$b/X5BU2aU8JBP73YNghGiO0JZaOi9NcqNazdZAg8Vp5vXtAYoauNC', NULL),
(6, 'Aayush', 'Bhatta', 'bhatta@gmail.com', '9847387285', '$2y$10$Ezjas8dgxa4g7kWXlQ/Of.FDJ/LbSnc31vjpx5amm39cM2Ny1qFI.', NULL),
(7, 'Saugat', 'Thapa', 'thapa@gmail.com', '9348939890', '$2y$10$3RHyVPGTzJTN4F/iCy1Pr.WXTp0e88RNcBustkMaAHSP4dMJKZL1i', NULL),
(9, 'Ashim', 'Mishra', 'ashimmishra@gmail.com', '9823167724', '$2y$10$etodBBqzEE71Uycla0XzkO1x0zDUGEiKasz7SQYs5CH3.4weUf1RS', NULL),
(10, 'Sushila', 'Nepal', 'sushila@gmail.com', '9823167724', '$2y$10$DPbNcGA9Wm1HNsco5EiI1ehl3zv8lk/DHpsHk2JB1gHq67qRZ6ahW', NULL),
(11, 'Paru', 'Subedi', 'subedi@gmail.com', '9867637367', '$2y$10$ooa1G7Ljk6nHMER0BLUc0uRaVLqBUS4aht.6i69D.q45o5mH5PTCi', NULL),
(12, 'Raja', 'ram', 'ram@gmail.com', '9823167724', '$2y$10$vFxU31epTVRvW9t72ygvwOl7eEljcvyAxKUmZP8EHXEGPyH4R0G0W', NULL),
(13, 'Ramu', 'Kale', 'kale@gmai.com', '9823167724', '$2y$10$yaDknHN7NWTbr98XZDaCOeq6Lu4xcYeUoEThHhQBLvE8W9eGxR7Gq', NULL),
(14, 'Surya', 'bk', 'bk@gmial.com', '9823167724', '$2y$10$LwdshUbsM3NS/AGfpsU9XOqQm/MVdp.NDgsCuoCDsRmebRryDaMzO', NULL),
(15, 'yellow ', 'bird', 'bird@gmail.com', '9823167724', '$2y$10$cfJ.ys5RKD62VoFzxEqmIuN1wUF/An03nP4rZHSQsjlvIUHyXCGva', NULL),
(16, 'hey', 'there', 'hey@gmail.com', '9823167724', '$2y$10$A72L8ozYw6APgBCyBjCL4u1/hj5hA1HdcuS6eFyRI0QhrbeWaA/A.', NULL),
(17, 'arry', 'bey', 'bey@gmail.com', '9823167724', '$2y$10$EJPK9QhAlOrVZuq2bMKv4O.oGiujBCbNOdEWlxfUZo1S9BARx5o9C', NULL),
(18, 'side', 'delta', 'delta@gmail.com', '9823167724', '$2y$10$hgxsBemt6GXND2Q17Hs6YOflKMsxjC.4iOUTAkMaUscbBYm28hsfu', NULL),
(19, 'hello', 'beta', 'beta@gmail.com', '9823167724', '$2y$10$/jRcAG/hYAhgpdfenF40uuQLs/HGPa1mtTWaZliRBE930IdntZLHS', NULL),
(20, 'Aryan', 'Sigdel', 'sigdel@gmail.com', '9823167724', '$2y$10$snI6ebXRxUOZXHHOnhCySOcCq.uFy6a0yUY/v.4otZCF7RiLocejC', NULL),
(21, 'saaho', 'raj', 'raj@gmail.com', '9823167724', '$2y$10$YtzeCrJ/7UcI4VvOl/gltuPyVCuamQ68jtWshPsZan2jqVlDWpvIO', NULL),
(22, 'sarika', 'baby', 'baby@gmail.com', '9823167724', 'sarika0909', NULL),
(23, 'saara', 'taara', 'saara@gmaail.com', '9823167724', '0909saara', 'uploads/23.png'),
(24, 'Babu', 'Bhai', 'bhai@gmail.com', '9823167724', '0909babu', NULL),
(26, 'sameek', 'silwal', 'sami@gmail.com', '9823167724', '$2y$10$TkovS.w4guAiJhZymJu0kefsncpg/.MiAsYX8Lktky9M.a6reAF4a', NULL),
(27, 'abc', 'bcd', 'abc@gmail.com', '9823167724', '$2y$10$V0d3xPoeuwU5X0Aao4md9OAW40h1g6A9gL2xjnQHzMugv5lkTS8vW', NULL),
(28, 'aarju', 'rana', 'aarju@gmail.com', '9823167724', '$2y$10$Bi/4YrcYfXy3NANGcgYTp.0U2TSQKqNFNePA/rGKtTxqSEguQ/9b6', NULL),
(29, 'ac', 'bc', 'cc@gmail.com', '9823167724', 'cc12345', NULL),
(30, 'kale', 'babu', 'babu@gmail.com', '9823167724', '$2y$10$9r.F4BOpccnZz9vw4/4Vr.J7fRF.1lwX2TWvTIMb4On6H5d8cOkQK', NULL),
(31, 'Asmita', 'bk', 'bk@gmail.com', '9823167724', '$2y$10$2gZ6vtPHgVqNuRXEEk8SH.ULew6aDOQklpx7eGA0iM9Eq9/2Uryb2', NULL),
(32, 'Raju', 'Dahal', 'raju@gmail.com', '9823167724', '$2y$10$V8Z4vAZWKG4QXopKPSy3GONMi0WzH2C306.utbfxYU2pWp4Vha3lu', NULL),
(33, 'Krishna', 'prasad', 'prasad@gmail.com', '9823167724', '0909prasad', NULL),
(34, 'Yamuna', 'Rijal', 'rijal@gmail.com', '9823167724', 'rijal123', NULL),
(35, 'Sasi', 'Kala', 'kala@gmail.com', '9823167724', '$2y$10$L2Llt6zPnfSVTAZGUlZKbewhv/t9bB55g1Wdqbxxb.Cl7kK5j4HkW', NULL),
(36, 'Saroj', 'singh', 'saroj@gmail.com', '9823167724', 'saroj0909', NULL),
(37, 'Saroj', 'Singh', 'singh@gmail.com', '9823167724', '0909saroj', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `form`
--
ALTER TABLE `form`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `houseproperties`
--
ALTER TABLE `houseproperties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `land_properties`
--
ALTER TABLE `land_properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `form`
--
ALTER TABLE `form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `houseproperties`
--
ALTER TABLE `houseproperties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `land_properties`
--
ALTER TABLE `land_properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
