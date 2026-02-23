-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2025 at 07:13 AM
-- Server version: 11.8.3-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cvforge_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `edu_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `degree` varchar(150) NOT NULL,
  `school` varchar(150) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`edu_id`, `user_id`, `degree`, `school`, `start_date`, `end_date`, `description`, `created_at`) VALUES
(1, 6, 'Bsc', 'Comsats', '2025-11-01', '2025-11-29', 'i was best student there', '2025-11-07 17:38:27'),
(2, 6, 'Bsc', 'Nust', '2025-11-01', '2025-11-29', 'good', '2025-11-07 17:39:01'),
(3, 6, 'Bsc', 'Comsats', '2025-11-08', '2025-11-23', 'good', '2025-11-07 19:29:34'),
(4, 6, 'Bsc', 'Comsats', '2025-11-08', '2025-11-23', 'l', '2025-11-07 19:46:17'),
(5, 2, 'Bsc', 'Comsats', '2025-11-08', '2025-11-23', 'i got top graduate award in my college', '2025-11-09 17:13:39'),
(6, 6, 'Bsc', 'Comsats', '2025-11-08', '2025-11-23', 'got first award', '2025-11-09 17:35:56'),
(7, 6, 'Bsc', 'Comsats', '2025-11-08', '2025-11-23', 'ggg', '2025-11-09 18:05:49'),
(8, 6, 'Bsc', 'Comsats', '2025-11-08', '2025-11-23', 'gg', '2025-11-09 18:06:45'),
(9, 8, 'Bsc', 'Comsats', '2025-11-08', '2025-11-23', 'well', '2025-11-09 18:46:38'),
(10, 8, 'Bsc', 'Comsats', '2025-11-08', '2025-11-23', 'welll', '2025-11-09 19:12:13'),
(11, 8, 'Bsc', 'Comsats', '2025-11-08', '2025-11-23', 'none', '2025-11-09 19:24:42'),
(12, 8, 'Bsc', 'Comsats', '2025-11-08', '2025-11-23', 'I was top ranked in my university and i was top graduate in my batch', '2025-11-10 18:47:29'),
(15, 10, 'Bsc', 'Nanjing Tech', '2025-11-08', '2025-11-23', 'I got the \"Jiangsu Provincial Scholarship\" in first academic year and got many certificates in Sports', '2025-11-12 16:10:51'),
(27, 12, 'Bsc', 'uni', '2025-11-08', '2025-11-23', 'well', '2025-11-13 07:15:46'),
(44, 8, 'Bsc', 'Standford university', '2025-11-08', '2025-11-23', 'kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkssssssssssssssssssssssssssssssssssssssss', '2025-11-16 16:57:34'),
(53, 26, 'Bsc', 'Standford university', '2025-11-08', '2025-11-23', 'okk', '2025-11-19 03:22:23');

-- --------------------------------------------------------

--
-- Table structure for table `experience`
--

CREATE TABLE `experience` (
  `exp_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_title` varchar(150) NOT NULL,
  `company` varchar(150) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `experience`
--

INSERT INTO `experience` (`exp_id`, `user_id`, `job_title`, `company`, `start_date`, `end_date`, `description`, `created_at`) VALUES
(1, 1, 'software engineer', 'google', '2025-11-14', '2025-11-27', 'i work as an emplooye', '2025-11-06 06:27:35'),
(2, 1, 'software engineer', 'google', '2025-11-14', '2025-11-27', 'i work as an emplooye', '2025-11-06 06:27:36'),
(3, 2, 'Web developer', 'google', '2025-11-14', '2025-11-27', 'i am a good web developer', '2025-11-06 06:36:56'),
(4, 2, 'Cyber engineer', 'Microsoft', '2025-11-14', '2025-11-27', 'i work as a Cyber security enigeer', '2025-11-06 06:37:57'),
(6, 6, 'Cyber engineer', 'TechView', '2025-11-04', '2025-11-29', 'i work as a boss', '2025-11-07 17:21:50'),
(7, 6, 'Cyber engineer', 'TechView', '2025-11-04', '2025-11-29', 'boss', '2025-11-07 17:39:34'),
(8, 6, 'software engineer', 'google', '2025-11-28', '2025-12-01', 'ok', '2025-11-07 19:29:11'),
(9, 6, 'software engineer', 'google', '2025-11-28', '2025-12-01', 'good', '2025-11-07 19:34:33'),
(10, 6, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'fair', '2025-11-07 19:35:57'),
(11, 6, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'fair', '2025-11-07 19:36:12'),
(12, 6, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'no', '2025-11-07 19:36:20'),
(13, 6, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'no', '2025-11-07 19:39:19'),
(14, 6, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'no', '2025-11-07 19:39:25'),
(15, 6, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'oo', '2025-11-07 19:40:05'),
(16, 6, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'no', '2025-11-07 19:43:11'),
(17, 6, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'mp', '2025-11-07 19:46:06'),
(18, 6, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'oo', '2025-11-07 19:49:19'),
(19, 6, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'oo', '2025-11-07 19:54:13'),
(20, 6, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'ooploo', '2025-11-07 19:55:12'),
(21, 6, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'ppllkk', '2025-11-07 20:10:02'),
(22, 2, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'i have a lot of experience in this work and i am good at this ', '2025-11-09 17:13:14'),
(23, 6, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'good at this ', '2025-11-09 17:35:44'),
(24, 6, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'hhh', '2025-11-09 18:03:19'),
(25, 8, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'goood', '2025-11-09 18:46:29'),
(26, 8, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'good', '2025-11-09 19:12:05'),
(27, 8, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'good', '2025-11-09 19:24:34'),
(28, 8, 'software engineer', 'TechView', '2025-10-26', '2025-11-30', 'I work in company and i work as a boss assistant i was very good in managing customers ', '2025-11-10 18:46:52'),
(31, 10, 'Software Engineer', 'TechView', '2025-10-26', '2025-11-30', 'I work as full time employee i was good at managing people, problem solving and have a strong bond with my colleges ', '2025-11-12 16:09:37'),
(45, 8, 'will science ', 'gotech ', '2025-10-26', '2025-11-30', 'go in ', '2025-11-13 06:24:30'),
(49, 12, 'software ', 'gotech ', '2025-10-26', '2025-11-30', 'good', '2025-11-13 07:15:13'),
(61, 8, 'software engineer', 'gotech ', '2025-11-07', '2025-11-25', 'okikokiojggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg', '2025-11-16 16:57:15'),
(68, 26, 'software engineer', 'gotech ', '2025-11-01', '2025-11-01', 'Good ', '2025-11-19 03:22:15');

-- --------------------------------------------------------

--
-- Table structure for table `final_resume`
--

CREATE TABLE `final_resume` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `data_json` longtext NOT NULL,
  `template_choice` varchar(50) DEFAULT NULL,
  `status` enum('draft','final') DEFAULT 'final',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `final_resume`
--

INSERT INTO `final_resume` (`id`, `user_id`, `data_json`, `template_choice`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, '{\"header\":[],\"experience\":[{\"exp_id\":\"3\",\"user_id\":\"2\",\"job_title\":\"Web developer\",\"company\":\"google\",\"start_date\":\"2025-11-14\",\"end_date\":\"2025-11-27\",\"description\":\"i am a good web developer\",\"created_at\":\"2025-11-05 22:36:56\"},{\"exp_id\":\"4\",\"user_id\":\"2\",\"job_title\":\"Cyber engineer\",\"company\":\"Microsoft\",\"start_date\":\"2025-11-14\",\"end_date\":\"2025-11-27\",\"description\":\"i work as a Cyber security enigeer\",\"created_at\":\"2025-11-05 22:37:57\"},{\"exp_id\":\"22\",\"user_id\":\"2\",\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"i have a lot of experience in this work and i am good at this \",\"created_at\":\"2025-11-09 09:13:14\"}],\"education\":[{\"edu_id\":\"5\",\"user_id\":\"2\",\"degree\":\"Bsc\",\"school\":\"Comsats\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"i got top graduate award in my college\",\"created_at\":\"2025-11-09 09:13:39\"}],\"languages\":[{\"id\":\"7\",\"user_id\":\"2\",\"language\":\"English\",\"proficiency\":\"100\",\"created_at\":\"2025-11-09 09:14:47\"},{\"id\":\"8\",\"user_id\":\"2\",\"language\":\"Chinese\",\"proficiency\":\"80\",\"created_at\":\"2025-11-09 09:14:47\"},{\"id\":\"9\",\"user_id\":\"2\",\"language\":\"French\",\"proficiency\":\"74\",\"created_at\":\"2025-11-09 09:14:47\"}],\"skills\":[{\"skill_id\":\"12\",\"user_id\":\"2\",\"skill_name\":\"python\",\"proficiency\":\"Intermediate\",\"years_experience\":\"2\",\"rating\":\"5\",\"created_at\":\"2025-11-09 09:13:56\"},{\"skill_id\":\"13\",\"user_id\":\"2\",\"skill_name\":\"leadership\",\"proficiency\":\"Expert\",\"years_experience\":\"4\",\"rating\":\"5\",\"created_at\":\"2025-11-09 09:14:26\"}],\"summary\":[{\"id\":\"3\",\"user_id\":\"2\",\"headline\":\"Computer Science \",\"about\":\"i am good at coding\",\"objective\":\"i will make same AI chatbots\",\"years_experience\":\"2\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"achievements\":\"Scholarships\",\"projects\":\"Make a AI chatbot\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\",\"hobbies\":\"Badminton \",\"created_at\":\"2025-11-09 09:15:59\"}]}', NULL, 'final', '2025-11-09 17:16:25', '2025-11-09 17:17:24'),
(2, 8, '{\"header\":[{\"id\":\"10\",\"first_name\":\"Ameer \",\"last_name\":\"Subhan \",\"city\":\"Karachi\",\"state\":\"Pakistan\",\"zip\":\"430000\",\"email\":\"ameer@2334455.COM\",\"phone\":\"199863562\",\"user_id\":\"8\",\"profile_image\":\"1762800368_fraud 2.jpg\"}],\"experience\":[{\"exp_id\":\"25\",\"user_id\":\"8\",\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"goood\",\"created_at\":\"2025-11-09 10:46:29\"},{\"exp_id\":\"26\",\"user_id\":\"8\",\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"good\",\"created_at\":\"2025-11-09 11:12:05\"},{\"exp_id\":\"27\",\"user_id\":\"8\",\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"good\",\"created_at\":\"2025-11-09 11:24:34\"},{\"exp_id\":\"28\",\"user_id\":\"8\",\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"I work in company and i work as a boss assistant i was very good in managing customers \",\"created_at\":\"2025-11-10 10:46:52\"}],\"education\":[{\"edu_id\":\"9\",\"user_id\":\"8\",\"degree\":\"Bsc\",\"school\":\"Comsats\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"well\",\"created_at\":\"2025-11-09 10:46:38\"},{\"edu_id\":\"10\",\"user_id\":\"8\",\"degree\":\"Bsc\",\"school\":\"Comsats\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"welll\",\"created_at\":\"2025-11-09 11:12:13\"},{\"edu_id\":\"11\",\"user_id\":\"8\",\"degree\":\"Bsc\",\"school\":\"Comsats\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"none\",\"created_at\":\"2025-11-09 11:24:42\"},{\"edu_id\":\"12\",\"user_id\":\"8\",\"degree\":\"Bsc\",\"school\":\"Comsats\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"I was top ranked in my university and i was top graduate in my batch\",\"created_at\":\"2025-11-10 10:47:29\"}],\"languages\":[{\"id\":\"13\",\"user_id\":\"8\",\"language\":\"English\",\"proficiency\":\"80\",\"created_at\":\"2025-11-10 10:48:16\"},{\"id\":\"14\",\"user_id\":\"8\",\"language\":\"Chinese\",\"proficiency\":\"100\",\"created_at\":\"2025-11-10 10:48:16\"},{\"id\":\"15\",\"user_id\":\"8\",\"language\":\"French\",\"proficiency\":\"82\",\"created_at\":\"2025-11-10 10:48:16\"}],\"skills\":[{\"skill_id\":\"15\",\"user_id\":\"8\",\"skill_name\":\"python\",\"proficiency\":\"Expert\",\"years_experience\":\"5\",\"rating\":\"5\",\"created_at\":\"2025-11-09 10:46:53\"},{\"skill_id\":\"16\",\"user_id\":\"8\",\"skill_name\":\"python\",\"proficiency\":\"Expert\",\"years_experience\":\"2\",\"rating\":\"5\",\"created_at\":\"2025-11-09 11:12:26\"},{\"skill_id\":\"17\",\"user_id\":\"8\",\"skill_name\":\"python\",\"proficiency\":\"Intermediate\",\"years_experience\":\"0\",\"rating\":\"3\",\"created_at\":\"2025-11-09 11:24:49\"},{\"skill_id\":\"18\",\"user_id\":\"8\",\"skill_name\":\"python\",\"proficiency\":\"Expert\",\"years_experience\":\"2\",\"rating\":\"5\",\"created_at\":\"2025-11-10 10:47:41\"},{\"skill_id\":\"19\",\"user_id\":\"8\",\"skill_name\":\"C++\",\"proficiency\":\"Intermediate\",\"years_experience\":\"2\",\"rating\":\"4\",\"created_at\":\"2025-11-10 10:48:00\"}],\"summary\":[{\"id\":\"5\",\"user_id\":\"8\",\"headline\":\"Computer Science \",\"about\":\"was very good in coding i work as a lab assistant with my professor \",\"objective\":\"i want to work in the field of robotics and wanna learn more about that \",\"years_experience\":\"2\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"achievements\":\"\",\"projects\":\"i got the jiangsu provincial scholarship in first academic year \",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\",\"hobbies\":\"Badminton \",\"created_at\":\"2025-11-09 10:47:29\"},{\"id\":\"6\",\"user_id\":\"8\",\"headline\":\"Computer Science \",\"about\":\"was very good in coding i work as a lab assistant with my professor \",\"objective\":\"i want to work in the field of robotics and wanna learn more about that \",\"years_experience\":\"2\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"achievements\":\"\",\"projects\":\"i got the jiangsu provincial scholarship in first academic year \",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\",\"hobbies\":\"Badminton \",\"created_at\":\"2025-11-09 11:12:54\"},{\"id\":\"7\",\"user_id\":\"8\",\"headline\":\"Computer Science \",\"about\":\"was very good in coding i work as a lab assistant with my professor \",\"objective\":\"i want to work in the field of robotics and wanna learn more about that \",\"years_experience\":\"2\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"achievements\":\"\",\"projects\":\"i got the jiangsu provincial scholarship in first academic year \",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\",\"hobbies\":\"Badminton \",\"created_at\":\"2025-11-09 11:25:20\"}]}', NULL, 'final', '2025-11-09 19:37:41', '2025-11-10 21:24:19'),
(3, 12, '{\"header\":[{\"id\":\"15\",\"first_name\":\"james \",\"last_name\":\"bond\",\"city\":\"NY\",\"state\":\"LA\",\"zip\":\"2929292\",\"email\":\"qasim5540@gmail.com\",\"phone\":\"515162721881\",\"user_id\":\"12\",\"profile_image\":\"1763018094_profile pic.jpg\"}],\"experience\":[{\"exp_id\":\"49\",\"user_id\":\"12\",\"job_title\":\"software \",\"company\":\"gotech \",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"good\",\"created_at\":\"2025-11-12 23:15:13\"}],\"education\":[{\"edu_id\":\"27\",\"user_id\":\"12\",\"degree\":\"Bsc\",\"school\":\"uni\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"well\",\"created_at\":\"2025-11-12 23:15:46\"}],\"languages\":[{\"id\":\"25\",\"user_id\":\"12\",\"language\":\"English\",\"proficiency\":\"80\",\"created_at\":\"2025-11-12 23:16:28\"},{\"id\":\"26\",\"user_id\":\"12\",\"language\":\"Chinese\",\"proficiency\":\"87\",\"created_at\":\"2025-11-12 23:16:28\"}],\"skills\":[{\"skill_id\":\"27\",\"user_id\":\"12\",\"skill_name\":\"python\",\"proficiency\":\"Expert\",\"years_experience\":\"1\",\"rating\":\"3\",\"created_at\":\"2025-11-12 23:16:17\"}],\"summary\":[{\"id\":\"11\",\"user_id\":\"12\",\"headline\":\"research\",\"about\":\"wel kkaahshvs\",\"objective\":\"ghsddhhdd\",\"years_experience\":\"1\",\"strengths\":\"Problem\",\"soft_skills\":\"Communication\",\"achievements\":\"2024\",\"projects\":\"AI\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\",\"hobbies\":\"Badminton \",\"created_at\":\"2025-11-12 23:17:05\"}]}', NULL, 'final', '2025-11-13 07:18:24', '2025-11-13 07:18:24'),
(4, 26, '{\"header\":[{\"first_name\":\"JHON\",\"last_name\":\"DOE\",\"city\":\"NEWYORK\",\"state\":\"USA\",\"zip\":\"52000\",\"email\":\"jhondoe@gmail.com\",\"phone\":\"176373763373\",\"profile_image\":null}],\"experience\":[{\"job_title\":\"software engineer\",\"company\":\"gotech \",\"start_date\":\"2025-11-01\",\"end_date\":\"2025-11-01\",\"description\":\"Good \"}],\"education\":[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"okk\"}],\"languages\":[{\"language\":\"English\",\"proficiency\":80},{\"language\":\"arabic\",\"proficiency\":70}],\"skills\":[{\"skill_name\":\"C++\",\"proficiency\":\"Beginner\",\"years_experience\":\"1\",\"rating\":\"3\"}],\"summary\":[{\"headline\":\"Material Engineer \",\"about\":\"okk \",\"objective\":\"jjjjj\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2024\",\"projects\":\"ai\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]}', NULL, 'final', '2025-11-19 03:24:42', '2025-11-19 20:08:00'),
(5, 1, '{\"header\":[{\"first_name\":\"Zayn\",\"last_name\":\"Watson\",\"city\":\"Nanjing\",\"state\":\"Jiangsu\",\"zip\":\"88888\",\"email\":\"ValentinaRaymondwknby@outlook.may\",\"phone\":\"19705169539\",\"profile_image\":null}],\"experience\":[],\"education\":[],\"languages\":[],\"skills\":[],\"summary\":[]}', NULL, 'final', '2025-11-19 05:37:19', '2025-11-19 05:37:19'),
(6, 9, '{\"header\":[{\"first_name\":\"Jack\",\"last_name\":\"Watson\",\"city\":\"Nanjing\",\"state\":\"Jiangsu\",\"zip\":\"88888\",\"email\":\"\",\"phone\":\"12345678\",\"profile_image\":null}],\"experience\":[{\"job_title\":\"Software engineer\",\"company\":\"Gotech \",\"start_date\":\"2025-11-07\",\"end_date\":\"2025-11-25\",\"description\":\"Vivamus tristique vestibulum justo, sit amet aliquam purus feugiat eu. Integer aliquam arcu vitae sem dictum, sed facilisis erat consequat. \"},{\"job_title\":\"Engineer \",\"company\":\"Google\",\"start_date\":\"2025-11-29\",\"end_date\":\"2025-11-22\",\"description\":\"okk\"}],\"education\":[{\"degree\":\"Bachelor Engineer\",\"school\":\"Standford\",\"start_date\":\"2025-11-28\",\"end_date\":\"2025-11-20\",\"description\":\"okk\"}],\"languages\":[{\"language\":\"English\",\"proficiency\":80}],\"skills\":[{\"skill_name\":\"c++\",\"proficiency\":\"Beginner\",\"years_experience\":\"1\",\"rating\":\"3\"}],\"summary\":[{\"headline\":\"Reasher \",\"about\":\"okk\",\"objective\":\"okk\",\"years_experience\":\"1\",\"strengths\":\"oo\",\"soft_skills\":\"l\",\"hobbies\":\"Reading \",\"achievements\":\"2024\",\"projects\":\"ai\",\"linkedin\":\"https:\\/\\/linkdin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]}', NULL, 'final', '2025-11-19 05:45:25', '2025-11-19 06:17:59');

-- --------------------------------------------------------

--
-- Table structure for table `header`
--

CREATE TABLE `header` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `header`
--

INSERT INTO `header` (`id`, `first_name`, `last_name`, `city`, `state`, `zip`, `email`, `phone`, `user_id`, `profile_image`) VALUES
(7, 'Zayn', 'Watson', 'Nanjing', 'Jiangsu', '88888', 'ValentinaRaymondwknby@outlook.may', '19705169539', 1, NULL),
(8, 'ZAYN', 'Ali', 'Islamabad', 'Pakistan', '52000', 'Ali@1100', '', 6, NULL),
(10, 'AHMAD', 'KHAN', 'SWAT', 'PAKISTAN', '54000', 'ahmadkhan@216', '1736278263', 8, NULL),
(14, 'Ahmad', 'Wick ', 'Swat', 'Pakistan ', '34000', 'ahmad@216', '16677888', 10, '1762963713_profile pic.jpg'),
(15, 'james ', 'bond', 'NY', 'LA', '2929292', 'qasim5540@gmail.com', '515162721881', 12, '1763018094_profile pic.jpg'),
(18, 'WICK', 'DOE', 'NEWYORK', 'USA', '52000', 'jhondoe@gmail.com', '176373763373', 26, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `proficiency` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `user_id`, `language`, `proficiency`, `created_at`) VALUES
(7, 2, 'English', 100, '2025-11-09 17:14:47'),
(8, 2, 'Chinese', 80, '2025-11-09 17:14:47'),
(9, 2, 'French', 74, '2025-11-09 17:14:47'),
(10, 6, 'English', 80, '2025-11-09 18:11:15'),
(19, 10, 'English', 86, '2025-11-12 16:11:27'),
(20, 10, 'Chinese', 82, '2025-11-12 16:11:27'),
(21, 10, 'French', 92, '2025-11-12 16:11:27'),
(25, 12, 'English', 80, '2025-11-13 07:16:28'),
(26, 12, 'Chinese', 87, '2025-11-13 07:16:28'),
(51, 8, 'arabic', 80, '2025-11-16 16:58:14'),
(52, 8, 'English', 70, '2025-11-16 16:58:14'),
(76, 26, 'English', 80, '2025-11-19 03:22:46'),
(77, 26, 'arabic', 70, '2025-11-19 03:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resume_versions`
--

CREATE TABLE `resume_versions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `version_name` varchar(100) DEFAULT 'Current',
  `template_used` varchar(50) DEFAULT NULL,
  `header_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`header_data`)),
  `experience_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`experience_data`)),
  `education_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`education_data`)),
  `skills_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`skills_data`)),
  `languages_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`languages_data`)),
  `summary_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`summary_data`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resume_versions`
--

INSERT INTO `resume_versions` (`id`, `user_id`, `version_name`, `template_used`, `header_data`, `experience_data`, `education_data`, `skills_data`, `languages_data`, `summary_data`, `created_at`, `updated_at`, `is_active`) VALUES
(6, 8, 'Current', '1', '[{\"first_name\":\"Ahmad\",\"last_name\":\"Wick\",\"city\":\"Swat\",\"state\":\"Pakistan\",\"zip\":\"34000\",\"email\":\"ahmad@216\",\"phone\":\"16677888\",\"profile_image\":\"1763159568_fraud 1.jpg\"}]', '[{\"job_title\":\"Software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-12-05\",\"end_date\":\"2025-11-28\",\"description\":\"hood\"}]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"well\"}]', '[{\"skill_name\":\"C++\",\"proficiency\":\"Expert\",\"years_experience\":\"4\",\"rating\":\"4\"},{\"skill_name\":\"python\",\"proficiency\":\"Beginner\",\"years_experience\":\"1\",\"rating\":\"3\"}]', '[{\"language\":\"English\",\"proficiency\":80},{\"language\":\"Chinese\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"good\",\"objective\":\"well\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"ok\",\"projects\":\"nicce\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-14 22:34:09', '2025-11-14 22:34:09', 1),
(7, 8, 'Current', '1', '[{\"first_name\":\"ALI\",\"last_name\":\"Wick\",\"city\":\"Swat\",\"state\":\"Pakistan\",\"zip\":\"34000\",\"email\":\"ahmad@216\",\"phone\":\"16677888\",\"profile_image\":null}]', '[{\"job_title\":\"Software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-12-05\",\"end_date\":\"2025-11-28\",\"description\":\"hood\"},{\"job_title\":\"Computer science \",\"company\":\"TechView\",\"start_date\":\"2025-12-05\",\"end_date\":\"2025-11-28\",\"description\":\"ok\"}]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"well\"}]', '[{\"skill_name\":\"C++\",\"proficiency\":\"Expert\",\"years_experience\":\"4\",\"rating\":\"4\"},{\"skill_name\":\"python\",\"proficiency\":\"Beginner\",\"years_experience\":\"1\",\"rating\":\"3\"}]', '[{\"language\":\"English\",\"proficiency\":80},{\"language\":\"Chinese\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"good\",\"objective\":\"well\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"ok\",\"projects\":\"nicce\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-14 22:36:58', '2025-11-14 22:36:58', 1),
(27, 8, 'Current', '3', '[{\"first_name\":\"AHMAD\",\"last_name\":\"KHAN\",\"city\":\"SWAT\",\"state\":\"PAKISTAN\",\"zip\":\"54000\",\"email\":\"ahmadkhan@216\",\"phone\":\"1736278263\",\"profile_image\":null}]', '[{\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"goood\"},{\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"good\"},{\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"good\"},{\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"I work in company and i work as a boss assistant i was very good in managing customers \"},{\"job_title\":\"will science \",\"company\":\"gotech \",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"go in \"},{\"job_title\":\"software engineer\",\"company\":\"gotech \",\"start_date\":\"2025-11-07\",\"end_date\":\"2025-11-25\",\"description\":\"okikokiojggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg\"}]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkssssssssssssssssssssssssssssssssssssssss\"}]', '[{\"skill_name\":\"python\",\"proficiency\":\"Intermediate\",\"years_experience\":\"3\",\"rating\":\"3\"},{\"skill_name\":\"Data Analyst \",\"proficiency\":\"Expert\",\"years_experience\":\"2\",\"rating\":\"3\"}]', '[{\"language\":\"arabic\",\"proficiency\":80},{\"language\":\"English\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"hhhhhhhhhhhhhhhhhhhhhhhhhhhjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn\",\"objective\":\"kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2025\",\"projects\":\"2026\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-16 16:59:03', '2025-11-16 16:59:03', 1),
(28, 8, 'Current', '3', '[{\"first_name\":\"AHMAD\",\"last_name\":\"KHAN\",\"city\":\"SWAT\",\"state\":\"PAKISTAN\",\"zip\":\"54000\",\"email\":\"ahmadkhan@216\",\"phone\":\"1736278263\",\"profile_image\":null}]', '[{\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"goood\"},{\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"good\"},{\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"good\"},{\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"I work in company and i work as a boss assistant i was very good in managing customers \"},{\"job_title\":\"will science \",\"company\":\"gotech \",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"go in \"},{\"job_title\":\"software engineer\",\"company\":\"gotech \",\"start_date\":\"2025-11-07\",\"end_date\":\"2025-11-25\",\"description\":\"okikokiojggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg\"}]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkssssssssssssssssssssssssssssssssssssssss\"}]', '[{\"skill_name\":\"python\",\"proficiency\":\"Intermediate\",\"years_experience\":\"3\",\"rating\":\"3\"},{\"skill_name\":\"Data Analyst \",\"proficiency\":\"Expert\",\"years_experience\":\"2\",\"rating\":\"3\"}]', '[{\"language\":\"arabic\",\"proficiency\":80},{\"language\":\"English\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"hhhhhhhhhhhhhhhhhhhhhhhhhhhjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn\",\"objective\":\"kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2025\",\"projects\":\"2026\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-16 17:11:48', '2025-11-16 17:11:48', 1),
(29, 8, 'Current', '3', '[{\"first_name\":\"AHMAD\",\"last_name\":\"KHAN\",\"city\":\"SWAT\",\"state\":\"PAKISTAN\",\"zip\":\"54000\",\"email\":\"ahmadkhan@216\",\"phone\":\"1736278263\",\"profile_image\":null}]', '[{\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"goood\"},{\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"good\"},{\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"good\"},{\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"I work in company and i work as a boss assistant i was very good in managing customers \"},{\"job_title\":\"will science \",\"company\":\"gotech \",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"go in \"},{\"job_title\":\"software engineer\",\"company\":\"gotech \",\"start_date\":\"2025-11-07\",\"end_date\":\"2025-11-25\",\"description\":\"okikokiojggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg\"}]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkssssssssssssssssssssssssssssssssssssssss\"}]', '[{\"skill_name\":\"python\",\"proficiency\":\"Intermediate\",\"years_experience\":\"3\",\"rating\":\"3\"},{\"skill_name\":\"Data Analyst \",\"proficiency\":\"Expert\",\"years_experience\":\"2\",\"rating\":\"3\"}]', '[{\"language\":\"arabic\",\"proficiency\":80},{\"language\":\"English\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"hhhhhhhhhhhhhhhhhhhhhhhhhhhjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn\",\"objective\":\"kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2025\",\"projects\":\"2026\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-16 17:29:23', '2025-11-16 17:29:23', 1),
(30, 8, 'Current', '3', '[{\"first_name\":\"AHMAD\",\"last_name\":\"KHAN\",\"city\":\"SWAT\",\"state\":\"PAKISTAN\",\"zip\":\"54000\",\"email\":\"ahmadkhan@216\",\"phone\":\"1736278263\",\"profile_image\":null}]', '[{\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"goood\"},{\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"good\"},{\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"good\"},{\"job_title\":\"software engineer\",\"company\":\"TechView\",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"I work in company and i work as a boss assistant i was very good in managing customers \"},{\"job_title\":\"will science \",\"company\":\"gotech \",\"start_date\":\"2025-10-26\",\"end_date\":\"2025-11-30\",\"description\":\"go in \"},{\"job_title\":\"software engineer\",\"company\":\"gotech \",\"start_date\":\"2025-11-07\",\"end_date\":\"2025-11-25\",\"description\":\"okikokiojggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg\"}]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkssssssssssssssssssssssssssssssssssssssss\"}]', '[{\"skill_name\":\"python\",\"proficiency\":\"Intermediate\",\"years_experience\":\"3\",\"rating\":\"3\"},{\"skill_name\":\"Data Analyst \",\"proficiency\":\"Expert\",\"years_experience\":\"2\",\"rating\":\"3\"}]', '[{\"language\":\"arabic\",\"proficiency\":80},{\"language\":\"English\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"hhhhhhhhhhhhhhhhhhhhhhhhhhhjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn\",\"objective\":\"kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2025\",\"projects\":\"2026\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-16 17:30:39', '2025-11-16 17:30:39', 1),
(184, 26, 'Current', '2', '[{\"first_name\":\"JHON\",\"last_name\":\"DOE\",\"city\":\"NEWYORK\",\"state\":\"USA\",\"zip\":\"52000\",\"email\":\"jhondoe@gmail.com\",\"phone\":\"176373763373\",\"profile_image\":null}]', '[{\"job_title\":\"software engineer\",\"company\":\"gotech \",\"start_date\":\"2025-11-01\",\"end_date\":\"2025-11-01\",\"description\":\"Good \"}]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"okk\"}]', '[{\"skill_name\":\"C++\",\"proficiency\":\"Intermediate\",\"years_experience\":\"2\",\"rating\":\"3\"}]', '[{\"language\":\"English\",\"proficiency\":80},{\"language\":\"arabic\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"okk \",\"objective\":\"jjjjj\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2024\",\"projects\":\"ai\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-19 03:24:42', '2025-11-19 03:24:42', 1),
(185, 26, 'Current', '2', '[{\"first_name\":\"JHON\",\"last_name\":\"DOE\",\"city\":\"NEWYORK\",\"state\":\"USA\",\"zip\":\"52000\",\"email\":\"jhondoe@gmail.com\",\"phone\":\"176373763373\",\"profile_image\":null}]', '[{\"job_title\":\"software engineer\",\"company\":\"gotech \",\"start_date\":\"2025-11-01\",\"end_date\":\"2025-11-01\",\"description\":\"Good \"}]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"okk\"}]', '[{\"skill_name\":\"C++\",\"proficiency\":\"Intermediate\",\"years_experience\":\"2\",\"rating\":\"3\"}]', '[{\"language\":\"English\",\"proficiency\":80},{\"language\":\"arabic\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"okk \",\"objective\":\"jjjjj\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2024\",\"projects\":\"ai\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-19 03:24:47', '2025-11-19 03:24:47', 1),
(186, 26, 'Current', '2', '[{\"first_name\":\"JHON\",\"last_name\":\"DOE\",\"city\":\"NEWYORK\",\"state\":\"USA\",\"zip\":\"52000\",\"email\":\"jhondoe@gmail.com\",\"phone\":\"176373763373\",\"profile_image\":null}]', '[{\"job_title\":\"software engineer\",\"company\":\"gotech \",\"start_date\":\"2025-11-01\",\"end_date\":\"2025-11-01\",\"description\":\"Good \"}]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"okk\"}]', '[{\"skill_name\":\"C++\",\"proficiency\":\"Intermediate\",\"years_experience\":\"2\",\"rating\":\"3\"}]', '[{\"language\":\"English\",\"proficiency\":80},{\"language\":\"arabic\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"okk \",\"objective\":\"jjjjj\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2024\",\"projects\":\"ai\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-19 03:25:12', '2025-11-19 03:25:12', 1),
(187, 26, 'Current', '2', '[{\"first_name\":\"JHON\",\"last_name\":\"DOE\",\"city\":\"NEWYORK\",\"state\":\"USA\",\"zip\":\"52000\",\"email\":\"jhondoe@gmail.com\",\"phone\":\"176373763373\",\"profile_image\":null}]', '[{\"job_title\":\"software engineer\",\"company\":\"gotech \",\"start_date\":\"2025-11-01\",\"end_date\":\"2025-11-01\",\"description\":\"Good \"}]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"okk\"}]', '[{\"skill_name\":\"C++\",\"proficiency\":\"Intermediate\",\"years_experience\":\"2\",\"rating\":\"3\"}]', '[{\"language\":\"English\",\"proficiency\":80},{\"language\":\"arabic\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"okk \",\"objective\":\"jjjjj\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2024\",\"projects\":\"ai\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-19 03:25:38', '2025-11-19 03:25:38', 1),
(188, 26, 'Current', '1', '[{\"first_name\":\"JHON\",\"last_name\":\"DOE\",\"city\":\"NEWYORK\",\"state\":\"USA\",\"zip\":\"52000\",\"email\":\"jhondoe@gmail.com\",\"phone\":\"176373763373\",\"profile_image\":null}]', '[]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"okk\"}]', '[{\"skill_name\":\"C++\",\"proficiency\":\"Intermediate\",\"years_experience\":\"2\",\"rating\":\"3\"}]', '[{\"language\":\"English\",\"proficiency\":80},{\"language\":\"arabic\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"okk \",\"objective\":\"jjjjj\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2024\",\"projects\":\"ai\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-19 04:15:01', '2025-11-19 04:15:01', 1),
(189, 26, 'Current', '1', '[{\"first_name\":\"JHON\",\"last_name\":\"DOE\",\"city\":\"NEWYORK\",\"state\":\"USA\",\"zip\":\"52000\",\"email\":\"jhondoe@gmail.com\",\"phone\":\"176373763373\",\"profile_image\":null}]', '[]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"okk\"}]', '[{\"skill_name\":\"C++\",\"proficiency\":\"Intermediate\",\"years_experience\":\"2\",\"rating\":\"3\"}]', '[{\"language\":\"English\",\"proficiency\":80},{\"language\":\"arabic\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"okk \",\"objective\":\"jjjjj\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2024\",\"projects\":\"ai\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-19 04:15:05', '2025-11-19 04:15:05', 1),
(190, 26, 'Current', '1', '[{\"first_name\":\"JHON\",\"last_name\":\"DOE\",\"city\":\"NEWYORK\",\"state\":\"USA\",\"zip\":\"52000\",\"email\":\"jhondoe@gmail.com\",\"phone\":\"176373763373\",\"profile_image\":null}]', '[]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"okk\"}]', '[{\"skill_name\":\"C++\",\"proficiency\":\"Intermediate\",\"years_experience\":\"2\",\"rating\":\"3\"}]', '[{\"language\":\"English\",\"proficiency\":80},{\"language\":\"arabic\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"okk \",\"objective\":\"jjjjj\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2024\",\"projects\":\"ai\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-19 04:15:12', '2025-11-19 04:15:12', 1),
(191, 26, 'Current', '1', '[{\"first_name\":\"JHON\",\"last_name\":\"DOE\",\"city\":\"NEWYORK\",\"state\":\"USA\",\"zip\":\"52000\",\"email\":\"jhondoe@gmail.com\",\"phone\":\"176373763373\",\"profile_image\":null}]', '[]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"okk\"}]', '[{\"skill_name\":\"C++\",\"proficiency\":\"Intermediate\",\"years_experience\":\"2\",\"rating\":\"3\"}]', '[{\"language\":\"English\",\"proficiency\":80},{\"language\":\"arabic\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"okk \",\"objective\":\"jjjjj\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2024\",\"projects\":\"ai\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-19 04:15:14', '2025-11-19 04:15:14', 1),
(192, 26, 'Current', '1', '[{\"first_name\":\"JHON\",\"last_name\":\"DOE\",\"city\":\"NEWYORK\",\"state\":\"USA\",\"zip\":\"52000\",\"email\":\"jhondoe@gmail.com\",\"phone\":\"176373763373\",\"profile_image\":null}]', '[]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"okk\"}]', '[{\"skill_name\":\"C++\",\"proficiency\":\"Intermediate\",\"years_experience\":\"2\",\"rating\":\"3\"}]', '[{\"language\":\"English\",\"proficiency\":80},{\"language\":\"arabic\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"okk \",\"objective\":\"jjjjj\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2024\",\"projects\":\"ai\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-19 04:15:16', '2025-11-19 04:15:16', 1),
(193, 26, 'Current', '1', '[{\"first_name\":\"JHON\",\"last_name\":\"DOE\",\"city\":\"NEWYORK\",\"state\":\"USA\",\"zip\":\"52000\",\"email\":\"jhondoe@gmail.com\",\"phone\":\"176373763373\",\"profile_image\":null}]', '[]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"okk\"}]', '[{\"skill_name\":\"C++\",\"proficiency\":\"Intermediate\",\"years_experience\":\"2\",\"rating\":\"3\"}]', '[{\"language\":\"English\",\"proficiency\":80},{\"language\":\"arabic\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"okk \",\"objective\":\"jjjjj\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2024\",\"projects\":\"ai\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-19 04:19:25', '2025-11-19 04:19:25', 1),
(194, 26, 'Current', '1', '[{\"first_name\":\"JHON\",\"last_name\":\"DOE\",\"city\":\"NEWYORK\",\"state\":\"USA\",\"zip\":\"52000\",\"email\":\"jhondoe@gmail.com\",\"phone\":\"176373763373\",\"profile_image\":null}]', '[]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"okk\"}]', '[{\"skill_name\":\"C++\",\"proficiency\":\"Intermediate\",\"years_experience\":\"2\",\"rating\":\"3\"}]', '[{\"language\":\"English\",\"proficiency\":80},{\"language\":\"arabic\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"okk \",\"objective\":\"jjjjj\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2024\",\"projects\":\"ai\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-19 04:21:58', '2025-11-19 04:21:58', 1),
(201, 1, 'Current', '1', '[{\"first_name\":\"Zayn\",\"last_name\":\"Watson\",\"city\":\"Nanjing\",\"state\":\"Jiangsu\",\"zip\":\"88888\",\"email\":\"ValentinaRaymondwknby@outlook.may\",\"phone\":\"19705169539\",\"profile_image\":null}]', '[]', '[]', '[]', '[]', '[]', '2025-11-19 05:37:19', '2025-11-19 05:37:19', 1),
(209, 26, 'Current', '6', '[{\"first_name\":\"JHON\",\"last_name\":\"DOE\",\"city\":\"NEWYORK\",\"state\":\"USA\",\"zip\":\"52000\",\"email\":\"jhondoe@gmail.com\",\"phone\":\"176373763373\",\"profile_image\":null}]', '[{\"job_title\":\"software engineer\",\"company\":\"gotech \",\"start_date\":\"2025-11-01\",\"end_date\":\"2025-11-01\",\"description\":\"Good \"}]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"okk\"}]', '[{\"skill_name\":\"C++\",\"proficiency\":\"Beginner\",\"years_experience\":\"1\",\"rating\":\"3\"}]', '[{\"language\":\"English\",\"proficiency\":80},{\"language\":\"arabic\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"okk \",\"objective\":\"jjjjj\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2024\",\"projects\":\"ai\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-19 20:08:00', '2025-11-19 20:08:00', 1),
(210, 26, 'Current', '6', '[{\"first_name\":\"JHON\",\"last_name\":\"DOE\",\"city\":\"NEWYORK\",\"state\":\"USA\",\"zip\":\"52000\",\"email\":\"jhondoe@gmail.com\",\"phone\":\"176373763373\",\"profile_image\":null}]', '[{\"job_title\":\"software engineer\",\"company\":\"gotech \",\"start_date\":\"2025-11-01\",\"end_date\":\"2025-11-01\",\"description\":\"Good \"}]', '[{\"degree\":\"Bsc\",\"school\":\"Standford university\",\"start_date\":\"2025-11-08\",\"end_date\":\"2025-11-23\",\"description\":\"okk\"}]', '[{\"skill_name\":\"C++\",\"proficiency\":\"Beginner\",\"years_experience\":\"1\",\"rating\":\"3\"}]', '[{\"language\":\"English\",\"proficiency\":80},{\"language\":\"arabic\",\"proficiency\":70}]', '[{\"headline\":\"Material Engineer \",\"about\":\"okk \",\"objective\":\"jjjjj\",\"years_experience\":\"4\",\"strengths\":\"Problem solving\",\"soft_skills\":\"Communication\",\"hobbies\":\"Badminton \",\"achievements\":\"2024\",\"projects\":\"ai\",\"linkedin\":\"https:\\/\\/linkedin.com\",\"portfolio\":\"https:\\/\\/github.com\"}]', '2025-11-19 20:08:18', '2025-11-19 20:08:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skill_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  `proficiency` varchar(50) DEFAULT NULL,
  `years_experience` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`skill_id`, `user_id`, `skill_name`, `proficiency`, `years_experience`, `rating`, `created_at`) VALUES
(1, 6, 'Python', 'Intermediate', 9, 4, '2025-11-07 17:51:29'),
(2, 6, 'hhh', 'Intermediate', 5, 4, '2025-11-07 19:19:23'),
(3, 6, 'python', 'Expert', 2, 4, '2025-11-07 20:14:24'),
(4, 6, 'python', 'Intermediate', 2, 3, '2025-11-07 20:19:00'),
(5, 6, 'python', 'Expert', 2, 4, '2025-11-07 20:24:50'),
(6, 6, 'python', 'Intermediate', 2, 4, '2025-11-07 20:25:14'),
(7, 6, 'java', 'Intermediate', 2, 5, '2025-11-07 20:25:49'),
(8, 6, 'HTML', 'Intermediate', 7, 5, '2025-11-07 20:26:56'),
(9, 6, 'python', 'Intermediate', 2, 4, '2025-11-07 20:27:10'),
(10, 6, 'python', 'Intermediate', 3, 5, '2025-11-07 20:30:26'),
(11, 6, 'hhh', 'Expert', 9, 5, '2025-11-07 20:30:48'),
(12, 2, 'python', 'Intermediate', 2, 5, '2025-11-09 17:13:56'),
(13, 2, 'leadership', 'Expert', 4, 5, '2025-11-09 17:14:26'),
(14, 6, 'python', 'Intermediate', 2, 4, '2025-11-09 17:36:07'),
(15, 8, 'python', 'Expert', 5, 5, '2025-11-09 18:46:53'),
(16, 8, 'python', 'Expert', 2, 5, '2025-11-09 19:12:26'),
(17, 8, 'python', 'Intermediate', 0, 3, '2025-11-09 19:24:49'),
(18, 8, 'python', 'Expert', 2, 5, '2025-11-10 18:47:41'),
(19, 8, 'C++', 'Intermediate', 2, 4, '2025-11-10 18:48:00'),
(22, 10, 'python', 'Intermediate', 1, 4, '2025-11-12 16:11:02'),
(25, 8, 'hhh', 'Beginner', 1, 4, '2025-11-13 06:25:21'),
(27, 12, 'python', 'Expert', 1, 3, '2025-11-13 07:16:17'),
(34, 8, 'C++', 'Expert', 4, 4, '2025-11-14 22:33:16'),
(35, 8, 'python', 'Beginner', 1, 3, '2025-11-14 22:33:27'),
(43, 8, 'Data Analyst ', 'Expert', 2, 3, '2025-11-16 16:58:05'),
(49, 26, 'C++', 'Intermediate', 2, 3, '2025-11-19 03:22:36'),
(51, 26, 'C++', 'Beginner', 1, 3, '2025-11-19 20:07:52'),
(52, 1, 'C++', 'Beginner', 1, 3, '2025-11-20 03:36:57');

-- --------------------------------------------------------

--
-- Table structure for table `summary`
--

CREATE TABLE `summary` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `headline` varchar(255) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `objective` text DEFAULT NULL,
  `years_experience` int(11) DEFAULT NULL,
  `strengths` text DEFAULT NULL,
  `soft_skills` text DEFAULT NULL,
  `achievements` text DEFAULT NULL,
  `projects` text DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `portfolio` varchar(255) DEFAULT NULL,
  `hobbies` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `summary`
--

INSERT INTO `summary` (`id`, `user_id`, `headline`, `about`, `objective`, `years_experience`, `strengths`, `soft_skills`, `achievements`, `projects`, `linkedin`, `portfolio`, `hobbies`, `created_at`) VALUES
(1, 6, 'Material Engineer ', 'i am good at managing', 'i will work good ', 2, 'Problem solving', 'Communication', 'Award in 2024', 'Develop AI chatbot ', 'https://linkedin.com', 'https://github.com', 'Badminton ', '2025-11-08 16:47:09'),
(2, 6, 'Material Engineer ', 'i am good at managing', 'i will work good ', 2, 'Problem solving', 'Communication', 'Award in 2024', 'Develop AI chatbot ', 'https://linkedin.com', 'https://github.com', 'Badminton ', '2025-11-08 16:47:13'),
(3, 2, 'Computer Science ', 'i am good at coding', 'i will make same AI chatbots', 2, 'Problem solving', 'Communication', 'Scholarships', 'Make a AI chatbot', 'https://linkedin.com', 'https://github.com', 'Badminton ', '2025-11-09 17:15:59'),
(4, 6, 'Computer Science ', 'Python', 'chatbot making ', 2, 'Problem solving', 'Communication', 'scholarships', 'chatbot ai tool ', 'https://linkedin.com', 'https://github.com', 'Badminton ', '2025-11-09 17:36:59'),
(5, 8, 'Material Engineer ', 'hhhhhhhhhhhhhhhhhhhhhhhhhhhjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn', 'kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk', 4, 'Problem solving', 'Communication', '2025', '2026', 'https://linkedin.com', 'https://github.com', 'Badminton ', '2025-11-09 18:47:29'),
(6, 8, 'Material Engineer ', 'hhhhhhhhhhhhhhhhhhhhhhhhhhhjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn', 'kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk', 4, 'Problem solving', 'Communication', '2025', '2026', 'https://linkedin.com', 'https://github.com', 'Badminton ', '2025-11-09 19:12:54'),
(7, 8, 'Material Engineer ', 'hhhhhhhhhhhhhhhhhhhhhhhhhhhjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn', 'kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk', 4, 'Problem solving', 'Communication', '2025', '2026', 'https://linkedin.com', 'https://github.com', 'Badminton ', '2025-11-09 19:25:20'),
(8, 1, 'kkk', 'ooo', 'llkh', 2, 'iiii', 'j', 'ouvddg', 'kkkkc', 'https://linkdin.com', 'https://github.com', 'l', '2025-11-10 18:53:50'),
(10, 10, 'Material Engineer ', 'Problem solver', 'I am that if i got job in this company i will take this compnay\'s profile to success.', 2, 'Problem solving', 'Communication', '2024', 'Sensor fusion ', 'https://linkedin.com', 'https://github.com', 'Badminton ', '2025-11-12 16:40:23'),
(11, 12, 'research', 'wel kkaahshvs', 'ghsddhhdd', 1, 'Problem', 'Communication', '2024', 'AI', 'https://linkedin.com', 'https://github.com', 'Badminton ', '2025-11-13 07:17:05'),
(14, 26, 'Material Engineer ', 'okk ', 'jjjjj', 4, 'Problem solving', 'Communication', '2024', 'ai', 'https://linkedin.com', 'https://github.com', 'Badminton ', '2025-11-19 03:23:14');

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id` int(11) NOT NULL,
  `template_name` varchar(255) NOT NULL,
  `template_description` text DEFAULT NULL,
  `template_version` varchar(20) DEFAULT NULL,
  `template_number` int(11) NOT NULL,
  `image_path` varchar(500) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `has_completed_resume` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `created_at`, `has_completed_resume`) VALUES
(1, 'zain', 'zain@1122.com', '$2y$10$L5vuB7oY/2eARbYIsTbn1erv15.O.CZeg9LY0KfLHlW73ayy8L1NO', '2025-10-30 17:40:02', 1),
(2, 'zain', 'alice@1122.com', '$2y$10$yryxFWPGXBjELZmanMP9BeGqkcIGOegpMFziXLr87bT0gDKmEuXuO', '2025-10-30 17:42:10', 0),
(3, 'zain', 'zara@1122.com', '$2y$10$rqHsc0jvXWl0I8i.hyZ8f.mWXEIlyDnqg3UEA5rSiFCjQ9t4LrVFy', '2025-10-30 17:50:10', 0),
(4, 'Bruce', 'habibiqra196@gmail.com', '$2y$10$XhIbm8rpbC06Uoohx3/v3.UkOQNRsESF0tLEPAtvLXcfcwtjKLgKm', '2025-11-04 06:20:40', 0),
(6, 'Musa', 'musa@1100', '$2y$10$FKTzNP/XSZMIQEV/1bh6hOesHi3/wA8JXuAKQljak61oL3mUNxiUG', '2025-11-07 17:18:45', 0),
(7, 'Bruce', 'bruse@0000', '$2y$10$PxYPJSWNxDfTfCWGIs.QMel98BD12tQeTwZ/RO3JwqTaStBvJbW0O', '2025-11-08 15:17:53', 0),
(8, 'Asim', 'asim@8899', '$2y$10$EtiqU.dLJTiTFCKEa3i0MeAafObyT8FlWBDn1lmqRPIUB7lSyCiWC', '2025-11-09 18:45:32', 1),
(10, 'Ahmad', 'ahmad@216', '$2y$10$xxAKJAI50EMLAvkQ/8J/JO55D/YNWk1Izw4RPMHx5uhdffEXbnaRK', '2025-11-12 16:07:11', 0),
(11, 'zain', 'zain01chaudhary@gmail.com', '$2y$10$XABaD9iGh9gZwoRv7U4nFeYO9oOv5p.HE920vFBVWaHdV5ssGQ502', '2025-11-12 18:59:16', 0),
(12, 'zain ', 'zain@2334.com', '$2y$10$yXtmb0nC2b4icUEMs97jMOIeTMapeC311OX85zFj27mT5eYt0yt1W', '2025-11-13 07:13:47', 0),
(14, 'zain', 'zain@3355', '$2y$10$mHJOLf6Cr5nVF3UHE42w8uIodlTWxHL4kt9NYmSk31Ne9Zu30Iz.W', '2025-11-14 19:21:52', 0),
(15, 'Alice', 'alice@0000', '$2y$10$zxEOvsMC8l7Rp.UTCAtVjez0PCOoPCyKp5maHaEbAxtux.vN3Blbe', '2025-11-14 19:56:58', 0),
(17, 'zain', 'zain@112233.com', '$2y$10$brWjFn0bjARaMtEjveKRQuF8uqa./dMjsf6EzgY.Rrbo2zoYHc4oe', '2025-11-15 22:16:54', 0),
(18, 'zain', 'zain@1100.com', '$2y$10$7vw6Y9ilhQFkAq6nJ4EZ7uXRMCKBUKsqCmISfnFZBlsBXtP4NuRzK', '2025-11-15 22:22:25', 0),
(19, 'zain', 'zain@11.com', '$2y$10$FTlNynxX9KOqIwn79kxEUONkl5OG/QmQzF5d.wyLeFjD6Mj0Z305C', '2025-11-15 22:26:22', 0),
(20, 'zain', 'zain@1133.com', '$2y$10$SOA.8QBdr2oDRVDyY88Xa.DRnTeL5pMc6H006OM2Ol8CyorHjG6z6', '2025-11-15 22:28:36', 0),
(26, 'zain', 'musa@889900', '$2y$10$RSvOPa9Z3TJA/RnUIu2queoEWOxTXeicUj/zmAjVw8m/qlomvZyM.', '2025-11-19 03:14:09', 1),
(28, 'Ali', 'ali@0000', '$2y$10$bc88mL3u6BnRyl82hm2Ui.dO4.iT3uAl/tDBsjl7n5axHTgbDySvm', '2025-11-20 02:40:28', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_template_choices`
--

CREATE TABLE `user_template_choices` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_template_choices`
--

INSERT INTO `user_template_choices` (`id`, `user_id`, `template_id`, `created_at`, `updated_at`) VALUES
(3, 8, 3, '2025-11-16 16:56:40', '2025-11-16 16:56:42'),
(6, 26, 2, '2025-11-19 03:21:27', '2025-11-20 03:24:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`edu_id`),
  ADD KEY `fk_education_user` (`user_id`);

--
-- Indexes for table `experience`
--
ALTER TABLE `experience`
  ADD PRIMARY KEY (`exp_id`),
  ADD KEY `fk_experience_user` (`user_id`);

--
-- Indexes for table `final_resume`
--
ALTER TABLE `final_resume`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `header`
--
ALTER TABLE `header`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_languages_user` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resume_versions`
--
ALTER TABLE `resume_versions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skill_id`),
  ADD KEY `fk_skills_user` (`user_id`);

--
-- Indexes for table `summary`
--
ALTER TABLE `summary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_summary_user` (`user_id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `template_number` (`template_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_template_choices`
--
ALTER TABLE `user_template_choices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
  MODIFY `edu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `experience`
--
ALTER TABLE `experience`
  MODIFY `exp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `final_resume`
--
ALTER TABLE `final_resume`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `header`
--
ALTER TABLE `header`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `resume_versions`
--
ALTER TABLE `resume_versions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `skill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `summary`
--
ALTER TABLE `summary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `user_template_choices`
--
ALTER TABLE `user_template_choices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `education`
--
ALTER TABLE `education`
  ADD CONSTRAINT `education_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_education_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `experience`
--
ALTER TABLE `experience`
  ADD CONSTRAINT `experience_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_experience_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `header`
--
ALTER TABLE `header`
  ADD CONSTRAINT `fk_header_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `languages`
--
ALTER TABLE `languages`
  ADD CONSTRAINT `fk_languages_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `resume_versions`
--
ALTER TABLE `resume_versions`
  ADD CONSTRAINT `resume_versions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `skills`
--
ALTER TABLE `skills`
  ADD CONSTRAINT `fk_skills_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `skills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `summary`
--
ALTER TABLE `summary`
  ADD CONSTRAINT `fk_summary_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_template_choices`
--
ALTER TABLE `user_template_choices`
  ADD CONSTRAINT `user_template_choices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
