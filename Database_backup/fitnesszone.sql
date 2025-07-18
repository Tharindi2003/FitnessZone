-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2025 at 06:45 PM
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
-- Database: `fitnesszone`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `name`, `date`, `time`) VALUES
(1, 'ilyaas', '2024-12-13', '10:00 AM'),
(6, 'sunil', '2024-12-21', '1:30 PM'),
(7, 'ikhlaas', '2024-12-13', '12:30 PM'),
(8, 'afzer', '2024-12-16', '1:00 PM'),
(9, 'Basith', '2024-12-28', '2:00 PM'),
(10, 'Afzer', '2025-01-23', '12:00 PM'),
(11, 'naruto uzumaki', '2025-01-21', '10:00 AM');

-- --------------------------------------------------------

--
-- Table structure for table `coach`
--

CREATE TABLE `coach` (
  `c_id` int(11) NOT NULL,
  `c_firstname` varchar(255) NOT NULL,
  `c_lastname` varchar(255) NOT NULL,
  `c_age` int(11) NOT NULL,
  `c_dob` date NOT NULL,
  `c_email` varchar(255) NOT NULL,
  `c_gender` enum('Male','Female','Other') DEFAULT NULL,
  `c_specialization` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coach`
--

INSERT INTO `coach` (`c_id`, `c_firstname`, `c_lastname`, `c_age`, `c_dob`, `c_email`, `c_gender`, `c_specialization`) VALUES
(1, 'John', 'Smith', 35, '1988-05-10', 'john.smith@fz.com', 'Male', 'Strength & Conditioning'),
(2, 'Emma', 'Johnson', 29, '1994-03-22', 'emma.johnson@fz.com', 'Female', 'Yoga Instructor'),
(3, 'Michael', 'Williams', 42, '1981-01-15', 'michael.williams@fz.com', 'Male', 'Personal Training'),
(4, 'Olivia', 'Brown', 27, '1996-07-05', 'olivia.brown@fz.com', 'Female', 'Zumba Specialist'),
(5, 'James', 'Taylor', 39, '1984-11-20', 'james.taylor@fz.com', 'Male', 'Weightlifting Coach'),
(6, 'Sophia', 'Martinez', 33, '1990-06-14', 'sophia.martinez@fz.com', 'Female', 'Pilates Instructor'),
(7, 'Mia', 'Rodriguez', 31, '1992-12-09', 'mia.rodriguez@fz.com', 'Female', 'Nutrition & Wellness Coach'),
(8, 'Ethan', 'Lopez', 28, '1995-04-18', 'ethan.lopez@fz.com', 'Male', 'Cardio Training Specialist'),
(9, 'Charlotte', 'Anderson', 40, '1983-09-25', 'charlotte.anderson@fz.com', 'Female', 'HIIT Trainer');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `name`, `age`, `gender`, `email`) VALUES
(1, 'Ahamed ilyaas', 20, 'Male', 'mrrilyaas@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE `membership` (
  `member_id` varchar(10) NOT NULL,
  `package_name` varchar(50) NOT NULL,
  `expiry_date` date NOT NULL,
  `facilities` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `membership_id` varchar(50) NOT NULL,
  `package` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expiry_date` date NOT NULL,
  `card_number` varchar(20) NOT NULL,
  `card_holder_name` varchar(100) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `membership_id`, `package`, `amount`, `expiry_date`, `card_number`, `card_holder_name`, `payment_date`) VALUES
(1, 'M00004', 'Basic (1 Month)', 1000.00, '2025-01-02', '4588', 'ilyaas', '2024-12-02 05:06:50'),
(2, 'M00004', 'Basic (1 Month)', 1000.00, '2025-01-05', '1450', 'ilyaas', '2024-12-05 11:56:47'),
(3, 'M00004', 'Basic (1 Month)', 1000.00, '2025-01-05', '50000', 'ilyaas', '2024-12-05 12:06:02'),
(4, 'M00001', 'Standard (3 Months)', 2500.00, '2025-03-10', '6151', 'ilyaas', '2024-12-10 15:46:32'),
(5, 'M00001', 'Ultimate (1 Year)', 8000.00, '2025-12-15', '488', 'ilyaas', '2024-12-15 05:26:51'),
(6, 'M00001', 'Standard (3 Months)', 2500.00, '2025-03-22', 'iid', 'nnf', '2024-12-22 08:05:32'),
(7, 'M00002', 'Ultimate (1 Year)', 8000.00, '2025-12-23', '4', '4', '2024-12-23 06:55:18');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `username`, `password`, `email`) VALUES
(1, 'ADMIN', 'ADMIN', 'admin@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `u_id` int(11) NOT NULL,
  `u_firstname` varchar(50) DEFAULT NULL,
  `u_lastname` varchar(50) DEFAULT NULL,
  `u_age` int(11) DEFAULT NULL,
  `u_dob` date DEFAULT NULL,
  `u_gender` enum('Male','Female') DEFAULT NULL,
  `u_email` varchar(100) DEFAULT NULL,
  `u_membershipid` varchar(50) DEFAULT NULL,
  `u_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`u_id`, `u_firstname`, `u_lastname`, `u_age`, `u_dob`, `u_gender`, `u_email`, `u_membershipid`, `u_password`) VALUES
(1, 'Ahamed', 'ilyaas', 20, '2004-03-02', 'Male', 'mrrilyaas@gmail.com', 'M00001', '2004'),
(2, 'ADMIN', 'ADMIN', 20, '2012-10-10', 'Male', 'mrrilyaas@gmail.com', 'M00002', '2004'),
(3, 'Naruto', 'Uzumaki', 20, '2005-02-05', 'Male', 'uzumaki@gmail.com', 'M00003', '2004');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `member_id` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`member_id`, `password`, `name`) VALUES
('M00001', '2004', 'Ahamed ilyaas'),
('M00002', '2004', 'ADMIN ADMIN'),
('M00003', '2004', 'Naruto Uzumaki');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date` (`date`,`time`);

--
-- Indexes for table `coach`
--
ALTER TABLE `coach`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `membership`
--
ALTER TABLE `membership`
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`u_id`),
  ADD UNIQUE KEY `u_membershipid` (`u_membershipid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `coach`
--
ALTER TABLE `coach`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `membership`
--
ALTER TABLE `membership`
  ADD CONSTRAINT `membership_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `users` (`member_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
