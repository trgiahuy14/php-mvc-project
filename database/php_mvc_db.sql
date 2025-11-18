-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2025 at 04:06 AM
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
(2, 'manager', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
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

INSERT INTO `posts` (`id`, `title`, `author`, `content`, `tags`, `minutes_read`, `views`, `comments`, `shares`, `created_at`, `updated_at`) VALUES
(1, 'PHP 8.2 – Tổng quan tính năng mới', 'Trần Gia Huy', 'Tóm tắt các điểm mới của PHP 8.2 và cách áp dụng vào dự án thực tế.', 'php, backend', '7', '5200', '34', '18', '2025-10-28 09:10:00', '2025-11-02 10:05:00'),
(2, 'Hướng dẫn tối ưu MySQL cho website nhỏ', 'Nguyễn An', 'Các kỹ thuật index, phân tích query và tối ưu cấu trúc bảng cho site nhỏ.', 'mysql, database, tips', '8', '3900', '22', '12', '2025-10-29 14:30:00', NULL),
(3, 'So sánh PHP và Node.js trong phát triển web', 'Bình Nguyễn', 'Ưu – nhược điểm của PHP và Node.js trong các bài toán web thông dụng.', 'php, nodejs, compare', '9', '7400', '41', '29', '2025-10-30 08:20:00', '2025-11-01 16:45:00'),
(4, 'Thiết kế RESTful API chuẩn trong PHP', 'Minh Sang', 'Quy ước endpoint, versioning, auth và error handling cho RESTful API.', 'api, php, design', '6', '3100', '15', '9', '2025-10-31 10:00:00', NULL),
(5, 'Laravel Eloquent: 15 mẹo tăng tốc truy vấn', 'Đoàn Việt', 'Những thủ thuật eager loading, chunking và caching cho Eloquent.', 'laravel, performance', '7', '6800', '27', '21', '2025-11-01 09:12:00', '2025-11-03 13:20:00'),
(6, 'Bảo mật ứng dụng PHP: checklist căn bản', 'Trần Gia Huy', 'Checklist nhanh: XSS, CSRF, SQLi, session, password hashing.', 'security, php', '5', '4500', '19', '14', '2025-11-01 15:40:00', NULL),
(8, 'Tối ưu ảnh và tệp tĩnh cho web tốc độ cao', 'Bình Nguyễn', 'Chiến lược nén ảnh, lazyload, cache-control và CDN.', 'performance, frontend, cdn', '6', '5600', '23', '17', '2025-11-02 11:30:00', '2025-11-05 09:10:00'),
(9, 'Viết middleware auth tối giản cho MVC tự code', 'Minh Sang', 'Tạo lớp Auth và requireLogin cho router/controller mini MVC.', 'php, mvc, auth', '7', '3300', '16', '11', '2025-11-02 16:50:00', NULL),
(10, 'MySQL Index: Khi nào nên tạo và tránh lạm dụng', 'Đoàn Việt', 'Hiểu cơ chế B-Tree, chọn cột phù hợp và đo đạc bằng EXPLAIN.', 'mysql, index', '8', '6200', '28', '18', '2025-11-03 09:00:00', '2025-11-06 10:55:00'),
(11, 'SEO căn bản cho bài viết kỹ thuật', 'Trần Gia Huy', 'Cấu trúc tiêu đề, meta description, heading và internal link.', 'seo, content', '5', '2900', '10', '7', '2025-11-03 13:40:00', NULL),
(12, 'Quy ước commit: feat, fix, refactor… dùng sao cho đúng', 'Nguyễn An', 'Giải thích conventional commits và ví dụ thực tế dự án.', 'git, workflow', '6', '4100', '20', '13', '2025-11-03 18:30:00', NULL),
(13, 'Tạo trang admin gọn nhẹ với AdminLTE', 'Bình Nguyễn', 'Tích hợp AdminLTE, tùy biến theme và layout cơ bản.', 'adminlte, ui', '5', '3800', '14', '9', '2025-11-04 08:25:00', NULL),
(14, 'Pagination chuẩn: limit/offset, count và UX', 'Minh Sang', 'Cách phân trang tối ưu, hiển thị per-page và giữ query string.', 'pagination, ux', '5', '2600', '9', '6', '2025-11-04 10:10:00', NULL),
(15, 'Upload file an toàn: validate mime & size', 'Đoàn Việt', 'Kiểm tra MIME, kích thước, chống upload shell và lưu public path.', 'security, upload', '6', '3000', '11', '8', '2025-11-04 14:45:00', NULL),
(16, 'Sử dụng PDO Prepared Statements chống SQLi', 'Trần Gia Huy', 'Áp dụng prepared statements, bindParam và kiểu dữ liệu.', 'pdo, security', '7', '5900', '26', '19', '2025-11-05 09:35:00', '2025-11-07 12:00:00'),
(17, 'Thiết lập .htaccess rewrite đẹp cho MVC', 'Nguyễn An', 'Rewrite toàn bộ request về index.php và loại bỏ index.php trên URL.', 'apache, rewrite', '5', '2400', '8', '5', '2025-11-05 11:20:00', '2025-11-15 17:02:42'),
(18, 'Quản lý cấu hình đa môi trường (.env) trong PHP', 'Bình Nguyễn', 'Tách config theo môi trường: local, staging, production.', 'config, env', '6', '3500', '13', '9', '2025-11-05 15:00:00', NULL),
(19, 'Thiết kế cấu trúc thư mục dự án PHP sạch', 'Minh Sang', 'Chuẩn hóa core/, app/, public/, routers/ và helpers.', 'architecture, php', '6', '4200', '17', '12', '2025-11-06 09:10:00', '2025-11-15 17:02:37');

-- --------------------------------------------------------

--
-- Table structure for table `token_login`
--

CREATE TABLE `token_login` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `token_login`
--

INSERT INTO `token_login` (`id`, `user_id`, `token`, `created_at`, `expires_at`) VALUES
(89, 94, '370039695151c47578a0f7bc5b275507b01656d6bafe67f657113128b7fc9c7b', '2025-11-18 09:49:22', '2025-11-19 09:49:22');

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
  `is_active` int(11) DEFAULT 0 COMMENT '0 - Chưa kích hoạt, 1 - Đã kích hoạt',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `phone`, `avatar`, `password`, `address`, `forget_token`, `active_token`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Trần Gia Huy', 'trgiahuy14@gmail.com', '0901438544', '/public/assets/img/user-avt-default.jpg', '$2y$10$G.565HQX.qPdx7otnfI3BuXP6AqUya6Mqs8J55k29s6va6i24M8Iy', '225/27/21 Lê Văn Quới,', NULL, NULL, 1, '2025-10-14 09:41:22', '2025-11-12 08:03:37'),
(18, 'An Vũ', 'trgiahuy16@gmail.com', '0901438544', '/public/assets/img/user-avt-default.jpg', NULL, NULL, '', NULL, 1, '2025-10-15 09:40:33', '2025-11-12 08:03:37'),
(22, 'Trần Gia Huy', 'trgiahuy15@gmail.com', '0901438544', '/public/assets/img/user-avt-default.jpg', NULL, NULL, '', NULL, 1, '2025-10-15 09:40:28', '2025-11-12 08:03:37'),
(23, 'An Vũ', 'trgiahuy16@gmail.com', '0901438544', '/public/assets/img/user-avt-default.jpg', NULL, NULL, '', NULL, 1, '2025-10-15 09:40:33', '2025-11-12 08:03:37'),
(25, 'Bình Nghi', 'trgiahuy16@gmail.com', '0901438544', '/public/assets/img/user-avt-default.jpg', NULL, NULL, '', NULL, 1, '2025-10-17 09:40:41', '2025-11-12 08:03:37'),
(26, 'Đoàn Việt', 'trgiahuy16@gmail.com', '0901438544', '/public/assets/img/user-avt-default.jpg', NULL, NULL, '', NULL, 1, '2025-10-16 09:40:45', '2025-11-12 08:03:37'),
(47, 'Nguyễn Văn A', 'vana01@gmail.com', '0901123456', '/public/assets/img/user-avt-default.jpg', NULL, NULL, NULL, NULL, 1, '2025-10-18 11:52:32', '2025-11-12 08:03:37'),
(63, 'Vũ Mai Q', 'maiq17@gmail.com', '0917123456', '/public/assets/img/user-avt-default.jpg', NULL, NULL, NULL, NULL, 1, '2025-10-18 11:52:32', '2025-11-12 08:03:37'),
(64, 'Đinh Công R', 'congr18@gmail.com', '0918123456', '/public/assets/img/user-avt-default.jpg', NULL, NULL, NULL, NULL, 1, '2025-10-18 11:52:32', '2025-11-12 08:03:37'),
(65, 'Nguyễn Thanh S', 'thanhs19@gmail.com', '0919123456', '/public/assets/img/user-avt-default.jpg', NULL, NULL, NULL, NULL, 1, '2025-10-18 11:52:32', '2025-11-12 08:03:37'),
(66, 'Trần Hồng T', 'hongt20@gmail.com', '0920123456', '/public/assets/img/user-avt-default.jpg', NULL, NULL, NULL, NULL, 1, '2025-10-18 11:52:32', '2025-11-12 08:03:37'),
(67, 'Thế Hiển', 'trgiahuy142@gmail.com', '0901438544', '/public/assets/img/user-avt-default.jpg', '$2y$10$4/H.QMhzxsHikOy4m5eqC.LR1DKiht/WCMAtqzjgZb/.5MbXYXaD.', NULL, NULL, NULL, 1, '2025-10-18 23:07:34', '2025-11-12 08:03:37'),
(71, 'tsstrt', 'tsstrt@gmail.com', '0901438544', '/public/assets/img/user-avt-default.jpg', '$2y$10$S4IeEKra/7BTveKiGEwOGOq4IbYEZ/p168rT8yjIAT1.EGmlq4vDq', '225/27/21 Lê Văn Quớiii', NULL, NULL, 1, '2025-10-18 23:55:04', '2025-11-12 08:03:37'),
(72, 'Minh Sang', 'MinhSang@gmail.com', '0901438544', '/public/assets/img/user-avt-default.jpg', '$2y$10$RvZFhXD6NCoSSdGYcR4TKeAcPWzLkqt/yJWLAxvO/IZ27LAx9c48y', '162 Tran Quang co', NULL, NULL, 1, '2025-10-19 00:01:29', '2025-11-12 08:03:37'),
(73, 'Hy', 'hy@gmail.com', NULL, '/public/assets/img/user-avt-default.jpg', NULL, NULL, NULL, NULL, 1, '2025-10-28 02:54:33', '2025-11-12 08:03:37'),
(74, 'rdwaw', '321321@gmail.com', '0901438555', '/public/assets/img/user-avt-default.jpg', '$2y$10$jdnAVDVnGi.xt9jyS6gwRuw/6tiSy/RrRjISjO8k.27rbDww0PLmS', NULL, NULL, NULL, 1, '2025-11-03 09:18:37', '2025-11-12 08:03:37'),
(85, 'Trần Gia Huy', 'trgiahuy14222222@gmail.com', '0901438544', '/public/assets/img/user-avt-default.jpg', '$2y$10$IMp.0zEa9QNJWvOufc4zeOgFAtQt8gnzoLuUzfbRVNV7g8LJHo6bO', NULL, NULL, NULL, 1, '2025-11-09 10:33:05', '2025-11-12 08:03:37'),
(94, 'Trần Gia Huy', 'giahuy14103@gmail.com', '0901438544', '/public/assets/img/user-avt-default.jpg', '$2y$10$Jg9KPWwW2Xfnf60s1SjiH.irMyJBPive5rcKgEF9C5XMJUjnXCR8O', NULL, NULL, NULL, 1, '2025-11-17 19:33:10', '2025-11-18 02:18:35');

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
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `token_login`
--
ALTER TABLE `token_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `token_login`
--
ALTER TABLE `token_login`
  ADD CONSTRAINT `token_login_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
