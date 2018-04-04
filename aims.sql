-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2018 at 12:06 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aims`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_num` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `position` enum('ah','as','ea','os','sss') NOT NULL,
  `emp_type` enum('probationary','fixed_period','regular','trainee') NOT NULL,
  `date_hired` date NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `mother_name` varchar(255) NOT NULL,
  `sss_no` varchar(255) NOT NULL,
  `ph_no` varchar(255) NOT NULL,
  `pagibig` varchar(255) NOT NULL,
  `tin` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `score` int(100) NOT NULL,
  `acct_type` enum('admin','employee') NOT NULL DEFAULT 'employee',
  `status` enum('pending','active') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `fname`, `mname`, `lname`, `address`, `contact_num`, `birthdate`, `position`, `emp_type`, `date_hired`, `father_name`, `mother_name`, `sss_no`, `ph_no`, `pagibig`, `tin`, `photo`, `score`, `acct_type`, `status`) VALUES
(1, 'admin', 'admin', 'Luis Edward', 'Manzanal', 'Miranda', 'Sipocot, Camarines Sur', '09980083433', '0000-00-00', 'ah', 'probationary', '0000-00-00', '', '', '', '', '', '', '', 0, 'admin', 'active'),
(6, 'abccc', 'asdasd', 'asdasdasdasdas', 'dasdas', 'dasdasda', 'sdasdasd', 'asdasdasdasd', '2018-03-08', 'ah', 'fixed_period', '2018-03-08', 'asdasdasd', 'asdasdasdas', 'dasdasdas', 'asdasdasdassdasd', 'asdasd', 'asdasd', '1522061327Desert.jpg', 0, 'employee', 'active'),
(8, 'asd123', 'asd123', 'Jess123', 'Men', 'Pards', 'Abc', 'asd291898', '2018-03-27', 'as', 'regular', '2018-03-27', 'Pards1', 'Pards2', '12412412', '412412412', '1241242', '4124124', '1522564913download.jpg', 0, 'employee', 'active'),
(12, 'kim', 'kim', 'Kim', 'b', 'Luta', 'aksfgsdgh', '0837349562', '1988-02-08', 'as', 'trainee', '2015-02-09', 'm', 'l', '039457', '385619', '124154', '343254', '', 0, 'employee', 'active'),
(13, '1234567890', '1234567890', 'shay', 'm', 'tias', 'shay@gmail.com', '09562738193', '0000-00-00', 'ah', 'probationary', '0000-00-00', '', '', '', '', '', '', '', 9, 'employee', 'active'),
(14, '', '', 'Kobe', 'Frank', 'Byant', 'DYD', '09153623213', '0000-00-00', 'ah', 'probationary', '0000-00-00', '', '', '', '', '', '', '', 14, 'employee', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(255) NOT NULL,
  `account_id` int(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `date_uploaded` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`id`, `account_id`, `description`, `photo`, `date_uploaded`) VALUES
(1, 6, 'asdasd', 'Jellyfish.jpg', '2018-03-26'),
(2, 6, 'testing ulit', 'Hydrangeas.jpg', '2018-03-26'),
(5, 6, 'Alnera', '1522139135Koala.jpg', '2018-03-27'),
(6, 8, 'SSS', '1522193637Tulips.jpg', '2018-03-27'),
(7, 8, 'Test1', '152277542429693149_10211796193848010_1728155287_o.jpg', '2018-04-03');

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

CREATE TABLE `codes` (
  `id` int(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `status` enum('0','1') DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `codes`
--

INSERT INTO `codes` (`id`, `code`, `status`) VALUES
(1, 'abcd1', '1'),
(7, '98723bca26c9f6ba5b9e96239fe8e2c6', '1'),
(8, '6b2fdae6b2453866b0fe24aa429ea2c1', '1'),
(9, '95f5d632bbb8179679eb629d08c2b232', '1'),
(10, 'cdba7cd7e6c77b6eb444451570a6fde0', '1'),
(11, '27653209db6431c4bf9985fec9ce99e4', '0'),
(12, '1a7171cc5c83448e5a6549161aa60257', '0'),
(16, '3e4a556d9b67ec9700289883147f2d73', '0'),
(17, 'a17e34a7f9504458ff1c7e19f289235e', '0'),
(18, 'd6b593dd78731ea297942e432e0ab7bd', '0'),
(19, 'fde8d8eb6de344d4abe13e52c91b479e', '0'),
(20, '91987df563d1e23da0393d01aedacd0b', '0');

-- --------------------------------------------------------

--
-- Table structure for table `eval`
--

CREATE TABLE `eval` (
  `id` int(255) NOT NULL,
  `acct_id` int(255) NOT NULL,
  `rating` float NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evaldata`
--

CREATE TABLE `evaldata` (
  `id` int(255) NOT NULL,
  `eval_id` int(255) NOT NULL,
  `emp_status` enum('trainee','fix_period','probationary','regular') DEFAULT 'trainee',
  `eval_period` enum('Q1','Q2','Q3','Q4') DEFAULT NULL,
  `comment` text,
  `evaluator` varchar(255) DEFAULT NULL,
  `1a` int(1) NOT NULL,
  `2a` int(1) NOT NULL,
  `3a` int(1) NOT NULL,
  `4a` int(1) NOT NULL,
  `5a` int(1) NOT NULL,
  `6a` int(1) NOT NULL,
  `6b` int(1) NOT NULL,
  `6c` int(1) NOT NULL,
  `6d` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `id` int(255) NOT NULL,
  `account_id` int(255) NOT NULL,
  `C1` varchar(1) NOT NULL,
  `C2` varchar(1) NOT NULL,
  `C3` varchar(1) NOT NULL,
  `C4` varchar(1) NOT NULL,
  `C5` varchar(1) NOT NULL,
  `C6` varchar(1) NOT NULL,
  `C7` varchar(1) NOT NULL,
  `C8` varchar(1) NOT NULL,
  `C9` varchar(1) NOT NULL,
  `C10` varchar(1) NOT NULL,
  `C11` varchar(1) NOT NULL,
  `C12` varchar(1) NOT NULL,
  `C13` varchar(1) NOT NULL,
  `C14` varchar(100) NOT NULL,
  `C15` varchar(100) NOT NULL,
  `C16` varchar(1) NOT NULL,
  `C17` varchar(1) NOT NULL,
  `C18` varchar(1) NOT NULL,
  `C19a` varchar(1) NOT NULL,
  `C19b` varchar(1) NOT NULL,
  `C19c` varchar(1) NOT NULL,
  `C19d` varchar(1) NOT NULL,
  `C20a` varchar(1) NOT NULL,
  `C20b` varchar(1) NOT NULL,
  `C20c` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`id`, `account_id`, `C1`, `C2`, `C3`, `C4`, `C5`, `C6`, `C7`, `C8`, `C9`, `C10`, `C11`, `C12`, `C13`, `C14`, `C15`, `C16`, `C17`, `C18`, `C19a`, `C19b`, `C19c`, `C19d`, `C20a`, `C20b`, `C20c`) VALUES
(5, 13, 'b', 'c', 'a', 'b', 'b', '1', 'b', 'c', 'b', 'c', 'b', '1', 'c', 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"1\";}', 'a:2:{i:0;s:1:\"a\";i:1;s:1:\"1\";}', '1', '1', '1', '1', '1', 'a', 'b', '1', 'b', 'a'),
(6, 14, '1', '1', '1', '1', '1', 'b', '1', '1', '1', '1', 'b', 'a', 'c', 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"b\";}', 'a:2:{i:0;s:1:\"a\";i:1;s:1:\"1\";}', '1', '1', '1', 'b', '1', 'a', 'b', '1', 'b', 'b');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `codes`
--
ALTER TABLE `codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `eval`
--
ALTER TABLE `eval`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acct_id` (`acct_id`);

--
-- Indexes for table `evaldata`
--
ALTER TABLE `evaldata`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `eval_id` (`eval_id`);

--
-- Indexes for table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_id` (`account_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `codes`
--
ALTER TABLE `codes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `eval`
--
ALTER TABLE `eval`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evaldata`
--
ALTER TABLE `evaldata`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `attachments_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `eval`
--
ALTER TABLE `eval`
  ADD CONSTRAINT `eval_ibfk_1` FOREIGN KEY (`acct_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `evaldata`
--
ALTER TABLE `evaldata`
  ADD CONSTRAINT `evaldata_ibfk_1` FOREIGN KEY (`eval_id`) REFERENCES `eval` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `exam`
--
ALTER TABLE `exam`
  ADD CONSTRAINT `exam_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
