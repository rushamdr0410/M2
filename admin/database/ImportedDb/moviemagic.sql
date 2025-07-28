-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 28, 2025 at 05:41 AM
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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(11) NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`, `created_at`) VALUES
(1, '2024_04_25_000001_create_register_table', 1745598578, '2025-04-25 16:29:38'),
(2, '2024_04_25_000002_create_watch_history_table', 1745598578, '2025-04-25 16:29:38'),
(3, '2024_04_25_000007_create_reviews_table', 1745598578, '2025-04-25 16:29:38'),
(4, '2024_04_25_000008_create_watchlist_table', 1745598578, '2025-04-25 16:29:38');

-- --------------------------------------------------------

--
-- Table structure for table `moviedetails`
--

CREATE TABLE `moviedetails` (
  `id` int(11) NOT NULL,
  `title` varchar(191) NOT NULL,
  `cast_id` int(11) DEFAULT NULL,
  `release_date` varchar(20) DEFAULT NULL,
  `poster_path` varchar(255) DEFAULT NULL,
  `genreid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moviedetails`
--

INSERT INTO `moviedetails` (`id`, `title`, `cast_id`, `release_date`, `poster_path`, `genreid`) VALUES
(1668, 'Friends', NULL, '1994-09-22', 'https://image.tmdb.org/t/p/w500/2koX1xLkpTQM4IZebYvKysFW1Nh.jpg', NULL),
(37135, 'Tarzan', NULL, '1999-06-18', 'https://image.tmdb.org/t/p/w500/bTvHlcqiOjGa3lFtbrTLTM3zasY.jpg', NULL),
(122108, 'Let\'s BTS', NULL, '2021-03-29', 'https://image.tmdb.org/t/p/w500/pM3RHTbYfSvUwQ9t0B61oj9bkkU.jpg', NULL),
(219246, 'When Life Gives You Tangerines', NULL, '2025-03-07', 'https://image.tmdb.org/t/p/w500/Afrz3QAcQcsT6w4S5wrKUxmYPWM.jpg', NULL),
(261980, 'Our Unwritten Seoul', NULL, '2025-05-24', 'https://image.tmdb.org/t/p/w500/85UA5fmBs3DgcvF6IfX7IzLz9z5.jpg', NULL),
(262828, 'Crushology 101', NULL, '2025-04-11', 'https://image.tmdb.org/t/p/w500/9K4rTjVM9YLsYLeQxgKuea8qonE.jpg', NULL),
(447273, 'Snow White', NULL, '2025-03-12', 'https://image.tmdb.org/t/p/w500/oLxWocqheC8XbXbxqJ3x422j9PW.jpg', NULL),
(574475, 'Final Destination Bloodlines', NULL, '2025-05-09', 'https://image.tmdb.org/t/p/w500/6WxhEvFsauuACfv8HyoVX6mZKFj.jpg', NULL),
(931349, 'Ash', NULL, '2025-03-20', 'https://image.tmdb.org/t/p/w500/5Oz39iyRuztiA8XqCNVDBuy2Ut3.jpg', NULL),
(950387, 'A Minecraft Movie', NULL, '2025-03-31', 'https://image.tmdb.org/t/p/w500/iPPTGh2OXuIv6d7cwuoPkw8govp.jpg', NULL),
(1022789, 'Inside Out 2', NULL, '2024-06-11', 'https://image.tmdb.org/t/p/w500/vpnVM9B6NMmQpWeZvzLvDESb2QY.jpg', NULL),
(1197306, 'A Working Man', NULL, '2025-03-26', 'https://image.tmdb.org/t/p/w500/6FRFIogh3zFnVWn7Z6zcYnIbRcX.jpg', NULL),
(1233069, 'Exterritorial', NULL, '2025-04-29', 'https://image.tmdb.org/t/p/w500/jM2uqCZNKbiyStyzXOERpMqAbdx.jpg', NULL),
(1241982, 'Moana 2', NULL, '2024-11-21', 'https://image.tmdb.org/t/p/w500/aLVkiINlIeCkcZIzb7XHzPYgO6L.jpg', NULL),
(1356236, 'Saint Catherine', NULL, '2024-09-24', 'https://image.tmdb.org/t/p/w500/hBJdzKPeDaC96AzlrtMWBomYSZV.jpg', NULL);

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
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `last_location_update` datetime DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`id`, `username`, `email`, `password`, `usertype`, `latitude`, `longitude`, `last_location_update`, `location`) VALUES
(31, 'Rusha Manandhar', 'rusmdr@gmail.com', '$2a$12$6.3GTCmO2uGv1GWX/eBDI.QEcRpiBPK7FNXWmN1pXDbjQMiKlfabm', 'admin', 39.03000000, -77.50000000, '2025-04-26 12:16:13', NULL),
(32, 'jensa_sthapit', 'jensa@gmail.com', '$2y$10$IKu3FUQKJfmYT6d2LmHWJ.vnnANFh83oORJjXKGO2U9KUNgvpm212', 'user', 27.70525677, 85.30161056, '2025-04-26 10:05:06', 'Bishnumati Track Road, Kalimati, Kathmandu-13, Kathmandu Metropolitan City, Kathmandu, Bagamati Province, 44000, Nepal'),
(45, '__Shubekshya', 'shubekshya22@gmail.com', '$2y$10$4Is.jgk4//eMxNCwEiWxbOq3eVAnizopna9u4Z7T8qE8Z5D9nGr96', 'user', 27.70521900, 85.30162000, '2025-07-28 08:40:39', 'Bishnumati Track Road, Kalimati, Kathmandu-13, Kathmandu Metropolitan City, Kathmandu, Bagamati Province, 44000, Nepal'),
(46, 'rawrmdr', 'r.heahost@gmail.com', '$2y$10$hCGvhj4r0it.M9ibxVyGuOmrWJ8M2TCzMU4qngEoOU.HRqLzCHYG2', 'user', 27.70525527, 85.30101936, '2025-06-01 14:21:59', 'School of Sponge Learners, Bishnumati Track Road, Kalimati, Kathmandu-13, Kathmandu Metropolitan City, Kathmandu, Bagamati Province, 44000, Nepal'),
(47, 'sicheyyy', 'minni.io@gmail.com', '$2y$10$F6Tu0esm7YDIgDPp7eQKLucFx1Smxlohh8La/.OEGaMq0JFtW8dgK', 'user', 27.70525931, 85.30101993, '2025-06-01 13:57:43', 'School of Sponge Learners, Bishnumati Track Road, Kalimati, Kathmandu-13, Kathmandu Metropolitan City, Kathmandu, Bagamati Province, 44000, Nepal'),
(48, '_swyam', 'swyam.mdr@gmail.com', '$2y$10$NtWUnhYDr9Y0GYkc3gthceORaSH.I0g/nhQ0TT1zalxWOJks0EtTe', 'user', 39.03000000, -77.50000000, '2025-04-26 11:08:51', NULL),
(49, '_rsuxtha', 'reshushrestha21@gmail.com', '$2y$10$s0gj4zazKqDl2/QetU5gUuHr4NDhmZDJBShZu53EO5H6f7I40WdYa', 'user', 27.70522972, 85.30161639, '2025-05-22 11:54:03', 'Bishnumati Track Road, Kalimati, Kathmandu-13, Kathmandu Metropolitan City, Kathmandu, Bagamati Province, 44000, Nepal'),
(50, 'moulik.maharjan', 'moulikmaharjan@gmail.com', '$2y$10$gFKzWuoq8d4b6QFi1ULZ7.m49sarcUIjX3/vKscq6tkigHWooMQ7a', 'user', 27.70484016, 85.30149347, '2025-05-18 09:47:44', 'Bishnumati Track Road, Kalimati, Kathmandu-13, Kathmandu Metropolitan City, Kathmandu, Bagamati Province, 44000, Nepal'),
(51, 'xree_giri', 'shreejal2110@gmail.com', '$2y$10$AqD1/Dy13jkojqZ5pLYJW.xLKn6M7nTgcE2VAheiNusLcAD/ABqcO', 'user', 27.70524647, 85.30162434, '2025-05-18 10:07:18', 'Bishnumati Track Road, Kalimati, Kathmandu-13, Kathmandu Metropolitan City, Kathmandu, Bagamati Province, 44000, Nepal'),
(52, 'sujay_mdr', 'sujay10@gmail.com', '$2y$10$E04r/JEMfE9LYhFFngvVAODtpBJhrsE7oW/JZanJ62nj.UdbLK1ba', 'user', 27.70519655, 85.30160273, '2025-05-18 10:11:56', 'Bishnumati Track Road, Kalimati, Kathmandu-13, Kathmandu Metropolitan City, Kathmandu, Bagamati Province, 44000, Nepal'),
(53, 'shreeja_mhrjn', 'shreeja.21@gmail.com', '$2y$10$toRlM/XT8mmbbmPFMvTxUeIf9AsW0kIKEl8wCffSaPExVh97WCaNy', 'user', 27.70522166, 85.30162000, '2025-05-19 17:10:13', 'Bishnumati Track Road, Kalimati, Kathmandu-13, Kathmandu Metropolitan City, Kathmandu, Bagamati Province, 44000, Nepal'),
(54, 'user', 'user123@gmail.com', '$2y$10$faeT8km0vMHJd40QlWO2Nea8jm2AmcNaMTWrXTzB2Gx223JyQrk76', 'user', 27.70484016, 85.30149347, '2025-05-19 17:25:00', 'Bishnumati Track Road, Kalimati, Kathmandu-13, Kathmandu Metropolitan City, Kathmandu, Bagamati Province, 44000, Nepal');

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
  `movie_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `review_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating` tinyint(4) NOT NULL COMMENT 'Rating between 1-5',
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `movie_id`, `user_id`, `review_text`, `created_at`, `rating`, `review_date`) VALUES
(19, 219246, 45, 'A charming and heartfelt story about finding hope in unexpected places. When Life Gives You Tangerines mixes humor and honesty to show that even life’s smaller moments can be full of meaning. Quick to read but hard to forget.', '2025-04-27 09:25:57', 5, '2025-04-27 09:25:57'),
(20, 122108, 45, 'Let\'s BTS offers an intimate and inspiring look at the global phenomenon, showing the group\'s genuine bond, humility, and dreams. It\'s a heartfelt celebration of music, friendship, and the power of staying true to yourself.', '2025-04-27 09:30:00', 4, '2025-04-27 09:30:00'),
(21, 931349, 49, 'Ash was a raw and emotional read. It made me reflect on loss, healing, and the quiet strength it takes to start over. A simple yet powerful story.', '2025-04-27 10:35:34', 3, '2025-04-27 10:35:34'),
(22, 950387, 45, 'hey', '2025-04-29 06:05:48', 4, '2025-04-29 06:05:48'),
(23, 1233069, 45, 'Exterritorial challenges traditional ideas of space and identity with striking visuals and deep concepts. It\'s abstract at times, but rewarding for those who enjoy boundary-pushing, intellectual work.', '2025-05-08 04:55:15', 4, '2025-05-08 04:55:15'),
(24, 37135, 45, 'Tarzan is a visually stunning and emotionally rich film that blends action, humor, and heartfelt storytelling. The animation is top-notch, the jungle setting is vibrant, and Phil Collins\' soundtrack elevates every scene. A true Disney classic that holds up beautifully.', '2025-05-08 05:30:52', 5, '2025-05-08 05:30:52'),
(25, 1356236, 47, 'Saint Catherine is a powerful symbol of courage, intellect, and faith. Her legacy as a defender of truth and her unwavering stand against injustice continue to inspire people across generations. A true role model for resilience and conviction.', '2025-05-08 05:43:43', 5, '2025-05-08 05:43:43'),
(26, 574475, 45, 'Pretty solid addition to the franchise! The opening disaster was intense and the deaths were super creative, as expected. The bloodline twist added some depth, but parts felt a bit slow and predictable. Still, a fun watch if you\'re into the series!', '2025-05-16 16:46:52', 3, '2025-05-16 16:46:52'),
(27, 447273, 49, 'Just watched the new Snow White, and honestly, it didn’t live up to the hype. The visuals were nice, and Rachel Zegler did her best with a modern twist on the character, but the movie felt flat. The CGI dwarfs were weird, and the story changes felt forced. Gal Gadot looked stunning as the Evil Queen but didn’t leave a strong impact. It tried to be empowering but ended up losing the charm of the original.', '2025-05-17 15:13:45', 2, '2025-05-17 15:13:45'),
(28, 1668, 49, 'An all-time classic! The humor, the friendships, and the iconic one-liners never get old. Each character brings something unique, and the chemistry is perfect. It’s the ultimate feel-good show — easy to binge, hard to forget.', '2025-05-17 15:15:31', 5, '2025-05-17 15:15:31'),
(29, 1668, 45, 'Friends is a timeless sitcom filled with humor, heart, and unforgettable characters. The chemistry between the cast makes every episode a joy to watch. A perfect blend of comedy and emotion that still resonates today.', '2025-05-17 15:44:43', 5, '2025-05-17 15:44:43'),
(30, 1356236, 45, 'Saint Catherine of Alexandria is admired for her wisdom, courage, and strong faith. Known for outsmarting pagan philosophers and staying true to her beliefs even under persecution, she remains a symbol of strength and inspiration. Her legacy lives on through art, history, and the famous monastery named after her.', '2025-05-17 15:48:17', 5, '2025-05-17 15:48:17'),
(31, 219246, 49, 'A refreshing twist on the classic \"lemons to lemonade\" theme, When Life Gives You Tangerines is a charming, heartfelt story about finding sweetness in unexpected places. With witty narration and tender moments, it encourages resilience, joy, and embracing life’s quirks. Perfect for readers who enjoy lighthearted yet meaningful reads.', '2025-05-17 15:52:24', 4, '2025-05-17 15:52:24'),
(32, 1241982, 49, 'Moana 2 offers a visually captivating adventure with familiar characters and new additions. While it may not capture the full magic of the original, it provides an enjoyable experience for fans and families alike.', '2025-05-17 15:55:53', 3, '2025-05-17 15:55:53'),
(33, 1233069, 49, 'Exterritorial offers a gripping, if somewhat familiar, action-thriller experience. It\'s a solid choice for fans of the genre seeking a strong female protagonist and intense action sequences', '2025-05-17 16:21:51', 3, '2025-05-17 16:21:51'),
(34, 447273, 45, 'Snow White offers a visually engaging and musically rich experience, anchored by Rachel Zegler\'s compelling performance. However, its attempts at modernization and certain stylistic choices have led to a polarized reception.', '2025-05-18 00:58:34', 3, '2025-05-18 00:58:34'),
(35, 1356236, 49, 'Saint Catherine offers a chilling exploration of supernatural horror through its anthology format. While it may not revolutionize the genre, its cohesive narrative and thematic depth provide a haunting cinematic experience for horror enthusiasts.', '2025-05-18 03:43:36', 3, '2025-05-18 03:43:36'),
(36, 574475, 49, 'Final Destination: Bloodlines successfully breathes new life into the franchise, offering a mix of inventive horror, character depth, and emotional resonance. It\'s a must-watch for fans and a promising entry point for newcomers', '2025-05-18 03:53:49', 4, '2025-05-18 03:53:49'),
(37, 574475, 50, 'Final Destination: Bloodlines successfully breathes new life into the franchise, offering a mix of inventive horror, character depth, and emotional resonance. It\'s a must-watch for fans and a promising entry point for newcomers.', '2025-05-18 04:04:15', 4, '2025-05-18 04:04:15'),
(38, 1022789, 51, 'Inside Out 2 is a worthy successor to the original, offering a compelling narrative that resonates with both children and adults. Its insightful portrayal of emotional complexity, combined with stunning animation and strong performances, makes it a standout film in Pixar\'s repertoire.', '2025-05-18 04:15:02', 4, '2025-05-18 04:15:02'),
(39, 1197306, 52, 'A Working Man offers the action and intensity fans expect from Jason Statham, but it doesn\'t break new ground in the genre. For viewers seeking a straightforward action film, it delivers; however, those looking for innovation may find it lacking.', '2025-05-18 04:28:37', 2, '2025-05-18 04:28:37'),
(40, 574475, 54, 'a fun, gory, and entertaining addition to the franchise, with a few notable points of discussion', '2025-05-19 11:42:26', 5, '2025-05-19 11:42:26'),
(41, 261980, 47, 'This drama is a must-watch—it powerfully highlights how quickly we tend to judge others based on appearances, even when we haven\'t fully understood ourselves. It shows how easily we assume we know someone better than they know themselves. It\'s a brilliant 10/10, and I highly recommend everyone to give it a watch.\r\n', '2025-06-01 08:19:57', 5, '2025-06-01 08:19:57'),
(42, 219246, 47, 'This heartfelt family drama beautifully portrays how parents go to great lengths to ensure our happiness. It shows the journey of immature teenagers who grow into responsible parents, facing countless challenges but still putting their children first. The emotions are so raw and real—I was in tears throughout all 16 episodes. It\'s incredibly moving and a definite must-watch.\r\n', '2025-06-01 08:25:13', 4, '2025-06-01 08:25:13'),
(43, 262828, 47, 'It\'s great but a little too boring so watched it in 1.5x the story lines are something like an old school drama type ', '2025-06-01 08:28:01', 3, '2025-06-01 08:28:01'),
(44, 574475, 47, 'This movie is definitely a must-watch! It\'s not overly scary, but it has a touch of humor that adds to the fun. Some might jokingly call you a psycho if your thoughts about it are anything like mine, haha. The ending is especially satisfying! Overall, it\'s a great film, and if you can, watch it in 4D for an even better experience.\r\n', '2025-06-01 08:33:06', 5, '2025-06-01 08:33:06');

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
(7, 45, '939243', 'movie', '2025-04-24 03:15:28'),
(8, 45, '299536', 'movie', '2025-04-24 03:17:03'),
(9, 45, '931349', 'movie', '2025-04-24 03:32:16'),
(10, 45, '92685', 'tv', '2025-04-25 03:24:32'),
(11, 46, '1244944', 'movie', '2025-04-28 12:48:11'),
(14, 45, '950387', 'movie', '2025-04-29 05:38:50'),
(17, 45, '1092899', 'movie', '2025-04-29 05:52:32'),
(18, 45, '668489', 'movie', '2025-04-29 06:01:28'),
(22, 45, '574475', 'movie', '2025-05-16 16:38:33'),
(23, 49, '372058', 'movie', '2025-05-17 13:38:22'),
(26, 49, '858', 'movie', '2025-05-17 13:47:07'),
(29, 49, '728754', 'movie', '2025-05-17 13:56:52'),
(30, 49, '65733', 'tv', '2025-05-17 15:01:09'),
(31, 49, '1668', 'tv', '2025-05-17 15:03:37'),
(33, 50, '762509', 'movie', '2025-05-18 04:05:34'),
(34, 51, '1022789', 'movie', '2025-05-18 04:12:47'),
(35, 52, '1197306', 'movie', '2025-05-18 04:27:57'),
(36, 54, '1359977', 'movie', '2025-05-19 11:42:49'),
(37, 45, '261980', 'tv', '2025-06-01 08:10:14');

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
(2, 45, 2261, 'tv', '2025-04-25 09:36:33'),
(7, 49, 931349, 'movie', '2025-04-27 16:20:36'),
(28, 49, 950387, 'movie', '2025-05-17 18:28:56'),
(29, 49, 447273, 'movie', '2025-05-17 20:58:47'),
(30, 49, 1668, 'tv', '2025-05-17 21:00:33'),
(33, 49, 219246, 'tv', '2025-05-17 21:37:33'),
(34, 49, 1241982, 'movie', '2025-05-17 21:40:55'),
(35, 49, 1233069, 'movie', '2025-05-17 22:06:53'),
(37, 49, 1356236, 'movie', '2025-05-18 09:28:38'),
(38, 49, 574475, 'movie', '2025-05-18 09:38:51'),
(39, 50, 574475, 'movie', '2025-05-18 09:49:18'),
(40, 51, 1022789, 'movie', '2025-05-18 10:00:04'),
(41, 52, 1197306, 'movie', '2025-05-18 10:13:40'),
(48, 53, 574475, 'movie', '2025-05-19 17:11:43'),
(49, 54, 574475, 'movie', '2025-05-19 17:27:35'),
(50, 47, 261980, 'tv', '2025-06-01 14:04:59'),
(51, 47, 219246, 'tv', '2025-06-01 14:10:15'),
(52, 47, 262828, 'tv', '2025-06-01 14:13:03'),
(53, 47, 574475, 'movie', '2025-06-01 14:18:08'),
(59, 45, 1311031, 'movie', '2025-07-27 22:32:26'),
(60, 45, 93405, 'tv', '2025-07-27 22:33:17');

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
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
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_reviews_movie` (`movie_id`);

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
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `moviedetails`
--
ALTER TABLE `moviedetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1356237;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `watch_history`
--
ALTER TABLE `watch_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

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
  ADD CONSTRAINT `fk_reviews_movie` FOREIGN KEY (`movie_id`) REFERENCES `moviedetails` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `register` (`id`);

--
-- Constraints for table `watch_history`
--
ALTER TABLE `watch_history`
  ADD CONSTRAINT `watch_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `register` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
