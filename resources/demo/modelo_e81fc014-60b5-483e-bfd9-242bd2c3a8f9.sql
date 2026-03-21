-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 02, 2026 at 10:57 AM
-- Server version: 8.0.44-0ubuntu0.24.04.2
-- PHP Version: 8.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `modelo_e81fc014-60b5-483e-bfd9-242bd2c3a8f9`
--

-- --------------------------------------------------------

--
-- Table structure for table `anexo`
--

CREATE TABLE `anexo` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `arquivo` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extensao` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dominio`
--

CREATE TABLE `dominio` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_dominio_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `anexo_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dominio` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `navegacao_opc` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `navegacao_subopc` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icone` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fonte_cor` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bg_cor` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dominio`
--

INSERT INTO `dominio` (`id`, `tipo_dominio_id`, `anexo_id`, `dominio`, `navegacao_opc`, `navegacao_subopc`, `icone`, `fonte_cor`, `bg_cor`, `ativo`, `created_at`, `updated_at`) VALUES
('10e1f003-fedf-11f0-b1c6-5891cfde019d', 'c090a69c-fe15-11f0-b1c6-5891cfde019d', NULL, 'Agrale', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
('1eadffc7-fedf-11f0-b1c6-5891cfde019d', 'c090a69c-fe15-11f0-b1c6-5891cfde019d', NULL, 'Massey Ferguson', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
('1eae0654-fedf-11f0-b1c6-5891cfde019d', 'c090a69c-fe15-11f0-b1c6-5891cfde019d', NULL, 'Valtra', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
('2c0895d8-fedf-11f0-b1c6-5891cfde019d', 'c090a69c-fe15-11f0-b1c6-5891cfde019d', NULL, 'Mahindra', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
('2c089b2d-fedf-11f0-b1c6-5891cfde019d', 'c090a69c-fe15-11f0-b1c6-5891cfde019d', NULL, 'John Deere', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
('3447c1eb-fedf-11f0-b1c6-5891cfde019d', 'c090a69c-fe15-11f0-b1c6-5891cfde019d', NULL, 'Case IH', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
('3447c6c2-fedf-11f0-b1c6-5891cfde019d', 'c090a69c-fe15-11f0-b1c6-5891cfde019d', NULL, 'LS Tractor', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
('3cf5c61b-fedf-11f0-b1c6-5891cfde019d', 'c090a69c-fe15-11f0-b1c6-5891cfde019d', NULL, 'Fendt', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
('51308657-fedf-11f0-b1c6-5891cfde019d', 'c090a69c-fe15-11f0-b1c6-5891cfde019d', NULL, 'New Holland', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
('62553b98-fedf-11f0-b1c6-5891cfde019d', 'c090a69c-fe15-11f0-b1c6-5891cfde019d', NULL, 'Stara', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
('62554081-fedf-11f0-b1c6-5891cfde019d', 'c090a69c-fe15-11f0-b1c6-5891cfde019d', NULL, 'Yanmar', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
('6a929fb1-fedf-11f0-b1c6-5891cfde019d', 'c090a69c-fe15-11f0-b1c6-5891cfde019d', NULL, 'Landini', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
('6a92a63d-fedf-11f0-b1c6-5891cfde019d', 'c090a69c-fe15-11f0-b1c6-5891cfde019d', NULL, 'Budny', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
('7871c676-fedf-11f0-b1c6-5891cfde019d', 'c090a69c-fe15-11f0-b1c6-5891cfde019d', NULL, 'Tramontini', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
('bc4d3436-ff76-11f0-b311-5891cfde019d', 'ad141e53-ff76-11f0-b311-5891cfde019d', NULL, 'Trator', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
('bc4d3932-ff76-11f0-b311-5891cfde019d', 'ad141e53-ff76-11f0-b311-5891cfde019d', NULL, 'Colheitadeira', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '2026_01_29_023810_create_propriedade_table', 1),
(6, '2026_01_29_213103_create_tipo_dominio_table', 2),
(7, '2026_01_30_183501_create_anexo_table', 2),
(8, '2026_01_30_183806_create_dominio_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `propriedade`
--

CREATE TABLE `propriedade` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `propriedade`
--

INSERT INTO `propriedade` (`id`, `nome`, `created_at`, `updated_at`) VALUES
('08acafc7-fcbc-11f0-b414-5891cfde019d', 'teste', NULL, NULL),
('08acb554-fcbc-11f0-b414-5891cfde019d', 'teste2', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tipo_dominio`
--

CREATE TABLE `tipo_dominio` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_dominio` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `navegacao_opc` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `navegacao_subopc` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rota` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publico` tinyint(1) DEFAULT NULL,
  `datasource` tinyint(1) NOT NULL DEFAULT '1',
  `icone` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fonte_cor` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bg_cor` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `subtitulo` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tipo_dominio`
--

INSERT INTO `tipo_dominio` (`id`, `tipo_dominio`, `navegacao_opc`, `navegacao_subopc`, `rota`, `publico`, `datasource`, `icone`, `fonte_cor`, `bg_cor`, `ativo`, `subtitulo`, `created_at`, `updated_at`) VALUES
('ad141e53-ff76-11f0-b311-5891cfde019d', 'Tipo de equipamento', 'cadastro', NULL, '/dominio/{id}', NULL, 1, NULL, NULL, NULL, 1, 'Tipos de equipamento', NULL, NULL),
('c090a69c-fe15-11f0-b1c6-5891cfde019d', 'Fabricantes', 'cadastro', NULL, '/dominio/{id}', NULL, 1, NULL, NULL, NULL, 1, 'Lista de fabricantes', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
('d04c992c-531a-4f5e-a007-0d975152f6c2', 'Client User', 'client@localhost.com', NULL, '$2y$12$MIDbq6LOX8kHUXbZ4vR.z.q9yIWewD/wiL9GctY5VDLiWEdZwZkEy', NULL, '2026-01-31 23:03:00', '2026-01-31 23:03:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anexo`
--
ALTER TABLE `anexo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dominio`
--
ALTER TABLE `dominio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dominio_dominio_unique` (`dominio`),
  ADD KEY `dominio_tipo_dominio_id_index` (`tipo_dominio_id`),
  ADD KEY `dominio_anexo_id_index` (`anexo_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `propriedade`
--
ALTER TABLE `propriedade`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tipo_dominio`
--
ALTER TABLE `tipo_dominio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipo_dominio_tipo_dominio_unique` (`tipo_dominio`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dominio`
--
ALTER TABLE `dominio`
  ADD CONSTRAINT `dominio_anexo_id_foreign` FOREIGN KEY (`anexo_id`) REFERENCES `anexo` (`id`),
  ADD CONSTRAINT `dominio_tipo_dominio_id_foreign` FOREIGN KEY (`tipo_dominio_id`) REFERENCES `tipo_dominio` (`id`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
