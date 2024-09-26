<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama'])) {
    // Memanggil Koneksi Database
    require_once "../assets/koneksi.php";
    $koneksi = connectDB();

    require_once "../perhitungan/perhitunganAksi.php";
    
    // Mendapatkan data user (juri)
    $users = getUser($koneksi);

    // Mendapatkan id_alternatif dari URL
    $id_alternatif = isset($_GET['id_alternatif']) ? intval($_GET['id_alternatif']) : 0;

    // Validasi id_alternatif
    if ($id_alternatif <= 0) {
        die('ID Alternatif tidak valid');
    }

    // Query untuk mendapatkan nama_alternatif berdasarkan id_alternatif
    $sql_alternatif = "SELECT nama_alternatif FROM alternatif WHERE id_alternatif = ?";
    $stmt = $koneksi->prepare($sql_alternatif);
    $stmt->bind_param('i', $id_alternatif);
    $stmt->execute();
    $result_alternatif = $stmt->get_result();
    $row_alternatif = $result_alternatif->fetch_assoc();

    // Cek jika nama_alternatif ditemukan
    if (!$row_alternatif) {
        die('Nama alternatif tidak ditemukan');
    }

    $nama_alternatif = $row_alternatif['nama_alternatif'];

    // Load TCPDF library
    require_once('tcpdf/tcpdf.php');

    // Buat objek TCPDF baru
    $pdf = new TCPDF('L', PDF_UNIT, 'F4', true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Nama Pengarang');
    $pdf->SetTitle($nama_alternatif);
    $pdf->SetSubject('Komentar untuk PSM Nasional XVIII');
    $pdf->SetKeywords('TCPDF, komentar, PSM');
    $pdf->SetMargins(20, 20, 20);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetPrintHeader(false);
    $pdf->setPrintFooter(false);
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
    $pdf->SetFont('helvetica', 'B', 13);
    $pdf->Cell(0, 10, 'Laporan Rangkuman Komentar Juri terhadap Paduan Suara Mahasiswa Nasional XVIII', 0, 0, 'C');

    // Tambahkan tanggal cetak di ujung kanan atas
    $tanggal = date('d F Y');

    // Mengatur posisi
    $pdf->SetXY(300 - $pdf->GetStringWidth('Tanggal: ' . $tanggal), 0);

    // Mengatur font khusus untuk tanggal
    $pdf->SetFont('helvetica', '', 12); // Misalnya, menggunakan font "times" dengan italic dan ukuran 10
    $pdf->Cell(89, 10, 'Tanggal: ' . $tanggal, 0, 0, 'C');

    // Kembalikan font ke default setelah menampilkan tanggal
    $pdf->SetFont('helvetica', '', 12); // Font default untuk konten lainnya

    // Tampilkan judul dengan nama_alternatif
    $pdf->SetXY(20, 27);
    $pdf->Cell(0, 10, htmlspecialchars($nama_alternatif), 0, 1, 'C');

    // Tambahkan garis di bawah judul dan teks, perpanjang ke kanan
    $pdf->SetXY(5, 30);
    $pdf->Cell(319, 0, '', 'B', 1, 'C');

    // Query untuk mendapatkan komentar
    $sql_komentar = "SELECT penilaian.komentar, user.nama_lengkap, penilaian.id_kriteria, alternatif.nama_alternatif, alternatif.lagu_pertama, alternatif.lagu_kedua 
                        FROM penilaian 
                        INNER JOIN user ON penilaian.id = user.id 
                        JOIN alternatif ON penilaian.id_alternatif = alternatif.id_alternatif
                        WHERE penilaian.id_alternatif = '{$id_alternatif}' 
                        GROUP BY user.id, penilaian.id_kriteria
                        ORDER BY user.id ASC";

    $result_komentar = $koneksi->query($sql_komentar);
    $komentar_per_kriteria = [];

    // Mengelompokkan komentar berdasarkan id_kriteria
    while ($row_komentar = $result_komentar->fetch_assoc()) {
        $komentar_per_kriteria[$row_komentar['id_kriteria']][] = $row_komentar;
    }

    // Bagian untuk menampilkan judul lagu pertama dan kedua
    $html = '';

    if (isset($komentar_per_kriteria[2])) { // Kriteria 2: Lagu Pertama
        $pdf->SetXY(20, $pdf->GetY() + 10);

        // Tampilkan judul "Lagu Pertama" dengan huruf tebal dan di tengah
        $pdf->SetFont('helvetica', 'B', 15); // Mengatur font menjadi helvetica, Bold, ukuran 12
        $pdf->Cell(0, 10, 'Lagu Pertama ', 0, 1, 'C'); // 'C' untuk center

        $pdf->SetFont('Times', '', 16); // Kembali ke font normal untuk isi lagu

        // Tampilkan isi lagu pertama
        $pdf->Cell(0, 10, htmlspecialchars($komentar_per_kriteria[2][0]['lagu_pertama']), 0, 1, 'C');

        $html .= '<table border="1" cellpadding="5">
                    <thead>
                        <tr>
                            <th style="text-align: center; font-weight: bold; width: 290px;">Nama Juri</th>
                            <th style="text-align: center; font-weight: bold; width: 520px;">Komentar</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($komentar_per_kriteria[2] as $komentar) {
            $html .= '<tr>
                        <td style="text-align: left; width: 290px;">' . htmlspecialchars($komentar['nama_lengkap']) . '</td>
                        <td style="text-align: left; width: 520px;">' . htmlspecialchars($komentar['komentar']) . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, true, false, '');
    }

    if (isset($komentar_per_kriteria[4])) { // Kriteria 4: Lagu Kedua
        $pdf->SetXY(20, $pdf->GetY() + 2);

        // Tampilkan judul "Lagu Kedua" dengan huruf tebal dan di tengah
        $pdf->SetFont('helvetica', 'B', 15); // Mengatur font menjadi helvetica, Bold, ukuran 12
        $pdf->Cell(0, 10, 'Lagu Kedua ', 0, 1, 'C'); // 'C' untuk center

        $pdf->SetFont('Times', '', 16); // Kembali ke font normal untuk isi lagu


        // Tampilkan isi lagu kedua
        $pdf->Cell(0, 10, htmlspecialchars($komentar_per_kriteria[4][0]['lagu_kedua']), 0, 1, 'C');

        $html = '<table border="1" cellpadding="5">
                    <thead>
                        <tr>
                            <th style="text-align: center; font-weight: bold; width: 290px;">Nama Juri</th>
                            <th style="text-align: center; font-weight: bold; width: 520px;">Komentar</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($komentar_per_kriteria[4] as $komentar) {
            $html .= '<tr>
                        <td style="text-align: left; width: 290px;">' . htmlspecialchars($komentar['nama_lengkap']) . '</td>
                        <td style="text-align: left; width: 520px;">' . htmlspecialchars($komentar['komentar']) . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, true, false, '');
    }


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

    // Mengeluarkan dokumen PDF
    $pdf->Output('Rangkuman Komentar Juri terhadap ' . $nama_alternatif . '.pdf', 'I');
} else {
    header("Location: ../login.php");
}
