-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-09-10 01:38:08
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `contract_management_db`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `contracts`
--

CREATE TABLE `contracts` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `contract_title` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `contract_period` date DEFAULT NULL,
  `initiator` varchar(255) DEFAULT NULL,
  `inspector` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `contracts`
--

INSERT INTO `contracts` (`id`, `company_name`, `contract_title`, `amount`, `contract_period`, `initiator`, `inspector`, `created_at`, `updated_at`) VALUES
(1, 'acn', 'test name', 1000000.00, '2024-09-19', 'kimura', 'ogata', '2024-09-04 14:39:21', '2024-09-04 14:39:21'),
(2, 'acn', 'test name', 1000000.00, '2024-09-19', 'kimura', 'ogata', '2024-09-04 14:48:27', '2024-09-04 14:48:27'),
(3, 'OO会社', 'OO契約', 0.00, '2024-10-01', '木村', '片山', '2024-09-09 23:29:35', '2024-09-09 23:29:35');

-- --------------------------------------------------------

--
-- テーブルの構造 `contract_status`
--

CREATE TABLE `contract_status` (
  `id` int(11) NOT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `document_preparation` varchar(255) DEFAULT NULL,
  `application_status` varchar(255) DEFAULT NULL,
  `seal_status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `contract_status`
--

INSERT INTO `contract_status` (`id`, `contract_id`, `document_preparation`, `application_status`, `seal_status`, `created_at`, `updated_at`) VALUES
(1, 1, '契約書見積書あり', '代行申請依頼済み', '未実施', '2024-09-04 15:00:43', '2024-09-04 15:00:43'),
(2, 3, '未実施', '未実施', '未実施', '2024-09-09 23:29:54', '2024-09-09 23:29:54');

-- --------------------------------------------------------

--
-- テーブルの構造 `inspection_status`
--

CREATE TABLE `inspection_status` (
  `id` int(11) NOT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `invoice_preparation` varchar(255) DEFAULT NULL,
  `inspection_status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `inspection_status`
--

INSERT INTO `inspection_status` (`id`, `contract_id`, `invoice_preparation`, `inspection_status`, `created_at`, `updated_at`) VALUES
(1, 1, '書類依頼済み', '未実施', '2024-09-04 14:53:17', '2024-09-04 15:00:59');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `contract_status`
--
ALTER TABLE `contract_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_id` (`contract_id`);

--
-- テーブルのインデックス `inspection_status`
--
ALTER TABLE `inspection_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_id` (`contract_id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- テーブルの AUTO_INCREMENT `contract_status`
--
ALTER TABLE `contract_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- テーブルの AUTO_INCREMENT `inspection_status`
--
ALTER TABLE `inspection_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `contract_status`
--
ALTER TABLE `contract_status`
  ADD CONSTRAINT `contract_status_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`);

--
-- テーブルの制約 `inspection_status`
--
ALTER TABLE `inspection_status`
  ADD CONSTRAINT `inspection_status_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
