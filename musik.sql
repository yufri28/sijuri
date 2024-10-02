-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Okt 2024 pada 19.59
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `musik`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `nama_alternatif` varchar(200) NOT NULL,
  `dirigen` varchar(200) NOT NULL,
  `nomor_urut` int(5) NOT NULL,
  `lagu_pertama` varchar(200) NOT NULL,
  `durasi_pertama` varchar(100) NOT NULL,
  `lagu_kedua` varchar(200) NOT NULL,
  `durasi_kedua` varchar(100) NOT NULL,
  `total_durasi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `nama_alternatif`, `dirigen`, `nomor_urut`, `lagu_pertama`, `durasi_pertama`, `lagu_kedua`, `durasi_kedua`, `total_durasi`) VALUES
(53, 'PSM Universitas Sanata Dharma', 'Setiawan Ezra Bennova', 28, 'Elijah Rock / Moses Hogan', '00:00', 'Blessed art Thou, O Lord / Sergei Rachmaninoff', '00:00', '00:00'),
(54, 'PSM Universitas Timor', 'Bernad Marianus Deni Akoit', 31, 'God So Loved The World / John Stainer', '00:00', 'Ain\'t Got Time to Die / Hall Johnson', '00:00', '00:00'),
(55, 'PSM Universitas Kristen Indonesia Maluku', 'Dusthyn G. F. X Kadju', 26, 'Os Justi / Anton Bruckner ', '00:00', 'I Got a Home in-a Dat Rock / Moses Hogan', '00:00', '00:00'),
(56, 'PSM Universitas Negeri Medan', 'Tulus Nathanael Tarigan', 11, 'Cantate Domino / Claudio Monteverdi', '00:00', 'Elijah Rock / Moses Hogan', '00:00', '00:00'),
(57, 'PSM Universitas Caritas Indonesia', 'Brianus Dopo Nusa', 33, 'Locus Iste / Anton Bruckner', '00:00', 'Ride On King Jesus / Stacey V. Gibbs', '00:00', '00:00'),
(58, 'PSM Universitas Tadulako', 'Prischal Rianto Njaya', 27, 'Cantate Domino / Hans Leo Hassler', '00:00', 'Witness / Jack Halloran', '00:00', '00:00'),
(59, 'PSM Universitas Palangka Raya', 'Imanuel', 22, 'Exsultate Deo / Alessandro Scarlatti', '00:00', 'Rockin\' Jerusalem / Stacey V. Gibbs', '00:00', '00:00'),
(60, 'PSM Universitas Musamus Merauke', 'Nurlela Pandiangan', 23, 'Os Justi / Anton Bruckner', '00:00', 'Great God Almighty / Stacey V. Gibbs', '00:00', '00:00'),
(61, 'PSM Universitas Tanjungpura', 'Cristi Novia Gulatri', 21, 'Virga Jesse / Anton Bruckner', '00:00', 'My God is a Rock / Ken Berg', '00:00', '00:00'),
(62, 'PSM Universitas Atma Jaya Yogyakarta', 'Ignasius Axel Cokrodiharjo', 19, 'Christus Factus Est / Anton Bruckner', '00:00', 'My God is a Rock / Ken Berg', '00:00', '00:00'),
(63, 'PSM Universitas Pattimura', ' Costantinus Varano Batmanlussy', 3, 'Virga Jesse Floruit / Anton Bruckner', '00:00', 'Daniel, Servant of The Lord / Stacey V. Gibbs', '00:00', '00:00'),
(64, 'PSM Universitas Nusa Cendana', 'Salviani Sarita Natol', 25, 'Richte Mich, Gott / Felix Mendelssohn B.', '00:00', 'Elijah Rock / Moses Hogan', '00:00', '00:00'),
(65, 'PSM Universitas Sebelas Maret', 'Dionisius Indra Raditya', 29, 'Die Himmel Erzahlen die Ehre Gottes / Heinrich Schütz', '00:00', 'Wade in de Water / Allen Koepke', '00:00', '00:00'),
(66, 'PSM Universitas Lambung Mangkurat', 'Paula Carolina Natalia', 8, 'Joshua / Rollo Dilworth', '00:00', 'Ave Maria / Anton Bruckner', '00:00', '00:00'),
(67, 'PSM Universitas Brawijaya', 'Librina Rosario', 15, 'Die Himmel erzählen die Ehre Gottes / Heinrich Schütz', '00:00', 'My Soul’s Been Anhored in the Lord / Moses Hogan', '00:00', '00:00'),
(68, 'PSM Universitas Cenderawasih', 'Fridolin Sasria Sulu', 4, 'Denn Er hat seinen Engeln befohlen / Felix Mendelssohn Bartholdy', '00:00', 'Wade in de Water / Allen Koepke', '00:00', '00:00'),
(69, 'PSM Universitas Lelemuku Saumlaki', 'Marta Nanaryain', 1, 'Super flumina Babilonis / Giovanni Pierluigi da Palestrina', '00:00', 'Great God Almighty / Stacey V. Gibbs', '00:00', '00:00'),
(70, 'PSM Universitas Katolik Widya Mandira Kupang', 'Dominikus Dionisius T. Tukan', 17, 'Ehre Sei Gott in der Hohe / Felix Mendelssohn', '00:00', 'I Can Tell / Moses Hogan', '00:00', '00:00'),
(71, 'PSM Universitas Kristen Artha Wacana', 'Putra Agung Setyawan Pono & Adventhino Petrus Neisessalem Saudale', 5, 'Angelus Domini descendit / Josef Rheinberger', '00:00', 'Great God Almighty / Stacey V. Gibbs', '00:00', '00:00'),
(72, 'PSM Universitas Kristen Satya Wacana', 'Gloria Clara Fangohoy', 10, 'Lux Aeterna / Edward Elgar, arr. John Cameron', '00:00', 'Joshua Fought the Battle of Jericho / Jonathan Rathbone', '00:00', '00:00'),
(73, 'PSM Universitas Negeri Yogyakarta', 'Brigita Silvia Perfectiani Indra Haristi', 14, 'Ehre sei Gott in der Höhe / Felix Mendelssohn', '00:00', 'Hold On / Moses Hogan', '00:00', '00:00'),
(74, 'PSM Universitas Negeri Surabaya', 'Rakaryan Wiryawisesa', 16, 'Wunshcet Jerussalem Gluck / Gottfried A. Homilius', '00:00', 'My God Is a Rock / Stacey V. Gibbs', '00:00', '00:00'),
(75, 'PSM Universitas Kristen Maranatha', 'Vivaldi immanuel Pardede', 2, 'Sing Joyfully / William Byrd', '00:00', 'John the Revelator / Paul Caldwell & Sean Ivory', '00:00', '00:00'),
(77, 'PSM Universitas Negeri Makassar', 'Muhammad Zaky Athari Ihsan', 20, 'Exultate Deo / Giovani Pierluigi da Palestrina', '00:00', 'Hold On / Moses Hogan', '00:00', '00:00'),
(78, 'PSM Universitas Surabaya', 'Darrent Kristian Utama', 6, 'Cantate Domino / J. P. Sweelinck', '00:00', 'Way Over in Beulah Land / Stacey V. Gibbs', '00:00', '00:00'),
(79, 'PSM Universitas Kristen Indonesia Tomohon', 'Risky A.H. Lengkong', 30, 'Christus Factus Est / Anton Bruckner', '00:00', 'Rockin’ Jerusalem / Stacey V. Gibbs', '00:00', '00:00'),
(80, 'PSM Universitas Sam Ratulangi', 'Amadeo Parmadi', 13, ' Os Justi / Anton Bruckner', '00:00', 'Soon I Will Be Done / Stacey V. Gibbs', '00:00', '00:00'),
(81, 'PSM Universitas Sumatera Utara', 'Billy Joel Hagai Simbolon', 9, 'Ehre sei Gott in der Höhe / Felix Mendelssohn', '00:00', 'Didn\'t My Lord Deliver Daniel / Aaron Dale', '00:00', '00:00'),
(82, 'PSM Universitas Negeri Manado', 'Marcello Denny Ohy', 18, 'Richte Mich, Gott / Felix Mendelssohn B.', '00:00', 'Swing Low, Sweet Chariot/ David L. Brunner', '00:00', '00:00'),
(83, 'PSM Universitas Bina Nusantara', 'Andy Wijaya', 24, 'Abendlied / Josef Rheinberger', '00:00', 'Ride On King Jesus / Moses Hogan', '00:00', '00:00'),
(84, 'PSM Universitas Katolik Indonesia Santu Paulus Ruteng', 'Konradus Pian', 32, 'Jubilate Deo / Giovanni Gabrieli', '00:00', 'Ain\'t-a That Good News / Stacey V. Gibbs', '00:00', '00:00'),
(85, 'PSM Universitas Diponegoro', 'Widi Nugraheni Riring Danurwinda', 12, 'Dixit Maria / Hans Leo Hassler', '00:00', 'My God Is A Rock / Ken Berg', '00:00', '00:00'),
(86, 'PSM Universitas HKBP Nommensen', 'Hartati Simanjuntak', 7, 'Exultate Deo / Giovani Pierluigi da Palestrina', '00:00', 'Way Over In Beulah Lan\'/ Stacey V. Gibbs', '00:00', '00:00');

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
(2, 'K1', 'Lagu ke-1', 'Keterangan untuk lagu pertama'),
(4, 'K2', 'Lagu ke-2', 'Keterangan untuk lagu kedua');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaian`
--

CREATE TABLE `penilaian` (
  `id_penilaian` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `id_alternatif` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `subkriteria_id` int(11) NOT NULL,
  `nilai` float NOT NULL,
  `komentar` varchar(255) NOT NULL,
  `nilai_akhir` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(3, 'Kesesuaian dengan Partitur', '25'),
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
(1, 'admin', 'Admin sijuri', 'adminsijuri123@gmail.com', '202cb962ac59075b964b07152d234b70', 'admin'),
(2, 'ketuajuri', 'Ega O.  Azarya', 'ketuajuri@gmail.com', '88c7d1e4ad2c1feb793dcfe64d7fd721', 'juri'),
(4, 'anggotasatu', 'Drs. Agastya Rama Listya, MSM, Ph.D', 'anggotasatu@gmail.com', '7989b5025e2a3aa84c8437c302aa5025', 'juri'),
(5, 'anggotadua', 'Ardelia Padma Sawitri', 'anggotadua@gmail.com', 'fb69875462aafa905bdf430f3d2588e9', 'juri'),
(6, 'anggotatiga', 'Budi Susanto Yohanes', 'anggotatiga@gmail.com', '14f07e56134aebe19d9a5035701f7d45', 'juri'),
(9, 'anggotaempat', 'Jessica Fedora Amadea', 'anggotaempat@gmail.com', '2a2e42870b96810b4ccd61afcec792e4', 'juri');

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
  ADD KEY `id` (`id`),
  ADD KEY `id_alternatif` (`id_alternatif`),
  ADD KEY `id_kriteria` (`id_kriteria`),
  ADD KEY `subkriteria_id` (`subkriteria_id`);

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
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=409;

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
  ADD CONSTRAINT `penilaian_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penilaian_ibfk_2` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penilaian_ibfk_3` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penilaian_ibfk_4` FOREIGN KEY (`subkriteria_id`) REFERENCES `subkriteria` (`subkriteria_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
