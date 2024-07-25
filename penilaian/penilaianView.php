<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama']) && $_SESSION['level'] !== 'admin') {

    // Memanggil Koneksi Database
    require_once "../assets/koneksi.php";
    $koneksi = connectDB(); // Menggunakan fungsi connectDB() untuk mendapatkan koneksi database

    // Fungsi untuk mendapatkan data alternatif
    function getAlternatif($koneksi)
    {
        $sql = "SELECT a.id_alternatif, a.nama_alternatif 
        FROM alternatif a 
        JOIN periode p ON a.periode_penilaian = CONCAT(p.tahun_periode, ' ', p.bulan_periode) 
        WHERE p.status_periode = 'Aktif'";
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
        <div class="text">Data Penilaian
            <div class="container-penilaian">
                <div class="card mt-4">
                    <div class="card-header fa-regular fa-rectangle-list">
                        <span><b>Daftar Data Penilaian - Periode
                                <?php
                                    // Ambil tahun dan bulan periode yang aktif dari database
                                    $sql = "SELECT tahun_periode, bulan_periode FROM periode WHERE status_periode = 'Aktif'";
                                    $result = $koneksi->query($sql);

                                    if ($result && $result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        echo $row['tahun_periode'] . ' ' . $row['bulan_periode'];
                                    } else {
                                        echo "Tidak ada periode aktif";
                                    }
                                    ?>
                            </b></span>
                    </div>
                    <div class="card-body">
                        <!-- Menampilkan daftar alternatif -->
                        <?php $alternatif = getAlternatif($koneksi); ?>
                        <?php if (!empty($alternatif)) : ?>
                        <table id="example" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="small-column1" style="text-align: center;">No</th>
                                    <th style="text-align: center; width:980px;">Nama Perguruan Tinggi</th>
                                    <th class="small-column2" style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                        // Ambil data kriteria dari database
                                        $sql_kriteria = "SELECT * FROM kriteria";
                                        $result_kriteria = $koneksi->query($sql_kriteria);

                                        // Cek jika query berhasil dan hasilnya tidak kosong
                                        if ($result_kriteria && $result_kriteria->num_rows > 0) {
                                            // Ambil data subkriteria dari database
                                            $sql_subkriteria = "SELECT * FROM subkriteria";
                                            $result_subkriteria = $koneksi->query($sql_subkriteria);

                                            // Cek jika query subkriteria berhasil dan hasilnya tidak kosong
                                            if ($result_subkriteria && $result_subkriteria->num_rows > 0) {
                                                // Ambil data periode yang sedang aktif
                                                $sql_periode_aktif = "SELECT id_periode FROM periode WHERE status_periode = 'Aktif'";
                                                $result_periode_aktif = $koneksi->query($sql_periode_aktif);

                                                // Jika query berhasil dan hasilnya tidak kosong
                                                if ($result_periode_aktif && $result_periode_aktif->num_rows > 0) {
                                                    // Ambil ID periode yang sedang aktif
                                                    $row_periode_aktif = $result_periode_aktif->fetch_assoc();
                                                    $id_periode_aktif = $row_periode_aktif['id_periode'];
                                                    // Loop untuk setiap item alternatif
                                                    // Persiapan menampilkan data
                                                    $no = 1;
                                                    foreach ($alternatif as $alternatif_item) : ?>
                                <tr>
                                    <td style="text-align: center;"><?= $no++ ?></td>
                                    <td style="text-align: left;"><?= $alternatif_item['nama_alternatif'] ?></td>
                                    <?php
                                                            // Mendefinisikan variabel untuk menampung nilai id_alternatif dari array $alternatif_item
                                                            $id_alternatif = $alternatif_item['id_alternatif'];

                                                            // Query untuk mengecek apakah sudah ada id_penilaian untuk alternatif saat ini
                                                            $sql_cek_penilaian = "SELECT id_penilaian FROM penilaian WHERE id_alternatif = '$id_alternatif' AND id_periode = '$id_periode_aktif' AND id = '{$_SESSION['id']}'";
                                                            $result_cek_penilaian = $koneksi->query($sql_cek_penilaian);

                                                            // Jika query berhasil dieksekusi dan ada data penilaian
                                                            if ($result_cek_penilaian && $result_cek_penilaian->num_rows > 0) {
                                                                // Ambil data penilaian
                                                                $penilaian = $result_cek_penilaian->fetch_assoc();
                                                                $id_penilaian = $penilaian['id_penilaian'];

                                                                // Tombol aksi menjadi "Ubah"
                                                                echo '  <td style="text-align: center;">
                                                                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUbah' . $id_alternatif . '"><i class="fa-regular fa-edit"></i> Ubah</a>
                                                                    </td>';
                                                            } else {
                                                                // Tombol aksi tetap "Masukkan"
                                                                echo '  <td style="text-align: center;">
                                                                        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah' . $id_alternatif . '"><i class="fa-solid fa-plus"></i> Masukkan</a>
                                                                    </td>';
                                                            }
                                                            ?>
                                </tr>
                                <?php
                                                        // Ambil tahun dan bulan periode yang aktif dari database
                                                        $sql_periode_aktif = "SELECT id_periode, tahun_periode, bulan_periode FROM periode WHERE status_periode = 'Aktif'";
                                                        $result_periode_aktif = $koneksi->query($sql_periode_aktif);

                                                        if ($result_periode_aktif && $result_periode_aktif->num_rows > 0) {
                                                            $row_periode_aktif = $result_periode_aktif->fetch_assoc();
                                                            $id_periode_aktif = $row_periode_aktif['id_periode'];
                                                            $tahun_periode_aktif = $row_periode_aktif['tahun_periode'];
                                                            $bulan_periode_aktif = $row_periode_aktif['bulan_periode'];

                                                            // Tampilkan modal penilaian hanya jika periode yang aktif sesuai dengan periode penilaian saat ini
                                                            if ($row['tahun_periode'] == $tahun_periode_aktif && $row['bulan_periode'] == $bulan_periode_aktif) {
                                                        ?>

                                <!-- Awal Modal Ubah -->
                                <div class="modal fade modal-lg" id="modalUbah<?= $alternatif_item['id_alternatif'] ?>"
                                    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <!-- Judul modal disesuaikan dengan nama karyawan kontrak -->
                                                <h5 class="modal-title" id="staticBackdropLabel">
                                                    <span style="margin-left: 205px;">Ubah Data Penilaian Peserta
                                                        Lomba</span><br>
                                                    <small><strong>Nama Perguruan Tinggi:</strong>
                                                        <?= $alternatif_item['nama_alternatif'] ?></small><br>
                                                </h5>
                                            </div>
                                            <!-- Form untuk mengubah data penilaian -->
                                            <form class="row g-3" method="POST" action="penilaianAksi.php">
                                                <!-- Input hidden untuk menyimpan id_alternatif -->
                                                <input type="hidden" name="id_alternatif"
                                                    value="<?= $alternatif_item['id_alternatif'] ?>">
                                                <input type="hidden" name="id_periode"
                                                    value="<?= $row_periode_aktif['id_periode'] ?>">
                                                <input type="hidden" name="id_user" value="<?= $_SESSION['id'] ?>">
                                                <!-- Bagian tubuh modal -->
                                                <div class="modal-body">
                                                    <?php
                                                                                    // Koneksi ke database
                                                                                    $koneksi = connectDB();
                                                                                    // Query untuk mengambil semua id_kriteria yang unik dari tabel kriteria
                                                                                    $sql_kriteria = "SELECT DISTINCT id_kriteria, nama_kriteria FROM kriteria";
                                                                                    $result_kriteria = $koneksi->query($sql_kriteria);
                                                                                    // Periksa apakah query berhasil dan hasilnya tidak kosong
                                                                                    if ($result_kriteria && $result_kriteria->num_rows > 0) {
                                                                                        // Loop melalui setiap baris hasil query untuk kriteria
                                                                                        while ($row_kriteria = $result_kriteria->fetch_assoc()) {
                                                                                            $id_kriteria = $row_kriteria['id_kriteria'];
                                                                                            $nama_kriteria = $row_kriteria['nama_kriteria'];

                                                                                            // Ambil nilai penilaian sebelumnya untuk kriteria ini
                                                                                            $sql_penilaian = "SELECT sub.subkriteria_id, sub.subkriteria_keterangan, pen.nilai
                                                                                                                FROM subkriteria sub
                                                                                                                LEFT JOIN penilaian pen ON sub.subkriteria_id = pen.subkriteria_id 
                                                                                                                AND pen.id_kriteria = $id_kriteria
                                                                                                                AND pen.id_alternatif = {$alternatif_item['id_alternatif']}
                                                                                                                AND pen.id_periode = {$row_periode_aktif['id_periode']}
                                                                                                                WHERE pen.id = {$_SESSION['id']}";
                                                                                            $result_penilaian = $koneksi->query($sql_penilaian);
                                                                                    ?>
                                                    <!-- Label untuk setiap kriteria -->
                                                    <div class="col-md-12 text-center">
                                                        <label for="<?= $nama_kriteria ?>"
                                                            class="form-label"><strong><?= $nama_kriteria ?></strong></label><br>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <?php
                                                                                                    // Periksa apakah query subkriteria berhasil dan hasilnya tidak kosong
                                                                                                    if ($result_penilaian && $result_penilaian->num_rows > 0) {
                                                                                                        // Loop melalui setiap baris hasil query untuk subkriteria
                                                                                                        while ($row_subkriteria = $result_penilaian->fetch_assoc()) {
                                                                                                            $subkriteria_id = $row_subkriteria['subkriteria_id'];
                                                                                                            $subkriteria_keterangan = $row_subkriteria['subkriteria_keterangan'];
                                                                                                            $nilai_subkriteria = $row_subkriteria['nilai'];
                                                                                                    ?>
                                                            <div class="col-md-6">
                                                                <label
                                                                    class="form-label"><?= $subkriteria_keterangan ?></label>
                                                                <input type="number"
                                                                    title="Silakan masukkan nilai antara 1 sampai 100"
                                                                    class="form-control"
                                                                    name="nilai[<?= $id_kriteria ?>][<?= $subkriteria_id ?>]"
                                                                    min="1" max="100" value="<?= $nilai_subkriteria ?>"
                                                                    required
                                                                    oninvalid="this.setCustomValidity('Nilai belum diisi atau di luar batas (1-100)')"
                                                                    onchange="try{setCustomValidity('')}catch(e){}">
                                                            </div>
                                                            <?php
                                                                                                        }
                                                                                                    } else {
                                                                                                        // Jika tidak ada subkriteria yang ditemukan, berikan pesan bahwa tidak ada subkriteria
                                                                                                        echo "<div class='col-md-12'><p>Tidak ada subkriteria</p></div>";
                                                                                                    }
                                                                                                    ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <hr>
                                                    </div>
                                                    <?php
                                                                                        }
                                                                                    } else {
                                                                                        // Jika tidak ada kriteria yang ditemukan, berikan pesan bahwa tidak ada kriteria
                                                                                        echo "<div class='col-md-12'><p>Tidak ada kriteria</p></div>";
                                                                                    }
                                                                                    ?>
                                                </div>
                                                <!-- Bagian footer modal -->
                                                <div class="modal-footer">
                                                    <!-- Tombol untuk mereset nilai menjadi "Pilih subkriteria" -->
                                                    <button type="button" class="btn btn-warning resetBtn"
                                                        data-modalid="<?= $alternatif_item['id_alternatif'] ?>"><i
                                                            class="fa-solid fa-rotate-right"></i> Reset Pilihan</button>
                                                    <!-- Tombol untuk menyimpan perubahan -->
                                                    <button type="submit" class="btn btn-primary" name="bubah"><i
                                                            class="fa-regular fa-save"></i> Simpan Perubahan</button>
                                                    <!-- Tombol untuk keluar dari modal -->
                                                    <button type="reset" value="reset" class="btn btn-danger"
                                                        data-bs-dismiss="modal"><i
                                                            class="fa-solid fa-right-from-bracket"></i> Keluar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Akhir Modal Ubah -->

                                <!-- Awal Modal Tambah -->
                                <div class="modal fade modal-lg"
                                    id="modalTambah<?= $alternatif_item['id_alternatif'] ?>" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <!-- Judul modal disesuaikan dengan nama Perguruan Tinggi -->
                                                <h5 class="modal-title" id="staticBackdropLabel">
                                                    <span style="margin-left: 190px;">Tambah Data Penilaian Peserta
                                                        Lomba</span><br>
                                                    <small><strong>Nama Perguruan Tinggi:</strong>
                                                        <?= $alternatif_item['nama_alternatif'] ?></small><br>
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form class="row g-3" method="POST" action="penilaianAksi.php">
                                                <input type="hidden" name="id_alternatif"
                                                    value="<?= $alternatif_item['id_alternatif'] ?>">
                                                <input type="hidden" name="id_periode"
                                                    value="<?= $row_periode_aktif['id_periode'] ?>">
                                                <input type="hidden" name="id_user" value="<?= $_SESSION['id'] ?>">
                                                <div class="modal-body">
                                                    <?php
                                                                                    $koneksi = connectDB();
                                                                                    // Query untuk mengambil semua id_kriteria yang unik dari tabel kriteria
                                                                                    $sql_kriteria = "SELECT DISTINCT id_kriteria, nama_kriteria FROM kriteria";
                                                                                    $result_kriteria = $koneksi->query($sql_kriteria);
                                                                                    // Periksa apakah query berhasil dan hasilnya tidak kosong
                                                                                    if ($result_kriteria && $result_kriteria->num_rows > 0) {
                                                                                        // Loop melalui setiap baris hasil query untuk kriteria
                                                                                        while ($row_kriteria = $result_kriteria->fetch_assoc()) {
                                                                                            $id_kriteria = $row_kriteria['id_kriteria'];
                                                                                            $nama_kriteria = $row_kriteria['nama_kriteria'];
                                                                                    ?>
                                                    <!-- Label untuk setiap kriteria -->
                                                    <div class="col-md-12 text-center">
                                                        <label
                                                            class="form-label"><strong><?= $nama_kriteria ?></strong></label>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <?php
                                                                                                    // Query untuk mengambil semua subkriteria_keterangan
                                                                                                    $sql_subkriteria = "SELECT subkriteria_id, subkriteria_keterangan FROM subkriteria";
                                                                                                    $result_subkriteria = $koneksi->query($sql_subkriteria);

                                                                                                    // Periksa apakah query subkriteria berhasil dan hasilnya tidak kosong
                                                                                                    if ($result_subkriteria && $result_subkriteria->num_rows > 0) {
                                                                                                        // Loop melalui setiap baris hasil query untuk subkriteria
                                                                                                        while ($row_subkriteria = $result_subkriteria->fetch_assoc()) {
                                                                                                            $subkriteria_id = $row_subkriteria['subkriteria_id'];
                                                                                                            $subkriteria_keterangan = $row_subkriteria['subkriteria_keterangan'];
                                                                                                    ?>
                                                            <div class="col-md-6">
                                                                <label
                                                                    class="form-label"><?= $subkriteria_keterangan ?></label>
                                                                <input type="number"
                                                                    placeholder="Masukkan nilai antara 1-100"
                                                                    title="Silakan masukkan nilai antara 1 sampai 100"
                                                                    class="form-control"
                                                                    name="nilai[<?= $id_kriteria ?>][<?= $subkriteria_id ?>]"
                                                                    min="1" max="100" required
                                                                    oninvalid="this.setCustomValidity('Nilai belum diisi atau di luar batas (1-100)')"
                                                                    onchange="try{setCustomValidity('')}catch(e){}">
                                                            </div>
                                                            <?php
                                                                                                        }
                                                                                                    } else {
                                                                                                        // Jika tidak ada subkriteria yang ditemukan, berikan pesan bahwa tidak ada subkriteria
                                                                                                        echo "<div class='col-md-12'><p>Tidak ada subkriteria</p></div>";
                                                                                                    }
                                                                                                    ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <hr>
                                                    </div>
                                                    <?php
                                                                                        }
                                                                                    } else {
                                                                                        // Jika tidak ada kriteria yang ditemukan, berikan pesan bahwa tidak ada kriteria
                                                                                        echo "<div class='col-md-12'><p>Tidak ada kriteria</p></div>";
                                                                                    }
                                                                                    ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary" name="bsimpan"><i
                                                            class="fa-regular fa-floppy-disk"></i> Simpan</button>
                                                    <button type="reset" class="btn btn-danger"
                                                        data-bs-dismiss="modal"><i
                                                            class="fa-solid fa-right-from-bracket"></i> Keluar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Akhir Modal Tambah -->

                                <?php
                                                            }
                                                        } else {
                                                            // Tidak ada periode aktif, tidak menampilkan modal
                                                            echo "Tidak ada periode aktif";
                                                        }
                                                        ?>
                                <?php
                                                    endforeach;
                                                } else {
                                                    echo "Tidak ada data subkriteria.";
                                                }
                                            } else {
                                                echo "Tidak ada data kriteria.";
                                            }
                                            ?>
                            </tbody>
                            <?php
                                        } else {
                                            // Jika tidak ada periode aktif
                                            echo "<tr><td colspan='4' style='text-align:center;'>Tidak ada periode aktif</td></tr>";
                                        }
                                ?>
                        </table>
                        <?php else : ?>
                        <p>Tidak ada alternatif.</p>
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