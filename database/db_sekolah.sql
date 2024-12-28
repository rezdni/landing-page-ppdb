-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 28, 2024 at 03:57 PM
-- Server version: 8.0.39
-- PHP Version: 8.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sekolah`
--
CREATE DATABASE IF NOT EXISTS `db_sekolah` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_sekolah`;

-- --------------------------------------------------------

--
-- Table structure for table `calon_siswa`
--

DROP TABLE IF EXISTS `calon_siswa`;
CREATE TABLE `calon_siswa` (
  `id_calon` int NOT NULL,
  `nama_calon_siswa` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `no_nis` int DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_general_ci NOT NULL,
  `agama` enum('Islam','Katolik','Kristen Protestan','Hindu','Budha','Konghucu') COLLATE utf8mb4_general_ci NOT NULL,
  `sekolah_asal` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kewarganegaraan` enum('WNI','WNA') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `golongan_darah` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_tinggal` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `provinsi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kota_kabupaten` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kecamatan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kelurahan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_post` int DEFAULT NULL,
  `tanggal_daftar` date NOT NULL,
  `no_telepon` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_pendaftaran`
--

DROP TABLE IF EXISTS `dokumen_pendaftaran`;
CREATE TABLE `dokumen_pendaftaran` (
  `id_dokumen` int NOT NULL,
  `id_calon` int NOT NULL,
  `jenis_dokumen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orang_tua`
--

DROP TABLE IF EXISTS `orang_tua`;
CREATE TABLE `orang_tua` (
  `id_orang_tua` int NOT NULL,
  `id_calon` int NOT NULL,
  `nama_orang_tua` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nomor_telepon_orang_tua` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pekerjaan_orang_tua` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_orang_tua` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

DROP TABLE IF EXISTS `pengguna`;
CREATE TABLE `pengguna` (
  `id` int NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('Admin','Calon') COLLATE utf8mb4_general_ci NOT NULL,
  `id_calon` int DEFAULT NULL,
  `lupa_sandi` tinyint(1) NOT NULL,
  `sesi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama`, `email`, `password`, `role`, `id_calon`, `lupa_sandi`, `sesi`) VALUES
(2, 'reza', 'reza@admin', '$2y$10$MH.pJmjlIVkbpL8/LQx38eXYvEK75h3Eh30cdMfQQcz8aQ8UyByfG', 'Admin', NULL, 0, ''),
(3, 'zaki', 'zaki@user', '$2y$10$8o5uvT2Og7FFzFxZhk3aL.r.d9jcF6nELHFuQffslffzu/yTnGxXa', 'Calon', NULL, 0, ''),
(4, 'Raiden Mei', 'mei@raiden', '$2y$10$TJEAZVPuJn502rOpgx1lxO6nXeuRGLwfMNYthI8Gv971LD6./wS7u', 'Admin', NULL, 0, ''),
(6, 'andika', 'andika@raharja', '$2y$10$D11nkrRxN/vnJxGCdXysr.b790uQqdP2mO697V9gx4785rdbmsKuy', 'Calon', NULL, 0, NULL),
(7, 'fadel', 'fadel@raharja', '$2y$10$26eMYsMv2ehr6Ab.eg6L7e/GUGftFcldYnJLnppDcWOyjuV9qK5by', 'Calon', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

DROP TABLE IF EXISTS `pengumuman`;
CREATE TABLE `pengumuman` (
  `id_pengumuman` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `isi_pengumuman` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calon_siswa`
--
ALTER TABLE `calon_siswa`
  ADD PRIMARY KEY (`id_calon`);

--
-- Indexes for table `dokumen_pendaftaran`
--
ALTER TABLE `dokumen_pendaftaran`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `id_calon` (`id_calon`);

--
-- Indexes for table `orang_tua`
--
ALTER TABLE `orang_tua`
  ADD PRIMARY KEY (`id_orang_tua`),
  ADD KEY `id_calon` (`id_calon`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_calon` (`id_calon`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id_pengumuman`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calon_siswa`
--
ALTER TABLE `calon_siswa`
  MODIFY `id_calon` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dokumen_pendaftaran`
--
ALTER TABLE `dokumen_pendaftaran`
  MODIFY `id_dokumen` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orang_tua`
--
ALTER TABLE `orang_tua`
  MODIFY `id_orang_tua` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id_pengumuman` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dokumen_pendaftaran`
--
ALTER TABLE `dokumen_pendaftaran`
  ADD CONSTRAINT `dokumen_pendaftaran_ibfk_1` FOREIGN KEY (`id_calon`) REFERENCES `calon_siswa` (`id_calon`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orang_tua`
--
ALTER TABLE `orang_tua`
  ADD CONSTRAINT `orang_tua_ibfk_1` FOREIGN KEY (`id_calon`) REFERENCES `calon_siswa` (`id_calon`) ON DELETE CASCADE;

--
-- Constraints for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `pengguna_ibfk_1` FOREIGN KEY (`id_calon`) REFERENCES `calon_siswa` (`id_calon`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
