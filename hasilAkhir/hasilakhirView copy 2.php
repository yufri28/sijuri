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

    // Mendapatkan peringkat berdasarkan nilai rata-rata akhir
    $alternatif_dengan_peringkat = berikanPeringkat($nilai_akhir, $alternatif);

?>

    <!DOCTYPE html>
    <html lang="en" dir="ltr">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Penilaian Lomba Paduan Suara</title>
        <link rel="stylesheet" href="../assets/css/style.css" />
        <!--Bootstrap -->
        <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css" />
        <!-- Boxicons CDN Link -->
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
        <!-- Icons Fontawesome -->
        <link rel="stylesheet" href="../fontawesome/css/all.css">
        <!-- Icon Musik -->
        <link rel="shortcut icon" href="../assets/img/logoMusik.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
        <!-- Tables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css">
        <!-- Chart -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>

    <body>
        <?php include "../assets/basic/sidebar.php"; ?>
        <!-- START SECTION -->
        <section class="home-section">
            <div class="text">Data Hasil Akhir
                <div class="container-hasil">
                    <div class="card mt-4">
                        <div class="card-header fa-regular fa-rectangle-list">
                            <span><b>Daftar Hasil Akhir</b></span>
                        </div>
                        <div class="card-body">
                            <!-- Button Cetak dan Tampilkan Semua Grafik -->
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" id="btnCetakData" class="btn btn-success d-flex align-items-center">
                                    <div class="fa-solid fa-print"></div><span class="ms-2">Cetak Data</span>
                                </button>
                            </div>
                            <!-- Menampilkan daftar hasil akhir -->
                            <?php
                            if (!empty($alternatif_dengan_peringkat)) :
                            ?>
                                <table id="example" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No</th>
                                            <th style="text-align: center;">Nama Paduan Suara</th>
                                            <?php $i = 1;
                                            foreach ($users as $user) : ?>
                                                <th style="text-align: center;" title="Nilai dari <?= $user['nama_lengkap'] ?>">Juri <?= $i++ ?></th>
                                            <?php endforeach; ?>
                                            <th style="text-align: center;" title="Gabungan nilai semua juri">Hasil Akhir</th>
                                            <th style="text-align: center;">Kelompok</th>
                                            <th style="text-align: center;">Peringkat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($alternatif_dengan_peringkat as $index => $alt) : ?>
                                            <tr>
                                                <td style="text-align: center;"><?= $index + 1 ?></td>
                                                <td style="text-align: left;"><?= $alt['nama_alternatif'] ?></td>
                                                <?php foreach ($users as $user) : ?>
                                                    <?php
                                                    $id_user = $user['id'];
                                                    $sql_nilai = "SELECT nilai_akhir FROM penilaian 
                                                            WHERE id_alternatif = {$alt['id_alternatif']} 
                                                            AND id = $id_user";
                                                    $result_nilai = $koneksi->query($sql_nilai);
                                                    $nilai = ($result_nilai && $result_nilai->num_rows > 0) ? $result_nilai->fetch_assoc()['nilai_akhir'] : '-';
                                                    ?>
                                                    <td style="text-align: center;">
                                                        <?php if ($nilai != '-') : ?>
                                                            <?php echo number_format($nilai, 3); ?>
                                                        <?php else : ?>
                                                            <div class="alert alert-danger alert-dismissible fade show py-1 px-2 m-0" role="alert" style="font-size: 0.8rem;">
                                                                Belum diberikan nilai
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                <?php endforeach; ?>
                                                <td style="text-align: center;"><?= number_format($nilai_akhir[$alt['id_alternatif']], 3) ?></td>
                                                <td style="text-align: center;"><?= getKelompok(number_format($nilai_akhir[$alt['id_alternatif']], 3)) ?></td>
                                                <td style="text-align: center;"><?= $alt['peringkat'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <div class="alert alert-warning" role="alert">
                                    Tidak ada hasil akhir.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END SECTION -->

        <script src="//code.jquery.com/jquery-3.7.1.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="//cdn.datatables.net/2.0.2/js/dataTables.js"></script>
        <script src="//cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>
        <script src="../assets/js/script.js"></script>
        <script src="../bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>

        <script>
            new DataTable('#example');
        </script>
        <script>
            // Ketika dokumen siap
            $(document).ready(function() {
                // Ketika tombol cetak data diklik
                $("#btnCetakData").click(function() {
                    // Mengarahkan ke halaman hasilakhirCetak.php
                    window.location.href = "hasilakhirCetak.php";
                });
            });
        </script>
        
    </body>

    </html>

<?php
} else {
    header("Location: ../index.php");
    exit();
}
?>