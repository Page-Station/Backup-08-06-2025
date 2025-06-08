-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 25, 2025 at 11:06 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `page-station-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `stock` int DEFAULT '0',
  `category` varchar(100) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `jenjang` varchar(255) DEFAULT NULL,
  `kelas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `cover_image`, `stock`, `category`, `author`, `description`, `pdf_path`, `jenjang`, `kelas`) VALUES
(1, 'adsadasd', 'uploads/covers/Lmk_brt  [0321].jpg', 123, 'Pelajaran', 'rafi', 'adadawdwefqwa', 'uploads/pdfs/sunan ampel.pdf', 'SMP', '7'),
(2, 'Macskdsakd', 'uploads/covers/Atomic Habits (1).jpg', 11, 'Self Development', 'addsdasda', 'sadasdadad', 'uploads/pdfs/sunan ampel.pdf', '', '0'),
(3, 'TEST ', 'uploads/covers/Atomic Habits.jpg', 12, 'Self Development', 'SAYA', 'ADADASDSADABAJINGAN SDADASDASDA ', 'uploads/pdfs/sunan ampel.pdf', '', '0');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `email`, `password`) VALUES
('amirul', '123@gmail.com', '$2y$10$lXBOHoM.eumiJGZ./m8hLOlyGscKjVgivvr5zcE2Oa.oGC0bqpWDK'),
('Dido', 'Dido@gmail.com', '$2y$10$kPTPvl61iWiDTESt3uQhJeBRkuooDG5/9W3W.hs6ehYBxLKcSVm/2'),
('fatkhur', 'fatkhur@gmail.com', '$2y$10$FNLF9fOI2PpdFvAIp.WKY.KzVk5mX5Q3hz7miCJnpOtrdxrbpXmvC'),
('gusagus', 'gusagus@gmail.com', '$2y$10$1RHWgNToNdVuwrZe.wuxRevrbM/fhw/ZyvbKOSHMXYVzJFARbWfve'),
('surya', 'suryadi@gmail.com', '$2y$10$vfzTbhXciFFvl37GRX7un.kSFNNXwnB0wqglPsmrFKXj0DfN4m8He');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
