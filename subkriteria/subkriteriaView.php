<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama'])) {

    // Memanggil Koneksi Database
    require_once "../assets/koneksi.php";
    $koneksi = connectDB(); // Menggunakan fungsi connectDB() untuk mendapatkan koneksi database

    // Fungsi untuk mendapatkan data kriteria
    function getSubkriteria($koneksi)
    {
        $sql = "SELECT * FROM subkriteria";
        $result = $koneksi->query($sql);

        if ($result && $result->num_rows > 0) {
            $subkriteria = [];
            while ($row = $result->fetch_assoc()) {
                $subkriteria[] = [
                    'subkriteria_id' => $row['subkriteria_id'],
                    'subkriteria_keterangan' => $row['subkriteria_keterangan'],
                    'subkriteria_nilai' => $row['subkriteria_nilai'],
                ];
            }
            return $subkriteria;
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
            <div class="text">Data Materi Penilaian
                <div class="container-kriteria">
                    <div class="card mt-4">
                        <div class="card-header fa-regular fa-rectangle-list">
                            <span><b>Daftar Data Materi Penilaian</b></span>
                        </div>
                        <div class="card-body">
                            <!-- Button trigger modal -->
                            <?php if ($_SESSION['level'] == 'admin') : ?>
                                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                    <div class="fa-solid fa-plus"></div><span> Tambah Data</span>
                                </button>
                            <?php endif; ?>
                            <!-- Menampilkan daftar subkriteria -->
                            <?php $subkriteria = getSubkriteria($koneksi); ?>
                            <?php if (!empty($subkriteria)) : ?>
                                <table id="example" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No</th>
                                            <th style="text-align: center; width: 945px;">Nama Materi Penilaian</th>
                                            <th style="text-align: center;">Bobot Nilai</th>
                                            <?php if ($_SESSION['level'] == 'admin') : ?>
                                                <th style="text-align: center; width: 200px;">Aksi</th>
                                            <?php endif; ?>
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
                                                <?php if ($_SESSION['level'] == 'admin') : ?>
                                                    <td>
                                                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUbah<?= $subkriteria_item['subkriteria_id'] ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                                                        <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $subkriteria_item['subkriteria_id'] ?>"><i class="fa-regular fa-trash-can"></i></a>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>

                                            <!-- Awal Modal Ubah -->
                                            <div class="modal fade modal-lg" id="modalUbah<?= $subkriteria_item['subkriteria_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="staticBackdropLabel">Ubah Data Materi Penilaian</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form class="row g-3" method="POST" action="subkriteriaAksi.php">
                                                            <input type="hidden" id="ubah-subkriteria-id" name="subkriteria_id" value="<?= $subkriteria_item['subkriteria_id'] ?>">
                                                            <div class="row g-3 modal-body">
                                                                <div class="col-md-6">
                                                                    <label for="inputNama" class="form-label">Nama Materi Penilaian</label>
                                                                    <textarea name="tnama" title="Jika dimulai dengan huruf, maka dimulai dengan huruf kapital" class="form-control nama-subkriteria" id="inputNama" required onchange="try{setCustomValidity('')}catch(e){}" autocomplete="off"><?= $subkriteria_item['subkriteria_keterangan'] ?></textarea>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="inputNilai" class="form-label">Bobot Nilai</label>
                                                                    <div class="input-group">
                                                                        <input id="inputNilai" title="Silakan masukkan nilai antara 1 sampai 100" class="form-control" name="tnilai" type="number" min="1" max="100" required oninvalid="this.setCustomValidity('Nilai belum diisi atau di luar batas (1-100)')" onchange="try{setCustomValidity('')}catch(e){}" value="<?= $subkriteria_item['subkriteria_nilai'] ?>">
                                                                        <span class="input-group-text">%</span>
                                                                    </div>
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
                                            <div class="modal fade modal-lg" id="modalHapus<?= $subkriteria_item['subkriteria_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="staticBackdropLabel">Hapus Data Materi Penilaian</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form class="row g-3" method="POST" action="subkriteriaAksi.php">
                                                            <input type="hidden" id="hapus-subkriteria-id" name="subkriteria_id" value="<?= $subkriteria_item['subkriteria_id'] ?>">
                                                            <div class="row g-3 modal-body">
                                                                <h5 class="text-center"> Apakah anda yakin akan menghapus data ini? <br>
                                                                    <span class="text-danger"><?= $subkriteria_item['subkriteria_keterangan'] ?> - <?= $subkriteria_item['subkriteria_nilai'] ?>%</span>
                                                                </h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary" name="bhapus"><i class="fa-regular fa-floppy-disk"></i> Hapus data</button>
                                                                <button type="reset" value="reset" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button>
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
                                    Tidak ada data materi penilaian.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal Tambah -->
        <div class="modal fade modal-lg" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Tambah Data Materi Penilaian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="row g-3" method="POST" action="subkriteriaAksi.php">
                        <div class="row g-3 modal-body">
                            <div class="col-md-6">
                                <label for="inputNama" class="form-label">Nama Materi Penilaian</label>
                                <textarea name="tnama" title="Jika dimulai dengan huruf, maka dimulai dengan huruf kapital" class="form-control nama-subkriteria" id="inputNama" required onchange="try{setCustomValidity('')}catch(e){}" autocomplete="off"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="inputNilai" class="form-label">Bobot Nilai</label>
                                <div class="input-group">
                                    <input id="inputNilai" title="Silakan masukkan nilai antara 1 sampai 100" class="form-control" name="tnilai" type="number" min="1" max="100" required oninvalid="this.setCustomValidity('Nilai belum diisi atau di luar batas (1-100)')" onchange="try{setCustomValidity('')}catch(e){}">
                                    <span class="input-group-text">%</span>
                                </div>
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


        <!-- Bootstrap -->
        <script src="//code.jquery.com/jquery-3.7.1.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="//cdn.datatables.net/2.0.2/js/dataTables.js"></script>
        <script src="//cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>
        <script src="../assets/js/script.js"></script>
        <script src="../bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#example').DataTable();
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