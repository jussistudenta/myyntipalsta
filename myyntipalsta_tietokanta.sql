-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 11.10.2023 klo 16:26
-- Palvelimen versio: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myyntipalsta`
--

-- --------------------------------------------------------

--
-- Rakenne taululle `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` float DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `date_added` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `items`
--

INSERT INTO `items` (`id`, `title`, `description`, `price`, `image`, `username`, `date_added`) VALUES
(37, 'Playstation 3', 'Playstation 3', 50, 'playstation3__.jpg', 'testaaja2', '2023-10-11');

-- --------------------------------------------------------

--
-- Rakenne taululle `users`
--

CREATE TABLE `users` (
  `id` int(6) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` tinytext NOT NULL,
  `phone` tinytext NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `password`) VALUES
(60, 'testaaja', 'testaaja@testiluukku.com', '123', '$2y$10$RlPIj1hUrl17ztPtYPdHH.8Kbv63AxZjE4p/ZjE23/sD8tDqZkzb6'),
(61, 'testaaja2', 'testaaja2@testaajat.com', '35796', '$2y$10$cqgx4WfVPlbzvM1cU7Flx.4sH7BIbOXt9glafLxSxOi/kqElInijK'),
(62, 'hakkeri', 'hakkeri@hakkerit.com', '0', '$2y$10$158E7R.0AGdDg/roqFnNkuwV.aGsiAQ4un/IqF80tm8.40tjFLAiS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Rajoitteet vedostauluille
--

--
-- Rajoitteet taululle `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `fk_username` FOREIGN KEY (`username`) REFERENCES `users` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
