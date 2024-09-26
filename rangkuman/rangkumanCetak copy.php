<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama'])) {
    // Memanggil Koneksi Database
    require_once "../assets/koneksi.php";
    $koneksi = connectDB();

    // Mendapatkan id_alternatif dari URL
    $id_alternatif = isset($_GET['id_alternatif']) ? intval($_GET['id_alternatif']) : 0;

    // Validasi id_alternatif
    if ($id_alternatif <= 0) {
        die('ID Alternatif tidak valid');
    }

    // Load TCPDF library
    require_once('tcpdf/tcpdf.php');

    // Buat objek TCPDF baru
    $pdf = new TCPDF('L', PDF_UNIT, 'F4', true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Nama Pengarang');
    $pdf->SetTitle('Komentar untuk Alternatif ID ' . $id_alternatif);
    $pdf->SetSubject('Komentar untuk Alternatif');
    $pdf->SetKeywords('TCPDF, komentar, alternatif');
    $pdf->SetMargins(20, 20, 20);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->AddPage();

    // Tampilkan judul
    $pdf->SetXY(20, 20);
    $pdf->Cell(0, 10, 'Komentar untuk Alternatif ID: ' . $id_alternatif, 0, 1, 'C');


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

    // Tampilkan komentar berdasarkan kriteria
    foreach ($komentar_per_kriteria as $id_kriteria => $komentar_list) {
        if ($id_kriteria == 2) { // Kriteria 2
            $pdf->SetXY(20, $pdf->GetY() + 10);
            $pdf->Cell(0, 10, 'Judul Lagu Pertama: ' . htmlspecialchars($komentar_list[0]['lagu_pertama']), 0, 1, 'L');
            $html = '<table border="1" cellpadding="5">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Nama Juri</th>
                                <th style="text-align: center;">Komentar</th>
                            </tr>
                        </thead>
                        <tbody>';
            foreach ($komentar_list as $komentar) {
                $html .= '<tr>
                            <td style="text-align: left;">' . htmlspecialchars($komentar['nama_lengkap']) . '</td>
                            <td style="text-align: left;">' . htmlspecialchars($komentar['komentar']) . '</td>
                          </tr>';
            }
            $html .= '</tbody></table>';
            $pdf->writeHTML($html, true, false, true, false, '');
        } elseif ($id_kriteria == 4) { // Kriteria 4
            $pdf->AddPage();
            $pdf->SetXY(20, 20);
            $pdf->Cell(0, 10, 'Judul Lagu Kedua: ' . htmlspecialchars($komentar_list[0]['lagu_kedua']), 0, 1, 'L');
            $html = '<table border="1" cellpadding="5">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Nama Juri</th>
                                <th style="text-align: center;">Komentar</th>
                            </tr>
                        </thead>
                        <tbody>';
            foreach ($komentar_list as $komentar) {
                $html .= '<tr>
                            <td style="text-align: left;">' . htmlspecialchars($komentar['nama_lengkap']) . '</td>
                            <td style="text-align: left;">' . htmlspecialchars($komentar['komentar']) . '</td>
                          </tr>';
            }
            $html .= '</tbody></table>';
            $pdf->writeHTML($html, true, false, true, false, '');
        }
    }

    // Mengeluarkan dokumen PDF
    $pdf->Output('Komentar_Alternatif_ID_' . $id_alternatif . '.pdf', 'I');
} else {
    header("Location: ../login.php");
}
