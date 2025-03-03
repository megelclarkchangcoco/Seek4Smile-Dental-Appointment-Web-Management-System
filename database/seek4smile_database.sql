-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2025 at 07:41 AM
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
-- Database: `seek4smile_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `LogID` int(11) NOT NULL,
  `UserType` enum('Patient','Dentist','Assistant','BillingSpecialist','Admin') NOT NULL,
  `UserID` varchar(10) NOT NULL,
  `Activity` enum('Login','Logout','OtherAction') NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tracks login/logout activities for all user types';

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`LogID`, `UserType`, `UserID`, `Activity`, `Timestamp`) VALUES
(1, 'Patient', 'PAT002', 'Login', '2025-01-25 08:16:42'),
(2, 'Patient', 'PAT002', 'Logout', '2025-01-25 08:16:46'),
(3, 'Admin', 'ADMID002', 'Login', '2025-01-25 08:24:26'),
(4, 'Admin', 'ADMID002', 'Logout', '2025-01-25 08:24:34'),
(5, 'Admin', 'ADMID001', 'Login', '2025-01-25 08:24:50'),
(6, 'Admin', 'ADMID001', 'Logout', '2025-01-25 08:24:52'),
(7, 'Admin', 'ADMID002', 'Login', '2025-01-25 08:25:47'),
(8, 'Admin', 'ADMID002', 'Logout', '2025-01-25 08:25:51'),
(9, 'Admin', 'ADMID001', 'Login', '2025-01-25 08:26:53'),
(10, 'Admin', 'ADMID001', 'Login', '2025-01-25 09:43:23'),
(11, 'Admin', 'ADMID001', 'Logout', '2025-01-25 09:43:26'),
(12, 'BillingSpecialist', 'SPEID001', 'Login', '2025-01-25 09:49:06'),
(13, 'BillingSpecialist', 'SPEID001', 'Logout', '2025-01-25 09:49:16'),
(14, 'Admin', 'ADMID001', 'Login', '2025-01-25 09:49:46'),
(15, 'Admin', 'ADMID001', 'Logout', '2025-01-25 09:50:06'),
(16, 'Admin', 'ADMID002', 'Login', '2025-01-25 09:50:19'),
(17, 'Admin', 'ADMID002', 'Logout', '2025-01-25 09:50:29'),
(18, 'Admin', 'ADMID001', 'Login', '2025-01-25 09:51:06'),
(19, 'Admin', 'ADMID002', 'Login', '2025-01-25 09:51:35'),
(20, 'Admin', 'ADMID002', 'Logout', '2025-01-25 09:51:37'),
(21, 'Admin', 'ADMID001', 'Login', '2025-01-25 09:53:28'),
(22, 'Admin', 'ADMID001', 'Logout', '2025-01-25 09:54:53'),
(23, 'Admin', 'ADMID001', 'Login', '2025-01-25 09:55:14'),
(24, 'Admin', 'ADMID001', 'Logout', '2025-01-25 09:56:17'),
(25, 'Dentist', 'DENID001', 'Login', '2025-01-25 10:05:29'),
(26, 'Dentist', 'DENID001', 'Logout', '2025-01-25 10:05:31'),
(27, 'Assistant', 'DASID001', 'Login', '2025-01-25 10:12:10'),
(28, 'Assistant', 'DASID001', 'Logout', '2025-01-25 10:13:15'),
(29, 'Assistant', 'DASID001', 'Login', '2025-01-27 17:00:10'),
(30, 'Assistant', 'DASID001', 'Login', '2025-01-27 17:01:08'),
(31, 'Assistant', 'DASID001', 'Login', '2025-01-27 17:01:36'),
(32, 'Assistant', 'DASID001', 'Login', '2025-01-27 17:01:42'),
(33, 'Assistant', 'DASID001', 'Login', '2025-01-27 17:02:09'),
(34, 'Assistant', 'DASID001', 'Logout', '2025-01-27 17:02:14'),
(35, 'Assistant', 'DASID001', 'Login', '2025-01-27 17:02:40'),
(36, 'Assistant', 'DASID001', 'Logout', '2025-01-27 17:06:23'),
(37, 'Assistant', 'DASID001', 'Login', '2025-01-27 17:07:14'),
(38, 'Assistant', 'DASID001', 'Logout', '2025-01-27 17:07:22'),
(39, 'Assistant', 'DASID001', 'Login', '2025-01-27 17:08:40'),
(40, 'Assistant', 'DASID001', 'Logout', '2025-01-27 17:08:47'),
(41, 'Patient', 'PAT004', 'Login', '2025-01-28 03:57:40'),
(42, 'Patient', 'PAT002', 'Login', '2025-01-28 19:03:19'),
(43, 'Patient', 'PAT002', 'Login', '2025-01-29 05:33:03'),
(44, 'Patient', 'PAT002', 'Login', '2025-01-29 17:46:33'),
(45, 'Patient', 'PAT002', 'Login', '2025-01-30 08:11:03'),
(46, 'Patient', 'PAT002', 'Login', '2025-01-30 12:33:58'),
(47, 'Patient', 'PAT002', 'Login', '2025-01-30 14:23:53'),
(48, 'Patient', 'PAT005', 'Login', '2025-01-30 15:33:04'),
(49, 'Patient', 'PAT005', 'Logout', '2025-01-30 15:34:58'),
(50, 'Patient', 'PAT002', 'Login', '2025-01-30 20:08:56'),
(51, 'Patient', 'PAT002', 'Logout', '2025-01-30 20:09:37'),
(52, 'Patient', 'PAT002', 'Login', '2025-01-30 20:13:56'),
(53, 'Patient', 'PAT002', 'Login', '2025-02-01 07:21:22'),
(54, 'Patient', 'PAT002', 'Login', '2025-02-01 09:12:56'),
(55, 'Patient', 'PAT002', 'Login', '2025-02-01 09:14:40'),
(56, 'Patient', 'PAT002', 'Logout', '2025-02-01 09:14:46'),
(57, 'Patient', 'PAT005', 'Login', '2025-02-01 09:15:38'),
(58, 'Patient', 'PAT005', 'Logout', '2025-02-01 09:16:03'),
(59, 'Patient', 'PAT002', 'Login', '2025-02-01 09:16:26'),
(60, 'Patient', 'PAT002', 'Logout', '2025-02-01 09:29:32'),
(61, 'Patient', 'PAT005', 'Login', '2025-02-01 09:29:45'),
(62, 'Patient', 'PAT005', 'Logout', '2025-02-01 09:30:17'),
(63, 'Patient', 'PAT002', 'Login', '2025-02-01 09:30:31'),
(64, 'Patient', 'PAT002', 'Login', '2025-02-01 16:50:22'),
(65, 'Admin', 'ADMID001', 'Login', '2025-02-03 03:49:27'),
(66, 'Admin', 'ADMID001', 'Logout', '2025-02-03 03:49:28'),
(67, 'BillingSpecialist', 'SPEID001', 'Login', '2025-02-03 03:49:41'),
(68, 'BillingSpecialist', 'SPEID001', 'Logout', '2025-02-03 03:49:42'),
(69, 'Dentist', 'DENID002', 'Login', '2025-02-03 03:49:54'),
(70, 'Dentist', 'DENID002', 'Logout', '2025-02-03 03:49:55'),
(71, 'Assistant', 'DASID002', 'Login', '2025-02-03 03:50:05'),
(72, 'Assistant', 'DASID002', 'Logout', '2025-02-03 03:50:08'),
(73, 'Patient', 'PAT002', 'Login', '2025-02-03 03:50:20'),
(74, 'Patient', 'PAT002', 'Login', '2025-02-03 05:01:13'),
(75, 'Patient', 'PAT005', 'Login', '2025-02-03 05:13:08'),
(76, 'Patient', 'PAT005', 'Login', '2025-02-03 05:15:02'),
(77, 'Patient', 'PAT005', 'Logout', '2025-02-03 05:15:11'),
(78, 'Patient', 'PAT005', 'Login', '2025-02-03 05:15:18'),
(79, 'Patient', 'PAT005', 'Logout', '2025-02-03 05:15:51'),
(80, 'Patient', 'PAT002', 'Login', '2025-02-03 05:15:57'),
(81, 'Patient', 'PAT002', 'Logout', '2025-02-03 05:21:13'),
(82, 'Patient', 'PAT005', 'Login', '2025-02-03 05:21:20'),
(83, 'Patient', 'PAT005', 'Logout', '2025-02-03 06:13:28'),
(84, 'Patient', 'PAT002', 'Login', '2025-02-03 06:13:35'),
(85, 'Patient', 'PAT002', 'Logout', '2025-02-03 06:17:46'),
(86, 'Patient', 'PAT005', 'Login', '2025-02-03 06:17:53'),
(87, 'Patient', 'PAT005', 'Logout', '2025-02-03 06:18:30'),
(88, 'Patient', 'PAT002', 'Login', '2025-02-03 06:18:38'),
(89, 'Patient', 'PAT002', 'Logout', '2025-02-03 06:18:45'),
(90, 'Patient', 'PAT003', 'Login', '2025-02-03 06:19:14'),
(91, 'Patient', 'PAT003', 'Logout', '2025-02-03 08:06:00'),
(92, 'Patient', 'PAT002', 'Login', '2025-02-03 08:06:07'),
(93, 'Patient', 'PAT002', 'Logout', '2025-02-03 08:08:41'),
(94, 'Patient', 'PAT005', 'Login', '2025-02-03 08:08:50'),
(95, 'Patient', 'PAT005', 'Logout', '2025-02-03 08:08:56'),
(96, 'Patient', 'PAT002', 'Login', '2025-02-03 08:09:04'),
(97, 'Patient', 'PAT002', 'Logout', '2025-02-03 08:09:48'),
(98, 'Patient', 'PAT005', 'Login', '2025-02-03 08:10:00'),
(99, 'Patient', 'PAT005', 'Logout', '2025-02-03 08:11:30'),
(100, 'Patient', 'PAT002', 'Login', '2025-02-03 08:11:38'),
(101, 'Patient', 'PAT002', 'Logout', '2025-02-03 08:25:18'),
(102, 'Admin', 'ADMID001', 'Login', '2025-02-03 08:25:32'),
(103, 'Admin', 'ADMID001', 'Logout', '2025-02-03 08:25:36'),
(104, 'BillingSpecialist', 'SPEID001', 'Login', '2025-02-03 08:25:55'),
(105, 'BillingSpecialist', 'SPEID001', 'Logout', '2025-02-03 08:25:57'),
(106, 'Dentist', 'DENID001', 'Login', '2025-02-03 08:26:14'),
(107, 'Dentist', 'DENID001', 'Logout', '2025-02-03 08:26:18'),
(108, 'Assistant', 'DASID001', 'Login', '2025-02-03 08:26:32'),
(109, 'Assistant', 'DASID001', 'Logout', '2025-02-03 08:27:05'),
(110, 'Patient', 'PAT002', 'Login', '2025-02-03 08:27:16'),
(111, 'Patient', 'PAT002', 'Login', '2025-02-03 08:27:26'),
(112, 'Dentist', 'DENID001', 'Login', '2025-02-03 08:27:56'),
(113, 'Patient', 'PAT002', 'Login', '2025-02-04 09:10:43'),
(114, 'Patient', 'PAT002', 'Login', '2025-02-04 09:11:12'),
(115, 'Patient', 'PAT002', 'Logout', '2025-02-04 09:33:52'),
(116, 'Patient', 'PAT005', 'Login', '2025-02-04 09:33:58'),
(117, 'Patient', 'PAT005', 'Logout', '2025-02-04 09:34:08'),
(118, 'Patient', 'PAT002', 'Login', '2025-02-04 09:34:14'),
(119, 'Patient', 'PAT002', 'Login', '2025-02-04 10:49:16'),
(120, 'Patient', 'PAT002', 'Login', '2025-02-04 10:51:03'),
(121, 'Patient', 'PAT002', 'Login', '2025-02-04 11:10:06'),
(122, 'Dentist', 'DENID001', 'Login', '2025-02-04 11:10:52'),
(123, 'Dentist', 'DENID001', 'Login', '2025-02-04 11:12:20'),
(124, 'Dentist', 'DENID001', 'Login', '2025-02-04 11:14:07'),
(125, 'Dentist', 'DENID001', 'Logout', '2025-02-04 11:39:24'),
(126, 'Dentist', 'DENID002', 'Login', '2025-02-04 11:39:46'),
(127, 'Dentist', 'DENID002', 'Login', '2025-02-04 11:45:39'),
(128, 'Patient', 'PAT002', 'Login', '2025-02-04 23:45:03'),
(129, 'Dentist', 'DENID002', 'Login', '2025-02-05 00:25:32'),
(130, 'Dentist', 'DENID002', 'Logout', '2025-02-05 01:28:07'),
(131, 'Patient', 'PAT005', 'Login', '2025-02-05 01:28:13'),
(132, 'Patient', 'PAT005', 'Logout', '2025-02-05 01:28:20'),
(133, 'Dentist', 'DENID002', 'Login', '2025-02-05 01:29:09'),
(134, 'Dentist', 'DENID002', 'Logout', '2025-02-05 02:41:16'),
(135, 'Dentist', 'DENID002', 'Login', '2025-02-05 02:41:27'),
(136, 'Dentist', 'DENID002', 'Logout', '2025-02-05 04:28:09'),
(137, 'Patient', 'PAT005', 'Login', '2025-02-05 04:28:29'),
(138, 'Dentist', 'DENID002', 'Login', '2025-02-05 04:28:41'),
(139, 'Dentist', 'DENID002', 'Login', '2025-02-05 04:32:55'),
(140, 'Dentist', 'DENID002', 'Logout', '2025-02-05 04:33:43'),
(141, 'Patient', 'PAT005', 'Login', '2025-02-05 04:33:49'),
(142, 'Patient', 'PAT005', 'Logout', '2025-02-05 04:35:10'),
(143, 'Dentist', 'DENID002', 'Logout', '2025-02-05 04:35:12'),
(144, 'Patient', 'PAT005', 'Login', '2025-02-05 04:35:20'),
(145, 'Dentist', 'DENID002', 'Login', '2025-02-05 04:35:35'),
(146, 'Dentist', 'DENID002', 'Logout', '2025-02-05 04:41:41'),
(147, 'Dentist', 'DENID001', 'Login', '2025-02-05 04:41:50'),
(148, 'Dentist', 'DENID001', 'Logout', '2025-02-05 04:41:54'),
(149, 'Dentist', 'DENID001', 'Login', '2025-02-05 04:42:14'),
(150, 'Patient', 'PAT005', 'Login', '2025-02-05 05:26:51'),
(151, 'Dentist', 'DENID001', 'Logout', '2025-02-05 05:30:53'),
(152, 'Patient', 'PAT005', 'Login', '2025-02-05 05:31:05'),
(153, 'Dentist', 'DENID001', 'Login', '2025-02-05 05:31:27'),
(154, 'Patient', 'PAT005', 'Logout', '2025-02-05 05:33:34'),
(155, 'Dentist', 'DENID001', 'Logout', '2025-02-05 05:46:44'),
(156, 'Patient', 'PAT005', 'Login', '2025-02-05 05:46:49'),
(157, 'Patient', 'PAT005', 'Logout', '2025-02-05 05:49:07'),
(158, 'Dentist', 'DENID001', 'Login', '2025-02-05 05:49:15'),
(159, 'Patient', 'PAT004', 'Login', '2025-02-05 05:49:40'),
(160, 'Dentist', 'DENID001', 'Logout', '2025-02-05 05:52:43'),
(161, 'Dentist', 'DENID002', 'Login', '2025-02-05 05:54:51'),
(162, 'Patient', 'PAT004', 'Logout', '2025-02-05 05:55:15'),
(163, 'Dentist', 'DENID002', 'Logout', '2025-02-05 05:55:16'),
(164, 'Patient', 'PAT005', 'Login', '2025-02-06 03:16:11'),
(165, 'Dentist', 'DENID001', 'Login', '2025-02-06 03:16:39'),
(166, 'Dentist', 'DENID001', 'Logout', '2025-02-06 03:18:09'),
(167, 'Patient', 'PAT005', 'Login', '2025-02-06 03:18:17'),
(168, 'Patient', 'PAT005', 'Logout', '2025-02-06 03:19:14'),
(169, 'Patient', 'PAT002', 'Login', '2025-02-06 03:19:20'),
(170, 'Dentist', 'DENID001', 'Login', '2025-02-06 03:19:33'),
(171, 'Dentist', 'DENID001', 'Logout', '2025-02-06 03:24:23'),
(172, 'Patient', 'PAT002', 'Login', '2025-02-06 03:24:30'),
(173, 'Patient', 'PAT002', 'Logout', '2025-02-06 03:28:09'),
(174, 'Patient', 'PAT002', 'Logout', '2025-02-06 03:28:14'),
(175, 'Patient', 'PAT005', 'Login', '2025-02-06 03:35:42'),
(176, 'Patient', 'PAT005', 'Logout', '2025-02-06 03:39:50'),
(177, 'Patient', 'PAT005', 'Login', '2025-02-06 03:40:11'),
(178, 'Patient', 'PAT005', 'Logout', '2025-02-06 03:50:56'),
(179, 'Patient', 'PAT002', 'Login', '2025-02-06 03:53:18'),
(180, 'Patient', 'PAT002', 'Logout', '2025-02-06 03:54:04'),
(181, 'Patient', 'PAT002', 'Login', '2025-02-06 04:15:45'),
(182, 'Patient', 'PAT002', 'Logout', '2025-02-06 04:15:53'),
(183, 'Patient', 'PAT002', 'Login', '2025-02-06 04:17:54'),
(184, 'Patient', 'PAT002', 'Logout', '2025-02-06 04:18:11'),
(185, 'Patient', 'PAT006', 'Login', '2025-02-06 04:19:44'),
(186, 'Dentist', 'DENID001', 'Login', '2025-02-06 04:35:09'),
(187, 'Patient', 'PAT006', 'Logout', '2025-02-06 04:36:32'),
(188, 'Patient', 'PAT002', 'Login', '2025-02-06 04:36:39'),
(189, 'Patient', 'PAT002', 'Logout', '2025-02-06 04:45:56'),
(190, 'Patient', 'PAT002', 'Login', '2025-02-06 04:46:33'),
(191, 'Patient', 'PAT002', 'Login', '2025-02-09 03:13:33'),
(192, 'Patient', 'PAT002', 'Login', '2025-02-09 08:15:16'),
(193, 'Dentist', 'DENID001', 'Login', '2025-02-09 08:18:48'),
(194, 'Patient', 'PAT002', 'Login', '2025-02-09 08:20:19'),
(195, 'Patient', 'PAT002', 'Logout', '2025-02-09 08:20:20'),
(196, 'Patient', 'PAT002', 'Login', '2025-02-09 08:20:33'),
(197, 'Dentist', 'DENID001', 'Login', '2025-02-09 08:20:58'),
(198, 'Dentist', 'DENID001', 'Logout', '2025-02-09 08:21:09'),
(199, 'Patient', 'PAT002', 'Login', '2025-02-09 08:24:42'),
(200, 'Patient', 'PAT002', 'Login', '2025-02-11 08:55:16'),
(201, 'Patient', 'PAT002', 'Logout', '2025-02-11 10:44:51'),
(202, 'Patient', 'PAT002', 'Login', '2025-02-11 10:45:01'),
(203, 'Patient', 'PAT002', 'Logout', '2025-02-11 12:06:34'),
(204, 'Patient', 'PAT002', 'Login', '2025-02-11 12:06:40'),
(205, 'Patient', 'PAT002', 'Logout', '2025-02-11 12:08:11'),
(206, 'Patient', 'PAT002', 'Login', '2025-02-11 12:08:20'),
(207, 'Patient', 'PAT002', 'Login', '2025-02-12 23:58:55'),
(208, 'Patient', 'PAT002', 'Logout', '2025-02-13 00:04:46'),
(209, 'Patient', 'PAT002', 'Login', '2025-02-13 00:04:51'),
(210, 'Patient', 'PAT002', 'Login', '2025-02-13 04:04:36'),
(211, 'Patient', 'PAT002', 'Login', '2025-02-13 06:40:58'),
(212, 'Patient', 'PAT008', 'Login', '2025-02-13 11:08:14'),
(213, 'Patient', 'PAT008', 'Logout', '2025-02-13 11:08:23'),
(214, 'Patient', 'PAT008', 'Login', '2025-02-13 11:12:51'),
(215, 'Patient', 'PAT008', 'Login', '2025-02-13 11:15:43'),
(216, 'Patient', 'PAT008', 'Logout', '2025-02-13 11:38:26'),
(217, 'Patient', 'PAT008', 'Login', '2025-02-13 11:38:34'),
(218, 'Patient', 'PAT002', 'Login', '2025-02-14 04:36:16'),
(219, 'Patient', 'PAT002', 'Logout', '2025-02-14 04:39:50'),
(220, 'Patient', 'PAT008', 'Login', '2025-02-14 04:40:54'),
(221, 'Patient', 'PAT008', 'Login', '2025-02-14 04:49:52'),
(222, 'Patient', 'PAT009', 'Login', '2025-02-14 04:53:14'),
(223, 'Patient', 'PAT009', 'Logout', '2025-02-14 04:58:01'),
(224, 'Patient', 'PAT002', 'Login', '2025-02-14 05:11:14'),
(225, 'Patient', 'PAT002', 'Login', '2025-02-14 08:49:24'),
(226, 'Patient', 'PAT002', 'Logout', '2025-02-14 08:50:15'),
(227, 'Patient', 'PAT008', 'Login', '2025-02-14 08:50:25'),
(228, 'Patient', 'PAT008', 'Logout', '2025-02-14 08:50:35'),
(229, 'Patient', 'PAT002', 'Login', '2025-02-14 08:50:49'),
(230, 'Dentist', 'DENID001', 'Login', '2025-02-14 08:51:17'),
(231, 'Dentist', 'DENID001', 'Logout', '2025-02-14 08:51:37'),
(232, 'Patient', 'PAT002', 'Login', '2025-02-14 12:46:29'),
(233, 'Patient', 'PAT010', 'Login', '2025-02-14 14:01:30'),
(234, 'Patient', 'PAT010', 'Logout', '2025-02-14 14:02:56'),
(235, 'Patient', 'PAT011', 'Login', '2025-02-14 14:03:34'),
(236, 'Patient', 'PAT002', 'Login', '2025-02-14 19:40:28'),
(237, 'Patient', 'PAT002', 'Logout', '2025-02-14 20:28:58'),
(238, 'Patient', 'PAT008', 'Login', '2025-02-14 20:29:04'),
(239, 'Patient', 'PAT008', 'Logout', '2025-02-14 20:45:00'),
(240, 'Patient', 'PAT002', 'Login', '2025-02-14 20:45:09'),
(241, 'Patient', 'PAT002', 'Logout', '2025-02-14 21:31:49'),
(242, 'Patient', 'PAT002', 'Login', '2025-02-15 07:56:57'),
(243, 'Patient', 'PAT002', 'Logout', '2025-02-15 10:38:57'),
(244, 'Patient', 'PAT008', 'Login', '2025-02-15 10:39:02'),
(245, 'Patient', 'PAT002', 'Login', '2025-02-15 18:04:00'),
(246, 'Patient', 'PAT002', 'Logout', '2025-02-15 18:06:52'),
(247, 'Patient', 'PAT002', 'Login', '2025-02-15 18:19:36'),
(248, 'Patient', 'PAT002', 'Login', '2025-02-15 20:37:39'),
(249, 'Patient', 'PAT002', 'Logout', '2025-02-15 21:16:27'),
(250, 'Patient', 'PAT008', 'Login', '2025-02-15 21:16:34'),
(251, 'Patient', 'PAT008', 'Logout', '2025-02-15 22:00:21'),
(252, 'Patient', 'PAT012', 'Login', '2025-02-16 12:06:40'),
(253, 'BillingSpecialist', 'SPEID002', 'Login', '2025-02-16 23:20:52'),
(254, 'Patient', 'PAT002', 'Login', '2025-02-16 23:36:52'),
(255, 'Patient', 'PAT002', 'Logout', '2025-02-17 00:26:04'),
(256, 'Patient', 'PAT002', 'Login', '2025-02-17 02:00:23'),
(257, 'BillingSpecialist', 'SPEID002', 'Logout', '2025-02-17 02:42:03'),
(258, 'Patient', 'PAT002', 'Login', '2025-02-17 02:42:11'),
(259, 'Patient', 'PAT002', 'Login', '2025-02-17 02:42:49'),
(260, 'Patient', 'PAT002', 'Logout', '2025-02-17 02:43:12'),
(261, 'BillingSpecialist', 'SPEID002', 'Login', '2025-02-17 02:43:48'),
(262, 'BillingSpecialist', 'SPEID002', 'Logout', '2025-02-17 02:54:04'),
(263, 'Patient', 'PAT002', 'Login', '2025-02-17 02:54:10'),
(264, 'Patient', 'PAT002', 'Logout', '2025-02-17 02:54:20'),
(265, 'BillingSpecialist', 'SPEID002', 'Login', '2025-02-17 02:54:35'),
(266, 'BillingSpecialist', 'SPEID002', 'Login', '2025-02-17 03:01:23'),
(267, 'BillingSpecialist', 'SPEID002', 'Logout', '2025-02-17 04:38:47'),
(268, 'Patient', 'PAT002', 'Login', '2025-02-17 04:38:56'),
(269, 'Patient', 'PAT002', 'Logout', '2025-02-17 05:08:18'),
(270, 'BillingSpecialist', 'SPEID002', 'Login', '2025-02-17 05:08:34'),
(271, 'Patient', 'PAT002', 'Login', '2025-02-17 05:31:58'),
(272, 'Patient', 'PAT002', 'Login', '2025-02-17 07:02:18'),
(273, 'BillingSpecialist', 'SPEID002', 'Login', '2025-02-17 07:49:45'),
(274, 'BillingSpecialist', 'SPEID002', 'Logout', '2025-02-17 07:51:11'),
(275, 'Patient', 'PAT002', 'Login', '2025-02-17 07:51:17'),
(276, 'Patient', 'PAT002', 'Logout', '2025-02-17 07:58:36'),
(277, 'Patient', 'PAT008', 'Login', '2025-02-17 07:58:43'),
(278, 'Patient', 'PAT008', 'Logout', '2025-02-17 07:59:58'),
(279, 'Patient', 'PAT002', 'Login', '2025-02-17 08:00:07'),
(280, 'Patient', 'PAT008', 'Login', '2025-02-17 08:01:56'),
(281, 'Patient', 'PAT002', 'Logout', '2025-02-17 08:23:47'),
(282, 'BillingSpecialist', 'SPEID002', 'Login', '2025-02-17 08:24:19'),
(283, 'Patient', 'PAT002', 'Login', '2025-02-17 08:28:50'),
(284, 'BillingSpecialist', 'SPEID002', 'Logout', '2025-02-17 09:13:37'),
(285, 'Patient', 'PAT002', 'Login', '2025-02-17 09:13:41'),
(286, 'Patient', 'PAT002', 'Logout', '2025-02-17 09:15:40'),
(287, 'BillingSpecialist', 'SPEID002', 'Login', '2025-02-17 09:16:10'),
(288, 'BillingSpecialist', 'SPEID002', 'Logout', '2025-02-17 09:26:21'),
(289, 'BillingSpecialist', 'SPEID002', 'Login', '2025-02-17 09:26:45'),
(290, 'BillingSpecialist', 'SPEID002', 'Logout', '2025-02-17 09:42:32'),
(291, 'Patient', 'PAT002', 'Login', '2025-02-17 09:42:37'),
(292, 'Patient', 'PAT002', 'Login', '2025-02-17 10:02:35'),
(293, 'Patient', 'PAT002', 'Logout', '2025-02-17 10:18:27'),
(294, 'BillingSpecialist', 'SPEID002', 'Login', '2025-02-17 10:18:41'),
(295, 'BillingSpecialist', 'SPEID002', 'Logout', '2025-02-17 10:27:09'),
(296, 'BillingSpecialist', 'SPEID002', 'Login', '2025-02-17 10:38:37'),
(297, 'BillingSpecialist', 'SPEID002', 'Login', '2025-02-17 10:48:37'),
(298, 'BillingSpecialist', 'SPEID002', 'Logout', '2025-02-17 10:53:14'),
(299, 'BillingSpecialist', 'SPEID002', 'Login', '2025-02-17 10:53:26'),
(300, 'BillingSpecialist', 'SPEID002', 'Logout', '2025-02-17 10:54:31'),
(301, 'Patient', 'PAT002', 'Login', '2025-02-17 10:54:36'),
(302, 'Patient', 'PAT002', 'Logout', '2025-02-17 11:25:03'),
(303, 'Patient', 'PAT002', 'Login', '2025-02-17 11:25:10'),
(304, 'Assistant', 'DASID001', 'Login', '2025-02-18 19:10:29'),
(305, 'Assistant', 'DASID001', 'Login', '2025-02-19 20:57:30'),
(306, 'Patient', 'PAT002', 'Login', '2025-02-19 21:05:13'),
(307, 'Assistant', 'DASID001', 'Login', '2025-02-20 23:29:24'),
(308, 'Assistant', 'DASID001', 'Login', '2025-02-20 23:29:37'),
(309, 'BillingSpecialist', 'SPEID001', 'Login', '2025-02-21 00:33:53'),
(310, 'Assistant', 'DASID001', 'Login', '2025-02-21 15:30:06'),
(311, 'Patient', 'PAT002', 'Login', '2025-02-21 16:46:04'),
(312, 'Patient', 'PAT002', 'Logout', '2025-02-21 20:46:38'),
(313, 'BillingSpecialist', 'SPEID001', 'Login', '2025-02-21 21:36:02'),
(314, 'BillingSpecialist', 'SPEID001', 'Logout', '2025-02-21 21:45:17'),
(315, 'BillingSpecialist', 'SPEID001', 'Login', '2025-02-21 21:45:25'),
(316, 'BillingSpecialist', 'SPEID001', 'Logout', '2025-02-21 21:54:02'),
(317, 'Assistant', 'DASID001', 'Login', '2025-02-21 21:54:48'),
(318, 'Assistant', 'DASID001', 'Logout', '2025-02-21 22:12:02'),
(319, 'BillingSpecialist', 'SPEID001', 'Login', '2025-02-21 22:12:14'),
(320, 'BillingSpecialist', 'SPEID001', 'Logout', '2025-02-21 22:16:16'),
(321, 'Assistant', 'DASID001', 'Login', '2025-02-21 22:16:29'),
(322, 'Assistant', 'DASID001', 'Logout', '2025-02-22 17:19:52'),
(323, 'Assistant', 'DASID001', 'Login', '2025-02-22 17:20:02'),
(324, 'Assistant', 'DASID001', 'Logout', '2025-02-22 19:50:51'),
(325, 'BillingSpecialist', 'SPEID001', 'Login', '2025-02-22 19:51:00'),
(326, 'BillingSpecialist', 'SPEID001', 'Logout', '2025-02-22 19:51:51'),
(327, 'Patient', 'PAT002', 'Login', '2025-02-22 19:52:04'),
(328, 'Dentist', 'DENID001', 'Login', '2025-02-23 19:01:49'),
(329, 'Dentist', 'DENID001', 'Logout', '2025-02-23 19:04:36'),
(330, 'Patient', 'PAT002', 'Login', '2025-02-23 19:04:40'),
(331, 'Patient', 'PAT002', 'Logout', '2025-02-23 19:05:23'),
(332, 'Dentist', 'DENID001', 'Login', '2025-02-23 19:47:14'),
(333, 'Dentist', 'DENID001', 'Logout', '2025-02-23 20:00:47'),
(334, 'Patient', 'PAT002', 'Login', '2025-02-23 20:00:52'),
(335, 'Patient', 'PAT002', 'Logout', '2025-02-23 20:02:15'),
(336, 'Dentist', 'DENID001', 'Login', '2025-02-23 20:02:26'),
(337, 'Dentist', 'DENID001', 'Login', '2025-02-23 20:02:40'),
(338, 'Dentist', 'DENID001', 'Logout', '2025-02-23 20:22:58'),
(339, 'Assistant', 'DASID001', 'Login', '2025-02-23 20:23:27'),
(340, 'Assistant', 'DASID001', 'Logout', '2025-02-23 20:23:56'),
(341, 'Dentist', 'DENID001', 'Login', '2025-02-23 20:29:32'),
(342, 'Dentist', 'DENID001', 'Logout', '2025-02-23 22:41:59'),
(343, 'Patient', 'PAT002', 'Login', '2025-02-23 22:42:07'),
(344, 'Patient', 'PAT002', 'Logout', '2025-02-23 22:42:34'),
(345, 'Assistant', 'DASID001', 'Login', '2025-02-23 22:42:50'),
(346, 'Assistant', 'DASID001', 'Logout', '2025-02-23 22:43:42'),
(347, 'Dentist', 'DENID001', 'Login', '2025-02-23 22:47:08'),
(348, 'Dentist', 'DENID001', 'Logout', '2025-02-24 03:10:49'),
(349, 'Patient', 'PAT002', 'Login', '2025-02-24 03:10:56'),
(350, 'Patient', 'PAT002', 'Logout', '2025-02-24 03:23:49'),
(351, 'Dentist', 'DENID001', 'Login', '2025-02-24 03:24:03'),
(352, 'Dentist', 'DENID001', 'Logout', '2025-02-24 04:01:52'),
(353, 'Patient', 'PAT002', 'Login', '2025-02-24 04:01:58'),
(354, 'Patient', 'PAT002', 'Logout', '2025-02-24 04:06:59'),
(355, 'Patient', 'PAT005', 'Login', '2025-02-24 04:07:11'),
(356, 'Patient', 'PAT002', 'Login', '2025-02-24 04:07:53'),
(357, 'Patient', 'PAT002', 'Logout', '2025-02-24 04:10:32'),
(358, 'Dentist', 'DENID001', 'Login', '2025-02-24 04:10:52'),
(359, 'Dentist', 'DENID001', 'Login', '2025-02-24 04:12:23'),
(360, 'Patient', 'PAT002', 'Login', '2025-02-24 16:56:13'),
(361, 'Patient', 'PAT002', 'Login', '2025-02-24 17:01:15'),
(362, 'Patient', 'PAT002', 'Login', '2025-02-24 17:03:43'),
(363, 'Patient', 'PAT002', 'Logout', '2025-02-24 17:03:46'),
(364, 'Patient', 'PAT002', 'Login', '2025-02-24 17:04:05'),
(365, 'Patient', 'PAT002', 'Login', '2025-02-24 17:12:55'),
(366, 'Patient', 'PAT002', 'Logout', '2025-02-24 17:13:37'),
(367, 'Assistant', 'DASID001', 'Login', '2025-02-26 00:03:09'),
(368, 'Assistant', 'DASID001', 'Logout', '2025-02-26 00:03:33'),
(369, 'Dentist', 'DENID001', 'Login', '2025-02-26 00:08:34'),
(370, 'Dentist', 'DENID001', 'Logout', '2025-02-26 00:17:38'),
(371, 'Dentist', 'DENID003', 'Login', '2025-02-26 00:18:00'),
(372, 'Dentist', 'DENID003', 'Logout', '2025-02-26 00:18:13'),
(373, 'Dentist', 'DENID001', 'Login', '2025-02-26 00:18:21'),
(374, 'Dentist', 'DENID001', 'Login', '2025-02-26 00:25:29'),
(375, 'Dentist', 'DENID001', 'Logout', '2025-02-26 00:42:06'),
(376, 'Dentist', 'DENID001', 'Login', '2025-02-26 00:42:35'),
(377, 'Dentist', 'DENID001', 'Logout', '2025-02-26 00:42:38'),
(378, 'Dentist', 'DENID001', 'Login', '2025-02-26 00:42:47'),
(379, 'Dentist', 'DENID001', 'Logout', '2025-02-26 00:42:52'),
(380, 'Dentist', 'DENID001', 'Login', '2025-02-26 00:43:03'),
(381, 'Dentist', 'DENID001', 'Logout', '2025-02-26 00:46:11'),
(382, 'Dentist', 'DENID001', 'Login', '2025-02-26 00:46:31'),
(383, 'Dentist', 'DENID001', 'Login', '2025-02-26 00:48:47'),
(384, 'Dentist', 'DENID001', 'Logout', '2025-02-26 00:48:57'),
(385, 'Dentist', 'DENID001', 'Login', '2025-02-26 00:49:07'),
(386, 'Dentist', 'DENID001', 'Logout', '2025-02-26 00:49:23'),
(387, 'Dentist', 'DENID001', 'Login', '2025-02-26 00:54:19'),
(388, 'Dentist', 'DENID001', 'Logout', '2025-02-26 01:03:05'),
(389, 'Dentist', 'DENID001', 'Login', '2025-02-26 01:03:15'),
(390, 'Dentist', 'DENID001', 'Logout', '2025-02-26 01:03:23'),
(391, 'Dentist', 'DENID001', 'Login', '2025-02-26 01:03:31'),
(392, 'Dentist', 'DENID001', 'Logout', '2025-02-26 01:06:34'),
(393, 'Dentist', 'DENID001', 'Login', '2025-02-26 01:06:52'),
(394, 'Dentist', 'DENID001', 'Logout', '2025-02-26 01:07:50'),
(395, 'Dentist', 'DENID001', 'Login', '2025-02-26 01:08:02'),
(396, 'Dentist', 'DENID001', 'Logout', '2025-02-26 01:08:53'),
(397, 'Dentist', 'DENID001', 'Login', '2025-02-26 01:09:07'),
(398, 'Dentist', 'DENID001', 'Logout', '2025-02-26 01:10:01'),
(399, 'Dentist', 'DENID001', 'Login', '2025-02-26 01:10:11'),
(400, 'Dentist', 'DENID001', 'Logout', '2025-02-26 01:10:17'),
(401, 'Dentist', 'DENID001', 'Login', '2025-02-26 01:38:42'),
(402, 'Dentist', 'DENID001', 'Logout', '2025-02-26 01:53:42'),
(403, 'Assistant', 'DASID001', 'Login', '2025-02-26 01:54:03'),
(404, 'Assistant', 'DASID001', 'Logout', '2025-02-26 02:01:56'),
(405, 'Dentist', 'DENID001', 'Login', '2025-02-26 02:02:03'),
(406, 'Dentist', 'DENID001', 'Logout', '2025-02-26 04:12:15'),
(407, 'Assistant', 'DASID001', 'Login', '2025-02-26 04:12:25'),
(408, 'Assistant', 'DASID001', 'Logout', '2025-02-26 04:45:47'),
(409, 'Dentist', 'DENID001', 'Login', '2025-02-26 04:46:01'),
(410, 'Dentist', 'DENID001', 'Login', '2025-02-26 04:47:48'),
(411, 'Dentist', 'DENID001', 'Login', '2025-02-26 04:48:30'),
(412, 'Dentist', 'DENID001', 'Login', '2025-02-26 04:50:23'),
(413, 'Dentist', 'DENID001', 'Login', '2025-02-26 09:28:11'),
(414, 'Dentist', 'DENID001', 'Login', '2025-02-26 09:32:48'),
(415, 'Dentist', 'DENID001', 'Logout', '2025-02-26 09:42:18'),
(416, 'Dentist', 'DENID001', 'Login', '2025-02-26 09:42:37'),
(417, 'Dentist', 'DENID001', 'Logout', '2025-02-26 09:42:46'),
(418, 'Dentist', 'DENID001', 'Login', '2025-02-26 09:42:55'),
(419, 'Dentist', 'DENID001', 'Login', '2025-02-26 19:46:43'),
(420, 'Dentist', 'DENID001', 'Login', '2025-02-26 20:13:46'),
(421, 'Dentist', 'DENID001', 'Login', '2025-02-26 20:14:07'),
(422, 'Dentist', 'DENID001', 'Login', '2025-02-26 21:02:40'),
(423, 'Dentist', 'DENID001', 'Login', '2025-02-26 21:04:27'),
(424, 'Dentist', 'DENID001', 'Login', '2025-02-26 21:05:12'),
(425, 'Dentist', 'DENID001', 'Login', '2025-02-27 10:43:22'),
(426, 'Dentist', 'DENID001', 'Logout', '2025-02-27 10:51:39'),
(427, 'Assistant', 'DASID001', 'Login', '2025-02-27 10:51:54'),
(428, 'Assistant', 'DASID001', 'Logout', '2025-02-27 10:53:04'),
(429, 'Dentist', 'DENID001', 'Login', '2025-02-27 10:53:11'),
(430, 'Dentist', 'DENID001', 'Logout', '2025-02-27 10:57:39'),
(431, 'Dentist', 'DENID001', 'Login', '2025-02-27 10:57:45'),
(432, 'Dentist', 'DENID001', 'Logout', '2025-02-27 11:12:45'),
(433, 'Assistant', 'DASID001', 'Login', '2025-02-27 11:13:04'),
(434, 'Assistant', 'DASID001', 'Logout', '2025-02-27 11:16:55'),
(435, 'Dentist', 'DENID001', 'Login', '2025-02-27 11:17:23'),
(436, 'Dentist', 'DENID001', 'Login', '2025-02-27 12:46:53'),
(437, 'Dentist', 'DENID001', 'Login', '2025-02-27 12:49:40'),
(438, 'Dentist', 'DENID001', 'Login', '2025-02-27 12:50:00'),
(439, 'Assistant', 'DASID001', 'Login', '2025-02-27 13:17:12'),
(440, 'Assistant', 'DASID001', 'Logout', '2025-02-27 13:20:53'),
(441, 'Patient', 'PAT002', 'Login', '2025-02-28 00:59:41'),
(442, 'Patient', 'PAT002', 'Login', '2025-02-28 04:19:24'),
(443, 'Assistant', 'DASID001', 'Login', '2025-02-28 04:30:35'),
(444, 'Patient', 'PAT002', 'Logout', '2025-02-28 04:36:30'),
(445, 'Patient', 'PAT013', 'Login', '2025-02-28 04:39:16'),
(446, 'Assistant', 'DASID001', 'Logout', '2025-02-28 04:54:00'),
(447, 'Dentist', 'DENID001', 'Login', '2025-02-28 04:54:13'),
(448, 'Dentist', 'DENID001', 'Logout', '2025-02-28 05:00:23'),
(449, 'Dentist', 'DENID001', 'Login', '2025-02-28 05:00:31'),
(450, 'Patient', 'PAT013', 'Logout', '2025-02-28 05:08:57'),
(451, 'Dentist', 'DENID001', 'Login', '2025-02-28 05:09:10'),
(452, 'Dentist', 'DENID001', 'Logout', '2025-02-28 05:11:41'),
(453, 'Patient', 'PAT013', 'Login', '2025-02-28 05:11:58'),
(454, 'BillingSpecialist', 'SPEID001', 'Login', '2025-02-28 05:14:57'),
(455, 'BillingSpecialist', 'SPEID001', 'Logout', '2025-02-28 05:28:07'),
(456, 'Patient', 'PAT002', 'Login', '2025-02-28 05:28:21'),
(457, 'Patient', 'PAT002', 'Logout', '2025-02-28 06:07:29'),
(458, 'Dentist', 'DENID001', 'Login', '2025-02-28 06:16:28'),
(459, 'Dentist', 'DENID001', 'Logout', '2025-02-28 06:22:44'),
(460, 'Assistant', 'DASID001', 'Login', '2025-02-28 06:22:59'),
(461, 'Assistant', 'DASID001', 'Logout', '2025-02-28 06:31:06'),
(462, 'BillingSpecialist', 'SPEID001', 'Login', '2025-02-28 06:32:33'),
(463, 'BillingSpecialist', 'SPEID001', 'Logout', '2025-02-28 06:55:16'),
(464, 'Dentist', 'DENID001', 'Login', '2025-02-28 07:19:46'),
(465, 'Dentist', 'DENID001', 'Logout', '2025-02-28 07:33:59'),
(466, 'Patient', 'PAT002', 'Login', '2025-02-28 07:34:10'),
(467, 'Patient', 'PAT002', 'Logout', '2025-02-28 07:36:17'),
(468, 'Patient', 'PAT002', 'Login', '2025-02-28 07:40:15'),
(469, 'Dentist', 'DENID001', 'Login', '2025-02-28 07:54:04'),
(470, 'Dentist', 'DENID001', 'Logout', '2025-02-28 08:04:05'),
(471, 'Assistant', 'DASID001', 'Login', '2025-02-28 08:04:26'),
(472, 'Assistant', 'DASID001', 'Logout', '2025-02-28 08:08:11'),
(473, 'Dentist', 'DENID001', 'Login', '2025-02-28 08:08:25'),
(474, 'Dentist', 'DENID001', 'Logout', '2025-02-28 08:26:08'),
(475, 'Patient', 'PAT002', 'Login', '2025-02-28 09:46:10'),
(476, 'Patient', 'PAT002', 'Logout', '2025-02-28 09:47:35'),
(477, 'Dentist', 'DENID001', 'Login', '2025-03-01 06:27:43'),
(478, 'Dentist', 'DENID001', 'Logout', '2025-03-01 06:28:51'),
(479, 'Patient', 'PAT002', 'Login', '2025-03-01 07:23:59'),
(480, 'Patient', 'PAT002', 'Logout', '2025-03-01 08:43:53'),
(481, 'Assistant', 'DASID001', 'Login', '2025-03-01 08:44:16'),
(482, 'Assistant', 'DASID001', 'Logout', '2025-03-01 08:44:29'),
(483, 'Dentist', 'DENID001', 'Login', '2025-03-01 08:44:40'),
(484, 'Dentist', 'DENID001', 'Logout', '2025-03-01 08:44:58'),
(485, 'Patient', 'PAT013', 'Login', '2025-03-01 09:08:32'),
(486, 'Patient', 'PAT013', 'Logout', '2025-03-01 09:14:05'),
(487, 'Patient', 'PAT002', 'Login', '2025-03-01 09:14:12'),
(488, 'Assistant', 'DASID001', 'Login', '2025-03-01 09:23:17'),
(489, 'Patient', 'PAT002', 'Login', '2025-03-01 09:24:51'),
(490, 'Patient', 'PAT002', 'Logout', '2025-03-01 09:35:18'),
(491, 'Assistant', 'DASID001', 'Login', '2025-03-01 09:35:31'),
(492, 'Assistant', 'DASID001', 'Logout', '2025-03-01 09:38:34'),
(493, 'Assistant', 'DASID001', 'Login', '2025-03-01 09:38:58'),
(494, 'Assistant', 'DASID001', 'Login', '2025-03-01 09:39:43'),
(495, 'Assistant', 'DASID001', 'Logout', '2025-03-01 10:05:30'),
(496, 'Dentist', 'DENID001', 'Login', '2025-03-01 10:06:03'),
(497, 'Dentist', 'DENID001', 'Logout', '2025-03-01 10:55:37'),
(498, 'BillingSpecialist', 'SPEID001', 'Login', '2025-03-01 10:56:02'),
(499, 'Patient', 'PAT014', 'Login', '2025-03-01 12:01:09'),
(500, 'Assistant', 'DASID002', 'Login', '2025-03-01 12:18:30'),
(501, 'Assistant', 'DASID002', 'Logout', '2025-03-01 12:46:31'),
(502, 'Patient', 'PAT014', 'Login', '2025-03-01 12:46:40'),
(503, 'Patient', 'PAT014', 'Logout', '2025-03-01 12:47:27'),
(504, 'Dentist', 'DENID002', 'Login', '2025-03-01 12:48:12'),
(505, 'Dentist', 'DENID002', 'Logout', '2025-03-01 12:48:17'),
(506, 'Dentist', 'DENID002', 'Login', '2025-03-01 12:48:31'),
(507, 'Dentist', 'DENID002', 'Logout', '2025-03-01 12:48:35'),
(508, 'Dentist', 'DENID002', 'Login', '2025-03-01 12:49:01'),
(509, 'Dentist', 'DENID002', 'Logout', '2025-03-01 12:59:40'),
(510, 'Patient', 'PAT014', 'Login', '2025-03-01 12:59:49'),
(511, 'Patient', 'PAT014', 'Login', '2025-03-01 12:59:56'),
(512, 'Patient', 'PAT014', 'Logout', '2025-03-01 13:01:23'),
(513, 'Assistant', 'DASID002', 'Login', '2025-03-01 13:02:06'),
(514, 'Assistant', 'DASID002', 'Login', '2025-03-01 14:17:00'),
(515, 'Assistant', 'DASID002', 'Logout', '2025-03-01 14:36:59'),
(516, 'Dentist', 'DENID002', 'Login', '2025-03-01 14:37:08'),
(517, 'Patient', 'PAT015', 'Login', '2025-03-02 08:51:28'),
(518, 'Dentist', 'DENID002', 'Login', '2025-03-02 08:52:02'),
(519, 'Dentist', 'DENID002', 'Logout', '2025-03-02 08:53:53'),
(520, 'Assistant', 'DASID001', 'Login', '2025-03-02 08:54:07'),
(521, 'Assistant', 'DASID001', 'Logout', '2025-03-02 08:54:46'),
(522, 'Dentist', 'DENID002', 'Login', '2025-03-02 08:54:58'),
(523, 'Dentist', 'DENID002', 'Logout', '2025-03-02 09:06:05'),
(524, 'Assistant', 'DASID002', 'Login', '2025-03-02 09:10:59'),
(525, 'Assistant', 'DASID002', 'Logout', '2025-03-02 09:11:54'),
(526, 'Patient', 'PAT015', 'Logout', '2025-03-02 09:12:47'),
(527, 'Patient', 'PAT014', 'Login', '2025-03-02 09:27:16'),
(528, 'Patient', 'PAT014', 'Logout', '2025-03-02 09:28:06'),
(529, 'BillingSpecialist', 'SPEID001', 'Login', '2025-03-02 09:28:14'),
(530, 'BillingSpecialist', 'SPEID001', 'Logout', '2025-03-02 09:29:37'),
(531, 'Patient', 'PAT013', 'Login', '2025-03-02 11:31:50'),
(532, 'Patient', 'PAT013', 'Logout', '2025-03-02 11:31:53'),
(533, 'Patient', 'PAT015', 'Login', '2025-03-02 11:32:08'),
(534, 'Patient', 'PAT015', 'Logout', '2025-03-02 11:32:49'),
(535, 'Assistant', 'DASID002', 'Login', '2025-03-02 11:33:11'),
(536, 'Assistant', 'DASID002', 'Logout', '2025-03-02 11:34:07'),
(537, 'Patient', 'PAT015', 'Login', '2025-03-02 11:34:19'),
(538, 'Patient', 'PAT015', 'Logout', '2025-03-02 11:52:30'),
(539, 'Assistant', 'DASID002', 'Login', '2025-03-02 11:52:38'),
(540, 'Patient', 'PAT013', 'Login', '2025-03-02 12:00:02'),
(541, 'Assistant', 'DASID002', 'Logout', '2025-03-02 12:01:06'),
(542, 'Patient', 'PAT002', 'Login', '2025-03-02 12:01:13'),
(543, 'Patient', 'PAT002', 'Logout', '2025-03-02 12:04:42'),
(544, 'Patient', 'PAT001', 'Login', '2025-03-02 12:08:38'),
(545, 'Patient', 'PAT001', 'Logout', '2025-03-02 12:12:50'),
(546, 'Assistant', 'DASID002', 'Login', '2025-03-02 12:13:06'),
(547, 'Assistant', 'DASID002', 'Logout', '2025-03-02 12:14:29'),
(548, 'Patient', 'PAT001', 'Login', '2025-03-02 12:14:38'),
(549, 'Patient', 'PAT001', 'Logout', '2025-03-02 12:16:35'),
(550, 'Assistant', 'DASID002', 'Login', '2025-03-02 12:16:44'),
(551, 'Assistant', 'DASID002', 'Logout', '2025-03-02 12:34:50'),
(552, 'Patient', 'PAT002', 'Login', '2025-03-02 12:34:56'),
(553, 'Patient', 'PAT002', 'Logout', '2025-03-02 12:38:05'),
(554, 'Assistant', 'DASID002', 'Login', '2025-03-02 12:38:16'),
(555, 'Assistant', 'DASID002', 'Logout', '2025-03-02 12:38:57'),
(556, 'Dentist', 'DENID002', 'Login', '2025-03-02 12:39:11'),
(557, 'Dentist', 'DENID002', 'Logout', '2025-03-02 12:41:11'),
(558, 'Patient', 'PAT002', 'Login', '2025-03-02 12:41:17'),
(559, 'Patient', 'PAT002', 'Logout', '2025-03-02 13:19:28'),
(560, 'Patient', 'PAT013', 'Login', '2025-03-02 13:19:34'),
(561, 'Patient', 'PAT013', 'Logout', '2025-03-02 13:22:29'),
(562, 'BillingSpecialist', 'SPEID001', 'Login', '2025-03-02 13:22:44'),
(563, 'BillingSpecialist', 'SPEID001', 'Logout', '2025-03-02 13:22:56'),
(564, 'Patient', 'PAT013', 'Login', '2025-03-02 13:23:04'),
(565, 'Patient', 'PAT013', 'Logout', '2025-03-02 13:23:59'),
(566, 'Patient', 'PAT009', 'Login', '2025-03-02 13:25:42'),
(567, 'Patient', 'PAT009', 'Logout', '2025-03-02 13:26:59'),
(568, 'Patient', 'PAT010', 'Login', '2025-03-02 13:27:25'),
(569, 'Patient', 'PAT010', 'Logout', '2025-03-02 13:29:13'),
(570, 'Assistant', 'DASID002', 'Login', '2025-03-02 13:29:39'),
(571, 'Assistant', 'DASID002', 'Logout', '2025-03-02 13:30:01'),
(572, 'Patient', 'PAT008', 'Login', '2025-03-02 13:30:34'),
(573, 'Patient', 'PAT008', 'Logout', '2025-03-02 13:31:57'),
(574, 'Patient', 'PAT012', 'Login', '2025-03-02 13:32:26'),
(575, 'Patient', 'PAT012', 'Logout', '2025-03-02 13:33:05'),
(576, 'Assistant', 'DASID002', 'Login', '2025-03-02 13:33:23'),
(577, 'Patient', 'PAT015', 'Login', '2025-03-02 22:49:10'),
(578, 'Patient', 'PAT015', 'Logout', '2025-03-02 22:50:43'),
(579, 'Dentist', 'DENID002', 'Login', '2025-03-02 22:50:55'),
(580, 'Dentist', 'DENID002', 'Logout', '2025-03-02 22:52:24'),
(581, 'Patient', 'PAT014', 'Login', '2025-03-02 23:34:37'),
(582, 'Patient', 'PAT014', 'Logout', '2025-03-02 23:34:55'),
(583, 'Assistant', 'DASID002', 'Login', '2025-03-02 23:35:06'),
(584, 'Assistant', 'DASID002', 'Logout', '2025-03-03 02:24:02'),
(585, 'Patient', 'PAT014', 'Login', '2025-03-03 02:24:31'),
(586, 'Patient', 'PAT014', 'Logout', '2025-03-03 02:26:03'),
(587, 'Patient', 'PAT016', 'Login', '2025-03-03 02:52:40'),
(588, 'Patient', 'PAT016', 'Logout', '2025-03-03 02:55:17'),
(589, 'Assistant', 'DASID002', 'Login', '2025-03-03 02:55:47'),
(590, 'Assistant', 'DASID002', 'Logout', '2025-03-03 02:59:57'),
(591, 'Dentist', 'DENID002', 'Login', '2025-03-03 03:00:10'),
(592, 'Dentist', 'DENID002', 'Logout', '2025-03-03 03:03:26'),
(593, 'Patient', 'PAT016', 'Login', '2025-03-03 03:03:49'),
(594, 'Patient', 'PAT016', 'Logout', '2025-03-03 03:05:56'),
(595, 'BillingSpecialist', 'SPEID001', 'Login', '2025-03-03 03:06:13'),
(596, 'BillingSpecialist', 'SPEID001', 'Logout', '2025-03-03 03:08:53'),
(597, 'Assistant', 'DASID002', 'Login', '2025-03-03 03:09:58'),
(598, 'Assistant', 'DASID002', 'Logout', '2025-03-03 03:13:48'),
(599, 'BillingSpecialist', 'SPEID001', 'Login', '2025-03-03 03:50:09'),
(600, 'BillingSpecialist', 'SPEID001', 'Logout', '2025-03-03 03:51:10');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `AdminID` varchar(10) NOT NULL,
  `Firstname` varchar(100) DEFAULT NULL,
  `Lastname` varchar(100) DEFAULT NULL,
  `Middlename` varchar(100) DEFAULT NULL,
  `Sex` char(1) DEFAULT NULL CHECK (`Sex` in ('M','F')),
  `Age` int(11) DEFAULT NULL,
  `ContactDetails` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AdminID`, `Firstname`, `Lastname`, `Middlename`, `Sex`, `Age`, `ContactDetails`, `Email`, `password`, `img`, `status`, `created_at`) VALUES
('ADMID001', 'Karlos', 'Mendes', NULL, 'M', 45, '+1234567890', 'kmendes@admin.com', '$2y$10$pn11R78X6eWVBunQ8aIU8eYGiwmOU1Q7q0UoOMij7b92xCnACrBai', 'user_default.png', 'Offline', '2025-01-25 08:23:38'),
('ADMID001', 'Anna', 'Delacruz', NULL, 'F', 38, '+9876543210', 'anna.delacruz@admin.com', '$2y$10$HeyOSt6GBfDP4z1yl0JSh.qrKwvp9VMjqRsE0/r.6q7RonXeBYFsy', 'user_default.png', 'Offline', '2025-01-25 08:23:38'),
('ADMID001', 'Mark', 'Santiago', NULL, 'M', 41, '+7654321098', 'mark.santiago@admin.com', '$2y$10$FTWy19NxbqQRIL8DHbu0FOy3dYn1P9AFNrfuAd0ZaH2NfTdfLXN6C', 'user_default.png', 'Offline', '2025-01-25 08:23:38');

--
-- Triggers `admin`
--
DELIMITER $$
CREATE TRIGGER `generate_admin_id` BEFORE INSERT ON `admin` FOR EACH ROW BEGIN
    DECLARE last_id INT;
    DECLARE new_id VARCHAR(10);

    -- Extract numeric part after "ADMID" prefix
    SELECT COALESCE(MAX(CAST(SUBSTRING(AdminID, 6) AS UNSIGNED)), 0) INTO last_id FROM admin;

    -- Generate new ID (e.g., ADMID001)
    SET new_id = CONCAT('ADMID', LPAD(last_id + 1, 3, '0'));
    SET NEW.AdminID = new_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `AppointmentID` varchar(10) NOT NULL,
  `PatientID` varchar(10) NOT NULL,
  `DentistID` varchar(10) NOT NULL,
  `AppointmentType` varchar(50) DEFAULT NULL,
  `AppointmentLaboratory` varchar(50) DEFAULT NULL,
  `AppointmentProcedure` varchar(50) DEFAULT NULL,
  `AppointmentTreatment` varchar(50) DEFAULT NULL,
  `AppointmentDate` date DEFAULT NULL,
  `TimeStart` time DEFAULT NULL,
  `TimeEnd` time DEFAULT NULL,
  `PaymentType` varchar(50) DEFAULT NULL,
  `Reason` text DEFAULT NULL,
  `AppointmentStatus` varchar(50) DEFAULT NULL,
  `CancelationReason` text DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`AppointmentID`, `PatientID`, `DentistID`, `AppointmentType`, `AppointmentLaboratory`, `AppointmentProcedure`, `AppointmentTreatment`, `AppointmentDate`, `TimeStart`, `TimeEnd`, `PaymentType`, `Reason`, `AppointmentStatus`, `CancelationReason`, `CreatedAt`) VALUES
('APT00010', 'PAT008', 'DENID001', 'laboratory', 'Tooth X-ray', '', '', '2025-02-17', '09:00:00', '11:00:00', 'cash', 'Hello po', 'Completed', NULL, '2025-02-15 21:34:09'),
('APT00011', 'PAT008', 'DENID002', 'laboratory', 'Tooth X-ray', '', '', '2025-02-17', '11:00:00', '22:00:00', 'cash', 'sad', 'Completed', NULL, '2025-02-15 21:36:21'),
('APT00012', 'PAT012', 'DENID001', 'treatment', '', '', 'Surgical Care', '2025-02-17', '13:00:00', '15:00:00', 'cash', 'Hi po ', 'Completed', NULL, '2025-02-16 12:07:37'),
('APT00013', 'PAT002', 'DENID008', 'treatment', '', '', 'Orthodontic Care', '2025-02-20', '09:00:00', '11:00:00', 'card', 'hello po', 'Completed', NULL, '2025-02-17 04:59:26'),
('APT00014', 'PAT002', 'DENID002', 'consultation', '', '', '', '2025-02-19', '09:00:00', '11:00:00', 'cash', 'Hello po', 'Completed', NULL, '2025-02-17 05:34:41'),
('APT00015', 'PAT002', 'DENID008', 'laboratory', 'Tooth X-ray', '', '', '2025-02-28', '09:00:00', '11:00:00', 'card', 'Test try', 'Completed', NULL, '2025-02-17 07:58:33'),
('APT00016', 'PAT008', 'DENID008', 'laboratory', 'Tooth X-ray', '', '', '2025-02-28', '09:00:00', '11:00:00', 'cash', 'Try', 'Completed', NULL, '2025-02-17 07:59:17'),
('APT00017', 'PAT008', 'DENID001', 'consultation', '', '', '', '2025-02-22', '09:00:00', '11:00:00', 'cash', 'Try', 'Completed', NULL, '2025-02-17 08:02:34'),
('APT00018', 'PAT002', 'DENID003', 'laboratory', 'Tooth X-ray', '', '', '2025-02-21', '09:00:00', '11:00:00', 'cash', 'Hello po ', 'penalty', NULL, '2025-02-19 21:05:49'),
('APT00019', 'PAT002', 'DENID002', 'check-up', '', '', '', '2025-02-21', '00:00:00', '13:00:00', 'cash', 'Hi po', 'Completed', NULL, '2025-02-19 21:06:21'),
('APT00020', 'PAT002', 'DENID004', 'check-up', '', '', '', '2025-02-21', '09:00:00', '11:00:00', 'cash', 'sad', 'Canceled', NULL, '2025-02-19 21:07:48'),
('APT00021', 'PAT002', 'DENID007', 'procedure', '', '', '', '2025-02-21', '09:00:00', '23:00:00', 'card', 'SADSADASD', 'Canceled', NULL, '2025-02-19 21:08:48'),
('APT00022', 'PAT002', 'DENID001', 'laboratory', 'Tooth X-ray', '', '', '2025-02-28', '13:00:00', '14:00:00', 'cash', 'SAD', 'Canceled', NULL, '2025-02-19 21:39:56'),
('APT00023', 'PAT002', 'DENID002', 'consultation', '', '', '', '2025-02-28', '13:00:00', '14:00:00', 'card', 'asdsadasd', 'Canceled', NULL, '2025-02-19 21:40:18'),
('APT00024', 'PAT002', 'DENID008', 'follow-up', '', '', '', '2025-02-28', '11:00:00', '13:00:00', 'cash', 'sdsada', 'Completed', NULL, '2025-02-19 21:43:29'),
('APT00025', 'PAT002', 'DENID001', 'check-up', '', '', '', '2025-02-28', '14:00:00', '15:00:00', 'cash', 'asdadasda', 'Completed', NULL, '2025-02-19 21:43:57'),
('APT00026', 'PAT002', 'DENID008', 'check-up', '', '', '', '2025-02-28', '15:00:00', '16:00:00', 'cash', 'asdasdadsa', 'Canceled', NULL, '2025-02-19 21:47:35'),
('APT00027', 'PAT002', 'DENID007', 'procedure', '', 'Dental Filling', '', '2025-02-26', '15:00:00', '16:00:00', 'cash', 'sadadasdsadas', 'Completed', NULL, '2025-02-19 21:48:01'),
('APT00028', 'PAT002', 'DENID006', 'procedure', '', 'Examination', '', '2025-02-21', '13:00:00', '14:00:00', 'cash', 'asdasda', 'Completed', NULL, '2025-02-19 21:53:54'),
('APT00029', 'PAT002', 'DENID006', 'consultation', '', '', '', '2025-02-20', '15:00:00', '16:00:00', 'cash', 'asdsadasdas', 'Completed', NULL, '2025-02-19 21:54:14'),
('APT00030', 'PAT002', 'DENID001', 'check-up', '', '', '', '2025-03-01', '09:00:00', '11:00:00', 'cash', 'Test try again po lods', 'Completed', NULL, '2025-02-20 14:54:40'),
('APT00031', 'PAT002', 'DENID002', 'consultation', '', '', '', '2025-03-01', '09:00:00', '11:00:00', 'cash', 'Hi po boss', 'Canceled', NULL, '2025-02-20 15:02:33'),
('APT00032', 'PAT002', 'DENID001', 'consultation', '', '', '', '2025-03-02', '09:00:00', '11:00:00', 'card', 'HAHHAHAH\r\n', 'penalty', NULL, '2025-02-20 15:37:45'),
('APT00033', 'PAT002', 'DENID003', 'consultation', '', '', '', '2025-03-01', '09:00:00', '11:00:00', 'cash', 'SADIST\r\n', 'Completed', NULL, '2025-02-20 15:47:55'),
('APT00034', 'PAT002', 'DENID006', 'treatment', '', '', 'Surgical Care', '2025-02-28', '09:00:00', '11:00:00', 'cash', 'test case para dalawa', 'penalty', NULL, '2025-02-28 04:23:21'),
('APT00035', 'PAT002', 'DENID008', 'consultation', '', '', '', '2025-02-28', '09:00:00', '11:00:00', 'cash', 'gawin penalty ', 'penalty', NULL, '2025-02-28 04:30:18'),
('APT00036', 'PAT013', 'DENID008', 'consultation', '', '', '', '2025-02-28', '01:00:00', '03:00:00', 'cash', 'test try for cancel', 'penalty', NULL, '2025-02-28 04:41:00'),
('APT00037', 'PAT013', 'DENID008', 'laboratory', 'Tooth X-ray', '', '', '2026-01-28', '03:00:00', '05:00:00', 'card', 'test case for penalty\r\n', 'Canceled', 'change_of_mind: wew', '2025-02-28 04:41:54'),
('APT00038', 'PAT002', 'DENID007', 'consultation', '', '', '', '2025-03-01', '09:00:00', '11:00:00', 'cash', 'test for cancelation\r\n', 'Canceled', 'change_of_mind', '2025-03-01 07:25:05'),
('APT00039', 'PAT002', 'DENID006', 'laboratory', 'Tooth X-ray', '', '', '2025-03-01', '09:00:00', '10:00:00', 'cash', 'x-tray', 'penalty', NULL, '2025-03-01 09:25:36'),
('APT00040', 'PAT014', 'DENID002', 'consultation', '', '', '', '2025-03-01', '13:00:00', '14:00:00', 'card', 'My gums are aching for several days already and I want it to go away.', 'Completed', NULL, '2025-03-01 12:05:32'),
('APT00041', 'PAT015', 'DENID002', 'consultation', '', '', '', '2025-03-03', '09:00:00', '11:00:00', 'card', 'consultation about my pain tooth', 'Approved', NULL, '2025-03-02 08:53:48'),
('APT00042', 'PAT015', 'DENID002', 'consultation', '', '', '', '2025-03-02', '09:00:00', '11:00:00', 'cash', 'Hello its me', 'penalty', NULL, '2025-03-02 11:32:42'),
('APT00043', 'PAT015', 'DENID002', 'check-up', '', '', '', '2025-03-02', '11:00:00', '12:00:00', 'cash', 'follow up check up', 'penalty', NULL, '2025-03-02 11:49:49'),
('APT00044', 'PAT013', 'DENID002', 'laboratory', 'Tooth X-ray', '', '', '2025-03-04', '09:00:00', '11:00:00', 'card', 'new tooth x-tray', 'Approved', NULL, '2025-03-02 12:00:48'),
('APT00045', 'PAT002', 'DENID002', 'treatment', '', '', 'Surgical Care', '2025-03-04', '11:00:00', '13:00:00', 'cash', 'surgical care', 'Approved', NULL, '2025-03-02 12:02:40'),
('APT00046', 'PAT002', 'DENID002', 'consultation', '', '', '', '2025-03-10', '09:00:00', '11:00:00', 'card', 'Follow up consultations', 'Approved', NULL, '2025-03-02 12:03:08'),
('APT00047', 'PAT013', 'DENID002', 'procedure', '', 'Dental Filling', '', '2025-03-06', '09:00:00', '11:00:00', 'cash', 'Procedures', 'Approved', NULL, '2025-03-02 12:04:35'),
('APT00048', 'PAT013', 'DENID002', 'consultation', '', '', '', '2025-03-10', '13:00:00', '14:00:00', 'cash', 'Consultation for my tooths', 'Approved', NULL, '2025-03-02 12:12:08'),
('APT00049', 'PAT013', 'DENID002', 'laboratory', 'Tooth X-ray', '', '', '2025-03-11', '09:00:00', '11:00:00', 'cash', 'X-tray for tooth problems', 'Approved', NULL, '2025-03-02 12:12:39'),
('APT00050', 'PAT001', 'DENID002', 'consultation', '', '', '', '2025-03-20', '09:00:00', '11:00:00', 'cash', 'Consultation only\r\n', 'Approved', NULL, '2025-03-02 12:15:24'),
('APT00051', 'PAT001', 'DENID002', 'follow-up', '', '', '', '2025-03-24', '13:00:00', '14:00:00', 'cash', 'follow-up for my consultations', 'Approved', NULL, '2025-03-02 12:16:11'),
('APT00052', 'PAT002', 'DENID002', 'laboratory', 'Tooth X-ray', '', '', '2025-03-17', '09:00:00', '10:00:00', 'cash', 'for only tooth - xtry', 'Approved', NULL, '2025-03-02 12:35:35'),
('APT00053', 'PAT002', 'DENID002', 'treatment', 'Tooth X-ray', '', 'Preventive Care', '2025-03-31', '09:00:00', '10:00:00', 'cash', 'i need preventive care service for my tooth', 'scheduled', NULL, '2025-03-02 12:38:04'),
('APT00054', 'PAT009', 'DENID002', 'laboratory', 'Tooth X-ray', '', '', '2025-03-05', '09:00:00', '10:00:00', 'cash', 'x-tray', 'scheduled', NULL, '2025-03-02 13:26:28'),
('APT00055', 'PAT009', 'DENID002', 'check-up', '', '', '', '2025-03-12', '09:00:00', '10:00:00', 'card', 'follow-up check ups ', 'scheduled', NULL, '2025-03-02 13:26:56'),
('APT00056', 'PAT010', 'DENID003', 'check-up', '', '', '', '2025-03-19', '10:00:00', '11:00:00', 'cash', 'follow up check up for my tooth', 'scheduled', NULL, '2025-03-02 13:28:25'),
('APT00057', 'PAT010', 'DENID001', 'follow-up', '', '', '', '2025-03-26', '11:00:00', '12:00:00', 'cash', 'Hello po', 'scheduled', NULL, '2025-03-02 13:28:50'),
('APT00058', 'PAT008', 'DENID008', 'consultation', '', '', '', '2025-03-06', '09:00:00', '10:00:00', 'cash', 'trying to consult my tooth problem', 'scheduled', NULL, '2025-03-02 13:31:24'),
('APT00059', 'PAT008', 'DENID003', 'consultation', '', '', '', '2025-03-19', '09:00:00', '10:00:00', 'card', 'trying consult my tooth for braces', 'scheduled', NULL, '2025-03-02 13:31:55'),
('APT00060', 'PAT012', 'DENID004', 'treatment', '', '', 'Orthodontic Care', '2025-03-15', '09:00:00', '10:00:00', 'card', 'I need asap orthodontic Care', 'scheduled', NULL, '2025-03-02 13:33:03'),
('APT00061', 'PAT015', 'DENID002', 'consultation', '', '', '', '2025-03-28', '09:00:00', '10:00:00', 'cash', 'try to consult my tooth problem\r\n', 'scheduled', NULL, '2025-03-02 22:50:02'),
('APT00062', 'PAT016', 'DENID002', 'consultation', '', '', '', '2025-03-03', '13:00:00', '14:00:00', 'card', 'gum problemns', 'Approved', NULL, '2025-03-03 02:54:27');

--
-- Triggers `appointment`
--
DELIMITER $$
CREATE TRIGGER `generate_appointment_id` BEFORE INSERT ON `appointment` FOR EACH ROW BEGIN
    DECLARE last_id INT;
    DECLARE new_id VARCHAR(10);
    
    -- Get the highest existing numeric part of AppointmentID
    SELECT COALESCE(MAX(CAST(SUBSTRING(AppointmentID, 4) AS UNSIGNED)), 0) INTO last_id FROM appointment;
    
    -- Generate new AppointmentID with 5 digits
    SET new_id = CONCAT('APT', LPAD(last_id + 1, 5, '0'));
    
    -- Assign new ID
    SET NEW.AppointmentID = new_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `appointmentbilling`
--

CREATE TABLE `appointmentbilling` (
  `BillingID` varchar(10) NOT NULL,
  `AppointmentID` varchar(10) NOT NULL,
  `PatientID` varchar(10) NOT NULL,
  `AppointmentFee` float DEFAULT NULL,
  `LaboratoryFee` float DEFAULT NULL,
  `ProcedureFee` float DEFAULT NULL,
  `TreatmentFee` float DEFAULT NULL,
  `TotalFee` float GENERATED ALWAYS AS (`AppointmentFee` + `LaboratoryFee` + `ProcedureFee` + `TreatmentFee`) STORED,
  `PaymentType` varchar(50) DEFAULT NULL,
  `PaymentStatus` varchar(50) DEFAULT 'unpaid',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointmentbilling`
--

INSERT INTO `appointmentbilling` (`BillingID`, `AppointmentID`, `PatientID`, `AppointmentFee`, `LaboratoryFee`, `ProcedureFee`, `TreatmentFee`, `PaymentType`, `PaymentStatus`, `CreatedAt`) VALUES
('BILLID005', 'APT00010', 'PAT008', 0, 5000, 0, 0, 'Card', 'paid', '2025-02-15 21:34:09'),
('BILLID006', 'APT00011', 'PAT008', 0, 5000, 0, 0, 'Card', 'paid', '2025-02-15 21:36:21'),
('BILLID007', 'APT00012', 'PAT012', 0, 0, 0, 20000, 'cash', 'paid', '2025-02-16 12:07:37'),
('BILLID008', 'APT00013', 'PAT002', 0, 0, 0, 20000, 'card', 'paid', '2025-02-17 04:59:26'),
('BILLID009', 'APT00014', 'PAT002', 1000, 0, 0, 0, 'Card', 'paid', '2025-02-17 05:34:41'),
('BILLID010', 'APT00015', 'PAT002', 0, 5000, 0, 0, 'card', 'paid', '2025-02-17 07:58:33'),
('BILLID011', 'APT00016', 'PAT008', 0, 5000, 0, 0, 'cash', 'unpaid', '2025-02-17 07:59:17'),
('BILLID012', 'APT00017', 'PAT008', 1000, 0, 0, 0, 'cash', 'unpaid', '2025-02-17 08:02:34'),
('BILLID013', 'APT00018', 'PAT002', 0, 5000, 0, 0, 'Card', 'partial', '2025-02-19 21:05:49'),
('BILLID014', 'APT00019', 'PAT002', 500, 0, 0, 0, 'cash', 'unpaid', '2025-02-19 21:06:21'),
('BILLID015', 'APT00020', 'PAT002', 500, 0, 0, 0, 'cash', 'unpaid', '2025-02-19 21:07:48'),
('BILLID016', 'APT00021', 'PAT002', 0, 0, 0, 0, 'card', 'unpaid', '2025-02-19 21:08:48'),
('BILLID017', 'APT00022', 'PAT002', 0, 5000, 0, 0, 'cash', 'unpaid', '2025-02-19 21:39:56'),
('BILLID018', 'APT00023', 'PAT002', 1000, 0, 0, 0, 'card', 'unpaid', '2025-02-19 21:40:18'),
('BILLID019', 'APT00024', 'PAT002', 1000, 0, 0, 0, 'cash', 'unpaid', '2025-02-19 21:43:29'),
('BILLID020', 'APT00025', 'PAT002', 500, 0, 0, 0, 'Card', 'paid', '2025-02-19 21:43:57'),
('BILLID021', 'APT00026', 'PAT002', 500, 0, 0, 0, 'cash', 'unpaid', '2025-02-19 21:47:35'),
('BILLID022', 'APT00027', 'PAT002', 0, 0, 1500, 0, 'Card', 'paid', '2025-02-19 21:48:01'),
('BILLID023', 'APT00028', 'PAT002', 0, 0, 500, 0, 'cash', 'unpaid', '2025-02-19 21:53:54'),
('BILLID024', 'APT00029', 'PAT002', 1000, 0, 0, 0, 'Card', 'paid', '2025-02-19 21:54:14'),
('BILLID025', 'APT00030', 'PAT002', 500, 0, 0, 0, 'Card', 'paid', '2025-02-20 14:54:40'),
('BILLID026', 'APT00031', 'PAT002', 1000, 0, 0, 0, 'cash', 'unpaid', '2025-02-20 15:02:33'),
('BILLID027', 'APT00032', 'PAT002', 1000, 0, 0, 0, 'card', 'paid', '2025-02-20 15:37:45'),
('BILLID028', 'APT00033', 'PAT002', 1000, 0, 0, 0, 'Card', 'paid', '2025-02-20 15:47:55'),
('BILLID029', 'APT00034', 'PAT002', 0, 0, 0, 20000, 'Card', 'paid', '2025-02-28 04:23:21'),
('BILLID030', 'APT00035', 'PAT002', 1000, 0, 0, 0, 'Card', 'paid', '2025-02-28 04:30:18'),
('BILLID031', 'APT00036', 'PAT013', 1000, 0, 0, 0, 'Card', 'paid', '2025-02-28 04:41:00'),
('BILLID032', 'APT00037', 'PAT013', 0, 5000, 0, 0, 'card', 'unpaid', '2025-02-28 04:41:54'),
('BILLID033', 'APT00038', 'PAT002', 1000, 0, 0, 0, 'cash', 'unpaid', '2025-03-01 07:25:05'),
('BILLID034', 'APT00039', 'PAT002', 0, 5000, 0, 0, 'Card', 'partial', '2025-03-01 09:25:37'),
('BILLID035', 'APT00040', 'PAT014', 1000, 0, 0, 0, 'card', 'paid', '2025-03-01 12:05:32'),
('BILLID036', 'APT00041', 'PAT015', 1000, 0, 0, 0, 'card', 'paid', '2025-03-02 08:53:48'),
('BILLID037', 'APT00042', 'PAT015', 1000, 0, 0, 0, 'Card', 'paid', '2025-03-02 11:32:42'),
('BILLID038', 'APT00043', 'PAT015', 500, 0, 0, 0, 'Card', 'paid', '2025-03-02 11:49:49'),
('BILLID039', 'APT00044', 'PAT013', 0, 5000, 0, 0, 'card', 'paid', '2025-03-02 12:00:48'),
('BILLID040', 'APT00045', 'PAT002', 0, 0, 0, 20000, 'Card', 'paid', '2025-03-02 12:02:40'),
('BILLID041', 'APT00046', 'PAT002', 1000, 0, 0, 0, 'card', 'paid', '2025-03-02 12:03:08'),
('BILLID042', 'APT00047', 'PAT013', 0, 0, 1500, 0, 'Card', 'paid', '2025-03-02 12:04:35'),
('BILLID043', 'APT00048', 'PAT013', 1000, 0, 0, 0, 'Card', 'paid', '2025-03-02 12:12:08'),
('BILLID044', 'APT00049', 'PAT013', 0, 5000, 0, 0, 'Card', 'paid', '2025-03-02 12:12:39'),
('BILLID045', 'APT00050', 'PAT001', 1000, 0, 0, 0, 'cash', 'unpaid', '2025-03-02 12:15:24'),
('BILLID046', 'APT00051', 'PAT001', 1000, 0, 0, 0, 'cash', 'unpaid', '2025-03-02 12:16:11'),
('BILLID047', 'APT00052', 'PAT002', 0, 5000, 0, 0, 'Card', 'paid', '2025-03-02 12:35:35'),
('BILLID048', 'APT00053', 'PAT002', 0, 5000, 0, 1500, 'Card', 'paid', '2025-03-02 12:38:04'),
('BILLID049', 'APT00054', 'PAT009', 0, 5000, 0, 0, 'cash', 'unpaid', '2025-03-02 13:26:28'),
('BILLID050', 'APT00055', 'PAT009', 500, 0, 0, 0, 'card', 'unpaid', '2025-03-02 13:26:56'),
('BILLID051', 'APT00056', 'PAT010', 500, 0, 0, 0, 'cash', 'unpaid', '2025-03-02 13:28:25'),
('BILLID052', 'APT00057', 'PAT010', 1000, 0, 0, 0, 'cash', 'unpaid', '2025-03-02 13:28:50'),
('BILLID053', 'APT00058', 'PAT008', 1000, 0, 0, 0, 'cash', 'unpaid', '2025-03-02 13:31:24'),
('BILLID054', 'APT00059', 'PAT008', 1000, 0, 0, 0, 'card', 'unpaid', '2025-03-02 13:31:55'),
('BILLID055', 'APT00060', 'PAT012', 0, 0, 0, 20000, 'card', 'unpaid', '2025-03-02 13:33:03'),
('BILLID056', 'APT00061', 'PAT015', 1000, 0, 0, 0, 'cash', 'unpaid', '2025-03-02 22:50:02'),
('BILLID057', 'APT00062', 'PAT016', 1000, 0, 0, 0, 'card', 'paid', '2025-03-03 02:54:27');

--
-- Triggers `appointmentbilling`
--
DELIMITER $$
CREATE TRIGGER `generate_billing_id` BEFORE INSERT ON `appointmentbilling` FOR EACH ROW BEGIN
    DECLARE last_id INT;
    DECLARE new_id VARCHAR(10);
    SELECT COALESCE(MAX(CAST(SUBSTRING(BillingID, 7) AS UNSIGNED)), 0) INTO last_id FROM appointmentbilling;
    SET new_id = CONCAT('BILLID', LPAD(last_id + 1, 3, '0'));
    SET NEW.BillingID = new_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `appointment_pricing`
--

CREATE TABLE `appointment_pricing` (
  `ProcedureID` varchar(10) NOT NULL,
  `AppointmentType` varchar(50) NOT NULL,
  `SubCategory` varchar(50) DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_pricing`
--

INSERT INTO `appointment_pricing` (`ProcedureID`, `AppointmentType`, `SubCategory`, `Price`, `created_at`) VALUES
('PROID001', 'Consultation', NULL, 1000.00, '2025-02-14 19:24:24'),
('PROID002', 'Check-up', NULL, 500.00, '2025-02-14 19:24:24'),
('PROID003', 'Laboratory', 'Tooth X-ray', 5000.00, '2025-02-14 19:24:24'),
('PROID004', 'Procedure', 'Dental Cleaning', 1000.00, '2025-02-14 19:24:24'),
('PROID005', 'Procedure', 'Dental Filling', 1500.00, '2025-02-14 19:24:24'),
('PROID006', 'Procedure', 'Braces Adjustment', 1500.00, '2025-02-14 19:24:24'),
('PROID007', 'Procedure', 'Teeth Whitening', 5000.00, '2025-02-14 19:24:24'),
('PROID008', 'Procedure', 'Denture Fitting', 10000.00, '2025-02-14 19:24:24'),
('PROID009', 'Procedure', 'Examination', 500.00, '2025-02-14 19:24:24'),
('PROID010', 'Procedure', 'Plaque and Tartar Removal', 1000.00, '2025-02-14 19:24:24'),
('PROID011', 'Procedure', 'Fluoride Treatment', 5000.00, '2025-02-14 19:24:24'),
('PROID012', 'Treatment', 'Preventive Care', 1500.00, '2025-02-14 19:24:24'),
('PROID013', 'Treatment', 'Restorative Care', 5000.00, '2025-02-14 19:24:24'),
('PROID014', 'Treatment', 'Cosmetic Care', 10000.00, '2025-02-14 19:24:24'),
('PROID015', 'Treatment', 'Orthodontic Care', 20000.00, '2025-02-14 19:24:24'),
('PROID016', 'Treatment', 'Surgical Care', 20000.00, '2025-02-14 19:24:24'),
('PROID017', 'Follow-up', NULL, 1000.00, '2025-02-14 19:24:24');

--
-- Triggers `appointment_pricing`
--
DELIMITER $$
CREATE TRIGGER `generate_procedure_id` BEFORE INSERT ON `appointment_pricing` FOR EACH ROW BEGIN
    DECLARE last_id INT;
    DECLARE new_id VARCHAR(10);

    -- Get the numeric part of the last ProcedureID
    SELECT COALESCE(MAX(CAST(SUBSTRING(ProcedureID, 6) AS UNSIGNED)), 0) INTO last_id FROM appointment_pricing;

    -- Increment and concatenate with prefix
    SET new_id = CONCAT('PROID', LPAD(last_id + 1, 3, '0'));

    -- Assign the new ProcedureID
    SET NEW.ProcedureID = new_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `billingspecialist`
--

CREATE TABLE `billingspecialist` (
  `SpecialistID` varchar(10) NOT NULL,
  `Firstname` varchar(100) DEFAULT NULL,
  `Lastname` varchar(100) DEFAULT NULL,
  `Middlename` varchar(100) DEFAULT NULL,
  `Sex` char(1) DEFAULT NULL CHECK (`Sex` in ('M','F')),
  `Age` int(11) DEFAULT NULL,
  `ContactDetails` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billingspecialist`
--

INSERT INTO `billingspecialist` (`SpecialistID`, `Firstname`, `Lastname`, `Middlename`, `Sex`, `Age`, `ContactDetails`, `Email`, `password`, `img`, `status`, `created_at`) VALUES
('SPEID001', 'Lara Janine', 'Alcantara', NULL, 'F', 32, '09987654321', 'larajaninealcantara@billingspecialist.com', '$2y$10$hENnNI2hOqbxPIHzeL7xVe.kHjZE77lmrevVgr.jQFiVabWBUO2.G', 'img/Lara Janine Alcantara.png', 'Offline', '2025-01-25 09:48:34'),
('SPEID002', 'John', 'Doe', NULL, 'M', 28, '+639123456789', 'john.doe@billingspecialist.com', '$2y$10$lZywIl0nNPAy7YzS7fZlf.HaNnv9REfFft9eNdsKJbdFo27tihuH6', '', 'Offline', '2025-01-25 09:48:34'),
('SPEID003', 'Maria', 'Clara', NULL, 'F', 35, '+639987654321', 'maria.clara@billingspecialist.com', '$2y$10$k0X/KFAnHFSzn1yRDYJxoOKqKvw2Cs4dU/cpKFVwW0z.hEvqU2O3W', '', 'Active', '2025-01-25 09:48:34');

--
-- Triggers `billingspecialist`
--
DELIMITER $$
CREATE TRIGGER `generate_specialist_id` BEFORE INSERT ON `billingspecialist` FOR EACH ROW BEGIN
    DECLARE last_id INT;
    DECLARE new_id VARCHAR(10);

    -- Extract numeric part after "SPEID" prefix
    SELECT COALESCE(MAX(CAST(SUBSTRING(SpecialistID, 6) AS UNSIGNED)), 0) INTO last_id FROM billingspecialist;

    -- Generate new ID (e.g., SPEID001)
    SET new_id = CONCAT('SPEID', LPAD(last_id + 1, 3, '0'));
    SET NEW.SpecialistID = new_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `billingspecialist_working_hour`
--

CREATE TABLE `billingspecialist_working_hour` (
  `SpecialistID` varchar(10) NOT NULL,
  `Monday` varchar(50) DEFAULT NULL,
  `Tuesday` varchar(50) DEFAULT NULL,
  `Wednesday` varchar(50) DEFAULT NULL,
  `Thursday` varchar(50) DEFAULT NULL,
  `Friday` varchar(50) DEFAULT NULL,
  `Saturday` varchar(50) DEFAULT NULL,
  `Sunday` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billingspecialist_working_hour`
--

INSERT INTO `billingspecialist_working_hour` (`SpecialistID`, `Monday`, `Tuesday`, `Wednesday`, `Thursday`, `Friday`, `Saturday`, `Sunday`) VALUES
('SPEID001', '08:00 AM - 04:00 PM', '08:00 AM - 04:00 PM', '08:00 AM - 12:00 PM', 'Closed', '08:00 AM - 05:00 PM', '09:00 AM - 01:00 PM', 'Closed'),
('SPEID002', 'Closed', '09:00 AM - 05:00 PM', '09:00 AM - 12:00 PM', '09:00 AM - 05:00 PM', '10:00 AM - 06:00 PM', 'Closed', '09:30 AM - 03:00 PM'),
('SPEID003', '04:00 PM - 10:00 PM', 'Closed', '04:00 PM - 10:00 PM', '04:00 PM - 10:00 PM', '04:00 PM - 10:00 PM', 'Closed', 'Closed');

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

CREATE TABLE `card` (
  `CardID` varchar(10) NOT NULL,
  `PatientID` varchar(10) NOT NULL,
  `CardType` varchar(50) DEFAULT NULL,
  `NameOnCard` varchar(100) DEFAULT NULL,
  `CardNumber` varchar(19) DEFAULT NULL,
  `ExpiryDate` date DEFAULT NULL,
  `CVV` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `card`
--

INSERT INTO `card` (`CardID`, `PatientID`, `CardType`, `NameOnCard`, `CardNumber`, `ExpiryDate`, `CVV`) VALUES
('CADID001', 'PAT002', 'BPI', 'Hope Soberano', '1111222233334444', '0000-00-00', '1234'),
('CADID002', 'PAT008', 'BPI', 'Mykie Patosa', '0000111122223333', '0000-00-00', '1231'),
('CADID003', 'PAT008', 'Mastercard', 'Mykie Patosa', '9999888877776666', '0000-00-00', '9999'),
('CADID004', 'PAT002', 'Visa', 'Hope Soberano', '8888888888888888', '0000-00-00', '8888'),
('CADID005', 'PAT002', 'BDO', 'Hope Soberano', '5555555555555555', '0000-00-00', '5555'),
('CADID006', 'PAT013', 'BPI', 'charles darwin', '9999999999999999', '0000-00-00', '9999'),
('CADID007', 'PAT014', 'Visa', 'Layla Genshin', '3333333333333333', '0000-00-00', '3333'),
('CADID008', 'PAT015', 'BPI', 'Charles Leclercs', '1616161616161616', '0000-00-00', '1616'),
('CADID009', 'PAT016', 'BDO', 'Marci Dota', '1234666777888888', '0000-00-00', '1111');

--
-- Triggers `card`
--
DELIMITER $$
CREATE TRIGGER `generate_card_id` BEFORE INSERT ON `card` FOR EACH ROW BEGIN
    DECLARE last_id INT;
    DECLARE new_id VARCHAR(10);

    -- Get the numeric part of the last CardID
    SELECT COALESCE(MAX(CAST(SUBSTRING(CardID, 6) AS UNSIGNED)), 0) INTO last_id FROM card;

    -- Increment and concatenate with prefix
    SET new_id = CONCAT('CADID', LPAD(last_id + 1, 3, '0'));

    -- Assign the new CardID
    SET NEW.CardID = new_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `dentist`
--

CREATE TABLE `dentist` (
  `DentistID` varchar(10) NOT NULL,
  `Firstname` varchar(100) DEFAULT NULL,
  `Lastname` varchar(100) DEFAULT NULL,
  `Middlename` varchar(100) DEFAULT NULL,
  `Sex` char(1) DEFAULT NULL CHECK (`Sex` in ('M','F')),
  `Age` int(11) DEFAULT NULL,
  `ContactDetails` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `Specialization` varchar(255) DEFAULT NULL,
  `Description` varchar(500) DEFAULT NULL,
  `YearExperience` varchar(255) DEFAULT NULL,
  `esignature` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dentist`
--

INSERT INTO `dentist` (`DentistID`, `Firstname`, `Lastname`, `Middlename`, `Sex`, `Age`, `ContactDetails`, `Email`, `password`, `img`, `Specialization`, `Description`, `YearExperience`, `esignature`, `status`, `created_at`) VALUES
('DENID001', 'Arnaldo', 'Turillo', NULL, 'M', 40, '09123456789', 'arnaldo.turillo@dentist.com', '$2y$10$srSZCl.cinegwTTQOq7Eg.on9sIaJPrDkOquSs.A/n5UXeyEOJTh6', 'img/Dr. Arnaldo A. Turillo.png', 'Oral Surgery', 'Specialist in wisdom tooth extractions.', '15 years', 'img/Turillo.png', 'Offline', '2025-01-25 10:04:48'),
('DENID002', 'Mia', 'Alvarez', NULL, 'F', 38, '09378881234', 'mia.alvarez@dentist.com', '$2y$10$nlBWHOb6rdhXTOiNpkWcp.DOQknHBeIjXErrYeYEOYZdy.wdnlLDe', 'img/Dr. Mia R. Alvarez.png', 'General Dentistry', 'Experienced in fillings and extractions.', '12 years', 'img/Alvarez.png', 'Offline', '2025-01-25 10:04:48'),
('DENID003', 'Sophia', 'Reyes', NULL, 'F', 42, '09365509123', 'sophia.reyes@dentist.com', '$2y$10$qaQkWfThvcTbqsnGqQjVve4K7Tr2mk/R7XVtIqXopo5ESoQwLhoRm', 'img/Dr. Sophia L. Reyes.png', 'Orthodontics', 'Specialist in braces and alignments.', '18 years', 'img/Reyes.png', 'Offline', '2025-01-25 10:04:48'),
('DENID004', 'Isabella', 'Torres', NULL, 'F', 36, '09795550123', 'isabelle.torres@dentist.com', '$2y$10$qr9vR.TevPLtwI8K.cBFautLTt59MpVcrL/w5b3uOE7Nh5I4mxq2.', 'img/Dr. Isabella T. Torres.png', 'Implants', 'Focuses on dental implants.', '10 years', 'img/Isabela.png', 'Offline', '2025-01-25 10:04:48'),
('DENID005', 'Lucas', 'Ramirez', NULL, 'M', 44, '09395550345', 'lucas.ramirez@dentist.com', '$2y$10$My6VzSiP9gB/6SnkkgWnLO3SH4y9FcND43/1UvLpYnB5VLXXxR4Z6', 'img/Dr. Lucas M. Ramirez.png', 'Endodontics', 'Root canal treatments.', '15 years', 'img/Ramirez.png', 'Offline', '2025-01-25 10:04:48'),
('DENID006', 'Olivia', 'Castillo', NULL, 'F', 30, '09385550123', 'olivia.castillo@dentist.com', '$2y$10$v2dktsQsYPZ4BpoeR2OOruDVbM7rbC26qZDzgFK6UhIkk93rG7x1K', 'img/Dr. Olivia K. Castillo.png', 'Cosmetic Dentistry', 'Specializes in veneers and whitening.', '8 years', 'img/Castillo.png', 'Offline', '2025-01-25 10:04:48'),
('DENID007', 'Evan', 'Santos', NULL, 'M', 39, '09835550123', 'evan.santos@dentist.com', '$2y$10$EKC9123YU8f6kjbKuVG54O90nEhUqbQCcNLEtCmPM1xthJysralHS', 'img/Dr. Evan J. Santos.png', 'General Dentistry', 'Experienced in cleanings and exams.', '13 years', 'img/Santos.png', 'Offline', '2025-01-25 10:04:48'),
('DENID008', 'Daniel', 'Martinez', NULL, 'M', 46, '09375550123', 'daniel.martinez@dentist.com', '$2y$10$Vuwm24THXofb7O7WW.xrHejLYnVnlscewAvBbhGt3ki8hFQyzKIi6', 'img/Daniel P. Martinez.png', 'Periodontics', 'Specialist in gum diseases.', '20 years', 'img/Martinez.png', 'Offline', '2025-01-25 10:04:48');

--
-- Triggers `dentist`
--
DELIMITER $$
CREATE TRIGGER `generate_dentist_id` BEFORE INSERT ON `dentist` FOR EACH ROW BEGIN
    DECLARE last_id INT;
    DECLARE new_id VARCHAR(10);
    SELECT COALESCE(MAX(CAST(SUBSTRING(DentistID, 6) AS UNSIGNED)), 0) INTO last_id FROM dentist;
    SET new_id = CONCAT('DENID', LPAD(last_id + 1, 3, '0'));
    SET NEW.DentistID = new_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `dentistassistant`
--

CREATE TABLE `dentistassistant` (
  `AssistantID` varchar(10) NOT NULL,
  `Firstname` varchar(100) DEFAULT NULL,
  `Lastname` varchar(100) DEFAULT NULL,
  `Middlename` varchar(100) DEFAULT NULL,
  `Sex` char(1) DEFAULT NULL CHECK (`Sex` in ('M','F')),
  `Age` int(11) DEFAULT NULL,
  `ContactDetails` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dentistassistant`
--

INSERT INTO `dentistassistant` (`AssistantID`, `Firstname`, `Lastname`, `Middlename`, `Sex`, `Age`, `ContactDetails`, `Email`, `password`, `img`, `status`, `created_at`) VALUES
('DASID001', 'Jasmine', 'Ramos', NULL, 'F', 28, '09829831234', 'jhramos@dentistassistant.com', '$2y$10$EPyTF7YrF1GVteIcbIQ.sOlIzuAj8bdVPfwdwIyOmF7s3RYWPm0x.', 'img/Jasmine H. Ramos.png', 'Offline', '2025-01-25 10:11:34'),
('DASID002', 'Mark', 'Salazar', NULL, 'M', 32, '09127831234', 'msalazar@dentistassistant.com', '$2y$10$fZxMxV.TYkh9oTLfHfivBOefv/UD/g9izlfurue1WvnpJMd2a1Vpa', 'img/Mark P. Salazar.png', 'Offline', '2025-01-25 10:11:34'),
('DASID003', 'Linda', 'Cruz', NULL, 'F', 29, '09234567890', 'lcruz@dentistassistant.com', '$2y$10$kTXDSZ1tBuqhx6r24xQFeuQH0TLpUUWtIn5otTGOhsHwus9fgr90i', 'img/Linda V. Cruz.png', 'Offline', '2025-01-25 10:11:34'),
('DASID004', 'Paul', 'Montoya', NULL, 'M', 35, '09345678901', 'pmontoya@dentistassistant.com', '$2y$10$WK2Nxav/KGxhhWxHgn/pFe0mfiutjsLsHjDzCu3PYEXdvU6rn88Im', 'img/Paul E. Montoya.png', 'Offline', '2025-01-25 10:11:34');

--
-- Triggers `dentistassistant`
--
DELIMITER $$
CREATE TRIGGER `generate_assistant_id` BEFORE INSERT ON `dentistassistant` FOR EACH ROW BEGIN
    DECLARE last_id INT;
    DECLARE new_id VARCHAR(10);

    -- Extract numeric part after "DASID" prefix
    SELECT COALESCE(MAX(CAST(SUBSTRING(AssistantID, 6) AS UNSIGNED)), 0) INTO last_id FROM dentistassistant;

    -- Generate new ID (e.g., DASID001)
    SET new_id = CONCAT('DASID', LPAD(last_id + 1, 3, '0'));
    SET NEW.AssistantID = new_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `dentistassistant_working_hour`
--

CREATE TABLE `dentistassistant_working_hour` (
  `AssistantID` varchar(10) NOT NULL,
  `Monday` varchar(50) DEFAULT NULL,
  `Tuesday` varchar(50) DEFAULT NULL,
  `Wednesday` varchar(50) DEFAULT NULL,
  `Thursday` varchar(50) DEFAULT NULL,
  `Friday` varchar(50) DEFAULT NULL,
  `Saturday` varchar(50) DEFAULT NULL,
  `Sunday` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dentistassistant_working_hour`
--

INSERT INTO `dentistassistant_working_hour` (`AssistantID`, `Monday`, `Tuesday`, `Wednesday`, `Thursday`, `Friday`, `Saturday`, `Sunday`) VALUES
('DASID001', '07:00 AM - 03:00 PM', '07:00 AM - 03:00 PM', '07:00 AM - 03:00 PM', 'Closed', '07:00 AM - 03:00 PM', '09:00 AM - 01:00 PM', 'Closed'),
('DASID002', '04:00 PM - 08:00 PM', 'Closed', '04:00 PM - 08:00 PM', '04:00 PM - 10:00 PM', '04:00 PM - 08:00 PM', 'Closed', 'Closed'),
('DASID003', '04:00 PM - 08:00 PM', '04:00 PM - 08:00 PM', '04:00 PM - 08:00 PM', 'Closed', '04:00 PM - 08:00 PM', 'Closed', 'Closed'),
('DASID004', 'Closed', '07:00 AM - 03:00 PM', '07:00 AM - 03:00 PM', '07:00 AM - 03:00 PM', 'Closed', '09:30 AM - 03:00 PM', 'Closed');

-- --------------------------------------------------------

--
-- Table structure for table `dentist_working_hour`
--

CREATE TABLE `dentist_working_hour` (
  `DentistID` varchar(10) NOT NULL,
  `Monday` varchar(50) DEFAULT NULL,
  `Tuesday` varchar(50) DEFAULT NULL,
  `Wednesday` varchar(50) DEFAULT NULL,
  `Thursday` varchar(50) DEFAULT NULL,
  `Friday` varchar(50) DEFAULT NULL,
  `Saturday` varchar(50) DEFAULT NULL,
  `Sunday` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dentist_working_hour`
--

INSERT INTO `dentist_working_hour` (`DentistID`, `Monday`, `Tuesday`, `Wednesday`, `Thursday`, `Friday`, `Saturday`, `Sunday`) VALUES
('DENID001', '09:00 AM - 05:00 PM', '09:00 AM - 05:00 PM', '09:00 AM - 12:00 PM', 'Closed', '10:00 AM - 06:00 PM', '09:00 AM - 01:00 PM', '09:00 AM - 03:00 PM'),
('DENID002', '10:00 AM - 04:00 PM', 'Closed', '10:00 AM - 03:00 PM', '09:00 AM - 12:00 PM', 'Closed', 'Closed', 'Closed'),
('DENID003', '08:30 AM - 05:30 PM', 'Closed', '08:30 AM - 03:30 PM', 'Closed', '10:00 AM - 02:00 PM', 'Closed', 'Closed'),
('DENID004', '09:30 AM - 04:30 PM', 'Closed', '09:30 AM - 02:30 PM', 'Closed', '10:00 AM - 06:00 PM', 'Closed', 'Closed'),
('DENID005', 'Closed', '09:00 AM - 05:00 PM', '09:00 AM - 12:00 PM', 'Closed', 'Closed', '09:00 AM - 01:00 PM', 'Closed'),
('DENID006', '10:00 AM - 03:00 PM', 'Closed', '10:00 AM - 03:00 PM', 'Closed', '10:00 AM - 06:00 PM', 'Closed', '09:00 AM - 03:00 PM'),
('DENID007', 'Closed', '09:30 AM - 05:30 PM', '09:30 AM - 01:30 PM', 'Closed', '10:00 AM - 04:00 PM', 'Closed', 'Closed'),
('DENID008', '09:00 AM - 04:00 PM', 'Closed', '09:00 AM - 03:00 PM', 'Closed', '10:00 AM - 06:00 PM', 'Closed', 'Closed');

-- --------------------------------------------------------

--
-- Table structure for table `diagnostictreatment`
--

CREATE TABLE `diagnostictreatment` (
  `laboratoryID` varchar(10) NOT NULL,
  `PatientID` varchar(10) NOT NULL,
  `orderID` varchar(10) DEFAULT NULL,
  `testName` varchar(255) DEFAULT NULL,
  `testResult` text DEFAULT NULL,
  `testDate` date DEFAULT NULL,
  `DiagnosticStatus` varchar(255) DEFAULT NULL,
  `DentistID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `InventoryID` varchar(10) NOT NULL,
  `ItemName` varchar(255) NOT NULL,
  `Category` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL,
  `QuantityAvailable` int(11) NOT NULL,
  `ReorderPoints` int(11) NOT NULL,
  `UnitType` varchar(50) NOT NULL,
  `UnitPrice` decimal(10,2) NOT NULL,
  `Supplier` varchar(255) NOT NULL,
  `ExpiryDate` date NOT NULL,
  `LastRestockedDate` date NOT NULL,
  `Location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`InventoryID`, `ItemName`, `Category`, `Description`, `QuantityAvailable`, `ReorderPoints`, `UnitType`, `UnitPrice`, `Supplier`, `ExpiryDate`, `LastRestockedDate`, `Location`) VALUES
('ITL0001', 'Ibuprofen', 'Pharmaceutical', 'A nonsteroidal anti-inflammatory drug (NSAID) that helps reduce pain, inflammation, and fever.', 90, 80, 'Pair', 8.00, 'HealthEssentials', '2025-03-25', '2025-01-22', 'Storage Room A Shelf 1'),
('ITL0002', 'Acetaminophen', 'Pharmaceutical', 'Disposable gloves, medium', 498, 80, 'Pair', 1.00, 'HealthEssentials', '2026-02-26', '0005-01-20', 'Storage Room A Shelf 1'),
('ITL0003', 'Nitrile Gloves', 'Consumables', 'Disposable gloves, medium', 490, 80, '0', 8.00, 'HealthEssentials', '0000-00-00', '0005-01-20', 'Storage Room A Shelf 1'),
('ITL0004', 'Benzocaine', 'Pharmaceutical', ' A topical anesthetic that temporarily soothes and relieves pain on the treated area.', 490, 80, '0', 8.00, 'HealthEssentials', '0000-00-00', '2025-01-20', 'Storage Room A Shelf 1'),
('ITL0005', 'Mefenamic Acid', 'Pharmaceutical', 'Disposable gloves, medium', 90, 80, '0', 15.00, 'HealthEssentials', '2025-03-24', '2025-01-20', 'Storage Room A Shelf 1'),
('ITL0006', 'Penicillin', 'Consumables', 'Antibiotic', 453, 80, 'packs', 8.00, 'HealthEssentials', '2025-03-25', '2025-03-03', 'Storage Room A Shelf 1');

--
-- Triggers `inventory`
--
DELIMITER $$
CREATE TRIGGER `before_insert_inventory` BEFORE INSERT ON `inventory` FOR EACH ROW BEGIN
    DECLARE next_id INT;

    -- Get the last numeric part of InventoryID
    SELECT COALESCE(MAX(CAST(SUBSTRING(InventoryID, 4, 4) AS UNSIGNED)), 0) + 1 
    INTO next_id FROM inventory;

    -- Format new InventoryID as ITL0001, ITL0002, etc.
    SET NEW.InventoryID = CONCAT('ITL', LPAD(next_id, 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `InvoiceID` varchar(10) NOT NULL,
  `BillingID` varchar(10) NOT NULL,
  `PatientID` varchar(10) NOT NULL,
  `TotalFee` float NOT NULL,
  `TotalPayment` float DEFAULT 0,
  `PaymentStatus` varchar(50) DEFAULT 'unpaid',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `invoice`
--
DELIMITER $$
CREATE TRIGGER `generate_invoice_id` BEFORE INSERT ON `invoice` FOR EACH ROW BEGIN
    DECLARE last_id INT;
    DECLARE new_id VARCHAR(10);
    SELECT COALESCE(MAX(CAST(SUBSTRING(InvoiceID, 4) AS UNSIGNED)), 0) INTO last_id FROM invoice;
    SET new_id = CONCAT('INV', LPAD(last_id + 1, 3, '0'));
    SET NEW.InvoiceID = new_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `medicalrecord`
--

CREATE TABLE `medicalrecord` (
  `id` int(11) NOT NULL,
  `PatientID` varchar(10) NOT NULL,
  `AssistantID` varchar(10) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `timeSubmitted` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateSubmitted` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicalrecord`
--

INSERT INTO `medicalrecord` (`id`, `PatientID`, `AssistantID`, `subject`, `filename`, `timeSubmitted`, `dateSubmitted`) VALUES
(1, 'PAT002', 'DASID001', 'Tooth XRay Result', 'file/Xray.pdf', '2025-02-04 09:03:08', '2025-02-04'),
(2, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:18:59', '2025-02-21'),
(3, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:19:16', '2025-02-21'),
(4, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:20:46', '2025-02-21'),
(5, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:22:32', '2025-02-21'),
(6, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:24:12', '2025-02-21'),
(7, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:27:03', '2025-02-21'),
(8, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:30:09', '2025-02-21'),
(9, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:35:42', '2025-02-21'),
(10, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:35:58', '2025-02-21'),
(11, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:36:45', '2025-02-21'),
(12, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:36:48', '2025-02-21'),
(13, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:39:04', '2025-02-21'),
(14, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:39:09', '2025-02-21'),
(15, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:39:19', '2025-02-21'),
(16, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:42:20', '2025-02-21'),
(17, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Vision Statement.pdf', '2025-02-21 01:42:31', '2025-02-21'),
(18, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:43:36', '2025-02-21'),
(19, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:43:47', '2025-02-21'),
(20, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:46:17', '2025-02-21'),
(21, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:49:36', '2025-02-21'),
(22, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:52:09', '2025-02-21'),
(23, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:52:14', '2025-02-21'),
(24, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:54:43', '2025-02-21'),
(25, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:56:55', '2025-02-21'),
(26, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:57:07', '2025-02-21'),
(27, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:58:30', '2025-02-21'),
(28, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 01:58:37', '2025-02-21'),
(29, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 02:01:58', '2025-02-21'),
(30, 'PAT002', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 02:02:11', '2025-02-21'),
(31, 'PAT002', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 02:08:45', '2025-02-21'),
(32, 'PAT008', 'DASID001', 'Miguel', 'file/Xray.pdf', '2025-02-21 02:09:02', '2025-02-21'),
(33, 'PAT008', 'DASID001', 'Miguel', 'file/Vision Statement.pdf', '2025-02-21 02:09:25', '2025-02-21'),
(34, 'PAT008', 'DASID001', 'Miguel', 'file/Vision Statement.pdf', '2025-02-21 02:10:37', '2025-02-21'),
(35, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray - Copy.pdf', '2025-02-21 02:10:48', '2025-02-21'),
(36, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray - Copy.pdf', '2025-02-21 02:11:06', '2025-02-21'),
(37, 'PAT008', 'DASID001', 'Dr. Miguel', 'file/Xray.pdf', '2025-02-21 02:14:16', '2025-02-21'),
(38, 'PAT008', 'DASID001', 'sad', 'file/Xray.pdf', '2025-02-21 02:19:42', '2025-02-21'),
(39, 'PAT008', 'DASID001', 'Dr. Miguel Polison', 'file/Xray_and_Prescription.pdf', '2025-02-26 04:42:22', '2025-02-26'),
(40, 'PAT014', 'DASID002', 'Tooth X-tray ', 'file/Xray.pdf', '2025-03-02 23:35:41', '2025-03-03');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` varchar(10) NOT NULL,
  `outgoing_msg_id` varchar(10) NOT NULL,
  `msg` varchar(1000) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`, `created_at`) VALUES
(1, 'DENID001', 'PAT005', 'hi', '2025-02-05 04:48:46'),
(2, 'PAT005', 'DENID001', 'hello', '2025-02-05 04:48:54'),
(3, 'PAT005', 'DENID001', 'hello', '2025-02-05 04:48:54'),
(4, 'PAT005', 'DENID001', 'hi', '2025-02-05 04:49:34'),
(5, 'DENID001', 'PAT005', 'hi doc', '2025-02-05 05:09:22'),
(6, 'PAT005', 'DENID001', 'bakit?', '2025-02-05 05:27:07'),
(7, 'PAT004', 'DENID001', 'Hello Syd', '2025-02-05 05:52:30'),
(8, 'PAT005', 'DENID001', 'hi donato', '2025-02-06 03:17:49'),
(9, 'DENID001', 'PAT006', 'hi doctor, good afternoon', '2025-02-06 04:35:36'),
(10, 'PAT002', 'DENID001', 'hi', '2025-02-09 08:18:59'),
(11, 'DENID001', 'PAT002', 'Boss Pa consult', '2025-02-09 08:19:09'),
(12, 'PAT002', 'DENID001', 'Boss Liza <3', '2025-02-09 08:19:20'),
(13, 'PAT002', 'DENID001', '<:', '2025-02-09 08:19:23'),
(14, 'DENID001', 'PAT002', 'NAIIBA OH ', '2025-02-09 08:19:54'),
(15, 'DENID001', 'PAT002', 'boss', '2025-02-14 08:51:25'),
(16, 'PAT002', 'DENID001', 'red', '2025-02-14 08:51:29'),
(17, 'DENID001', 'PAT002', 'janna', '2025-02-14 08:51:33'),
(18, 'PAT013', 'DENID001', 'Hello', '2025-02-28 05:12:19'),
(19, 'PAT013', 'DENID001', 'Hi earl', '2025-02-28 05:12:32'),
(20, 'DENID001', 'PAT013', 'hello dr arnaldo', '2025-02-28 05:12:46'),
(21, 'DENID002', 'PAT015', 'Hi Doc!, ', '2025-03-02 08:52:25'),
(22, 'DENID002', 'PAT015', 'good morning', '2025-03-02 08:52:34');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `PatientID` varchar(10) NOT NULL,
  `Firstname` varchar(100) DEFAULT NULL,
  `Lastname` varchar(100) DEFAULT NULL,
  `Middlename` varchar(100) DEFAULT NULL,
  `Sex` char(1) DEFAULT NULL CHECK (`Sex` in ('M','F')),
  `Age` int(11) DEFAULT NULL,
  `Birthday` date DEFAULT NULL,
  `ContactDetails` varchar(100) DEFAULT NULL,
  `HouseNumberStreet` varchar(100) DEFAULT NULL,
  `Barangay` varchar(100) DEFAULT NULL,
  `CityMunicipality` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`PatientID`, `Firstname`, `Lastname`, `Middlename`, `Sex`, `Age`, `Birthday`, `ContactDetails`, `HouseNumberStreet`, `Barangay`, `CityMunicipality`, `Email`, `password`, `img`, `status`, `created_at`) VALUES
('PAT001', 'Miguel', 'Polison', '', 'M', 35, NULL, '09295527474', NULL, NULL, NULL, 'miguel13@gmail.com', '$2y$10$C/JcVamPm.0F0QLO2g9Bg.905Ltu3fltvym8FhcsuivblWezLV66S', 'img/megelID.jpg', 'Offline', '2025-01-20 08:30:08'),
('PAT002', 'Hope', 'Soberano', 'Elizabeth', 'F', 27, '1998-01-13', '09143143143', 'Street ni mykie', 'brgy ni mykie', 'city ni mykie', 'hope143@gmail.com', '$2y$10$Q0tuVtOMKYUtKcqgAK0mpeLzftnRPl7JwVhwHk0.MeS1O.zIPFQnm', 'img/lizamylove.jpg', 'Offline', '2025-01-20 08:33:45'),
('PAT003', 'Sky', 'Changcoco', 'Walker', 'M', 0, NULL, '09295527474', NULL, NULL, NULL, 'sky123@gmail.com', '$2y$10$kps/2Hncxyo1./U3ugmXUO4YsULpHL2e9yFaHUsADkUY07tLImEQS', 'img/678e133072933-db1.jpg', 'Offline', '2025-01-20 09:11:12'),
('PAT004', 'Sydney', 'Sweeney', '', 'F', 27, NULL, '09143143143', NULL, NULL, NULL, 'Syd143@gmail.com', '$2y$10$5aoJ3Vagvhxq70JzRJQUwepfVY8nlKB5C1xJPQ1ljmwiyzRIHhxPu', 'img/678e1608bc429-syd.jpg', 'Offline', '2025-01-20 09:23:20'),
('PAT005', 'Allen', 'Donato', '', 'M', 0, NULL, '09295527474', NULL, NULL, NULL, 'donato@gmail.com', '$2y$10$lKdyZcV5tP4GyMAA3oS1FOO8YCbi4MXujzMCZT67hCNhH.6QHoK/m', 'img/679b9babca612-tabLogo.png', 'Online', '2025-01-30 15:32:59'),
('PAT006', 'Hope', 'Eliza', '', 'F', 22, NULL, '09292812131', NULL, NULL, NULL, 'eliza@gmail.com', '$2y$10$pX9Oi50nU8kLjtjizcRwPOoZqCcuEjf.MV4PQcBPeT0AIX8MXLmoG', 'img/67a43840645ab-tabLogo.png', 'Offline', '2025-02-06 04:19:12'),
('PAT007', 'Alliaza', 'Era', '', 'F', 20, NULL, '09295523232', NULL, NULL, NULL, 'Alliaza1@gmail.com', '$2y$10$i38u9E4Pjh3edTgXqhaUH.ufJje4l2oNc.O9rElnp3r.3.kbp7Ysm', '', 'Active', '2025-02-13 11:03:49'),
('PAT008', 'Mykie', 'Patosa', '', 'F', 21, NULL, '09292929929', NULL, NULL, NULL, 'mykie1@gmail.com', '$2y$10$mRbrS7BCRoivfDXZ8IUsKuix7X4WDgeMFEpBeEYpynrX8I8oALzjW', '', 'Offline', '2025-02-13 11:07:50'),
('PAT009', 'Jallen', 'Mangona', '', 'F', 25, NULL, '09292952313', NULL, NULL, NULL, 'jallen1@gmail.com', '$2y$10$vK6IQjTmYX5TxHRxGU0nyu2zlUfOnOuBOIjYjh2Z.HcJzFkDfGIRi', '', 'Offline', '2025-02-14 04:53:07'),
('PAT010', 'Earl', 'Mendilio', '', 'M', NULL, '2003-03-03', '09291231232', NULL, NULL, NULL, 'earl1@gmail.com', '$2y$10$HTyrvawXXcLkaN7P5vXDVuZ4QMF5xWVoXnA4d/releyAbYJWzOsF6', '', 'Offline', '2025-02-14 14:01:24'),
('PAT011', 'charles', 'leclerc', 'War', 'M', 26, '1998-12-12', '09090909090', 'fake street', 'brgy 123', 'Fake Lake City', 'charles16@gmail.com', '$2y$10$JnJ2IF8btA.UYvXn/s3KgehRredBfrhYAeCFLV0Yh.h0Rg39GFRU6', '', 'Online', '2025-02-14 14:03:25'),
('PAT012', 'sky', 'clark', '', 'M', 22, '2003-01-13', '09001231231', NULL, NULL, NULL, 'sky@gmail.com', '$2y$10$J./gJYVWHdjqQA39sUzc4uGSovglK1mwCo459XNtZ6YNTpMSV6p6e', '', 'Offline', '2025-02-16 12:06:33'),
('PAT013', 'charles', 'darwin', '', 'M', 64, '1960-12-21', '12312312312', NULL, NULL, NULL, 'charles123@gmail.com', '$2y$10$.u/IyGXs5ID93Axse7SzgePJ9nuMwTrQ33LfrUQaSwPiMQ7DpcFF2', 'img/67c13de694fd7-db1.jpg', 'Offline', '2025-02-28 04:39:02'),
('PAT014', 'Layla', 'Genshin', 'Santos', 'F', 28, '1996-03-02', '09998887777', '501 Treasure Street', 'Sumeru Akademiya', 'Sumeru City', 'layla1@gmail.com', '$2y$10$o5YC5/tUqQLWYOcqfCINJ.WIDN.FUgJQ9itWPCZ6x/thEdSsW8Geq', 'img/67c2f6c68ebc6-07a8c35ddfce52595b4777482de75446.jpg', 'Offline', '2025-03-01 12:00:06'),
('PAT015', 'Charles', 'Leclerc', '', 'M', 28, '1996-10-16', '09912365411', NULL, NULL, NULL, 'ferrari16@gmail.com', '$2y$10$70NwurSkh4xD.zjguiVU3.ySSeRxTtEhZNvMEcM/.iHDR6fSQChq2', 'img/67c41c09526cd-1.JPG', 'Offline', '2025-03-02 08:51:21'),
('PAT016', 'Marci', 'Dota', '', 'F', 20, '2004-06-04', '09254753333', '1342 S. Nicolas St', '', 'Tondo, Manila', 'marcidota@gmail.com', '$2y$10$oSh/tfYkum7B1loFzQfUw.6Jl/h.gYqngmfZVttfRjFKc5e7/RrwK', 'img/67c5196f9b567-marci_profile.jpg', 'Offline', '2025-03-03 02:52:31');

--
-- Triggers `patient`
--
DELIMITER $$
CREATE TRIGGER `generate_patient_id` BEFORE INSERT ON `patient` FOR EACH ROW BEGIN
    DECLARE last_id INT;
    DECLARE new_id VARCHAR(10);

    -- Get the numeric part of the last PatientID
    SELECT COALESCE(MAX(CAST(SUBSTRING(PatientID, 4) AS UNSIGNED)), 0) INTO last_id FROM patient;

    -- Increment and concatenate with prefix
    SET new_id = CONCAT('PAT', LPAD(last_id + 1, 3, '0'));

    -- Assign the new PatientID
    SET NEW.PatientID = new_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `PaymentID` varchar(10) NOT NULL,
  `BillingID` varchar(10) NOT NULL,
  `PaymentAmount` float NOT NULL,
  `PaymentDate` date DEFAULT NULL,
  `PaymentMethod` varchar(50) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`PaymentID`, `BillingID`, `PaymentAmount`, `PaymentDate`, `PaymentMethod`, `CreatedAt`) VALUES
('PAYID007', 'BILLID006', 1000, '2025-02-15', 'Card', '2025-02-15 21:43:53'),
('PAYID008', 'BILLID006', 1000, '2025-02-15', 'Card', '2025-02-15 21:57:21'),
('PAYID009', 'BILLID005', 2500, '2025-02-15', 'Card', '2025-02-15 21:59:15'),
('PAYID010', 'BILLID005', 2500, '2025-02-15', 'Card', '2025-02-15 21:59:44'),
('PAYID011', 'BILLID008', 10000, '2025-02-17', 'Card', '2025-02-17 05:00:26'),
('PAYID012', 'BILLID008', 10000, '2025-02-17', 'Card', '2025-02-17 05:00:35'),
('PAYID013', 'BILLID009', 500, '2025-02-17', 'Card', '2025-02-17 05:34:50'),
('PAYID016', 'BILLID006', 300, '2025-02-17', 'Cash', '2025-02-17 05:41:12'),
('PAYID017', 'BILLID009', 500, '2025-02-17', 'Card', '2025-02-17 07:52:06'),
('PAYID018', 'BILLID010', 2500, '2025-02-17', 'Card', '2025-02-17 08:51:50'),
('PAYID019', 'BILLID006', 2700, '2025-02-17', 'Cash', '2025-02-17 09:10:17'),
('PAYID020', 'BILLID010', 2500, '2025-02-17', 'Cash', '2025-02-17 09:10:22'),
('PAYID021', 'BILLID027', 1000, '2025-02-21', 'Card', '2025-02-21 19:39:22'),
('PAYID022', 'BILLID028', 1000, '2025-02-23', 'Card', '2025-02-23 20:02:05'),
('PAYID023', 'BILLID029', 10000, '2025-02-28', 'Card', '2025-02-28 04:26:34'),
('PAYID024', 'BILLID029', 10000, '2025-02-28', 'Card', '2025-02-28 04:27:26'),
('PAYID025', 'BILLID031', 2000, '2025-02-28', 'Card', '2025-02-28 04:44:48'),
('PAYID026', 'BILLID007', 20000, '2025-02-28', 'Cash', '2025-02-28 05:17:50'),
('PAYID027', 'BILLID035', 1000, '2025-03-01', 'Card', '2025-03-01 12:09:58'),
('PAYID028', 'BILLID037', 2000, '2025-03-02', 'Card', '2025-03-02 11:47:15'),
('PAYID029', 'BILLID036', 1000, '2025-03-02', 'Card', '2025-03-02 11:48:41'),
('PAYID030', 'BILLID048', 6500, '2025-03-02', 'Card', '2025-03-02 12:41:38'),
('PAYID031', 'BILLID047', 5000, '2025-03-02', 'Card', '2025-03-02 12:41:47'),
('PAYID032', 'BILLID041', 1000, '2025-03-02', 'Card', '2025-03-02 12:41:58'),
('PAYID033', 'BILLID040', 20000, '2025-03-02', 'Card', '2025-03-02 12:42:07'),
('PAYID034', 'BILLID025', 500, '2025-03-02', 'Card', '2025-03-02 12:42:32'),
('PAYID035', 'BILLID034', 2000, '2025-03-02', 'Card', '2025-03-02 12:43:10'),
('PAYID036', 'BILLID034', 2000, '2025-03-02', 'Card', '2025-03-02 12:43:24'),
('PAYID037', 'BILLID020', 6000, '2025-03-02', 'Card', '2025-03-02 12:43:46'),
('PAYID038', 'BILLID022', 15000, '2025-03-02', 'Card', '2025-03-02 12:44:10'),
('PAYID039', 'BILLID024', 1000, '2025-03-02', 'Card', '2025-03-02 13:18:24'),
('PAYID040', 'BILLID013', 2000, '2025-03-02', 'Card', '2025-03-02 13:18:38'),
('PAYID041', 'BILLID030', 1000, '2025-03-02', 'Card', '2025-03-02 13:19:11'),
('PAYID042', 'BILLID039', 2500, '2025-03-02', 'Card', '2025-03-02 13:19:49'),
('PAYID043', 'BILLID039', 2500, '2025-03-02', 'Card', '2025-03-02 13:22:10'),
('PAYID044', 'BILLID042', 1500, '2025-03-02', 'Card', '2025-03-02 13:22:19'),
('PAYID045', 'BILLID043', 1000, '2025-03-02', 'Card', '2025-03-02 13:22:27'),
('PAYID046', 'BILLID044', 10, '2025-03-02', 'Card', '2025-03-02 13:23:41'),
('PAYID047', 'BILLID044', 4990, '2025-03-02', 'Card', '2025-03-02 13:23:56'),
('PAYID048', 'BILLID038', 500, '2025-03-02', 'Card', '2025-03-02 22:50:26'),
('PAYID049', 'BILLID057', 1000, '2025-03-03', 'Card', '2025-03-03 03:05:28');

--
-- Triggers `payments`
--
DELIMITER $$
CREATE TRIGGER `generate_payment_id` BEFORE INSERT ON `payments` FOR EACH ROW BEGIN
    DECLARE last_id INT;
    DECLARE new_id VARCHAR(10);
    SELECT COALESCE(MAX(CAST(SUBSTRING(PaymentID, 6) AS UNSIGNED)), 0) INTO last_id FROM payments;
    SET new_id = CONCAT('PAYID', LPAD(last_id + 1, 3, '0'));
    SET NEW.PaymentID = new_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `id` int(11) NOT NULL,
  `PrescriptionID` varchar(20) DEFAULT NULL,
  `PatientID` varchar(50) NOT NULL,
  `PrescriptionDate` date NOT NULL,
  `DentistID` varchar(50) NOT NULL,
  `Notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`id`, `PrescriptionID`, `PatientID`, `PrescriptionDate`, `DentistID`, `Notes`, `created_at`) VALUES
(1, 'PREID0001', 'PAT002', '2025-02-24', 'DENID001', 'Schedule a follow-up visit in one week to assess healing progress.\r\n', '2025-02-24 03:32:10'),
(2, 'PREID0002', 'PAT002', '2025-02-24', 'DENID001', 'Schedule a follow-up visit in one week to assess healing progress.\r\n', '2025-02-24 03:35:29'),
(3, 'PREID0003', 'PAT013', '2025-02-28', 'DENID001', 'Meron isang part sa documentation na eto lang yung laman', '2025-02-28 05:07:52'),
(4, 'PREID0004', 'PAT014', '2025-03-01', 'DENID002', 'Notify the clinic if any signs of allergic reaction occur and avoid alcohol while taking this medication.', '2025-03-01 12:57:12'),
(5, 'PREID0005', 'PAT016', '2025-03-03', 'DENID002', 'take this per day', '2025-03-03 03:02:17');

-- --------------------------------------------------------

--
-- Table structure for table `prescription_medicines`
--

CREATE TABLE `prescription_medicines` (
  `id` int(11) NOT NULL,
  `MedicineID` varchar(20) DEFAULT NULL,
  `PrescriptionID` varchar(20) NOT NULL,
  `Medicine` varchar(100) NOT NULL,
  `Instructions` text DEFAULT NULL,
  `RefillStatus` varchar(50) DEFAULT NULL,
  `Dosage` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescription_medicines`
--

INSERT INTO `prescription_medicines` (`id`, `MedicineID`, `PrescriptionID`, `Medicine`, `Instructions`, `RefillStatus`, `Dosage`) VALUES
(1, 'MEDID001', 'PREID0001', 'Amoxicillin', 'Take with food to avoid stomach upset. Complete the full course even if symptoms improve', 'Not Allowed', '500 mg'),
(2, 'MEDID002', 'PREID0001', 'Norgesic', 'Take with food to avoid stomach upset. Complete the full course even if symptoms improve', 'Not Allowed', '500 mg'),
(3, 'MEDID003', 'PREID0002', 'Amoxicillin', 'Take with food to avoid stomach upset. Complete the full course even if symptoms improve.\r\n', 'Not Allowed', '500 mg'),
(4, 'MEDID004', 'PREID0003', 'Amoxicillin', 'mykie', 'Not Allowed', '500 mg'),
(5, 'MEDID005', 'PREID0003', 'Ibuprofen', 'test mykie', 'Not Allowed', '500 mg'),
(6, 'MEDID006', 'PREID0003', 'Norgesic', 'test earl', 'Not Allowed', '500 mg'),
(7, 'MEDID007', 'PREID0004', 'Penicillin', 'Take with a full glass of water\r\nComplete the full course of antibiotics, even if symptoms are gone\r\nTake on an empty stomach, either 1 hour or 2 hours after meal\r\nIf a dose is missed, take it as soon as possible unless it is almost time for the next dose. Do not double the dose', 'Allowed', '500 mg'),
(8, 'MEDID008', 'PREID0005', 'Penicillin', 'take this before sleep', 'Not Allowed', '500 mg');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `SupplierID` varchar(10) NOT NULL,
  `SupplierName` varchar(255) NOT NULL,
  `Status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `ContactPerson` varchar(255) NOT NULL,
  `ContactNumber` varchar(20) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `City` varchar(100) NOT NULL,
  `PostalCode` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`SupplierID`, `SupplierName`, `Status`, `ContactPerson`, `ContactNumber`, `Email`, `Address`, `City`, `PostalCode`) VALUES
('SUP0001', 'Charles', 'Inactive', 'Miguel Polison', '123123123', 'hope143@gmail.com', 'Street ni migs', 'City ni Migs', '1003'),
('SUP0002', 'Charles', 'Inactive', 'Miguel Polison', '123123123', 'hope143@gmail.com', 'Street ni migs', 'City ni Migs', '1003'),
('SUP0003', 'Charles', 'Inactive', 'Miguel Polison', '123123123', 'hope143@gmail.com', 'Street ni migs', 'City ni Migs', '1003');

--
-- Triggers `suppliers`
--
DELIMITER $$
CREATE TRIGGER `before_insert_supplier` BEFORE INSERT ON `suppliers` FOR EACH ROW BEGIN
    DECLARE new_id INT;

    INSERT INTO supplier_counter VALUES (NULL);
    SET new_id = LAST_INSERT_ID();

    SET NEW.SupplierID = CONCAT('SUP', LPAD(new_id, 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_counter`
--

CREATE TABLE `supplier_counter` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_counter`
--

INSERT INTO `supplier_counter` (`id`) VALUES
(1),
(2),
(3);

-- --------------------------------------------------------

--
-- Table structure for table `usage_counter`
--

CREATE TABLE `usage_counter` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usage_counter`
--

INSERT INTO `usage_counter` (`id`) VALUES
(3),
(4),
(5),
(6),
(7);

-- --------------------------------------------------------

--
-- Table structure for table `usage_records`
--

CREATE TABLE `usage_records` (
  `UsageID` varchar(10) NOT NULL,
  `PatientID` varchar(10) NOT NULL,
  `ProcedureName` varchar(255) NOT NULL,
  `DateOfProcedure` date NOT NULL,
  `ItemUsed` varchar(255) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `UnitType` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usage_records`
--

INSERT INTO `usage_records` (`UsageID`, `PatientID`, `ProcedureName`, `DateOfProcedure`, `ItemUsed`, `Quantity`, `UnitType`) VALUES
('USG0003', 'PAT002', 'Billing tooth', '2025-02-22', 'Nitrile Gloves', 10, '2'),
('USG0004', 'PAT002', 'Billing tooth', '2025-02-22', 'Miguel', 2, '2'),
('USG0005', 'PAT013', 'Consulation', '2025-02-28', 'Miguel', 10, 'pairs'),
('USG0006', 'PAT014', 'Tooth Extraction', '2025-03-01', 'Penicillin', 1, 'packs'),
('USG0007', 'PAT016', 'Tooth Extraction', '2025-03-03', 'Penicillin', 14, 'mg');

--
-- Triggers `usage_records`
--
DELIMITER $$
CREATE TRIGGER `before_insert_usage` BEFORE INSERT ON `usage_records` FOR EACH ROW BEGIN
    DECLARE new_id INT;
    
    INSERT INTO usage_counter VALUES (NULL);
    SET new_id = LAST_INSERT_ID();
    
    SET NEW.UsageID = CONCAT('USG', LPAD(new_id, 4, '0'));
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`LogID`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`AppointmentID`);

--
-- Indexes for table `appointmentbilling`
--
ALTER TABLE `appointmentbilling`
  ADD PRIMARY KEY (`BillingID`),
  ADD KEY `fk_appointmentbilling` (`AppointmentID`);

--
-- Indexes for table `appointment_pricing`
--
ALTER TABLE `appointment_pricing`
  ADD PRIMARY KEY (`ProcedureID`);

--
-- Indexes for table `billingspecialist`
--
ALTER TABLE `billingspecialist`
  ADD PRIMARY KEY (`SpecialistID`);

--
-- Indexes for table `billingspecialist_working_hour`
--
ALTER TABLE `billingspecialist_working_hour`
  ADD PRIMARY KEY (`SpecialistID`);

--
-- Indexes for table `card`
--
ALTER TABLE `card`
  ADD PRIMARY KEY (`CardID`),
  ADD KEY `PatientID` (`PatientID`);

--
-- Indexes for table `dentist`
--
ALTER TABLE `dentist`
  ADD KEY `idx_dentist_id` (`DentistID`);

--
-- Indexes for table `dentistassistant`
--
ALTER TABLE `dentistassistant`
  ADD KEY `idx_assistant_id` (`AssistantID`);

--
-- Indexes for table `dentistassistant_working_hour`
--
ALTER TABLE `dentistassistant_working_hour`
  ADD PRIMARY KEY (`AssistantID`);

--
-- Indexes for table `diagnostictreatment`
--
ALTER TABLE `diagnostictreatment`
  ADD PRIMARY KEY (`laboratoryID`),
  ADD KEY `PatientID` (`PatientID`),
  ADD KEY `DentistID` (`DentistID`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`InventoryID`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`InvoiceID`),
  ADD KEY `fk_invoice` (`BillingID`);

--
-- Indexes for table `medicalrecord`
--
ALTER TABLE `medicalrecord`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_patient` (`PatientID`),
  ADD KEY `fk_assistant` (`AssistantID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`PatientID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `idx_patient_id` (`PatientID`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `fk_payments` (`BillingID`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `PrescriptionID` (`PrescriptionID`);

--
-- Indexes for table `prescription_medicines`
--
ALTER TABLE `prescription_medicines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `MedicineID` (`MedicineID`),
  ADD KEY `PrescriptionID` (`PrescriptionID`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`SupplierID`);

--
-- Indexes for table `supplier_counter`
--
ALTER TABLE `supplier_counter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usage_counter`
--
ALTER TABLE `usage_counter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usage_records`
--
ALTER TABLE `usage_records`
  ADD PRIMARY KEY (`UsageID`),
  ADD KEY `PatientID` (`PatientID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=601;

--
-- AUTO_INCREMENT for table `medicalrecord`
--
ALTER TABLE `medicalrecord`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `prescription_medicines`
--
ALTER TABLE `prescription_medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `supplier_counter`
--
ALTER TABLE `supplier_counter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usage_counter`
--
ALTER TABLE `usage_counter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointmentbilling`
--
ALTER TABLE `appointmentbilling`
  ADD CONSTRAINT `fk_appointmentbilling` FOREIGN KEY (`AppointmentID`) REFERENCES `appointment` (`AppointmentID`) ON DELETE CASCADE;

--
-- Constraints for table `billingspecialist_working_hour`
--
ALTER TABLE `billingspecialist_working_hour`
  ADD CONSTRAINT `billingspecialist_working_hour_ibfk_1` FOREIGN KEY (`SpecialistID`) REFERENCES `billingspecialist` (`SpecialistID`) ON DELETE CASCADE;

--
-- Constraints for table `card`
--
ALTER TABLE `card`
  ADD CONSTRAINT `card_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`);

--
-- Constraints for table `dentistassistant_working_hour`
--
ALTER TABLE `dentistassistant_working_hour`
  ADD CONSTRAINT `dentistassistant_working_hour_ibfk_1` FOREIGN KEY (`AssistantID`) REFERENCES `dentistassistant` (`AssistantID`) ON DELETE CASCADE;

--
-- Constraints for table `diagnostictreatment`
--
ALTER TABLE `diagnostictreatment`
  ADD CONSTRAINT `diagnostictreatment_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`),
  ADD CONSTRAINT `diagnostictreatment_ibfk_2` FOREIGN KEY (`DentistID`) REFERENCES `dentist` (`DentistID`);

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_invoice` FOREIGN KEY (`BillingID`) REFERENCES `appointmentbilling` (`BillingID`) ON DELETE CASCADE;

--
-- Constraints for table `medicalrecord`
--
ALTER TABLE `medicalrecord`
  ADD CONSTRAINT `fk_assistant` FOREIGN KEY (`AssistantID`) REFERENCES `dentistassistant` (`AssistantID`),
  ADD CONSTRAINT `fk_patient` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments` FOREIGN KEY (`BillingID`) REFERENCES `appointmentbilling` (`BillingID`) ON DELETE CASCADE;

--
-- Constraints for table `prescription_medicines`
--
ALTER TABLE `prescription_medicines`
  ADD CONSTRAINT `prescription_medicines_ibfk_1` FOREIGN KEY (`PrescriptionID`) REFERENCES `prescription` (`PrescriptionID`);

--
-- Constraints for table `usage_records`
--
ALTER TABLE `usage_records`
  ADD CONSTRAINT `usage_records_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
