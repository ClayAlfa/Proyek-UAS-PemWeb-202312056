-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Jul 2025 pada 19.13
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bus_management`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `timestamp`) VALUES
(2, 1, 'Login', 'User logged in', '2025-07-13 08:19:19'),
(3, 1, 'Add User', 'User created: yusuf', '2025-07-13 08:20:42'),
(4, 1, 'Add Route', 'Route created: bontang to samarinda', '2025-07-13 08:20:59'),
(5, 1, 'Edit Route', 'Route updated: bontang to samarinda', '2025-07-13 08:21:11'),
(6, 1, 'Logout', 'User logged out', '2025-07-13 08:21:23'),
(7, 3, 'Login', 'User logged in', '2025-07-13 08:21:25'),
(8, NULL, 'Booking', 'Ticket booked for seat 2 on schedule 2', '2025-07-13 08:22:08'),
(9, 3, 'Logout', 'User logged out', '2025-07-13 08:22:46'),
(10, 1, 'Login', 'User logged in', '2025-07-13 08:22:52'),
(11, 1, 'Update Payment', 'Payment status updated to paid for transaction 1', '2025-07-13 08:23:12'),
(12, 1, 'Logout', 'User logged out', '2025-07-13 08:25:21'),
(13, 3, 'Login', 'User logged in', '2025-07-13 08:25:25'),
(14, 3, 'Logout', 'User logged out', '2025-07-13 08:25:53'),
(15, 1, 'Login', 'User logged in', '2025-07-13 08:25:59'),
(16, 1, 'Add Schedule', 'Schedule created for route ID: 4', '2025-07-13 08:31:59'),
(17, 1, 'Logout', 'User logged out', '2025-07-13 08:32:45'),
(18, 1, 'Login', 'User logged in', '2025-07-13 08:32:52'),
(19, 1, 'Logout', 'User logged out', '2025-07-13 08:32:59'),
(20, 2, 'Login', 'User logged in', '2025-07-13 08:33:09'),
(21, 2, 'Logout', 'User logged out', '2025-07-13 08:33:31'),
(22, 1, 'Login', 'User logged in', '2025-07-13 08:34:18'),
(23, 1, 'Logout', 'User logged out', '2025-07-13 11:40:34'),
(24, 1, 'Login', 'User logged in', '2025-07-13 11:41:23'),
(25, 1, 'Login', 'User logged in', '2025-07-15 12:58:23'),
(26, 1, 'Add User', 'User created: ita', '2025-07-15 12:59:06'),
(27, 1, 'Logout', 'User logged out', '2025-07-15 12:59:09'),
(28, 4, 'Login', 'User logged in', '2025-07-15 12:59:13'),
(29, NULL, 'Booking', 'Ticket booked for seat 7 on schedule 1', '2025-07-15 12:59:57'),
(30, 4, 'Logout', 'User logged out', '2025-07-15 13:01:01'),
(31, 1, 'Login', 'User logged in', '2025-07-15 13:01:09'),
(32, 1, 'Update Payment', 'Payment status updated to paid for transaction 2', '2025-07-15 13:01:35'),
(33, 1, 'Update Payment', 'Payment status updated to failed for transaction 2', '2025-07-15 13:01:37'),
(34, 1, 'Logout', 'User logged out', '2025-07-15 13:01:40'),
(35, 4, 'Login', 'User logged in', '2025-07-15 13:01:46'),
(36, 4, 'Logout', 'User logged out', '2025-07-15 13:02:18'),
(37, 1, 'Login', 'User logged in', '2025-07-15 13:02:29'),
(38, 1, 'Login', 'User logged in', '2025-07-16 11:43:31'),
(39, 1, 'Login', 'User logged in', '2025-07-23 01:30:44'),
(40, 1, 'Logout', 'User logged out', '2025-07-23 01:30:58'),
(41, NULL, 'Booking', 'Ticket booked for seat 25 on schedule 1', '2025-07-23 01:39:31'),
(42, 1, 'Login', 'User logged in', '2025-07-23 02:00:55'),
(43, 1, 'Logout', 'User logged out', '2025-07-23 02:00:58'),
(44, 1, 'Login', 'User logged in', '2025-07-23 03:37:36'),
(45, 1, 'Logout', 'User logged out', '2025-07-23 03:55:46'),
(46, 1, 'Login', 'User logged in', '2025-07-23 04:04:57'),
(47, 1, 'Logout', 'User logged out', '2025-07-23 04:04:59'),
(48, 2, 'Login', 'User logged in', '2025-07-23 04:05:04'),
(49, 1, 'Logout', 'User logged out', '2025-07-23 16:54:18'),
(50, 1, 'Login', 'User logged in', '2025-07-24 13:29:32'),
(51, 1, 'Logout', 'User logged out', '2025-07-24 13:29:41'),
(52, 2, 'Login', 'User logged in', '2025-07-24 13:29:45'),
(53, 2, 'Logout', 'User logged out', '2025-07-24 13:30:05'),
(54, 1, 'Login', 'User logged in', '2025-07-24 21:02:56'),
(55, 1, 'Logout', 'User logged out', '2025-07-24 21:03:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `buses`
--

CREATE TABLE `buses` (
  `id` int(11) NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `seat_count` int(11) NOT NULL,
  `status` enum('available','maintenance') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buses`
--

INSERT INTO `buses` (`id`, `plate_number`, `brand`, `seat_count`, `status`) VALUES
(1, 'B 1234 CD', 'Mercedes-Benz', 40, 'available'),
(2, 'B 5678 EF', 'Isuzu', 35, 'available'),
(3, 'B 9012 GH', 'Hino', 45, 'maintenance');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bus_driver`
--

CREATE TABLE `bus_driver` (
  `id` int(11) NOT NULL,
  `bus_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bus_driver`
--

INSERT INTO `bus_driver` (`id`, `bus_id`, `driver_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `license_number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `drivers`
--

INSERT INTO `drivers` (`id`, `name`, `phone`, `license_number`) VALUES
(1, 'Budi Santoso', '08123456789', 'SIM123456'),
(2, 'Ahmad Wijaya', '08234567890', 'SIM234567'),
(3, 'Siti Nurhaliza', '08345678901', 'SIM345678');

-- --------------------------------------------------------

--
-- Struktur dari tabel `passengers`
--

CREATE TABLE `passengers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `passengers`
--

INSERT INTO `passengers` (`id`, `name`, `email`, `phone`) VALUES
(1, 'Muhammad Yusuf Saputra', 'yusuf@gmail.com (booked by: yusuf)', '012830182301'),
(2, 'Blabla', 'sekian@gmail.com (booked by: ita)', '0812'),
(3, 'sd', 'dev.xyzuniverse@gmail.com (booked by: )', '23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `routes`
--

CREATE TABLE `routes` (
  `id` int(11) NOT NULL,
  `origin` varchar(100) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `distance_km` int(11) NOT NULL,
  `estimated_time` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `routes`
--

INSERT INTO `routes` (`id`, `origin`, `destination`, `distance_km`, `estimated_time`) VALUES
(1, 'Jakarta', 'Bandung', 150, '3 jam'),
(2, 'Jakarta', 'Surabaya', 800, '12 jam'),
(3, 'Bandung', 'Yogyakarta', 450, '8 jam'),
(4, 'bontang', 'samarinda', 20, '3 jam');

-- --------------------------------------------------------

--
-- Struktur dari tabel `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `bus_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `departure_time` datetime NOT NULL,
  `arrival_time` datetime NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `schedules`
--

INSERT INTO `schedules` (`id`, `bus_id`, `route_id`, `departure_time`, `arrival_time`, `price`) VALUES
(1, 1, 1, '2024-01-15 08:00:00', '2024-01-15 11:00:00', 75000.00),
(2, 2, 2, '2024-01-15 20:00:00', '2024-01-16 08:00:00', 250000.00),
(3, 1, 3, '2024-01-16 09:00:00', '2024-01-16 17:00:00', 150000.00),
(5, 2, 4, '2025-07-14 08:31:00', '2025-07-14 11:31:00', 200000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `seat_number` varchar(10) NOT NULL,
  `status` enum('booked','cancelled') DEFAULT 'booked'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tickets`
--

INSERT INTO `tickets` (`id`, `schedule_id`, `passenger_id`, `seat_number`, `status`) VALUES
(1, 2, 1, '2', 'booked'),
(2, 1, 2, '7', 'booked'),
(3, 1, 3, '25', 'booked');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` enum('paid','pending','failed') DEFAULT 'pending',
  `payment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `ticket_id`, `payment_method`, `payment_status`, `payment_date`) VALUES
(1, 1, 'Cash', 'paid', '2025-07-13 08:22:08'),
(2, 2, 'Cash', 'failed', '2025-07-15 12:59:57'),
(3, 3, 'Bank Transfer', 'pending', '2025-07-23 01:39:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin123', 'admin', '2025-07-13 08:18:43'),
(2, 'user', 'user123', 'operator', '2025-07-13 08:18:43'),
(3, 'yusuf', '202cb962ac59075b964b07152d234b70', 'user', '2025-07-13 08:20:42'),
(4, 'ita', '202cb962ac59075b964b07152d234b70', 'user', '2025-07-15 12:59:06');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plate_number` (`plate_number`);

--
-- Indeks untuk tabel `bus_driver`
--
ALTER TABLE `bus_driver`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indeks untuk tabel `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `license_number` (`license_number`);

--
-- Indeks untuk tabel `passengers`
--
ALTER TABLE `passengers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `route_id` (`route_id`);

--
-- Indeks untuk tabel `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `passenger_id` (`passenger_id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT untuk tabel `buses`
--
ALTER TABLE `buses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `bus_driver`
--
ALTER TABLE `bus_driver`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `passengers`
--
ALTER TABLE `passengers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `bus_driver`
--
ALTER TABLE `bus_driver`
  ADD CONSTRAINT `bus_driver_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`),
  ADD CONSTRAINT `bus_driver_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`);

--
-- Ketidakleluasaan untuk tabel `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`),
  ADD CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`);

--
-- Ketidakleluasaan untuk tabel `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`),
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`passenger_id`) REFERENCES `passengers` (`id`);

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
