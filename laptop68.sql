-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th7 29, 2023 lúc 12:37 AM
-- Phiên bản máy phục vụ: 8.0.17
-- Phiên bản PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `Laptop68`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `adminname` varchar(255) DEFAULT NULL COMMENT 'Tên đăng nhập',
  `role` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(255) DEFAULT NULL COMMENT 'Mật khẩu đăng nhập',
  `first_name` varchar(255) DEFAULT NULL COMMENT 'Fist name',
  `last_name` varchar(255) DEFAULT NULL COMMENT 'Last name',
  `phone` varchar(11) DEFAULT NULL COMMENT 'SĐT user',
  `address` varchar(255) DEFAULT NULL COMMENT 'Địa chỉ user',
  `email` varchar(255) DEFAULT NULL COMMENT 'Email của user',
  `avatar` varchar(255) DEFAULT NULL COMMENT 'File ảnh đại diện',
  `last_login` datetime DEFAULT NULL COMMENT 'Lần đăng nhập gần đây nhất',
  `status` tinyint(3) DEFAULT 0 COMMENT 'Trạng thái danh mục: 0 - Inactive, 1 - Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Ngày tạo',
  `updated_at` datetime DEFAULT NULL COMMENT 'Ngày cập nhật cuối'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Tên danh mục',
  `type` tinyint(3) DEFAULT 0 COMMENT 'Loại danh mục: 0 - Product, 1 - News',
  `avatar` varchar(255) DEFAULT NULL COMMENT 'Tên file ảnh danh mục',
  `description` text DEFAULT NULL COMMENT 'Mô tả chi tiết cho danh mục',
  `status` tinyint(3) DEFAULT 0 COMMENT 'Trạng thái danh mục: 0 - Inactive, 1 - Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Ngày tạo danh mục',
  `updated_at` datetime DEFAULT NULL COMMENT 'Ngày cập nhật cuối'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image_event` text DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL COMMENT 'Id của danh mục mà tin tức thuộc về, là khóa ngoại liên kết với bảng categories',
  `name` varchar(255) NOT NULL COMMENT 'Tiêu đề tin tức',
  `summary` text DEFAULT NULL COMMENT 'Mô tả ngắn cho tin tức',
  `avatar` varchar(255) DEFAULT NULL COMMENT 'Tên file ảnh tin tức',
  `content` text DEFAULT NULL COMMENT 'Mô tả chi tiết cho sản phẩm',
  `status` tinyint(3) DEFAULT 0 COMMENT 'Trạng thái danh mục: 0 - Inactive, 1 - Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Ngày tạo',
  `updated_at` datetime DEFAULT NULL COMMENT 'Ngày cập nhật cuối'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'Id của user trong trường hợp đã login và đặt hàng, là khóa ngoại liên kết với bảng users',
  `fullname` varchar(255) DEFAULT NULL COMMENT 'Tên khách hàng',
  `address` varchar(255) DEFAULT NULL COMMENT 'Địa chỉ khách hàng',
  `mobile` varchar(11) DEFAULT NULL COMMENT 'SĐT khách hàng',
  `email` varchar(255) DEFAULT NULL COMMENT 'Email khách hàng',
  `note` text DEFAULT NULL COMMENT 'Ghi chú từ khách hàng',
  `price_total` int(255) DEFAULT NULL COMMENT 'Tổng giá trị đơn hàng',
  `payment_status` tinyint(2) DEFAULT 0 COMMENT 'Trạng thái đơn hàng: 0 - Chưa thành toán, 1 - Đã thành toán',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Ngày tạo đơn',
  `updated_at` datetime DEFAULT NULL COMMENT 'Ngày cập nhật cuối'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `order_id` int(11) DEFAULT NULL COMMENT 'Id của order tương ứng, là khóa ngoại liên kết với bảng orders',
  `product_id` int(11) NOT NULL COMMENT 'ID sp tại thời điểm đặt hàng',
  `product_price` int(11) DEFAULT NULL COMMENT 'Giá sản phẩm tương ứng tại thời điểm đặt hàng',
  `quantity` int(11) DEFAULT NULL COMMENT 'Số lượng sản phẩm tương ứng tại thời điểm đặt hàng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Bẫy `order_details`
--
DELIMITER $$
CREATE TRIGGER `create_orders` AFTER INSERT ON `order_details` FOR EACH ROW BEGIN
    DECLARE quantity INT;
    DECLARE product_id INT;
    SELECT NEW.quantity, NEW.product_id INTO quantity, product_id;
    
    IF (SELECT amount FROM products WHERE id = product_id) >= quantity THEN
        UPDATE products SET amount = amount - quantity WHERE id = product_id;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Not enough products in stock.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL COMMENT 'Id của danh mục mà sản phẩm thuộc về, là khóa ngoại liên kết với bảng categories',
  `title` varchar(255) DEFAULT NULL COMMENT 'Tên sản phẩm',
  `avatar` text DEFAULT NULL COMMENT 'Tên file ảnh sản phẩm',
  `color` varchar(55) DEFAULT NULL COMMENT 'Màu sắc',
  `price` int(11) DEFAULT NULL COMMENT 'Giá sản phẩm',
  `amount` int(11) DEFAULT NULL COMMENT 'Số lượng sản phẩm trong kho',
  `summary` varchar(255) DEFAULT NULL COMMENT 'Mô tả ngắn cho sản phẩm',
  `content` text DEFAULT NULL COMMENT 'Mô tả chi tiết cho sản phẩm',
  `status` tinyint(3) DEFAULT 1 COMMENT 'Trạng thái danh mục: 0 - Inactive, 1 - Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Ngày tạo',
  `updated_at` datetime DEFAULT NULL COMMENT 'Ngày cập nhật cuối'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL COMMENT 'Tên đăng nhập',
  `password` varchar(255) DEFAULT NULL COMMENT 'Mật khẩu đăng nhập',
  `first_name` varchar(255) DEFAULT NULL COMMENT 'Fist name',
  `last_name` varchar(255) DEFAULT NULL COMMENT 'Last name',
  `phone` varchar(11) DEFAULT NULL COMMENT 'SĐT user',
  `address` varchar(255) DEFAULT NULL COMMENT 'Địa chỉ user',
  `email` varchar(255) DEFAULT NULL COMMENT 'Email của user',
  `avatar` varchar(255) DEFAULT NULL COMMENT 'File ảnh đại diện',
  `last_login` datetime DEFAULT NULL COMMENT 'Lần đăng nhập gần đây nhất',
  `facebook` varchar(255) DEFAULT NULL COMMENT 'Link facebook',
  `status` tinyint(3) DEFAULT 0 COMMENT 'Trạng thái danh mục: 0 - Inactive, 1 - Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Ngày tạo',
  `updated_at` datetime DEFAULT NULL COMMENT 'Ngày cập nhật cuối'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_adminname` (`adminname`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Chỉ mục cho bảng `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT cho bảng `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT cho bảng `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `event_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`);

--
-- Các ràng buộc cho bảng `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `news_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`);
COMMIT;


--
-- Đang đổ dữ liệu cho bảng `admins`
--

INSERT INTO `admins` (`id`, `adminname`, `role`, `password`, `first_name`, `last_name`, `phone`, `address`, `email`, `avatar`, `last_login`, `status`, `created_at`, `updated_at`) VALUES
(160, 'long2805', 0, '$2y$10$uJXcGCXv2EXxJWWFpXSnpeG6qzrIikcP14MbUa8hxSBCvBPFSdXrO', 'Long', 'Nguyen', '0373521923', NULL, NULL, NULL, NULL, 0, '2023-07-31 11:53:35', NULL),
(161, 'long123', 0, '$2y$10$uJXcGCXv2EXxJWWFpXSnpeG6qzrIikcP14MbUa8hxSBCvBPFSdXrO', 'Long2', 'Nguyen', '0373521923', NULL, NULL, NULL, NULL, 0, '2023-07-31 11:55:14', NULL),
(164, 'viethai123', 0, '$2y$10$uJXcGCXv2EXxJWWFpXSnpeG6qzrIikcP14MbUa8hxSBCvBPFSdXrO', 'Hai', 'Nguyen', '0387228912', NULL, NULL, NULL, NULL, 1, '2023-07-30 06:50:46', NULL),
(172, 'hiep123', 0, '$2y$10$uJXcGCXv2EXxJWWFpXSnpeG6qzrIikcP14MbUa8hxSBCvBPFSdXrO', 'Hiep', 'Nguyen', '0252834123', NULL, NULL, NULL, NULL, 0, '2023-07-30 12:04:38', NULL);

-- --------------------------------------------------------

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `admin_id`, `name`, `type`, `avatar`, `description`, `status`, `created_at`, `updated_at`) VALUES
(60, 164, 'HP', 0, '', '', 1, '2023-07-22 14:25:13', '2023-07-30 16:59:46'),
(63, 164, 'Asus', 1, '', '', 1, '2023-07-23 01:47:30', '2023-07-31 09:18:51'),
(65, 164, 'Lenovo', 1, '', '', 1, '2023-07-24 02:57:35', NULL),
(67, 164, 'Acer', 0, '', '', 1, '2023-07-29 14:59:31', NULL),
(68, 164, 'Dell', 0, '', '', 1, '2023-07-29 14:59:55', '2023-08-01 02:50:24'),
(69, 164, 'MSI', 0, '', '', 0, '2023-07-29 15:00:01', '2023-08-01 18:24:12'),
(70, 164, 'MacBook', 0, '', '', 0, '2023-07-29 15:00:08', '2023-08-01 18:23:44'),
(71, 164, 'GIGABYTE', 0, '', '', 0, '2023-07-29 15:00:29', '2023-08-01 02:50:56'),
(72, 164, 'LG', 0, '', '', 1, '2023-07-29 15:00:59', NULL);
-- --------------------------------------------------------

--
-- Đang đổ dữ liệu cho bảng `event`
--

INSERT INTO `event` (`id`, `admin_id`, `category_id`, `image_event`, `description`) VALUES
(46, 164, 68, 'https://cdn.tgdd.vn/2023/07/banner/b2s-1200-300-1200x300-1.png', ''),
(49, 164, 65, 'https://cdn.tgdd.vn/2023/07/banner/i3-1200-300-1200x300.png', ''),
(50, 160, 69, 'https://cdn.tgdd.vn/2023/07/banner/gaming-1200-300-1200x300-1.png', NULL),
(51, 160, 67, 'https://cdn.tgdd.vn/2023/07/banner/acer-1200-300-1200x300-2.png', NULL);

-- --------------------------------------------------------

--
-- Đang đổ dữ liệu cho bảng `news`
--

INSERT INTO `news` (`id`, `admin_id`, `category_id`, `name`, `summary`, `avatar`, `content`, `status`, `created_at`, `updated_at`) VALUES
(13, 164, 67, '5 Laptop Tuyệt Vời Dành Cho Học Sinh Học Tập Hiệu Quả', 'Hiện nay, đa phần các bạn học sinh, sinh viên thường xuyên sử dụng laptop trong quá trình học tập. Đặc biệt trong thời điểm dịch bệnh, hình thức học Online càng khuyến khích học sinh phải có máy tính hoặc laptop. Đó là lý do mà nhiều phụ huynh học sinh đang tìm kiếm laptop dành cho con mình. Sau đây là top 5 laptop tuyệt vời dành cho học sinh được khuyên dùng.', 'https://hoanghapc.vn/media/news/1605_laptop-danh-cho-hoc-sinh-2.jpg', 'Asus E203MAH-FD004T là dòng máy tính hỗ trợ các công việc cơ bản như học tập, nghiên cứu, giải trí…rất phù hợp với học sinh, sinh viên và dân văn phòng. Máy có kích thước nhỏ hơn tờ giấy A4 và khối lượng 1,2 kg, dễ dàng mang đi làm, đi học.Được thiết kế tối ưu cho việc di chuyển và thuộc dòng laptop Asus pin lâu có thời lượng lên đến 10 giờ, nên bạn không cần phải lo lắng với việc tìm kiếm ổ điện hay quên thiết bị sạc của máy ở nhà. Chưa hết, máy còn linh hoạt với tính năng gập 180 độ như quyển sổ, gõ phím nhẹ nhàng với bàn phím ergonomic… Sở hữu những đặc điểm giá trị trên mà máy chỉ có giá 5,51 triệu đồng, khá phù hợp với các bạn học sinh, sinh viên.', 1, '2023-07-27 06:04:06', '2023-07-30 20:15:02'),
(14, 160, 69, 'Laptop MSI Của Nước Nào? Có Nên Mua Hay Không?', 'Laptop msi được rất nhiều người ưa chuộng sử dụng và đánh giá cao hiện nay. Bao gồm về cả mẫu mã lẫn chất lượng. Song không phải ai cũng biết đầy đủ các thông tin về dòng laptop đình đám này. Laptop msi của nước nào? Laptop msi có những ưu điểm gì? Liệu có nên mua laptop này sử dụng hay không? Đây chắc hẳn là những câu hỏi người tiêu dùng quan tâm khi mua laptop về để sử dụng.', 'https://hoanghapc.vn/media/news/1405_laptop-msi-cua-nuoc-nao-3.jpg','Laptop msi đến từ hãng cùng tên - Msi, tên đầy đủ là Micro-Star International. Đây là hãng sản xuất đồ công nghệ điện tử tại Đài Loan (trụ sở chính: Tân Bắc, Đài Loan) vô cùng đình đám. Thương hiệu này chuyên sản xuất các linh kiện điện tử. Ví dụ như card đồ họa, thiết bị ngoại vi, laptop,... Nhìn chung, các sản phẩm từ thương hiệu này đều được đánh giá cao về chất lượng cũng như mẫu mã, phổ biến ở hơn 120 quốc gia. Hứa hẹn mang đến trải nghiệm sử dụng tối ưu cho khách hàng. Cho đến năm 2015, msi cũng đã thuộc top thương hiệu laptop hàng đầu trên thế giới.Trong khi đó, Msi khá nổi tiếng tại thị trường Việt Nam.Và laptop msi là dòng sản phẩm bán chạy hàng đầu, luôn thuộc top best seller hiện nay. Được nhiều người tiêu dùng đánh giá cao và lựa chọn sử dụng phổ biến. Vậy laptop msi có những ưu điểm nổi trội gì? Có xứng đáng với chi phí bán hiện nay hay không? Hãy cùng tham khảo ở ngay phần kế tiếp với Laptop68 !', 1, '2023-08-01 05:06:15', NULL);

-- --------------------------------------------------------

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `admin_id`, `category_id`, `title`, `avatar`, `color`, `price`, `amount`, `summary`, `content`, `status`, `created_at`, `updated_at`)
VALUES
(1, 160, 69, 'Laptop MSI GF65 Thin 10UE-477VN 15.6 inch (i7-10750H/16GB/512GB NVMe/GTX 1650 Max-Q/W10H)', 'https://cdn.tgdd.vn/Products/Images/450x450/87479/MSI-GF65-Thin-10UE-477VN-156-inch-i7-10750H-16GB-512GB-NVMe-GTX-1650-Max-Q-W10H.jpg', 'Xanh', 23990000, 100, 'Laptop MSI GF65 Thin 10UE-477VN là sản phẩm laptop gaming cao cấp đến từ MSI, được trang bị chip Intel Core i7-10750H, card đồ họa NVIDIA GeForce GTX 1650 Max-Q, màn hình 15.6 inch Full HD, RAM 16GB và SSD 512GB.', 'Laptop MSI GF65 Thin 10UE-477VN có thiết kế mỏng nhẹ, vỏ kim loại nguyên khối sang trọng, cấu hình mạnh mẽ với chip Intel Core i7-10750H và card đồ họa NVIDIA GeForce GTX 1650 Max-Q, màn hình 15.6 inch Full HD, RAM 16GB và SSD 512GB. Đây là một sản phẩm laptop gaming cao cấp phù hợp với người dùng cần một chiếc máy có hiệu năng mạnh mẽ để chơi game, thiết kế đẹp mắt và sang trọng.', '1', '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(2, 160, 68, 'Laptop Dell XPS 15 9510 15.6 inch (i7-11800H/16GB/512GB NVMe/RTX 3050Ti/Win11 Home)', 'https://cdn.tgdd.vn/Products/Images/450x450/87501/Dell-XPS-15-9510-156-inch-i7-11800H-16GB-512GB-NVMe-RTX-3050Ti-Win11-Home.jpg', 'Silver', 30990000, 100, 'Laptop Dell XPS 15 9510 là sản phẩm laptop cao cấp đến từ Dell, được trang bị chip Intel Core i7-11800H, card đồ họa NVIDIA GeForce RTX 3050Ti, màn hình 15.6 inch InfinityEdge Full HD, RAM 16GB và SSD 512GB.', 'Laptop Dell XPS 15 9510 có thiết kế sang trọng, vỏ nhôm nguyên khối, cấu hình mạnh mẽ với chip Intel Core i7-11800H và card đồ họa NVIDIA GeForce RTX 3050Ti, màn hình 15.6 inch InfinityEdge Full HD, RAM 16GB và SSD 512GB. Đây là một sản phẩm laptop cao cấp phù hợp với người dùng cần một chiếc máy có hiệu năng mạnh mẽ, thiết kế sang trọng và thời lượng pin lâu.', '1', '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(15, 160, 67, 'Laptop Acer Nitro 5 AN515-57 15.6 inch (i5-1240P/8GB/512GB NVMe/GTX 1650 Ti/Win11 Home)', 'https://cdn.tgdd.vn/Products/Images/450x450/87623/Acer-Nitro-5-AN515-57-156-inch-i5-1240P-8GB-512GB-NVMe-GTX-1650-Ti-Win11-Home.jpg', 'Black', 25990000, 100, 'Laptop Acer Nitro 5 AN515-57 là sản phẩm laptop gaming tầm trung đến từ Acer, được trang bị chip Intel Core i5-1240P, card đồ họa NVIDIA GeForce GTX 1650 Ti, màn hình 15.6 inch Full HD, RAM 8GB và SSD 512GB.', 'Laptop Acer Nitro 5 AN515-57 có thiết kế trẻ trung, vỏ nhựa, cấu hình mạnh mẽ với chip Intel Core i5-1240P và card đồ họa NVIDIA GeForce GTX 1650 Ti, màn hình 15.6 inch Full HD, RAM 8GB và SSD 512GB. Đây là một sản phẩm laptop gaming tầm trung phù hợp với người dùng cần một chiếc máy có hiệu năng mạnh mẽ để chơi game, thiết kế đẹp mắt và giá thành hợp lý.', '1', '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(16, 160, 65, 'Laptop Lenovo Ideapad Gaming 3 15IMH05 15.6 inch (R5-5600H/8GB/512GB NVMe/GTX 1650/Win11 Home)', 'https://cdn.tgdd.vn/Products/Images/450x450/87624/Lenovo-Ideapad-Gaming-3-15IMH05-156-inch-R5-5600H-8GB-512GB-NVMe-GTX-1650-Win11-Home.jpg', 'Gray', 20990000, 100, 'Laptop Lenovo Ideapad Gaming 3 15IMH05 là sản phẩm laptop gaming giá rẻ đến từ Lenovo, được trang bị chip AMD Ryzen 5 5600H, card đồ họa NVIDIA GeForce GTX 1650, màn hình 15.6 inch Full HD, RAM 8GB và SSD 512GB.', 'Laptop Lenovo Ideapad Gaming 3 15IMH05 có thiết kế đơn giản, vỏ nhựa, cấu hình mạnh mẽ với chip AMD Ryzen 5 5600H và card đồ họa NVIDIA GeForce GTX 1650, màn hình 15.6 inch Full HD, RAM 8GB và SSD 512GB. Đây là một sản phẩm laptop gaming giá rẻ phù hợp với người dùng cần một chiếc máy có hiệu năng mạnh mẽ để chơi game, thiết kế đẹp mắt và giá thành hợp lý.', '1', '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(17, 160, 60, 'Laptop HP Pavilion Gaming 15-ec2070AX 15.6 inch (R5-5600H/8GB/512GB NVMe/GTX 1650/Win11 Home)', 'https://cdn.tgdd.vn/Products/Images/450x450/87625/HP-Pavilion-Gaming-15-ec2070AX-156-inch-R5-5600H-8GB-512GB-NVMe-GTX-1650-Win11-Home.jpg', 'Black', 22990000, 100, 'Laptop HP Pavilion Gaming 15-ec2070AX là sản phẩm laptop gaming tầm trung đến từ HP, được trang bị chip AMD Ryzen 5 5600H, card đồ họa NVIDIA GeForce GTX 1650, màn hình 15.6 inch Full HD, RAM 8GB và SSD 512GB.', 'Laptop HP Pavilion Gaming 15-ec2070AX có thiết kế trẻ trung, vỏ nhựa, cấu hình mạnh mẽ với chip AMD Ryzen 5 5600H và card đồ họa NVIDIA GeForce GTX 1650, màn hình 15.6 inch Full HD, RAM 8GB và SSD 512GB. Đây là một sản phẩm laptop gaming tầm trung phù hợp với người dùng cần một chiếc máy có hiệu năng mạnh mẽ để chơi game, thiết kế đẹp mắt và giá thành hợp lý.', '1', '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(18, 160, 63, 'Laptop Asus TUF Gaming A15 FA506QR-HF008T 15.6 inch (R7-5800H/16GB/512GB NVMe/RTX 3060/Win11 Home)', 'https://cdn.tgdd.vn/Products/Images/450x450/87626/Asus-TUF-Gaming-A15-FA506QR-HF008T-156-inch-R7-5800H-16GB-512GB-NVMe-RTX-3060-Win11-Home.jpg', 'Black', 30990000, 100, 'Laptop Asus TUF Gaming A15 FA506QR-HF008T là sản phẩm laptop gaming cao cấp đến từ Asus, được trang bị chip AMD Ryzen 7 5800H, card đồ họa NVIDIA GeForce RTX 3060, màn hình 15.6 inch Full HD, RAM 16GB và SSD 512GB.', 'Laptop Asus TUF Gaming A15 FA506QR-HF008T có thiết kế mạnh mẽ, vỏ nhựa, cấu hình mạnh mẽ với chip AMD Ryzen 7 5800H và card đồ họa NVIDIA GeForce RTX 3060, màn hình 15.6 inch Full HD, RAM 16GB và SSD 512GB. Đây là một sản phẩm laptop gaming cao cấp phù hợp với người dùng cần một chiếc máy có hiệu năng mạnh mẽ để chơi game, thiết kế đẹp mắt và thời lượng pin lâu.', '1', '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(19, 160, 69, 'Laptop MSI Creator 17M A11UE-257VN 17.3 inch (i7-11800H/16GB/512GB NVMe/RTX 3060/Win11 Home)', 'https://cdn.tgdd.vn/Products/Images/450x450/87627/MSI-Creator-17M-A11UE-257VN-173-inch-i7-11800H-16GB-512GB-NVMe-RTX-3060-Win11-Home.jpg', 'Silver', 35990000, 100, 'Laptop MSI Creator 17M A11UE-257VN là sản phẩm laptop chuyên đồ họa cao cấp đến từ MSI, được trang bị chip Intel Core i7-11800H, card đồ họa NVIDIA GeForce RTX 3060, màn hình 17.3 inch Full HD, RAM 16GB và SSD 512GB.', 'Laptop MSI Creator 17M A11UE-257VN có thiết kế sang trọng, vỏ kim loại nguyên khối, cấu hình mạnh mẽ với chip Intel Core i7-11800H và card đồ họa NVIDIA GeForce RTX 3060, màn hình 17.3 inch Full HD, RAM 16GB và SSD 512GB. Đây là một sản phẩm laptop chuyên đồ họa cao cấp phù hợp với người dùng cần một chiếc máy có hiệu năng mạnh mẽ để làm đồ họa, thiết kế đẹp mắt và thời lượng pin lâu.', '1', '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(20, 160, 70, 'Laptop Apple MacBook Pro M2 14.2 inch (M2/16GB/512GB SSD/MWP83SA/A)', 'https://cdn.tgdd.vn/Products/Images/450x450/87628/Apple-MacBook-Pro-M2-142-inch-M2-16GB-512GB-SSD-MWP83SA-A.jpg', 'Silver', 42990000, 100, 'Laptop Apple MacBook Pro M2 14.2 inch (M2/16GB/512GB SSD/MWP83SA/A) là sản phẩm laptop cao cấp đến từ Apple, được trang bị chip Apple M2, RAM 16GB và SSD 512GB.', 'Laptop Apple MacBook Pro M2 14.2 inch (M2/16GB/512GB SSD/MWP83SA/A) có thiết kế sang trọng, vỏ nhôm nguyên khối, cấu hình mạnh mẽ với chip Apple M2, RAM 16GB và SSD 512GB. Đây là một sản phẩm laptop cao cấp phù hợp với người dùng cần một chiếc máy có hiệu năng mạnh mẽ để làm việc, thiết kế đẹp mắt và thời lượng pin lâu.', '1', '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(21, 160, 63, 'Laptop ASUS ROG Zephyrus G14 GA401QM-AZ036T 14 inch (R9-5900HS/16GB/512GB NVMe/RTX 3060/Win11 Home)', 'https://cdn.tgdd.vn/Products/Images/450x450/87629/ASUS-ROG-Zephyrus-G14-GA401QM-AZ036T-14-inch-R9-5900HS-16GB-512GB-NVMe-RTX-3060-Win11-Home.jpg', 'Black', 45990000, 100, 'Laptop ASUS ROG Zephyrus G14 GA401QM-AZ036T là sản phẩm laptop gaming cao cấp đến từ ASUS, được trang bị chip AMD Ryzen 9 5900HS, card đồ họa NVIDIA GeForce RTX 3060, màn hình 14 inch Full HD, RAM 16GB và SSD 512GB.', 'Laptop ASUS ROG Zephyrus G14 GA401QM-AZ036T có thiết kế mỏng nhẹ, vỏ kim loại nguyên khối, cấu hình mạnh mẽ với chip AMD Ryzen 9 5900HS và card đồ họa NVIDIA GeForce RTX 3060, màn hình 14 inch Full HD, RAM 16GB và SSD 512GB. Đây là một sản phẩm laptop gaming cao cấp phù hợp với người dùng cần một chiếc máy có hiệu năng mạnh mẽ để chơi game, thiết kế đẹp mắt và thời lượng pin lâu.', '1', '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(22, 160, 65, 'Laptop Lenovo Legion 5 Pro 16ACH6H 16 inch (R7-5800H/16GB/512GB NVMe/RTX 3070/Win11 Home)', 'https://cdn.tgdd.vn/Products/Images/450x450/87630/Lenovo-Legion-5-Pro-16ACH6H-16-inch-R7-5800H-16GB-512GB-NVMe-RTX-3070-Win11-Home.jpg', 'Black', 55990000, 100, 'Laptop Lenovo Legion 5 Pro 16ACH6H là sản phẩm laptop gaming cao cấp đến từ Lenovo, được trang bị chip AMD Ryzen 7 5800H, card đồ họa NVIDIA GeForce RTX 3070, màn hình 16 inch Full HD, RAM 16GB và SSD 512GB.', 'Laptop Lenovo Legion 5 Pro 16ACH6H có thiết kế mạnh mẽ, vỏ nhựa, cấu hình mạnh mẽ với chip AMD Ryzen 7 5800H và card đồ họa NVIDIA GeForce RTX 3070, màn hình 16 inch Full HD, RAM 16GB và SSD 512GB. Đây là một sản phẩm laptop gaming cao cấp phù hợp với người dùng cần một chiếc máy có hiệu năng mạnh mẽ để chơi game, thiết kế đẹp mắt và thời lượng pin lâu.', '1', '2023-08-01 04:27:22', '2023-08-01 04:27:22');

-- --------------------------------------------------------

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `phone`, `address`, `email`, `avatar`, `last_login`, `facebook`, `status`, `created_at`, `updated_at`)
VALUES
(11, 'nguyễn văn a', '123456', 'Nguyễn', 'Văn A', '0912345678', 'Hà Nội', 'nguyenvana@gmail.com', 'https://i.pravatar.cc/123456', '2023-08-01 04:27:22', 'https://www.facebook.com/nguyenvana', '1', '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(12, 'nguyễn thị b', '123456', 'Nguyễn', 'Thị B', '0912345679', 'Hồ Chí Minh', 'nguyenthibb@gmail.com', 'https://i.pravatar.cc/123457', '2023-08-01 04:27:22', 'https://www.facebook.com/nguyenthibb', '1', '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(13, 'trần văn c', '123456', 'Trần', 'Văn C', '0912345680', 'Đà Nẵng', 'tranvanc@gmail.com', 'https://i.pravatar.cc/123458', '2023-08-01 04:27:22', 'https://www.facebook.com/tranvanc', '1', '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(14, 'pham thị d', '123456', 'Phạm', 'Thị D', '0912345681', 'Nghệ An', 'phamthid@gmail.com', 'https://i.pravatar.cc/123459', '2023-08-01 04:27:22', 'https://www.facebook.com/phamthid', '1', '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(15, 'lê văn e', '123456', 'Lê', 'Văn E', '0912345682', 'Quảng Bình', 'levanve@gmail.com', 'https://i.pravatar.cc/123460', '2023-08-01 04:27:22', 'https://www.facebook.com/levanv', '1', '2023-08-01 04:27:22', '2023-08-01 04:27:22');

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `fullname`, `address`, `mobile`, `email`, `note`, `price_total`, `payment_status`, `created_at`, `updated_at`)
VALUES
(1, 11, 'Nguyễn Văn A', 'Hà Nội', '0912345678', 'nguyenvana@gmail.com', 'Ghi chú của đơn hàng 1', 100000, 1, '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(2, 12, 'Nguyễn Thị B', 'Hồ Chí Minh', '0912345679', 'nguyenthibb@gmail.com', 'Ghi chú của đơn hàng 2', 200000, 1, '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(3, 13, 'Trần Văn C', 'Đà Nẵng', '0912345680', 'tranvanc@gmail.com', 'Ghi chú của đơn hàng 3', 300000, 1, '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(4, 14, 'Phạm Thị D', 'Nghệ An', '0912345681', 'phamthid@gmail.com', 'Ghi chú của đơn hàng 4', 400000, 1, '2023-08-01 04:27:22', '2023-08-01 04:27:22'),
(5, 15, 'Lê Văn E', 'Quảng Bình', '0912345682', 'levanve@gmail.com', 'Ghi chú của đơn hàng 5', 500000, 1, '2023-08-01 04:27:22', '2023-08-01 04:27:22');

-- --------------------------------------------------------

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;