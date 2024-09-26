<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama']) && $_SESSION['level'] !== 'admin') {

    // Memanggil Koneksi Database
    require_once "../assets/koneksi.php";
    $koneksi = connectDB(); // Menggunakan fungsi connectDB() untuk mendapatkan koneksi database

    // Memanggil file perhitunganAksi.php
    require_once "perhitunganAksi.php";

    // Mendapatkan id_user dari sesi
    $id_user = $_SESSION['id'];

    // Dapatkan data kriteria dari database
    $kriteria = getSubkriteria($koneksi);

    // Dapatkan data kriteria dari database
    $kriteria = getKriteria($koneksi);

    // Mendapatkan data alternatif
    $alternatif = getAlternatif($koneksi);

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
            <div class="text">Data Perhitungan Nilai Peserta Lomba
                <!-- AWAL TABEL DAFTAR DATA MATERI PENILAIAN -->
                <div class="container-perhitungan">
                    <div class="card mt-4">
                        <div class="card-header fa-regular fa-rectangle-list">
                            <span><b>Daftar data materi penilaian</b></span>
                        </div>
                        <div class="card-body">
                            <!-- Menampilkan daftar kriteria -->
                            <?php $subkriteria = getSubkriteria($koneksi); ?>
                            <?php if (!empty($subkriteria)) : ?>
                                <table id="tabel-normalisasi-bobot" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No</th>
                                            <th style="text-align: center; width: 945px;">Nama Materi Penilaian</th>
                                            <th style="text-align: center;">Bobot Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Persiapan menampilkan data
                                        $no = 1;
                                        foreach ($subkriteria as $subkriteria_item) : ?>
                                            <tr>
                                                <td style="text-align: center;"><?= $no++ ?></td>
                                                <td style="text-align: left;"><?= $subkriteria_item['subkriteria_keterangan'] ?></td>
                                                <td style="text-align: center;"><?= $subkriteria_item['subkriteria_nilai'] ?>%</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <div class="alert alert-warning" role="alert">
                                    Tidak ada data materi penilaian.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- AKHIR TABEL DAFTAR DATA MATERI PENILAIAN -->

                <!-- AWAL TABEL NILAI ASLI UNTUK SETIAP PESERTA LOMBA -->
                <div class="container-perhitungan">
                    <div class="card mt-4">
                        <div class="card-header fa-regular fa-rectangle-list">
                            <span><b>Nilai asli untuk setiap paduan suara</b></span>
                        </div>
                        <div class="card-body">
                            <?php
                            // Menampilkan nilai kriteria untuk setiap alternatif
                            $alternatif = getAlternatif($koneksi);
                            if (!empty($alternatif)) : ?>
                                <table id="tabel-nilai-kriteria" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No</th>
                                            <th style="text-align: center;">Nama Paduan Suara</th>
                                            <th style="text-align: center; width: 300px;">Materi Penilaian</th>
                                            <?php
                                            $nomor = 1;
                                            foreach ($kriteria as $kriteria_item) : ?>
                                                <th style="text-align: center;" title="Lagu ke-<?= $nomor++ ?>"><?= $kriteria_item['nama_kriteria'] ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($alternatif as $alternatif_item) : ?>
                                            <tr>
                                                <td style="text-align: center;"><?= $no++ ?></td>
                                                <td style="text-align: left;"><?= $alternatif_item['nama_alternatif'] ?></td>
                                                <td style="text-align: left;">
                                                    <?php
                                                    // Menggabungkan keterangan subkriteria
                                                    $subkriteria_keterangan_list = [];
                                                    foreach ($kriteria as $kriteria_item) {
                                                        $id_kriteria = $kriteria_item['id_kriteria'];
                                                        $id_alternatif = $alternatif_item['id_alternatif'];

                                                        // Query untuk mendapatkan keterangan subkriteria dari tabel penilaian berdasarkan id kriteria dan id alternatif
                                                        $sql_nilai_subkriteria = "SELECT subkriteria_keterangan 
                                                                    FROM penilaian 
                                                                    JOIN subkriteria ON penilaian.subkriteria_id = subkriteria.subkriteria_id 
                                                                    WHERE penilaian.id_kriteria = $id_kriteria 
                                                                    AND penilaian.id_alternatif = $id_alternatif";
                                                        $result_nilai_subkriteria = $koneksi->query($sql_nilai_subkriteria);

                                                        // Jika query berhasil dieksekusi dan ada data penilaian
                                                        if ($result_nilai_subkriteria && $result_nilai_subkriteria->num_rows > 0) {
                                                            // Ambil data keterangan subkriteria
                                                            while ($nilai_subkriteria = $result_nilai_subkriteria->fetch_assoc()) {
                                                                $subkriteria_keterangan_list[] = $nilai_subkriteria['subkriteria_keterangan'];
                                                            }
                                                        }
                                                    }
                                                    foreach (array_unique($subkriteria_keterangan_list) as $subketerangan) {
                                                        echo $subketerangan . '<hr style="margin: 2px 0;">';
                                                    }
                                                    ?>
                                                </td>
                                                <?php
                                                foreach ($kriteria as $kriteria_item) {
                                                    $id_kriteria = $kriteria_item['id_kriteria'];
                                                    $id_alternatif = $alternatif_item['id_alternatif'];

                                                    // Query untuk mendapatkan nilai dari tabel penilaian berdasarkan id_kriteria, id_alternatif, dan id_user
                                                    $sql_nilai = "SELECT nilai 
                                                    FROM penilaian 
                                                    WHERE id_kriteria = $id_kriteria 
                                                    AND id_alternatif = $id_alternatif 
                                                    AND id = $id_user";
                                                    $result_nilai = $koneksi->query($sql_nilai);

                                                    echo '<td style="text-align: center; vertical-align: middle;">';
                                                    if ($result_nilai && $result_nilai->num_rows > 0) {
                                                        // Ambil data nilai dan gabungkan dengan pemisah baris
                                                        while ($nilai_data = $result_nilai->fetch_assoc()) {
                                                            echo $nilai_data['nilai'] . '<hr style="margin: 2px 0;">';
                                                        }
                                                    } else {
                                                        echo 'Belum memberikan nilai';
                                                    }
                                                    echo '</td>';
                                                }
                                                ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <div class="alert alert-warning" role="alert">
                                    Tidak ada nilai untuk setiap paduan suara.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- AKHIR TABEL NILAI ASLI UNTUK SETIAP PESERTA LOMBA -->

                <!-- AWAL TABEL NILAI BERBOBOT UNTUK SETIAP PESERTA LOMBA -->
                <div class="container-perhitungan">
                    <div class="card mt-4">
                        <div class="card-header fa-regular fa-rectangle-list">
                            <span><b>Nilai berbobot untuk setiap paduan suara</b></span>
                        </div>
                        <div class="card-body">

                            <!-- Menampilkan nilai berbobot kriteria untuk setiap alternatif -->
                            <?php $alternatif = getAlternatif($koneksi); ?>
                            <?php if (!empty($alternatif)) : ?>
                                <table id="tabel-nilai-kriteria" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No</th>
                                            <th style="text-align: center;">Nama Paduan Suara</th>
                                            <th style="text-align: center; width: 300px;">Materi Penilaian</th>
                                            <?php $nomor = 1; ?>
                                            <?php foreach ($kriteria as $kriteria_item) : ?>
                                                <th style="text-align: center; width: 130px;" title="Lagu ke-<?= $nomor ?>"><?= $kriteria_item['nama_kriteria'] ?></th>
                                                <th style="text-align: center;" title="Total persentase lagu ke-<?= $nomor ?>">Total Persentase</th>
                                                <?php $nomor++; ?>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($alternatif as $alternatif_item) :
                                        ?>
                                            <tr>
                                                <td style="text-align: center;"><?= $no++ ?></td>
                                                <td style="text-align: left;"><?= $alternatif_item['nama_alternatif'] ?></td>
                                                <td style="text-align: left;">
                                                    <?php
                                                    // Menggabungkan keterangan subkriteria
                                                    $subkriteria_keterangan_list = [];
                                                    foreach ($kriteria as $kriteria_item) {
                                                        $id_kriteria = $kriteria_item['id_kriteria'];
                                                        $id_alternatif = $alternatif_item['id_alternatif'];

                                                        // Query untuk mendapatkan keterangan subkriteria dari tabel penilaian berdasarkan id kriteria dan id alternatif
                                                        $sql_nilai_subkriteria = "SELECT subkriteria_keterangan 
                                                                    FROM penilaian 
                                                                    JOIN subkriteria ON penilaian.subkriteria_id = subkriteria.subkriteria_id 
                                                                    WHERE penilaian.id_kriteria = $id_kriteria 
                                                                    AND penilaian.id_alternatif = $id_alternatif";
                                                        $result_nilai_subkriteria = $koneksi->query($sql_nilai_subkriteria);

                                                        // Jika query berhasil dieksekusi dan ada data penilaian
                                                        if ($result_nilai_subkriteria && $result_nilai_subkriteria->num_rows > 0) {
                                                            // Ambil data keterangan subkriteria
                                                            while ($nilai_subkriteria = $result_nilai_subkriteria->fetch_assoc()) {
                                                                $subkriteria_keterangan_list[] = $nilai_subkriteria['subkriteria_keterangan'];
                                                            }
                                                        }
                                                    }
                                                    foreach (array_unique($subkriteria_keterangan_list) as $subketerangan) {
                                                        echo $subketerangan . '<hr style="margin: 2px 0;">';
                                                    }
                                                    ?>
                                                </td>
                                                <?php
                                                foreach ($kriteria as $kriteria_item) {
                                                    $total_persentase = 0; // Reset total persentase untuk setiap kriteria
                                                    $id_kriteria = $kriteria_item['id_kriteria'];
                                                    $id_alternatif = $alternatif_item['id_alternatif'];

                                                    // Query untuk mendapatkan nilai dan subkriteria_id dari tabel penilaian berdasarkan id_kriteria, id_alternatif, dan id_user
                                                    $sql_nilai = "SELECT nilai, subkriteria_id 
                                                    FROM penilaian 
                                                    WHERE id_kriteria = $id_kriteria 
                                                    AND id_alternatif = $id_alternatif 
                                                    AND id = $id_user";
                                                    $result_nilai = $koneksi->query($sql_nilai);

                                                    echo '<td style="text-align: center; vertical-align: middle;">';
                                                    if ($result_nilai && $result_nilai->num_rows > 0) {
                                                        // Ambil data nilai dan subkriteria_id
                                                        while ($nilai_data = $result_nilai->fetch_assoc()) {
                                                            $nilai = $nilai_data['nilai'];
                                                            $subkriteria_id = $nilai_data['subkriteria_id'];

                                                            // Query untuk mendapatkan subkriteria_nilai berdasarkan subkriteria_id
                                                            $sql_subkriteria = "SELECT subkriteria_nilai 
                                                FROM subkriteria 
                                                WHERE subkriteria_id = $subkriteria_id";
                                                            $result_subkriteria = $koneksi->query($sql_subkriteria);

                                                            if ($result_subkriteria && $result_subkriteria->num_rows > 0) {
                                                                $subkriteria_data = $result_subkriteria->fetch_assoc();
                                                                $subkriteria_nilai = $subkriteria_data['subkriteria_nilai'];

                                                                // Hitung nilai berbobot
                                                                $nilai_berbobot = ($nilai * $subkriteria_nilai) / 100;
                                                                echo $nilai_berbobot . '<hr style="margin: 2px 0;">';

                                                                // Tambahkan nilai berbobot ke total persentase
                                                                $total_persentase += $nilai_berbobot;
                                                            } else {
                                                                echo '-';
                                                            }
                                                        }
                                                    } else {
                                                        echo '-';
                                                    }
                                                    echo '</td>';
                                                    // Tampilkan total persentase untuk kriteria ini
                                                    echo '<td style="text-align: center; vertical-align: middle;">' . $total_persentase . '</td>';
                                                }
                                                ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <div class="alert alert-warning" role="alert">
                                    Tidak ada nilai untuk setiap paduan suara.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- AKHIR TABEL NILAI BERBOBOT UNTUK SETIAP PESERTA LOMBA -->

                <!-- AWAL TABEL NILAI AKHIR UNTUK SEMUA LAGU -->
                <div class="container-perhitungan">
                    <div class="card mt-4">
                        <div class="card-header fa-regular fa-rectangle-list">
                            <span><b>Nilai akhir untuk semua lagu</b></span>
                        </div>
                        <div class="card-body">
                            <!-- Menampilkan nilai kriteria untuk setiap alternatif -->
                            <?php
                            $alternatif = getAlternatif($koneksi);
                            if (!empty($alternatif)) : ?>
                                <table id="tabel-nilai-kriteria" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No</th>
                                            <th style="text-align: center;">Nama Paduan Suara</th>
                                            <th style="text-align: center;">Nilai Akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($alternatif as $alternatif_item) :
                                            $id_alternatif = $alternatif_item['id_alternatif'];
                                            $total_persentase = 0;
                                        ?>
                                            <tr>
                                                <td style="text-align: center;"><?= $no++ ?></td>
                                                <td style="text-align: left;"><?= $alternatif_item['nama_alternatif'] ?></td>
                                                <td style="text-align: center;">
                                                    <?php
                                                    foreach ($kriteria as $kriteria_item) {
                                                        $id_kriteria = $kriteria_item['id_kriteria'];

                                                        // Query untuk mendapatkan nilai dan subkriteria_id dari tabel penilaian berdasarkan kriteria dan alternatif
                                                        $sql_nilai = "SELECT nilai, subkriteria_id 
                                                        FROM penilaian 
                                                        WHERE id_kriteria = $id_kriteria 
                                                        AND id_alternatif = $id_alternatif 
                                                        AND id = $id_user";

                                                        $result_nilai = $koneksi->query($sql_nilai);

                                                        if ($result_nilai && $result_nilai->num_rows > 0) {
                                                            while ($nilai_data = $result_nilai->fetch_assoc()) {
                                                                $nilai = $nilai_data['nilai'];
                                                                $subkriteria_id = $nilai_data['subkriteria_id'];

                                                                // Query untuk mendapatkan subkriteria_nilai berdasarkan subkriteria_id
                                                                $sql_subkriteria = "SELECT subkriteria_nilai 
                                                    FROM subkriteria 
                                                    WHERE subkriteria_id = $subkriteria_id";

                                                                $result_subkriteria = $koneksi->query($sql_subkriteria);

                                                                if ($result_subkriteria && $result_subkriteria->num_rows > 0) {
                                                                    $subkriteria_data = $result_subkriteria->fetch_assoc();
                                                                    $subkriteria_nilai = $subkriteria_data['subkriteria_nilai'];

                                                                    // Hitung nilai berbobot
                                                                    $nilai_berbobot = ($nilai * $subkriteria_nilai) / 100;

                                                                    // Tambahkan nilai berbobot ke total persentase
                                                                    $total_persentase += $nilai_berbobot;
                                                                }
                                                            }
                                                        }
                                                    }

                                                    // Hitung nilai akhir berdasarkan total persentase dan jumlah kriteria
                                                    $nilai_akhir = $total_persentase / count($kriteria);

                                                    // Simpan nilai akhir ke tabel penilaian
                                                    $sql_simpan_nilai_akhir = "UPDATE penilaian 
                                                                SET nilai_akhir = $nilai_akhir 
                                                                WHERE id_alternatif = $id_alternatif 
                                                                AND id = $id_user";

                                                    $result_simpan = $koneksi->query($sql_simpan_nilai_akhir);

                                                    if ($result_simpan) {
                                                        echo number_format($nilai_akhir, 3); // Tampilkan nilai akhir dengan format dua desimal
                                                    } else {
                                                        echo "Gagal menyimpan nilai akhir";
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <div class="alert alert-warning" role="alert">
                                    Tidak ada nilai untuk setiap paduan suara.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- AKHIR TABEL NILAI AKHIR UNTUK SEMUA LAGU -->

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
            // Inisialisasi DataTable
            $(document).ready(function() {
                $('#tabel-normalisasi-bobot, #tabel-nilai-kriteria, #tabel-nilai-utility, #tabel-nilai-akhir').DataTable();
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