<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama'])) {
    // Misalkan informasi level pengguna disimpan dalam session
    if (isset($_SESSION['level']) && $_SESSION['level'] === 'admin') {
        $margin = '100px'; // Jika level adalah admin, set margin-left ke 100px
    } else {
        $margin = '150px'; // Jika bukan admin, set margin-left ke 150px
    }

    // Memanggil Koneksi Database
    require_once "../assets/koneksi.php";
    $koneksi = connectDB(); // Menggunakan fungsi connectDB() untuk mendapatkan koneksi database
    // Fungsi untuk mendapatkan data alternatif berdasarkan nomor urut, kecuali 0 di akhir
    function getAlternatif($koneksi)
    {
        $sql = "SELECT *
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


    // Fungsi untuk mendapatkan data kriteria
    function getKriteria($koneksi)
    {
        $sql = "SELECT nama_kriteria FROM kriteria"; // Sesuaikan dengan tabel dan kolom yang ada
        $result = $koneksi->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $kriteria[] = $row;
            }
            return $kriteria;
        } else {
            return [];
        }
    }

    $kriteria = getKriteria($koneksi);

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
            <div class="text">Data Peserta Lomba
                <div class="container-alternatif">
                    <div class="card mt-4">
                        <div class="card-header fa-regular fa-rectangle-list">
                            <span><b>Daftar Data Peserta Lomba</b></span>
                        </div>
                        <div class="card-body">
                            <?php if ($_SESSION['level'] == 'admin') : ?>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                    <div class="fa-solid fa-plus"></div><span> Tambah Data</span>
                                </button>
                            <?php endif; ?>
                            <!-- Menampilkan daftar alternatif -->
                            <?php $alternatif = getAlternatif($koneksi); ?>
                            <?php if (!empty($alternatif)) : ?>
                                <table id="example" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No</th>
                                            <?php if ($_SESSION['level'] == 'admin') : ?>
                                                <th style="text-align: center;">Nama Paduan Suara</th>
                                            <?php endif; ?>
                                            <?php if ($_SESSION['level'] == 'juri') : ?>
                                                <th style="text-align: center; width: 760px;">Nama Paduan Suara</th>
                                            <?php endif; ?>
                                            <th style="text-align: center;  width: 210px;">Nomor Urut Tampil</th>
                                            <th style="text-align: center;  width: 210px;">Dirigen</th>
                                            <?php foreach ($kriteria as $item) : ?>
                                                <th style="text-align: center; width: 210px;"><?= htmlspecialchars($item['nama_kriteria']) ?></th>
                                                <?php if ($_SESSION['level'] == 'admin') : ?>
                                                    <th style="text-align: center; width: 150px;">Durasi Lagu</th>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            <?php if ($_SESSION['level'] == 'admin') : ?>
                                                <th style="text-align: center; width: 150px;">Total Durasi Lagu</th>
                                                <th style="text-align: center; width: 160px;">Aksi</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Persiapan menampilkan data
                                        $no = 1;
                                        foreach ($alternatif as $alternatif_item) : ?>
                                            <tr>
                                                <td style="text-align: center;"><?= $no++ ?></td>
                                                <td style="text-align: left;"><?= $alternatif_item['nama_alternatif'] ?></td>
                                                <td style="text-align: center;"><?= $alternatif_item['nomor_urut'] ?></td>
                                                <td style="text-align: left;"><?= $alternatif_item['dirigen'] ?></td>
                                                <td style="text-align: left;"><?= $alternatif_item['lagu_pertama'] ?></td>
                                                <?php if ($_SESSION['level'] == 'admin') : ?>
                                                    <td style="text-align: left;"><?= $alternatif_item['durasi_pertama'] ?></td>
                                                <?php endif; ?>
                                                <td style="text-align: left;"><?= $alternatif_item['lagu_kedua'] ?></td>
                                                <?php if ($_SESSION['level'] == 'admin') : ?>
                                                    <td style="text-align: left;"><?= $alternatif_item['durasi_kedua'] ?></td>
                                                    <td style="text-align: left;"><?= $alternatif_item['total_durasi'] ?></td>
                                                    <td>
                                                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUbah<?= $no ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                                                        <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $no ?>"><i class="fa-regular fa-trash-can"></i></a>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>

                                            <!-- Awal Modal Ubah -->
                                            <div class="modal fade modal-lg" id="modalUbah<?= $no ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="staticBackdropLabel">Ubah Data Peserta</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form class="row g-3" method="POST" action="alternatifAksi.php">
                                                            <input type="hidden" name="id_alternatif" value="<?= $alternatif_item['id_alternatif'] ?>">
                                                            <div class="row g-3 modal-body">
                                                                <!-- Input untuk Nama Paduan Suara -->
                                                                <div class="col-md-6">
                                                                    <label for="inputNama" class="form-label">Nama Paduan Suara</label>
                                                                    <input type="text" name="tnama" value="<?= $alternatif_item['nama_alternatif'] ?>" pattern="[A-Z][a-zA-Z0-9.\- ]{1,}$" title="Silakan diisi dan dimulai dengan huruf kapital" class="form-control" id="inputNama" required autocomplete="off">
                                                                </div>
                                                                <!-- Input untuk Nomor Urut Peserta -->
                                                                <div class="col-md-6">
                                                                    <label for="inputUrut" class="form-label">Nomor Urut Peserta</label>
                                                                    <input type="number" name="turut" value="<?= $alternatif_item['nomor_urut'] ?>" title="Silakan diisi" class="form-control" id="inputUrut" required autocomplete="off">
                                                                </div>
                                                                <!-- Input untuk Dirigen -->
                                                                <div class="col-md-6">
                                                                    <label for="inputDirigen" class="form-label">Dirigen</label>
                                                                    <input type="text" name="tdirigen" value="<?= $alternatif_item['dirigen'] ?>" class="form-control" id="inputDirigen" placeholder="Masukkan nama dirigen" required autocomplete="off">
                                                                </div>
                                                                <!-- Input untuk Lagu ke-1 -->
                                                                <div class="col-md-6">
                                                                    <label for="inputLaguPertama" class="form-label">Lagu ke-1</label>
                                                                    <input type="text" name="tlagupertama" value="<?= $alternatif_item['lagu_pertama'] ?>" class="form-control" placeholder="Masukkan judul lagu" id="inputLaguPertama" required autocomplete="off">
                                                                </div>
                                                                <!-- Input untuk Durasi Lagu ke-1 -->
                                                                <div class="col-md-6">
                                                                    <label for="inputDurasiPertama" class="form-label">Durasi Lagu ke-1 (dalam menit)</label>
                                                                    <input type="text" name="tdurasipertama" value="<?= $alternatif_item['durasi_pertama'] ?>" class="form-control" placeholder="Masukkan durasi" id="inputDurasiPertama" required autocomplete="off">
                                                                </div>
                                                                <!-- Input untuk Lagu ke-2 -->
                                                                <div class="col-md-6">
                                                                    <label for="inputLaguKedua" class="form-label">Lagu ke-2</label>
                                                                    <input type="text" name="tlagukedua" value="<?= $alternatif_item['lagu_kedua'] ?>" class="form-control" placeholder="Masukkan judul lagu" id="inputLaguKedua" required autocomplete="off">
                                                                </div>
                                                                <!-- Input untuk Durasi Lagu ke-2 -->
                                                                <div class="col-md-6">
                                                                    <label for="inputDurasiKedua" class="form-label">Durasi Lagu ke-2 (dalam menit)</label>
                                                                    <input type="text" name="tdurasikedua" value="<?= $alternatif_item['durasi_kedua'] ?>" class="form-control" placeholder="Masukkan durasi" id="inputDurasiKedua" required autocomplete="off">
                                                                </div>
                                                                <!-- Input untuk Total Durasi -->
                                                                <div class="col-md-6">
                                                                    <label for="inputTotalDurasi" class="form-label">Total Durasi (dalam menit)</label>
                                                                    <input type="text" style="background-color: #f0f0f0;" name="ttotal_durasi" value="<?= $alternatif_item['total_durasi'] ?>" class="form-control" id="inputTotalDurasi" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary" name="bubah"><i class="fa-regular fa-floppy-disk"></i> Ubah data</button>
                                                                <button type="reset" value="reset" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Akhir Modal Ubah -->

                                            <!-- Awal Modal Hapus -->
                                            <div class="modal fade modal-lg" id="modalHapus<?= $no ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="staticBackdropLabel">Konfirmasi Hapus Data Peserta</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form class="row g-3" method="POST" action="alternatifAksi.php">
                                                            <input type="hidden" name="id_alternatif" value="<?= $alternatif_item['id_alternatif'] ?>">
                                                            <div class="row g-3 modal-body">
                                                                <h5 class="text-center"> Apakah anda yakin akan menghapus data ini? <br>
                                                                    <span class="text-danger"><?= $alternatif_item['nama_alternatif'] ?> - <?= $alternatif_item['nomor_urut'] ?></span>
                                                                </h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary" name="bhapus"><i class="fa-regular fa-trash-can"></i> Ya, hapus saja!</button>
                                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Akhir Modal Hapus -->
                                        <?php endforeach; ?>

                                    </tbody>
                                </table>
                            <?php else : ?>
                                <div class="alert alert-warning" role="alert">
                                    Tidak ada data peserta.
                                </div>
                            <?php endif; ?>

                            <!-- Awal Modal Tambah -->
                            <div class="modal fade modal-lg" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Tambah Data Peserta</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form class="row g-3" method="POST" action="alternatifAksi.php">
                                            <div class="row g-3 modal-body">
                                                <!-- Input untuk Nama Paduan Suara -->
                                                <div class="col-md-6">
                                                    <label for="inputNama" class="form-label">Nama Paduan Suara</label>
                                                    <input type="text" name="tnama" pattern="[A-Z][a-zA-Z0-9.\- ]{1,}$" title="Silakan diisi dan dimulai dengan huruf kapital" class="form-control" id="inputNama" required autocomplete="off">
                                                </div>
                                                <!-- Input untuk Nomor Urut Peserta -->
                                                <div class="col-md-6">
                                                    <label for="inputUrut" class="form-label">Nomor Urut Peserta</label>
                                                    <input type="number" name="turut" title="Silakan diisi" class="form-control" id="inputUrut" required autocomplete="off">
                                                </div>
                                                <!-- Input untuk Dirigen -->
                                                <div class="col-md-6">
                                                    <label for="inputDirigen" class="form-label">Dirigen</label>
                                                    <input type="text" name="tdirigen" class="form-control" id="inputDirigen" placeholder="Masukkan nama dirigen" required autocomplete="off">
                                                </div>
                                                <!-- Input untuk Lagu ke-1 -->
                                                <div class="col-md-6">
                                                    <label for="inputLaguPertama" class="form-label">Lagu ke-1</label>
                                                    <input type="text" name="tlagupertama" class="form-control" placeholder="Masukkan judul lagu" id="inputLaguPertama" required autocomplete="off">
                                                </div>
                                                <!-- Input untuk Durasi Lagu ke-1 -->
                                                <div class="col-md-6">
                                                    <label for="inputDurasiPertama" class="form-label">Durasi Lagu ke-1 (dalam menit)</label>
                                                    <input type="text" name="tdurasipertama" class="form-control" placeholder="Masukkan durasi lagu (mm:ss)" id="inputDurasiPertama" pattern="\d{2}:\d{2}" title="Format durasi harus mm:ss" required autocomplete="off">
                                                </div>
                                                <!-- Input untuk Lagu ke-2 -->
                                                <div class="col-md-6">
                                                    <label for="inputLaguKedua" class="form-label">Lagu ke-2</label>
                                                    <input type="text" name="tlagukedua" class="form-control" placeholder="Masukkan judul lagu" id="inputLaguKedua" required autocomplete="off">
                                                </div>
                                                <!-- Input untuk Durasi Lagu ke-2 -->
                                                <div class="col-md-6">
                                                    <label for="inputDurasiKedua" class="form-label">Durasi Lagu ke-2 (dalam menit)</label>
                                                    <input type="text" name="tdurasikedua" class="form-control" placeholder="Masukkan durasi lagu (mm:ss)" id="inputDurasiKedua" pattern="\d{2}:\d{2}" title="Format durasi harus mm:ss" required autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary" name="bsimpan"><i class="fa-regular fa-floppy-disk"></i> Tambah data</button>
                                                <button type="reset" value="reset" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Akhir Modal Tambah -->

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
            // Temukan semua textarea dengan kelas 'ket-kriteria'
            var textareas = document.querySelectorAll('.ket-alternatif');

            // Iterasi melalui setiap textarea
            textareas.forEach(function(textarea) {
                // Tambahkan event listener untuk meng-handle perubahan
                textarea.addEventListener('change', function() {
                    var textareaValue = this.value;
                    // Validasi dengan pola tertentu
                    var pattern = /^[A-Z0-9][A-Za-z0-9.- ]*$/;
                    if (!pattern.test(textareaValue)) {
                        this.setCustomValidity("Berawalan dengan huruf kapital");
                    } else {
                        this.setCustomValidity("");
                    }
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