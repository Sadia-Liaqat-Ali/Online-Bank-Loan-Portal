-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 10:46 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `loanportal`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `cnic` varchar(20) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `password`, `cnic`, `phone`, `address`, `created_at`) VALUES
(7, 'diya', 'diya@gmail.com', '123', '3740599881234', '03111222333', 'Islamabad', '2025-04-25 16:47:27'),
(8, 'wafa', 'wafa@gmail.com', '$2y$10$abcde2', '3520222234567', '03445556677', 'Lahore', '2025-04-25 16:47:27'),
(9, 'sana', 'sana@gmail.com', '$2y$10$abcde3', '3740654321999', '03007778888', 'Faisalabad', '2025-04-25 16:47:27'),
(13, 'Diya Shezadi', 'abu@gmail.com', '$2y$10$OpZRs7EQQl.eqQG4titfSuMktr5G6MPUrjVQLFnqD4ZqD0Az1CM1e', '779999999900', '888888888899', 'punjab', '2025-04-25 16:56:16'),
(14, 'Sadiaa', 'sadia@gmail.com', '$2y$10$qkuLjllMgCiHxfw5MF55IuzZg8gXvbcU1uvdT5AYvV8D2hpcnQarO', '77999999996644', '8888888888', 'attock', '2025-04-25 19:39:50');

-- --------------------------------------------------------

--
-- Table structure for table `loan_applications`
--

CREATE TABLE `loan_applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `loan_type_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `purpose` text NOT NULL,
  `proof_file` varchar(255) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_repaid` decimal(10,2) DEFAULT 0.00,
  `due_date` date DEFAULT NULL,
  `application_type` enum('domestic','overseas') NOT NULL DEFAULT 'domestic',
  `officer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loan_applications`
--

INSERT INTO `loan_applications` (`id`, `user_id`, `loan_type_id`, `amount`, `purpose`, `proof_file`, `status`, `feedback`, `created_at`, `total_repaid`, `due_date`, `application_type`, `officer_id`) VALUES
(23, 7, 10, '150000.00', 'Tuition and laptop for final year', 'proof_tuition_zeeshan.jpg', 'Pending', NULL, '2025-04-25 16:47:45', '0.00', NULL, 'domestic', 5),
(24, 8, 13, '80000.00', 'Emergency surgery for father', 'hospital_docs_fatima.pdf', 'Pending', NULL, '2025-04-25 16:47:45', '0.00', NULL, 'domestic', 8),
(25, 7, 11, '450000.00', 'Launching a mobile accessories business', 'startup_pitch_zeeshan.pdf', 'Pending', NULL, '2025-04-25 16:47:45', '0.00', NULL, 'overseas', 8),
(26, 9, 12, '120000.00', 'Renovating two rooms and kitchen', 'property_proof_rizwan.jpg', 'Pending', NULL, '2025-04-25 16:47:45', '0.00', NULL, 'domestic', 5),
(27, 9, 14, '500000.00', 'Buying new tractor and irrigation pump', 'farmer_card_rizwan.png', 'Pending', NULL, '2025-04-25 16:47:45', '0.00', NULL, 'domestic', 5),
(28, 13, 10, '5000.00', 'urgently needed', '1745601852_WhatsApp Image 2024-12-21 at 8.10.11 AM.jpeg', 'Approved', 'great work', '2025-04-25 17:24:12', '0.00', NULL, 'overseas', 8),
(29, 13, 14, '444000.00', 'needed', '1745603731_Untitled Diagram.drawio (2).png', 'Approved', 'very nice', '2025-04-25 17:55:31', '0.00', NULL, 'domestic', 5);

-- --------------------------------------------------------

--
-- Table structure for table `loan_queries`
--

CREATE TABLE `loan_queries` (
  `id` int(11) NOT NULL,
  `topic` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `reply` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loan_queries`
--

INSERT INTO `loan_queries` (`id`, `topic`, `type`, `message`, `reply`, `created_at`, `customer_id`) VALUES
(11, 'Clarification on interest', 'Loan Info', 'Is interest fixed for medical loan?', NULL, '2025-04-25 16:48:01', 8),
(12, 'Document upload error', 'Technical', 'I was unable to upload my proof file.', 'Please try compressing it to under 2MB.', '2025-04-25 16:48:01', 9),
(13, 'Expected approval time?', 'Status', 'When can I expect loan approval?', 'Within 5 working days after officer review.', '2025-04-25 16:48:01', 7),
(14, 'My cnic is outdated', 'Eligibility', 'kindly suggest me ways to submit proof of eligibility perfectly.', 'wait i will update today!', '2025-04-25 20:04:31', 13);

-- --------------------------------------------------------

--
-- Table structure for table `loan_types`
--

CREATE TABLE `loan_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `loan_category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `min_amount` decimal(10,2) NOT NULL,
  `max_amount` decimal(10,2) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `term_months` int(11) NOT NULL,
  `eligibility` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loan_types`
--

INSERT INTO `loan_types` (`id`, `name`, `loan_category`, `description`, `min_amount`, `max_amount`, `interest_rate`, `term_months`, `eligibility`, `created_at`) VALUES
(5, 'New IT sector Developement loan', 'Education Loan', 'great new govttt initiative for it department..', '20000.00', '50000.00', '2.00', 12, 'age 25 to 50', '2025-04-22 14:45:37'),
(10, 'Student Career Growth Loan', 'Education Loan', 'Aimed at supporting students pursuing higher studies or skill development programs. Covers tuition, accommodation, and learning material.', '10000.00', '300000.00', '3.50', 24, 'Students aged 18-30 with valid enrollment proof.', '2025-04-25 16:45:52'),
(11, 'Startup Business Loan', 'Business Loan', 'For aspiring entrepreneurs launching new ventures. Helps with initial setup, equipment, and marketing expenses.', '50000.00', '1000000.00', '4.75', 36, 'Applicants aged 22-45 with a business proposal.', '2025-04-25 16:45:52'),
(12, 'Home Renovation Loan', 'Home Loan', 'Designed for homeowners planning renovations, repairs, or home upgrades.', '20000.00', '500000.00', '5.00', 18, 'Property ownership documents and CNIC required.', '2025-04-25 16:45:52'),
(13, 'Medical Emergency Loan', 'Personal Loan', 'Emergency funds for unexpected medical expenses, surgeries, or hospital bills.', '15000.00', '200000.00', '2.50', 12, 'All citizens with medical documentation.', '2025-04-25 16:45:52'),
(14, 'Agricultural Equipment Loan', 'Business Loan', 'For farmers or agri-entrepreneurs needing funds for machinery or irrigation setup.', '30000.00', '700000.00', '3.75', 24, 'Land ownership proof or farmer registration needed.', '2025-04-25 16:45:52'),
(15, 'Freelancer Gear Loan', 'Technology Loan', 'For freelancers needing laptops, cameras, or gadgets to upgrade their work setup.', '10000.00', '150000.00', '2.25', 12, 'Freelancing proof or work portfolio mandatory.', '2025-04-25 16:45:52');

-- --------------------------------------------------------

--
-- Table structure for table `repayments`
--

CREATE TABLE `repayments` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `installment_no` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` varchar(50) NOT NULL,
  `proof` varchar(255) NOT NULL,
  `status` enum('In Review','Completed','Rejected') DEFAULT 'In Review',
  `submitted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `repayments`
--

INSERT INTO `repayments` (`id`, `application_id`, `installment_no`, `due_date`, `amount`, `method`, `proof`, `status`, `submitted_at`) VALUES
(6, 29, 12, '2027-04-25', '38387.50', 'Debit/Credit Card', '../uploads/repayments/1745603847_Untitled Diagram.drawio (2).png', 'Completed', '2025-04-25 10:57:27'),
(7, 29, 11, '2027-02-25', '38387.50', 'Debit/Credit Card', '../uploads/repayments/1745610776_Untitled Diagram.drawio (3).png', 'Completed', '2025-04-25 12:52:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('Bank Administrator','Loan Officer') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Ali Raza', 'admin@gmail.com', '123', 'Bank Administrator', '2025-04-17 05:25:12'),
(5, 'Aish Khan', 'aishh@gmail.com', 'aish', 'Loan Officer', '2025-04-22 14:42:38'),
(8, 'Diya Shezadi', 'diya@gmail.com', 'diya', 'Loan Officer', '2025-04-25 17:25:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `loan_applications`
--
ALTER TABLE `loan_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_type_id` (`loan_type_id`),
  ADD KEY `fk_customer_app` (`user_id`),
  ADD KEY `fk_officer` (`officer_id`);

--
-- Indexes for table `loan_queries`
--
ALTER TABLE `loan_queries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_customer` (`customer_id`);

--
-- Indexes for table `loan_types`
--
ALTER TABLE `loan_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `repayments`
--
ALTER TABLE `repayments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `loan_applications`
--
ALTER TABLE `loan_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `loan_queries`
--
ALTER TABLE `loan_queries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `loan_types`
--
ALTER TABLE `loan_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `repayments`
--
ALTER TABLE `repayments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loan_applications`
--
ALTER TABLE `loan_applications`
  ADD CONSTRAINT `fk_customer_app` FOREIGN KEY (`user_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `fk_officer` FOREIGN KEY (`officer_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loan_applications_ibfk_2` FOREIGN KEY (`loan_type_id`) REFERENCES `loan_types` (`id`);

--
-- Constraints for table `loan_queries`
--
ALTER TABLE `loan_queries`
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `repayments`
--
ALTER TABLE `repayments`
  ADD CONSTRAINT `repayments_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `loan_applications` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
