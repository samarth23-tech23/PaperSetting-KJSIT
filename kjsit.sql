-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Nov 02, 2023 at 02:48 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kjsit`
--

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`attribute_id`, `question_id`, `co_number`, `bt_level`) VALUES
(1, 2, 'CO1', 'BT1'),
(2, 4, 'CO2', 'BT2'),
(14, 1, NULL, NULL);

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`) VALUES
(1, 'Information Technology'),
(2, 'BS&H');

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `subject_id`, `question_text`, `image_path`, `marks`) VALUES
(1, 1, NULL, NULL, NULL),
(2, 1, NULL, NULL, NULL);

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`, `department_id`, `co1`, `co2`, `co3`, `co4`, `co5`, `co6`) VALUES
(1, 'DBMS', 1, 'understand', 'Observe', 'explain', 'solve', 'both', 'both'),
(2, 'Engg.Drawing', 2, 'Learn', 'Draw', NULL, NULL, NULL, NULL);

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`Teacher_id`, `Teacher_name`, `department_id`) VALUES
(1, 'Hariram Chavan', 1);

--
-- Dumping data for table `teacher_subjects`
--

INSERT INTO `teacher_subjects` (`teacher_subject_id`, `teacher_id`, `subject_id`) VALUES
(1, 1, 1);

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `teacher_id`) VALUES
(1, 'hariram@somaiya.edu', 'abcd', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
