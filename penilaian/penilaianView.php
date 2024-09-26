<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama']) && $_SESSION['level'] !== 'admin') {

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
            <div class="text">Data Penilaian
                <div class="container-penilaian">
                    <div class="card mt-4">
                        <div class="card-header fa-regular fa-rectangle-list">
                            <span><b>Daftar Data Penilaian</b></span>
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
                                                // Loop untuk setiap item alternatif
                                                $no = 1;
                                                foreach ($alternatif as $alternatif_item) : ?>
                                                    <tr>
                                                        <td style="text-align: center;"><?= $no++ ?></td>
                                                        <td style="text-align: left;"><?= $alternatif_item['nama_alternatif'] ?></td>
                                                        <td style="text-align: center;"><?= $alternatif_item['nomor_urut'] ?></td>
                                                        <?php
                                                        // Mendefinisikan variabel untuk menampung nilai id_alternatif dari array $alternatif_item
                                                        $id_alternatif = $alternatif_item['id_alternatif'];

                                                        // Query untuk mengecek apakah sudah ada id_penilaian untuk alternatif saat ini
                                                        $sql_cek_penilaian = "SELECT id_penilaian FROM penilaian WHERE id_alternatif = '$id_alternatif' AND id = '{$_SESSION['id']}'";
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
                                                    // Tampilkan modal penilaian
                                                    ?>
                                                    <!-- Awal Modal Ubah -->
                                                    <div class="modal fade modal-lg" id="modalUbah<?= $alternatif_item['id_alternatif'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="staticBackdropLabel">
                                                                        <span style="margin-left: 205px;">Ubah Data Penilaian Peserta Lomba</span><br>
                                                                        <small><strong>Nama Paduan Suara:</strong> <?= htmlspecialchars($alternatif_item['nama_alternatif']) ?></small><br>
                                                                    </h5>
                                                                </div>
                                                                <form class="row g-3" method="POST" action="penilaianAksi.php">
                                                                    <input type="hidden" name="id_alternatif" value="<?= htmlspecialchars($alternatif_item['id_alternatif']) ?>">
                                                                    <input type="hidden" name="id_user" value="<?= htmlspecialchars($_SESSION['id']) ?>">
                                                                    <div class="modal-body">
                                                                        <?php
                                                                        // Koneksi ke database
                                                                        $koneksi = connectDB();

                                                                        // Query untuk mengambil semua id_kriteria yang unik dari tabel kriteria
                                                                        $sql_kriteria = "SELECT DISTINCT id_kriteria, nama_kriteria FROM kriteria";
                                                                        $result_kriteria = $koneksi->query($sql_kriteria);

                                                                        // Ambil data dari tabel alternatif berdasarkan id_alternatif
                                                                        $sql_alternatif = "SELECT lagu_pertama, lagu_kedua FROM alternatif WHERE id_alternatif = ?";
                                                                        $stmt_alternatif = $koneksi->prepare($sql_alternatif);
                                                                        $stmt_alternatif->bind_param("i", $alternatif_item['id_alternatif']);
                                                                        $stmt_alternatif->execute();
                                                                        $result_alternatif = $stmt_alternatif->get_result();
                                                                        $row_alternatif = $result_alternatif->fetch_assoc();

                                                                        // Periksa apakah query kriteria berhasil dan hasilnya tidak kosong
                                                                        if ($result_kriteria && $result_kriteria->num_rows > 0) {
                                                                            while ($row_kriteria = $result_kriteria->fetch_assoc()) {
                                                                                $id_kriteria = $row_kriteria['id_kriteria'];
                                                                                $nama_kriteria = $row_kriteria['nama_kriteria'];

                                                                                // Ambil judul lagu berdasarkan id_kriteria
                                                                                $judul_lagu = '';
                                                                                if ($id_kriteria == 2) {
                                                                                    $judul_lagu = isset($row_alternatif['lagu_pertama']) ? $row_alternatif['lagu_pertama'] : 'Belum ada judul lagu';
                                                                                } elseif ($id_kriteria == 4) {
                                                                                    $judul_lagu = isset($row_alternatif['lagu_kedua']) ? $row_alternatif['lagu_kedua'] : 'Belum ada judul lagu';
                                                                                }
                                                                                // Tambahkan kondisi untuk lebih banyak lagu jika ada

                                                                        ?>
                                                                                <div class="col-md-12 text-center">
                                                                                    <label for="<?= htmlspecialchars($id_kriteria) ?>" class="form-label"><strong><?= htmlspecialchars($nama_kriteria) ?></strong></label><br>
                                                                                    <small class="judullagu">Judul Lagu: <?= htmlspecialchars($judul_lagu) ?></small>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="row">
                                                                                        <?php
                                                                                        // Query untuk mengambil nilai penilaian sebelumnya untuk kriteria ini
                                                                                        $sql_penilaian = "SELECT sub.subkriteria_id, sub.subkriteria_keterangan, pen.nilai, pen.komentar
                                                                                                            FROM subkriteria sub
                                                                                                            LEFT JOIN penilaian pen ON sub.subkriteria_id = pen.subkriteria_id 
                                                                                                            AND pen.id_kriteria = $id_kriteria
                                                                                                            AND pen.id_alternatif = {$alternatif_item['id_alternatif']}
                                                                                                            WHERE pen.id = {$_SESSION['id']}";
                                                                                        $result_penilaian = $koneksi->query($sql_penilaian);

                                                                                        if ($result_penilaian && $result_penilaian->num_rows > 0) {
                                                                                            while ($row_subkriteria = $result_penilaian->fetch_assoc()) {
                                                                                                $subkriteria_id = $row_subkriteria['subkriteria_id'];
                                                                                                $subkriteria_keterangan = $row_subkriteria['subkriteria_keterangan'];
                                                                                                $nilai_subkriteria = $row_subkriteria['nilai'];
                                                                                                $komentar_subkriteria = $row_subkriteria['komentar']; // Ambil komentar
                                                                                        ?>
                                                                                                <div class="col-md-6">
                                                                                                    <label class="form-label"><?= htmlspecialchars($subkriteria_keterangan) ?></label>
                                                                                                    <input type="text" title="Silakan masukkan nilai antara 1 sampai 100" class="form-control" name="nilai[<?= htmlspecialchars($id_kriteria) ?>][<?= htmlspecialchars($subkriteria_id) ?>]" min="1" max="100" value="<?= htmlspecialchars($nilai_subkriteria) ?>" required pattern="^(100(\.0+)?|[1-9]?\d(\.\d+)?)$" oninvalid="this.setCustomValidity('Nilai belum diisi atau di luar batas (1-100)')" onchange="try{setCustomValidity('')}catch(e){}" autocomplete="off">
                                                                                                </div>
                                                                                        <?php
                                                                                            }
                                                                                        } else {
                                                                                            echo "<div class='col-md-12'><p>Tidak ada subkriteria</p></div>";
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <hr>
                                                                                    <!-- Tambahkan textarea untuk komentar -->
                                                                                    <label for="komentar_<?= htmlspecialchars($id_kriteria) ?>" class="form-label text-center d-block">Komentar:</label>
                                                                                    <textarea class="form-control" name="komentar[<?= htmlspecialchars($id_kriteria) ?>]" id="komentar_<?= htmlspecialchars($id_kriteria) ?>" rows="3" placeholder="Memberikan komentar untuk lagu ini" oninvalid="this.setCustomValidity('Silakan memberikan komentar untuk lagu ini')" onchange="try{setCustomValidity('')}catch(e){}" required><?= htmlspecialchars($komentar_subkriteria) ?></textarea>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <hr>
                                                                                </div>
                                                                        <?php
                                                                            }
                                                                        } else {
                                                                            echo "<div class='col-md-12'><p>Tidak ada kriteria</p></div>";
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-primary" name="bubah"><i class="fa-regular fa-save"></i> Simpan Perubahan</button>
                                                                        <button type="reset" value="reset" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Akhir Modal Ubah -->

                                                    <!-- Awal Modal Tambah -->
                                                    <div class="modal fade modal-lg" id="modalTambah<?= htmlspecialchars($alternatif_item['id_alternatif']) ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="staticBackdropLabel">
                                                                        <span style="margin-left: 190px;">Tambah Data Penilaian Peserta Lomba</span><br>
                                                                        <small><strong>Nama Paduan Suara:</strong> <?= htmlspecialchars($alternatif_item['nama_alternatif']) ?></small><br>
                                                                    </h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form class="row g-3" method="POST" action="penilaianAksi.php">
                                                                    <input type="hidden" name="id_alternatif" value="<?= htmlspecialchars($alternatif_item['id_alternatif']) ?>">
                                                                    <input type="hidden" name="id_user" value="<?= htmlspecialchars($_SESSION['id']) ?>">
                                                                    <div class="modal-body">
                                                                        <?php
                                                                        $koneksi = connectDB();
                                                                        $sql_kriteria = "SELECT DISTINCT id_kriteria, nama_kriteria FROM kriteria";
                                                                        $result_kriteria = $koneksi->query($sql_kriteria);

                                                                        if ($result_kriteria && $result_kriteria->num_rows > 0) {
                                                                            // Ambil semua data alternatif terlebih dahulu
                                                                            $sql_alternatif = "SELECT * FROM alternatif WHERE id_alternatif = {$alternatif_item['id_alternatif']}";
                                                                            $result_alternatif = $koneksi->query($sql_alternatif);
                                                                            $row_alternatif = $result_alternatif->fetch_assoc();

                                                                            while ($row_kriteria = $result_kriteria->fetch_assoc()) {
                                                                                $id_kriteria = $row_kriteria['id_kriteria'];
                                                                                $nama_kriteria = $row_kriteria['nama_kriteria'];
                                                                                // Ambil judul lagu berdasarkan id_kriteria
                                                                                $judul_lagu = '';
                                                                                if ($id_kriteria == 2) {
                                                                                    $judul_lagu = isset($row_alternatif['lagu_pertama']) ? $row_alternatif['lagu_pertama'] : 'Belum ada judul lagu';
                                                                                } elseif ($id_kriteria == 4) {
                                                                                    $judul_lagu = isset($row_alternatif['lagu_kedua']) ? $row_alternatif['lagu_kedua'] : 'Belum ada judul lagu';
                                                                                }
                                                                                // Tambahkan kondisi untuk lebih banyak lagu jika ada

                                                                        ?>
                                                                                <div class="col-md-12 text-center">
                                                                                    <label for="<?= htmlspecialchars($id_kriteria) ?>" class="form-label"><strong><?= htmlspecialchars($nama_kriteria) ?></strong></label><br>
                                                                                    <small class="judullagu">Judul Lagu: <?= htmlspecialchars($judul_lagu) ?></small>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="row">
                                                                                        <?php
                                                                                        $sql_subkriteria = "SELECT subkriteria_id, subkriteria_keterangan FROM subkriteria";
                                                                                        $result_subkriteria = $koneksi->query($sql_subkriteria);

                                                                                        if ($result_subkriteria && $result_subkriteria->num_rows > 0) {
                                                                                            while ($row_subkriteria = $result_subkriteria->fetch_assoc()) {
                                                                                                $subkriteria_id = $row_subkriteria['subkriteria_id'];
                                                                                                $subkriteria_keterangan = $row_subkriteria['subkriteria_keterangan'];
                                                                                        ?>
                                                                                                <div class="col-md-6">
                                                                                                    <label class="form-label"><?= htmlspecialchars($subkriteria_keterangan) ?></label>
                                                                                                    <input type="text" placeholder="Masukkan nilai antara 1-100" title="Silakan masukkan nilai antara 1 sampai 100" class="form-control" name="nilai[<?= htmlspecialchars($id_kriteria) ?>][<?= htmlspecialchars($subkriteria_id) ?>]" min="1" max="100" required pattern="^(100(\.0+)?|[1-9]?\d(\.\d+)?)$" oninvalid="this.setCustomValidity('Nilai belum diisi atau di luar batas (1-100)')" onchange="try{setCustomValidity('')}catch(e){}" autocomplete="off">
                                                                                                </div>
                                                                                        <?php
                                                                                            }
                                                                                        } else {
                                                                                            echo "<div class='col-md-12'><p>Tidak ada subkriteria</p></div>";
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <hr>
                                                                                    <!-- Tambahkan textarea untuk komentar -->
                                                                                    <label for="komentar_<?= htmlspecialchars($id_kriteria) ?>" class="form-label text-center d-block">Komentar:</label>
                                                                                    <textarea class="form-control" name="komentar[<?= htmlspecialchars($id_kriteria) ?>]" id="komentar_<?= htmlspecialchars($id_kriteria) ?>" rows="3" placeholder="Memberikan komentar untuk lagu ini" oninvalid="this.setCustomValidity('Silakan memberikan komentar untuk lagu ini')" onchange="try{setCustomValidity('')}catch(e){}" required></textarea>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <hr>
                                                                                </div>
                                                                        <?php
                                                                            }
                                                                        } else {
                                                                            echo "<div class='col-md-12'><p>Tidak ada kriteria</p></div>";
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-primary" name="bsimpan"><i class="fa-regular fa-floppy-disk"></i> Simpan</button>
                                                                        <button type="reset" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Akhir Modal Tambah -->
                                        <?php
                                                endforeach;
                                            } else {
                                                echo "<tr><td colspan='3'>Tidak ada subkriteria</td></tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='3'>Tidak ada kriteria</td></tr>";
                                        }
                                        ?>
                                    </tbody>

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

    </body>

    </html>

<?php
} else {
    header("Location: ../index.php");
    exit();
}
?>