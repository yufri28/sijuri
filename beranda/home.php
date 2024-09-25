<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama'])) {

    // Memanggil Koneksi Database
    require_once "../assets/koneksi.php";
    $koneksi = connectDB(); // Menggunakan fungsi connectDB() untuk mendapatkan koneksi database

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
    </head>

    <body>
        <?php include "../assets/basic/sidebar.php"; ?>
        <section class="home-section beranda">
            <div class="text">Beranda
                <div class="container-beranda">
                    <!-- tombol menu -->
                    <nav class="menu">
                        <div class="menu-row button-subkriteria">
                            <button class="menu-button" onclick="window.location.href='../subkriteria/subkriteriaView.php'">
                                <span></span><span></span><span></span><span></span>
                                <?php
                                $query_subkriteria = "SELECT COUNT(*) AS total_subkriteria FROM subkriteria";
                                $result_subkriteria = mysqli_query($koneksi, $query_subkriteria);
                                $row_subkriteria = mysqli_fetch_assoc($result_subkriteria);
                                $total_subkriteria = $row_subkriteria['total_subkriteria'];
                                ?>
                                <?php echo $total_subkriteria; ?> Data Materi Penilaian&nbsp;
                                <i class="fa-solid fa-boxes-stacked"></i>
                            </button>
                            <button class="menu-button" onclick="window.location.href='../alternatif/alternatifView.php'">
                                <span></span><span></span><span></span><span></span>
                                <?php
                                $query_alternatif = "SELECT COUNT(*) AS total_alternatif FROM alternatif";
                                $result_alternatif = mysqli_query($koneksi, $query_alternatif);
                                $row_alternatif = mysqli_fetch_assoc($result_alternatif);
                                $total_alternatif = $row_alternatif['total_alternatif'];
                                ?>
                                <?php echo $total_alternatif; ?> Data Peserta
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="fa-solid fa-users"></i>
                            </button>
                        
                        <?php if ($_SESSION['level'] == 'juri') : ?>
                            
                                <button class="menu-button" onclick="window.location.href='../penilaian/penilaianView.php'">
                                    <span></span><span></span><span></span><span></span>
                                    Data Penilaian
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button class="menu-button" onclick="window.location.href='../perhitungan/perhitunganView.php'">
                                    <span></span><span></span><span></span><span></span>
                                    Data Perhitungan
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <i class="fa-solid fa-calculator"></i>
                                </button>
                            <?php endif; ?>
                            <?php if ($_SESSION['level'] == 'admin') : ?>
                                <button class="menu-button" onclick="window.location.href='../hasilAkhir/hasilakhirView.php'">
                                    <span></span><span></span><span></span><span></span>
                                    Data Hasil Akhir
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <i class="fa-solid fa-chart-column"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                        <?php if ($_SESSION['level'] == 'juri') : ?>
                            <button class="menu-button" onclick="window.location.href='../hasilAkhir/hasilakhirView.php'">
                                <span></span><span></span><span></span><span></span>
                                Data Hasil Akhir
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="fa-solid fa-chart-column"></i>
                            </button>
                </div>
            <?php endif; ?>
            </nav>
            </div>
            </div>
        </section>

        <script src="../assets/js/script.js"></script>
        <script src="../bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>

    </body>

    </html>

<?php
} else {
    header("Location: ../index.php");
    exit();
}
?>