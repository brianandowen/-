-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2024 年 11 月 16 日 08:11
-- 伺服器版本： 10.4.28-MariaDB
-- PHP 版本： 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `profile`
--

-- --------------------------------------------------------

--
-- 資料表結構 `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `activity_name` varchar(100) DEFAULT NULL,
  `role` enum('幹部','會員') DEFAULT '會員',
  `activity_date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `member_id`, `activity_name`, `role`, `activity_date`) VALUES
(4, 1, '資管宿營', '會員', '2024-11-14'),
(5, 1, '資管迎新', '會員', '2024-11-04'),
(7, 5, '資管宿營', '會員', '2024-11-14'),
(8, 8, '資管宿營', '幹部', '2024-11-13'),
(9, 5, '資管宿營', '幹部', '2024-11-16'),
(10, 7, 'test1', '會員', '2024-11-16'),
(11, 7, 'test4', '會員', '2024-11-30'),
(12, 8, '制服趴', '幹部', '2024-11-16'),
(13, 6, '制服趴1', '幹部', '2024-11-15');

-- --------------------------------------------------------

--
-- 資料表結構 `fees`
--

CREATE TABLE `fees` (
  `id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `fee_status` enum('paid','unpaid') DEFAULT 'unpaid',
  `payment_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `fees`
--

INSERT INTO `fees` (`id`, `member_id`, `fee_status`, `payment_date`) VALUES
(8, 5, 'paid', '2024-11-14'),
(9, 6, 'paid', '2024-11-16');

-- --------------------------------------------------------

--
-- 資料表結構 `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `contact_info` varchar(100) DEFAULT NULL,
  `enrollment_year` year(4) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `members`
--

INSERT INTO `members` (`id`, `name`, `student_id`, `contact_info`, `enrollment_year`, `position`, `created_at`) VALUES
(1, '陳庭毅', '412401317', '0900080126', '2024', '會員', '2024-11-14 08:02:51'),
(5, '林瑞凡', '412401111', '0900080111', '2023', '幹部', '2024-11-14 12:45:20'),
(6, '趙定宇', '412401290', '090909000', '2024', '幹部', '2024-11-14 12:45:52'),
(7, '孫語謙', '412401408', '0933040508', '2024', '會員', '2024-11-14 15:56:29'),
(8, '齊　一', '412401472', '0988848573', '2024', '幹部', '2024-11-14 15:56:56');

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `account` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` char(1) DEFAULT 'M',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`id`, `account`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin', 'M', '2024-11-14 08:24:52');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- 資料表索引 `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- 資料表索引 `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account` (`account`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `fees`
--
ALTER TABLE `fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`);

--
-- 資料表的限制式 `fees`
--
ALTER TABLE `fees`
  ADD CONSTRAINT `fees_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
