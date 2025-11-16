-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 10, 2025 at 04:02 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_mvc_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `thumbnail` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `name`, `slug`, `category_id`, `description`, `price`, `thumbnail`, `created_at`, `updated_at`) VALUES
(1, 'marketing tổng quan', 'marketing-tong-quan', 1, NULL, 180000, 'https://online.hienu.vn/storage/photos/31/khoa-hoc-marketing-tong-quan.jpeg', NULL, NULL),
(2, 'content tinh gọn', 'content-tinh-gon', 2, NULL, 200000, 'https://online.hienu.vn/storage/photos/31/content-tinh-gon.jpeg', NULL, NULL),
(3, 'content marketing', 'content-marketing', 3, NULL, 300000, 'https://online.hienu.vn/storage/photos/31/khoa-hoc-xay-dung-thuong-hieu-min.jpg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `course_category`
--

CREATE TABLE `course_category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `course_category`
--

INSERT INTO `course_category` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Hoc Marketing', 'hoc-marketing', '2025-10-14 23:42:57', NULL),
(2, 'PHP', 'hoc-PHP', '2025-10-15 23:43:02', NULL),
(3, 'IT', 'cong-nghe-thong-tin', '2025-10-16 20:43:07', NULL),
(11, 'HTML', 'hoc-html', '2025-10-19 23:43:26', NULL),
(12, 'Lập trình', 'lap-trinh', '2025-10-20 23:44:02', NULL),
(13, 'backend', 'backend', '2025-10-20 23:44:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'student', NULL, NULL),
(2, 'manager', NULL, NULL),
(3, 'test', '2025-10-28 03:01:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `tags` varchar(50) DEFAULT NULL,
  `minutes_read` varchar(50) DEFAULT NULL,
  `views` varchar(50) DEFAULT NULL,
  `comments` varchar(50) DEFAULT NULL,
  `shares` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `tags`, `minutes_read`, `views`, `comments`, `shares`, `created_at`, `updated_at`) VALUES
(1, 'Lập trình PHP cơ bản - đã sửa', 'Bài viết giới thiệu các khái niệm cơ bản của PHP và cách sử dụng trong ứng dụng web.', 'PHP, GIAHUY', '10', '5k', '120', '120', '2025-11-07 14:30:02', '2025-11-08 07:14:14'),
(2, 'Cách tối ưu MySQL cho dự án nhỏ', 'Hướng dẫn tối ưu database MySQL giúp website chạy nhanh và hiệu quả hơn.', 'mysql, database, tips', '7', '250', '22', '12', '2025-11-07 14:30:02', NULL),
(3, 'Kinh nghiệm phỏng vấn fresher developer', 'Một số câu hỏi thường gặp và cách trả lời khi đi phỏng vấn fresher lập trình viên.', 'career, interview', '6', '310', '40', '19', '2025-11-07 14:30:02', NULL),
(4, 'Lập trình PHP cơ bản', 'Bài viết giới thiệu các khái niệm cơ bản của PHP và cách sử dụng trong ứng dụng web.', 'career, interview', '5', '120', '120', '120', '2025-11-08 04:17:48', '2025-11-08 10:35:50'),
(11, 'Lập trình PHP cơ bản – dành cho người mới', 'Trong bài viết này, chúng ta sẽ tìm hiểu tổng quan về PHP: cú pháp cơ bản, biến, hàm, và cách xử lý form trên trình duyệt. PHP là một trong những ngôn ngữ backend dễ học nhất và được sử dụng rộng rãi để xây dựng website động. Bài viết dành cho người mới bắt đầu, bao gồm ví dụ minh họa và hướng dẫn rõ ràng.', NULL, NULL, '5200', '120', '45', '2025-11-08 22:30:03', NULL),
(12, 'Cách tối ưu MySQL cho dự án nhỏ', 'Bạn có biết rằng tốc độ truy vấn của MySQL phụ thuộc rất nhiều vào cách thiết kế bảng và index? Trong bài viết này, chúng ta sẽ học cách sử dụng EXPLAIN, tạo INDEX hợp lý, cũng như cách tối ưu cấu trúc bảng giúp dự án chạy nhanh và hiệu quả hơn.', NULL, NULL, '10300', '350', '80', '2025-11-08 22:30:03', NULL),
(13, 'Kinh nghiệm phỏng vấn fresher developer', 'Bài viết chia sẻ kinh nghiệm thực tế khi đi phỏng vấn vị trí fresher developer: chuẩn bị CV, các câu hỏi thường gặp về kiến thức lập trình, thuật toán, kỹ năng mềm và mindset khi bước vào ngành IT.', NULL, NULL, '19800', '790', '140', '2025-11-08 22:30:03', NULL),
(14, 'So sánh PHP và Node.js trong phát triển web', 'PHP và Node.js là hai công nghệ phổ biến trong lập trình web. Bài viết này so sánh ưu nhược điểm của từng nền tảng dựa trên hiệu năng, tài nguyên, cộng đồng và độ dễ học.', NULL, NULL, '25400', '1020', '210', '2025-11-08 22:30:03', NULL),
(15, 'Hướng dẫn triển khai website lên hosting cPanel', 'Bài viết hướng dẫn cách upload file lên hosting bằng File Manager, import database bằng phpMyAdmin, cấu hình domain và SSL.', NULL, NULL, '32000', '1340', '380', '2025-11-08 22:30:03', NULL),
(16, '321321', '2321321321', '3213213', '', '', '', '', '2025-11-09 11:13:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `token_login`
--

CREATE TABLE `token_login` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `token_login`
--

INSERT INTO `token_login` (`id`, `user_id`, `token`, `created_at`, `updated_at`) VALUES
(53, 84, '9dce3bf92bc9dd41507c1282e25041e223a82f54', '2025-11-09 11:06:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(200) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `avatar` varchar(500) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `forget_token` varchar(500) DEFAULT NULL,
  `active_token` varchar(500) DEFAULT NULL,
  `status` int(11) DEFAULT 0 COMMENT '0 - Chưa kích hoạt, 1 - Đã kích hoạt',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `phone`, `avatar`, `password`, `address`, `forget_token`, `active_token`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Trần Gia Huy', 'trgiahuy14@gmail.com', '0901438544', '/public/assets/image/user-avt-default.jpg', '$2y$10$G.565HQX.qPdx7otnfI3BuXP6AqUya6Mqs8J55k29s6va6i24M8Iy', '225/27/21 Lê Văn Quới,', NULL, NULL, 1, '2025-10-14 09:41:22', '2025-10-20 12:45:03'),
(18, 'An Vũ', 'trgiahuy16@gmail.com', '0901438544', '/public/assets/image/user-avt-default.jpg', NULL, NULL, '', NULL, 1, '2025-10-15 09:40:33', NULL),
(22, 'Trần Gia Huy', 'trgiahuy15@gmail.com', '0901438544', '/public/assets/image/user-avt-default.jpg', NULL, NULL, '', NULL, 1, '2025-10-15 09:40:28', NULL),
(23, 'An Vũ', 'trgiahuy16@gmail.com', '0901438544', '/public/assets/image/user-avt-default.jpg', NULL, NULL, '', NULL, 1, '2025-10-15 09:40:33', NULL),
(25, 'Bình Nghi', 'trgiahuy16@gmail.com', '0901438544', '/public/assets/image/user-avt-default.jpg', NULL, NULL, '', NULL, 1, '2025-10-17 09:40:41', NULL),
(26, 'Đoàn Việt', 'trgiahuy16@gmail.com', '0901438544', '/public/assets/image/user-avt-default.jpg', NULL, NULL, '', NULL, 1, '2025-10-16 09:40:45', NULL),
(47, 'Nguyễn Văn A', 'vana01@gmail.com', '0901123456', '/public/assets/image/user-avt-default.jpg', NULL, NULL, NULL, NULL, 1, '2025-10-18 11:52:32', NULL),
(63, 'Vũ Mai Q', 'maiq17@gmail.com', '0917123456', '/public/assets/image/user-avt-default.jpg', NULL, NULL, NULL, NULL, 1, '2025-10-18 11:52:32', NULL),
(64, 'Đinh Công R', 'congr18@gmail.com', '0918123456', '/public/assets/image/user-avt-default.jpg', NULL, NULL, NULL, NULL, 1, '2025-10-18 11:52:32', NULL),
(65, 'Nguyễn Thanh S', 'thanhs19@gmail.com', '0919123456', '/public/assets/image/user-avt-default.jpg', NULL, NULL, NULL, NULL, 1, '2025-10-18 11:52:32', NULL),
(66, 'Trần Hồng T', 'hongt20@gmail.com', '0920123456', '/public/assets/image/user-avt-default.jpg', NULL, NULL, NULL, NULL, 1, '2025-10-18 11:52:32', NULL),
(67, 'Thế Hiển', 'trgiahuy142@gmail.com', '0901438544', '/public/assets/image/user-avt-default.jpg', '$2y$10$4/H.QMhzxsHikOy4m5eqC.LR1DKiht/WCMAtqzjgZb/.5MbXYXaD.', NULL, NULL, NULL, 0, '2025-10-18 23:07:34', '2025-10-21 10:52:27'),
(71, 'tsstrt', 'tsstrt@gmail.com', '0901438544', '/public/assets/image/user-avt-default.jpg', '$2y$10$S4IeEKra/7BTveKiGEwOGOq4IbYEZ/p168rT8yjIAT1.EGmlq4vDq', '225/27/21 Lê Văn Quớiii', NULL, NULL, 0, '2025-10-18 23:55:04', '2025-10-21 07:16:04'),
(72, 'Minh Sang', 'MinhSang@gmail.com', '0901438544', '/public/assets/image/user-avt-default.jpg', '$2y$10$RvZFhXD6NCoSSdGYcR4TKeAcPWzLkqt/yJWLAxvO/IZ27LAx9c48y', '162 Tran Quang co', NULL, NULL, 1, '2025-10-19 00:01:29', '2025-10-21 10:52:12'),
(73, 'Hy', 'hy@gmail.com', NULL, '/public/assets/image/user-avt-default.jpg', NULL, NULL, NULL, NULL, 0, '2025-10-28 02:54:33', NULL),
(74, 'rdwaw', '321321@gmail.com', '0901438555', '/public/assets/image/user-avt-default.jpg', '$2y$10$jdnAVDVnGi.xt9jyS6gwRuw/6tiSy/RrRjISjO8k.27rbDww0PLmS', NULL, NULL, '6c2de342132a3a4b618bc800ebf1e857b7dd7ce6', 0, '2025-11-03 09:18:37', NULL),
(84, 'Trần Gia Huy', 'giahuy14103@gmail.com', '0901438544', '/public/assets/image/user-avt-default.jpg', '$2y$10$ho.Vc8SneqlrqvzVIOEj5OZ2hpeHT43o75ks8.cGUk/F6rRToNjli', NULL, NULL, NULL, 1, '2025-11-05 06:21:37', '2025-11-05 05:51:51'),
(85, 'Trần Gia Huy', 'trgiahuy14222222@gmail.com', '0901438544', '/public/assets/image/user-avt-default.jpg', '$2y$10$IMp.0zEa9QNJWvOufc4zeOgFAtQt8gnzoLuUzfbRVNV7g8LJHo6bO', NULL, NULL, '3877a370b1088d3aedd3571d6157feae0b6ae438', 0, '2025-11-09 10:33:05', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `course_category`
--
ALTER TABLE `course_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `token_login`
--
ALTER TABLE `token_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `course_category`
--
ALTER TABLE `course_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `token_login`
--
ALTER TABLE `token_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `course_category` (`id`);

--
-- Constraints for table `token_login`
--
ALTER TABLE `token_login`
  ADD CONSTRAINT `token_login_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
