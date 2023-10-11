-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 02.10.2023 klo 22:22
-- Palvelimen versio: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


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
  `image` varchar(200) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `date_added` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `items`
--

INSERT INTO `items` (`id`, `title`, `description`, `image`, `username`, `date_added`) VALUES
(1, 'Macbook Pro -kannettava', 'Toimiva Macbook Pro -kannettava. Todella hyvä kuvanlaatu. Jonkin verran hidastunut iän myötä. Kuumenee ladattaessa ja raskaassa käytössä paljon.', '', 'testaaja', '2023-10-02'),
(4, 'Playstation 3', 'Hyvässä kunnossa.', 'ps3_slim_320-2.jpg', 'testaaja', '2023-10-02');

-- --------------------------------------------------------

--
-- Rakenne taululle `users`
--

CREATE TABLE `users` (
  `id` int(6) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(8, 'testaaja', '$2y$10$9OpsgsUILy5LzhEtKypRW.i3uzJ2hbTvfNNgMRKJt1nMoRTRXv8Pm'),
(16, '', '$2y$10$rfKqsIUluvMmMmPdaWB1KedffS3zq/yf2wj8ApuG4IMotmSuZalVi');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
