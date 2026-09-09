-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 07, 2026 at 08:04 AM
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
-- Database: `praktikum11`
--

-- --------------------------------------------------------

--
-- Table structure for table `databuku`
--

CREATE TABLE `databuku` (
  `id_buku` int(5) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `penulis` varchar(255) NOT NULL,
  `kategori` varchar(30) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dataguru`
--

CREATE TABLE `dataguru` (
  `id` int(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `mapel` varchar(50) NOT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dataguru`
--

INSERT INTO `dataguru` (`id`, `nama`, `nip`, `mapel`, `alamat`, `foto`) VALUES
(1, 'Abigail', '00001', 'Bahasa Inggris', 'New York', '9.png'),
(2, 'Oyon', '00002', 'Bahasa Indonesia', 'sawah kulon', 'oyonimut.jpg'),
(3, 'Dadan M. Ramdan', '00003', 'Bahasa Indonesia', 'Depan SMPN 2 Paseh', 'oyondora.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `datakelas`
--

CREATE TABLE `datakelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(100) NOT NULL,
  `tingkat` enum('X','XI','XII') NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `id_guru` int(11) DEFAULT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `id_ruang` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datakelas`
--

INSERT INTO `datakelas` (`id_kelas`, `nama_kelas`, `tingkat`, `tahun_ajaran`, `id_guru`, `id_siswa`, `id_ruang`) VALUES
(1, '', 'XII', '', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dataruang`
--

CREATE TABLE `dataruang` (
  `id_ruang` int(11) NOT NULL,
  `nama_ruang` varchar(100) NOT NULL,
  `kapasitas` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dataruang`
--

INSERT INTO `dataruang` (`id_ruang`, `nama_ruang`, `kapasitas`, `keterangan`, `foto`) VALUES
(1, 'Ruang 1', 40, 'Di gedung 1, di sebelah ruang 2', 'alma2045.jpg'),
(2, 'Ruang 2', 40, 'Gedung 1 di sebelah ruang 1 dan ruang 3', 'alma2045.jpg'),
(3, 'Ruang 3', 40, 'Di gedung 1, di sebelah ruang 2 dan ruang 4', 'alma2045.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `datasiswa`
--

CREATE TABLE `datasiswa` (
  `id` int(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `kelas` enum('X','XI','XII') NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `status` enum('aktif','tidak aktif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datasiswa`
--

INSERT INTO `datasiswa` (`id`, `nama`, `kelas`, `tanggal_lahir`, `alamat`, `foto`, `status`) VALUES
(1, 'nadnad', 'XII', '2008-10-31', 'jln. setiabudi, bandung', 'nadira.3.jpeg', 'aktif'),
(2, 'Oyon jamet', 'XII', '2009-01-13', 'jekardah', 'foto oyon jamet.jpeg', 'aktif'),
(4, 'salwaufairahmabaunpad27', 'XII', '2009-06-27', 'sawah kulon', 'salwa.jpeg', 'aktif'),
(5, 'Oyon kecil', 'XII', '2026-08-24', 'sawah kulon', 'oyonpakyu.jpg', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `id_guru` int(5) DEFAULT NULL,
  `id_siswa` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`, `id_guru`, `id_siswa`) VALUES
(2, 'abdul', 'user123', 'Abdul', 'admin', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `databuku`
--
ALTER TABLE `databuku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `dataguru`
--
ALTER TABLE `dataguru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `datakelas`
--
ALTER TABLE `datakelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `datakelas_ibfk_1` (`id_ruang`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `dataruang`
--
ALTER TABLE `dataruang`
  ADD PRIMARY KEY (`id_ruang`);

--
-- Indexes for table `datasiswa`
--
ALTER TABLE `datasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_guru` (`id_guru`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `databuku`
--
ALTER TABLE `databuku`
  MODIFY `id_buku` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dataguru`
--
ALTER TABLE `dataguru`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `datakelas`
--
ALTER TABLE `datakelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dataruang`
--
ALTER TABLE `dataruang`
  MODIFY `id_ruang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `datasiswa`
--
ALTER TABLE `datasiswa`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `datakelas`
--
ALTER TABLE `datakelas`
  ADD CONSTRAINT `datakelas_ibfk_1` FOREIGN KEY (`id_ruang`) REFERENCES `dataruang` (`id_ruang`),
  ADD CONSTRAINT `datakelas_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `datasiswa` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `datasiswa` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`id_guru`) REFERENCES `dataguru` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
