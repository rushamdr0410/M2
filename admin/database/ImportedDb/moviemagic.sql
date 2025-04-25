-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 06:26 AM
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
-- Table structure for table `cast_info`
--

CREATE TABLE `cast_info` (
  `cast_id` int(11) NOT NULL,
  `cast_name` varchar(100) NOT NULL,
  `cast_image` varchar(255) DEFAULT NULL,
  `biography` text DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_place` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cast_info`
--

INSERT INTO `cast_info` (`cast_id`, `cast_name`, `cast_image`, `biography`, `birth_date`, `birth_place`, `created_at`, `updated_at`) VALUES
(1, 'Jennifer Aniston', '', 'Jennifer Joanna Aniston is an American actress. She rose to international fame for her role as Rachel Green on the television sitcom Friends from 1994 to 2004, which earned her Primetime Emmy, Golden Globe, and Screen Actors Guild awards.', '1969-02-11', ' Sherman Oaks, Los Angeles, California, United States', '2025-03-29 07:40:39', '2025-03-29 07:40:39'),
(2, 'Lisa Kudrow', NULL, 'Lisa Valerie Kudrow is an American actress. She rose to international fame for her role as Phoebe Buffay in the American television sitcom Friends, which aired from 1994 to 2004. The series earned her Primetime Emmy, Screen Actors Guild, Satellite, American Comedy and TV Guide awards.', '1963-07-30', 'Encino, Los Angeles, California, United States', '2025-03-29 07:49:09', '2025-03-29 07:49:09');

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
(7, 'Romance', 1),
(8, 'Sci-Fic', 1),
(9, 'Comedy', 1);

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
(3, 'Amrita', 'aameyy@gmail.com', 'hey there!!you are doing an amazing job'),
(5, 'Decor', 'rusmdr@gmail.com', 'hey');

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
  `duration` varchar(30) NOT NULL,
  `type` varchar(30) NOT NULL,
  `poster_img` varchar(191) NOT NULL,
  `quality` mediumtext NOT NULL,
  `video_url` varchar(191) DEFAULT NULL,
  `cast_id` int(11) DEFAULT NULL,
  `director` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moviedetails`
--

INSERT INTO `moviedetails` (`id`, `title`, `description`, `genreid`, `release_year`, `duration`, `type`, `poster_img`, `quality`, `video_url`, `cast_id`, `director`) VALUES
(9, 'Kung Fu Panda 4', 'After Po is tapped to become the Spiritual Leader of the Valley of Peace, he needs to find and train a new Dragon Warrior, while a wicked sorceress plans to re-summon all the master villains whom Po has vanquished to the spirit re...', 5, 2024, '94 min', 'Movie', 'kungfupanda4.jpg', 'CAM', 'upload/449736242_7565002540236147_123395736279267924_n.mp4', NULL, NULL),
(14, 'Halo', 'Halo is set in the 26th century and follows the conflict between humanity and a formidable alien alliance known as the Covenant. The story is centered around Master Chief Petty Officer John-117, a super-soldier of the Spartan-II program, and several other major characters from the video games series.', 5, 2024, '1h 25min', 'Movie', 'Halo.jpeg', 'CAM', NULL, NULL, NULL),
(18, 'F.R.I.E.N.D.S', 'The show focuses on the characters’ individual and collective search for sex, commitment, and meaning. The friends consist of three men and three women, each with unique personalities and shortcomings, which allow for both broad audience identification and abundant comedic moments.', 9, 1994, '22 MIN', 'TV-Show', 'friends.jpg', 'HD', 'upload/friends.mp4', 1, 'James Burrows'),
(19, 'The Kissing Booth', 'The Kissing Booth is an American teen romantic comedy film written and directed by Vince Marcello, based on the Wattpad novel of the same name by Beth Reekles. The movie follows a high school student who finds herself face-to-face with her long-term crush when she signs up to run a kissing booth at the spring carnival.', 7, 2018, '1h 45min', 'Movie', 'kissingbo.jpg', 'HD', 'upload/kissingbooth.mp4', NULL, NULL),
(21, 'Dune', 'Feature adaptation of Frank Herbert’s science fiction novel, about the son of a noble family entrusted with the protection of the most valuable asset and most vital element in the galaxy.::Anonymous', 6, 2021, '155min', 'Movie', '68039e7662909_Dune.jpeg', 'CAM', 'upload/68039e7662909_449736242_7565002540236147_123395736279267924_n.mp4', 2, 'Denis Villeneuve');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `usertype` varchar(20) NOT NULL,
  `reset_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`id`, `username`, `email`, `password`, `usertype`, `reset_token`) VALUES
(31, 'Rusha Manandhar', 'rusmdr@gmail.com', '$2a$12$6.3GTCmO2uGv1GWX/eBDI.QEcRpiBPK7FNXWmN1pXDbjQMiKlfabm', 'admin', 'abee1c1a89113215ac69fff74540f7bba7650b9680040cf449f9b36862da884f55879c0b4cdc01c38c5e301a878d0fb83527'),
(32, 'Jensa Sthapit', 'jensa@gmail.com', '$2y$10$UZ/8xGzFulh5LF9e4Br8TO7Nc7oGYFL9kt1a6w7B1Q2I2jtcXPvn6', 'user', NULL),
(38, 'Reshu Shrestha', 'reshushrestha21@gmail.com', '$2y$10$w/CmVTe3Bydy8kFpWsXlTeCCS9BiWyi1bDdK.GAko/mKpplVSTk4G', 'user', NULL),
(45, '__Shubekshya', 'shubekshya22@gmail.com', '$2y$10$0n/AcPKw23bclXPSbmL81OTkuQNmzjShU3exGIbBjICzqTXwELUQG', 'user', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `register_backup`
--

CREATE TABLE `register_backup` (
  `id` int(11) NOT NULL DEFAULT 0,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `usertype` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register_backup`
--

INSERT INTO `register_backup` (`id`, `username`, `email`, `password`, `usertype`) VALUES
(31, 'Rusha Manandhar', 'rusmdr@gmail.com', '1234', 'admin'),
(32, 'Jensa Sthapit', 'jensa@gmail.com', '3456', 'user'),
(34, 'Reshu Shrestha', 'rsu@gmail.com', '0987', 'user'),
(35, 'Radha Krishna', 'radhakrishna5@gmail.com', 'radhakrishna', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `review_text` text NOT NULL,
  `likes` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating` tinyint(4) NOT NULL COMMENT 'Rating between 1-5',
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `movie_id`, `user_id`, `review_text`, `likes`, `created_at`, `rating`, `review_date`) VALUES
(9, 9, 32, 'hey', 0, '2024-07-11 20:56:47', 3, '2025-03-29 16:08:19'),
(10, 9, 32, 'hey', 0, '2024-07-11 20:57:25', 3, '2025-03-29 16:08:19'),
(16, 19, 32, 'The Kissing Booth isn’t groundbreaking, but it’s a guilty pleasure that’s perfect for a cozy movie night. If you love cheesy, feel-good romances with charismatic leads, this one’s for you.', 0, '2025-03-29 16:24:08', 5, '2025-03-29 16:24:08');

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

-- --------------------------------------------------------

--
-- Table structure for table `watchlist`
--

CREATE TABLE `watchlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tmdb_id` varchar(255) NOT NULL,
  `media_type` enum('movie','tv') NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `watchlist`
--

INSERT INTO `watchlist` (`id`, `user_id`, `tmdb_id`, `media_type`, `date_added`) VALUES
(1, 45, '1197306', 'movie', '2025-04-24 02:15:14'),
(3, 45, '59941', 'tv', '2025-04-24 02:25:57'),
(5, 45, '1396', 'tv', '2025-04-24 02:41:28'),
(6, 45, '219246', 'tv', '2025-04-24 02:41:59'),
(7, 45, '939243', 'movie', '2025-04-24 03:15:28'),
(8, 45, '299536', 'movie', '2025-04-24 03:17:03'),
(9, 45, '931349', 'movie', '2025-04-24 03:32:16'),
(10, 45, '92685', 'tv', '2025-04-25 03:24:32');

-- --------------------------------------------------------

--
-- Table structure for table `watchlist_old`
--

CREATE TABLE `watchlist_old` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `watchlist_old`
--

INSERT INTO `watchlist_old` (`id`, `user_id`, `movie_id`, `added_at`) VALUES
(12, 32, 9, '2025-04-03 06:45:11'),
(14, 32, 18, '2025-04-03 06:47:03'),
(15, 32, 19, '2025-04-03 06:47:41');

-- --------------------------------------------------------

--
-- Table structure for table `watch_history`
--

CREATE TABLE `watch_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `media_type` varchar(10) NOT NULL,
  `watch_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `watch_history`
--

INSERT INTO `watch_history` (`id`, `user_id`, `movie_id`, `media_type`, `watch_date`) VALUES
(1, 45, 1241982, 'movie', '2025-04-25 09:33:12'),
(2, 45, 2261, 'tv', '2025-04-25 09:36:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cast_info`
--
ALTER TABLE `cast_info`
  ADD PRIMARY KEY (`cast_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `cast_id` (`cast_id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_id` (`movie_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `watchlist`
--
ALTER TABLE `watchlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_movie` (`user_id`,`tmdb_id`);

--
-- Indexes for table `watchlist_old`
--
ALTER TABLE `watchlist_old`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_movie_unique` (`user_id`,`movie_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `watch_history`
--
ALTER TABLE `watch_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`movie_id`),
  ADD KEY `watch_date` (`watch_date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cast_info`
--
ALTER TABLE `cast_info`
  MODIFY `cast_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `genre_info`
--
ALTER TABLE `genre_info`
  MODIFY `genreid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `moviedetails`
--
ALTER TABLE `moviedetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `watchlist`
--
ALTER TABLE `watchlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `watchlist_old`
--
ALTER TABLE `watchlist_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `watch_history`
--
ALTER TABLE `watch_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `moviedetails`
--
ALTER TABLE `moviedetails`
  ADD CONSTRAINT `moviedetails_ibfk_1` FOREIGN KEY (`cast_id`) REFERENCES `cast_info` (`cast_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `moviedetails` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `register` (`id`);

--
-- Constraints for table `watchlist`
--
ALTER TABLE `watchlist`
  ADD CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `register` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `watchlist_old`
--
ALTER TABLE `watchlist_old`
  ADD CONSTRAINT `watchlist_old_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `register` (`id`),
  ADD CONSTRAINT `watchlist_old_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `moviedetails` (`id`);

--
-- Constraints for table `watch_history`
--
ALTER TABLE `watch_history`
  ADD CONSTRAINT `watch_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `register` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
