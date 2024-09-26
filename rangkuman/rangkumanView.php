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
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-success btnCetakData" data-id="<?= $alternatif_item['id_alternatif'] ?>">
                                                            <i class="fa-solid fa-print" aria-hidden="true"></i>
                                                        </button>
                                                    </td>
                                                </tr>

                                                <!-- Awal Modal Lihat Komentar -->
                                                <div class="modal fade" id="modalLihatKomentar<?= $alternatif_item['id_alternatif'] ?>" tabindex="-1" aria-labelledby="modalLihatKomentarLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalLihatKomentarLabel">Komentar untuk <?= htmlspecialchars($alternatif_item['nama_alternatif']) ?></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Tempat untuk menampilkan komentar -->
                                                                <?php
                                                                // Query untuk mendapatkan komentar
                                                                $sql_komentar = "SELECT penilaian.komentar, user.nama_lengkap, penilaian.id_kriteria, alternatif.nama_alternatif, alternatif.lagu_pertama, alternatif.lagu_kedua 
                                                                                    FROM penilaian 
                                                                                    INNER JOIN user ON penilaian.id = user.id 
                                                                                    JOIN alternatif ON penilaian.id_alternatif = alternatif.id_alternatif
                                                                                    WHERE penilaian.id_alternatif = '{$alternatif_item['id_alternatif']}'
                                                                                    GROUP BY user.id, penilaian.id_kriteria
                                                                                    ORDER BY user.id ASC"; // Mengurutkan berdasarkan id user

                                                                $result_komentar = $koneksi->query($sql_komentar);
                                                                if ($result_komentar && $result_komentar->num_rows > 0) {
                                                                    // Array untuk menyimpan komentar berdasarkan kriteria
                                                                    $komentar_per_kriteria = [];

                                                                    // Loop untuk mengelompokkan komentar berdasarkan id_kriteria
                                                                    while ($row_komentar = $result_komentar->fetch_assoc()) {
                                                                        $komentar_per_kriteria[$row_komentar['id_kriteria']][] = $row_komentar;
                                                                    }

                                                                    // Loop untuk menampilkan komentar terpisah berdasarkan kriteria
                                                                    foreach ($komentar_per_kriteria as $id_kriteria => $komentar_list) {

                                                                        // Jika id_kriteria = 2, tampilkan lagu_pertama
                                                                        if ($id_kriteria == 2) {
                                                                            echo "<p class='lagu-pertama'>" . htmlspecialchars($komentar_list[0]['lagu_pertama']) . "</p>";
                                                                        }

                                                                        // Jika id_kriteria = 4, tampilkan lagu_kedua
                                                                        if ($id_kriteria == 4) {
                                                                            echo "<p class='lagu-kedua'>" . htmlspecialchars($komentar_list[0]['lagu_kedua']) . "</p>";
                                                                        }

                                                                        foreach ($komentar_list as $komentar) {
                                                                            echo "<div class='card mb-3'>";
                                                                            echo "<div class='card-body'>";
                                                                            echo "<h6 class='card-subtitle mb-2 text-muted nama-komentator'>" . htmlspecialchars($komentar['nama_lengkap']) . "</h6>";
                                                                            echo "<p class='card-text komentar'>" . htmlspecialchars($komentar['komentar']) . "</p>";
                                                                            echo "</div>";
                                                                            echo "</div>";
                                                                        }
                                                                        echo "<br>";
                                                                    }
                                                                } else {
                                                                    echo "<p class='text-center'>Tidak ada komentar.</p>";
                                                                }
                                                                ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Akhir Modal Lihat Komentar -->

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
    $(document).ready(function() {
        $(".btnCetakData").click(function() {
            var idAlternatif = $(this).data("id");
            window.location.href = "rangkumanCetak.php?id_alternatif=" + idAlternatif;
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