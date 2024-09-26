<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama'])) {

    // Memanggil Koneksi Database
    require_once "../assets/koneksi.php";
    $koneksi = connectDB(); // Menggunakan fungsi connectDB() untuk mendapatkan koneksi database

    require_once "../perhitungan/perhitunganAksi.php";

    // Mendapatkan data alternatif (peserta lomba)
    $alternatif = getAlternatif($koneksi);

    // Mendapatkan data user (juri)
    $users = getUser($koneksi);

    // Mendapatkan nilai akhir dari setiap alternatif
    $nilai_akhir = getNilaiAkhir($koneksi);

    // Mendapatkan peringkat berdasarkan nilai dari setiap juri
    $peringkat = [];
    foreach ($users as $user) {
        $id_user = $user['id'];
        // Ambil semua nilai untuk juri ini
        $sql_nilai = "SELECT id_alternatif, nilai_akhir FROM penilaian WHERE id = $id_user";
        $result_nilai = $koneksi->query($sql_nilai);

        $nilai_user = [];
        while ($row = $result_nilai->fetch_assoc()) {
            $nilai_user[$row['id_alternatif']] = $row['nilai_akhir'];
        }

        // Hitung peringkat untuk nilai-nilai yang diambil
        arsort($nilai_user);
        $rank = 1;
        foreach ($nilai_user as $id_alt => $nilai) {
            $peringkat[$id_alt][$id_user] = $rank++;
        }
    }

    // Hitung akumulasi predikat
    $akumulasi_predikat = [];
    foreach ($alternatif as $alt) {
        $id_alt = $alt['id_alternatif'];
        $akumulasi_predikat[$id_alt] = array_sum($peringkat[$id_alt]);
    }

    // Load TCPDF library
    require_once('tcpdf/tcpdf.php');

    // Buat objek TCPDF baru dengan orientasi landscape
    $pdf = new TCPDF('L', PDF_UNIT, 'F4', true, 'UTF-8', false);

    // Atur informasi dokumen
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Nama Pengarang');
    $pdf->SetTitle('Laporan Hasil Akhir Penilaian Lomba PSM Nasional XVIII');
    $pdf->SetSubject('Laporan Hasil Akhir Penilaian Lomba PSM Nasional XVIII');
    $pdf->SetKeywords('TCPDF, tabel, hasil akhir');

    // Atur margin
    $pdf->SetMargins(20, 20, 20);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // Atur font
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Tambahkan halaman baru
    $pdf->AddPage();


    // Tambahkan logo pertama
    $logo = '../assets/img/logoC1.jpg';
    $pdf->Image($logo, 4, 2, 15, '', 'JPG');

    // Tambahkan logo kedua
    $logo2 = '../assets/img/logoC2.jpg';
    $pdf->Image($logo2, 20, 4, 28, '', 'JPG');

    // Tambahkan logo ketiga
    $logo3 = '../assets/img/logoC3.jpg';
    $pdf->Image($logo3, 49, 7, 15, '', 'JPG');

    // Tambahkan logo keempat
    $logo4 = '../assets/img/logoC4.jpg';
    $pdf->Image($logo4, 65, 2, 16, '', 'JPG');

    // Tambahkan logo kelima
    $logo5 = '../assets/img/logoC5.jpg';
    $pdf->Image($logo5, 82, 2, 16, '', 'JPG');

    // Tambahkan logo keenam
    $logo6 = '../assets/img/logoC6.jpg';
    $pdf->Image($logo6, 99, 3, 26, '', 'JPG');

    // Tambahkan logo ketujuh
    $logo7 = '../assets/img/logoC7.jpg';
    $pdf->Image($logo7, 124, 3, 26, '', 'JPG');

    // Tambahkan logo kedelapan
    $logo8 = '../assets/img/logoC8.jpg';
    $pdf->Image($logo8, 149, 3, 20, '', 'JPG');

    // Tambahkan teks di sebelah logo dengan style tebal (bold)
    $pdf->SetXY(17, 19);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Laporan Hasil Akhir Penilaian Lomba Paduan Suara Mahasiswa Nasional XVIII', 0, 0, 'C');

// Tambahkan tanggal cetak di ujung kanan atas
$tanggal = date('d F Y');

// Mengatur posisi
$pdf->SetXY(312 - $pdf->GetStringWidth('Tanggal: ' . $tanggal), 0);

// Mengatur font khusus untuk tanggal
$pdf->SetFont('helvetica', '', 11);// Misalnya, menggunakan font "times" dengan italic dan ukuran 10
$pdf->Cell(89, 10, 'Tanggal: ' . $tanggal, 0, 0, 'C');

// Kembalikan font ke default setelah menampilkan tanggal
$pdf->SetFont('helvetica', '', 9); // Font default untuk konten lainnya


    // Tambahkan garis di bawah judul dan teks, perpanjang ke kanan
    $pdf->SetXY(5, 25);
    $pdf->Cell(319, 0, '', 'B', 1, 'C');

    // Set posisi X dan Y untuk menempatkan tabel di tengah halaman
    $pdf->SetXY(5, 35);

    // Tambahkan tabel
    $html = '<table border="1" cellpadding="5">
                    <thead>
                        <tr>
                            <th style="text-align: center; font-weight: bold; width: 23px;">No</th>
                            <th style="text-align: center; font-weight: bold; width: 103px;">Nama Paduan Suara</th>';

    // Tambahkan kolom untuk setiap user
    foreach ($users as $index => $user) {
        $html .= '<th style="text-align: center; font-weight: bold; width: 57px;" title="Nilai dari ' . $user['nama_lengkap'] . '">Nilai Juri ' . ($index + 1) . '</th>
                  <th style="text-align: center; font-weight: bold; width: 57px;">Peringkat</th>';
    }

    $html .= '<th style="text-align: center; font-weight: bold;">Hasil Akhir</th>
                      <th style="text-align: center; font-weight: bold; width: 55px;">Kelompok</th>
                      <th style="text-align: center; font-weight: bold; width: 95px;">Akumulasi Predikat</th>
                    </tr>
                  </thead>
                  <tbody>';

    // Isi tabel
    $no = 1;
    foreach ($alternatif as $index => $alt) {
        $html .= '<tr>
                <td style="text-align: center; width: 23px;">' . $no++ . '</td>
                <td style="text-align: left; width: 103px; font-size: 11px;">' . $alt['nama_alternatif'] . '</td>';

        // Loop untuk menambahkan nilai dan peringkat dari setiap user
        foreach ($users as $user) {
            $id_user = $user['id'];
            $sql_nilai = "SELECT nilai_akhir FROM penilaian 
                      WHERE id_alternatif = {$alt['id_alternatif']} 
                      AND id = $id_user";
            $result_nilai = $koneksi->query($sql_nilai);
            $nilai = ($result_nilai && $result_nilai->num_rows > 0) ? number_format($result_nilai->fetch_assoc()['nilai_akhir'], 3) : '-';

            $html .= '<td style="text-align: center; width: 57px; font-size: 11px;">';
            $html .= $nilai != '-' ? $nilai : '<div style="color: red;">Belum diberikan nilai</div>';
            $html .= '</td>';
            // Peringkat
            $html .= '<td style="text-align: center; width: 57px; font-size: 13px;">' . (isset($peringkat[$alt['id_alternatif']][$id_user]) ? $peringkat[$alt['id_alternatif']][$id_user] : '-') . '</td>';
        }

        // Tambahkan kolom Hasil Akhir, Kelompok, dan Akumulasi Predikat
        $html .= '<td style="text-align: center; font-size: 11px;">' . number_format($nilai_akhir[$alt['id_alternatif']], 3) . '</td>
                          <td style="text-align: center; width: 55px; font-size: 11px;">' . getKelompok(number_format($nilai_akhir[$alt['id_alternatif']], 3)) . '</td>
                          <td style="text-align: center; width: 95px; font-size: 13px; font-weight: bold;">' . $akumulasi_predikat[$alt['id_alternatif']] . '</td>
                      </tr>';
    }
    $html .= '</tbody></table>';

    // Tulis tabel ke halaman
    $pdf->writeHTML($html, true, false, true, false, '');

    // Menambahkan bagian tanda tangan
    $pdf->Ln(5); // Jarak sebelum bagian tanda tangan
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Mengetahui:', 0, 1, 'C');

    // Tabel tanda tangan tanpa garis
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Ln(5); // Jarak sebelum tabel tanda tangan

    // Cek apakah $juri_list ada isinya
    if (!empty($users)) {
        // Membuat HTML untuk tabel tanda tangan
        $html_tanda_tangan = '<table cellpadding="5" cellspacing="0" style="width: 100%; text-align: center;">
                    <tr>';

        // Menambahkan juri ke tabel
        $count = 0;
        foreach ($users as $index => $user) {
            // Jika sudah ada 5 juri di baris, buat baris baru
            if ($index % 5 == 0 && $index > 0) {
                $html_tanda_tangan .= '</tr><tr>';
            }
            $juri_number = $index + 1;
            $html_tanda_tangan .= '<td style="width: 20%; font-size: 13px;">Juri ' . $juri_number . '<br><br><br><br>(' . htmlspecialchars($user['nama_lengkap']) . ')</td>';
            $count++;
        }

        // Menambahkan sel kosong jika baris terakhir kurang dari 5 juri
        if ($count % 5 != 0) {
            $html_tanda_tangan .= str_repeat('<td style="width: 20%;"></td>', 5 - ($count % 5));
        }

        $html_tanda_tangan .= '</tr></table>';

        // Tulis tabel tanda tangan ke halaman PDF
        $pdf->writeHTML($html_tanda_tangan, true, false, true, false, '');
    } else {
        $pdf->Cell(0, 10, 'Tidak ada juri yang terdaftar.', 0, 1, 'C');
    }

    // Tutup dan hasilkan PDF
    $pdf->Output('Laporan Hasil Akhir Penilaian Lomba Paduan Suara Mahasiswa Nasional XVIII.pdf', 'I');
} else {
    echo "Akses ditolak!";
}
