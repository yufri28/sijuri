<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama'])) {

    // Memanggil Koneksi Database
    require_once "../assets/koneksi.php";
    $koneksi = connectDB(); // Menggunakan fungsi connectDB() untuk mendapatkan koneksi database

    // Fungsi untuk mendapatkan data alternatif
    function getAlternatif($koneksi)
    {
        $sql = "SELECT id_alternatif, nama_alternatif, nomor_urut
                FROM alternatif 
                ORDER BY 
                    CASE 
                        WHEN nomor_urut = 0 THEN 1
                        ELSE 0
                    END, 
                    nomor_urut ASC"; // Menaruh nomor_urut 0 di bagian terakhir
        $result = $koneksi->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $alternatif[] = $row;
            }
            return $alternatif;
        } else {
            return [];
        }
    }

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
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css">
</head>

<body>
    <?php include "../assets/basic/sidebar.php"; ?>
    <!-- START SECTION -->
    <section class="home-section">
        <div class="text">Data Komentar Juri
            <div class="container-penilaian">
                <div class="card mt-4">
                    <div class="card-header fa-regular fa-rectangle-list">
                        <span><b>Daftar Data Komentar Juri</b></span>
                    </div>
                    <div class="card-body">
                        <!-- Menampilkan daftar alternatif -->
                        <?php $alternatif = getAlternatif($koneksi); ?>
                        <?php if (!empty($alternatif)) : ?>
                        <table id="example" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="small-column1" style="text-align: center;">No</th>
                                    <th style="text-align: center; width:980px;">Nama Paduan Suara</th>
                                    <th style="text-align: center; width:80px;">Nomor Urut</th>
                                    <th style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                        // Ambil data kriteria dari database
                                        $sql_kriteria = "SELECT * FROM kriteria";
                                        $result_kriteria = $koneksi->query($sql_kriteria);

                                        // Cek jika query berhasil dan hasilnya tidak kosong
                                        if ($result_kriteria && $result_kriteria->num_rows > 0) {
                                            // Loop untuk setiap item alternatif
                                            $no = 1;
                                            foreach ($alternatif as $alternatif_item) : ?>
                                <tr>
                                    <td style="text-align: center;"><?= $no++ ?></td>
                                    <td style="text-align: left;"><?= $alternatif_item['nama_alternatif'] ?></td>
                                    <td style="text-align: center;"><?= $alternatif_item['nomor_urut'] ?></td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-info" data-bs-toggle="modal"
                                            data-bs-target="#modalLihatKomentar<?= $alternatif_item['id_alternatif'] ?>">
                                            <i class="fa fa-eye" aria-hidden="true"></i> Lihat
                                        </button>
                                        <button class="btn btn-success" data-bs-toggle="modal"
                                            data-bs-target="#modalCetak<?= $alternatif_item['id_alternatif'] ?>">
                                            <i class="fa fa-print" aria-hidden="true"></i> Cetak
                                        </button>
                                    </td>
                                </tr>

                                <!-- Awal Modal Lihat Komentar -->
                                <div class="modal fade" id="modalLihatKomentar<?= $alternatif_item['id_alternatif'] ?>"
                                    tabindex="-1" aria-labelledby="modalLihatKomentarLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalLihatKomentarLabel">Komentar untuk
                                                    <?= htmlspecialchars($alternatif_item['nama_alternatif']) ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Tempat untuk menampilkan komentar -->
                                                <?php
                                                                // Query untuk mendapatkan komentar
                                                                $sql_komentar = "SELECT DISTINCT penilaian.komentar, user.nama_lengkap, alternatif.nama_alternatif, alternatif.lagu_pertama, alternatif.lagu_kedua FROM penilaian 
                                                                                    INNER JOIN user ON penilaian.id = user.id 
                                                                                    JOIN alternatif ON penilaian.id_alternatif = alternatif.id_alternatif
                                                                                    WHERE penilaian.id_alternatif = '{$alternatif_item['id_alternatif']}'
                                                                                ORDER BY id_kriteria ASC";

                                                                $result_komentar = $koneksi->query($sql_komentar);
                                                                if ($result_komentar && $result_komentar->num_rows > 0) {
                                                                    $i = 0;
                                                                    // Loop untuk menampilkan data komentar dan nama lengkap
                                                                    while ($row_komentar = $result_komentar->fetch_assoc()) {
                                                                        echo $i == 0 ? $row_komentar['lagu_pertama']: ($i == 2 ? $row_komentar['lagu_kedua']:'');
                                                                        echo "<div class='card mb-3'>";
                                                                        echo "<div class='card-body'>";
                                                                        echo "<h6 class='card-subtitle mb-2 text-muted'>" . htmlspecialchars($row_komentar['nama_lengkap']) . "</h6>";
                                                                        echo "<p class='card-text'>" . htmlspecialchars($row_komentar['komentar']) . "</p>";
                                                                        echo "</div>";
                                                                        echo "</div>";
                                                                        $i++;
                                                                    }
                                                                } else {
                                                                    echo "<p class='text-center'>Tidak ada komentar.</p>";
                                                                }
                                                                ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Akhir Modal Lihat Komentar -->

                                <!-- Awal Modal Cetak -->
                                <div class="modal fade" id="modalCetak<?= $alternatif_item['id_alternatif'] ?>"
                                    tabindex="-1" aria-labelledby="modalCetakLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalCetakLabel">Cetak Data
                                                    <?= htmlspecialchars($alternatif_item['nama_alternatif']) ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Silakan tekan tombol di bawah untuk mencetak data.</p>
                                                <button type="button" class="btn btn-primary"
                                                    onclick="window.print()">Cetak</button>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Akhir Modal Cetak -->

                                <?php endforeach; ?>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php else : ?>
                        <p>Tidak ada data paduan suara.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
    // Fungsi untuk mereset nilai input number pada modal ubah
    function resetPilihan(button) {
        // Temukan modal yang mengandung tombol reset
        var modal = button.closest('.modal-content');
        // Temukan semua input number di dalam modal
        var inputs = modal.querySelectorAll('input[type="number"]');
        // Atur nilai semua input number menjadi default ('')
        inputs.forEach(function(input) {
            input.value = '';
            input.placeholder = 'Masukkan nilai antara 1-100';
        });
    }

    // Menambahkan event listener untuk klik tombol reset
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.resetBtn').forEach(function(button) {
            button.addEventListener('click', function() {
                resetPilihan(this);
            });
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