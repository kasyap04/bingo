-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2022 at 10:54 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bingo`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `c_id` int(11) NOT NULL,
  `msg` varchar(100) DEFAULT NULL,
  `send_by` int(11) DEFAULT NULL,
  `send_to` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `f_id` int(11) NOT NULL,
  `user1` int(11) DEFAULT NULL,
  `user2` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`f_id`, `user1`, `user2`, `status`) VALUES
(1, 1, 2, 1),
(5, 1, 3, 0),
(6, 2, 1, 1),
(8, 2, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `play`
--

CREATE TABLE `play` (
  `p_id` int(11) NOT NULL,
  `t_id` int(11) DEFAULT NULL,
  `selected` int(11) DEFAULT NULL,
  `selected_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `play`
--

INSERT INTO `play` (`p_id`, `t_id`, `selected`, `selected_by`) VALUES
(68, 30, 5, 1),
(70, 30, 3, 1),
(71, 30, 17, 2),
(72, 30, 1, 1),
(74, 30, 9, 1),
(75, 30, 7, 2),
(77, 30, 13, 2),
(78, 30, 11, 1),
(79, 30, 18, 2),
(80, 30, 20, 1),
(81, 30, 19, 2),
(83, 30, 2, 2),
(84, 30, 14, 1),
(85, 30, 15, 2),
(86, 30, 4, 2),
(87, 30, 23, 1),
(88, 30, 24, 2),
(90, 30, 22, 2),
(91, 30, 16, 2),
(101, 30, 25, 2);

-- --------------------------------------------------------

--
-- Table structure for table `record`
--

CREATE TABLE `record` (
  `id` int(11) NOT NULL,
  `t_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `status` varchar(5) NOT NULL,
  `started` datetime DEFAULT current_timestamp(),
  `ended` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `t_id` int(11) NOT NULL,
  `player1` int(11) DEFAULT NULL,
  `player2` int(11) DEFAULT NULL,
  `num_p1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`num_p1`)),
  `num_p2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`num_p2`)),
  `active` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Team is still active or not'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`t_id`, `player1`, `player2`, `num_p1`, `num_p2`, `active`) VALUES
(30, 1, 2, '[[\"10\",\"18\",\"08\",\"19\",\"07\"],[\"05\",\"09\",\"20\",\"03\",\"24\"],[\"13\",\"23\",\"11\",\"14\",\"04\"],[\"01\",\"25\",\"16\",\"22\",\"06\"],[\"17\",\"21\",\"15\",\"02\",\"12\"]]', '[[\"21\",\"16\",\"12\",\"09\",\"13\"],[\"10\",\"03\",\"08\",\"02\",\"25\"],[\"11\",\"18\",\"24\",\"07\",\"22\"],[\"14\",\"23\",\"15\",\"17\",\"01\"],[\"06\",\"20\",\"04\",\"19\",\"05\"]]', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `u_id` int(3) NOT NULL,
  `name` varchar(25) NOT NULL,
  `password` varchar(50) NOT NULL,
  `t_game` int(11) NOT NULL,
  `w_game` int(11) NOT NULL,
  `t_id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `last_request` datetime DEFAULT NULL,
  `feedback` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`u_id`, `name`, `password`, `t_game`, `w_game`, `t_id`, `request_id`, `last_request`, `feedback`) VALUES
(1, 'vishnu', '25d55ad283aa400af464c76d713c07ad', 1, 0, 0, NULL, NULL, 'hey there'),
(2, 'ridhik', '25d55ad283aa400af464c76d713c07ad', 0, 3, 0, NULL, NULL, NULL),
(3, 'saurav', '25d55ad283aa400af464c76d713c07ad', 0, 0, 0, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `send_by` (`send_by`),
  ADD KEY `send_to` (`send_to`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`f_id`),
  ADD KEY `user1` (`user1`),
  ADD KEY `user2` (`user2`);

--
-- Indexes for table `play`
--
ALTER TABLE `play`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `t_id` (`t_id`),
  ADD KEY `selected_by` (`selected_by`);

--
-- Indexes for table `record`
--
ALTER TABLE `record`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`t_id`),
  ADD KEY `player1` (`player1`),
  ADD KEY `player2` (`player2`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`u_id`),
  ADD KEY `request_id` (`request_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `play`
--
ALTER TABLE `play`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `record`
--
ALTER TABLE `record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `u_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`send_by`) REFERENCES `user` (`u_id`),
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`send_to`) REFERENCES `user` (`u_id`);

--
-- Constraints for table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`user1`) REFERENCES `user` (`u_id`),
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`user2`) REFERENCES `user` (`u_id`);

--
-- Constraints for table `play`
--
ALTER TABLE `play`
  ADD CONSTRAINT `play_ibfk_1` FOREIGN KEY (`t_id`) REFERENCES `team` (`t_id`),
  ADD CONSTRAINT `play_ibfk_2` FOREIGN KEY (`selected_by`) REFERENCES `user` (`u_id`);

--
-- Constraints for table `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `team_ibfk_1` FOREIGN KEY (`player1`) REFERENCES `user` (`u_id`),
  ADD CONSTRAINT `team_ibfk_2` FOREIGN KEY (`player2`) REFERENCES `user` (`u_id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `user` (`u_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
