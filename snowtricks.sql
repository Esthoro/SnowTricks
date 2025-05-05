-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2025 at 10:11 AM
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
-- Database: `snowtricks`
--

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250407090635', '2025-04-07 11:06:47', 134),
('DoctrineMigrations\\Version20250407093855', '2025-04-07 11:39:08', 7),
('DoctrineMigrations\\Version20250424090133', '2025-04-24 11:01:42', 59),
('DoctrineMigrations\\Version20250424095513', '2025-04-24 11:55:20', 7);

-- --------------------------------------------------------

--
-- Table structure for table `illustration`
--

CREATE TABLE `illustration` (
  `id` int(11) NOT NULL,
  `trick_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `illustration`
--

INSERT INTO `illustration` (`id`, `trick_id`, `path`) VALUES
(17, 18, '680f2eee65ec6.jpg'),
(26, 27, '6818683a7c10b.png'),
(27, 27, '6818683a7ce25.jpg'),
(28, 27, '6818683a7d5b5.png'),
(29, 28, '681868c457af6.png'),
(30, 29, '6818690ca59fc.png'),
(31, 30, '6818693addc98.png'),
(32, 30, '6818693adeb42.jpg'),
(33, 31, '6818695e41ac8.png'),
(34, 32, '681869a5a03c0.png'),
(35, 32, '6818698bb7d9e.png'),
(36, 33, '68186a2fe874c.png'),
(39, 35, 'trickDefaultPicture.webp'),
(40, 36, 'trickDefaultPicture.webp'),
(41, 37, 'trickDefaultPicture.webp');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `trick_id` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `author_id`, `trick_id`, `content`, `created_at`) VALUES
(4, 13, 18, 'Super trcik !', '2025-05-04 21:04:31'),
(5, 13, 18, 'j\'adore', '2025-05-04 21:04:37'),
(6, 13, 18, 'trop intéressant', '2025-05-04 21:04:44'),
(7, 13, 18, 'coucou', '2025-05-04 21:04:49'),
(8, 13, 18, 'blah', '2025-05-04 21:04:55'),
(9, 13, 18, 'coco', '2025-05-04 21:05:00'),
(10, 13, 18, 'hay', '2025-05-04 21:05:07');

-- --------------------------------------------------------

--
-- Table structure for table `trick`
--

CREATE TABLE `trick` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trick`
--

INSERT INTO `trick` (`id`, `user_id`, `name`, `description`, `group_name`, `slug`, `created_at`, `updated_at`) VALUES
(18, 13, 'Melon', 'Reach outside your leading knee and grab the heel edge of your board between the bindings with your lead hand.', 'grabs', 'melon', '2025-04-28 09:31:58', '2025-04-28 09:31:58'),
(27, 13, 'Ollie', 'The Ollie is the foundational trick that opens up everything from butters to corked 10s. It’s the key to unlocking freestyle riding. \r\n\r\nIt’s simple to do but hard to perfect, so let’s break it down. The Ollie is the trick that gets the board off the ground and requires four steps: loading the board with energy, shifting the weight back, springing off the tail, and then poking the nose to level the whole thing out. Get this down tight, and you’ll open up the big bad world of freestyle.', 'butters', 'ollie', '2025-05-05 09:26:50', '2025-05-05 09:26:50'),
(28, 13, 'Nollie', 'We covered the Ollie above, but the Nollie is the inverse. Crouch, shift your weight forward and then use the nose of your board to spring off.', 'butters', 'nollie', '2025-05-05 09:29:08', '2025-05-05 09:29:08'),
(29, 13, 'Nose-roll 180', 'Start a toe or heelside turn, and once you get on edge, lift the tail of your board, keeping the nose on the ground. Then, spin the board to land switch.', 'butters', 'nose-roll-180', '2025-05-05 09:30:20', '2025-05-05 09:30:20'),
(30, 13, 'Nose-roll 360', 'Start in the same way as the nose-roll 180, but pop harder and with more rotational force. When your board draws perpendicular to the riding direction, lift the nose from the snow and pop into an airborne spin, coming around into a full 360.', 'butters', 'nose-roll-360', '2025-05-05 09:31:06', '2025-05-05 09:31:06'),
(31, 13, 'Weddle', 'Named for Chris Weddle, the inventor, grab your toe edge between the bindings with your lead hand.', 'grabs', 'weddle', '2025-05-05 09:31:42', '2025-05-05 09:31:42'),
(32, 13, 'Method', 'From the Melon grab, extend your legs so your body is almost shaped like a scorpion’s tail and then reach for the sky with your trailing hand. The Method is the most stylish trick, and everyone has their own version.', 'grabs', 'method', '2025-05-05 09:32:27', '2025-05-05 09:32:27'),
(33, 13, 'Nose', 'Grab the nose of your board with your leading hand.', 'grabs', 'nose', '2025-05-05 09:35:11', '2025-05-05 09:35:11'),
(35, 13, 'Wildcat', 'A Wildcat is a backflip that keeps the board parallel to the riding line, so you’re doing a sort of ‘side’ flip without losing momentum.', 'flips', 'wildcat', '2025-05-05 09:36:30', '2025-05-05 09:36:30'),
(36, 13, 'Tamedog', 'The exact inverse of a Wildcat is a Tamedog. This is a frontflip that keeps the board parallel to the riding line. A hard Nollie uses the nose as a springboard to initiate the rotation.', 'flips', 'tamedog', '2025-05-05 09:37:09', '2025-05-05 09:37:09'),
(37, 13, 'Corked Spin', 'A Corked Spin simply adds a front or backflip into a flat spin. You’ll usually hear this in competition settings when pros throw Back Double Corked 10s or Cab Triple Cork 14s. But any spin can be corked, like the Rodeos above.', 'spins', 'corked-spin', '2025-05-05 09:38:55', '2025-05-05 09:38:55');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `confirmation_token` varchar(255) DEFAULT NULL,
  `token_requested_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `roles`, `password`, `photo_path`, `status`, `confirmation_token`, `token_requested_at`) VALUES
(13, 'Esther', 'esthoro28@gmail.com', '[]', '$2y$13$Q8u9YWsDSQwIwyNOhcyXXuFQ0gJo5UAwq/MHA4Np4tqyKGCknEIqi', '/assets/uploads/profile_pictures/defaultProfilePicture.png', 1, NULL, NULL),
(14, 'Essai', 'coucou@email.com', '[]', '$2y$13$8eT5MwhZ14vTnxdL4P1mXOaVN.tNrYKmEFKO/Aubn464x15W1Voh6', '/assets/uploads/profile_pictures/defaultProfilePicture.png', 0, '68d62965dfec417fbe66ab60460c5592dac897f3630b647bc6669279c039e9d1', NULL),
(15, 'Essai', 'coucou@coucouc.com', '[]', '$2y$13$JBabh8HgjN2JoUvVy6aKK.wgCskMZZ4QQ.KbS5LOBdiD39hBiVFGa', 'defaultProfilePicture.png', 1, NULL, NULL),
(16, 'Essai2', 'essai@email.fr', '[]', '$2y$13$p5aWcrmpX1bdV3DMljSLluQYzcDY8nnsIo6./iaL78b4zkCoz586q', 'defaultProfilePicture.png', 1, NULL, NULL),
(17, 'salut', 'ut@salut.co', '[]', '$2y$13$0lYqqLmn5hbrRFWSEbWcye1Z461fh51Lb8UBSJwtVRt5p2VmY72cm', 'barcode-6817baf38ea98.png', 0, 'd5cf94b1-cd39-4862-ab6e-655c1caebba4', '2025-05-04 21:07:31');

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `trick_id` int(11) NOT NULL,
  `embed_code` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `video`
--

INSERT INTO `video` (`id`, `trick_id`, `embed_code`) VALUES
(8, 18, '<iframe width=\"487\" height=\"410\" src=\"https://www.youtube.com/embed/51sQRIK-TEI\" title=\"How To Indy, Melon, Mute &amp; Stalefish Grab On A Snowboard (Regular)\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>'),
(9, 28, '<iframe width=\"678\" height=\"391\" src=\"https://www.youtube.com/embed/aAzP3wNT220\" title=\"How To Nollie On A Snowboard\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>'),
(10, 29, '<iframe width=\"1128\" height=\"634\" src=\"https://www.youtube.com/embed/N3ddt_yoxts\" title=\"How To 180 Nose Roll On A Snowboard\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>'),
(11, 29, '<iframe width=\"1128\" height=\"634\" src=\"https://www.youtube.com/embed/N3ddt_yoxts\" title=\"How To 180 Nose Roll On A Snowboard\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>'),
(12, 32, '<iframe width=\"1128\" height=\"634\" src=\"https://www.youtube.com/embed/N3ddt_yoxts\" title=\"How To 180 Nose Roll On A Snowboard\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `illustration`
--
ALTER TABLE `illustration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D67B9A42B281BE2E` (`trick_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B6BD307FF675F31B` (`author_id`),
  ADD KEY `IDX_B6BD307FB281BE2E` (`trick_id`);

--
-- Indexes for table `trick`
--
ALTER TABLE `trick`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D8F0A91EA76ED395` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Indexes for table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CC7DA2CB281BE2E` (`trick_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `illustration`
--
ALTER TABLE `illustration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `trick`
--
ALTER TABLE `trick`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `illustration`
--
ALTER TABLE `illustration`
  ADD CONSTRAINT `FK_D67B9A42B281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `trick` (`id`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_B6BD307FB281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `trick` (`id`),
  ADD CONSTRAINT `FK_B6BD307FF675F31B` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `trick`
--
ALTER TABLE `trick`
  ADD CONSTRAINT `FK_D8F0A91EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `FK_7CC7DA2CB281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `trick` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
