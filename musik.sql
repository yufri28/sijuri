-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Sep 2024 pada 16.57
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
  `nomor_urut` int(5) NOT NULL,
  `lagu_pertama` varchar(200) NOT NULL,
  `lagu_kedua` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `nama_alternatif`, `nomor_urut`, `lagu_pertama`, `lagu_kedua`) VALUES
(53, 'PSM Cantus Firmus - Universitas Sanata Dharma', 0, 'Blessed art Thou, O Lord / Sergei Rachmaninoff', 'Elijah Rock / Moses Hogan'),
(54, 'PSM Laudate Choir - Universitas Timor', 0, 'God So Loved The World / John Stainer', 'Ain\'t Got Time to Die / Hall Johnson'),
(55, 'PSM Vox Angelorum Choir - Universitas Kristen Indonesia Maluku', 0, 'Os Justi / Anton Bruckner ', 'I Got a Home in-a Dat Rock / Moses Hogan'),
(56, 'PSM Solfeggio Choir - Universitas Negeri Medan', 0, 'Cantate Domino / Claudio Monteverdi', 'Elijah Rock / Moses Hogan'),
(57, 'PSM Caritas Choral - Universitas Caritas Indonesia', 0, 'Locus Iste / Anton Bruckner', 'Ride On King Jesus / Stacey V. Gibbs'),
(58, 'PSM Haleluya - Universitas Tadulako', 0, 'Cantate Domino / Hans Leo Hassler', 'Witness / Jack Halloran'),
(59, 'PSM Universitas Palangka Raya', 0, 'Exsultate Deo / Alessandro Scarlatti', 'Rockin\' Jerusalem / Stacey V. Gibbs'),
(60, 'PSM Universitas Musamus Merauke', 0, 'Os Justi / Anton Bruckner', 'Great God Almighty / Stacey V. Gibbs'),
(61, 'PSM Qui Bene Cantat - Universitas Tanjungpura', 0, 'Virga Jesse / Anton Bruckner', 'My God is a Rock / Ken Berg'),
(62, 'PSM Universitas Atma Jaya Yogyakarta', 0, 'Christus Factus Est / Anton Bruckner', 'My God is a Rock / Ken Berg'),
(63, 'PSM Hotumese Choir - Universitas Pattimura', 0, 'Virga Jesse Floruit / Anton Bruckner', 'Daniel, Servant of The Lord / Stacey V. Gibbs'),
(64, 'PSM Bella Cantare Choir - Universitas Nusa Cendana', 0, 'Richte Mich, Gott / Felix Mendelssohn B.', 'Elijah Rock / Moses Hogan'),
(65, 'PSM Voca Erudita - Universitas Sebelas Maret', 0, 'Die Himmel Erzahlen die Ehre Gottes / Heinrich Schütz', 'Wade in de Water / Allen Koepke'),
(66, 'PSMP ULM - Universitas Lambung Mangkura', 0, 'Ave Maria / Anton Bruckner', 'Joshua / Rollo Dilworth'),
(67, 'PSM Universitas Brawijaya', 0, 'Die Himmel erzählen die Ehre Gottes / Heinrich Schütz', 'My Soul’s Been Anhored in the Lord / Moses Hogan'),
(68, 'PSM Universitas Cenderawasih', 0, 'Denn Er hat seinen Engeln befohlen / Felix Mendelssohn Bartholdy', 'Wade in de Water / Allen Koepke'),
(69, 'PSM Lelemuku Choir - Universitas Lelemuku Saumlaki', 0, 'Super flumina Babilonis / Giovanni Pierluigi da Palestrina', 'Great God Almighty / Stacey V. Gibbs'),
(70, 'PSM Uniwira - Universitas Katolik Widya Mandira Kupang', 0, 'Ehre Sei Gott in der Hohe / Felix Mendelssohn', 'I Can Tell / Moses Hogan'),
(71, 'PSM Evangelist Universitas Kristen Artha Wacana Kupang', 0, 'Angelus Domini descendit / Josef Rheinberger', 'Great God Almighty / Stacey V. Gibbs'),
(72, 'PSM Voice of SWCU - Universitas Kristen Satya Wacana', 0, 'Lux Aeterna / Edward Elgar, arr. John Cameron', 'Joshua Fought the Battle of Jericho / Jonathan Rathbone'),
(73, 'PSM Universitas Negeri Yogyakarta', 0, 'Ehre sei Gott in der Höhe / Felix Mendelssohn', 'Hold On / Moses Hogan'),
(74, 'PSM Psallo - Universitas Negeri Surabaya', 0, 'Wunshcet Jerussalem Gluck / Gottfried A. Homilius', 'My God Is a Rock / Stacey V. Gibbs'),
(75, 'PSM MCUC - Universitas Kristen Maranatha', 0, 'Sing Joyfully / William Byrd', 'John the Revelator / Paul Caldwell & Sean Ivory'),
(76, 'PSM Gita Choir - Sekolah Tinggi Filsafat Jaffray Makassar', 0, 'Locus Iste / Anton Bruckner', 'Great God Almigthy / Stacey V. Gibbs'),
(77, 'PSM Pinisi Choir - Universitas Negeri Makassar', 0, 'Exultate Deo / Giovani Pierluigi da Palestrina', 'Hold On / Moses Hogan'),
(78, 'PSM UBAYA Choir - Universitas Surabaya', 0, 'Cantate Domino / J. P. Sweelinck', 'Way Over in Beulah Land / Stacey V. Gibbs'),
(79, 'PSM UKIT - Universitas Kristen Indonesia Tomohon', 0, 'Christus Factus Est / Anton Bruckner', 'Rockin’ Jerusalem / Stacey V. Gibbs'),
(80, 'PSM UKM UNSRAT - Universitas Sam Ratulangi', 0, ' Os Justi / Anton Bruckner', 'Soon I Will Be Done / Stacey V. Gibbs'),
(81, 'PSM ULOS - Universitas Sumatera Utara', 0, 'Ehre sei Gott in der Höhe / Felix Mendelssohn', 'Didn\'t My Lord Deliver Daniel / Aaron Dale'),
(82, 'PSM UNIMA - Universitas Negeri Manado', 0, 'Richte Mich, Gott / Felix Mendelssohn B.', 'Swing Low, Sweet Chariot/ David L. Brunner'),
(83, 'PSM Universitas Bina Nusantara', 0, 'Abendlied / Josef Rheinberger', 'Ride On King Jesus / Moses Hogan'),
(84, 'PSM St. Paul Choir - Universitas Katolik Indonesia Santu Paulus Ruteng', 0, 'Jubilate Deo / Giovanni Gabrieli', 'Ain\'t-a That Good News / Stacey V. Gibbs'),
(85, 'PSM Universitas Diponegoro', 0, 'Dixit Maria / Hans Leo Hassler', 'My God Is A Rock / Ken Berg');

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

--
-- Dumping data untuk tabel `penilaian`
--

INSERT INTO `penilaian` (`id_penilaian`, `id`, `id_alternatif`, `id_kriteria`, `subkriteria_id`, `nilai`, `komentar`, `nilai_akhir`) VALUES
(25, 2, 53, 2, 1, 80.4, 'Oke', 86.3),
(26, 2, 53, 2, 2, 90, 'Oke', 86.3),
(27, 2, 53, 2, 3, 70, 'Oke', 86.3),
(28, 2, 53, 2, 4, 92, 'Oke', 86.3),
(29, 2, 53, 4, 1, 92, 'Bagus, kembangkan.', 86.3),
(30, 2, 53, 4, 2, 98, 'Bagus, kembangkan.', 86.3),
(31, 2, 53, 4, 3, 87, 'Bagus, kembangkan.', 86.3),
(32, 2, 53, 4, 4, 81, 'Bagus, kembangkan.', 86.3),
(33, 2, 71, 2, 1, 34, 'Smq', 48.25),
(34, 2, 71, 2, 2, 65, 'Smq', 48.25),
(35, 2, 71, 2, 3, 25, 'Smq', 48.25),
(36, 2, 71, 2, 4, 63, 'Smq', 48.25),
(37, 2, 71, 4, 1, 45, 'Fs', 48.25),
(38, 2, 71, 4, 2, 64, 'Fs', 48.25),
(39, 2, 71, 4, 3, 24, 'Fs', 48.25),
(40, 2, 71, 4, 4, 66, 'Fs', 48.25),
(49, 2, 73, 2, 1, 45.7, '', 33.0375),
(50, 2, 73, 2, 2, 85.4, '', 33.0375),
(51, 2, 73, 2, 3, 3, '', 33.0375),
(52, 2, 73, 2, 4, 5, '', 33.0375),
(53, 2, 73, 4, 1, 67, '', 33.0375),
(54, 2, 73, 4, 2, 7, '', 33.0375),
(55, 2, 73, 4, 3, 45.2, '', 33.0375),
(56, 2, 73, 4, 4, 6, '', 33.0375),
(57, 2, 72, 2, 1, 73.22, 'Keren', 48.4837),
(58, 2, 72, 2, 2, 34.4, 'Keren', 48.4837),
(59, 2, 72, 2, 3, 23.4, 'Keren', 48.4837),
(60, 2, 72, 2, 4, 55, 'Keren', 48.4837),
(61, 2, 72, 4, 1, 45.3, 'Bagus sekali.', 48.4837),
(62, 2, 72, 4, 2, 24.55, 'Bagus sekali.', 48.4837),
(63, 2, 72, 4, 3, 55, 'Bagus sekali.', 48.4837),
(64, 2, 72, 4, 4, 77, 'Bagus sekali.', 48.4837),
(65, 4, 53, 2, 1, 90, 'mantap', 46.4375),
(66, 4, 53, 2, 2, 30, 'mantap', 46.4375),
(67, 4, 53, 2, 3, 3, 'mantap', 46.4375),
(68, 4, 53, 2, 4, 4.5, 'mantap', 46.4375),
(69, 4, 53, 4, 1, 34, 'Oke bagus', 46.4375),
(70, 4, 53, 4, 2, 56, 'Oke bagus', 46.4375),
(71, 4, 53, 4, 3, 66, 'Oke bagus', 46.4375),
(72, 4, 53, 4, 4, 88, 'Oke bagus', 46.4375),
(73, 4, 71, 2, 1, 90.2, '', 56.025),
(74, 4, 71, 2, 2, 90, '', 56.025),
(75, 4, 71, 2, 3, 43, '', 56.025),
(76, 4, 71, 2, 4, 100, '', 56.025),
(77, 4, 71, 4, 1, 4, '', 56.025),
(78, 4, 71, 4, 2, 44, '', 56.025),
(79, 4, 71, 4, 3, 22, '', 56.025),
(80, 4, 71, 4, 4, 55, '', 56.025);

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
(1, 'admin', 'Admin sijuri', 'adminsijuri123@gmail.com', '202cb962ac59075b964b07152d234b70', 'admin'),
(2, 'jurisat', 'Drs. Agastya Rama Listya, MSM, Ph.D', 'jurisatu@gmail.com', '88c7d1e4ad2c1feb793dcfe64d7fd721', 'juri'),
(4, 'juridua', 'Ardelia Padma Sawitri', 'juridua@gmail.com', '88c7d1e4ad2c1feb793dcfe64d7fd721', 'juri'),
(5, 'juritiga', 'Budi Susanto Yohanes', 'juritiga@gmail.com', '88c7d1e4ad2c1feb793dcfe64d7fd721', 'juri'),
(6, 'juriempat', 'Ega O.  Azarya', 'juriempat@gmail.com', '88c7d1e4ad2c1feb793dcfe64d7fd721', 'juri'),
(9, 'jurilima', 'Jessica Fedora Amadea', 'jurilima@gmail.com', '88c7d1e4ad2c1feb793dcfe64d7fd721', 'juri');

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
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

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
