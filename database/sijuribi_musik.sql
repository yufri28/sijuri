-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 25 Jul 2024 pada 15.10
-- Versi server: 10.6.18-MariaDB-cll-lve
-- Versi PHP: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sijuribi_musik`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `nama_alternatif` varchar(200) NOT NULL,
  `musica_sacra` varchar(200) NOT NULL,
  `traditional_gospel` varchar(200) NOT NULL,
  `periode_penilaian` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `nama_alternatif`, `musica_sacra`, `traditional_gospel`, `periode_penilaian`) VALUES
(1, 'PSM Universitas Atma Jaya Yogyakarta', 'Lagu A', 'Lagu B', '2024 Oktober'),
(2, 'PSM Universitas Negeri Yogyakarta', 'Lagu C', 'Lagu D', '2024 Oktober'),
(4, 'PSM Universitas Sanata Dharma', '', '', '2024 Oktober'),
(5, 'PSM Universitas Pembangunan Nasional Veteran Yogyakarta', '', '', '2024 Oktober'),
(7, 'PSM Universitas Bina Nusantara', '', '', '2024 Oktober'),
(8, 'PSM Universitas Indonesia', '', '', '2024 Oktober'),
(11, 'PSM Institut Seni Budaya Indonesia Bandung', '', '', '2024 Oktober'),
(13, 'PSM Institut Teknologi Nasional Bandung', '', '', '2024 Oktober'),
(14, 'PSM Universitas Kristen Maranatha', '', '', '2024 Oktober'),
(15, 'PSM Universitas Kristen Satya Wacana', '', '', '2024 Oktober'),
(16, 'PSM Universitas Diponegoro', '', '', '2024 Oktober'),
(17, 'PSM Universitas Sebelas Maret', '', '', '2024 Oktober'),
(18, 'PSM Universitas Brawijaya', '', '', '2024 Oktober'),
(19, 'PSM Institut Teknologi Sepuluh Nopember', '', '', '2024 Oktober'),
(20, 'PSM Universitas Surabaya', '', '', '2024 Oktober'),
(21, 'PSM Universitas 17 Agustus 1945 Surabaya', '', '', '2024 Oktober'),
(22, 'PSM Universitas Negeri Surabaya', '', '', '2024 Oktober'),
(23, 'PSM Universitas Tanjungpura', '', '', '2024 Oktober'),
(24, 'PSM Universitas Lambung Mangkurat', '', '', '2024 Oktober'),
(25, 'PSM Universitas Palangka Raya', '', '', '2024 Oktober'),
(26, 'PSM Universitas LelemukuSaumlaki', '', '', '2024 Oktober'),
(27, 'PSM Universitas Kristen Indonesia Maluku', '', '', '2024 Oktober'),
(28, 'PSM Universitas Pattimura', '', '', '2024 Oktober'),
(29, 'PSM Universitas Katolik Indonesia Santu Paulus Ruteng', '', '', '2024 Oktober'),
(30, 'PSM Universitas Timor', '', '', '2024 Oktober'),
(31, 'PSM Universitas Nusa Cendana', '', '', '2024 Oktober'),
(32, 'PSM Universitas Katolik Widya Mandira Kupang', '', '', '2024 Oktober'),
(33, 'PSM Universitas Kristen Artha Wacana', '', '', '2024 Oktober'),
(34, 'PSM Universitas Cenderawasih', '', '', '2024 Oktober'),
(35, 'SM Sekolah Tinggi Ilmu Ekonomi Mah-eisa', '', '', '2024 Oktober'),
(36, 'PSM Sekolah Tinggi Ilmu Hukum Caritas Papua', '', '', '2024 Oktober'),
(37, 'PSM Universitas Musamus Merauke', '', '', '2024 Oktober'),
(38, 'PSM Universitas Negeri Makassar', '', '', '2024 Oktober'),
(39, 'PSM Sekolah Tinggi Filsafat Jaffray Makassar', '', '', '2024 Oktober'),
(40, 'PSM Universitas Tadulako', '', '', '2024 Oktober'),
(41, 'PSM Universitas Negeri Manado', '', '', '2024 Oktober'),
(42, 'PSM Universitas Sam Ratulangi', '', '', '2024 Oktober'),
(43, 'PSM Universitas Kristen Indonesia Tomohon', '', '', '2024 Oktober'),
(44, 'PSM Universitas HKBP Nommensen', '', '', '2024 Oktober'),
(45, 'PSM Universitas Sumatera Utara', '', '', '2024 Oktober'),
(46, 'PSM Universitas Negeri Medan', 'Lagu X', 'Lagu Z', '2024 Oktober');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `kode_kriteria` varchar(50) NOT NULL,
  `nama_kriteria` varchar(150) NOT NULL,
  `ket_kriteria` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `kode_kriteria`, `nama_kriteria`, `ket_kriteria`) VALUES
(2, 'K1', 'Musica Sacra', 'Keterangan untuk lagu pertama'),
(4, 'K2', 'Traditional Gospel', 'Keterangan untuk lagu kedua');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaian`
--

CREATE TABLE `penilaian` (
  `id_penilaian` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `id_alternatif` int(11) NOT NULL,
  `id_periode` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `subkriteria_id` int(11) NOT NULL,
  `nilai` int(10) NOT NULL,
  `nilai_akhir` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penilaian`
--

INSERT INTO `penilaian` (`id_penilaian`, `id`, `id_alternatif`, `id_periode`, `id_kriteria`, `subkriteria_id`, `nilai`, `nilai_akhir`) VALUES
(59, 2, 1, 1, 2, 1, 88, 87),
(60, 2, 1, 1, 2, 2, 85, 87),
(61, 2, 1, 1, 2, 3, 90, 87),
(62, 2, 1, 1, 2, 4, 90, 87),
(63, 2, 1, 1, 4, 1, 83, 87),
(64, 2, 1, 1, 4, 2, 85, 87),
(65, 2, 1, 1, 4, 3, 85, 87),
(66, 2, 1, 1, 4, 4, 90, 87),
(67, 4, 1, 1, 2, 1, 83, 81.375),
(68, 4, 1, 1, 2, 2, 82, 81.375),
(69, 4, 1, 1, 2, 3, 90, 81.375),
(70, 4, 1, 1, 2, 4, 83, 81.375),
(71, 4, 1, 1, 4, 1, 80, 81.375),
(72, 4, 1, 1, 4, 2, 75, 81.375),
(73, 4, 1, 1, 4, 3, 80, 81.375),
(74, 4, 1, 1, 4, 4, 78, 81.375),
(83, 2, 2, 1, 2, 1, 83, 47.25),
(84, 2, 2, 1, 2, 2, 82, 47.25),
(85, 2, 2, 1, 2, 3, 90, 47.25),
(86, 2, 2, 1, 2, 4, 83, 47.25),
(87, 2, 2, 1, 4, 1, 6, 47.25),
(88, 2, 2, 1, 4, 2, 26, 47.25),
(89, 2, 2, 1, 4, 3, 2, 47.25),
(90, 2, 2, 1, 4, 4, 6, 47.25),
(91, 2, 4, 1, 2, 1, 32, 17.25),
(92, 2, 4, 1, 2, 2, 13, 17.25),
(93, 2, 4, 1, 2, 3, 4, 17.25),
(94, 2, 4, 1, 2, 4, 55, 17.25),
(95, 2, 4, 1, 4, 1, 3, 17.25),
(96, 2, 4, 1, 4, 2, 25, 17.25),
(97, 2, 4, 1, 4, 3, 1, 17.25),
(98, 2, 4, 1, 4, 4, 5, 17.25),
(99, 5, 1, 1, 2, 1, 75, 77.875),
(100, 5, 1, 1, 2, 2, 80, 77.875),
(101, 5, 1, 1, 2, 3, 85, 77.875),
(102, 5, 1, 1, 2, 4, 75, 77.875),
(103, 5, 1, 1, 4, 1, 75, 77.875),
(104, 5, 1, 1, 4, 2, 75, 77.875),
(105, 5, 1, 1, 4, 3, 80, 77.875),
(106, 5, 1, 1, 4, 4, 78, 77.875),
(107, 6, 1, 1, 2, 1, 88, 85.125),
(108, 6, 1, 1, 2, 2, 90, 85.125),
(109, 6, 1, 1, 2, 3, 90, 85.125),
(110, 6, 1, 1, 2, 4, 85, 85.125),
(111, 6, 1, 1, 4, 1, 85, 85.125),
(112, 6, 1, 1, 4, 2, 80, 85.125),
(113, 6, 1, 1, 4, 3, 85, 85.125),
(114, 6, 1, 1, 4, 4, 78, 85.125),
(123, 5, 2, 1, 2, 1, 93, 95.125),
(124, 5, 2, 1, 2, 2, 98, 95.125),
(125, 5, 2, 1, 2, 3, 97, 95.125),
(126, 5, 2, 1, 2, 4, 95, 95.125),
(127, 5, 2, 1, 4, 1, 95, 95.125),
(128, 5, 2, 1, 4, 2, 94, 95.125),
(129, 5, 2, 1, 4, 3, 95, 95.125),
(130, 5, 2, 1, 4, 4, 94, 95.125),
(131, 4, 2, 1, 2, 1, 97, 95.5),
(132, 4, 2, 1, 2, 2, 95, 95.5),
(133, 4, 2, 1, 2, 3, 95, 95.5),
(134, 4, 2, 1, 2, 4, 95, 95.5),
(135, 4, 2, 1, 4, 1, 95, 95.5),
(136, 4, 2, 1, 4, 2, 95, 95.5),
(137, 4, 2, 1, 4, 3, 96, 95.5),
(138, 4, 2, 1, 4, 4, 96, 95.5),
(139, 6, 2, 1, 2, 1, 97, 95.75),
(140, 6, 2, 1, 2, 2, 95, 95.75),
(141, 6, 2, 1, 2, 3, 96, 95.75),
(142, 6, 2, 1, 2, 4, 97, 95.75),
(143, 6, 2, 1, 4, 1, 93, 95.75),
(144, 6, 2, 1, 4, 2, 95, 95.75),
(145, 6, 2, 1, 4, 3, 95, 95.75),
(146, 6, 2, 1, 4, 4, 98, 95.75),
(155, 2, 5, 1, 2, 1, 76, 77.25),
(156, 2, 5, 1, 2, 2, 99, 77.25),
(157, 2, 5, 1, 2, 3, 50, 77.25),
(158, 2, 5, 1, 2, 4, 88, 77.25),
(159, 2, 5, 1, 4, 1, 57, 77.25),
(160, 2, 5, 1, 4, 2, 96, 77.25),
(161, 2, 5, 1, 4, 3, 76, 77.25),
(162, 2, 5, 1, 4, 4, 76, 77.25),
(163, 5, 4, 1, 2, 1, 86, 79.5),
(164, 5, 4, 1, 2, 2, 56, 79.5),
(165, 5, 4, 1, 2, 3, 33, 79.5),
(166, 5, 4, 1, 2, 4, 88, 79.5),
(167, 5, 4, 1, 4, 1, 99, 79.5),
(168, 5, 4, 1, 4, 2, 99, 79.5),
(169, 5, 4, 1, 4, 3, 87, 79.5),
(170, 5, 4, 1, 4, 4, 88, 79.5),
(171, 4, 5, 1, 2, 1, 66, 56.625),
(172, 4, 5, 1, 2, 2, 66, 56.625),
(173, 4, 5, 1, 2, 3, 55, 56.625),
(174, 4, 5, 1, 2, 4, 57, 56.625),
(175, 4, 5, 1, 4, 1, 55, 56.625),
(176, 4, 5, 1, 4, 2, 66, 56.625),
(177, 4, 5, 1, 4, 3, 33, 56.625),
(178, 4, 5, 1, 4, 4, 55, 56.625),
(179, 2, 7, 1, 2, 1, 65, 77.75),
(180, 2, 7, 1, 2, 2, 78, 77.75),
(181, 2, 7, 1, 2, 3, 87, 77.75),
(182, 2, 7, 1, 2, 4, 86, 77.75),
(183, 2, 7, 1, 4, 1, 74, 77.75),
(184, 2, 7, 1, 4, 2, 75, 77.75),
(185, 2, 7, 1, 4, 3, 78, 77.75),
(186, 2, 7, 1, 4, 4, 79, 77.75),
(187, 4, 7, 1, 2, 1, 25, 63.625),
(188, 4, 7, 1, 2, 2, 25, 63.625),
(189, 4, 7, 1, 2, 3, 79, 63.625),
(190, 4, 7, 1, 2, 4, 55, 63.625),
(191, 4, 7, 1, 4, 1, 67, 63.625),
(192, 4, 7, 1, 4, 2, 78, 63.625),
(193, 4, 7, 1, 4, 3, 90, 63.625),
(194, 4, 7, 1, 4, 4, 90, 63.625),
(195, 5, 7, 1, 2, 1, 65, 65.75),
(196, 5, 7, 1, 2, 2, 76, 65.75),
(197, 5, 7, 1, 2, 3, 64, 65.75),
(198, 5, 7, 1, 2, 4, 76, 65.75),
(199, 5, 7, 1, 4, 1, 35, 65.75),
(200, 5, 7, 1, 4, 2, 65, 65.75),
(201, 5, 7, 1, 4, 3, 67, 65.75),
(202, 5, 7, 1, 4, 4, 78, 65.75),
(203, 6, 7, 1, 2, 1, 65, 60.75),
(204, 6, 7, 1, 2, 2, 46, 60.75),
(205, 6, 7, 1, 2, 3, 64, 60.75),
(206, 6, 7, 1, 2, 4, 35, 60.75),
(207, 6, 7, 1, 4, 1, 67, 60.75),
(208, 6, 7, 1, 4, 2, 89, 60.75),
(209, 6, 7, 1, 4, 3, 76, 60.75),
(210, 6, 7, 1, 4, 4, 44, 60.75),
(219, 9, 1, 1, 2, 1, 85, 82.125),
(220, 9, 1, 1, 2, 2, 80, 82.125),
(221, 9, 1, 1, 2, 3, 87, 82.125),
(222, 9, 1, 1, 2, 4, 83, 82.125),
(223, 9, 1, 1, 4, 1, 84, 82.125),
(224, 9, 1, 1, 4, 2, 78, 82.125),
(225, 9, 1, 1, 4, 3, 80, 82.125),
(226, 9, 1, 1, 4, 4, 80, 82.125),
(227, 2, 8, 1, 2, 1, 80, 25.75),
(228, 2, 8, 1, 2, 2, 87, 25.75),
(229, 2, 8, 1, 2, 3, 8, 25.75),
(230, 2, 8, 1, 2, 4, 7, 25.75),
(231, 2, 8, 1, 4, 1, 6, 25.75),
(232, 2, 8, 1, 4, 2, 6, 25.75),
(233, 2, 8, 1, 4, 3, 6, 25.75),
(234, 2, 8, 1, 4, 4, 6, 25.75),
(235, 2, 11, 1, 2, 1, 80, 81.125),
(236, 2, 11, 1, 2, 2, 86, 81.125),
(237, 2, 11, 1, 2, 3, 70, 81.125),
(238, 2, 11, 1, 2, 4, 80, 81.125),
(239, 2, 11, 1, 4, 1, 80, 81.125),
(240, 2, 11, 1, 4, 2, 90, 81.125),
(241, 2, 11, 1, 4, 3, 88, 81.125),
(242, 2, 11, 1, 4, 4, 75, 81.125),
(243, 9, 2, 1, 2, 1, 77, 83.125),
(244, 9, 2, 1, 2, 2, 75, 83.125),
(245, 9, 2, 1, 2, 3, 80, 83.125),
(246, 9, 2, 1, 2, 4, 80, 83.125),
(247, 9, 2, 1, 4, 1, 85, 83.125),
(248, 9, 2, 1, 4, 2, 90, 83.125),
(249, 9, 2, 1, 4, 3, 88, 83.125),
(250, 9, 2, 1, 4, 4, 90, 83.125);

-- --------------------------------------------------------

--
-- Struktur dari tabel `periode`
--

CREATE TABLE `periode` (
  `id_periode` int(11) NOT NULL,
  `tahun_periode` int(11) NOT NULL,
  `bulan_periode` varchar(100) NOT NULL,
  `status_periode` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `periode`
--

INSERT INTO `periode` (`id_periode`, `tahun_periode`, `bulan_periode`, `status_periode`) VALUES
(1, 2024, 'Oktober', 'Aktif'),
(2, 2025, 'Mei', 'Tidak Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `subkriteria`
--

CREATE TABLE `subkriteria` (
  `subkriteria_id` int(11) NOT NULL,
  `subkriteria_keterangan` varchar(200) NOT NULL,
  `subkriteria_nilai` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `subkriteria`
--

INSERT INTO `subkriteria` (`subkriteria_id`, `subkriteria_keterangan`, `subkriteria_nilai`) VALUES
(1, 'Intonasi', '25'),
(2, 'Kualitas suara', '25'),
(3, 'Kesesuaikan dengan Partitur', '25'),
(4, 'Penampilan Keseluruhan', '25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `email` varchar(200) NOT NULL,
  `katasandi` varchar(255) NOT NULL,
  `level` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `nama`, `nama_lengkap`, `email`, `katasandi`, `level`) VALUES
(1, 'admin', 'Admin nilai', 'admin123@gmail.com', '202cb962ac59075b964b07152d234b70', 'admin'),
(2, 'jurisatu', 'Juri A', 'jurisatu@gmail.com', '88c7d1e4ad2c1feb793dcfe64d7fd721', 'juri'),
(4, 'juridua', 'Juri B', 'juridua@gmail.com', '88c7d1e4ad2c1feb793dcfe64d7fd721', 'juri'),
(5, 'juritiga', 'Juri C', 'juritiga@gmail.com', '88c7d1e4ad2c1feb793dcfe64d7fd721', 'juri'),
(6, 'juriempat', 'Juri D', 'juriempat@gmail.com', '88c7d1e4ad2c1feb793dcfe64d7fd721', 'juri'),
(9, 'jurilima', 'Juri E', 'jurilima@gmail.com', '88c7d1e4ad2c1feb793dcfe64d7fd721', 'juri');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indeks untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id_penilaian`),
  ADD KEY `id_alternatif` (`id_alternatif`),
  ADD KEY `id_periode` (`id_periode`),
  ADD KEY `id_kriteria` (`id_kriteria`),
  ADD KEY `subkriteria_id` (`subkriteria_id`),
  ADD KEY `id` (`id`);

--
-- Indeks untuk tabel `periode`
--
ALTER TABLE `periode`
  ADD PRIMARY KEY (`id_periode`);

--
-- Indeks untuk tabel `subkriteria`
--
ALTER TABLE `subkriteria`
  ADD PRIMARY KEY (`subkriteria_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT untuk tabel `periode`
--
ALTER TABLE `periode`
  MODIFY `id_periode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `subkriteria`
--
ALTER TABLE `subkriteria`
  MODIFY `subkriteria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  ADD CONSTRAINT `penilaian_ibfk_1` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penilaian_ibfk_2` FOREIGN KEY (`id_periode`) REFERENCES `periode` (`id_periode`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penilaian_ibfk_3` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penilaian_ibfk_4` FOREIGN KEY (`subkriteria_id`) REFERENCES `subkriteria` (`subkriteria_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penilaian_ibfk_5` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
