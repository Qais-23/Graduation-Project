-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 04, 2025 at 04:59 PM
-- Server version: 8.0.41
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gradproj1190449_graduation_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerID` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Address` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Age` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `PhoneNumber` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `SecurityQuestion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `SecurityAnswer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerID`, `Name`, `Address`, `Age`, `Email`, `PhoneNumber`, `SecurityQuestion`, `SecurityAnswer`, `created_at`) VALUES
('0405290052', 'Wasfie w', 'Ramallah', '26', 'w@gmail.com', '0599654321', 'What city were you born in?', '$2y$10$GZNz7yf8woHckeq2GpHOseIu/Zh9bf653JRDclM1Xw9rTMzO4zrL6', '2025-01-09 17:04:48'),
('0483290151', 'israaayyad', 'Ramallah', '22', 'israa@gmail.com', '0598764570', 'What city were you born in?', '$2y$10$9.HO.jJ6jRwIOdpuANnb1uehIFHweuoa1TQzCOTDZ4f.VzwIlcgDO', '2025-01-14 09:19:25'),
('1076656600', 'abdalrhman', 'ramallah', '23', 'qadanet@gmail.com', '0594266255', 'What city were you born in?', '$2y$10$PpzJ9soxkSCwuETuA4M4Se01jQmrcpS1vJQ9h5otxgUlV/yCNruJq', '2025-01-02 08:51:51'),
('1316488225', 'diae', 'ffewe', '37', 'yazan@gmail.com', '0594266255', 'What city were you born in?', '$2y$10$oUQ9TAAC8humvXI97M5DEuUXfUatdGp5CRf3iVkrgVUWo7C6VRZSO', '2025-01-02 08:57:07'),
('1501391992', 'Alaa Ismail', 'Al-Zahra Street, Hebron', '22', 'Alaa773@gmail.com', '0598321654', 'What city were you born in?', '$2y$10$Eb6z2qfkxXDfbyWMPo0g/OZB58Mm/bKrXIgQJsprVXJF09cl3tcQC', '2024-12-04 17:55:56'),
('2051196568', 'test', 'test', '55', 'test@gmail.com', '0593123456', 'What is your mother\'s maiden name?', '$2y$10$O8xcVvhkmAFKOSvafyP3C.FL9beVFfdGgAjQD1SFf2GflJCwN.DkK', '2025-01-09 17:24:27'),
('3214416059', 'qutiba ', 'turmosaya', '19', 'qadanet@gmail.com', '0594266255', 'What city were you born in?', '$2y$10$L43Nzf.NAJwsBo2tXPJI4eEp.TVj6ya8c37fSM3rfhsdURBa/OIL6', '2024-12-31 21:35:09'),
('3550170914', 'Ahmad yasser', 'Ramallah', '49', 'ahmad@gmail.com', '0592123123', 'What city were you born in?', '$2y$10$vRzB6MT1rzjUog64VY4JDelmndBVcCgR8uHkC2/vWseFNIrYg6myO', '2025-01-02 19:14:26'),
('3657808260', 'Alaa Jameel', 'Ramallah', '29', 'alaa8754@gmail.com', '0591245783', 'What city were you born in?', '$2y$10$7wNUzHZW6qfVXdXmIGu8j.ubZ4hvXmo1I7XLCCPED8L5CqWtwrQPS', '2025-01-02 19:15:34'),
('4050539954', 'Anwaar qasem ahmad qasem', 'ramallah', '22', 'Anwaarqasem19@yahoo.com', '0597462602', 'What city were you born in?', '$2y$10$0J2cx9tKC6Sz.h4vFed6v.3S5ApfOSXNprnaai1xwif3cs6WD8eBG', '2025-01-09 18:03:27'),
('4955970674', 'Qais', 'Ramallah', '23', 'qais@gmail.com', '0599000333', 'What city were you born in?', '$2y$10$l7kqrSL8bL6zzm5pSSOgeuoHt6WzKNZaCX7nUguun/fovAs6s/3MK', '2024-12-04 17:54:58'),
('5363386848', 'israa', 'Ramallah', '22', 'israa@gmail.com', '0598764570', 'What city were you born in?', '$2y$10$dLkPyob3eJp2aVmzy0nt.egE2uJcVQ7OVHe64HeBZLr.1SR2ulC3q', '2025-01-02 08:54:15'),
('5402298369', 'Wasfia Awwad', 'Tulkarm', '20', 'wasfia@hotmail.com', '0577543788', 'What city were you born in?', '$2y$10$laC7xSVjU95tgBwwiX.8iO.I5j.NzFguG1AG45esEYRqh4n/qP8be', '2025-01-09 17:50:27'),
('5532712640', 'sadi', 'turmosaya', '32', 'yazan@gmail.com', '0595266253', 'What city were you born in?', '$2y$10$ixfEd0BJdRCwgSwKtkOYQuo7WctzEcojQxMG16ytY5cOPYZ4gzCRa', '2025-01-02 09:07:57'),
('6041235121', 'tamer', 'slwad', '45', 'suhaibjhmom16@gmail.com', '0597266253', 'What city were you born in?', '$2y$10$W9DXNZ7cMZ784Od0aoWrEeblbWNV9x4F8PYhPJrTZca6zGLk9OdoK', '2025-01-02 08:55:37'),
('6174605338', 'Ali Ahmad', 'Al-Wehda Street, Ramallah', '30', 'ali.ahmad@gmail.com', '0599876543', 'What is your favorite book?', '$2y$10$4dkZLRNRQAwDiyObCS68/eMi82PAmAzMvCqtvYbOkRqBQMEiLuaai', '2024-12-04 17:49:53'),
('6309430972', 'Khalid aboJameel', 'Nablus', '29', 'khalid23@gmail.com', '0592316545', 'What city were you born in?', '$2y$10$e45eBxjkw1Cj3LgXi1bm1Omcx0Sgz/cBFnx.9y5dEKspDmAkdVRPi', '2025-01-02 19:11:49'),
('8115612148', 'rulla', 'dcdwc', '35', 'thhthht@gmail.com', '0595266255', 'What city were you born in?', '$2y$10$Yp9w0dbW9XEG6RKXl6KOSuHtuVnMFTZzztrR7Crv78D/OQGcHTKX2', '2025-01-02 08:59:48'),
('8263233966', 'test22', 'test22', '55', 'test22@gmail.com', '0592123456', 'What was the name of your first school?', '$2y$10$AC8RCqh4InEUnQHIzEtTheqWEfzj5keJmSQ/5VOMyA2EiePDG3Y5y', '2025-01-11 16:48:49'),
('8290149739', 'Wafeeq Zaeem', 'Hebron', '32', 'wafeeq@gmail.com', '0569321654', 'What city were you born in?', '$2y$10$A/M/2Q8RJwr2jlXceiqxquPk5bGndB7XV/OQzHcWGQxPyqaoPzdkq', '2025-01-02 19:13:32'),
('8597194991', 'rebhi', 'ramallah', '22', 'ribhi@gmail.com', '0597266253', 'What city were you born in?', '$2y$10$7bCPx/GIFF0TO50ainEvbu6.Z/sh2Zz.ZgjALR68XcY8sfClJsttO', '2024-12-31 21:37:09'),
('8882976639', 'suhaib', 'Nablus', '32', 'suhaibjhmom16@gmail.com', '0595266255', 'What city were you born in?', '$2y$10$4jl79GlacqX5kyj97tYaGOgrsbaYjpMWatVbXxpa8oxKLqdnMwPXq', '2025-01-02 09:06:27'),
('9531271320', 'suhib', 'turmosaya', '20', 'suhaibjhmom16@gmail.com', '0595266255', 'What city were you born in?', '$2y$10$h96C.WFKCeeG3nVFilM6ROfuWWEvNSelE.FzmQiJ00JfqluR68eHe', '2024-12-31 21:33:23'),
('9597447671', 'tala', 'ish9us', '22', 'thhthht@gmail.com', '0594266255', 'What city were you born in?', '$2y$10$.jUUV.BTZc7VtUJE0RLPYey/obzgEJQL4QoNsM5ARCEyIEUrP7RfK', '2025-01-02 09:05:17'),
('9845853996', 'mahdi', 'turmosaya', '29', 'yazanjhmom@gmail.com', '0595266253', 'What city were you born in?', '$2y$10$kuWJv2gKipSpxSc0WmvYTeOv7OjVbLULDUpHaIYoIIEd2hNPz.vkC', '2024-12-31 21:40:14');

-- --------------------------------------------------------

--
-- Table structure for table `deleted_recommendations`
--

CREATE TABLE `deleted_recommendations` (
  `id` int NOT NULL,
  `customerID` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `product_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `Employee_Name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Employee_Id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Employee_Email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Employee_Address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Employee_PhoneNumber` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`Employee_Name`, `Employee_Id`, `Employee_Email`, `Employee_Address`, `Employee_PhoneNumber`) VALUES
('Ahmad Ali', '005', 'Ahmad@gmail.com', 'Ramallah', '0598321654'),
('Sami Issa', '055456189', 'Sami11@gmail.com', 'Ramallah', '0594512390'),
('Anwar Ahmad', '456789', 'anwar@gmail.com', 'Ramallah', '0598321654'),
('Yazan AbuAwwad', '9451234578', 'yazan@gmail.com', 'Ramallah', '0599200300');

-- --------------------------------------------------------

--
-- Table structure for table `emp_users`
--

CREATE TABLE `emp_users` (
  `emp_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `emp_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_users`
--

INSERT INTO `emp_users` (`emp_id`, `username`, `emp_password`) VALUES
('456789', 'anwar56', '$2y$10$Kc9.AimCiHbG0/hg1G4NU.SoS0T4CVRHhvBGZEdS5f.eTqECZW.jm'),
('055456189', 'fadi55', '$2y$10$sOejZw08hj/BFKzM7sFhdOP3rMRat7c.745OwTJnGpOd46Aq1dZJy'),
('005', 'samer123', '$2y$10$jGgZoQ3ChKwunzobVZkIHe1SWse7VBZr41MAgYDgwDC3Bzthn2KEu'),
('9451234578', 'yazan0', '$2y$10$prxy1Hc26glBzx1x7g0TdOoT6EcAPTYwLJWrODYH8YtLboddPKmUC');

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `Manager_ID` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Manager_Name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Manager_Email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Manager_Address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Manager_PhoneNumber` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Secret_Question` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Answer` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager`
--

INSERT INTO `manager` (`Manager_ID`, `Manager_Name`, `Manager_Email`, `Manager_Address`, `Manager_PhoneNumber`, `Secret_Question`, `Answer`) VALUES
('987654321', 'Qais Assaf', 'qaisassaf@gmail.com', 'Ramallah', '0599123000', 'What is your best food?', 'Kabab');

-- --------------------------------------------------------

--
-- Table structure for table `manager_employee_tickets`
--

CREATE TABLE `manager_employee_tickets` (
  `ticket_id` int NOT NULL,
  `manager_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `employee_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('open','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'open',
  `last_response` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager_employee_tickets`
--

INSERT INTO `manager_employee_tickets` (`ticket_id`, `manager_id`, `employee_id`, `message`, `message_date`, `status`, `last_response`) VALUES
(17, '987654321', '055456189', 'Dear Mr. Qais,\n\nI am writing to inform you of a significant increase in both orders and total sales on the platform. We are making excellent progress towards our goals.\n\nI would like to express my sincere gratitude for your strategic plans to improve our website. These enhancements are clearly having a positive impact, as customer satisfaction is also on the rise.\n\nThank you, Manager, for your continued support and guidance.\n\nSincerely,\n\nManager: Dear Fadi Khalil,\n\nThank you for this encouraging update! I\'m delighted to hear about the significant increase in orders and total sales. This is a testament to the hard work and dedication of the entire team.\n\nYour positive feedback regarding the website improvements is very rewarding. I\'m glad that these enhancements are contributing to increased customer satisfaction.\n\nPlease keep up the excellent work! I\'m confident that we will continue to achieve great success.\n\nBest regards,\n\nMr. Qais', '2025-01-12 10:53:18', 'open', '2025-01-12 11:05:01');

-- --------------------------------------------------------

--
-- Table structure for table `manager_user`
--

CREATE TABLE `manager_user` (
  `id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager_user`
--

INSERT INTO `manager_user` (`id`, `username`, `password`) VALUES
('987654321', 'qais1', '$2y$10$0Oc2qH7T9D9mOAgwi2Ha7.l7rdfq9D8ekvvOBY/r20wuctq7oyU4u');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int NOT NULL,
  `seller_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) DEFAULT '0',
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `Order_ID` int NOT NULL,
  `Order_Date` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Total_Amount` decimal(10,2) DEFAULT NULL,
  `Order_Status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `CustomerID` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `SellerID` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Payment_Status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`Order_ID`, `Order_Date`, `Total_Amount`, `Order_Status`, `CustomerID`, `SellerID`, `Payment_Status`) VALUES
(259, '2025-01-01 15:31:52', 48000.00, 'Shipped', '4955970674', '2385003204', 'Paid'),
(260, '2025-01-01 15:34:00', 30140.00, 'Shipped', '1501391992', '2385003204', 'Paid'),
(261, '2025-01-01 15:36:25', 132650.00, 'Shipped', '6174605338', '2385003204', 'Paid'),
(262, '2025-01-01 15:38:13', 840.00, 'Shipped', '4955970674', '2385003204', 'Paid'),
(263, '2025-01-01 16:37:04', 22800.00, 'Shipped', '4955970674', '2385003204', 'Paid'),
(264, '2025-01-02 15:09:15', 55350.00, 'Shipped', '5532712640', '2385003204', 'Paid'),
(266, '2025-01-02 16:28:31', 50700.00, 'Shipped', '9531271320', '2385003204', 'Paid'),
(267, '2025-01-02 19:20:31', 21000.00, 'Shipped', '3657808260', '2385003204', 'Paid'),
(268, '2025-01-03 07:19:54', 120.00, 'Shipped', '6174605338', '2385003204', 'Paid'),
(269, '2025-01-03 11:56:52', 473750.00, 'Shipped', '1501391992', '2385003204', 'Paid'),
(270, '2025-01-06 22:12:26', 182400.00, 'Shipped', '4955970674', '2385003204', 'Paid'),
(271, '2025-01-07 15:18:59', 336000.00, 'Shipped', '4955970674', '2385003204', 'Paid'),
(272, '2025-01-08 18:51:35', 94750.00, 'Shipped', '5532712640', '2385003204', 'Paid'),
(273, '2025-01-08 20:24:20', 800.00, 'Shipped', '8597194991', '2385003204', 'Paid'),
(276, '2025-01-09 18:30:19', 820.00, 'Pending', '5402298369', '9665101122', 'Paid'),
(277, '2025-01-09 18:36:47', 150.00, 'Shipped', '4955970674', '3102687798', 'Paid'),
(278, '2025-01-09 18:39:15', 150.00, 'Shipped', '5402298369', '3102687798', 'Paid'),
(279, '2025-01-09 18:46:20', 18950.00, 'Shipped', '4955970674', '2385003204', 'Paid'),
(280, '2025-01-10 13:39:23', 549550.00, 'Pending', '5402298369', '2385003204', 'Not Paid'),
(281, '2025-01-11 21:09:31', 256500.00, 'Pending', '8597194991', '2385003204', 'Not Paid'),
(282, '2025-01-14 05:52:17', 23150.00, 'Shipped', '5402298369', '2385003204', 'Paid'),
(283, '2025-01-14 05:53:28', 23150.00, 'Shipped', '5402298369', '2385003204', 'Paid'),
(284, '2025-01-14 06:02:29', 23150.00, 'Shipped', '5402298369', '2385003204', 'Paid'),
(286, '2025-01-15 12:46:06', 449400.00, 'Shipped', '4955970674', '2385003204', 'Paid'),
(287, '2025-01-16 21:32:10', 285000.00, 'Shipped', '6174605338', '2385003204', 'Paid'),
(288, '2025-01-19 09:35:49', 890650.00, 'Shipped', '4955970674', '2385003204', 'Paid'),
(289, '2025-01-20 10:07:54', 51000.00, 'Pending', '1076656600', '2385003204', 'Not Paid'),
(290, '2025-01-23 11:59:26', 430000.00, 'Pending', '9597447671', '2385003204', 'Not Paid'),
(291, '2025-01-23 23:37:53', 126000.00, 'Shipped', '4955970674', '2385003204', 'Paid'),
(293, '2025-01-25 13:43:40', 120.00, 'Pending', '1501391992', '3102687798', 'Not Paid'),
(294, '2025-01-25 13:47:00', 120.00, 'Pending', '1501391992', '3102687798', 'Not Paid'),
(295, '2025-02-04 14:57:14', 285000.00, 'Pending', '4955970674', '2385003204', 'Not Paid');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Quantity` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `CustomerID` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Ordered_Size` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `item_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  `SellerID` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `Quantity`, `CustomerID`, `Ordered_Size`, `item_status`, `SellerID`) VALUES
(283, 259, '1662336566', '6', '4955970674', 'one size', 'Shipped', '2385003204'),
(284, 259, '5704682364', '4', '4955970674', 'one size', 'Shipped', '2385003204'),
(285, 260, '7841503275', '1', '1501391992', 'one size', 'Shipped', '2385003204'),
(286, 260, '3669299380', '1', '1501391992', 'one size', 'Shipped', '2385003204'),
(287, 260, '1634907497', '1', '1501391992', 'one size', 'Shipped', '2385003204'),
(288, 260, '1750280510', '1', '1501391992', 'one size', 'Shipped', '2385003204'),
(289, 260, '4383532743', '1', '1501391992', 'one size', 'Shipped', '2385003204'),
(290, 261, '7841503275', '7', '6174605338', 'one size', 'Shipped', '2385003204'),
(291, 262, '6519306264', '6', '4955970674', 'One Size', 'Shipped', '2385003204'),
(292, 262, '1725367490', '1', '4955970674', 'One Size', 'Shipped', '2385003204'),
(293, 263, '5704682364', '4', '4955970674', 'one size', 'Shipped', '2385003204'),
(294, 264, '3055878105', '5', '5532712640', 'one size', 'Shipped', '2385003204'),
(295, 264, '8626431926', '7', '5532712640', 'one size', 'Shipped', '2385003204'),
(297, 266, '5653727252', '13', '9531271320', 'one size', 'Shipped', '2385003204'),
(298, 267, '1662336566', '5', '3657808260', 'one size', 'Shipped', '2385003204'),
(299, 268, '6519306264', '1', '6174605338', 'One Size', 'Shipped', '2385003204'),
(300, 269, '7841503275', '25', '1501391992', 'one size', 'Shipped', '2385003204'),
(301, 270, '5704682364', '32', '4955970674', 'one size', 'Shipped', '2385003204'),
(302, 271, '1662336566', '80', '4955970674', 'one size', 'Shipped', '2385003204'),
(303, 272, '7841503275', '5', '5532712640', 'one size', 'Shipped', '2385003204'),
(304, 273, '6902418323', '1', '8597194991', 'one size', 'Shipped', '2385003204'),
(310, 276, '2031762751', '1', '5402298369', 'one size', 'Pending', '9665101122'),
(311, 276, '2150863808', '1', '5402298369', '44', 'Shipped', '3102687798'),
(312, 276, '1064291851', '3', '5402298369', 'one size', 'Pending', '7397340373'),
(313, 276, '1211441631', '4', '5402298369', 'one size', 'Pending', '3448383796'),
(314, 277, '2928947859', '1', '4955970674', 'S', 'Shipped', '3102687798'),
(315, 278, '2928947859', '1', '5402298369', 'S', 'Shipped', '3102687798'),
(316, 279, '7841503275', '1', '4955970674', 'one size', 'Shipped', '2385003204'),
(317, 280, '7841503275', '29', '5402298369', 'one size', 'Pending', '2385003204'),
(318, 281, '3055878105', '30', '8597194991', 'one size', 'Pending', '2385003204'),
(319, 282, '1662336566', '1', '5402298369', 'one size', 'Shipped', '2385003204'),
(320, 282, '7841503275', '1', '5402298369', 'one size', 'Shipped', '2385003204'),
(321, 283, '1662336566', '1', '5402298369', 'one size', 'Shipped', '2385003204'),
(322, 283, '7841503275', '1', '5402298369', 'one size', 'Shipped', '2385003204'),
(323, 284, '1662336566', '1', '5402298369', 'one size', 'Shipped', '2385003204'),
(324, 284, '7841503275', '1', '5402298369', 'one size', 'Shipped', '2385003204'),
(326, 286, '1662336566', '107', '4955970674', 'one size', 'Shipped', '2385003204'),
(327, 287, '5704682364', '50', '6174605338', 'one size', 'Shipped', '2385003204'),
(328, 288, '7841503275', '47', '4955970674', 'one size', 'Shipped', '2385003204'),
(329, 289, '5775818547', '15', '1076656600', 'one size', 'Pending', '2385003204'),
(330, 290, '3340402056', '100', '9597447671', 'one size', 'Pending', '2385003204'),
(331, 291, '1662336566', '30', '4955970674', 'one size', 'Shipped', '2385003204'),
(333, 293, '7419721774', '1', '1501391992', 'L', 'Pending', '3102687798'),
(334, 294, '7419721774', '1', '1501391992', 'L', 'Pending', '3102687798'),
(335, 295, '5704682364', '50', '4955970674', 'one size', 'Pending', '2385003204');

-- --------------------------------------------------------

--
-- Table structure for table `policy`
--

CREATE TABLE `policy` (
  `id` int NOT NULL,
  `privacy_security_policy` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `seller_policy` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `customer_policy` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `privacy_font` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `privacy_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `privacy_size` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `policy`
--

INSERT INTO `policy` (`id`, `privacy_security_policy`, `seller_policy`, `customer_policy`, `privacy_font`, `privacy_color`, `privacy_size`) VALUES
(1, '1.      Information Collection: We collect personal information (e.g., name, email, address, payment details) only for order processing and account management.\r\n\r\n2.	Use of Information: Collected information is used to process orders, communicate with customers, improve services, and prevent fraud.\r\n\r\n3.	Data Protection: We implement industry-standard security measures to protect personal information from unauthorized access, loss, or misuse.\r\n\r\n4.	Cookies Usage: Our website uses cookies to enhance user experience. Users can manage cookie preferences through their browser settings.\r\n\r\n5.	Third-Party Disclosure: We do not sell, trade, or otherwise transfer personal information to outside parties without customer consent, except for service providers necessary for order fulfillment.\r\n\r\n6.	Email Communication: Customers may receive emails regarding order confirmation, shipping updates, and promotional offers, with an option to unsubscribe.\r\n\r\n7.	User Rights: Customers have the right to access, correct, or delete their personal information stored on our platform.\r\n\r\n8.	Data Retention: We retain personal information only as long as necessary to fulfill the purposes for which it was collected.\r\n\r\n9.	Children’s Privacy: We do not knowingly collect personal information from children under 13. If we become aware of such data, we will delete it promptly.\r\n\r\n10.	Policy Changes: We may update this privacy policy periodically. Customers and Sellers will be notified of significant changes via email or through the website.\r\n\r\nNote: Smart E-commerce platform has the right to change the terms anytime without sending any previous notifications for users.', '1.	Account Registration: Sellers must create an account with accurate information, including business name, contact details, and valid payment information.\r\n\r\n2.	Product Listings: Sellers are responsible for creating and managing their product listings, ensuring that all information is accurate, including prices, descriptions, and images.\r\n\r\n3.     Product Details: Sellers must fill a clear description for their products. This will enhance its products to be displayed in recommendation system at higher possibility.\r\n\r\n4.	Compliance with Laws: Sellers must comply with all applicable laws regarding the sale of their products, including obtaining necessary licenses and permits.\r\n\r\n5.	Quality Assurance: Sellers are responsible for the quality and safety of the products they sell. Products must meet industry standards and regulations.\r\n\r\n6.	Pricing: Sellers must set competitive and fair prices for their products, without misleading promotions or false advertising.\r\n\r\n7.	Order Fulfillment: Sellers must fulfill orders in a timely manner, including proper packaging and shipping, and must provide accurate tracking information to customers.\r\n\r\n8.	Customer Communication: Sellers must maintain open communication with customers regarding their orders, addressing inquiries and concerns promptly.\r\n\r\n9.	Returns and Refunds: Sellers must have a clear return and refund policy, which should be communicated to customers prior to purchase.\r\n\r\n10.	Prohibited Items: Sellers are prohibited from listing items that are illegal, counterfeit, or infringe on intellectual property rights.\r\n\r\n11.	Termination of Account: The smart e-commerce platform reserves the right to suspend or terminate seller accounts for violations of policies, fraud, or misconduct.\r\n\r\nNote: Smart E-commerce platform has the right to change the terms anytime without sending any previous notifications for sellers.', '1.	Account Creation: Customers must create an account with accurate personal information to make purchases and track orders.\r\n\r\n2.	Age Requirement: Customers must be at least 18 years old or have parental consent to make purchases on the platform.\r\n\r\n3.	Payment Information: Customers must provide valid payment information and authorize the e-commerce site to charge their chosen payment method for purchases.\r\n\r\n4.	Product Reviews: Customers are encouraged to leave honest reviews of products, but must refrain from using offensive language or false information.\r\n\r\n5.	Privacy of Information: Customers must keep their account information secure and not share their login details with others.\r\n\r\n6.	Returns and Exchanges: Customers must adhere to the seller’s return and exchange policy and initiate requests within the specified time frame.\r\n\r\n7.	Responsible Usage: Customers must use the e-commerce platform responsibly, refraining from fraudulent activities, harassment, or abusive behavior towards sellers or staff.\r\n\r\n8.	Notification of Issues: Customers must promptly report any issues with their orders, including missing items, damages, or incorrect products.\r\n\r\n9.	Termination of Account: The smart e-commerce platform reserves the right to suspend or terminate customer accounts for violations of policies, fraud, or misconduct.\r\n\r\n10.	Compliance with Policies: By using the platform, customers agree to comply with all site policies and terms of service.\r\n\r\nNote: Smart E-commerce platform has the right to change the terms anytime without sending any previous notifications for customers.', 'Times New Roman', '#ff0000', 19);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `Product_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Product_Description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Product_price` decimal(10,2) DEFAULT NULL,
  `Product_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Product_Quantity` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Product_Size` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Product_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `SellerID` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_blocked` tinyint(1) DEFAULT '0',
  `Product_Remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`Product_id`, `Product_name`, `Product_Description`, `Product_price`, `Product_category`, `Product_Quantity`, `Product_Size`, `Product_image`, `SellerID`, `is_blocked`, `Product_Remarks`, `created_at`) VALUES
('1030889266', 'PUBG game controller', 'PUBG AK48 game controller', 40.00, 'toys', '339', 'one size', 'item1030889266img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('1064291851', 'Magnetic board', 'Magnetic board for children, A4 size, multiple colors', 10.00, 'toys', '562', 'one size', 'item1064291851img1.jpg', '7397340373', 0, '', '2024-12-08 18:21:16'),
('1138046228', 'Iphone case for  14 Pro Max ', 'Iphone 14 Pro Max case [ Anti-Slip Edge ] [ MIL-Grade Airbag Shockproof ], Translucent Hard Slim Protective', 65.00, 'electronics', '500', 'One Size', 'item1138046228img1.webp,item1138046228img2.png', '2385003204', 0, '', '2024-12-22 15:53:00'),
('1172746193', 'Flashlight ', 'Flashlight with battery', 10.00, 'toys', '556', 'one size', 'item1172746193img1.jpg', '7397340373', 0, '', '2024-12-08 18:21:16'),
('1174385862', 'lantern', 'Blue metal lantern', 25.00, 'homeAppliances', '5000', 'One Size', 'item1174385862img1.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('1188339878', 'Iphone Poetic case for iphone 15 ', 'Apple iPhone 15 Case with Ring Stand – Poetic Cases ', 80.00, 'electronics', '260', 'One Size', 'item1188339878img1.png', '2385003204', 0, '', '2024-12-22 16:58:33'),
('1191783680', 'JBL Wireless ', 'JBL Tune 125BT Wireless Headphones-white', 200.00, 'electronics', '421', 'One Size', 'item1191783680img1.jpg,item1191783680img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('1211441631', 'porcelain bowl', 'Oval porcelain bowl, 33*21*1.5', 15.00, 'homeAppliances', '463', 'one size', 'item1211441631img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('1317199699', 'Silver necklace ', 'Silver necklace with rose design', 30.00, 'accessories', '342', 'One Size', 'item1317199699img1.jpg,item1317199699img2.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('1467319450', 'Porcelain bowl ', 'Porcelain bowl 26*25*3 square', 20.00, 'homeAppliances', '457', 'one size', 'item1467319450img1.jpg,item1467319450img2.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('1584247751', 'Iphone 16 Pro Max', 'Iphone 16 Pro Max 256GB-white', 5700.00, 'electronics', '5000', 'one size', 'item1584247751img1.jpeg', '2385003204', 0, '', '2024-12-13 20:34:27'),
('1629269866', 'JBL Wave Beam', 'JBL Wave Beam True wireless earbuds-black', 480.00, 'electronics', '155', 'One Size', 'item1629269866img1.jpg,item1629269866img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('1634907497', 'Beko Dryer', 'Beko Dryer 8Kg, Heat Pump System Save Energy, 15 Programs, Dark Stainless.\r\n', 3050.00, 'homeAppliances', '226', 'one size', 'item1634907497img1.jpg,item1634907497img2.png', '2385003204', 0, '', '2024-12-13 22:45:36'),
('1662336566', 'Samsung Galaxy S24 Ultra', 'Samsung Galaxy S24 Ultra 256/12GB\r\n', 4200.00, 'electronics', '4260', 'one size', 'item1662336566img1.jpg,item1662336566img2.jpg,item1662336566img3.jpg', '2385003204', 0, '', '2024-12-13 18:51:56'),
('1679372340', 'Vans ', 'Vans Men\'s Classic Tank black', 110.00, 'clothes', '454', 'S,M,L,XL,XLL', 'item1679372340img1.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('1725367490', 'JBL Wired ', 'JBL C100SI Wired In Ear Headphones with Mic Black', 120.00, 'electronics', '99', 'One Size', 'item1725367490img1.jpg,item1725367490img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('1750280510', 'Beko Oven Free Standing Gas/Electric', 'Beko Oven Free Standing Gas/Electric 4 Burners, Size 60*60 Cm, Capacity 72 Ltr, Stainless Steel.\r\n', 2750.00, 'homeAppliances', '668', 'one size', 'item1750280510img1.jpg,item1750280510img2.png,item1750280510img3.png', '2385003204', 0, '', '2024-12-13 22:55:05'),
('1927399439', 'Adidas ULTRABOOST', 'Adidas Womens\' ULTRABOOST 22 W Shoes\r\n', 800.00, 'shoes', '343', '37,38', 'item1927399439img1.jpg,item1927399439img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('1938420404', 'Tous Les Jours', ' Tous Les Jours Perfume Day 58 For Men 55 Ml', 150.00, 'perfumes', '334', 'one size', 'item1938420404img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('1960149443', 'Fabric basket', 'Fabric basket for laundry and toys', 25.00, 'toys', '343', 'one size', 'item1960149443img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('1969424373', 'Original C-Data Lightning', 'Original C-Data Lightning to 3.5mm Audio Adapter', 140.00, 'electronics', '235', 'One Size', 'item1969424373img1.jpg,item1969424373img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('1976182758', 'Beko Built-In Electric Oven', 'Beko Built-In Electric Oven, 90 Cm, 127 Liter Capacity, 8 Programs, 3100 Watts, Black.\r\n', 3050.00, 'homeAppliances', '120', 'one size', 'item1976182758img1.jpg,item1976182758img2.png,item1976182758img3.png', '2385003204', 0, '', '2024-12-13 22:53:17'),
('2031762751', 'gold \nnecklace', 'Soft heart gold necklace', 30.00, 'accessories', '863', 'one size', 'item2031762751img1.jpg', '9665101122', 0, '', '2024-12-08 18:21:16'),
('2107225440', '	\nGold necklace', 'Gold necklace with letter K design', 30.00, 'accessories', '342', 'one size', 'item2107225440img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('2150863808', 'Adidas Ultra Boost', 'adidas Ultra Boost 1.0 DNA Shoes-Black', 700.00, 'shoes', '452', '43,44,45', 'item2150863808img1.jpg,item2150863808img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('2194712667', 'Large bowl ', 'Large round porcelain bowl 31*31*1.5', 20.00, 'homeAppliances', '221', 'One Size', 'item2194712667img1.jpg,item2194712667img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('2214132478', 'Swimming glasses', 'Primalite Swimming Goggles Silicone Anti-Fog, UV Protection for Adults Men\'s Womens Kids with Protection Case Kit- No Leaking Swim Glasses Professional Adjustable Strap Comfort fit- Aqua Black', 300.00, 'accessories', '100', 'One Size', 'item2214132478img1.jpg', '3102687798', 0, '', '2025-01-25 15:39:09'),
('2249001402', 'Beko Dishwasher 6 Programs', 'Beko Dishwasher 6 Programs, 15 Place Setting, 3 Racks, Stainless Steel.\r\n', 2700.00, 'homeAppliances', '232', 'one size', 'item2249001402img1.jpg', '2385003204', 0, '', '2024-12-13 22:40:32'),
('2257164084', 'Hi-Tec Mens', 'Hi-Tec Mens\' Monar Full Zip Fleece Jacket', 200.00, 'clothes', '454', 'S,M,L,XL,XLL', 'item2257164084img1.jpg,item2257164084img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('2270254462', 'LG Refrigerator ', 'LG Refrigerator Capacity 547 Ltr, Inverter Linear Compressor Save Energy, Silver Color', 4550.00, 'homeAppliances', '213', 'one size', 'item2270254462img1.jpg', '2385003204', 0, '', '2024-12-13 21:10:40'),
('2281517450', 'Jori Perfumes', 'Jori EDP By Jori Perfumes For Women 90ML', 70.00, 'perfumes', '454', 'one size', 'item2281517450img1.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('2313307521', 'Reebok Royal', 'Reebok Royal Classic Jog 3 Shoes\r\n', 140.00, 'shoes', '567', '21.5', 'item2313307521img1.jpg,item2313307521img2.jpg', '7877845688', 0, '', '2024-12-08 18:21:16'),
('2387534679', 'Merrell Men', 'Merrell Men\'s Wildwood Aerosport Shoes\r\n', 360.00, 'shoes', '343', '43,44,45', 'item2387534679img1.jpg,item2387534679img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('2441555403', 'Samsung Galaxy A04e ', 'Samsung Galaxy A04e 64GB & 4GB RAM', 500.00, 'electronics', '232', 'one size', 'item2441555403img1.jpg', '2385003204', 0, '', '2024-12-13 18:57:30'),
('2548871106', 'Adidas Alphaboost ', 'adidas Mens\' Alphaboost V2 Shoes - Black', 550.00, 'shoes', '565', '43,44,45', 'item2548871106img1.jpg,item2548871106img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('2555273293', 'Beko Dishwasher 6 Programs', 'Beko Dishwasher 6 Programs, 15 Place Setting, Inverter Brushless Motor, 3 Racks, Dark Stainless.\r\n', 3050.00, 'homeAppliances', '230', 'one size', 'item2555273293img1.jpg', '2385003204', 0, '', '2024-12-13 22:41:25'),
('2579541322', 'Iphone 14 Pro Max', 'Iphone 14 Pro Max 1T-gold\r\n', 4550.00, 'electronics', '431', 'one size', 'item2579541322img1.jpg', '2385003204', 0, '', '2024-12-13 20:31:32'),
('2618420366', 'Hi-Tec Mens\'', 'Hi-Tec Mens\' Caen Jacket black', 280.00, 'clothes', '454', 'S, M, L, XL, XXL, XXXL', 'item2618420366img1.jpg,item2618420366img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('2651909489', 'Beko Refrigerator 4 Door Black ', 'Beko Refrigerator 4 Door Capacity 580 Ltr, Inverter Compressor Save Energy, Black Glass.\r\n', 8550.00, 'homeAppliances', '31', 'one size', 'item2651909489img1.jpg,item2651909489img2.jpg', '2385003204', 0, '', '2024-12-13 22:49:53'),
('2674205586', 'Adidas Alphaboost ', 'adidas Mens\' Alphaboost V2 Shoes - White', 550.00, 'shoes', '444', '43,44,45', 'item2674205586img1.jpg,item2674205586img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('2733338330', 'LG Microwave', 'LG Microwave 25 Liter, 1150W, Smart Inverter, Even Heating and Easy Clean, Black Color.\r\n', 800.00, 'homeAppliances', '223', 'one size', 'item2733338330img1.jpg,item2733338330img2.jpg,item2733338330img3.jpg', '2385003204', 0, '', '2024-12-13 21:32:30'),
('2779556480', 'Reebok Lite 3', 'Reebok Women\'s Lite 3 Shoes', 220.00, 'shoes', '468', '37.5', 'item2779556480img1.jpg,item2779556480img2.jpg', '7877845688', 0, '', '2024-12-08 18:21:16'),
('2786665298', 'Hi-Tec Mens', 'Hi-Tec Mens\' Caen Jacket', 280.00, 'clothes', '343', 'S, M, L, XL, XXL, XXXL', 'item2786665298img1.jpg,item2786665298img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('2823140540', 'Basket ', 'Basket with heavy pewter tissue box', 180.00, 'homeAppliances', '243', 'One Size', 'item2823140540img1.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('2928947859', 'Diadora', 'Diadora MENS CTN SHIRT FASHION', 150.00, 'clothes', '3451', 'S, M, L, XL, XXL, XXXL', 'item2928947859img1.jpg,item2928947859img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('2935925458', 'Foam ball', 'Foam ball, diameter 10 cm, designed in the shape of a basketball', 10.00, 'toys', '684', 'one size', 'item2935925458img1.jpg', '7397340373', 0, '', '2024-12-08 18:21:16'),
('2942367768', 'PUBG Games presses', 'Luxurious double PUBG game consoles', 50.00, 'toys', '454', 'one size', 'item2942367768img1.jpg', '4768279420', 1, '', '2024-12-08 18:21:16'),
('3005461530', 'JBL Tune 520BT ', 'JBL Tune 520BT Wireless Headphones black', 440.00, 'electronics', '123', 'One Size', 'item3005461530img1.jpg,item3005461530img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('3006512062', 'Gold chain ', 'Gold chain with letter O design', 30.00, 'accessories', '234', 'One Size', 'item3006512062img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('3055878105', 'Beko Refrigerator 4 Door White ', 'Beko Refrigerator 4 Door Capacity 580 Ltr, Inverter Compressor Save Energy, White Glass.\r\n', 8550.00, 'homeAppliances', '300', 'one size', 'item3055878105img1.jpg,item3055878105img2.jpg', '2385003204', 0, '', '2024-12-13 22:50:58'),
('3102628197', 'Reebok Floatride  Shoes', 'Reebok Womens\' Floatride Energy 5 Shoes', 480.00, 'shoes', '100', ' 36 37 37.5 38 38.5 40 40.5', 'item3102628197img1.jpg,item3102628197img2.jpg', '7877845688', 0, '', '2024-12-08 18:21:16'),
('3190832992', 'iPhone 15 ', 'Apple iPhone 15 (128GB)-black\r\n', 3400.00, 'electronics', '220', 'one size', 'item3190832992img1.jpg', '2385003204', 0, '', '2024-12-13 20:40:59'),
('3207774201', 'cups', 'Set of 6 Nescafe/Cappuccino cups', 50.00, 'homeAppliances', '454', 'one size', 'item3207774201img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('3224214205', 'Samsung Galaxy A25', 'Samsung Galaxy A25 5G 8/256GB', 1100.00, 'electronics', '142', 'one size', 'item3224214205img1.jpg,item3224214205img2.jpg,item3224214205img3.jpg', '2385003204', 0, '', '2024-12-13 18:50:20'),
('3280429000', 'wireless mouse', 'Bluetooth wireless mouse, type C charging, green color', 120.00, 'electronics', '515', 'one size', 'item3280429000img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('3297536098', 'iPhone 11', 'Apple iPhone 11 128GB-black', 2770.00, 'electronics', '452', 'one size', 'item3297536098img1.jpg', '2385003204', 0, '', '2024-12-13 20:37:06'),
('3325080345', 'Khalis Perfume', ' MARIAM Perfumes By Khalis for Women 100 ML\r\n\r\n', 80.00, 'perfumes', '334', 'one size', 'item3325080345img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('3340402056', 'IPHONE 14 Pro Max', 'IPHONE 14 Pro Max 1TB\r\n', 4300.00, 'electronics', '1100', 'one size', 'item3340402056img1.jpg', '2385003204', 0, '', '2024-12-13 20:30:24'),
('3353883941', 'medallion', 'Gold-colored beaded medallion', 15.00, 'toys', '468', 'one size', 'item3353883941img1.jpg,item3353883941img2.jpg', '7397340373', 0, '', '2024-12-08 18:21:16'),
('3355131491', 'Silver necklace', 'Silver necklace with my mother\'s design, white color', 30.00, 'accessories', '876', 'one size', 'item3355131491img1.jpg', '9665101122', 0, '', '2024-12-08 18:21:16'),
('3370572635', 'Silver earring ', 'Silver teardrop earring with small white zircon', 30.00, 'accessories', '454', 'one size', 'item3370572635img1.jpg', '9665101122', 0, '', '2024-12-08 18:21:16'),
('3483461988', 'Tous Les Jours', 'Beauty Musk Perfume By Tous Les Jours For Women 6 Ml', 150.00, 'perfumes', '243', 'one size', 'item3483461988img1.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('3502509397', 'Gold chain ', 'Gold chain with letter D design', 30.00, 'accessories', '454', 'one size', 'item3502509397img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('3534650245', 'adidas Womens NMD_V3 Shoes', 'adidas Womens\' NMD_V3 Shoes - Black', 600.00, 'shoes', '898', ' 36.7 37.3 38.7', 'item3534650245img1.jpg,item3534650245img2.jpg', '7877845688', 0, '', '2024-12-08 18:21:16'),
('3548115760', 'necklace', 'Silver necklace with the design “You are in God’s protection and in my heart I am” in blue color', 30.00, 'accessories', '333', 'One Size', 'item3548115760img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('3579562077', 'Prodrive Magnet', 'Prodrive Magnet Holder Air Vent Car Stand black', 40.00, 'electronics', '288', 'One Size', 'item3579562077img1.jpg,item3579562077img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('3651273526', 'Fresh ', ' Fresh Cat Pet Perfume', 20.00, 'perfumes', '343', 'one size', 'item3651273526img1.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('3669299380', 'Beko Dishwasher 5 Programs', 'Beko Dishwasher 5 Programs, 13 Place Setting, 2 Racks, Stainless Steel.\r\n', 1900.00, 'homeAppliances', '33', 'one size', 'item3669299380img1.jpg,item3669299380img2.png,item3669299380img3.png', '2385003204', 0, '', '2024-12-13 22:39:35'),
('3669716228', 'Silicon Power', 'Silicon Power SSD 3D NAND A55 SLC Cache Performance Boost SATA III 1T\r\n', 600.00, 'electronics', '100', 'One Size', 'item3669716228img1.jpg,item3669716228img2.jpg,item3669716228img3.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('3702030159', 'Tous Les Jours', 'LITTLE By Tous Les Jours Perfume For Kids 30 Ml', 80.00, 'perfumes', '565', 'one size', 'item3702030159img1.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('3710735333', 'necklace ', 'Silver necklace with love is power design, white color', 30.00, 'accessories', '787', 'one size', 'item3710735333img1.jpg', '9665101122', 0, '', '2024-12-08 18:21:16'),
('3716247138', ' Charging cable', 'Charging cable with 3 micro ports for iPhone, type C', 60.00, 'electronics', '467', 'one size', 'item3716247138img1.jpg,item3716247138img2.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('3755319294', 'Green lantern', 'Green metal lantern', 25.00, 'homeAppliances', '222', 'One Size', 'item3755319294img1.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('3800354364', 'Adidas SolarBoost  ', '- adidas Women\'s SolarBoost 3 Shoes - Black', 649.00, 'shoes', '454', '43,44,45', 'item3800354364img1.jpg,item3800354364img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('3801642449', 'cups', 'Set of 6 tea cups', 30.00, 'homeAppliances', '455', 'one size', 'item3801642449img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('3813884447', 'Penguin cloth toy', 'Penguin cloth toy, large size, yellow', 50.00, 'toys', '342', 'one size', 'item3813884447img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('3859083646', 'Gold chain ', 'Gold chain with letter W design', 30.00, 'accessories', '343', 'one size', 'item3859083646img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('3877196665', 'Tous Les Jours ', 'Rose Musk Perfume By Tous Les Jours For Women 6 Ml', 150.00, 'perfumes', '344', 'one size', 'item3877196665img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('3923769451', 'Faan Perfumes', 'Thanaghum EDP By Faan Perfumes for Women 100 ML', 150.00, 'perfumes', '456', 'one size', 'item3923769451img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('4013463675', 'Tous Les Jours', 'HERO By Tous Les Jours Perfume For Kids 30 Ml', 80.00, 'perfumes', '686', 'one size', 'item4013463675img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('4023459664', 'LG Washer & Dryer (Wash Tower)', 'LG Washer & Dryer (Wash Tower) 12Kg Wash / 10Kg Dry, 13 Programs, Inverter Direct Drive / DUAL Inverter Heat Pump, Black.\r\n', 11850.00, 'homeAppliances', '110', 'one size', 'item4023459664img1.png,item4023459664img2.png,item4023459664img3.png', '2385003204', 0, '', '2024-12-13 21:36:47'),
('4043271838', 'toy box', 'Rectangular toy box', 80.00, 'toys', '454', 'one size', 'item4043271838img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('4115590808', 'Beko Washer ', 'Beko Washer Capacity 10 Kg, 15 Programs, Inverter Brushless Motor Save Energy, Black.\r\n', 2900.00, 'homeAppliances', '231', 'one size', 'item4115590808img1.jpg,item4115590808img2.png,item4115590808img3.png', '2385003204', 0, '', '2024-12-13 22:44:14'),
('4261943139', 'Tous Les Jours', 'SWEETY By Tous Les Jours Perfume For Kids 30 Ml', 80.00, 'perfumes', '345', 'one size', 'item4261943139img1.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('4278973935', 'Apple USB-C Power Adapter', 'Apple USB-C Power Adapter 20W White', 220.00, 'electronics', '230', 'One Size', 'item4278973935img1.jpg,item4278973935img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('4324811686', 'Samsung In-Ear Headset', 'Samsung In-Ear Headset 3.5 mm Black', 60.00, 'electronics', '465', 'One Size', 'item4324811686img1.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('4332920884', 'sunglasses ', 'Black cat-shaped sunglasses for women', 30.00, 'accessories', '4900', 'one size', 'item4332920884img1.jpg,item4332920884img2.jpg', '9665101122', 0, '', '2024-12-08 18:21:16'),
('4383532743', 'LG Washer', 'LG Washer Capacity 10.5 kg, 14 Programs, Inverter Direct Drive AI Motor, Dark Stainless.\r\n', 3490.00, 'homeAppliances', '233', 'one size', 'item4383532743img1.jpg,item4383532743img2.png', '2385003204', 0, '', '2024-12-13 21:29:13'),
('4436171476', 'Adidas Osade', ' adidas Mens\' Osade Shoes - White', 400.00, 'shoes', '543', '40,41', 'item4436171476img1.jpg,item4436171476img2.jpg', '7877845688', 0, '', '2024-12-08 18:21:16'),
('4439498724', 'memory card', '64G memory card', 65.00, 'electronics', '468', 'one size', 'item4439498724img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('4492297327', 'Lattafa Perfumes', ' Ayaam EDP By Lattafa Perfumes 100ml', 80.00, 'perfumes', '354', 'one size', 'item4492297327img1.jpg,item4492297327img2.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('4551537241', 'Tous Les Jours', 'Tous Les Jours Perfume Day 7 For Men 55 Ml', 150.00, 'perfumes', '343', 'one size', 'item4551537241img1.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('4561134815', 'adidas Womens\' ', 'adidas Womens\' X9000L3 Shoes - Purple\r\n', 550.00, 'shoes', '333', '45,44', 'item4561134815img1.jpg', '7877845688', 0, '', '2024-12-08 18:21:16'),
('4686468367', 'LG Microwave & Grill', 'LG Microwave & Grill 42 Liter, 1100W , Smart Inverter, Even Heating and Easy Clean, Black Color.\r\n', 900.00, 'homeAppliances', '231', 'one size', 'item4686468367img1.jpg,item4686468367img2.jpg', '2385003204', 0, '', '2024-12-13 21:34:08'),
('4713464345', 'lantern', 'Large red metal lantern', 30.00, 'homeAppliances', '465', 'One Size', 'item4713464345img1.jpg,item4713464345img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('4840229610', 'game camera', 'Rechargeable game camera', 70.00, 'toys', '454', 'one size', 'item4840229610img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('4854573552', 'Beko Washer', 'Beko Washer Capacity 12 Kg, 15 Programs, Inverter Brushless Motor Save Energy, Dark Stainless.\r\n', 3400.00, 'homeAppliances', '121', 'one size', 'item4854573552img1.jpg', '2385003204', 0, '', '2024-12-13 22:42:38'),
('4904342921', ' Lattafa Perfumes', 'Musamam EDP By Lattafa Perfumes For Unisex 100ml', 100.00, 'perfumes', '455', 'one size', 'item4904342921img1.jpg,item4904342921img2.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('4928960622', 'Emeer', 'Emeer By Lattafa Perfumes Unisex 100ml', 180.00, 'perfumes', '283', 'one size', 'item4928960622img1.jpg,item4928960622img2.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('4955499444', 'Iphone MagSafe case for 16 pro max ', 'Bare Case - Thinnest MagSafe Case for iPhone 16 Pro Max', 80.00, 'electronics', '200', 'One Size', 'item4955499444img1.png,item4955499444img2.png', '2385003204', 0, '', '2024-12-22 16:56:22'),
('4959781076', 'Adidas Unisex Climacool', 'adidas Unisex Climacool BOOST Shoes - Black', 600.00, 'shoes', '444', '37,38', 'item4959781076img1.jpg,item4959781076img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('4964955321', 'Wall Clock', 'First Time Wall Clock\r\n', 65.00, 'homeAppliances', '234', 'One Size', 'item4964955321img1.jpg,item4964955321img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('5027350826', '9PRO Super Speed', ' 9PRO Super Speed Cable Type C to Type C', 50.00, 'electronics', '199', 'One Size', 'item5027350826img1.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('5226229924', 'beach ball ', 'Inflatable beach ball orange and white color', 7.00, 'toys', '986', 'one size', 'item5226229924img1.jpg', '7397340373', 0, '', '2024-12-08 18:21:16'),
('5305477301', 'Vans ', 'Vans Men\'s Classic Tank', 110.00, 'clothes', '242', 'S,M,L,XL,XLL', 'item5305477301img1.jpg,item5305477301img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('5325063427', 'children\'s pool', 'Colorful inflatable children\'s pool, 25*86 cm, Intex', 50.00, 'toys', '454', 'one size', 'item5325063427img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('5331645212', 'Porcelain bowl', 'Black oval porcelain bowl', 20.00, 'homeAppliances', '123', 'One Size', 'item5331645212img1.jpg,item5331645212img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('5332967231', 'Truck Toys', 'Dinosaur Transport Truck Toys– 4 PCS', 40.00, 'toys', '578', 'one size', 'item5332967231img1.jpg', '7397340373', 0, '', '2024-12-08 18:21:16'),
('5351900740', ' wireless mouse', 'Bluetooth wireless mouse, type C charging, pink color', 120.00, 'electronics', '577', 'one size', 'item5351900740img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('5384469869', 'Reebok Mens\'', 'Reebok Mens\' LX2200 Casual Shoes white\r\n', 380.00, 'shoes', '455', '44,45', 'item5384469869img1.jpg,item5384469869img2.jpg', '7877845688', 0, '', '2024-12-08 18:21:16'),
('5388588231', 'wastebasket', 'Round straw wastebasket, diameter 28 cm, burnt brown colour', 25.00, 'homeAppliances', '755', 'one size', 'item5388588231img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('5401320432', 'iPhone 11', 'Apple iPhone 11 128GB-white', 2770.00, 'electronics', '854', 'one size', 'item5401320432img1.jpg', '2385003204', 0, '', '2024-12-13 20:38:16'),
('5445579173', 'Wireless Microphone', 'WESTER Wireless Microphone Hifi Speaker', 100.00, 'electronics', '797', 'one size', 'item5445579173img1.jpg,item5445579173img2.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('5457579469', 'Hermes ', 'Hermes By LISA Perfume 80ML', 150.00, 'perfumes', '340', 'One Size', 'item5457579469img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('5588015803', 'Iphone OtterBox case for iphone 11', 'Iphone 11 case OtterBox', 100.00, 'electronics', '97', 'One Size', 'item5588015803img1.webp,item5588015803img2.png', '2385003204', 0, '', '2024-12-22 15:55:50'),
('5593229917', 'Gold necklace ', 'Gold necklace with letter L design', 30.00, 'accessories', '737', 'One Size', 'item5593229917img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('5633569976', 'light hour', 'Squid light hour', 10.00, 'toys', '678', 'one size', 'item5633569976img1.jpg', '7397340373', 0, '', '2024-12-08 18:21:16'),
('5646737856', 'Samsung S23 Ultra Case', 'Samsung Galaxy S23 Ultra Case', 50.00, 'electronics', '200', 'One Size', 'item5646737856img1.png', '2385003204', 0, '', '2024-12-22 17:02:47'),
('5653727252', 'Samsung Galaxy S23 Ultra', 'Samsung Galaxy S23 Ultra 256GB & 12GB RAM', 3900.00, 'electronics', '230', 'one size', 'item5653727252img1.jpg,item5653727252img2.jpg,item5653727252img3.jpg', '2385003204', 0, '', '2024-12-13 18:47:40'),
('5656508907', 'Porcelain bowl', 'Porcelain bowl 10.5*10.5*5', 6.00, 'homeAppliances', '897', 'one size', 'item5656508907img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('5664847993', 'LG Refrigerator ', 'LG Refrigerator Capacity 493 Ltr, Inverter Linear Compressor Save Energy, Silver Color.\r\n', 4250.00, 'homeAppliances', '211', 'one size', 'item5664847993img1.jpg,item5664847993img2.png', '2385003204', 0, '', '2024-12-13 21:20:51'),
('5670981870', 'Faan Perfumes', 'Shaghaf EDP By Faan Perfumes for Men 100 ML', 80.00, 'perfumes', '344', 'one size', 'item5670981870img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('5704682364', 'Iphone 16 Pro Max', 'Iphone 16 Pro Max 256GB-gold', 5700.00, 'electronics', '4400', 'one size', 'item5704682364img1.jpg', '2385003204', 0, '', '2024-12-13 20:35:33'),
('5740267269', 'bracelet', 'Silver bracelet with sun design', 30.00, 'accessories', '545', 'One Size', 'item5740267269img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('5761530329', '9PRO 2.4A', 'Charging Cable UBC to Iphone By 9PRO 2.4A black', 40.00, 'electronics', '567', 'one size', 'item5761530329img1.jpg,item5761530329img2.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('5775818547', 'iPhone 15 ', 'Apple iPhone 15 (128GB)-white\r\n', 3400.00, 'electronics', '100', 'one size', 'item5775818547img1.jpg', '2385003204', 0, '', '2024-12-13 20:42:01'),
('5863897380', 'glass cup', 'Heart head double glass cup 250 ml', 30.00, 'homeAppliances', '143', 'One Size', 'item5863897380img1.jpg,item5863897380img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('5869088999', 'JBL Wired ', 'JBL C100 Wired In Ear Headphones with Mic Red', 120.00, 'electronics', '200', 'One Size', 'item5869088999img1.jpg,item5869088999img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('5914449770', 'Adidas Pureboost', 'adidas Mens\' Pureboost 22 Shoes - Black', 600.00, 'shoes', '454', '43,44,45', 'item5914449770img1.jpg,item5914449770img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('5919628995', 'JBL Wired ', 'JBL C100 Wired In Ear Headphones with Mic White', 120.00, 'electronics', '150', 'One Size', 'item5919628995img1.jpg,item5919628995img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('5921961265', 'JBL Wave Flex', 'JBL Wave Flex True wireless earbuds', 480.00, 'electronics', '234', 'One Size', 'item5921961265img1.jpg,item5921961265img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('5934625459', 'Adidas Pureboost ', 'adidas Pureboost 22 Shoes - Black', 600.00, 'shoes', '454', '43,44,45', 'item5934625459img1.jpg,item5934625459img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('6058739088', 'LISA Perfume', '212Heroes By LISA Perfume 800ML', 150.00, 'perfumes', '445', 'one size', 'item6058739088img1.jpg,item6058739088img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('6065326971', 'Balloon ', 'Multicolour Latex Balloon - 100 Pieces', 15.00, 'toys', '567', 'one size', 'item6065326971img1.jpg,item6065326971img2.jpg', '7397340373', 0, '', '2024-12-08 18:21:16'),
('6081353538', 'Reebok', 'Reebok UNISEX GS DATA FITNESS T-Shirt', 180.00, 'clothes', '449', 'S,M,L,XL,XLL', 'item6081353538img1.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('6117549566', 'sunglasses ', 'Black sunglasses with small lenses', 25.00, 'accessories', '243', 'One Size', 'item6117549566img1.jpg,item6117549566img2.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('6132001926', ' Foldable toy ', 'Foldable toy box for children with 30 balls', 280.00, 'toys', '343', 'one size', 'item6132001926img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('6164849443', 'squares game', 'Wooden squares game with bag', 7.00, 'toys', '567', 'one size', 'item6164849443img1.jpg,item6164849443img2.jpg', '7397340373', 0, '', '2024-12-08 18:21:16'),
('6212905386', 'JBL Wave Flex', 'JBL Wave Flex True wireless earbuds black', 480.00, 'electronics', '234', 'One Size', 'item6212905386img1.jpg,item6212905386img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('6252432693', 'wall clock', 'Silver ship wall clock', 65.00, 'homeAppliances', '345', 'One Size', 'item6252432693img1.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('6254917598', 'Golden chain ', 'Golden chain with letter B design\r\n', 30.00, 'accessories', '454', 'one size', 'item6254917598img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('6275957543', 'memory card', '16G memory card', 40.00, 'electronics', '567', 'one size', 'item6275957543img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('6386762007', 'Sandisk Cruzer Blade', 'Sandisk Cruzer Blade USB 2.0 Flash Drive', 60.00, 'electronics', '356', 'One Size', 'item6386762007img1.jpg,item6386762007img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('6391320073', 'Faan Perfumes ', 'Khawater EDP By Faan Perfumes for Women 100 ML', 80.00, 'perfumes', '576', 'one size', 'item6391320073img1.jpg,item6391320073img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('6401647649', 'Tous Les Jours', ' Tous Les Jours Perfume Day 188 For Men 55 Ml', 150.00, 'perfumes', '454', 'one size', 'item6401647649img1.jpg,item6401647649img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('6481769767', 'Adidas X_PLRBOOST', 'adidas Mens\' X_PLRBOOST Shoes - Black', 700.00, 'shoes', '454', '43,44,45', 'item6481769767img1.jpg,item6481769767img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('6509051759', 'Ladder and snake game', 'Ladder and snake game, size 24.5 x 24.5 cm', 5.00, 'toys', '588', 'one size', 'item6509051759img1.jpg', '7397340373', 0, '', '2024-12-08 18:21:16'),
('6519306264', 'Samsung S24 Ultra Case', 'Samsung Galaxy S24 Ultra Case with Screen Protector', 120.00, 'electronics', '4043', 'One Size', 'item6519306264img1.jpg,item6519306264img2.jpg', '2385003204', 0, '', '2024-12-22 17:01:00'),
('6545219928', 'Iphone 14 Pro Max', 'Iphone 14 Pro Max 1T-black\r\n', 4550.00, 'electronics', '321', 'one size', 'item6545219928img1.jpg', '2385003204', 0, '', '2024-12-13 20:29:25'),
('6548336401', 'MARVO Headphone', 'MARVO Headphone adapter', 40.00, 'electronics', '455', 'one size', 'item6548336401img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('6730746694', 'chain ', 'Gold chain with letter G design', 30.00, 'accessories', '435', 'one size', 'item6730746694img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('6743660171', 'Lattafa Perfumes', 'Maahir EDP By Lattafa Perfumes For Unisex 100ml', 100.00, 'perfumes', '345', 'one size', 'item6743660171img1.jpg,item6743660171img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('6753668485', 'Samsung Galaxy A06', 'Samsung Galaxy A06 6/128GB', 650.00, 'electronics', '342', 'one size', 'item6753668485img1.jpg,item6753668485img2.jpg,item6753668485img3.jpg', '2385003204', 0, '', '2024-12-13 19:01:18'),
('6840870871', 'bracelet', 'Double layer silver bracelet', 30.00, 'accessories', '432', 'one size', 'item6840870871img1.jpg', '9665101122', 0, '', '2024-12-08 18:21:16'),
('6872205299', 'chain ', 'Gold chain with rose design', 30.00, 'accessories', '223', 'One Size', 'item6872205299img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('6893439153', 'lanterns', 'Set of 3 silver lanterns', 250.00, 'homeAppliances', '454', 'one size', 'item6893439153img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('6902418323', 'LG Vacuum Cleaner', 'LG Vacuum Cleaner Canister 2000W for Section Power 380AW, Silver Color.\r\n', 800.00, 'homeAppliances', '452', 'one size', 'item6902418323img1.jpg,item6902418323img2.jpg,item6902418323img3.jpg', '2385003204', 0, '', '2024-12-13 21:38:40'),
('6907754309', 'Samsung Galaxy A55', 'Samsung Galaxy A55 8/256GB\r\n', 1600.00, 'electronics', '232', 'one size', 'item6907754309img1.jpg,item6907754309img2.jpg,item6907754309img3.jpg', '2385003204', 0, '', '2024-12-13 19:02:59'),
('6910374254', 'Adidas ZX 1K Boost', 'adidas ZX 1K Boost Shoes', 500.00, 'shoes', '545', '43,44,45', 'item6910374254img1.jpg,item6910374254img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('6920121291', 'DISK ON KEY', 'DISK ON KEY 128GB USB 3.0 SANDISK', 200.00, 'electronics', '122', 'One Size', 'item6920121291img1.jpg,item6920121291img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('6939442133', 'MOXPLUG HDMI', 'MOXPLUG HDMI to Mini HDMI M/M cable 1.5 m\r\n', 24.00, 'electronics', '200', 'One Size', 'item6939442133img1.jpg,item6939442133img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('6962243867', 'Wall clock', 'Wall clock measuring 46 cm', 65.00, 'homeAppliances', '125', 'One Size', 'item6962243867img1.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('6974902244', 'PUBG game controller', 'Iron paws with blue corset', 50.00, 'toys', '343', 'one size', 'item6974902244img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('7100391619', 'Miriam Marvel', 'Miriam Marvel Reves Perfume 75 ML EDP For Women\r\n', 150.00, 'perfumes', '636', 'One Size', 'item7100391619img1.jpg,item7100391619img2.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('7141049390', 'necklace ', 'Gold necklace with letter R design\r\n', 30.00, 'accessories', '454', 'one size', 'item7141049390img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('7142153268', 'Under Armour Men\'s', 'Under Armour Men\'s HOVR Sonic 6 Running Shoes', 700.00, 'shoes', '798', '45', 'item7142153268img1.jpg', '7877845688', 0, '', '2024-12-08 18:21:16'),
('7162700809', 'LG Washer', 'LG Washer Capacity 9 kg, 14 Programs, Inverter Direct Drive AI Motor, Dark Stainless.\r\n', 3150.00, 'homeAppliances', '121', 'one size', 'item7162700809img1.jpg', '2385003204', 0, '', '2024-12-13 21:27:49'),
('7172309727', 'Lattafa Perfumes', 'Ser Hubbee EDP By Lattafa Perfumes For Women 100ml', 70.00, 'perfumes', '879', 'one size', 'item7172309727img1.jpg,item7172309727img2.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('7219070906', 'Rubik\'s cube', 'Rubik\'s cube', 15.00, 'toys', '566', 'one size', 'item7219070906img1.jpg,item7219070906img2.jpg', '7397340373', 0, '', '2024-12-08 18:21:16'),
('7278964232', 'Galaxy S24 Ultra', 'Galaxy S24 Ultra 12/256GB\r\n', 4000.00, 'electronics', '111', 'one size', 'item7278964232img1.jpg', '2385003204', 0, '', '2024-12-13 19:06:06'),
('7381582655', 'flash memory', 'DISKONKEY 64G Smart Blade flash memory', 70.00, 'electronics', '686', 'one size', 'item7381582655img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('7411591986', 'Under Armour ', 'Under Armour Men\'s UA Vanish Woven 2-in-1 Vent Shorts\r\n', 300.00, 'clothes', '454', 'S,M,L,XL,XLL', 'item7411591986img1.jpg,item7411591986img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('7418584867', 'Iphone 13 Pro Max', 'Iphone 13 Pro Max 256GB\r\n', 4000.00, 'electronics', '343', 'one size', 'item7418584867img1.jpg,item7418584867img2.jpg', '2385003204', 0, '', '2024-12-13 20:24:17'),
('7419721774', 'Swimming clothes for mens', 'Men\'s Wear Swim-Full outfit for Men\'s Wear Swim', 120.00, 'clothes', '398', 'L, XL, XXL, XXXL', 'item7419721774img1.png', '3102687798', 0, NULL, '2025-01-25 15:34:08'),
('7457144248', 'game camera', 'Rechargeable game camera', 70.00, 'toys', '450', 'one size', 'item7457144248img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('7543323440', 'Golden chain ', 'Golden chain with letter S design', 30.00, 'accessories', '234', 'One Size', 'item7543323440img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('7584942654', 'Reebok Classic ', 'Reebok Classic Leather Shoes - Grade School', 300.00, 'shoes', '674', ' 36, 36.5, 37 ,38', 'item7584942654img1.jpg,item7584942654img2.jpg', '7877845688', 0, '', '2024-12-08 18:21:16'),
('7637448406', 'Adidas Unisex\' ZX ', 'adidas Unisex\' ZX 1K Boost Shoes - White', 500.00, 'shoes', '454', '43,44,45', 'item7637448406img1.jpg,item7637448406img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('7695914958', 'porcelain bowl', 'Large oval porcelain bowl, 31*26*2', 20.00, 'homeAppliances', '435', 'one size', 'item7695914958img1.jpg,item7695914958img2.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('7698959678', 'LG Refrigerator', 'LG Refrigerator Capacity 423 Ltr, Inverter Linear Compressor Save Energy, Silver Color.\r\n', 3800.00, 'homeAppliances', '1332', 'one size', 'item7698959678img1.jpg,item7698959678img2.png,item7698959678img3.png', '2385003204', 0, '', '2024-12-13 21:18:13'),
('7731413047', 'iPhone 11', 'Apple iPhone 11 128GB-green', 2770.00, 'electronics', '340', 'one size', 'item7731413047img1.webp', '2385003204', 0, '', '2024-12-13 20:39:39'),
('7752185155', 'plate ', 'Round black porcelain plate 20*20*3', 15.00, 'homeAppliances', '454', 'one size', 'item7752185155img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('7841503275', 'LG Refrigerator InstaView ', 'LG Refrigerator InstaView 4 Door Capacity 847 Ltr, Inverter Compressor Save Energy, Black Stainless.\r\n', 18950.00, 'homeAppliances', '200', 'one size', 'item7841503275img1.jpg,item7841503275img2.jpg,item7841503275img3.jpg', '2385003204', 0, '', '2024-12-13 21:12:56'),
('7844827851', 'necklace ', 'Silver necklace with the design “I entrust you to God whose deposits are not lost”, white colour', 30.00, 'accessories', '343', 'One Size', 'item7844827851img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('7851135296', 'Adidas Originals ', 'adidas Originals Superstar Shoes', 400.00, 'shoes', '879', '37.3 42 42.7', 'item7851135296img1.jpg', '7877845688', 0, '', '2024-12-08 18:21:16'),
('7855441853', 'Lattafa Perfumes', 'Guinea EDP By Lattafa Perfumes For Unisex 100ml', 60.00, 'perfumes', '454', 'one size', 'item7855441853img1.jpg,item7855441853img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('7861872214', 'Samsung Galaxy A54', 'Samsung Galaxy A54 128GB & 8GB RAM', 1600.00, 'electronics', '222', 'one size', 'item7861872214img1.jpg,item7861872214img2.jpg', '2385003204', 0, '', '2024-12-13 18:55:10'),
('7920558217', 'slicer', 'Apple slicer', 10.00, 'homeAppliances', '268', 'One Size', 'item7920558217img1.jpg,item7920558217img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('7951405830', 'theater doll', 'Tiger children\'s theater doll', 12.00, 'toys', '455', 'one size', 'item7951405830img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('8020509189', 'LG Washer', 'LG Washer Capacity 13 kg, 12 Programs, Inverter Direct Drive AI Motor, Black.\r\n', 5390.00, 'homeAppliances', '454', 'one size', 'item8020509189img1.jpg', '2385003204', 0, '', '2024-12-13 21:30:22'),
('8020798954', 'lantern', 'Large blue metal lantern', 30.00, 'homeAppliances', '654', 'One Size', 'item8020798954img1.jpg,item8020798954img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('8029117434', 'chain ', 'Gold chain with letter F design', 30.00, 'accessories', '343', 'one size', 'item8029117434img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('8119895726', 'Beko Refrigerator', 'Beko Refrigerator Capacity 590 Ltr, Inverter Compressor Save Energy, Black.\r\n', 5050.00, 'homeAppliances', '231', 'one size', 'item8119895726img1.jpg', '2385003204', 0, '', '2024-12-13 22:47:23'),
('8130883350', 'Penguin cloth toy', 'Penguin cloth toy, large size, green color', 50.00, 'toys', '453', 'one size', 'item8130883350img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('8149882246', 'Wall clock', 'Wall clock with numbers, diameter 46 cm', 65.00, 'homeAppliances', '432', 'One Size', 'item8149882246img1.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('8187885678', '9PRO 66W', 'Charging Cable PD to Type-c By 9PRO 66W black', 50.00, 'electronics', '457', 'one size', 'item8187885678img1.jpg,item8187885678img2.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('8236163572', 'Tous Les Jours', 'CUTIE By Tous Les Jours Perfume For Kids 30 Ml', 80.00, 'perfumes', '353', 'one size', 'item8236163572img1.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('8271513546', 'AOC MS121', 'AOC MS121 Wired Optical Mouse', 25.00, 'electronics', '575', 'one size', 'item8271513546img1.jpg,item8271513546img2.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('8371880211', 'necklace ', 'Golden necklace with letter T design', 30.00, 'accessories', '344', 'one size', 'item8371880211img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('8381019870', 'Iphone 16 Pro Max', 'Iphone 16 Pro Max 256GB', 5700.00, 'electronics', '5012', 'one size', 'item8381019870img1.jpg', '2385003204', 0, '', '2024-12-13 20:33:09'),
('8406545375', 'Adidas ', 'Adidas STUDIO LOUNGE RIBBED CROPPED LONG SLEEVE TEE', 150.00, 'clothes', '556', 'S,M,L,XL,XLL', 'item8406545375img1.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('8541796968', 'cups', 'Set of tea cups, Nescafe, 6 pieces', 50.00, 'homeAppliances', '445', 'one size', 'item8541796968img1.jpg,item8541796968img2.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('8585311029', 'chain ', 'Gold chain with letter E design', 30.00, 'accessories', '344', 'one size', 'item8585311029img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('8626431926', 'Samsung Galaxy A35', 'Samsung Galaxy A35 128/8GB\r\n', 1800.00, 'electronics', '235', 'one size', 'item8626431926img1.jpg', '2385003204', 0, '', '2024-12-13 19:04:40'),
('8700626724', 'Tous Les Jours', 'Tous Les Jours Perfume Day 393 For Women 55 Ml', 150.00, 'perfumes', '343', 'one size', 'item8700626724img1.jpg,item8700626724img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('8714919257', 'PUBG game controller', 'PUBG game controller, iron paws with red corset', 50.00, 'toys', '450', 'one size', 'item8714919257img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('8739567099', 'LG Refrigerator', 'LG Refrigerator 4 Door Capacity 545 Ltr, Inverter Compressor Save Energy, White Glass.\r\n', 9500.00, 'homeAppliances', '231', 'one size', 'item8739567099img1.jpg,item8739567099img2.jpg', '2385003204', 0, '', '2024-12-13 21:19:34'),
('8843500658', 'Writing board', 'Writing board with Mickey mouse stand and pens', 140.00, 'homeAppliances', '457', 'one size', 'item8843500658img1.jpg,item8843500658img2.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('8883506175', 'chain ', 'Golden chain with letter Y design\r\n', 30.00, 'accessories', '454', 'one size', 'item8883506175img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('8916455396', 'Adidas Pureboost', 'adidas Pureboost 22 Shoes\r\n', 600.00, 'shoes', '343', '43,44,45', 'item8916455396img1.jpg,item8916455396img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('8970401319', 'Lovely freesia', 'Lovely freesia Hollywood style Perfume Body Splash', 50.00, 'perfumes', '444', 'one size', 'item8970401319img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('8987800085', ' Children\'s cooler', 'Children\'s cooler with a tank capacity of 200 ml', 10.00, 'toys', '465', 'one size', 'item8987800085img1.jpg,item8987800085img2.jpg', '7397340373', 0, '', '2024-12-08 18:21:16'),
('9014452591', 'Deep bowl ', 'Deep black porcelain bowl with soup spoon', 15.00, 'homeAppliances', '35', 'One Size', 'item9014452591img1.jpg,item9014452591img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('9024785718', 'necklace ', 'A silver necklace with the design of “Live as you wish, you are dead, and love as you wish, you will leave”, blue color\r\n', 30.00, 'accessories', '663', 'One Size', 'item9024785718img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('9026784034', 'Deep porcelain ', 'Deep black porcelain bowl 23*17*6', 25.00, 'homeAppliances', '125', 'One Size', 'item9026784034img1.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('9031481298', 'Tous Les Jours', ' Tous Les Jours Perfume Day 79 For Men 55 Ml', 150.00, 'perfumes', '445', 'one size', 'item9031481298img1.jpg,item9031481298img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('9077387038', 'porcelain bowl', 'Rectangular porcelain bowl 31*12.5*2.5', 20.00, 'homeAppliances', '455', 'one size', 'item9077387038img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('9280058734', 'porcelain plate ', 'Square porcelain plate 27*27*2', 15.00, 'homeAppliances', '556', 'one size', 'item9280058734img1.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('9315755599', 'Ovling', 'Ovling Ov-P3 3.5Mm Wired Gaming Headset', 220.00, 'electronics', '100', 'One Size', 'item9315755599img1.jpg,item9315755599img2.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('9500280045', 'necklace ', 'Silver necklace with the design: I entrust you to God, whose deposits are never lost', 30.00, 'accessories', '125', 'One Size', 'item9500280045img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('9500404487', 'Charging Cable', 'Charging Cable Type C to Iphone By 9PRO 27W black', 50.00, 'electronics', '478', 'one size', 'item9500404487img1.jpg,item9500404487img2.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('9536403191', 'Iphone Magsafe case for 13 pro max ', 'Iphone 13 pro max Matte Case With Magsafe Black', 40.00, 'electronics', '200', 'One Size', 'item9536403191img1.png', '2385003204', 0, '', '2024-12-22 15:57:53'),
('9552019905', 'chain ', 'Gold chain with letter H design', 30.00, 'accessories', '454', 'one size', 'item9552019905img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16'),
('9604084261', 'Lattafa Perfumes', '-MA\'\'ANI EDP By Lattafa Perfumes For Unisex 100ml', 60.00, 'perfumes', '454', 'one size', 'item9604084261img1.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('9644168974', 'lantern', 'Red metal lantern', 16.00, 'homeAppliances', '554', 'One Size', 'item9644168974img1.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('9703711605', 'Lattafa Pride', 'Thouq EDP By Lattafa Pride Perfumes For Unisex 80ml', 100.00, 'perfumes', '393', 'one size', 'item9703711605img1.jpg,item9703711605img2.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('9717856633', 'Tous Les Jours', ' Powder Musk Perfume By Tous Les Jours For Women 6 Ml', 150.00, 'perfumes', '343', 'one size', 'item9717856633img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('9754970579', 'Hi-Tec Mens', 'Hi-Tec Mens\' Monar Polar Full Zip Fleece Jacket', 200.00, 'clothes', '453', 'S,M', 'item9754970579img1.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('9780719035', 'necklace ', 'Silver necklace with the design of the earth is ours and Jerusalem is ours, white color', 30.00, 'accessories', '444', 'One Size', 'item9780719035img1.jpg', '3144142944', 0, '', '2024-12-08 18:21:16'),
('9796868598', 'gold-colored', 'Women\'s gold-colored, heart-shaped earrings in two different shapes', 40.00, 'accessories', '400', 'one size', 'item9796868598img1.jpg,item9796868598img2.jpg', '3448383796', 0, '', '2024-12-08 18:21:16'),
('9797929659', 'Reebok Kid\'s ', 'Reebok Kid\'s XT Sprinter Shoes', 150.00, 'shoes', '567', '21', 'item9797929659img1.jpg,item9797929659img2.jpg', '7877845688', 0, '', '2024-12-08 18:21:16'),
('9829996307', 'Hitec GEO', 'Hi Tec Geo Trail Pro Mens Walking Shoes\r\n', 350.00, 'shoes', '787', ' 42 ,43, 43.5, 44.5, 45, 46', 'item9829996307img1.jpg', '7877845688', 0, '', '2024-12-08 18:21:16'),
('9884042033', 'Hi-Tec Monar', 'Hi-Tec Monar Full Zip Fleece Jacket', 200.00, 'clothes', '565', 'S,M,L,XL,XLL', 'item9884042033img1.jpg', '3102687798', 0, '', '2024-12-08 18:21:16'),
('9893441563', 'LG Washer', 'LG Washer Capacity 8 kg, 14 Programs, Inverter Direct Drive AI Motor, Dark Stainless.\r\n', 3050.00, 'homeAppliances', '123', 'one size', 'item9893441563img1.jpg', '2385003204', 0, '', '2024-12-13 21:26:39'),
('9945966468', 'face mask', 'Plastic toy squid face mask', 15.00, 'toys', '565', 'one size', 'item9945966468img1.jpg', '7397340373', 0, '', '2024-12-08 18:21:16'),
('9969745187', 'mirror', 'Rectangular mirror with black and silver base', 100.00, 'homeAppliances', '243', 'One Size', 'item9969745187img1.jpg', '2385003204', 0, '', '2024-12-08 18:21:16'),
('9990085703', 'Samsung Galaxy A05s', 'Samsung Galaxy A05s 6/128GB', 650.00, 'electronics', '342', 'one size', 'item9990085703img1.jpg,item9990085703img2.jpg,item9990085703img3.jpg', '2385003204', 0, '', '2024-12-13 18:59:34'),
('9993368166', 'Theater doll', 'Theater doll for children', 12.00, 'toys', '545', 'one size', 'item9993368166img1.jpg', '4768279420', 0, '', '2024-12-08 18:21:16');

-- --------------------------------------------------------

--
-- Table structure for table `product_feedback`
--

CREATE TABLE `product_feedback` (
  `Feedback_ID` int NOT NULL,
  `Product_ID` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Customer_ID` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Feedback_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_feedback`
--

INSERT INTO `product_feedback` (`Feedback_ID`, `Product_ID`, `Customer_ID`, `Feedback`, `Feedback_Date`) VALUES
(8, '5704682364', '4955970674', 'Great Product', '2025-01-01 00:24:28'),
(10, '6519306264', '4955970674', 'good quaily product', '2025-01-01 16:51:01'),
(12, '2928947859', '4955970674', 'good product', '2025-01-09 18:44:52');

-- --------------------------------------------------------

--
-- Table structure for table `product_ratings`
--

CREATE TABLE `product_ratings` (
  `Rating_ID` int NOT NULL,
  `Product_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `CustomerID` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Rating` int DEFAULT NULL,
  `Rating_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_ratings`
--

INSERT INTO `product_ratings` (`Rating_ID`, `Product_id`, `CustomerID`, `Rating`, `Rating_Date`) VALUES
(1, '5704682364', '4955970674', 5, '2025-01-01 00:24:33'),
(3, '6519306264', '4955970674', 5, '2025-01-01 16:50:50'),
(5, '2928947859', '4955970674', 4, '2025-01-09 18:45:06'),
(6, '2928947859', '5402298369', 4, '2025-01-14 05:56:44');

-- --------------------------------------------------------

--
-- Table structure for table `product_views`
--

CREATE TABLE `product_views` (
  `id` int NOT NULL,
  `customerID` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `product_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `view_count` int DEFAULT '1',
  `last_viewed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_views`
--

INSERT INTO `product_views` (`id`, `customerID`, `product_id`, `view_count`, `last_viewed`) VALUES
(670, '8597194991', '1188339878', 6, '2025-01-08 20:24:38'),
(675, '8597194991', '6902418323', 1, '2025-01-08 20:24:15'),
(676, '8597194991', '4955499444', 4, '2025-01-08 20:24:47'),
(682, '8597194991', '1750280510', 1, '2025-01-08 20:29:00'),
(683, '8597194991', '3055878105', 5, '2025-01-11 21:08:55'),
(687, '4955970674', '1662336566', 6, '2025-01-23 23:37:36'),
(689, '5402298369', '2150863808', 3, '2025-01-09 18:28:00'),
(690, '5402298369', '1927399439', 2, '2025-01-09 18:13:20'),
(691, '2051196568', '1662336566', 1, '2025-01-09 18:11:32'),
(693, '5402298369', '5704682364', 1, '2025-01-09 18:14:18'),
(694, '4955970674', '2150863808', 2, '2025-01-09 18:15:14'),
(695, '5402298369', '7841503275', 5, '2025-01-14 06:02:23'),
(696, '5402298369', '4854573552', 1, '2025-01-09 18:14:36'),
(699, '5402298369', '1064291851', 2, '2025-01-09 18:28:33'),
(700, '5402298369', '1211441631', 2, '2025-01-09 18:28:55'),
(701, '5402298369', '2031762751', 3, '2025-01-09 18:27:08'),
(707, '4955970674', '2928947859', 1, '2025-01-09 18:36:28'),
(708, '5402298369', '2928947859', 1, '2025-01-09 18:39:03'),
(709, '4955970674', '7841503275', 3, '2025-01-20 10:12:47'),
(710, '5402298369', '1188339878', 2, '2025-01-09 19:06:38'),
(713, '8263233966', '1976182758', 4, '2025-01-11 16:49:57'),
(717, '4955970674', '5704682364', 4, '2025-02-04 14:57:08'),
(718, '8597194991', '8242777479', 1, '2025-01-11 21:08:47'),
(720, '8597194991', '2150863808', 1, '2025-01-11 21:09:51'),
(721, '4955970674', '5767084813', 7, '2025-01-12 09:21:48'),
(728, '4955970674', '5646737856', 2, '2025-01-23 23:36:03'),
(729, '5402298369', '1662336566', 4, '2025-01-14 06:02:16'),
(738, '6174605338', '5704682364', 1, '2025-01-16 21:31:54'),
(740, '1076656600', '5775818547', 1, '2025-01-20 10:07:43'),
(741, '1076656600', '5653727252', 2, '2025-01-20 10:14:37'),
(743, '4955970674', '1030889266', 1, '2025-01-20 10:13:25'),
(748, '9597447671', '3340402056', 6, '2025-01-23 11:59:12'),
(750, '9597447671', '2579541322', 1, '2025-01-23 11:54:46'),
(757, '1501391992', '5646737856', 1, '2025-01-23 23:45:28'),
(758, '1501391992', '1662336566', 4, '2025-01-26 16:47:44'),
(759, '4955970674', '1138046228', 1, '2025-01-24 18:57:43'),
(760, '4955970674', '5775818547', 1, '2025-01-24 18:58:43'),
(761, '4955970674', '8843500658', 3, '2025-01-24 19:00:48'),
(764, '4955970674', '2257164084', 1, '2025-01-25 13:29:50'),
(765, '1501391992', '7419721774', 4, '2025-01-26 16:47:39'),
(767, '1501391992', '2733338330', 1, '2025-01-25 13:48:50'),
(768, '4955970674', '7419721774', 1, '2025-01-25 14:00:39'),
(769, '1501391992', '2214132478', 2, '2025-01-26 17:00:47'),
(771, '1501391992', '1188339878', 1, '2025-01-26 16:47:08'),
(776, '1501391992', '5704682364', 1, '2025-01-26 16:58:11'),
(777, '1501391992', '2786665298', 1, '2025-01-26 16:58:25'),
(778, '1501391992', '9315755599', 1, '2025-01-26 16:58:51'),
(779, '1501391992', '9884042033', 1, '2025-01-26 16:58:58'),
(780, '1501391992', '9829996307', 1, '2025-01-26 16:59:35'),
(781, '1501391992', '2150863808', 1, '2025-01-26 17:00:15'),
(783, '1501391992', '6519306264', 1, '2025-01-26 17:00:50'),
(784, '1501391992', '4955499444', 1, '2025-01-26 17:01:04'),
(785, '1501391992', '3055878105', 1, '2025-01-26 17:01:19'),
(786, '1501391992', '2651909489', 1, '2025-01-26 17:01:24'),
(787, '1501391992', '8739567099', 1, '2025-01-26 17:01:32'),
(788, '1501391992', '7841503275', 1, '2025-01-26 17:01:35'),
(789, '1501391992', '3190832992', 1, '2025-01-26 17:01:41'),
(790, '1501391992', '6907754309', 1, '2025-01-26 17:01:56'),
(791, '1501391992', '2257164084', 1, '2025-01-26 17:02:04');

-- --------------------------------------------------------

--
-- Table structure for table `recommended_products`
--

CREATE TABLE `recommended_products` (
  `id` int NOT NULL,
  `customerID` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `product_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `similarity_score` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recommended_products`
--

INSERT INTO `recommended_products` (`id`, `customerID`, `product_id`, `similarity_score`, `created_at`) VALUES
(4559, '8263233966', '1188339878', 'Viewed', '2025-01-11 16:49:03'),
(4560, '8263233966', '2150863808', 'Viewed', '2025-01-11 16:49:03'),
(4561, '8263233966', '3055878105', 'Viewed', '2025-01-11 16:49:03'),
(4562, '8263233966', '4955499444', 'Viewed', '2025-01-11 16:49:03'),
(4563, '8263233966', '1976182758', 'Viewed', '2025-01-11 16:50:00'),
(5064, '4050539954', '1188339878', 'Viewed', '2025-01-12 16:31:45'),
(5065, '4050539954', '5767084813', 'Viewed', '2025-01-12 16:31:45'),
(5066, '4050539954', '2150863808', 'Viewed', '2025-01-12 16:31:45'),
(5067, '4050539954', '3055878105', 'Viewed', '2025-01-12 16:31:45'),
(5120, '0483290151', '1188339878', 'Viewed', '2025-01-14 09:19:45'),
(5121, '0483290151', '1662336566', 'Viewed', '2025-01-14 09:19:46'),
(5122, '0483290151', '5767084813', 'Viewed', '2025-01-14 09:19:46'),
(5123, '0483290151', '2150863808', 'Viewed', '2025-01-14 09:19:46'),
(5206, '1076656600', '1188339878', '9', '2025-01-25 13:39:09'),
(5207, '1076656600', '1662336566', 'Viewed', '2025-01-25 13:39:09'),
(5208, '1076656600', '7841503275', 'Viewed', '2025-01-25 13:39:09'),
(5209, '1076656600', '5767084813', 'Viewed', '2025-01-25 13:39:09'),
(5210, '1501391992', '4115590808', '12', '2025-01-25 13:39:09'),
(5211, '1501391992', '4854573552', '10', '2025-01-25 13:39:09'),
(5212, '1501391992', '2555273293', '14', '2025-01-25 13:39:09'),
(5213, '1501391992', '1976182758', '14', '2025-01-25 13:39:09'),
(5214, '1501391992', '2270254462', '10', '2025-01-25 13:39:09'),
(5215, '1501391992', '1662336566', 'Viewed', '2025-01-25 13:39:09'),
(5216, '1501391992', '1188339878', 'Viewed', '2025-01-25 13:39:09'),
(5217, '1501391992', '5767084813', 'Viewed', '2025-01-25 13:39:09'),
(5218, '3657808260', '6519306264', '11', '2025-01-25 13:39:09'),
(5219, '3657808260', '1188339878', 'Viewed', '2025-01-25 13:39:09'),
(5220, '3657808260', '7841503275', 'Viewed', '2025-01-25 13:39:09'),
(5221, '3657808260', '5767084813', 'Viewed', '2025-01-25 13:39:09'),
(5222, '4955970674', '5646737856', '9', '2025-01-25 13:39:09'),
(5223, '4955970674', '4955499444', '11', '2025-01-25 13:39:09'),
(5224, '4955970674', '5653727252', '9', '2025-01-25 13:39:09'),
(5225, '4955970674', '3005461530', '10', '2025-01-25 13:39:09'),
(5226, '4955970674', '4115590808', '12', '2025-01-25 13:39:09'),
(5227, '4955970674', '1188339878', 'Viewed', '2025-01-25 13:39:09'),
(5228, '4955970674', '5767084813', 'Viewed', '2025-01-25 13:39:09'),
(5229, '5402298369', '4959781076', '11', '2025-01-25 13:39:09'),
(5230, '5402298369', '4115590808', '12', '2025-01-25 13:39:09'),
(5231, '5402298369', '6519306264', '11', '2025-01-25 13:39:09'),
(5232, '5402298369', '1188339878', 'Viewed', '2025-01-25 13:39:09'),
(5233, '5402298369', '5767084813', 'Viewed', '2025-01-25 13:39:09'),
(5234, '5532712640', '4115590808', '14', '2025-01-25 13:39:09'),
(5235, '5532712640', '5646737856', '7', '2025-01-25 13:39:09'),
(5236, '5532712640', '4383532743', '12', '2025-01-25 13:39:09'),
(5237, '5532712640', '1662336566', 'Viewed', '2025-01-25 13:39:09'),
(5238, '5532712640', '1188339878', 'Viewed', '2025-01-25 13:39:09'),
(5239, '5532712640', '5767084813', 'Viewed', '2025-01-25 13:39:09'),
(5240, '6174605338', '4115590808', '12', '2025-01-25 13:39:09'),
(5241, '6174605338', '5646737856', '12', '2025-01-25 13:39:09'),
(5242, '6174605338', '4955499444', '11', '2025-01-25 13:39:09'),
(5243, '6174605338', '1662336566', 'Viewed', '2025-01-25 13:39:09'),
(5244, '6174605338', '1188339878', 'Viewed', '2025-01-25 13:39:09'),
(5245, '6174605338', '5767084813', 'Viewed', '2025-01-25 13:39:09'),
(5246, '8597194991', '2270254462', '10', '2025-01-25 13:39:09'),
(5247, '8597194991', '4115590808', '14', '2025-01-25 13:39:09'),
(5248, '8597194991', '1662336566', 'Viewed', '2025-01-25 13:39:09'),
(5249, '8597194991', '1188339878', 'Viewed', '2025-01-25 13:39:09'),
(5250, '8597194991', '7841503275', 'Viewed', '2025-01-25 13:39:09'),
(5251, '8597194991', '5767084813', 'Viewed', '2025-01-25 13:39:09'),
(5252, '9531271320', '5646737856', '11', '2025-01-25 13:39:09'),
(5253, '9531271320', '1662336566', 'Viewed', '2025-01-25 13:39:09'),
(5254, '9531271320', '1188339878', 'Viewed', '2025-01-25 13:39:09'),
(5255, '9531271320', '7841503275', 'Viewed', '2025-01-25 13:39:09'),
(5256, '9531271320', '5767084813', 'Viewed', '2025-01-25 13:39:09'),
(5257, '9597447671', '1138046228', '11', '2025-01-25 13:39:09'),
(5258, '9597447671', '1662336566', 'Viewed', '2025-01-25 13:39:09'),
(5259, '9597447671', '1188339878', 'Viewed', '2025-01-25 13:39:09'),
(5260, '9597447671', '7841503275', 'Viewed', '2025-01-25 13:39:09'),
(5261, '9597447671', '5767084813', 'Viewed', '2025-01-25 13:39:09'),
(5262, '1501391992', '7162700809', '12', '2025-01-25 13:43:13'),
(5263, '1501391992', '2249001402', '12', '2025-01-25 13:43:13'),
(5264, '1501391992', '2651909489', '12', '2025-01-25 13:43:13'),
(5265, '1501391992', '5664847993', '10', '2025-01-25 13:43:13'),
(5266, '1501391992', '8020509189', '12', '2025-01-25 13:43:44'),
(5267, '1501391992', '3055878105', '10', '2025-01-25 13:43:44'),
(5268, '1501391992', '8119895726', '10', '2025-01-25 13:43:44'),
(5269, '1501391992', '7698959678', '10', '2025-01-25 13:43:44'),
(5270, '1501391992', '2214132478', '14', '2025-01-25 13:43:44'),
(5271, '1501391992', '9893441563', '12', '2025-01-25 13:48:20'),
(5272, '1501391992', '4023459664', '10', '2025-01-25 13:48:20'),
(5273, '1501391992', '8739567099', '8', '2025-01-25 13:48:20'),
(5274, '1501391992', '2733338330', '8', '2025-01-25 13:48:20'),
(5275, '1501391992', '1679372340', '9', '2025-01-25 13:48:20'),
(5276, '1501391992', '4686468367', '10', '2025-01-25 13:48:41'),
(5277, '1501391992', '5305477301', '9', '2025-01-25 13:48:41'),
(5278, '1501391992', '7411591986', '9', '2025-01-25 13:48:52'),
(5279, '1501391992', '2387534679', '8', '2025-01-25 13:48:58'),
(5280, '1501391992', '7142153268', '8', '2025-01-25 13:49:32'),
(5281, '4955970674', '7278964232', '8', '2025-01-25 14:00:29'),
(5282, '4955970674', '1138046228', '9', '2025-01-25 14:00:29'),
(5283, '4955970674', '2441555403', '7', '2025-01-25 14:00:29'),
(5284, '4955970674', '1191783680', '8', '2025-01-25 14:00:29'),
(5285, '4955970674', '4383532743', '12', '2025-01-25 14:00:29'),
(5286, '4955970674', '9536403191', '9', '2025-01-25 14:00:44'),
(5287, '4955970674', '3224214205', '7', '2025-01-25 14:00:44'),
(5288, '4955970674', '1629269866', '8', '2025-01-25 14:00:44'),
(5289, '4955970674', '4854573552', '12', '2025-01-25 14:00:44'),
(5290, '4955970674', '6753668485', '7', '2025-02-04 14:56:52'),
(5291, '4955970674', '6212905386', '8', '2025-02-04 14:56:52'),
(5292, '4955970674', '7162700809', '12', '2025-02-04 14:56:52'),
(5293, '4955970674', '6907754309', '7', '2025-02-04 14:57:03'),
(5294, '4955970674', '8020509189', '12', '2025-02-04 14:57:03'),
(5295, '4955970674', '7861872214', '7', '2025-02-04 14:57:16'),
(5296, '4955970674', '9893441563', '12', '2025-02-04 14:57:16');

-- --------------------------------------------------------

--
-- Table structure for table `sellers`
--

CREATE TABLE `sellers` (
  `SellerID` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `SellerName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `BusinessName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `S_Address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `S_PhoneNumber` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `SellerEmail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `IBAN` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `SecurityQuestion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `SecurityAnswer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sellers`
--

INSERT INTO `sellers` (`SellerID`, `SellerName`, `BusinessName`, `S_Address`, `S_PhoneNumber`, `SellerEmail`, `IBAN`, `SecurityQuestion`, `SecurityAnswer`) VALUES
('2385003204', 'Amr Mohamed', 'Amr Store', 'Al-Quds Street, Ramallah', '05976543219', 'amr.mohamed@gmail.com', 'JO12345678901234567890', 'What is your mother\'s maiden name?', '$2y$10$2rICLCCczqBRJg5v5VtIIOrAN1OnEQ3/L4NBELqc/HCTWzGPiT1iO'),
('3102687798', 'Khaled Ahmed', 'Khaled Store', 'Salah al-Din Street, No. 34, Hebron', '05976543210', 'khaled.ahmed@gmail.com', ' JO12345678901234567891', 'What is your pet\'s name?', '$2y$10$kJrLm/Zfydkj8KVvB/.uWuxvu0dFIuO.xKWf1WO5gVLgyhRUUhwMu'),
('3144142944', 'Noor Abdullah', 'Noor Store', 'Al-Wahda Street, No. 25, Nablus', '05987654321', 'noor.abdullah@gmail.com', 'JO12345678901234567891', 'What is your favorite book?', '$2y$10$LgwNkFkrmzPm1GhmY31jDuCbRGJrR2NesgFD87WciNA7wcPP6NfH.'),
('3448383796', 'Youssef Ali', 'Youssef Store', 'Omar Mukhtar Street, No. 45, Jenin', '05954321098', 'youssef.ali@gmail.com', 'JO12345678901234567891', 'What city were you born in?', '$2y$10$ATNIpyJ59TzhxdKxEFrR3.JudmjuyqRU0QV5Bcs260vNo0nBaDNf.'),
('4768279420', 'Fatima Yasser', 'Fatima Store', 'Al-Ersal Street, No. 18, Ramallah', '05965432109', 'fatima.yasser@gmail.com', 'JO12345678901234567891', 'What was the name of your first school?', '$2y$10$zlsOKOust4rfMo9AxWsQAO7Yv0y2YpKxT77xKoiW/MgxpVCGkN8Iu'),
('5118396002', 'Qais Style', 'Qais Style', 'Ramallah', '0599477718', 'qaisstyle@gmail.com', 'ps0000550002000880002', 'What city were you born in?', 'bir nabala'),
('5767748039', 'Alaa Style', 'Alaa Style', 'Ramallah', '0599477719', 'alaass@gmail.com', 'ps0000550002000000002', 'What city were you born in?', 'bir nabala'),
('7397340373', 'Rana Ahmad', 'Rana Store', 'Al-Shuhada Street, No. 22, Tulkarem', '05943210987', 'rana.ahmad@gmail.com', 'JO12345678901234567891', 'What is your mother\'s maiden name?', '$2y$10$zl18WGGAO44g9vOGXXAOse3K1EWKl4I0e3DIDHsHMqKujxfPiMH4a'),
('7862644727', 'Test', 'Test3', 'Test', '0561234234', 'Test@gmail.com', 'PS00000000000000123456789', 'What is your favorite book?', '$2y$10$CflJ5xYEyCRNHOepBKWhCOeW2kVsLcZiPGui23j..eRI/YVv0zsp.'),
('7877845688', 'Omar Khaled', 'Omar Store', 'Al-Hamra Street, No. 31, Bethlehem', '05932109876', 'omar.khaled@gmail.com', 'JO12345678901234567891', 'What is your favorite book?', '$2y$10$a4S8x9u5hlajJRccOSN.suoZmmN8vjPEf.6OU5.p0Ik53hoN5Apxq'),
('8683101429', 'yazan ', 'hmom', 'turmosaya', '0595266253', 'yazan.2017.hmom@gmail.com', '6363', 'What is your pet\'s name?', '$2y$10$nwRqnrNMqHjeNiYCF.tcRubpX0kTuf81WfzjdMpaLp7wrPGNynH5a'),
('9665101122', 'Leen Mohamed', 'Leen Store', 'Al-Safouri Street, No. 14, Qalqilya', '05921098765', 'leen.mohamed@gmail.com', 'JO12345678901234567891', 'What was the name of your first school?', '$2y$10$V6w5zyjyjrGC.xJVQ2cam.3JxTAKx6rifcx0e1mALeo8rgmlqKOGa');

-- --------------------------------------------------------

--
-- Table structure for table `seller_notes`
--

CREATE TABLE `seller_notes` (
  `note_id` int NOT NULL,
  `product_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `seller_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller_support_tickets`
--

CREATE TABLE `seller_support_tickets` (
  `ticket_id` int NOT NULL,
  `seller_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `employee_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('open','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'open',
  `last_response` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller_users`
--

CREATE TABLE `seller_users` (
  `SellerID` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `suspended` tinyint(1) DEFAULT '0',
  `suspension_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller_users`
--

INSERT INTO `seller_users` (`SellerID`, `username`, `password`, `suspended`, `suspension_date`) VALUES
('5767748039', 'Alaa Style', '$2y$10$.Dw.LRJ.radMeHIKeJls/etX2UbvYuxlOcH1IRlmhtwiOwfw/9Cea', 0, NULL),
('2385003204', 'Amr123', '$2y$10$XoemUHqto.TunmK5p8KfY.rKoogGCCrHS1JMjHmNR.D.qEUz5TZma', 0, NULL),
('4768279420', 'Fatima432', '$2y$10$pr4dJq7oDRo8BaHTzkjTS.Svq65lJpUPiDWz8DxoXn8dZOxrq3eq6', 0, NULL),
('3102687798', 'Khaled765', '$2y$10$iglApbF4XFU5146uiZKBi.nVpr1Gp2ZHHN/q20kdOCoYxvEoQ5EA.', 0, NULL),
('9665101122', 'Leen876', '$2y$10$Np1fA99Rtopus4rQkDnHAe2wh9HdWez0d7/nfcvEPOQYK.hI27yE2', 0, NULL),
('3144142944', 'Noor987', '$2y$10$zJrcjtGg6Dy60b9S8T3tDuzCqjlI4LsFv6izvtFXewHvkG.Pn5Rli', 0, NULL),
('7877845688', 'Omar234', '$2y$10$y7RjFbW5pmgWKXY3E6pQBOXnAKL3u4urZRzDIvobwCzH7ew/LW9hi', 0, NULL),
('5118396002', 'Qais Style', '$2y$10$8e6q0Eu7sWqiNjemJk8jGuJwbmr5c/Dyi.EvUCQaTEBz3pAoMaSbS', 0, NULL),
('7397340373', 'Rana567', '$2y$10$YcBhZtqwfID56tn7dIATPuBQ5ZeWTk7mNzpSC1qrqCGuzPyyMUQUW', 0, NULL),
('7862644727', 'test123', '$2y$10$tI34WDoK6qgpapRhMyLD9.hXaYLUfTwAch6GYVpkOl3cry96VmGIe', 0, NULL),
('8683101429', 'yazanb', '$2y$10$FEXdvnDIjMK.ejTLSr/9wOtHJ2BsqMB/w8PP7WdlyDU6AcBJ3vAjm', 0, NULL),
('3448383796', 'Youssef108', '$2y$10$SzytYNxznua/yG8qcrDu..Jo.b/yAZPTK8KmZ9Iy6TTtXzuZggiYm', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `ticket_id` int NOT NULL,
  `customer_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `employee_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('open','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'open',
  `last_response` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `suspended` tinyint(1) DEFAULT '0',
  `suspension_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `suspended`, `suspension_date`) VALUES
('1076656600', 'abd123', '$2y$10$u4rwB0Cd1rQ4bt4zlG6iCOhM5fzknrQoAZ6dOPjFSYNJIhH.LtIWa', 0, NULL),
('3550170914', 'ahmad987', '$2y$10$.vXOaxgsrDKq5MspnOCTO.H/HxGB7cbirs3HomRq7FVlrQJTqWmOm', 0, NULL),
('3657808260', 'Alaa122221', '$2y$10$iyAkZ1Z8Q1BrPSeTw4q7puvdCY1/wefwu2QmJmMkvqzLJ7.rEUhnC', 0, NULL),
('1501391992', 'Alaa773', '$2y$10$gwyQBI9/RSnusPpgGxS.i.kX.TK5A32LTNEZ30W3MA1fbfZQDHFJi', 0, NULL),
('6174605338', 'Ali123', '$2y$10$TDn/Z1EOC5i/wg8zcIZb7esU..RbsMiLOoZV6E.m6V8RGUnoaihsK', 0, NULL),
('4050539954', 'Anwaar22', '$2y$10$m5bNuq2Lu/m06VxCzQNsU.2aKNfSFHMf7MfFLtHqmwbV5eqEYRIhe', 0, NULL),
('1316488225', 'dia123', '$2y$10$O.4PZQa.ta5HE4q3pIfqV.6GJbEtKdUawQt4ho5O4kgNOtvk3pydu', 0, NULL),
('0483290151', 'israaayyad', '$2y$10$Von2JxsZI5H0dTvuNP/e5ueC2DkabUz0JZHR1h61n6MOUfYiSuvEq', 0, NULL),
('6309430972', 'khalid653', '$2y$10$s6oiu3rj8bUe3TsGpeLm6OUHvUbaszXXb5ckT5ccfeN4.3A//SHym', 0, NULL),
('9845853996', 'mhd123', '$2y$10$SxMTdYYNoQiUirnzCxee8eN3BNiv.G2odkEQhyxRnAZkYt9c0Q9Uy', 0, NULL),
('5363386848', 'mos123', '$2y$10$/c/rdwpzQafVV4cOU2LtauIV35zPFnZ/6/4mcxnPos1MOo4JrNWve', 0, NULL),
('8882976639', 'nas123', '$2y$10$5xOnNRZiDoZiyhXcQMJGle9JUHt7KPbPSUqJvw5wb88UAacgi.0LK', 0, NULL),
('4955970674', 'qais1023', '$2y$10$r8/IVD44ontywz0N/JBR6.HQBznwnj3C2b1hvnz8cQedNAyzp0zc2', 0, NULL),
('3214416059', 'qua123', '$2y$10$ViHyBh5YEL9WTQhzLWbTuO3MdiQdVObhcPMrjJKGy2X8aNJfyD56m', 0, NULL),
('8597194991', 'rie123', '$2y$10$EmVfhieLCEI57mEjW7MtjekJ98K6tdaXPBH1NeHJaliknVRyKuKOG', 0, NULL),
('8115612148', 'rul123', '$2y$10$TFgLhy7MAedTwRXIQGJsg.DsCFLyRNm.U/4SPEQJ1W1..a41hHSNe', 0, NULL),
('5532712640', 'sad123', '$2y$10$9zmRfYrClXV9oNbkeixxK.067kChWICwoN5MaVo4pNqGnPbsQIpL2', 0, NULL),
('9531271320', 'sub123', '$2y$10$jb0krpmkJlB9Z3PQ3PZZJ.fTvorTWtv73A1qDWM5V1787y.4/SEE2', 0, NULL),
('9597447671', 'tal123', '$2y$10$us9GKcKcG9caQViSBPPkOOwMghtIFVfwS6x.l.I9bznksH1iy9HqO', 0, NULL),
('6041235121', 'tar123', '$2y$10$hnfIb3eUtc9ip./lky4kSO571rT7sWuSLmsb6oT6CsaTcIppyLKaC', 0, NULL),
('8263233966', 'test22', '$2y$10$wRMqo.5SDcmxAajRwmH0r.CUl6ckNjQhiDxp7nkgptYD46K68Tp.i', 0, NULL),
('2051196568', 'Test23', '$2y$10$edHl0wUgpn7deseANgHV2O5f69B9GJJ0VgOK9LA1UJ3hHzmQHpeIu', 0, NULL),
('8290149739', 'wafeeq123', '$2y$10$3Q/eFl.sLCKAQNIgBkk6xe5GJaz7Vft4SacW.RtoaXTMdxee.A.xq', 0, NULL),
('5402298369', 'wasfia', '$2y$10$KF5Ah6c9P5Oiq0g47rtoAe5P4ff0xOKPuzGcns7/FvlVM7xNJMmW6', 0, NULL),
('0405290052', 'wasfie23', '$2y$10$wkQwokKrtiv3szghGdcGf.feK9wcx14stTNSlrnhII.0vy2oVaTQ2', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerID`);

--
-- Indexes for table `deleted_recommendations`
--
ALTER TABLE `deleted_recommendations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deleted_recommendations_ibfk_1` (`customerID`),
  ADD KEY `deleted_recommendations_ibfk_2` (`product_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`Employee_Id`);

--
-- Indexes for table `emp_users`
--
ALTER TABLE `emp_users`
  ADD PRIMARY KEY (`username`),
  ADD KEY `emp_users_ibfk_1` (`emp_id`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`Manager_ID`);

--
-- Indexes for table `manager_employee_tickets`
--
ALTER TABLE `manager_employee_tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `manager_user`
--
ALTER TABLE `manager_user`
  ADD PRIMARY KEY (`username`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`Order_ID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `SellerID` (`SellerID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `policy`
--
ALTER TABLE `policy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`Product_id`),
  ADD KEY `SellerID` (`SellerID`);

--
-- Indexes for table `product_feedback`
--
ALTER TABLE `product_feedback`
  ADD PRIMARY KEY (`Feedback_ID`),
  ADD UNIQUE KEY `Product_ID` (`Product_ID`,`Customer_ID`),
  ADD KEY `Customer_ID` (`Customer_ID`);

--
-- Indexes for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD PRIMARY KEY (`Rating_ID`),
  ADD UNIQUE KEY `unique_rating` (`Product_id`,`CustomerID`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `product_views`
--
ALTER TABLE `product_views`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customerID` (`customerID`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `recommended_products`
--
ALTER TABLE `recommended_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customerID` (`customerID`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `sellers`
--
ALTER TABLE `sellers`
  ADD PRIMARY KEY (`SellerID`);

--
-- Indexes for table `seller_notes`
--
ALTER TABLE `seller_notes`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `seller_support_tickets`
--
ALTER TABLE `seller_support_tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `seller_users`
--
ALTER TABLE `seller_users`
  ADD PRIMARY KEY (`username`),
  ADD KEY `SellerID` (`SellerID`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deleted_recommendations`
--
ALTER TABLE `deleted_recommendations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `manager_employee_tickets`
--
ALTER TABLE `manager_employee_tickets`
  MODIFY `ticket_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `Order_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=296;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=336;

--
-- AUTO_INCREMENT for table `product_feedback`
--
ALTER TABLE `product_feedback`
  MODIFY `Feedback_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_ratings`
--
ALTER TABLE `product_ratings`
  MODIFY `Rating_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_views`
--
ALTER TABLE `product_views`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=793;

--
-- AUTO_INCREMENT for table `recommended_products`
--
ALTER TABLE `recommended_products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5297;

--
-- AUTO_INCREMENT for table `seller_notes`
--
ALTER TABLE `seller_notes`
  MODIFY `note_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `seller_support_tickets`
--
ALTER TABLE `seller_support_tickets`
  MODIFY `ticket_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `ticket_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deleted_recommendations`
--
ALTER TABLE `deleted_recommendations`
  ADD CONSTRAINT `deleted_recommendations_ibfk_1` FOREIGN KEY (`customerID`) REFERENCES `customer` (`customerID`),
  ADD CONSTRAINT `deleted_recommendations_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`Product_id`);

--
-- Constraints for table `emp_users`
--
ALTER TABLE `emp_users`
  ADD CONSTRAINT `emp_users_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `employee` (`Employee_Id`) ON DELETE CASCADE;

--
-- Constraints for table `manager_user`
--
ALTER TABLE `manager_user`
  ADD CONSTRAINT `manager_user_ibfk_1` FOREIGN KEY (`id`) REFERENCES `manager` (`Manager_ID`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`SellerID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`customerID`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`SellerID`) REFERENCES `sellers` (`SellerID`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`Order_ID`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`Product_id`),
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`customerID`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`SellerID`) REFERENCES `sellers` (`SellerID`);

--
-- Constraints for table `product_feedback`
--
ALTER TABLE `product_feedback`
  ADD CONSTRAINT `product_feedback_ibfk_1` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`customerID`),
  ADD CONSTRAINT `product_feedback_ibfk_2` FOREIGN KEY (`Product_ID`) REFERENCES `products` (`Product_id`);

--
-- Constraints for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD CONSTRAINT `product_ratings_ibfk_1` FOREIGN KEY (`Product_id`) REFERENCES `products` (`Product_id`),
  ADD CONSTRAINT `product_ratings_ibfk_2` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`customerID`);

--
-- Constraints for table `product_views`
--
ALTER TABLE `product_views`
  ADD CONSTRAINT `product_views_ibfk_1` FOREIGN KEY (`customerID`) REFERENCES `customer` (`customerID`),
  ADD CONSTRAINT `product_views_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`Product_id`);

--
-- Constraints for table `recommended_products`
--
ALTER TABLE `recommended_products`
  ADD CONSTRAINT `recommended_products_ibfk_1` FOREIGN KEY (`customerID`) REFERENCES `customer` (`customerID`);

--
-- Constraints for table `seller_notes`
--
ALTER TABLE `seller_notes`
  ADD CONSTRAINT `seller_notes_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`SellerID`),
  ADD CONSTRAINT `seller_notes_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`Product_id`);

--
-- Constraints for table `seller_support_tickets`
--
ALTER TABLE `seller_support_tickets`
  ADD CONSTRAINT `seller_support_tickets_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`SellerID`),
  ADD CONSTRAINT `seller_support_tickets_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `emp_users` (`emp_id`);

--
-- Constraints for table `seller_users`
--
ALTER TABLE `seller_users`
  ADD CONSTRAINT `seller_users_ibfk_1` FOREIGN KEY (`SellerID`) REFERENCES `sellers` (`SellerID`);

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customerID`),
  ADD CONSTRAINT `support_tickets_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `emp_users` (`emp_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id`) REFERENCES `customer` (`customerID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
