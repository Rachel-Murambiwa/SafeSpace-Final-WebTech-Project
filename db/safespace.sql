-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2025 at 11:32 PM
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
-- Database: `safespace`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments_safespace`
--
CREATE TABLE `users_safespace` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `roles` enum('general_user','admin') NOT NULL DEFAULT 'general_user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `profiles_safespace` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `anonymous_username` varchar(100) NOT NULL,
  `avatar_color` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `public_posts_safespace` (
  `post_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_flagged` tinyint(1) DEFAULT 0,
  `is_deleted` tinyint(1) DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `comments_safespace` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_flagged` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments_safespace`
--

INSERT INTO `comments_safespace` (`comment_id`, `post_id`, `profile_id`, `content`, `created_at`, `is_flagged`) VALUES
(1, 1, 2, 'Keep moving 🥰', '2025-12-10 23:32:51', 0),
(3, 5, 1, 'Nice picture there Spy163', '2025-12-11 11:14:20', 0),
(4, 2, 1, 'You will get there eventually', '2025-12-11 11:14:50', 0);

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries_safespace`
--

CREATE TABLE `journal_entries_safespace` (
  `journal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `content` text NOT NULL,
  `mood` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entries_safespace`
--

INSERT INTO `journal_entries_safespace` (`journal_id`, `user_id`, `title`, `content`, `mood`, `created_at`) VALUES
(1, 2, 'Exam Season', 'Yoh', 'Anxious', '2025-12-10 22:55:26'),
(2, 3, 'Thought', 'Ma1', 'Enthusiastic', '2025-12-11 00:51:16'),
(3, 3, 'Hmm', 'Shiit', 'Speechless', '2025-12-11 00:51:43');

-- --------------------------------------------------------

--
-- Table structure for table `mood_log`
--

CREATE TABLE `mood_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mood_value` int(11) NOT NULL,
  `mood_label` varchar(50) NOT NULL,
  `entry_date` date DEFAULT curdate(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mood_log`
--

INSERT INTO `mood_log` (`id`, `user_id`, `mood_value`, `mood_label`, `entry_date`, `created_at`) VALUES
(1, 1, 2, 'Anxious', '2025-12-11', '2025-12-11 12:23:58'),
(2, 3, 4, 'Happy', '2025-12-11', '2025-12-11 12:46:39'),
(3, 4, 3, 'Calm', '2025-12-11', '2025-12-11 19:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `post_images_safespace`
--

CREATE TABLE `post_images_safespace` (
  `image_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_images_safespace`
--

INSERT INTO `post_images_safespace` (`image_id`, `post_id`, `image_path`) VALUES
(4, 5, '1765414205_0_beach2.jpg'),
(5, 6, '1765451131_0_crochet3.jpeg'),
(6, 6, '1765451131_1_crochet1.jpeg'),
(7, 6, '1765451131_2_dwayne-joe-iP63zxeXKHk-unsplash (1).jpg');

-- --------------------------------------------------------

--
-- Table structure for table `profiles_safespace`
--


--
-- Dumping data for table `profiles_safespace`
--

INSERT INTO `profiles_safespace` (`profile_id`, `user_id`, `anonymous_username`, `avatar_color`, `created_at`) VALUES
(1, 1, 'phoenix', '#0eef1dff', '2025-12-10 21:37:08'),
(2, 2, 'wonder', '#4117e7ff', '2025-12-10 21:50:19'),
(3, 3, 'Spy163', '#4117e7ff', '2025-12-11 00:49:14'),
(4, 4, 'riser', '#FFD700', '2025-12-11 18:47:11');

-- --------------------------------------------------------

--
-- Table structure for table `public_posts_safespace`
--


--
-- Dumping data for table `public_posts_safespace`
--

INSERT INTO `public_posts_safespace` (`post_id`, `profile_id`, `title`, `content`, `created_at`, `updated_at`, `is_flagged`, `is_deleted`, `image_url`) VALUES
(1, 1, 'Community Post', 'Today I learnt to make crochet scrunchies as part of my growth journey. \r\n\r\n#growth #bounceback #resilience', '2025-12-10 21:48:29', '2025-12-10 21:48:29', 0, 0, '1765403309_Crochet Scrunchies.jpg'),
(2, 2, 'Community Post', 'Took a walk at the beach, to just reflect and connect with nature.\r\n\r\n#healing', '2025-12-10 21:54:44', '2025-12-10 21:54:44', 0, 0, '1765403684_beach2.jpg'),
(5, 3, 'Community Post', '.', '2025-12-11 00:50:05', '2025-12-11 00:50:05', 0, 0, NULL),
(6, 1, 'Community Post', 'Crocheting is not just a hobby for me, it is a lifestyle🧶😍', '2025-12-11 11:05:31', '2025-12-11 11:05:31', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quotes_safespace`
--

CREATE TABLE `quotes_safespace` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(100) DEFAULT 'Unknown'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotes_safespace`
--

INSERT INTO `quotes_safespace` (`id`, `content`, `author`) VALUES
(1, 'Healing takes time, and asking for help is a courageous step.', 'Mariska Hargitay'),
(2, 'You are not your mistakes. You are not your struggles. You are here to grow.', 'Unknown'),
(3, 'Rock bottom became the solid foundation on which I rebuilt my life.', 'J.K. Rowling'),
(4, 'Your present circumstances don’t determine where you can go; they merely determine where you start.', 'Nido Qubein'),
(5, 'Out of difficulties grow miracles.', 'Jean de La Bruyère'),
(6, 'Breathe. It’s just a bad day, not a bad life.', 'Unknown'),
(7, 'You are enough just as you are.', 'Meghan Markle'),
(8, 'There is hope, even when your brain tells you there isn’t.', 'John Green'),
(9, 'The wound is the place where the light enters you.', 'Rumi'),
(10, 'Healing takes courage, and we all have courage, even if we have to dig a little to find it', 'Tori Amos'),
(11, 'It’s not the load that breaks you down, it’s the way you carry it.', 'Lou Holtz'),
(12, 'Healing is not an overnight process. It is a daily cleansing of pain, a daily healing of your life.', 'Leigh Hershkovich'),
(13, 'The practice of forgiveness is our most important contribution to the healing of the world.', 'Marianne Williamson'),
(14, 'You don’t have to control your thoughts. You just have to stop letting them control you.', 'Dan Millman');

-- --------------------------------------------------------

--
-- Table structure for table `reactions_safespace`
--

CREATE TABLE `reactions_safespace` (
  `reaction_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `reaction_type_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reactions_safespace`
--

INSERT INTO `reactions_safespace` (`reaction_id`, `post_id`, `profile_id`, `reaction_type_id`, `created_at`) VALUES
(1, 2, 3, 1, '2025-12-11 00:49:37'),
(2, 1, 3, 1, '2025-12-11 00:49:41'),
(3, 5, 3, 1, '2025-12-11 00:50:11'),
(4, 1, 1, 1, '2025-12-11 11:01:48');

-- --------------------------------------------------------

--
-- Table structure for table `reaction_types_safespace`
--

CREATE TABLE `reaction_types_safespace` (
  `reaction_type_id` int(11) NOT NULL,
  `reaction_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reaction_types_safespace`
--

INSERT INTO `reaction_types_safespace` (`reaction_type_id`, `reaction_name`) VALUES
(1, 'Heart');

-- --------------------------------------------------------

--
-- Table structure for table `resources_safespace`
--

CREATE TABLE `resources_safespace` (
  `resource_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `resources_safespace`
--

INSERT INTO `resources_safespace` (`resource_id`, `category_id`, `title`, `content`, `created_at`) VALUES
(1, 1, 'Ghana Suicide Helpline', '2332 444 71279', '2025-12-11 17:04:32'),
(2, 1, 'Zimbabwe Police', '994', '2025-12-11 18:46:12');

-- --------------------------------------------------------

--
-- Table structure for table `resource_categories_safespace`
--

CREATE TABLE `resource_categories_safespace` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `resource_categories_safespace`
--

INSERT INTO `resource_categories_safespace` (`category_id`, `category_name`) VALUES
(4, 'Community Group'),
(1, 'Crisis Helpline'),
(3, 'Professional Therapy'),
(2, 'Self-Care Guide');

-- --------------------------------------------------------

--
-- Table structure for table `users_safespace`
--



--
-- Dumping data for table `users_safespace`
--

INSERT INTO `users_safespace` (`user_id`, `fname`, `lname`, `email`, `password_hash`, `country`, `created_at`, `updated_at`, `roles`) VALUES
(1, 'Rachel', 'Murambiwa', 'rachel@gmail.com', '$2y$10$iL2HdpME3/AmD9tLDVOv9eENVKy4lPNMAVBYQS7Bjx9fAjPlfjI8y', 'Zimbabwe', '2025-12-10 16:56:53', '2025-12-11 16:48:40', 'admin'),
(2, 'Shammah', 'Dzwairo', 'shammah@gmail.com', '$2y$10$ziQdAV/n8Ee.TA4wj6gDsuwY7Mg60t8mWlzsADzLkvahkV1KmQHdK', 'Eswatini', '2025-12-10 17:10:04', '2025-12-10 17:10:04', 'general_user'),
(3, 'Zviko', 'Dzwairo', 'zviko@gmail.com', '$2y$10$qUr.R0so1Xe7gKe0YdFdreRCAAQ7yVro1ysat2SbXQZXNS8n0cIsS', 'Benin', '2025-12-11 00:48:36', '2025-12-11 00:48:36', 'general_user'),
(4, 'Melisah', 'Nemasasi', 'melz@gmail.com', '$2y$10$nR.8XEGHuUsw0k8JvvpPzemV3HUG4b7X2csdcPDZR3/SL5IPHK53O', 'South Africa', '2025-12-11 18:38:47', '2025-12-11 19:11:14', 'general_user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments_safespace`
--
ALTER TABLE `comments_safespace`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `profile_id` (`profile_id`);

--
-- Indexes for table `journal_entries_safespace`
--
ALTER TABLE `journal_entries_safespace`
  ADD PRIMARY KEY (`journal_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `mood_log`
--
ALTER TABLE `mood_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_images_safespace`
--
ALTER TABLE `post_images_safespace`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `profiles_safespace`
--
ALTER TABLE `profiles_safespace`
  ADD PRIMARY KEY (`profile_id`),
  ADD UNIQUE KEY `anonymous_username` (`anonymous_username`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `public_posts_safespace`
--
ALTER TABLE `public_posts_safespace`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `profile_id` (`profile_id`);

--
-- Indexes for table `quotes_safespace`
--
ALTER TABLE `quotes_safespace`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reactions_safespace`
--
ALTER TABLE `reactions_safespace`
  ADD PRIMARY KEY (`reaction_id`),
  ADD UNIQUE KEY `post_id` (`post_id`,`profile_id`,`reaction_type_id`),
  ADD KEY `profile_id` (`profile_id`),
  ADD KEY `reaction_type_id` (`reaction_type_id`);

--
-- Indexes for table `reaction_types_safespace`
--
ALTER TABLE `reaction_types_safespace`
  ADD PRIMARY KEY (`reaction_type_id`),
  ADD UNIQUE KEY `reaction_name` (`reaction_name`);

--
-- Indexes for table `resources_safespace`
--
ALTER TABLE `resources_safespace`
  ADD PRIMARY KEY (`resource_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `resource_categories_safespace`
--
ALTER TABLE `resource_categories_safespace`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `users_safespace`
--
ALTER TABLE `users_safespace`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments_safespace`
--
ALTER TABLE `comments_safespace`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `journal_entries_safespace`
--
ALTER TABLE `journal_entries_safespace`
  MODIFY `journal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mood_log`
--
ALTER TABLE `mood_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `post_images_safespace`
--
ALTER TABLE `post_images_safespace`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `profiles_safespace`
--
ALTER TABLE `profiles_safespace`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `public_posts_safespace`
--
ALTER TABLE `public_posts_safespace`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `quotes_safespace`
--
ALTER TABLE `quotes_safespace`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `reactions_safespace`
--
ALTER TABLE `reactions_safespace`
  MODIFY `reaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reaction_types_safespace`
--
ALTER TABLE `reaction_types_safespace`
  MODIFY `reaction_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `resources_safespace`
--
ALTER TABLE `resources_safespace`
  MODIFY `resource_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `resource_categories_safespace`
--
ALTER TABLE `resource_categories_safespace`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users_safespace`
--
ALTER TABLE `users_safespace`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments_safespace`
--
ALTER TABLE `comments_safespace`
  ADD CONSTRAINT `comments_safespace_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `public_posts_safespace` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_safespace_ibfk_2` FOREIGN KEY (`profile_id`) REFERENCES `profiles_safespace` (`profile_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `journal_entries_safespace`
--
ALTER TABLE `journal_entries_safespace`
  ADD CONSTRAINT `journal_entries_safespace_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_safespace` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mood_log`
--
ALTER TABLE `mood_log`
  ADD CONSTRAINT `mood_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_safespace` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `post_images_safespace`
--
ALTER TABLE `post_images_safespace`
  ADD CONSTRAINT `post_images_safespace_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `public_posts_safespace` (`post_id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles_safespace`
--
ALTER TABLE `profiles_safespace`
  ADD CONSTRAINT `profiles_safespace_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_safespace` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `public_posts_safespace`
--
ALTER TABLE `public_posts_safespace`
  ADD CONSTRAINT `public_posts_safespace_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `profiles_safespace` (`profile_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reactions_safespace`
--
ALTER TABLE `reactions_safespace`
  ADD CONSTRAINT `reactions_safespace_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `public_posts_safespace` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reactions_safespace_ibfk_2` FOREIGN KEY (`profile_id`) REFERENCES `profiles_safespace` (`profile_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reactions_safespace_ibfk_3` FOREIGN KEY (`reaction_type_id`) REFERENCES `reaction_types_safespace` (`reaction_type_id`) ON UPDATE CASCADE;

--
-- Constraints for table `resources_safespace`
--
ALTER TABLE `resources_safespace`
  ADD CONSTRAINT `resources_safespace_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `resource_categories_safespace` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
