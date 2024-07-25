<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama'])) {

    // Memanggil Koneksi Database
    require_once "../assets/koneksi.php";
    $koneksi = connectDB(); // Menggunakan fungsi connectDB() untuk mendapatkan koneksi database

    // Fungsi untuk mendapatkan data kriteria
    function getPeriode($koneksi)
    {
        $sql = "SELECT id_periode, tahun_periode, bulan_periode, status_periode FROM periode";
        $result = $koneksi->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $periode[] = $row;
            }
            return $periode;
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
            <div class="text">Data Periode
                <div class="container-periode">
                    <div class="card mt-4">
                        <div class="card-header fa-regular fa-rectangle-list">
                            <span><b>Daftar Data Periode</b></span>
                        </div>
                        <div class="card-body">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                <div class="fa-solid fa-plus"></div><span> Tambah Data</span>
                            </button>
                            <!-- Menampilkan daftar periode -->
                            <?php $periode = getPeriode($koneksi); ?>
                            <?php if (!empty($periode)) : ?>
                                <table id="example" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No</th>
                                            <th style="text-align: center; width: 380px;">Tahun Periode</th>
                                            <th style="text-align: center; width: 240px;">Bulan</th>
                                            <th style="text-align: center; width: 220px;">Status</th>
                                            <th style="text-align: center; width: 40px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Persiapan menampilkan data
                                        $no = 1;
                                        foreach ($periode as $periode_item) :
                                        ?>
                                            <tr>
                                                <td style="text-align: center;"><?= $no++ ?></td>
                                                <td style="text-align: left;"><?= $periode_item['tahun_periode'] ?></td>
                                                <td style="text-align: left;"><?= $periode_item['bulan_periode'] ?></td>
                                                <td>
                                                    <span style="text-align: center;" class=<?= ($periode_item['status_periode'] == 'Aktif') ? 'status-aktif' : 'status-tidak-aktif' ?>><?= $periode_item['status_periode'] ?></span>
                                                </td>
                                                <td>
                                                    <form action="dataPeriodeAksi.php" method="POST" style="display: inline;">
                                                        <input type="hidden" name="id_periode" value="<?= $periode_item['id_periode'] ?>">
                                                        <input type="hidden" name="action" value="activate">
                                                        <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i></button>
                                                    </form>
                                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUbah<?= $no ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                                                    <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $no ?>"><i class="fa-regular fa-trash-can"></i></a>
                                                </td>
                                            </tr>
                                            <!-- Awal Modal Ubah -->
                                            <div class="modal fade modal-lg" id="modalUbah<?= $no ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="staticBackdropLabel">Ubah Data Periode</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form class="row g-3" method="POST" action="dataPeriodeAksi.php">
                                                            <input type="hidden" name="id_periode" value="<?= $periode_item['id_periode'] ?>">
                                                            <div class="row g-3 modal-body">
                                                                <div class="col-md-6">
                                                                    <label for="inputTahun" class="form-label">Tahun Periode</label>
                                                                    <input type="text" name="ttahun" value="<?= $periode_item['tahun_periode'] ?>" pattern="[0-9]{4,}$" title="Hanya menggunakan angka" class="form-control" id="inputNama" required oninvalid="this.setCustomValidity('Hanya menggunakan angka')" onchange="try{setCustomValidity('')}catch(e){}" autocomplete="off">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="inputBulan" class="form-label">Bulan Periode</label>
                                                                    <select id="inputBulan" class="form-select" name="tbulan" required>
                                                                        <option value="<?= $periode_item['bulan_periode'] ?>" hidden><?= $periode_item['bulan_periode'] ?></option>
                                                                        <option value="Januari">Januari</option>
                                                                        <option value="Februari">Februari</option>
                                                                        <option value="Maret">Maret</option>
                                                                        <option value="April">April</option>
                                                                        <option value="Mei">Mei</option>
                                                                        <option value="Juni">Juni</option>
                                                                        <option value="Juli">Juli</option>
                                                                        <option value="Agustus">Agustus</option>
                                                                        <option value="September">September</option>
                                                                        <option value="Oktober">Oktober</option>
                                                                        <option value="November">November</option>
                                                                        <option value="Desember">Desember</option>
                                                                    </select>
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
                                                            <h5 class="modal-title" id="staticBackdropLabel">Konfirmasi Hapus Data Periode</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form class="row g-3" method="POST" action="dataPeriodeAksi.php">
                                                            <input type="hidden" name="id_periode" value="<?= $periode_item['id_periode'] ?>">
                                                            <div class="row g-3 modal-body">
                                                                <h5 class="text-center"> Apakah anda yakin akan menghapus data ini? <br>
                                                                    <span class="text-danger"><?= $periode_item['tahun_periode'] ?> - <?= $periode_item['bulan_periode'] ?></span>
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
                                    Tidak ada data periode.
                                </div>
                            <?php endif; ?>

                            <!-- Awal Modal Tambah -->
                            <div class="modal fade modal-lg" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Tambah Data Periode</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form class="row g-3" method="POST" action="dataPeriodeAksi.php">
                                            <div class="row g-3 modal-body">
                                                <div class="col-md-6">
                                                    <label for="inputTahun" class="form-label">Tahun Periode</label>
                                                    <input type="text" name="ttahun" required pattern="[0-9]{4,}$" title="Hanya menggunakan angka" class="form-control" id="inputTahun" required oninvalid="this.setCustomValidity('Hanya menggunakan angka')" onchange="try{setCustomValidity('')}catch(e){}" autocomplete="off">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="inputBulan" class="form-label">Bulan Periode</label>
                                                    <select title="Silakan pilih bulan periode" id="inputBulan" class="form-select" name="tbulan" required oninvalid="this.setCustomValidity('Bulan periode belum diisi')" onchange="try{setCustomValidity('')}catch(e){}">
                                                        <option value="" disabled selected>--Pilih Bulan Periode--</option>
                                                        <option value="Januari">Januari</option>
                                                        <option value="Februari">Februari</option>
                                                        <option value="Maret">Maret</option>
                                                        <option value="April">April</option>
                                                        <option value="Mei">Mei</option>
                                                        <option value="Juni">Juni</option>
                                                        <option value="Juli">Juli</option>
                                                        <option value="Agustus">Agustus</option>
                                                        <option value="September">September</option>
                                                        <option value="Oktober">Oktober</option>
                                                        <option value="November">November</option>
                                                        <option value="Desember">Desember</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary" name="bsimpan"><i class="fa-regular fa-floppy-disk"></i> Simpan</button>
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
    </body>

    </html>

<?php
} else {
    header("Location: ../index.php");
    exit();
}
?>