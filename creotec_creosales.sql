-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2025 at 02:37 AM
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
-- Database: `creotec_creosales`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contactperson`
--

CREATE TABLE `tbl_contactperson` (
  `contactperson_id` int(11) NOT NULL,
  `contactperson_name` varchar(225) DEFAULT NULL,
  `contactperson_position` varchar(225) DEFAULT NULL,
  `contactperson_email` varchar(225) DEFAULT NULL,
  `contactperson_number` varchar(225) DEFAULT NULL,
  `potentialcustomer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_contactperson`
--

INSERT INTO `tbl_contactperson` (`contactperson_id`, `contactperson_name`, `contactperson_position`, `contactperson_email`, `contactperson_number`, `potentialcustomer_id`) VALUES
(1, 'John Pacheco', 'Decision Maker', 'abc@gmail.com', '0912345678', 1),
(2, 'Mylene Pecson', 'Principal', 'mypxn@gmail.com', '09178702047', 2),
(3, 'Susan V. Torres', 'Champion', 'sansutorres@gmail.com', '09989943940', 3),
(4, 'Jyr Marie Reyes', 'Influencer', 'abcde@gmail.com', '8531-80311', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_credentials`
--

CREATE TABLE `tbl_credentials` (
  `credentials_id` int(11) NOT NULL,
  `credentials_username` varchar(20) DEFAULT NULL,
  `credentials_password` varchar(255) NOT NULL,
  `credentials_email` varchar(50) DEFAULT NULL,
  `credentials_code` int(11) DEFAULT NULL,
  `user_type` int(1) DEFAULT NULL COMMENT '1 = user, 0 = admin',
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_credentials`
--

INSERT INTO `tbl_credentials` (`credentials_id`, `credentials_username`, `credentials_password`, `credentials_email`, `credentials_code`, `user_type`, `user_id`) VALUES
(1, 'creotec_thessb11', '$2y$10$MjgDdIJgRnABeS9sELoq5uhrXz460mSli9fKSbuwi.58HAU7NrJFa', 'thessb@creotec.com.ph', 0, 0, 1),
(2, 'creotecjenn', '$2y$10$4Gr59bCOe8IowdPMlqgBAuIUj3KGjRj8Xydb/GlG1XLB7R/Y6kp5e', 'jennmatuto@gmail.com', 0, 1, 2),
(3, 'Sales@0408', '$2y$10$G5ZM21.rhHE0sshob1nN7e4a7.Hl2vk8WuS46uQfDtzjlhs/v.Aum', 'miguelemmanuel316@gmail ', 0, 1, 3),
(4, 'Maya Abulencia', '$2y$10$juzJR.XsRqVejfiF.icwEuqo3I7RpyzvyDsTdvBGKgr9ctIvRvYsW', 'creotec.maya@gmail.com', 0, 1, 4),
(5, 'test', '$2y$10$Jh4S64Ws6Wy/rB4iU9BOZuRec5nsUxrzuLJ9EDSwCgFR32xBED4ou', 'rn.brmdz@gmail.com', 0, 0, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_criteria`
--

CREATE TABLE `tbl_criteria` (
  `criteria_id` int(11) NOT NULL,
  `criteria_criterion` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_criteria`
--

INSERT INTO `tbl_criteria` (`criteria_id`, `criteria_criterion`) VALUES
(1, 'Appearance'),
(2, 'Overall School Appearance'),
(3, 'Reputation'),
(4, 'School Population and Tuition'),
(5, 'Decision Making');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_evaluation`
--

CREATE TABLE `tbl_evaluation` (
  `evaluation_id` int(11) NOT NULL,
  `evaluation_rating` varchar(11) DEFAULT NULL,
  `evaluation_result` varchar(20) DEFAULT NULL,
  `evaluation_date` date DEFAULT NULL,
  `potentialcustomer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_evaluation`
--

INSERT INTO `tbl_evaluation` (`evaluation_id`, `evaluation_rating`, `evaluation_result`, `evaluation_date`, `potentialcustomer_id`) VALUES
(1, '3.7', 'Passed', '2025-04-16', 1),
(2, '4.5', 'Passed', '2025-04-16', 2),
(3, '4.6', 'Passed', '2025-04-20', 3),
(4, '4.4', 'Passed', '2025-04-22', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_existingfacilities`
--

CREATE TABLE `tbl_existingfacilities` (
  `existingfacilities_id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  `potentialcustomer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_existingfacilities`
--

INSERT INTO `tbl_existingfacilities` (`existingfacilities_id`, `facility_id`, `potentialcustomer_id`) VALUES
(1, 1, 1),
(2, 3, 1),
(3, 2, 2),
(4, 1, 3),
(5, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_existingpartners`
--

CREATE TABLE `tbl_existingpartners` (
  `existingpartners_id` int(11) NOT NULL,
  `existingpartners_years` int(11) DEFAULT NULL,
  `partners_id` int(11) NOT NULL,
  `potentialcustomer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_existingpartners`
--

INSERT INTO `tbl_existingpartners` (`existingpartners_id`, `existingpartners_years`, `partners_id`, `potentialcustomer_id`) VALUES
(1, 3, 1, 1),
(2, 2, 1, 2),
(3, 1, 2, 3),
(4, 4, 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_existingprograms`
--

CREATE TABLE `tbl_existingprograms` (
  `existingprograms_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `potentialcustomer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_existingprograms`
--

INSERT INTO `tbl_existingprograms` (`existingprograms_id`, `program_id`, `potentialcustomer_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 7, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_existingservices`
--

CREATE TABLE `tbl_existingservices` (
  `existingservices_id` int(11) NOT NULL,
  `services_id` int(11) NOT NULL,
  `potentialcustomer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_existingservices`
--

INSERT INTO `tbl_existingservices` (`existingservices_id`, `services_id`, `potentialcustomer_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 4, 2),
(4, 1, 3),
(5, 1, 4),
(6, 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_facility`
--

CREATE TABLE `tbl_facility` (
  `facility_id` int(11) NOT NULL,
  `facility_type` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_facility`
--

INSERT INTO `tbl_facility` (`facility_id`, `facility_type`) VALUES
(1, 'Robotics Lab / Bldg'),
(2, 'Robotics kits only'),
(3, 'Industry Hub');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gradelevel`
--

CREATE TABLE `tbl_gradelevel` (
  `gradelevel_id` int(11) NOT NULL,
  `gradelevel_type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_gradelevel`
--

INSERT INTO `tbl_gradelevel` (`gradelevel_id`, `gradelevel_type`) VALUES
(1, 'Kinder'),
(2, 'Grade 1'),
(3, 'Grade 2'),
(4, 'Grade 3'),
(5, 'Grade 4'),
(6, 'Grade 5'),
(7, 'Grade 6'),
(8, 'Grade 7'),
(9, 'Grade 8'),
(10, 'Grade 9'),
(11, 'Grade 10'),
(12, 'Grade 11'),
(13, 'Grade 12');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_images`
--

CREATE TABLE `tbl_images` (
  `images_id` int(11) NOT NULL,
  `images_name` varchar(255) DEFAULT NULL,
  `images_path` varchar(255) DEFAULT NULL,
  `images_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `evaluation_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_images`
--

INSERT INTO `tbl_images` (`images_id`, `images_name`, `images_path`, `images_date`, `evaluation_id`) VALUES
(1, '1744768231_1000001460.jpg', 'https://creosales.creoteconlinelearning.com/Mobile/Uploads/1744768231_1000001460.jpg', '2025-04-16 01:50:31', 1),
(2, '1744769164_1000082848.jpg', 'https://creosales.creoteconlinelearning.com/Mobile/Uploads/1744769164_1000082848.jpg', '2025-04-16 02:06:04', 2),
(3, '1745280991_IMG_20250304_111038.jpg', 'https://creosales.creoteconlinelearning.com/Mobile/Uploads/1745280991_IMG_20250304_111038.jpg', '2025-04-22 00:16:31', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_municipality`
--

CREATE TABLE `tbl_municipality` (
  `municipality_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `municipality_name` varchar(27) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_municipality`
--

INSERT INTO `tbl_municipality` (`municipality_id`, `province_id`, `municipality_name`) VALUES
(1, 2, 'Adams'),
(2, 2, 'Bacarra'),
(3, 2, 'Badoc'),
(4, 2, 'Bangui'),
(5, 2, 'City of Batac'),
(6, 2, 'Burgos'),
(7, 2, 'Carasi'),
(8, 2, 'Currimao'),
(9, 2, 'Dingras'),
(10, 2, 'Dumalneg'),
(11, 2, 'Banna'),
(12, 2, 'City of Laoag'),
(13, 2, 'Marcos'),
(14, 2, 'Nueva Era'),
(15, 2, 'Pagudpud'),
(16, 2, 'Paoay'),
(17, 2, 'Pasuquin'),
(18, 2, 'Piddig'),
(19, 2, 'Pinili'),
(20, 2, 'San Nicolas'),
(21, 2, 'Sarrat'),
(22, 2, 'Solsona'),
(23, 2, 'Vintar'),
(24, 3, 'Alilem'),
(25, 3, 'Banayoyo'),
(26, 3, 'Bantay'),
(27, 3, 'Burgos'),
(28, 3, 'Cabugao'),
(29, 3, 'City of Candon'),
(30, 3, 'Caoayan'),
(31, 3, 'Cervantes'),
(32, 3, 'Galimuyod'),
(33, 3, 'Gregorio del Pilar'),
(34, 3, 'Lidlidda'),
(35, 3, 'Magsingal'),
(36, 3, 'Nagbukel'),
(37, 3, 'Narvacan'),
(38, 3, 'Quirino'),
(39, 3, 'Salcedo'),
(40, 3, 'San Emilio'),
(41, 3, 'San Esteban'),
(42, 3, 'San Ildefonso'),
(43, 3, 'San Juan'),
(44, 3, 'San Vicente'),
(45, 3, 'Santa'),
(46, 3, 'Santa Catalina'),
(47, 3, 'Santa Cruz'),
(48, 3, 'Santa Lucia'),
(49, 3, 'Santa Maria'),
(50, 3, 'Santiago'),
(51, 3, 'Santo Domingo'),
(52, 3, 'Sigay'),
(53, 3, 'Sinait'),
(54, 3, 'Sugpon'),
(55, 3, 'Suyo'),
(56, 3, 'Tagudin'),
(57, 3, 'City of Vigan'),
(58, 4, 'Agoo'),
(59, 4, 'Aringay'),
(60, 4, 'Bacnotan'),
(61, 4, 'Bagulin'),
(62, 4, 'Balaoan'),
(63, 4, 'Bangar'),
(64, 4, 'Bauang'),
(65, 4, 'Burgos'),
(66, 4, 'Caba'),
(67, 4, 'Luna'),
(68, 4, 'Naguilian'),
(69, 4, 'Pugo'),
(70, 4, 'Rosario'),
(71, 4, 'City of San Fernando'),
(72, 4, 'San Gabriel'),
(73, 4, 'San Juan'),
(74, 4, 'Santo Tomas'),
(75, 4, 'Santol'),
(76, 4, 'Sudipen'),
(77, 4, 'Tubao'),
(78, 5, 'Agno'),
(79, 5, 'Aguilar'),
(80, 5, 'City of Alaminos'),
(81, 5, 'Alcala'),
(82, 5, 'Anda'),
(83, 5, 'Asingan'),
(84, 5, 'Balungao'),
(85, 5, 'Bani'),
(86, 5, 'Basista'),
(87, 5, 'Bautista'),
(88, 5, 'Bayambang'),
(89, 5, 'Binalonan'),
(90, 5, 'Binmaley'),
(91, 5, 'Bolinao'),
(92, 5, 'Bugallon'),
(93, 5, 'Burgos'),
(94, 5, 'Calasiao'),
(95, 5, 'City of Dagupan'),
(96, 5, 'Dasol'),
(97, 5, 'Infanta'),
(98, 5, 'Labrador'),
(99, 5, 'Lingayen'),
(100, 5, 'Mabini'),
(101, 5, 'Malasiqui'),
(102, 5, 'Manaoag'),
(103, 5, 'Mangaldan'),
(104, 5, 'Mangatarem'),
(105, 5, 'Mapandan'),
(106, 5, 'Natividad'),
(107, 5, 'Pozorrubio'),
(108, 5, 'Rosales'),
(109, 5, 'City of San Carlos'),
(110, 5, 'San Fabian'),
(111, 5, 'San Jacinto'),
(112, 5, 'San Manuel'),
(113, 5, 'San Nicolas'),
(114, 5, 'San Quintin'),
(115, 5, 'Santa Barbara'),
(116, 5, 'Santa Maria'),
(117, 5, 'Santo Tomas'),
(118, 5, 'Sison'),
(119, 5, 'Sual'),
(120, 5, 'Tayug'),
(121, 5, 'Umingan'),
(122, 5, 'Urbiztondo'),
(123, 5, 'City of Urdaneta'),
(124, 5, 'Villasis'),
(125, 5, 'Laoac'),
(126, 6, 'Basco'),
(127, 6, 'Itbayat'),
(128, 6, 'Ivana'),
(129, 6, 'Mahatao'),
(130, 6, 'Sabtang'),
(131, 6, 'Uyugan'),
(132, 7, 'Abulug'),
(133, 7, 'Alcala'),
(134, 7, 'Allacapan'),
(135, 7, 'Amulung'),
(136, 7, 'Aparri'),
(137, 7, 'Baggao'),
(138, 7, 'Ballesteros'),
(139, 7, 'Buguey'),
(140, 7, 'Calayan'),
(141, 7, 'Camalaniugan'),
(142, 7, 'Claveria'),
(143, 7, 'Enrile'),
(144, 7, 'Gattaran'),
(145, 7, 'Gonzaga'),
(146, 7, 'Iguig'),
(147, 7, 'Lal-Lo'),
(148, 7, 'Lasam'),
(149, 7, 'Pamplona'),
(150, 7, 'Peñablanca'),
(151, 7, 'Piat'),
(152, 7, 'Rizal'),
(153, 7, 'Sanchez-Mira'),
(154, 7, 'Santa Ana'),
(155, 7, 'Santa Praxedes'),
(156, 7, 'Santa Teresita'),
(157, 7, 'Santo Niño'),
(158, 7, 'Solana'),
(159, 7, 'Tuao'),
(160, 7, 'Tuguegarao City'),
(161, 8, 'Alicia'),
(162, 8, 'Angadanan'),
(163, 8, 'Aurora'),
(164, 8, 'Benito Soliven'),
(165, 8, 'Burgos'),
(166, 8, 'Cabagan'),
(167, 8, 'Cabatuan'),
(168, 8, 'City of Cauayan'),
(169, 8, 'Cordon'),
(170, 8, 'Dinapigue'),
(171, 8, 'Divilacan'),
(172, 8, 'Echague'),
(173, 8, 'Gamu'),
(174, 8, 'City of Ilagan'),
(175, 8, 'Jones'),
(176, 8, 'Luna'),
(177, 8, 'Maconacon'),
(178, 8, 'Delfin Albano'),
(179, 8, 'Mallig'),
(180, 8, 'Naguilian'),
(181, 8, 'Palanan'),
(182, 8, 'Quezon'),
(183, 8, 'Quirino'),
(184, 8, 'Ramon'),
(185, 8, 'Reina Mercedes'),
(186, 8, 'Roxas'),
(187, 8, 'San Agustin'),
(188, 8, 'San Guillermo'),
(189, 8, 'San Isidro'),
(190, 8, 'San Manuel'),
(191, 8, 'San Mariano'),
(192, 8, 'San Mateo'),
(193, 8, 'San Pablo'),
(194, 8, 'Santa Maria'),
(195, 8, 'City of Santiago'),
(196, 8, 'Santo Tomas'),
(197, 8, 'Tumauini'),
(198, 9, 'Ambaguio'),
(199, 9, 'Aritao'),
(200, 9, 'Bagabag'),
(201, 9, 'Bambang'),
(202, 9, 'Bayombong'),
(203, 9, 'Diadi'),
(204, 9, 'Dupax del Norte'),
(205, 9, 'Dupax del Sur'),
(206, 9, 'Kasibu'),
(207, 9, 'Kayapa'),
(208, 9, 'Quezon'),
(209, 9, 'Santa Fe'),
(210, 9, 'Solano'),
(211, 9, 'Villaverde'),
(212, 9, 'Alfonso Castaneda'),
(213, 10, 'Aglipay'),
(214, 10, 'Cabarroguis'),
(215, 10, 'Diffun'),
(216, 10, 'Maddela'),
(217, 10, 'Saguday'),
(218, 10, 'Nagtipunan'),
(219, 11, 'Abucay'),
(220, 11, 'Bagac'),
(221, 11, 'City of Balanga'),
(222, 11, 'Dinalupihan'),
(223, 11, 'Hermosa'),
(224, 11, 'Limay'),
(225, 11, 'Mariveles'),
(226, 11, 'Morong'),
(227, 11, 'Orani'),
(228, 11, 'Orion'),
(229, 11, 'Pilar'),
(230, 11, 'Samal'),
(231, 12, 'Angat'),
(232, 12, 'Balagtas'),
(233, 12, 'Baliuag'),
(234, 12, 'Bocaue'),
(235, 12, 'Bulacan'),
(236, 12, 'Bustos'),
(237, 12, 'Calumpit'),
(238, 12, 'Guiguinto'),
(239, 12, 'Hagonoy'),
(240, 12, 'City of Malolos'),
(241, 12, 'Marilao'),
(242, 12, 'City of Meycauayan'),
(243, 12, 'Norzagaray'),
(244, 12, 'Obando'),
(245, 12, 'Pandi'),
(246, 12, 'Paombong'),
(247, 12, 'Plaridel'),
(248, 12, 'Pulilan'),
(249, 12, 'San Ildefonso'),
(250, 12, 'City of San Jose Del Monte'),
(251, 12, 'San Miguel'),
(252, 12, 'San Rafael'),
(253, 12, 'Santa Maria'),
(254, 12, 'Doña Remedios Trinidad'),
(255, 13, 'Aliaga'),
(256, 13, 'Bongabon'),
(257, 13, 'City of Cabanatuan'),
(258, 13, 'Cabiao'),
(259, 13, 'Carranglan'),
(260, 13, 'Cuyapo'),
(261, 13, 'Gabaldon'),
(262, 13, 'City of Gapan'),
(263, 13, 'General Mamerto Natividad'),
(264, 13, 'General Tinio'),
(265, 13, 'Guimba'),
(266, 13, 'Jaen'),
(267, 13, 'Laur'),
(268, 13, 'Licab'),
(269, 13, 'Llanera'),
(270, 13, 'Lupao'),
(271, 13, 'Science City of Muñoz'),
(272, 13, 'Nampicuan'),
(273, 13, 'City of Palayan'),
(274, 13, 'Pantabangan'),
(275, 13, 'Peñaranda'),
(276, 13, 'Quezon'),
(277, 13, 'Rizal'),
(278, 13, 'San Antonio'),
(279, 13, 'San Isidro'),
(280, 13, 'San Jose City'),
(281, 13, 'San Leonardo'),
(282, 13, 'Santa Rosa'),
(283, 13, 'Santo Domingo'),
(284, 13, 'Talavera'),
(285, 13, 'Talugtug'),
(286, 13, 'Zaragoza'),
(287, 14, 'City of Angeles'),
(288, 14, 'Apalit'),
(289, 14, 'Arayat'),
(290, 14, 'Bacolor'),
(291, 14, 'Candaba'),
(292, 14, 'Floridablanca'),
(293, 14, 'Guagua'),
(294, 14, 'Lubao'),
(295, 14, 'Mabalacat City'),
(296, 14, 'Macabebe'),
(297, 14, 'Magalang'),
(298, 14, 'Masantol'),
(299, 14, 'Mexico'),
(300, 14, 'Minalin'),
(301, 14, 'Porac'),
(302, 14, 'City of San Fernando'),
(303, 14, 'San Luis'),
(304, 14, 'San Simon'),
(305, 14, 'Santa Ana'),
(306, 14, 'Santa Rita'),
(307, 14, 'Santo Tomas'),
(308, 14, 'Sasmuan'),
(309, 15, 'Anao'),
(310, 15, 'Bamban'),
(311, 15, 'Camiling'),
(312, 15, 'Capas'),
(313, 15, 'Concepcion'),
(314, 15, 'Gerona'),
(315, 15, 'La Paz'),
(316, 15, 'Mayantoc'),
(317, 15, 'Moncada'),
(318, 15, 'Paniqui'),
(319, 15, 'Pura'),
(320, 15, 'Ramos'),
(321, 15, 'San Clemente'),
(322, 15, 'San Manuel'),
(323, 15, 'Santa Ignacia'),
(324, 15, 'City of Tarlac'),
(325, 15, 'Victoria'),
(326, 15, 'San Jose'),
(327, 16, 'Botolan'),
(328, 16, 'Cabangan'),
(329, 16, 'Candelaria'),
(330, 16, 'Castillejos'),
(331, 16, 'Iba'),
(332, 16, 'Masinloc'),
(333, 16, 'City of Olongapo'),
(334, 16, 'Palauig'),
(335, 16, 'San Antonio'),
(336, 16, 'San Felipe'),
(337, 16, 'San Marcelino'),
(338, 16, 'San Narciso'),
(339, 16, 'Santa Cruz'),
(340, 16, 'Subic'),
(341, 17, 'Baler'),
(342, 17, 'Casiguran'),
(343, 17, 'Dilasag'),
(344, 17, 'Dinalungan'),
(345, 17, 'Dingalan'),
(346, 17, 'Dipaculao'),
(347, 17, 'Maria Aurora'),
(348, 17, 'San Luis'),
(349, 18, 'Agoncillo'),
(350, 18, 'Alitagtag'),
(351, 18, 'Balayan'),
(352, 18, 'Balete'),
(353, 18, 'Batangas City'),
(354, 18, 'Bauan'),
(355, 18, 'Calaca'),
(356, 18, 'Calatagan'),
(357, 18, 'Cuenca'),
(358, 18, 'Ibaan'),
(359, 18, 'Laurel'),
(360, 18, 'Lemery'),
(361, 18, 'Lian'),
(362, 18, 'City of Lipa'),
(363, 18, 'Lobo'),
(364, 18, 'Mabini'),
(365, 18, 'Malvar'),
(366, 18, 'Mataasnakahoy'),
(367, 18, 'Nasugbu'),
(368, 18, 'Padre Garcia'),
(369, 18, 'Rosario'),
(370, 18, 'San Jose'),
(371, 18, 'San Juan'),
(372, 18, 'San Luis'),
(373, 18, 'San Nicolas'),
(374, 18, 'San Pascual'),
(375, 18, 'Santa Teresita'),
(376, 18, 'City of Sto. Tomas'),
(377, 18, 'Taal'),
(378, 18, 'Talisay'),
(379, 18, 'City of Tanauan'),
(380, 18, 'Taysan'),
(381, 18, 'Tingloy'),
(382, 18, 'Tuy'),
(383, 19, 'Alfonso'),
(384, 19, 'Amadeo'),
(385, 19, 'City of Bacoor'),
(386, 19, 'Carmona'),
(387, 19, 'City of Cavite'),
(388, 19, 'City of Dasmariñas'),
(389, 19, 'General Emilio Aguinaldo'),
(390, 19, 'City of General Trias'),
(391, 19, 'City of Imus'),
(392, 19, 'Indang'),
(393, 19, 'Kawit'),
(394, 19, 'Magallanes'),
(395, 19, 'Maragondon'),
(396, 19, 'Mendez'),
(397, 19, 'Naic'),
(398, 19, 'Noveleta'),
(399, 19, 'Rosario'),
(400, 19, 'Silang'),
(401, 19, 'City of Tagaytay'),
(402, 19, 'Tanza'),
(403, 19, 'Ternate'),
(404, 19, 'City of Trece Martires'),
(405, 19, 'Gen. Mariano Alvarez'),
(406, 20, 'Alaminos'),
(407, 20, 'Bay'),
(408, 20, 'City of Biñan'),
(409, 20, 'City of Cabuyao'),
(410, 20, 'City of Calamba'),
(411, 20, 'Calauan'),
(412, 20, 'Cavinti'),
(413, 20, 'Famy'),
(414, 20, 'Kalayaan'),
(415, 20, 'Liliw'),
(416, 20, 'Los Baños'),
(417, 20, 'Luisiana'),
(418, 20, 'Lumban'),
(419, 20, 'Mabitac'),
(420, 20, 'Magdalena'),
(421, 20, 'Majayjay'),
(422, 20, 'Nagcarlan'),
(423, 20, 'Paete'),
(424, 20, 'Pagsanjan'),
(425, 20, 'Pakil'),
(426, 20, 'Pangil'),
(427, 20, 'Pila'),
(428, 20, 'Rizal'),
(429, 20, 'City of San Pablo'),
(430, 20, 'City of San Pedro'),
(431, 20, 'Santa Cruz'),
(432, 20, 'Santa Maria'),
(433, 20, 'City of Santa Rosa'),
(434, 20, 'Siniloan'),
(435, 20, 'Victoria'),
(436, 21, 'Agdangan'),
(437, 21, 'Alabat'),
(438, 21, 'Atimonan'),
(439, 21, 'Buenavista'),
(440, 21, 'Burdeos'),
(441, 21, 'Calauag'),
(442, 21, 'Candelaria'),
(443, 21, 'Catanauan'),
(444, 21, 'Dolores'),
(445, 21, 'General Luna'),
(446, 21, 'General Nakar'),
(447, 21, 'Guinayangan'),
(448, 21, 'Gumaca'),
(449, 21, 'Infanta'),
(450, 21, 'Jomalig'),
(451, 21, 'Lopez'),
(452, 21, 'Lucban'),
(453, 21, 'City of Lucena'),
(454, 21, 'Macalelon'),
(455, 21, 'Mauban'),
(456, 21, 'Mulanay'),
(457, 21, 'Padre Burgos'),
(458, 21, 'Pagbilao'),
(459, 21, 'Panukulan'),
(460, 21, 'Patnanungan'),
(461, 21, 'Perez'),
(462, 21, 'Pitogo'),
(463, 21, 'Plaridel'),
(464, 21, 'Polillo'),
(465, 21, 'Quezon'),
(466, 21, 'Real'),
(467, 21, 'Sampaloc'),
(468, 21, 'San Andres'),
(469, 21, 'San Antonio'),
(470, 21, 'San Francisco'),
(471, 21, 'San Narciso'),
(472, 21, 'Sariaya'),
(473, 21, 'Tagkawayan'),
(474, 21, 'City of Tayabas'),
(475, 21, 'Tiaong'),
(476, 21, 'Unisan'),
(477, 22, 'Angono'),
(478, 22, 'City of Antipolo'),
(479, 22, 'Baras'),
(480, 22, 'Binangonan'),
(481, 22, 'Cainta'),
(482, 22, 'Cardona'),
(483, 22, 'Jala-Jala'),
(484, 22, 'Rodriguez'),
(485, 22, 'Morong'),
(486, 22, 'Pililla'),
(487, 22, 'San Mateo'),
(488, 22, 'Tanay'),
(489, 22, 'Taytay'),
(490, 22, 'Teresa'),
(491, 23, 'Boac'),
(492, 23, 'Buenavista'),
(493, 23, 'Gasan'),
(494, 23, 'Mogpog'),
(495, 23, 'Santa Cruz'),
(496, 23, 'Torrijos'),
(497, 24, 'Abra De Ilog'),
(498, 24, 'Calintaan'),
(499, 24, 'Looc'),
(500, 24, 'Lubang'),
(501, 24, 'Magsaysay'),
(502, 24, 'Mamburao'),
(503, 24, 'Paluan'),
(504, 24, 'Rizal'),
(505, 24, 'Sablayan'),
(506, 24, 'San Jose'),
(507, 24, 'Santa Cruz'),
(508, 25, 'Baco'),
(509, 25, 'Bansud'),
(510, 25, 'Bongabong'),
(511, 25, 'Bulalacao'),
(512, 25, 'City of Calapan'),
(513, 25, 'Gloria'),
(514, 25, 'Mansalay'),
(515, 25, 'Naujan'),
(516, 25, 'Pinamalayan'),
(517, 25, 'Pola'),
(518, 25, 'Puerto Galera'),
(519, 25, 'Roxas'),
(520, 25, 'San Teodoro'),
(521, 25, 'Socorro'),
(522, 25, 'Victoria'),
(523, 26, 'Aborlan'),
(524, 26, 'Agutaya'),
(525, 26, 'Araceli'),
(526, 26, 'Balabac'),
(527, 26, 'Bataraza'),
(528, 26, 'Brooke\'S Point'),
(529, 26, 'Busuanga'),
(530, 26, 'Cagayancillo'),
(531, 26, 'Coron'),
(532, 26, 'Cuyo'),
(533, 26, 'Dumaran'),
(534, 26, 'El Nido'),
(535, 26, 'Linapacan'),
(536, 26, 'Magsaysay'),
(537, 26, 'Narra'),
(538, 26, 'City of Puerto Princesa'),
(539, 26, 'Quezon'),
(540, 26, 'Roxas'),
(541, 26, 'San Vicente'),
(542, 26, 'Taytay'),
(543, 26, 'Kalayaan'),
(544, 26, 'Culion'),
(545, 26, 'Rizal'),
(546, 26, 'Sofronio Española'),
(547, 27, 'Alcantara'),
(548, 27, 'Banton'),
(549, 27, 'Cajidiocan'),
(550, 27, 'Calatrava'),
(551, 27, 'Concepcion'),
(552, 27, 'Corcuera'),
(553, 27, 'Looc'),
(554, 27, 'Magdiwang'),
(555, 27, 'Odiongan'),
(556, 27, 'Romblon'),
(557, 27, 'San Agustin'),
(558, 27, 'San Andres'),
(559, 27, 'San Fernando'),
(560, 27, 'San Jose'),
(561, 27, 'Santa Fe'),
(562, 27, 'Ferrol'),
(563, 27, 'Santa Maria'),
(564, 28, 'Bacacay'),
(565, 28, 'Camalig'),
(566, 28, 'Daraga'),
(567, 28, 'Guinobatan'),
(568, 28, 'Jovellar'),
(569, 28, 'City of Legazpi'),
(570, 28, 'Libon'),
(571, 28, 'City of Ligao'),
(572, 28, 'Malilipot'),
(573, 28, 'Malinao'),
(574, 28, 'Manito'),
(575, 28, 'Oas'),
(576, 28, 'Pio Duran'),
(577, 28, 'Polangui'),
(578, 28, 'Rapu-Rapu'),
(579, 28, 'Santo Domingo'),
(580, 28, 'City of Tabaco'),
(581, 28, 'Tiwi'),
(582, 29, 'Basud'),
(583, 29, 'Capalonga'),
(584, 29, 'Daet'),
(585, 29, 'San Lorenzo Ruiz'),
(586, 29, 'Jose Panganiban'),
(587, 29, 'Labo'),
(588, 29, 'Mercedes'),
(589, 29, 'Paracale'),
(590, 29, 'San Vicente'),
(591, 29, 'Santa Elena'),
(592, 29, 'Talisay'),
(593, 29, 'Vinzons'),
(594, 30, 'Baao'),
(595, 30, 'Balatan'),
(596, 30, 'Bato'),
(597, 30, 'Bombon'),
(598, 30, 'Buhi'),
(599, 30, 'Bula'),
(600, 30, 'Cabusao'),
(601, 30, 'Calabanga'),
(602, 30, 'Camaligan'),
(603, 30, 'Canaman'),
(604, 30, 'Caramoan'),
(605, 30, 'Del Gallego'),
(606, 30, 'Gainza'),
(607, 30, 'Garchitorena'),
(608, 30, 'Goa'),
(609, 30, 'City of Iriga'),
(610, 30, 'Lagonoy'),
(611, 30, 'Libmanan'),
(612, 30, 'Lupi'),
(613, 30, 'Magarao'),
(614, 30, 'Milaor'),
(615, 30, 'Minalabac'),
(616, 30, 'Nabua'),
(617, 30, 'City of Naga'),
(618, 30, 'Ocampo'),
(619, 30, 'Pamplona'),
(620, 30, 'Pasacao'),
(621, 30, 'Pili'),
(622, 30, 'Presentacion'),
(623, 30, 'Ragay'),
(624, 30, 'Sagñay'),
(625, 30, 'San Fernando'),
(626, 30, 'San Jose'),
(627, 30, 'Sipocot'),
(628, 30, 'Siruma'),
(629, 30, 'Tigaon'),
(630, 30, 'Tinambac'),
(631, 31, 'Bagamanoc'),
(632, 31, 'Baras'),
(633, 31, 'Bato'),
(634, 31, 'Caramoran'),
(635, 31, 'Gigmoto'),
(636, 31, 'Pandan'),
(637, 31, 'Panganiban'),
(638, 31, 'San Andres'),
(639, 31, 'San Miguel'),
(640, 31, 'Viga'),
(641, 31, 'Virac'),
(642, 32, 'Aroroy'),
(643, 32, 'Baleno'),
(644, 32, 'Balud'),
(645, 32, 'Batuan'),
(646, 32, 'Cataingan'),
(647, 32, 'Cawayan'),
(648, 32, 'Claveria'),
(649, 32, 'Dimasalang'),
(650, 32, 'Esperanza'),
(651, 32, 'Mandaon'),
(652, 32, 'City of Masbate'),
(653, 32, 'Milagros'),
(654, 32, 'Mobo'),
(655, 32, 'Monreal'),
(656, 32, 'Palanas'),
(657, 32, 'Pio V. Corpuz'),
(658, 32, 'Placer'),
(659, 32, 'San Fernando'),
(660, 32, 'San Jacinto'),
(661, 32, 'San Pascual'),
(662, 32, 'Uson'),
(663, 33, 'Barcelona'),
(664, 33, 'Bulan'),
(665, 33, 'Bulusan'),
(666, 33, 'Casiguran'),
(667, 33, 'Castilla'),
(668, 33, 'Donsol'),
(669, 33, 'Gubat'),
(670, 33, 'Irosin'),
(671, 33, 'Juban'),
(672, 33, 'Magallanes'),
(673, 33, 'Matnog'),
(674, 33, 'Pilar'),
(675, 33, 'Prieto Diaz'),
(676, 33, 'Santa Magdalena'),
(677, 33, 'City of Sorsogon'),
(678, 34, 'Altavas'),
(679, 34, 'Balete'),
(680, 34, 'Banga'),
(681, 34, 'Batan'),
(682, 34, 'Buruanga'),
(683, 34, 'Ibajay'),
(684, 34, 'Kalibo'),
(685, 34, 'Lezo'),
(686, 34, 'Libacao'),
(687, 34, 'Madalag'),
(688, 34, 'Makato'),
(689, 34, 'Malay'),
(690, 34, 'Malinao'),
(691, 34, 'Nabas'),
(692, 34, 'New Washington'),
(693, 34, 'Numancia'),
(694, 34, 'Tangalan'),
(695, 35, 'Anini-Y'),
(696, 35, 'Barbaza'),
(697, 35, 'Belison'),
(698, 35, 'Bugasong'),
(699, 35, 'Caluya'),
(700, 35, 'Culasi'),
(701, 35, 'Tobias Fornier'),
(702, 35, 'Hamtic'),
(703, 35, 'Laua-An'),
(704, 35, 'Libertad'),
(705, 35, 'Pandan'),
(706, 35, 'Patnongon'),
(707, 35, 'San Jose'),
(708, 35, 'San Remigio'),
(709, 35, 'Sebaste'),
(710, 35, 'Sibalom'),
(711, 35, 'Tibiao'),
(712, 35, 'Valderrama'),
(713, 36, 'Cuartero'),
(714, 36, 'Dao'),
(715, 36, 'Dumalag'),
(716, 36, 'Dumarao'),
(717, 36, 'Ivisan'),
(718, 36, 'Jamindan'),
(719, 36, 'Ma-Ayon'),
(720, 36, 'Mambusao'),
(721, 36, 'Panay'),
(722, 36, 'Panitan'),
(723, 36, 'Pilar'),
(724, 36, 'Pontevedra'),
(725, 36, 'President Roxas'),
(726, 36, 'City of Roxas'),
(727, 36, 'Sapi-An'),
(728, 36, 'Sigma'),
(729, 36, 'Tapaz'),
(730, 37, 'Ajuy'),
(731, 37, 'Alimodian'),
(732, 37, 'Anilao'),
(733, 37, 'Badiangan'),
(734, 37, 'Balasan'),
(735, 37, 'Banate'),
(736, 37, 'Barotac Nuevo'),
(737, 37, 'Barotac Viejo'),
(738, 37, 'Batad'),
(739, 37, 'Bingawan'),
(740, 37, 'Cabatuan'),
(741, 37, 'Calinog'),
(742, 37, 'Carles'),
(743, 37, 'Concepcion'),
(744, 37, 'Dingle'),
(745, 37, 'Dueñas'),
(746, 37, 'Dumangas'),
(747, 37, 'Estancia'),
(748, 37, 'Guimbal'),
(749, 37, 'Igbaras'),
(750, 37, 'City of Iloilo'),
(751, 37, 'Janiuay'),
(752, 37, 'Lambunao'),
(753, 37, 'Leganes'),
(754, 37, 'Lemery'),
(755, 37, 'Leon'),
(756, 37, 'Maasin'),
(757, 37, 'Miagao'),
(758, 37, 'Mina'),
(759, 37, 'New Lucena'),
(760, 37, 'Oton'),
(761, 37, 'City of Passi'),
(762, 37, 'Pavia'),
(763, 37, 'Pototan'),
(764, 37, 'San Dionisio'),
(765, 37, 'San Enrique'),
(766, 37, 'San Joaquin'),
(767, 37, 'San Miguel'),
(768, 37, 'San Rafael'),
(769, 37, 'Santa Barbara'),
(770, 37, 'Sara'),
(771, 37, 'Tigbauan'),
(772, 37, 'Tubungan'),
(773, 37, 'Zarraga'),
(774, 38, 'City of Bacolod'),
(775, 38, 'City of Bago'),
(776, 38, 'Binalbagan'),
(777, 38, 'City of Cadiz'),
(778, 38, 'Calatrava'),
(779, 38, 'Candoni'),
(780, 38, 'Cauayan'),
(781, 38, 'Enrique B. Magalona'),
(782, 38, 'City of Escalante'),
(783, 38, 'City of Himamaylan'),
(784, 38, 'Hinigaran'),
(785, 38, 'Hinoba-an'),
(786, 38, 'Ilog'),
(787, 38, 'Isabela'),
(788, 38, 'City of Kabankalan'),
(789, 38, 'City of La Carlota'),
(790, 38, 'La Castellana'),
(791, 38, 'Manapla'),
(792, 38, 'Moises Padilla'),
(793, 38, 'Murcia'),
(794, 38, 'Pontevedra'),
(795, 38, 'Pulupandan'),
(796, 38, 'City of Sagay'),
(797, 38, 'City of San Carlos'),
(798, 38, 'San Enrique'),
(799, 38, 'City of Silay'),
(800, 38, 'City of Sipalay'),
(801, 38, 'City of Talisay'),
(802, 38, 'Toboso'),
(803, 38, 'Valladolid'),
(804, 38, 'City of Victorias'),
(805, 38, 'Salvador Benedicto'),
(806, 39, 'Buenavista'),
(807, 39, 'Jordan'),
(808, 39, 'Nueva Valencia'),
(809, 39, 'San Lorenzo'),
(810, 39, 'Sibunag'),
(811, 40, 'Alburquerque'),
(812, 40, 'Alicia'),
(813, 40, 'Anda'),
(814, 40, 'Antequera'),
(815, 40, 'Baclayon'),
(816, 40, 'Balilihan'),
(817, 40, 'Batuan'),
(818, 40, 'Bilar'),
(819, 40, 'Buenavista'),
(820, 40, 'Calape'),
(821, 40, 'Candijay'),
(822, 40, 'Carmen'),
(823, 40, 'Catigbian'),
(824, 40, 'Clarin'),
(825, 40, 'Corella'),
(826, 40, 'Cortes'),
(827, 40, 'Dagohoy'),
(828, 40, 'Danao'),
(829, 40, 'Dauis'),
(830, 40, 'Dimiao'),
(831, 40, 'Duero'),
(832, 40, 'Garcia Hernandez'),
(833, 40, 'Guindulman'),
(834, 40, 'Inabanga'),
(835, 40, 'Jagna'),
(836, 40, 'Getafe'),
(837, 40, 'Lila'),
(838, 40, 'Loay'),
(839, 40, 'Loboc'),
(840, 40, 'Loon'),
(841, 40, 'Mabini'),
(842, 40, 'Maribojoc'),
(843, 40, 'Panglao'),
(844, 40, 'Pilar'),
(845, 40, 'Pres. Carlos P. Garcia'),
(846, 40, 'Sagbayan'),
(847, 40, 'San Isidro'),
(848, 40, 'San Miguel'),
(849, 40, 'Sevilla'),
(850, 40, 'Sierra Bullones'),
(851, 40, 'Sikatuna'),
(852, 40, 'City of Tagbilaran'),
(853, 40, 'Talibon'),
(854, 40, 'Trinidad'),
(855, 40, 'Tubigon'),
(856, 40, 'Ubay'),
(857, 40, 'Valencia'),
(858, 40, 'Bien Unido'),
(859, 41, 'Alcantara'),
(860, 41, 'Alcoy'),
(861, 41, 'Alegria'),
(862, 41, 'Aloguinsan'),
(863, 41, 'Argao'),
(864, 41, 'Asturias'),
(865, 41, 'Badian'),
(866, 41, 'Balamban'),
(867, 41, 'Bantayan'),
(868, 41, 'Barili'),
(869, 41, 'City of Bogo'),
(870, 41, 'Boljoon'),
(871, 41, 'Borbon'),
(872, 41, 'City of Carcar'),
(873, 41, 'Carmen'),
(874, 41, 'Catmon'),
(875, 41, 'City of Cebu'),
(876, 41, 'Compostela'),
(877, 41, 'Consolacion'),
(878, 41, 'Cordova'),
(879, 41, 'Daanbantayan'),
(880, 41, 'Dalaguete'),
(881, 41, 'Danao City'),
(882, 41, 'Dumanjug'),
(883, 41, 'Ginatilan'),
(884, 41, 'City of Lapu-Lapu'),
(885, 41, 'Liloan'),
(886, 41, 'Madridejos'),
(887, 41, 'Malabuyoc'),
(888, 41, 'City of Mandaue'),
(889, 41, 'Medellin'),
(890, 41, 'Minglanilla'),
(891, 41, 'Moalboal'),
(892, 41, 'City of Naga'),
(893, 41, 'Oslob'),
(894, 41, 'Pilar'),
(895, 41, 'Pinamungajan'),
(896, 41, 'Poro'),
(897, 41, 'Ronda'),
(898, 41, 'Samboan'),
(899, 41, 'San Fernando'),
(900, 41, 'San Francisco'),
(901, 41, 'San Remigio'),
(902, 41, 'Santa Fe'),
(903, 41, 'Santander'),
(904, 41, 'Sibonga'),
(905, 41, 'Sogod'),
(906, 41, 'Tabogon'),
(907, 41, 'Tabuelan'),
(908, 41, 'City of Talisay'),
(909, 41, 'City of Toledo'),
(910, 41, 'Tuburan'),
(911, 41, 'Tudela'),
(912, 42, 'Amlan'),
(913, 42, 'Ayungon'),
(914, 42, 'Bacong'),
(915, 42, 'City of Bais'),
(916, 42, 'Basay'),
(917, 42, 'City of Bayawan'),
(918, 42, 'Bindoy'),
(919, 42, 'City of Canlaon'),
(920, 42, 'Dauin'),
(921, 42, 'City of Dumaguete'),
(922, 42, 'City of Guihulngan'),
(923, 42, 'Jimalalud'),
(924, 42, 'La Libertad'),
(925, 42, 'Mabinay'),
(926, 42, 'Manjuyod'),
(927, 42, 'Pamplona'),
(928, 42, 'San Jose'),
(929, 42, 'Santa Catalina'),
(930, 42, 'Siaton'),
(931, 42, 'Sibulan'),
(932, 42, 'City of Tanjay'),
(933, 42, 'Tayasan'),
(934, 42, 'Valencia'),
(935, 42, 'Vallehermoso'),
(936, 42, 'Zamboanguita'),
(937, 43, 'Enrique Villanueva'),
(938, 43, 'Larena'),
(939, 43, 'Lazi'),
(940, 43, 'Maria'),
(941, 43, 'San Juan'),
(942, 43, 'Siquijor'),
(943, 44, 'Arteche'),
(944, 44, 'Balangiga'),
(945, 44, 'Balangkayan'),
(946, 44, 'City of Borongan'),
(947, 44, 'Can-Avid'),
(948, 44, 'Dolores'),
(949, 44, 'General Macarthur'),
(950, 44, 'Giporlos'),
(951, 44, 'Guiuan'),
(952, 44, 'Hernani'),
(953, 44, 'Jipapad'),
(954, 44, 'Lawaan'),
(955, 44, 'Llorente'),
(956, 44, 'Maslog'),
(957, 44, 'Maydolong'),
(958, 44, 'Mercedes'),
(959, 44, 'Oras'),
(960, 44, 'Quinapondan'),
(961, 44, 'Salcedo'),
(962, 44, 'San Julian'),
(963, 44, 'San Policarpo'),
(964, 44, 'Sulat'),
(965, 44, 'Taft'),
(966, 45, 'Abuyog'),
(967, 45, 'Alangalang'),
(968, 45, 'Albuera'),
(969, 45, 'Babatngon'),
(970, 45, 'Barugo'),
(971, 45, 'Bato'),
(972, 45, 'City of Baybay'),
(973, 45, 'Burauen'),
(974, 45, 'Calubian'),
(975, 45, 'Capoocan'),
(976, 45, 'Carigara'),
(977, 45, 'Dagami'),
(978, 45, 'Dulag'),
(979, 45, 'Hilongos'),
(980, 45, 'Hindang'),
(981, 45, 'Inopacan'),
(982, 45, 'Isabel'),
(983, 45, 'Jaro'),
(984, 45, 'Javier'),
(985, 45, 'Julita'),
(986, 45, 'Kananga'),
(987, 45, 'La Paz'),
(988, 45, 'Leyte'),
(989, 45, 'Macarthur'),
(990, 45, 'Mahaplag'),
(991, 45, 'Matag-Ob'),
(992, 45, 'Matalom'),
(993, 45, 'Mayorga'),
(994, 45, 'Merida'),
(995, 45, 'Ormoc City'),
(996, 45, 'Palo'),
(997, 45, 'Palompon'),
(998, 45, 'Pastrana'),
(999, 45, 'San Isidro'),
(1000, 45, 'San Miguel'),
(1001, 45, 'Santa Fe'),
(1002, 45, 'Tabango'),
(1003, 45, 'Tabontabon'),
(1004, 45, 'City of Tacloban'),
(1005, 45, 'Tanauan'),
(1006, 45, 'Tolosa'),
(1007, 45, 'Tunga'),
(1008, 45, 'Villaba'),
(1009, 46, 'Allen'),
(1010, 46, 'Biri'),
(1011, 46, 'Bobon'),
(1012, 46, 'Capul'),
(1013, 46, 'Catarman'),
(1014, 46, 'Catubig'),
(1015, 46, 'Gamay'),
(1016, 46, 'Laoang'),
(1017, 46, 'Lapinig'),
(1018, 46, 'Las Navas'),
(1019, 46, 'Lavezares'),
(1020, 46, 'Mapanas'),
(1021, 46, 'Mondragon'),
(1022, 46, 'Palapag'),
(1023, 46, 'Pambujan'),
(1024, 46, 'Rosario'),
(1025, 46, 'San Antonio'),
(1026, 46, 'San Isidro'),
(1027, 46, 'San Jose'),
(1028, 46, 'San Roque'),
(1029, 46, 'San Vicente'),
(1030, 46, 'Silvino Lobos'),
(1031, 46, 'Victoria'),
(1032, 46, 'Lope De Vega'),
(1033, 47, 'Almagro'),
(1034, 47, 'Basey'),
(1035, 47, 'City of Calbayog'),
(1036, 47, 'Calbiga'),
(1037, 47, 'City of Catbalogan'),
(1038, 47, 'Daram'),
(1039, 47, 'Gandara'),
(1040, 47, 'Hinabangan'),
(1041, 47, 'Jiabong'),
(1042, 47, 'Marabut'),
(1043, 47, 'Matuguinao'),
(1044, 47, 'Motiong'),
(1045, 47, 'Pinabacdao'),
(1046, 47, 'San Jose De Buan'),
(1047, 47, 'San Sebastian'),
(1048, 47, 'Santa Margarita'),
(1049, 47, 'Santa Rita'),
(1050, 47, 'Santo Niño'),
(1051, 47, 'Talalora'),
(1052, 47, 'Tarangnan'),
(1053, 47, 'Villareal'),
(1054, 47, 'Paranas'),
(1055, 47, 'Zumarraga'),
(1056, 47, 'Tagapul-An'),
(1057, 47, 'San Jorge'),
(1058, 47, 'Pagsanghan'),
(1059, 48, 'Anahawan'),
(1060, 48, 'Bontoc'),
(1061, 48, 'Hinunangan'),
(1062, 48, 'Hinundayan'),
(1063, 48, 'Libagon'),
(1064, 48, 'Liloan'),
(1065, 48, 'City of Maasin'),
(1066, 48, 'Macrohon'),
(1067, 48, 'Malitbog'),
(1068, 48, 'Padre Burgos'),
(1069, 48, 'Pintuyan'),
(1070, 48, 'Saint Bernard'),
(1071, 48, 'San Francisco'),
(1072, 48, 'San Juan'),
(1073, 48, 'San Ricardo'),
(1074, 48, 'Silago'),
(1075, 48, 'Sogod'),
(1076, 48, 'Tomas Oppus'),
(1077, 48, 'Limasawa'),
(1078, 49, 'Almeria'),
(1079, 49, 'Biliran'),
(1080, 49, 'Cabucgayan'),
(1081, 49, 'Caibiran'),
(1082, 49, 'Culaba'),
(1083, 49, 'Kawayan'),
(1084, 49, 'Maripipi'),
(1085, 49, 'Naval'),
(1086, 50, 'City of Dapitan'),
(1087, 50, 'City of Dipolog'),
(1088, 50, 'Katipunan'),
(1089, 50, 'La Libertad'),
(1090, 50, 'Labason'),
(1091, 50, 'Liloy'),
(1092, 50, 'Manukan'),
(1093, 50, 'Mutia'),
(1094, 50, 'Piñan'),
(1095, 50, 'Polanco'),
(1096, 50, 'Pres. Manuel A. Roxas'),
(1097, 50, 'Rizal'),
(1098, 50, 'Salug'),
(1099, 50, 'Sergio Osmeña Sr.'),
(1100, 50, 'Siayan'),
(1101, 50, 'Sibuco'),
(1102, 50, 'Sibutad'),
(1103, 50, 'Sindangan'),
(1104, 50, 'Siocon'),
(1105, 50, 'Sirawai'),
(1106, 50, 'Tampilisan'),
(1107, 50, 'Jose Dalman'),
(1108, 50, 'Gutalac'),
(1109, 50, 'Baliguian'),
(1110, 50, 'Godod'),
(1111, 50, 'Bacungan'),
(1112, 50, 'Kalawit'),
(1113, 51, 'Aurora'),
(1114, 51, 'Bayog'),
(1115, 51, 'Dimataling'),
(1116, 51, 'Dinas'),
(1117, 51, 'Dumalinao'),
(1118, 51, 'Dumingag'),
(1119, 51, 'Kumalarang'),
(1120, 51, 'Labangan'),
(1121, 51, 'Lapuyan'),
(1122, 51, 'Mahayag'),
(1123, 51, 'Margosatubig'),
(1124, 51, 'Midsalip'),
(1125, 51, 'Molave'),
(1126, 51, 'City of Pagadian'),
(1127, 51, 'Ramon Magsaysay'),
(1128, 51, 'San Miguel'),
(1129, 51, 'San Pablo'),
(1130, 51, 'Tabina'),
(1131, 51, 'Tambulig'),
(1132, 51, 'Tukuran'),
(1133, 51, 'City of Zamboanga'),
(1134, 51, 'Lakewood'),
(1135, 51, 'Josefina'),
(1136, 51, 'Pitogo'),
(1137, 51, 'Sominot'),
(1138, 51, 'Vincenzo A. Sagun'),
(1139, 51, 'Guipos'),
(1140, 51, 'Tigbao'),
(1141, 52, 'Alicia'),
(1142, 52, 'Buug'),
(1143, 52, 'Diplahan'),
(1144, 52, 'Imelda'),
(1145, 52, 'Ipil'),
(1146, 52, 'Kabasalan'),
(1147, 52, 'Mabuhay'),
(1148, 52, 'Malangas'),
(1149, 52, 'Naga'),
(1150, 52, 'Olutanga'),
(1151, 52, 'Payao'),
(1152, 52, 'Roseller Lim'),
(1153, 52, 'Siay'),
(1154, 52, 'Talusan'),
(1155, 52, 'Titay'),
(1156, 52, 'Tungawan'),
(1157, 52, 'City of Isabela'),
(1158, 53, 'Baungon'),
(1159, 53, 'Damulog'),
(1160, 53, 'Dangcagan'),
(1161, 53, 'Don Carlos'),
(1162, 53, 'Impasug-ong'),
(1163, 53, 'Kadingilan'),
(1164, 53, 'Kalilangan'),
(1165, 53, 'Kibawe'),
(1166, 53, 'Kitaotao'),
(1167, 53, 'Lantapan'),
(1168, 53, 'Libona'),
(1169, 53, 'City of Malaybalay'),
(1170, 53, 'Malitbog'),
(1171, 53, 'Manolo Fortich'),
(1172, 53, 'Maramag'),
(1173, 53, 'Pangantucan'),
(1174, 53, 'Quezon'),
(1175, 53, 'San Fernando'),
(1176, 53, 'Sumilao'),
(1177, 53, 'Talakag'),
(1178, 53, 'City of Valencia'),
(1179, 53, 'Cabanglasan'),
(1180, 54, 'Catarman'),
(1181, 54, 'Guinsiliban'),
(1182, 54, 'Mahinog'),
(1183, 54, 'Mambajao'),
(1184, 54, 'Sagay'),
(1185, 55, 'Bacolod'),
(1186, 55, 'Baloi'),
(1187, 55, 'Baroy'),
(1188, 55, 'City of Iligan'),
(1189, 55, 'Kapatagan'),
(1190, 55, 'Sultan Naga Dimaporo'),
(1191, 55, 'Kauswagan'),
(1192, 55, 'Kolambugan'),
(1193, 55, 'Lala'),
(1194, 55, 'Linamon'),
(1195, 55, 'Magsaysay'),
(1196, 55, 'Maigo'),
(1197, 55, 'Matungao'),
(1198, 55, 'Munai'),
(1199, 55, 'Nunungan'),
(1200, 55, 'Pantao Ragat'),
(1201, 55, 'Poona Piagapo'),
(1202, 55, 'Salvador'),
(1203, 55, 'Sapad'),
(1204, 55, 'Tagoloan'),
(1205, 55, 'Tangcal'),
(1206, 55, 'Tubod'),
(1207, 55, 'Pantar'),
(1208, 56, 'Aloran'),
(1209, 56, 'Baliangao'),
(1210, 56, 'Bonifacio'),
(1211, 56, 'Calamba'),
(1212, 56, 'Clarin'),
(1213, 56, 'Concepcion'),
(1214, 56, 'Jimenez'),
(1215, 56, 'Lopez Jaena'),
(1216, 56, 'City of Oroquieta'),
(1217, 56, 'City of Ozamiz'),
(1218, 56, 'Panaon'),
(1219, 56, 'Plaridel'),
(1220, 56, 'Sapang Dalaga'),
(1221, 56, 'Sinacaban'),
(1222, 56, 'City of Tangub'),
(1223, 56, 'Tudela'),
(1224, 56, 'Don Victoriano Chiongbian'),
(1225, 57, 'Alubijid'),
(1226, 57, 'Balingasag'),
(1227, 57, 'Balingoan'),
(1228, 57, 'Binuangan'),
(1229, 57, 'City of Cagayan De Oro'),
(1230, 57, 'Claveria'),
(1231, 57, 'City of El Salvador'),
(1232, 57, 'City of Gingoog'),
(1233, 57, 'Gitagum'),
(1234, 57, 'Initao'),
(1235, 57, 'Jasaan'),
(1236, 57, 'Kinoguitan'),
(1237, 57, 'Lagonglong'),
(1238, 57, 'Laguindingan'),
(1239, 57, 'Libertad'),
(1240, 57, 'Lugait'),
(1241, 57, 'Magsaysay'),
(1242, 57, 'Manticao'),
(1243, 57, 'Medina'),
(1244, 57, 'Naawan'),
(1245, 57, 'Opol'),
(1246, 57, 'Salay'),
(1247, 57, 'Sugbongcogon'),
(1248, 57, 'Tagoloan'),
(1249, 57, 'Talisayan'),
(1250, 57, 'Villanueva'),
(1251, 58, 'Asuncion'),
(1252, 58, 'Carmen'),
(1253, 58, 'Kapalong'),
(1254, 58, 'New Corella'),
(1255, 58, 'City of Panabo'),
(1256, 58, 'Island Garden City of Samal'),
(1257, 58, 'Santo Tomas'),
(1258, 58, 'City of Tagum'),
(1259, 58, 'Talaingod'),
(1260, 58, 'Braulio E. Dujali'),
(1261, 58, 'San Isidro'),
(1262, 59, 'Bansalan'),
(1263, 59, 'City of Davao'),
(1264, 59, 'City of Digos'),
(1265, 59, 'Hagonoy'),
(1266, 59, 'Kiblawan'),
(1267, 59, 'Magsaysay'),
(1268, 59, 'Malalag'),
(1269, 59, 'Matanao'),
(1270, 59, 'Padada'),
(1271, 59, 'Santa Cruz'),
(1272, 59, 'Sulop'),
(1273, 60, 'Baganga'),
(1274, 60, 'Banaybanay'),
(1275, 60, 'Boston'),
(1276, 60, 'Caraga'),
(1277, 60, 'Cateel'),
(1278, 60, 'Governor Generoso'),
(1279, 60, 'Lupon'),
(1280, 60, 'Manay'),
(1281, 60, 'City of Mati'),
(1282, 60, 'San Isidro'),
(1283, 60, 'Tarragona'),
(1284, 61, 'Compostela'),
(1285, 61, 'Laak'),
(1286, 61, 'Mabini'),
(1287, 61, 'Maco'),
(1288, 61, 'Maragusan'),
(1289, 61, 'Mawab'),
(1290, 61, 'Monkayo'),
(1291, 61, 'Montevista'),
(1292, 61, 'Nabunturan'),
(1293, 61, 'New Bataan'),
(1294, 61, 'Pantukan'),
(1295, 62, 'Don Marcelino'),
(1296, 62, 'Jose Abad Santos'),
(1297, 62, 'Malita'),
(1298, 62, 'Santa Maria'),
(1299, 62, 'Sarangani'),
(1300, 63, 'Alamada'),
(1301, 63, 'Carmen'),
(1302, 63, 'Kabacan'),
(1303, 63, 'City of Kidapawan'),
(1304, 63, 'Libungan'),
(1305, 63, 'Magpet'),
(1306, 63, 'Makilala'),
(1307, 63, 'Matalam'),
(1308, 63, 'Midsayap'),
(1309, 63, 'M\'Lang'),
(1310, 63, 'Pigkawayan'),
(1311, 63, 'Pikit'),
(1312, 63, 'President Roxas'),
(1313, 63, 'Tulunan'),
(1314, 63, 'Antipas'),
(1315, 63, 'Banisilan'),
(1316, 63, 'Aleosan'),
(1317, 63, 'Arakan'),
(1318, 64, 'Banga'),
(1319, 64, 'City of General Santos'),
(1320, 64, 'City of Koronadal'),
(1321, 64, 'Norala'),
(1322, 64, 'Polomolok'),
(1323, 64, 'Surallah'),
(1324, 64, 'Tampakan'),
(1325, 64, 'Tantangan'),
(1326, 64, 'T\'Boli'),
(1327, 64, 'Tupi'),
(1328, 64, 'Santo Niño'),
(1329, 64, 'Lake Sebu'),
(1330, 65, 'Bagumbayan'),
(1331, 65, 'Columbio'),
(1332, 65, 'Esperanza'),
(1333, 65, 'Isulan'),
(1334, 65, 'Kalamansig'),
(1335, 65, 'Lebak'),
(1336, 65, 'Lutayan'),
(1337, 65, 'Lambayong'),
(1338, 65, 'Palimbang'),
(1339, 65, 'President Quirino'),
(1340, 65, 'City of Tacurong'),
(1341, 65, 'Sen. Ninoy Aquino'),
(1342, 66, 'Alabel'),
(1343, 66, 'Glan'),
(1344, 66, 'Kiamba'),
(1345, 66, 'Maasim'),
(1346, 66, 'Maitum'),
(1347, 66, 'Malapatan'),
(1348, 66, 'Malungon'),
(1349, 66, 'Cotabato City'),
(1350, 1, 'Manila'),
(1351, 1, 'Mandaluyong City'),
(1352, 1, 'Marikina City'),
(1353, 1, 'Pasig City'),
(1354, 1, 'Quezon City'),
(1355, 1, 'San Juan City'),
(1356, 1, 'Caloocan City'),
(1357, 1, 'Malabon City'),
(1358, 1, 'Navotas City'),
(1359, 1, 'Valenzuela City'),
(1360, 1, 'Las Piñas City'),
(1361, 1, 'Makati City'),
(1362, 1, 'Muntinlupa City'),
(1363, 1, 'Parañaque City'),
(1364, 1, 'Pasay City'),
(1365, 1, 'Pateros'),
(1366, 1, 'Taguig City'),
(1367, 67, 'Bangued'),
(1368, 67, 'Boliney'),
(1369, 67, 'Bucay'),
(1370, 67, 'Bucloc'),
(1371, 67, 'Daguioman'),
(1372, 67, 'Danglas'),
(1373, 67, 'Dolores'),
(1374, 67, 'La Paz'),
(1375, 67, 'Lacub'),
(1376, 67, 'Lagangilang'),
(1377, 67, 'Lagayan'),
(1378, 67, 'Langiden'),
(1379, 67, 'Licuan-Baay'),
(1380, 67, 'Luba'),
(1381, 67, 'Malibcong'),
(1382, 67, 'Manabo'),
(1383, 67, 'Peñarrubia'),
(1384, 67, 'Pidigan'),
(1385, 67, 'Pilar'),
(1386, 67, 'Sallapadan'),
(1387, 67, 'San Isidro'),
(1388, 67, 'San Juan'),
(1389, 67, 'San Quintin'),
(1390, 67, 'Tayum'),
(1391, 67, 'Tineg'),
(1392, 67, 'Tubo'),
(1393, 67, 'Villaviciosa'),
(1394, 68, 'Atok'),
(1395, 68, 'City of Baguio'),
(1396, 68, 'Bakun'),
(1397, 68, 'Bokod'),
(1398, 68, 'Buguias'),
(1399, 68, 'Itogon'),
(1400, 68, 'Kabayan'),
(1401, 68, 'Kapangan'),
(1402, 68, 'Kibungan'),
(1403, 68, 'La Trinidad'),
(1404, 68, 'Mankayan'),
(1405, 68, 'Sablan'),
(1406, 68, 'Tuba'),
(1407, 68, 'Tublay'),
(1408, 69, 'Banaue'),
(1409, 69, 'Hungduan'),
(1410, 69, 'Kiangan'),
(1411, 69, 'Lagawe'),
(1412, 69, 'Lamut'),
(1413, 69, 'Mayoyao'),
(1414, 69, 'Alfonso Lista'),
(1415, 69, 'Aguinaldo'),
(1416, 69, 'Hingyon'),
(1417, 69, 'Tinoc'),
(1418, 69, 'Asipulo'),
(1419, 70, 'Balbalan'),
(1420, 70, 'Lubuagan'),
(1421, 70, 'Pasil'),
(1422, 70, 'Pinukpuk'),
(1423, 70, 'Rizal'),
(1424, 70, 'City of Tabuk'),
(1425, 70, 'Tanudan'),
(1426, 70, 'Tinglayan'),
(1427, 71, 'Barlig'),
(1428, 71, 'Bauko'),
(1429, 71, 'Besao'),
(1430, 71, 'Bontoc'),
(1431, 71, 'Natonin'),
(1432, 71, 'Paracelis'),
(1433, 71, 'Sabangan'),
(1434, 71, 'Sadanga'),
(1435, 71, 'Sagada'),
(1436, 71, 'Tadian'),
(1437, 72, 'Calanasan'),
(1438, 72, 'Conner'),
(1439, 72, 'Flora'),
(1440, 72, 'Kabugao'),
(1441, 72, 'Luna'),
(1442, 72, 'Pudtol'),
(1443, 72, 'Santa Marcela'),
(1444, 73, 'City of Lamitan'),
(1445, 73, 'Lantawan'),
(1446, 73, 'Maluso'),
(1447, 73, 'Sumisip'),
(1448, 73, 'Tipo-Tipo'),
(1449, 73, 'Tuburan'),
(1450, 73, 'Akbar'),
(1451, 73, 'Al-Barka'),
(1452, 73, 'Hadji Mohammad Ajul'),
(1453, 73, 'Ungkaya Pukan'),
(1454, 73, 'Hadji Muhtamad'),
(1455, 73, 'Tabuan-Lasa'),
(1456, 74, 'Bacolod-Kalawi'),
(1457, 74, 'Balabagan'),
(1458, 74, 'Balindong'),
(1459, 74, 'Bayang'),
(1460, 74, 'Binidayan'),
(1461, 74, 'Bubong'),
(1462, 74, 'Butig'),
(1463, 74, 'Ganassi'),
(1464, 74, 'Kapai'),
(1465, 74, 'Lumba-Bayabao'),
(1466, 74, 'Lumbatan'),
(1467, 74, 'Madalum'),
(1468, 74, 'Madamba'),
(1469, 74, 'Malabang'),
(1470, 74, 'Marantao'),
(1471, 74, 'City of Marawi'),
(1472, 74, 'Masiu'),
(1473, 74, 'Mulondo'),
(1474, 74, 'Pagayawan'),
(1475, 74, 'Piagapo'),
(1476, 74, 'Poona Bayabao'),
(1477, 74, 'Pualas'),
(1478, 74, 'Ditsaan-Ramain'),
(1479, 74, 'Saguiaran'),
(1480, 74, 'Tamparan'),
(1481, 74, 'Taraka'),
(1482, 74, 'Tubaran'),
(1483, 74, 'Tugaya'),
(1484, 74, 'Wao'),
(1485, 74, 'Marogong'),
(1486, 74, 'Calanogas'),
(1487, 74, 'Buadiposo-Buntong'),
(1488, 74, 'Maguing'),
(1489, 74, 'Picong'),
(1490, 74, 'Lumbayanague'),
(1491, 74, 'Amai Manabilang'),
(1492, 74, 'Tagoloan Ii'),
(1493, 74, 'Kapatagan'),
(1494, 74, 'Sultan Dumalondong'),
(1495, 74, 'Lumbaca-Unayan'),
(1496, 75, 'Ampatuan'),
(1497, 75, 'Buldon'),
(1498, 75, 'Buluan'),
(1499, 75, 'Datu Paglas'),
(1500, 75, 'Datu Piang'),
(1501, 75, 'Datu Odin Sinsuat'),
(1502, 75, 'Shariff Aguak'),
(1503, 75, 'Matanog'),
(1504, 75, 'Pagalungan'),
(1505, 75, 'Parang'),
(1506, 75, 'Sultan Kudarat'),
(1507, 75, 'Sultan Sa Barongis'),
(1508, 75, 'Kabuntalan'),
(1509, 75, 'Upi'),
(1510, 75, 'Talayan'),
(1511, 75, 'South Upi'),
(1512, 75, 'Barira'),
(1513, 75, 'Gen. S.K. Pendatun'),
(1514, 75, 'Mamasapano'),
(1515, 75, 'Talitay'),
(1516, 75, 'Pagagawan'),
(1517, 75, 'Paglat'),
(1518, 75, 'Sultan Mastura'),
(1519, 75, 'Guindulungan'),
(1520, 75, 'Datu Saudi-Ampatuan'),
(1521, 75, 'Datu Unsay'),
(1522, 75, 'Datu Abdullah Sangki'),
(1523, 75, 'Rajah Buayan'),
(1524, 75, 'Datu Blah T. Sinsuat'),
(1525, 75, 'Datu Anggal Midtimbang'),
(1526, 75, 'Mangudadatu'),
(1527, 75, 'Pandag'),
(1528, 75, 'Northern Kabuntalan'),
(1529, 75, 'Datu Hoffer Ampatuan'),
(1530, 75, 'Datu Salibo'),
(1531, 75, 'Shariff Saydona Mustapha'),
(1532, 76, 'Indanan'),
(1533, 76, 'Jolo'),
(1534, 76, 'Kalingalan Caluang'),
(1535, 76, 'Luuk'),
(1536, 76, 'Maimbung'),
(1537, 76, 'Hadji Panglima Tahil'),
(1538, 76, 'Old Panamao'),
(1539, 76, 'Pangutaran'),
(1540, 76, 'Parang'),
(1541, 76, 'Pata'),
(1542, 76, 'Patikul'),
(1543, 76, 'Siasi'),
(1544, 76, 'Talipao'),
(1545, 76, 'Tapul'),
(1546, 76, 'Tongkil'),
(1547, 76, 'Panglima Estino'),
(1548, 76, 'Lugus'),
(1549, 76, 'Pandami'),
(1550, 76, 'Omar'),
(1551, 77, 'Panglima Sugala'),
(1552, 77, 'Bongao (Capital)'),
(1553, 77, 'Mapun'),
(1554, 77, 'Simunul'),
(1555, 77, 'Sitangkai'),
(1556, 77, 'South Ubian'),
(1557, 77, 'Tandubas'),
(1558, 77, 'Turtle Islands'),
(1559, 77, 'Languyan'),
(1560, 77, 'Sapa-Sapa'),
(1561, 77, 'Sibutu'),
(1562, 78, 'Buenavista'),
(1563, 78, 'City of Butuan'),
(1564, 78, 'City of Cabadbaran'),
(1565, 78, 'Carmen'),
(1566, 78, 'Jabonga'),
(1567, 78, 'Kitcharao'),
(1568, 78, 'Las Nieves'),
(1569, 78, 'Magallanes'),
(1570, 78, 'Nasipit'),
(1571, 78, 'Santiago'),
(1572, 78, 'Tubay'),
(1573, 78, 'Remedios T. Romualdez'),
(1574, 79, 'City of Bayugan'),
(1575, 79, 'Bunawan'),
(1576, 79, 'Esperanza'),
(1577, 79, 'La Paz'),
(1578, 79, 'Loreto'),
(1579, 79, 'Prosperidad'),
(1580, 79, 'Rosario'),
(1581, 79, 'San Francisco'),
(1582, 79, 'San Luis'),
(1583, 79, 'Santa Josefa'),
(1584, 79, 'Talacogon'),
(1585, 79, 'Trento'),
(1586, 79, 'Veruela'),
(1587, 79, 'Sibagat'),
(1588, 80, 'Alegria'),
(1589, 80, 'Bacuag'),
(1590, 80, 'Burgos'),
(1591, 80, 'Claver'),
(1592, 80, 'Dapa'),
(1593, 80, 'Del Carmen'),
(1594, 80, 'General Luna'),
(1595, 80, 'Gigaquit'),
(1596, 80, 'Mainit'),
(1597, 80, 'Malimono'),
(1598, 80, 'Pilar'),
(1599, 80, 'Placer'),
(1600, 80, 'San Benito'),
(1601, 80, 'San Francisco'),
(1602, 80, 'San Isidro'),
(1603, 80, 'Santa Monica'),
(1604, 80, 'Sison'),
(1605, 80, 'Socorro'),
(1606, 80, 'City of Surigao'),
(1607, 80, 'Tagana-An'),
(1608, 80, 'Tubod'),
(1609, 81, 'Barobo'),
(1610, 81, 'Bayabas'),
(1611, 81, 'City of Bislig'),
(1612, 81, 'Cagwait'),
(1613, 81, 'Cantilan'),
(1614, 81, 'Carmen'),
(1615, 81, 'Carrascal'),
(1616, 81, 'Cortes'),
(1617, 81, 'Hinatuan'),
(1618, 81, 'Lanuza'),
(1619, 81, 'Lianga'),
(1620, 81, 'Lingig'),
(1621, 81, 'Madrid'),
(1622, 81, 'Marihatag'),
(1623, 81, 'San Agustin'),
(1624, 81, 'San Miguel'),
(1625, 81, 'Tagbina'),
(1626, 81, 'Tago'),
(1627, 81, 'City of Tandag'),
(1628, 82, 'Basilisa'),
(1629, 82, 'Cagdianao'),
(1630, 82, 'Dinagat'),
(1631, 82, 'Libjo'),
(1632, 82, 'Loreto'),
(1633, 82, 'San Jose'),
(1634, 82, 'Tubajon');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_partners`
--

CREATE TABLE `tbl_partners` (
  `partners_id` int(11) NOT NULL,
  `partners_name` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_partners`
--

INSERT INTO `tbl_partners` (`partners_id`, `partners_name`) VALUES
(1, 'TFI'),
(2, 'FELTA');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_population`
--

CREATE TABLE `tbl_population` (
  `population_id` int(11) NOT NULL,
  `population_count` int(11) DEFAULT NULL,
  `potentialcustomer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_population`
--

INSERT INTO `tbl_population` (`population_id`, `population_count`, `potentialcustomer_id`) VALUES
(1, 1300, 1),
(2, 1236, 2),
(3, 3460, 3),
(4, 3964, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_potentialcustomer`
--

CREATE TABLE `tbl_potentialcustomer` (
  `potentialcustomer_id` int(11) NOT NULL,
  `potentialcustomer_name` varchar(225) DEFAULT NULL,
  `potentialcustomer_type` tinyint(1) DEFAULT NULL COMMENT '0 = public, 1 = private',
  `potentialcustomer_location` varchar(225) DEFAULT NULL,
  `potentialcustomer_facility` varchar(225) DEFAULT NULL,
  `potentialcustomer_tuition` varchar(225) DEFAULT NULL,
  `sector_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_potentialcustomer`
--

INSERT INTO `tbl_potentialcustomer` (`potentialcustomer_id`, `potentialcustomer_name`, `potentialcustomer_type`, `potentialcustomer_location`, `potentialcustomer_facility`, `potentialcustomer_tuition`, `sector_id`, `user_id`) VALUES
(1, 'Our Lady of Remedios Montessori school', 1, 'Governor drive Hi way, City of General Trias, Cavite', 'Owned', '20k - 30k', 1, 5),
(2, 'Holy Rosary College', 1, 'Brgy. Tagapo, City of Santa Rosa, Laguna', 'Owned', '20k - 30k', 1, 5),
(3, 'FEU Roosevelt Marikina', 1, '504 JP Rizal St., Marikina City, Metro Manila', 'Owned', '20k - 30k', 1, 5),
(4, 'Jose Rizal University', 1, '80 Shaw Boulevard, Mandaluyong City, Metro Manila', 'Owned', '20k - 30k', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `products_id` int(11) NOT NULL,
  `products_item` varchar(225) DEFAULT NULL,
  `products_description` varchar(225) DEFAULT NULL,
  `products_cost` decimal(10,0) DEFAULT NULL,
  `products_srp` decimal(10,0) DEFAULT NULL,
  `services_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`products_id`, `products_item`, `products_description`, `products_cost`, `products_srp`, `services_id`) VALUES
(1, '1', '2', 1, 1, 3),
(2, '1', 'Work Immersion Program (Hybrid)', 3, 2500, 1),
(4, 'Edited Item', 'Test Desc', 100, 100, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_program`
--

CREATE TABLE `tbl_program` (
  `program_id` int(11) NOT NULL,
  `program_type` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_program`
--

INSERT INTO `tbl_program` (`program_id`, `program_type`) VALUES
(1, 'All Offering (K to 12)'),
(2, 'ES and JHS (K to 10)'),
(3, 'JHS with SHS'),
(4, 'Purely ES'),
(5, 'Purely JHS'),
(6, 'Purely SHS'),
(7, 'College');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_province`
--

CREATE TABLE `tbl_province` (
  `province_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `province_name` varchar(19) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_province`
--

INSERT INTO `tbl_province` (`province_id`, `region_id`, `province_name`) VALUES
(1, 1, 'Metro Manila'),
(2, 3, 'Ilocos Norte'),
(3, 3, 'Ilocos Sur'),
(4, 3, 'La Union'),
(5, 3, 'Pangasinan'),
(6, 4, 'Batanes'),
(7, 4, 'Cagayan'),
(8, 4, 'Isabela'),
(9, 4, 'Nueva Vizcaya'),
(10, 4, 'Quirino'),
(11, 5, 'Bataan'),
(12, 5, 'Bulacan'),
(13, 5, 'Nueva Ecija'),
(14, 5, 'Pampanga'),
(15, 5, 'Tarlac'),
(16, 5, 'Zambales'),
(17, 5, 'Aurora'),
(18, 6, 'Batangas'),
(19, 6, 'Cavite'),
(20, 6, 'Laguna'),
(21, 6, 'Quezon'),
(22, 6, 'Rizal'),
(23, 7, 'Marinduque'),
(24, 7, 'Occidental Mindoro'),
(25, 7, 'Oriental Mindoro'),
(26, 7, 'Palawan'),
(27, 7, 'Romblon'),
(28, 8, 'Albay'),
(29, 8, 'Camarines Norte'),
(30, 8, 'Camarines Sur'),
(31, 8, 'Catanduanes'),
(32, 8, 'Masbate'),
(33, 8, 'Sorsogon'),
(34, 9, 'Aklan'),
(35, 9, 'Antique'),
(36, 9, 'Capiz'),
(37, 9, 'Iloilo'),
(38, 9, 'Negros Occidental'),
(39, 9, 'Guimaras'),
(40, 10, 'Bohol'),
(41, 10, 'Cebu'),
(42, 10, 'Negros Oriental'),
(43, 10, 'Siquijor'),
(44, 11, 'Eastern Samar'),
(45, 11, 'Leyte'),
(46, 11, 'Northern Samar'),
(47, 11, 'Samar'),
(48, 11, 'Southern Leyte'),
(49, 11, 'Biliran'),
(50, 12, 'Zamboanga del Norte'),
(51, 12, 'Zamboanga del Sur'),
(52, 12, 'Zamboanga Sibugay'),
(53, 13, 'Bukidnon'),
(54, 13, 'Camiguin'),
(55, 13, 'Lanao del Norte'),
(56, 13, 'Misamis Occidental'),
(57, 13, 'Misamis Oriental'),
(58, 14, 'Davao del Norte'),
(59, 14, 'Davao del Sur'),
(60, 14, 'Davao Oriental'),
(61, 14, 'Davao de Oro'),
(62, 14, 'Davao Occidental'),
(63, 15, 'Cotabato'),
(64, 15, 'South Cotabato'),
(65, 15, 'Sultan Kudarat'),
(66, 15, 'Sarangani'),
(67, 2, 'Abra'),
(68, 2, 'Benguet'),
(69, 2, 'Ifugao'),
(70, 2, 'Kalinga'),
(71, 2, 'Mountain Province'),
(72, 2, 'Apayao'),
(73, 17, 'Basilan'),
(74, 17, 'Lanao del Sur'),
(75, 17, 'Maguindanao'),
(76, 17, 'Sulu'),
(77, 17, 'Tawi-Tawi'),
(78, 16, 'Agusan del Norte'),
(79, 16, 'Agusan del Sur'),
(80, 16, 'Surigao del Norte'),
(81, 16, 'Surigao del Sur'),
(82, 16, 'Dinagat Islands');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rating`
--

CREATE TABLE `tbl_rating` (
  `rating_id` int(11) NOT NULL,
  `rating_score` varchar(11) DEFAULT NULL,
  `rating_notes` varchar(2000) DEFAULT NULL,
  `criteria_id` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_rating`
--

INSERT INTO `tbl_rating` (`rating_id`, `rating_score`, `rating_notes`, `criteria_id`, `evaluation_id`) VALUES
(1, '3.8', 'new renovated ', 1, 1),
(2, '3.4', 'Good', 2, 1),
(3, '3.7', 'Good ', 3, 1),
(4, '4.5', '', 4, 1),
(5, '2.5', 'not yet interested ', 5, 1),
(6, '4.0', '', 1, 2),
(7, '4.2', '', 2, 2),
(8, '4.3', '', 3, 2),
(9, '5.0', '', 4, 2),
(10, '4.5', '', 5, 2),
(11, '4.8', '', 1, 3),
(12, '4.4', '', 2, 3),
(13, '5.0', '', 3, 3),
(14, '5.0', '', 4, 3),
(15, '3.5', '', 5, 3),
(16, '4.3', '', 1, 4),
(17, '4.6', '', 2, 4),
(18, '4.3', '', 3, 4),
(19, '5.0', '', 4, 4),
(20, '3.5', '', 5, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_region`
--

CREATE TABLE `tbl_region` (
  `region_id` int(11) NOT NULL,
  `region_name` varchar(11) DEFAULT NULL,
  `region_description` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_region`
--

INSERT INTO `tbl_region` (`region_id`, `region_name`, `region_description`) VALUES
(1, 'NCR', 'National Capital Region'),
(2, 'CAR', 'Cordillera Administrative Region'),
(3, 'Region I', 'Ilocos Region'),
(4, 'Region II', 'Cagayan Valley'),
(5, 'Region III', 'Central Luzon'),
(6, 'Region IV-A', 'CALABARZON'),
(7, 'Region IV-B', 'MIMAROPA'),
(8, 'Region V', 'Bicol Region'),
(9, 'Region VI', 'Western Visayas'),
(10, 'Region VII', 'Central Visayas'),
(11, 'Region VIII', 'Eastern Visayas'),
(12, 'Region IX', 'Zamboanga Peninsula'),
(13, 'Region X', 'Northern Mindanao'),
(14, 'Region XI', 'Davao Region'),
(15, 'Region XII', 'SOCCSKSARGEN'),
(16, 'Region XIII', 'CARAGA'),
(17, 'ARMM', 'Autonomous Region in Muslim Mindanao');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sector`
--

CREATE TABLE `tbl_sector` (
  `sector_id` int(11) NOT NULL,
  `sector_name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_sector`
--

INSERT INTO `tbl_sector` (`sector_id`, `sector_name`) VALUES
(1, 'School'),
(2, 'Government'),
(3, 'Sponsor'),
(4, 'Industry');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_services`
--

CREATE TABLE `tbl_services` (
  `services_id` int(11) NOT NULL,
  `services_type` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_services`
--

INSERT INTO `tbl_services` (`services_id`, `services_type`) VALUES
(1, 'Robotics'),
(2, 'Immersion'),
(3, 'Industry Tour'),
(4, 'Seminars'),
(5, 'OJT'),
(8, 'Edited Type'),
(9, 'Test Type'),
(10, 'Test Type');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subcriteria`
--

CREATE TABLE `tbl_subcriteria` (
  `subcriteria_id` int(11) NOT NULL,
  `subcriteria_criterion` varchar(255) DEFAULT NULL,
  `criteria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_subcriteria`
--

INSERT INTO `tbl_subcriteria` (`subcriteria_id`, `subcriteria_criterion`, `criteria_id`) VALUES
(1, 'Cleanliness of rooms including restrooms', 1),
(2, 'Learning environment', 1),
(3, 'Lighting and ventilation', 1),
(4, 'Maintenance and functionality of restrooms', 1),
(5, 'Exterior condition', 2),
(6, 'Signage', 2),
(7, 'Accessibility', 2),
(8, 'Security and Safety', 2),
(9, 'Facilities', 2),
(10, 'Social Media Activity and Engagement', 3),
(11, 'Online Reputation (Reviews and Feedback)', 3),
(12, 'Existing for at least 10 years', 3),
(13, 'Has at least 300 students', 4),
(14, 'Tuition can accommodate other fees', 4),
(15, 'Ease of access to decision makers', 5),
(16, 'Willingness of decision makers to engage', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subpopulation`
--

CREATE TABLE `tbl_subpopulation` (
  `subpopulation_id` int(11) NOT NULL,
  `subpopulation_count` int(11) DEFAULT NULL,
  `gradelevel_id` int(11) NOT NULL,
  `population_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_subpopulation`
--

INSERT INTO `tbl_subpopulation` (`subpopulation_id`, `subpopulation_count`, `gradelevel_id`, `population_id`) VALUES
(1, 72, 1, 1),
(2, 69, 2, 1),
(3, 75, 3, 1),
(4, 96, 4, 1),
(5, 96, 5, 1),
(6, 81, 6, 1),
(7, 102, 7, 1),
(8, 161, 8, 1),
(9, 127, 9, 1),
(10, 174, 10, 1),
(11, 150, 11, 1),
(12, 72, 12, 1),
(13, 25, 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subrating`
--

CREATE TABLE `tbl_subrating` (
  `subrating_id` int(11) NOT NULL,
  `subrating_score` varchar(20) DEFAULT NULL,
  `subcriteria_id` int(11) NOT NULL,
  `rating_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_subrating`
--

INSERT INTO `tbl_subrating` (`subrating_id`, `subrating_score`, `subcriteria_id`, `rating_id`) VALUES
(1, '4', 1, 1),
(2, '3', 2, 1),
(3, '4', 3, 1),
(4, '4', 4, 1),
(5, '2', 5, 2),
(6, '3', 6, 2),
(7, '5', 7, 2),
(8, '4', 8, 2),
(9, '3', 9, 2),
(10, '3', 10, 3),
(11, '3', 11, 3),
(12, '5', 12, 3),
(13, '4', 13, 4),
(14, '5', 14, 4),
(15, '3', 15, 5),
(16, '2', 16, 5),
(17, '4', 1, 6),
(18, '4', 2, 6),
(19, '4', 3, 6),
(20, '4', 4, 6),
(21, '4', 5, 7),
(22, '4', 6, 7),
(23, '4', 7, 7),
(24, '5', 8, 7),
(25, '4', 9, 7),
(26, '4', 10, 8),
(27, '4', 11, 8),
(28, '5', 12, 8),
(29, '5', 13, 9),
(30, '5', 14, 9),
(31, '5', 15, 10),
(32, '4', 16, 10),
(33, '5', 1, 11),
(34, '5', 2, 11),
(35, '5', 3, 11),
(36, '4', 4, 11),
(37, '4', 5, 12),
(38, '4', 6, 12),
(39, '5', 7, 12),
(40, '5', 8, 12),
(41, '4', 9, 12),
(42, '5', 10, 13),
(43, '5', 11, 13),
(44, '5', 12, 13),
(45, '5', 13, 14),
(46, '5', 14, 14),
(47, '3', 15, 15),
(48, '4', 16, 15),
(49, '4', 1, 16),
(50, '5', 2, 16),
(51, '4', 3, 16),
(52, '4', 4, 16),
(53, '4', 5, 17),
(54, '5', 6, 17),
(55, '5', 7, 17),
(56, '4', 8, 17),
(57, '5', 9, 17),
(58, '4', 10, 18),
(59, '4', 11, 18),
(60, '5', 12, 18),
(61, '5', 13, 19),
(62, '5', 14, 19),
(63, '3', 15, 20),
(64, '4', 16, 20);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `user_firstname` varchar(225) DEFAULT NULL,
  `user_lastname` varchar(225) DEFAULT NULL,
  `user_position` varchar(225) DEFAULT NULL,
  `user_department` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `user_firstname`, `user_lastname`, `user_position`, `user_department`) VALUES
(1, 'Ma. Theresa ', 'Bermudez', 'Director', 'Director'),
(2, 'Jennilyn ', 'Matuto', 'External Partnership Head', 'Business Development '),
(3, 'Michael ', 'Saliling', 'epc', 'business development '),
(4, 'Hannah', 'Abulencia', 'External Partnership Coordinator', 'Business Development'),
(5, 'Firstname', 'Lastname', 'Position', 'Department'),
(6, 'Test Firstname', 'Test Lastname', 'Test Position', 'Test Department');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_contactperson`
--
ALTER TABLE `tbl_contactperson`
  ADD PRIMARY KEY (`contactperson_id`,`potentialcustomer_id`),
  ADD KEY `client_id` (`potentialcustomer_id`);

--
-- Indexes for table `tbl_credentials`
--
ALTER TABLE `tbl_credentials`
  ADD PRIMARY KEY (`credentials_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_criteria`
--
ALTER TABLE `tbl_criteria`
  ADD PRIMARY KEY (`criteria_id`);

--
-- Indexes for table `tbl_evaluation`
--
ALTER TABLE `tbl_evaluation`
  ADD PRIMARY KEY (`evaluation_id`,`potentialcustomer_id`),
  ADD KEY `tbl_evaluation_ibfk_1` (`potentialcustomer_id`);

--
-- Indexes for table `tbl_existingfacilities`
--
ALTER TABLE `tbl_existingfacilities`
  ADD PRIMARY KEY (`existingfacilities_id`,`facility_id`,`potentialcustomer_id`),
  ADD KEY `facility_id` (`facility_id`),
  ADD KEY `potentialcustomer_id` (`potentialcustomer_id`);

--
-- Indexes for table `tbl_existingpartners`
--
ALTER TABLE `tbl_existingpartners`
  ADD PRIMARY KEY (`existingpartners_id`,`partners_id`,`potentialcustomer_id`),
  ADD KEY `partners_id` (`partners_id`),
  ADD KEY `potentialcustomer_id` (`potentialcustomer_id`);

--
-- Indexes for table `tbl_existingprograms`
--
ALTER TABLE `tbl_existingprograms`
  ADD PRIMARY KEY (`existingprograms_id`,`program_id`,`potentialcustomer_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `potentialcustomer_id` (`potentialcustomer_id`);

--
-- Indexes for table `tbl_existingservices`
--
ALTER TABLE `tbl_existingservices`
  ADD PRIMARY KEY (`existingservices_id`,`services_id`,`potentialcustomer_id`),
  ADD KEY `services_id` (`services_id`),
  ADD KEY `potentialcustomer_id` (`potentialcustomer_id`);

--
-- Indexes for table `tbl_facility`
--
ALTER TABLE `tbl_facility`
  ADD PRIMARY KEY (`facility_id`);

--
-- Indexes for table `tbl_gradelevel`
--
ALTER TABLE `tbl_gradelevel`
  ADD PRIMARY KEY (`gradelevel_id`);

--
-- Indexes for table `tbl_images`
--
ALTER TABLE `tbl_images`
  ADD PRIMARY KEY (`images_id`),
  ADD KEY `evaluation_id` (`evaluation_id`);

--
-- Indexes for table `tbl_municipality`
--
ALTER TABLE `tbl_municipality`
  ADD PRIMARY KEY (`municipality_id`),
  ADD UNIQUE KEY `municipality_id` (`municipality_id`),
  ADD KEY `province_id` (`province_id`);

--
-- Indexes for table `tbl_partners`
--
ALTER TABLE `tbl_partners`
  ADD PRIMARY KEY (`partners_id`);

--
-- Indexes for table `tbl_population`
--
ALTER TABLE `tbl_population`
  ADD PRIMARY KEY (`population_id`,`potentialcustomer_id`),
  ADD KEY `tbl_population_ibfk_1` (`potentialcustomer_id`);

--
-- Indexes for table `tbl_potentialcustomer`
--
ALTER TABLE `tbl_potentialcustomer`
  ADD PRIMARY KEY (`potentialcustomer_id`,`user_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `sector_id` (`sector_id`);

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`products_id`,`services_id`),
  ADD KEY `services_id` (`services_id`);

--
-- Indexes for table `tbl_program`
--
ALTER TABLE `tbl_program`
  ADD PRIMARY KEY (`program_id`);

--
-- Indexes for table `tbl_province`
--
ALTER TABLE `tbl_province`
  ADD PRIMARY KEY (`province_id`),
  ADD UNIQUE KEY `province_id` (`province_id`),
  ADD KEY `region_id` (`region_id`);

--
-- Indexes for table `tbl_rating`
--
ALTER TABLE `tbl_rating`
  ADD PRIMARY KEY (`rating_id`,`criteria_id`,`evaluation_id`),
  ADD KEY `criteria_id` (`criteria_id`),
  ADD KEY `evaluation_id` (`evaluation_id`);

--
-- Indexes for table `tbl_region`
--
ALTER TABLE `tbl_region`
  ADD PRIMARY KEY (`region_id`),
  ADD UNIQUE KEY `region_id` (`region_id`);

--
-- Indexes for table `tbl_sector`
--
ALTER TABLE `tbl_sector`
  ADD PRIMARY KEY (`sector_id`);

--
-- Indexes for table `tbl_services`
--
ALTER TABLE `tbl_services`
  ADD PRIMARY KEY (`services_id`);

--
-- Indexes for table `tbl_subcriteria`
--
ALTER TABLE `tbl_subcriteria`
  ADD PRIMARY KEY (`subcriteria_id`),
  ADD KEY `criteria_id` (`criteria_id`);

--
-- Indexes for table `tbl_subpopulation`
--
ALTER TABLE `tbl_subpopulation`
  ADD PRIMARY KEY (`subpopulation_id`,`gradelevel_id`,`population_id`),
  ADD KEY `gradelevel_id` (`gradelevel_id`),
  ADD KEY `population_id` (`population_id`);

--
-- Indexes for table `tbl_subrating`
--
ALTER TABLE `tbl_subrating`
  ADD PRIMARY KEY (`subrating_id`,`subcriteria_id`,`rating_id`),
  ADD KEY `subcriteria_id` (`subcriteria_id`),
  ADD KEY `rating_id` (`rating_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_contactperson`
--
ALTER TABLE `tbl_contactperson`
  MODIFY `contactperson_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_credentials`
--
ALTER TABLE `tbl_credentials`
  MODIFY `credentials_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_criteria`
--
ALTER TABLE `tbl_criteria`
  MODIFY `criteria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_evaluation`
--
ALTER TABLE `tbl_evaluation`
  MODIFY `evaluation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_existingfacilities`
--
ALTER TABLE `tbl_existingfacilities`
  MODIFY `existingfacilities_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_existingpartners`
--
ALTER TABLE `tbl_existingpartners`
  MODIFY `existingpartners_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_existingprograms`
--
ALTER TABLE `tbl_existingprograms`
  MODIFY `existingprograms_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_existingservices`
--
ALTER TABLE `tbl_existingservices`
  MODIFY `existingservices_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_facility`
--
ALTER TABLE `tbl_facility`
  MODIFY `facility_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_gradelevel`
--
ALTER TABLE `tbl_gradelevel`
  MODIFY `gradelevel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_images`
--
ALTER TABLE `tbl_images`
  MODIFY `images_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_municipality`
--
ALTER TABLE `tbl_municipality`
  MODIFY `municipality_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1635;

--
-- AUTO_INCREMENT for table `tbl_partners`
--
ALTER TABLE `tbl_partners`
  MODIFY `partners_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_population`
--
ALTER TABLE `tbl_population`
  MODIFY `population_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_potentialcustomer`
--
ALTER TABLE `tbl_potentialcustomer`
  MODIFY `potentialcustomer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `products_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_program`
--
ALTER TABLE `tbl_program`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_province`
--
ALTER TABLE `tbl_province`
  MODIFY `province_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `tbl_rating`
--
ALTER TABLE `tbl_rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_region`
--
ALTER TABLE `tbl_region`
  MODIFY `region_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_sector`
--
ALTER TABLE `tbl_sector`
  MODIFY `sector_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_services`
--
ALTER TABLE `tbl_services`
  MODIFY `services_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_subcriteria`
--
ALTER TABLE `tbl_subcriteria`
  MODIFY `subcriteria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_subpopulation`
--
ALTER TABLE `tbl_subpopulation`
  MODIFY `subpopulation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_subrating`
--
ALTER TABLE `tbl_subrating`
  MODIFY `subrating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_contactperson`
--
ALTER TABLE `tbl_contactperson`
  ADD CONSTRAINT `tbl_contactperson_ibfk_1` FOREIGN KEY (`potentialcustomer_id`) REFERENCES `tbl_potentialcustomer` (`potentialcustomer_id`);

--
-- Constraints for table `tbl_credentials`
--
ALTER TABLE `tbl_credentials`
  ADD CONSTRAINT `tbl_credentials_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`);

--
-- Constraints for table `tbl_evaluation`
--
ALTER TABLE `tbl_evaluation`
  ADD CONSTRAINT `tbl_evaluation_ibfk_1` FOREIGN KEY (`potentialcustomer_id`) REFERENCES `tbl_potentialcustomer` (`potentialcustomer_id`);

--
-- Constraints for table `tbl_existingfacilities`
--
ALTER TABLE `tbl_existingfacilities`
  ADD CONSTRAINT `tbl_existingfacilities_ibfk_1` FOREIGN KEY (`facility_id`) REFERENCES `tbl_facility` (`facility_id`),
  ADD CONSTRAINT `tbl_existingfacilities_ibfk_2` FOREIGN KEY (`potentialcustomer_id`) REFERENCES `tbl_potentialcustomer` (`potentialcustomer_id`);

--
-- Constraints for table `tbl_existingpartners`
--
ALTER TABLE `tbl_existingpartners`
  ADD CONSTRAINT `tbl_existingpartners_ibfk_1` FOREIGN KEY (`partners_id`) REFERENCES `tbl_partners` (`partners_id`),
  ADD CONSTRAINT `tbl_existingpartners_ibfk_2` FOREIGN KEY (`potentialcustomer_id`) REFERENCES `tbl_potentialcustomer` (`potentialcustomer_id`);

--
-- Constraints for table `tbl_existingprograms`
--
ALTER TABLE `tbl_existingprograms`
  ADD CONSTRAINT `tbl_existingprograms_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `tbl_program` (`program_id`),
  ADD CONSTRAINT `tbl_existingprograms_ibfk_2` FOREIGN KEY (`potentialcustomer_id`) REFERENCES `tbl_potentialcustomer` (`potentialcustomer_id`);

--
-- Constraints for table `tbl_existingservices`
--
ALTER TABLE `tbl_existingservices`
  ADD CONSTRAINT `tbl_existingservices_ibfk_1` FOREIGN KEY (`services_id`) REFERENCES `tbl_services` (`services_id`),
  ADD CONSTRAINT `tbl_existingservices_ibfk_2` FOREIGN KEY (`potentialcustomer_id`) REFERENCES `tbl_potentialcustomer` (`potentialcustomer_id`);

--
-- Constraints for table `tbl_images`
--
ALTER TABLE `tbl_images`
  ADD CONSTRAINT `tbl_images_ibfk_1` FOREIGN KEY (`evaluation_id`) REFERENCES `tbl_evaluation` (`evaluation_id`);

--
-- Constraints for table `tbl_municipality`
--
ALTER TABLE `tbl_municipality`
  ADD CONSTRAINT `tbl_municipality_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `tbl_province` (`province_id`);

--
-- Constraints for table `tbl_population`
--
ALTER TABLE `tbl_population`
  ADD CONSTRAINT `tbl_population_ibfk_1` FOREIGN KEY (`potentialcustomer_id`) REFERENCES `tbl_potentialcustomer` (`potentialcustomer_id`);

--
-- Constraints for table `tbl_potentialcustomer`
--
ALTER TABLE `tbl_potentialcustomer`
  ADD CONSTRAINT `tbl_potentialcustomer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`),
  ADD CONSTRAINT `tbl_potentialcustomer_ibfk_2` FOREIGN KEY (`sector_id`) REFERENCES `tbl_sector` (`sector_id`);

--
-- Constraints for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD CONSTRAINT `tbl_products_ibfk_1` FOREIGN KEY (`services_id`) REFERENCES `tbl_services` (`services_id`);

--
-- Constraints for table `tbl_province`
--
ALTER TABLE `tbl_province`
  ADD CONSTRAINT `tbl_province_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `tbl_region` (`region_id`);

--
-- Constraints for table `tbl_rating`
--
ALTER TABLE `tbl_rating`
  ADD CONSTRAINT `tbl_rating_ibfk_1` FOREIGN KEY (`criteria_id`) REFERENCES `tbl_criteria` (`criteria_id`),
  ADD CONSTRAINT `tbl_rating_ibfk_2` FOREIGN KEY (`evaluation_id`) REFERENCES `tbl_evaluation` (`evaluation_id`);

--
-- Constraints for table `tbl_subcriteria`
--
ALTER TABLE `tbl_subcriteria`
  ADD CONSTRAINT `tbl_subcriteria_ibfk_1` FOREIGN KEY (`criteria_id`) REFERENCES `tbl_criteria` (`criteria_id`);

--
-- Constraints for table `tbl_subpopulation`
--
ALTER TABLE `tbl_subpopulation`
  ADD CONSTRAINT `tbl_subpopulation_ibfk_1` FOREIGN KEY (`gradelevel_id`) REFERENCES `tbl_gradelevel` (`gradelevel_id`),
  ADD CONSTRAINT `tbl_subpopulation_ibfk_2` FOREIGN KEY (`population_id`) REFERENCES `tbl_population` (`population_id`);

--
-- Constraints for table `tbl_subrating`
--
ALTER TABLE `tbl_subrating`
  ADD CONSTRAINT `tbl_subrating_ibfk_1` FOREIGN KEY (`subcriteria_id`) REFERENCES `tbl_subcriteria` (`subcriteria_id`),
  ADD CONSTRAINT `tbl_subrating_ibfk_2` FOREIGN KEY (`rating_id`) REFERENCES `tbl_rating` (`rating_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
