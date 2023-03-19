-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2023 at 07:07 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `icloud`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `branch_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `common_fee_collection`
--

CREATE TABLE `common_fee_collection` (
  `id` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL,
  `transid` int(11) NOT NULL,
  `admno` varchar(255) NOT NULL,
  `rollno` varchar(255) NOT NULL,
  `amount` float NOT NULL,
  `brid` int(11) NOT NULL,
  `acadamicyear` varchar(255) NOT NULL,
  `financialyear` varchar(255) NOT NULL,
  `displayreciptno` varchar(255) NOT NULL,
  `entrymode` int(11) NOT NULL,
  `paid_date` date NOT NULL,
  `inactive` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `common_fee_collection_headwise`
--

CREATE TABLE `common_fee_collection_headwise` (
  `id` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL,
  `receiptid` int(11) NOT NULL,
  `headid` int(11) NOT NULL,
  `headname` varchar(255) NOT NULL,
  `brid` int(11) NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `entrymode`
--

CREATE TABLE `entrymode` (
  `id` int(11) NOT NULL,
  `entry_modename` varchar(255) NOT NULL,
  `crdr` varchar(255) NOT NULL,
  `entrymodeno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `entrymode`
--

INSERT INTO `entrymode` (`id`, `entry_modename`, `crdr`, `entrymodeno`) VALUES
(13, 'due', 'D', 0),
(14, 'REVDUE', 'C', 12),
(15, 'scholarship', 'C', 15),
(16, 'scholarshiprev/revconsession', 'D', 16),
(17, 'consession', 'C', 15),
(18, 'RCPT', 'C', 0),
(19, 'REVRCPT', 'D', 0),
(20, 'JV', 'C', 14),
(21, 'RevJV', 'D', 14),
(22, 'PMT', 'D', 1),
(23, 'REVPMT', 'C', 1),
(24, 'Fundtransfer', 'positive and negative', 1);

-- --------------------------------------------------------

--
-- Table structure for table `feecategory`
--

CREATE TABLE `feecategory` (
  `id` int(11) NOT NULL,
  `fee_category` varchar(255) DEFAULT NULL,
  `br_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `feecollectiontype`
--

CREATE TABLE `feecollectiontype` (
  `id` int(11) NOT NULL,
  `collectionhead` varchar(255) DEFAULT NULL,
  `collectiondesc` varchar(255) DEFAULT NULL,
  `br_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `feetypes`
--

CREATE TABLE `feetypes` (
  `id` int(11) NOT NULL,
  `fee_category` int(11) NOT NULL,
  `f_name` varchar(255) NOT NULL,
  `collection_id` int(11) NOT NULL,
  `br_id` int(11) NOT NULL,
  `seq_id` int(11) NOT NULL,
  `fee_type_ledger` varchar(255) NOT NULL,
  `fee_head_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `financial_trans`
--

CREATE TABLE `financial_trans` (
  `id` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL,
  `tranid` int(11) NOT NULL,
  `admno` varchar(255) NOT NULL,
  `amount` float NOT NULL,
  `crdr` varchar(255) NOT NULL,
  `trandate` date NOT NULL,
  `acadyear` varchar(255) NOT NULL,
  `entrymode` int(11) NOT NULL,
  `voucherno` int(11) NOT NULL,
  `brid` int(11) NOT NULL,
  `type_of_concession` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `financial_transdetail`
--

CREATE TABLE `financial_transdetail` (
  `id` int(11) NOT NULL,
  `financialtranid` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL,
  `amount` float NOT NULL,
  `headid` int(11) NOT NULL,
  `crdr` varchar(255) NOT NULL,
  `br_id` int(11) NOT NULL,
  `head_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `id` int(11) NOT NULL,
  `module` varchar(255) NOT NULL,
  `moduleid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`id`, `module`, `moduleid`) VALUES
(1, 'Academic', 1),
(2, 'Academic Misc', 11),
(3, 'Hostel', 2),
(4, 'Hostel Misc', 22),
(5, 'Transport', 3),
(6, 'Transport Misc', 33);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `common_fee_collection`
--
ALTER TABLE `common_fee_collection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `common_fee_collection_headwise`
--
ALTER TABLE `common_fee_collection_headwise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entrymode`
--
ALTER TABLE `entrymode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feecategory`
--
ALTER TABLE `feecategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feecollectiontype`
--
ALTER TABLE `feecollectiontype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feetypes`
--
ALTER TABLE `feetypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `financial_trans`
--
ALTER TABLE `financial_trans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `financial_transdetail`
--
ALTER TABLE `financial_transdetail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `common_fee_collection`
--
ALTER TABLE `common_fee_collection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `common_fee_collection_headwise`
--
ALTER TABLE `common_fee_collection_headwise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `entrymode`
--
ALTER TABLE `entrymode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `feecategory`
--
ALTER TABLE `feecategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feecollectiontype`
--
ALTER TABLE `feecollectiontype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feetypes`
--
ALTER TABLE `feetypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_trans`
--
ALTER TABLE `financial_trans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_transdetail`
--
ALTER TABLE `financial_transdetail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
