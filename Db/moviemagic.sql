-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2024 at 10:59 AM
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
-- Database: `moviemagic`
--

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `id` int(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `subtitle` varchar(50) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `links` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`id`, `title`, `subtitle`, `description`, `links`) VALUES
(2, 'MovieMagic', 'Where Every Frame tells a Story', 'MovieMagic is a groundbreaking film-centric platform that transcends conventional storytelling boundaries. With a focus on every frame as a narrative element, MovieMagic empowers filmmakers to amplify their creative vision. Seamlessly integrating cutting-edge technology into the cinematic process, the platform boasts a meticulously crafted login page, an intuitive admin panel, and captivating home and about pages. By harnessing the power of individual frames, MovieMagic promises to redefine the art of filmmaking, providing a dynamic space for storytellers to unfold narratives with precision and impact. Get ready to embark on a journey where each frame tells a story, and MovieMagic leads the way in transforming the landscape of visual storytelling.', 'http://localhost/MovieMagic/admin/HomePage.php');

-- --------------------------------------------------------

--
-- Table structure for table `genre_info`
--

CREATE TABLE `genre_info` (
  `genreid` int(11) NOT NULL,
  `genre_name` varchar(50) DEFAULT NULL,
  `active` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genre_info`
--

INSERT INTO `genre_info` (`genreid`, `genre_name`, `active`) VALUES
(5, 'Action', 1),
(6, 'Adventure', 1),
(7, 'Romance', 1);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `message` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `name`, `email`, `message`) VALUES
(2, 'Apurva', 'apurva@gmail.com', 'hey! Just to let you know your websites is amazing'),
(3, 'Amrita', 'aameyy@gmail.com', 'hey there!!you are doing an amazing job');

-- --------------------------------------------------------

--
-- Table structure for table `moviedetails`
--

CREATE TABLE `moviedetails` (
  `id` int(11) NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` longtext NOT NULL,
  `genreid` int(11) NOT NULL,
  `release_year` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `type` varchar(30) NOT NULL,
  `poster_img` varchar(191) NOT NULL,
  `URL` varchar(191) NOT NULL,
  `quality` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moviedetails`
--

INSERT INTO `moviedetails` (`id`, `title`, `description`, `genreid`, `release_year`, `duration`, `type`, `poster_img`, `URL`, `quality`) VALUES
(9, 'Kung Fu Panda 4', 'After Po is tapped to become the Spiritual Leader of the Valley of Peace, he needs to find and train a new Dragon Warrior, while a wicked sorceress plans to re-summon all the master villains whom Po has vanquished to the spirit re...', 5, 2024, 94, 'Movie', 'kungfupanda4.jpg', '', 'CAM'),
(11, 'Dune ', '', 5, 2024, 94, 'Movie', 'Dune.jpeg', '', 'CAM');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `usertype` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`id`, `username`, `email`, `password`, `usertype`) VALUES
(2, '', '', '', ''),
(28, 'Shubekshya Lama', 'subekshya@gmail.com', '4567', 'user'),
(31, 'Rusha Manandhar', 'rusmdr@gmail.com', '1234', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(20) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` longtext DEFAULT NULL,
  `links` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `title`, `description`, `links`) VALUES
(2, 'High-Quality Streaming', 'Immerse yourself in the brilliance of entertainment with High-Quality Streaming. This cutting-edge service delivers a visual feast, offering content in vibrant high definition (HD) and, in some cases, 4K Ultra HD. Say goodbye to pixelation and lag – High-Quality Streaming ensures that every frame is a masterpiece, enhancing the details, colors, and clarity of your favorite movies and TV shows.  Whether you\'re on a big screen or a mobile device, this service guarantees an unparalleled viewing experience. Feel the intensity of every scene, savor the richness of colors, and enjoy crystal-clear audio that transports you into the heart of the narrative. High-Quality Streaming is not just about watching; it\'s about experiencing the magic of cinema in its truest form, right from the comfort of your own space. Elevate your entertainment standards with the pinnacle of visual excellence.', 'http://localhost/MovieMagic/admin/HomePage.php');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `genre_info`
--
ALTER TABLE `genre_info`
  ADD PRIMARY KEY (`genreid`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `moviedetails`
--
ALTER TABLE `moviedetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `genre_info`
--
ALTER TABLE `genre_info`
  MODIFY `genreid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `moviedetails`
--
ALTER TABLE `moviedetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
