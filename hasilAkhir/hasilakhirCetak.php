<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama'])) {

    // Memanggil Koneksi Database
    require_once "../assets/koneksi.php";
    $koneksi = connectDB(); // Menggunakan fungsi connectDB() untuk mendapatkan koneksi database

    require_once "../perhitungan/PerhitunganAksi.php";

    // Mendapatkan tahun_periode dan bulan_periode dari URL
    $tahun_periode = isset($_GET['tahun_periode']) ? $_GET['tahun_periode'] : '';
    $bulan_periode = isset($_GET['bulan_periode']) ? $_GET['bulan_periode'] : '';

    // Periksa apakah tahun_periode dan bulan_periode telah diset
    if (!empty($tahun_periode) && !empty($bulan_periode)) {
        // Gunakan tahun_periode dan bulan_periode dalam query untuk mendapatkan data
        $sql_periode = "SELECT id_periode FROM periode WHERE tahun_periode = '$tahun_periode' AND bulan_periode = '$bulan_periode'";
        $result_periode = $koneksi->query($sql_periode);
        if ($result_periode && $result_periode->num_rows > 0) {
            $row_periode = $result_periode->fetch_assoc();
            $id_periode = $row_periode['id_periode'];

            // Gunakan $id_periode untuk mendapatkan data alternatif dan kriteria yang sesuai dengan periode yang dipilih
            $alternatif = getAlternatif($koneksi);
            $kriteria = getKriteria($koneksi);
            $nilai_akhir = getNilaiAkhir($koneksi, $id_periode);
            $alternatif_dengan_peringkat = berikanPeringkat($nilai_akhir, $alternatif);

            // Mendapatkan data pengguna (users)
            $users = getUser($koneksi);

            // Mengambil data juri dari database
            $sql_juri = "SELECT nama_lengkap FROM user WHERE level = 'juri'";
            $result_juri = $koneksi->query($sql_juri);

            $juri_list = [];
            while ($row_juri = $result_juri->fetch_assoc()) {
                $juri_list[] = $row_juri['nama_lengkap'];
            }

            // Load TCPDF library
            require_once('tcpdf/tcpdf.php');

            // Buat objek TCPDF baru dengan orientasi landscape
            $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

            // Atur informasi dokumen
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Nama Pengarang');
            $pdf->SetTitle('Data Hasil Akhir');
            $pdf->SetSubject('Data Hasil Akhir');
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

            // Tambahkan logo
            $logo = '../assets/img/logo.jpg';
            $pdf->Image($logo, 12, 0, 23, '', 'JPG');

            // Tambahkan teks di sebelah logo dengan style tebal (bold)
            $pdf->SetXY(17, 7);
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->Cell(0, 10, 'Laporan Hasil Akhir Penilaian Lomba Paduan Suara', 0, 0, 'C');

            // Tambahkan teks untuk periode di bagian bawah laporan
            $pdf->SetXY(17, 12);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 10, 'Untuk Periode: ' . $tahun_periode . ' ' . $bulan_periode, 0, 1, 'C');

            // Tambahkan tanggal cetak di ujung kanan atas
            $tanggal = date('d F Y');
            $pdf->SetXY(270 - $pdf->GetStringWidth('Tanggal: ' . $tanggal), 0);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(0, 10, 'Tanggal: ' . $tanggal, 0, 0, 'C');

            // Tambahkan garis di bawah judul dan teks periode, perpanjang ke kanan
            $pdf->SetXY(5, 19);
            $pdf->Cell(285, 0, '', 'B', 1, 'C');

            // Set posisi X dan Y untuk menempatkan tabel di tengah halaman
            $pdf->SetXY(26, 30);

            // Tambahkan tabel
            $html = '<table border="1" cellpadding="5">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 23px; font-weight: bold; vertical-align: middle;">No</th>
                            <th style="text-align: center; width: 100px; font-weight: bold; vertical-align: middle;">Nama Perguruan Tinggi</th>';

            // Tambahkan kolom untuk setiap user
            $i = 1; // Inisialisasi nomor juri
            foreach ($users as $user) {
                $html .= '<th style="text-align: center; font-weight: bold; line-height: 2.5;">Juri ' . $i++ . '</th>';
            }

            $html .= '<th style="text-align: center; font-weight: bold; line-height: 2.5;">Hasil Akhir</th>
                      <th style="text-align: center; font-weight: bold; line-height: 2.5;">Kelompok</th>
                      <th style="text-align: center; font-weight: bold; line-height: 2.5;">Peringkat</th>
                    </tr>
                  </thead>
                  <tbody>';

            // Isi tabel
            $no = 1;
            foreach ($alternatif_dengan_peringkat as $alternatif_item) {
                $html .= '<tr>
                            <td style="text-align: center; width: 23px;">' . $no++ . '</td>
                            <td style="text-align: left; width: 100px;">' . $alternatif_item['nama_alternatif'] . '</td>';

                // Loop untuk menambahkan nilai dari setiap user
                foreach ($users as $user) {
                    $id_user = $user['id'];
                    $sql_nilai = "SELECT nilai_akhir FROM penilaian 
                                  WHERE id_alternatif = {$alternatif_item['id_alternatif']} 
                                  AND id = $id_user 
                                  AND id_periode = $id_periode";
                    $result_nilai = $koneksi->query($sql_nilai);
                    $nilai = ($result_nilai && $result_nilai->num_rows > 0) ? number_format($result_nilai->fetch_assoc()['nilai_akhir'], 2) : '-';

                    $html .= '<td style="text-align: center;">';
                    if ($nilai != '-') {
                        $html .= $nilai;
                    } else {
                        $html .= '<div class="alert alert-danger alert-dismissible fade show py-1 px-2 m-0" role="alert" style="font-size: 0.8rem;">Belum diberikan nilai</div>';
                    }
                    $html .= '</td>';
                }

                // Tambahkan kolom Hasil Akhir, Kelompok, dan Peringkat
                $html .= '<td style="text-align: center;">' . number_format($nilai_akhir[$alternatif_item['id_alternatif']], 2) . '</td>
                          <td style="text-align: center;">' . getKelompok(number_format($nilai_akhir[$alternatif_item['id_alternatif']], 2)) . '</td>
                          <td style="text-align: center;">' . $alternatif_item['peringkat'] . '</td>
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

            // Membuat HTML untuk tabel tanda tangan
            $html_tanda_tangan = '<table cellpadding="5" cellspacing="0" style="width: 100%; text-align: center;">
                <tr>';

            // Menambahkan juri ke tabel
            $count = 0;
            foreach ($juri_list as $index => $nama_juri) {
                // Jika sudah ada 5 juri di baris, buat baris baru
                if ($index % 5 == 0 && $index > 0) {
                    $html_tanda_tangan .= '</tr><tr>';
                }
                $juri_number = $index + 1;
                $html_tanda_tangan .= '<td style="width: 20%;">Juri ' . $juri_number . '<br><br><br><br>(' . htmlspecialchars($nama_juri) . ')</td>';
                $count++;
            }

            // Menambahkan sel kosong jika baris terakhir kurang dari 5 juri
            if ($count % 5 != 0) {
                $html_tanda_tangan .= str_repeat('<td style="width: 20%;"></td>', 5 - ($count % 5));
            }

            $html_tanda_tangan .= '</tr></table>';

            // Tulis tabel tanda tangan ke halaman PDF
            $pdf->writeHTML($html_tanda_tangan, true, false, true, false, '');

            // Tampilkan file PDF dalam browser
            $pdf->Output('Laporan Hasil Akhir Penilaian.pdf', 'I');

            // Exit script
            exit();
        } else {
            // Jika tidak ada data periode yang sesuai dengan yang dipilih, tampilkan pesan kesalahan
            echo "Tidak ada data periode yang sesuai dengan yang dipilih.";
        }
    } else {
        // Jika tahun_periode dan bulan_periode tidak diset, tampilkan pesan kesalahan
        echo "Tahun periode dan bulan periode tidak valid.";
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>
