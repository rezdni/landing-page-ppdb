-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 21, 2025 at 12:35 AM
-- Server version: 8.0.40-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

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
-- Table structure for table `dokumen_pendaftaran`
--

DROP TABLE IF EXISTS `dokumen_pendaftaran`;
CREATE TABLE `dokumen_pendaftaran` (
  `id_dokumen` int NOT NULL,
  `id_calon` int NOT NULL,
  `jenis_dokumen` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orang_tua`
--

DROP TABLE IF EXISTS `orang_tua`;
CREATE TABLE `orang_tua` (
  `id_orang_tua` int NOT NULL,
  `id_calon` int NOT NULL,
  `nama_orang_tua` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nomor_telepon_orang_tua` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pekerjaan_orang_tua` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_orang_tua` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran`
--

DROP TABLE IF EXISTS `pendaftaran`;
CREATE TABLE `pendaftaran` (
  `id_calon` int NOT NULL,
  `nama_calon_siswa` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `no_nis` int DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_general_ci NOT NULL,
  `agama` enum('Islam','Katolik','Kristen Protestan','Hindu','Budha','Konghucu') COLLATE utf8mb4_general_ci NOT NULL,
  `sekolah_asal` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kewarganegaraan` enum('WNI','WNA') COLLATE utf8mb4_general_ci NOT NULL,
  `golongan_darah` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_tinggal` text COLLATE utf8mb4_general_ci,
  `provinsi` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kota_kabupaten` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kecamatan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kelurahan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_post` int DEFAULT NULL,
  `tanggal_daftar` date NOT NULL,
  `no_telepon` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jurusan` enum('MIA (Matematika Ilmu Alam)','IIS (Ilmu Ilmu Sosial)','IPS (Ilmu Pengetahuan Sosial)','IPA (Ilmu Pengetahuan Alam)') COLLATE utf8mb4_general_ci NOT NULL,
  `gelombang` enum('1','2','3') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

DROP TABLE IF EXISTS `pengguna`;
CREATE TABLE `pengguna` (
  `id` int NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('Admin','Calon') COLLATE utf8mb4_general_ci NOT NULL,
  `id_calon` int DEFAULT NULL,
  `lupa_sandi` tinyint(1) NOT NULL,
  `sesi` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama`, `email`, `password`, `role`, `id_calon`, `lupa_sandi`, `sesi`) VALUES
(2, 'Reza Dani Pramudya', 'reza@admin', '$2y$10$nsQjcCGqzbqFjzZBFNTGk.5MNwvILoiskFhm.QRKQ6QVW2mDW0RH2', 'Admin', NULL, 0, ''),
(8, 'Shiroko', 'shiroko@abydos', '$2y$10$7kqiA8Z7QIXEPA3YL5qJtu/iD4RCo3rtHN5zLI5mhBP0ZMAPlIoxa', 'Admin', NULL, 0, '$2y$10$LDC7/sWdlkqssrjA4TNkwuasrBdU/eFeDFgP.Oj6FOlUcPurwWiai'),
(9, 'Sunaookami Shiroko', 'kuroko@abydos', '$2y$10$PSXvoiJtAZI8iDarvp.Phui2c13NnJtuo1.TtjORj8Rw/wWTN9xvu', 'Calon', NULL, 0, '$2y$10$Pqz3nJQTQcKlXQuxDJeqseck03/fO6pTh7YeEST055O3XEnxQVhga');

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
-- Indexes for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`id_calon`),
  ADD UNIQUE KEY `no_nis` (`no_nis`);

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
-- AUTO_INCREMENT for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `id_calon` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  ADD CONSTRAINT `dokumen_pendaftaran_ibfk_1` FOREIGN KEY (`id_calon`) REFERENCES `pendaftaran` (`id_calon`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orang_tua`
--
ALTER TABLE `orang_tua`
  ADD CONSTRAINT `orang_tua_ibfk_1` FOREIGN KEY (`id_calon`) REFERENCES `pendaftaran` (`id_calon`) ON DELETE CASCADE;

--
-- Constraints for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `pengguna_ibfk_1` FOREIGN KEY (`id_calon`) REFERENCES `pendaftaran` (`id_calon`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
