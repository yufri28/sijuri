<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama'])) {

    // Memanggil Koneksi Database
    require_once "../assets/koneksi.php";
    $koneksi = connectDB(); // Menggunakan fungsi connectDB() untuk mendapatkan koneksi database

    // Fungsi untuk mendapatkan data kriteria
    function getKriteria($koneksi)
    {
        $sql = "SELECT * FROM kriteria";
        $result = $koneksi->query($sql);

        if ($result && $result->num_rows > 0) {
            $kriteria = [];
            while ($row = $result->fetch_assoc()) {
                $kriteria[] = [
                    'id_kriteria' => $row['id_kriteria'],
                    'kode_kriteria' => $row['kode_kriteria'],
                    'nama_kriteria' => $row['nama_kriteria'],
                    'ket_kriteria' => $row['ket_kriteria'],
                ];
            }
            return $kriteria;
        } else {
            return [];
        }
    }

    function getNextKodeKriteria($koneksi)
    {
        $sql = "SELECT MAX(CAST(SUBSTRING(kode_kriteria, 2) AS UNSIGNED)) AS max_kode FROM kriteria";
        $result = $koneksi->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $next_kode = $row['max_kode'] + 1;
            return 'K' . $next_kode;
        } else {
            return 'K1'; // Jika tidak ada kode kriteria sebelumnya
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
            <div class="text">Data kategori
                <div class="container-kriteria">
                    <div class="card mt-4">
                        <div class="card-header fa-regular fa-rectangle-list">
                            <span><b>Daftar Data kategori</b></span>
                        </div>
                        <div class="card-body">
                            <!-- Button trigger modal -->
                            <?php if ($_SESSION['level'] == 'admin') : ?>
                                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                    <div class="fa-solid fa-plus"></div><span> Tambah Data</span>
                                </button>
                            <?php endif; ?>
                            <!-- Menampilkan daftar kriteria -->
                            <?php $kriteria = getKriteria($koneksi); ?>
                            <?php if (!empty($kriteria)) : ?>
                                <table id="example" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No</th>
                                            <th style="text-align: center; width: 580px;">Nama Kategori</th>
                                            <th style="text-align: center; width: 310px;">Keterangan Kategori</th>
                                            <?php if ($_SESSION['level'] == 'admin') : ?>
                                                <th style="text-align: center; width: 130px;">Aksi</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Persiapan menampilkan data
                                        $no = 1;
                                        foreach ($kriteria as $kriteria_item) : ?>
                                            <tr>
                                                <td style="text-align: center;"><?= $no++ ?></td>
                                                <td style="text-align: left;"><?= $kriteria_item['nama_kriteria'] ?></td>
                                                <td style="text-align: left;"><?= $kriteria_item['ket_kriteria'] ?></td>
                                                <?php if ($_SESSION['level'] == 'admin') : ?>
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
                                                            <h5 class="modal-title" id="staticBackdropLabel">Ubah Data Kategori</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form class="row g-3" method="POST" action="kriteriaAksi.php">
                                                            <input type="hidden" name="id_kriteria" value="<?= $kriteria_item['id_kriteria'] ?>">
                                                            <div class="row g-3 modal-body">
                                                            <input type="hidden" name="tkode" class="form-control" value="<?= $kriteria_item['kode_kriteria'] ?>" readonly style="background-color: #f0f0f0;">
                                                                <div class="col-md-6">
                                                                    <label for="inputNama" class="form-label">Nama Kategori</label>
                                                                    <input type="text" name="tnama" value="<?= $kriteria_item['nama_kriteria'] ?>" pattern="[A-Z][a-zA-Z ]{1,}$" title="Berawalan dengan huruf kapital dan tidak menggunakan karakter lain" class="form-control" id="inputNama" required oninvalid="this.setCustomValidity('Berawalan dengan huruf kapital dan tidak menggunakan karakter lain')" onchange="try{setCustomValidity('')}catch(e){}" autocomplete="off">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="inputKet" class="form-label">Keterangan Kategori</label>
                                                                    <textarea name="tket" class="form-control ket-kriteria" id="inputKet" title="Silakan diisi" required onchange="try{setCustomValidity('')}catch(e){}" autocomplete="off"><?= $kriteria_item['ket_kriteria'] ?></textarea>
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
                                                            <h5 class="modal-title" id="staticBackdropLabel">Konfirmasi Hapus Data Kriteria</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form class="row g-3" method="POST" action="kriteriaAksi.php">
                                                            <input type="hidden" name="id_kriteria" value="<?= $kriteria_item['id_kriteria'] ?>">
                                                            <div class="row g-3 modal-body">
                                                                <h5 class="text-center"> Apakah anda yakin akan menghapus data ini? <br>
                                                                    <span class="text-danger"><?= $kriteria_item['nama_kriteria'] ?> - <?= $kriteria_item['ket_kriteria'] ?></span>
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
                                    Tidak ada data kategori.
                                </div>
                            <?php endif; ?>

                            <!-- Awal Modal Tambah -->
                            <div class="modal fade modal-lg" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Tambah Data Kategori</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form id="formTambahKriteria" class="row g-3" method="POST" action="kriteriaAksi.php">
                                            <div class="row g-3 modal-body">
                                            <input type="hidden" name="tkode" class="form-control" value="<?= getNextKodeKriteria($koneksi) ?>" readonly style="background-color: #f0f0f0;">
                                                <div class="col-md-6">
                                                    <label for="inputNama" class="form-label">Nama Kategori</label>
                                                    <input type="text" name="tnama" pattern="[A-Z][a-zA-Z0-9 ]{1,}$" title="Berawalan dengan huruf kapital dan tidak menggunakan karakter lain" class="form-control" id="inputNama" required oninvalid="this.setCustomValidity('Berawalan dengan huruf kapital dan tidak menggunakan karakter lain')" onchange="try{setCustomValidity('')}catch(e){}" autocomplete="off">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="inputKet" class="form-label">Keterangan Kategori</label>
                                                    <textarea name="tket" class="form-control ket-kriteria" id="inputKet" title="Silakan diisi" required oninvalid="this.setCustomValidity('Keterangan kriteria harus diisi')" onchange="try{setCustomValidity('')}catch(e){}" autocomplete="off"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary" name="bsimpan"><i class="fa-regular fa-floppy-disk"></i> Simpan data</button>
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
            var textareas = document.querySelectorAll('.ket-kriteria');

            // Iterasi melalui setiap textarea
            textareas.forEach(function(textarea) {
                // Tambahkan event listener untuk meng-handle perubahan
                textarea.addEventListener('change', function() {
                    var textareaValue = this.value;
                    // Validasi dengan pola tertentu
                    var pattern = /^[A-Z0-9][A-Za-z0-9, ]*$/;
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