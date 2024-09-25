<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama']) && $_SESSION['level'] !== 'juri') {

    // Memanggil Koneksi Database
    require_once "../assets/koneksi.php";
    $koneksi = connectDB(); // Menggunakan fungsi connectDB() untuk mendapatkan koneksi database

    // Fungsi untuk mendapatkan data user yang sedang login
    function getUser($koneksi)
    {
        $sql = "SELECT id, nama, nama_lengkap, email, katasandi, level FROM user";
        $result = $koneksi->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user[] = $row;
            }
            return $user;
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
            <div class="text">Data User
                <div class="container-user">
                    <div class="card mt-4">
                        <div class="card-header fa-regular fa-rectangle-list">
                            <span><b>Daftar Data User</b></span>
                        </div>
                        <div class="card-body">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                <div class="fa-solid fa-plus"></div><span> Tambah Data</span>
                            </button>
                            <!-- Menampilkan daftar User -->
                            <?php $user = getUser($koneksi); ?>
                            <?php if (!empty($user)) : ?>
                                <table id="example" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No</th>
                                            <th style="text-align: center; width: 645px;">Nama Pengguna</th>
                                            <th style="text-align: center; width: 1645px;">Nama Lengkap</th>
                                            <th style="text-align: center; width: 355px;">E-mail</th>
                                            <th style="text-align: center; width: 302px;">Level</th>
                                            <th style="text-align: center; width: 445px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Persiapan menampilkan data
                                        $no = 1;
                                        foreach ($user as $user_item) : ?>
                                            <tr>
                                                <td style="text-align: center;"><?= $no++ ?></td>
                                                <td style="text-align: left;"><?= $user_item['nama'] ?></td>
                                                <td style="text-align: left;"><?= $user_item['nama_lengkap'] ?></td>
                                                <td style="text-align: left;"><?= $user_item['email'] ?></td>
                                                <td style="text-align: center;"><?= $user_item['level'] ?></td>
                                                <td>
                                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUbah<?= $no ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                                                    <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $no ?>"><i class="fa-regular fa-trash-can"></i></a>
                                                </td>
                                            </tr>

                                            <!-- Awal Modal Ubah -->
                                            <div class="modal fade modal-lg" id="modalUbah<?= $no ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="staticBackdropLabel">Ubah Data User</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form class="row g-3" method="POST" action="datauserAksi.php">
                                                            <input type="hidden" name="id" value="<?= $user_item['id'] ?>">
                                                            <div class="row g-3 modal-body">
                                                                <div class="col-md-6">
                                                                    <label for="inputNama" class="form-label">Nama Pengguna</label>
                                                                    <input type="text" name="tnama" value="<?= $user_item['nama'] ?>" pattern="[a-z0-9]{1,}$" title="Menggunakan huruf kecil dan tidak menggunakan spasi" class="form-control" id="inputNama" required oninvalid="this.setCustomValidity('Menggunakan huruf kecil dan tidak menggunakan spasi')" onchange="try{setCustomValidity('')}catch(e){}" autocomplete="off">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="inputNamaL" class="form-label">Nama Lengkap</label>
                                                                    <input type="text" name="tnamal"
                                                                        value="<?= htmlspecialchars($user_item['nama_lengkap']) ?>"
                                                                        pattern="^[A-Z][a-zA-Z., ]*$"
                                                                        title="Berawalan dengan huruf kapital"
                                                                        class="form-control" id="inputNamaL" required
                                                                        oninvalid="this.setCustomValidity('Berawalan dengan huruf kapital, diikuti huruf besar/kecil, koma, atau titik')"
                                                                        onchange="try{setCustomValidity('')}catch(e){}"
                                                                        autocomplete="off">
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="inputEmail" class="form-label">E-mail</label>
                                                                    <input type="text" name="temail" value="<?= $user_item['email'] ?>" pattern="[A-Za-z0-9_]+@[A-Za-z0-9]+\.[A-Za-z]{2,}$" oninvalid="this.setCustomValidity('E-mail harus diisi dan tidak boleh ada spasi')" onchange="try{setCustomValidity('')}catch(e){}" class="form-control" id="inputEmail" required autocomplete="off">
                                                                </div>
                                                                <div class="col-md-6 modal-ubah">
                                                                    <label for="inputPass" class="form-label">Kata Sandi</label>
                                                                    <button type="button" class="btn btn-secondary resetpwd" id="resetPassword"><i class="fa-solid fa-recycle"></i></button>
                                                                    <input type="password" name="tpass" value="<?= $user_item['katasandi'] ?>" class="form-control input-password" id="inputPass" required oninvalid="this.setCustomValidity('Kata sandi belum diisi')" onchange="try{setCustomValidity('')}catch(e){}" autocomplete="off" readonly style="background-color: #f0f0f0;">
                                                                    <span class="show-hide-user" id="showHideUser"><i class="bx bx-show iconubah"></i></span>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="inputLevel" class="form-label">Level</label>
                                                                    <select id="inputLevel" class="form-select" name="tlevel" required>
                                                                        <option value="<?= $user_item['level'] ?>" hidden><?= $user_item['level'] ?></option>
                                                                        <option value="admin">admin</option>
                                                                        <option value="juri">juri</option>
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
                                                            <h5 class="modal-title" id="staticBackdropLabel">Konfirmasi Hapus Data User</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form class="row g-3" method="POST" action="dataUserAksi.php">
                                                            <input type="hidden" name="id" value="<?= $user_item['id'] ?>">
                                                            <div class="row g-3 modal-body">
                                                                <h5 class="text-center"> Apakah anda yakin akan menghapus data ini? <br>
                                                                    <span class="text-danger"><?= $user_item['nama_lengkap'] ?> - <?= $user_item['level'] ?></span>
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
                                    Tidak ada data user.
                                </div>
                            <?php endif; ?>

                            <!-- Awal Modal Tambah -->
                            <div class="modal fade modal-lg" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Tambah Data User</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form class="row g-3" method="POST" action="dataUserAksi.php">
                                            <div class="row g-3 modal-body">
                                                <div class="col-md-6">
                                                    <label for="inputNama" class="form-label">Nama Pengguna</label>
                                                    <input type="text" name="tnama" pattern="[a-z0-9]{1,}$" title="Menggunakan huruf kecil dan tidak menggunakan spasi" class="form-control" id="inputNama" required oninvalid="this.setCustomValidity('Menggunakan huruf kecil dan tidak menggunakan spasi')" onchange="try{setCustomValidity('')}catch(e){}" autocomplete="off">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="inputNamaL" class="form-label">Nama Lengkap</label>
                                                    <input type="text" name="tnamal"
                                                        pattern="^[A-Z][a-zA-Z., ]*$"
                                                        title="Berawalan dengan huruf kapital"
                                                        class="form-control" id="inputNamaL" required
                                                        oninvalid="this.setCustomValidity('Berawalan dengan huruf kapital, diikuti huruf besar/kecil, koma, atau titik')"
                                                        onchange="try{setCustomValidity('')}catch(e){}"
                                                        autocomplete="off">
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="inputEmail" class="form-label">E-mail</label>
                                                    <input type="text" name="temail" pattern="[A-Za-z0-9_]+@[A-Za-z0-9]+\.[A-Za-z]{2,}$" oninvalid="this.setCustomValidity('E-mail harus diisi dan tidak boleh ada spasi')" onchange="try{setCustomValidity('')}catch(e){}" class="form-control" id="inputEmail" required autocomplete="off">
                                                </div>
                                                <div class="col-md-6 modal-tambah">
                                                    <label for="inputPass" class="form-label">Kata Sandi</label>
                                                    <button type="button" class="btn btn-secondary resetpwd" id="resetPassword"><i class="fa-solid fa-recycle"></i></button>
                                                    <input type="password" name="tpass" class="form-control input-password" id="inputPass" required oninvalid="this.setCustomValidity('Kata sandi belum diisi')" onchange="try{setCustomValidity('')}catch(e){}" autocomplete="off" readonly style="background-color: #f0f0f0;">
                                                    <span class="show-hide-user"><i class="bx bx-show icontambah"></i></span>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="inputLevel" class="form-label">Level</label>
                                                    <select id="inputLevel" class="form-select" name="tlevel" required oninvalid="this.setCustomValidity('Level akses belum diisi')" onchange="try{setCustomValidity('')}catch(e){}">
                                                        <option value="" disabled selected>--Pilih Level Akses--</option>
                                                        <option value="admin">admin</option>
                                                        <option value="juri">juri</option>
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
        <script>
            const passwords = document.querySelectorAll("input[type='password']");
            const btn_shows = document.querySelectorAll(".show-hide-user i");

            btn_shows.forEach((btn_show, index) => {
                btn_show.addEventListener("click", function() {
                    const password = passwords[index];
                    if (password.type === "password") {
                        password.type = "text";
                        btn_show.classList.replace("bx-show", "bx-hide");
                    } else {
                        password.type = "password";
                        btn_show.classList.replace("bx-hide", "bx-show");
                    }
                });
            });
        </script>
        <script>
            const resetButtons = document.querySelectorAll('.resetpwd');
            resetButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    // Mengambil elemen password terkait dengan tombol yang diklik
                    const passwordInput = button.parentElement.querySelector('.input-password');

                    // Periksa apakah modal adalah modal ubah atau modal tambah
                    const isUbahModal = button.closest('.modal-ubah');

                    // Menentukan pesan kesuksesan berdasarkan jenis modal
                    let successMessageText = '';
                    if (isUbahModal) {
                        successMessageText = 'Kata sandi berhasil direset.';
                    } else {
                        successMessageText = 'Kata sandi default berhasil diatur.';
                    }

                    // Menambahkan pesan bahwa password berhasil direset di samping ikon reset
                    const successMessage = document.createElement('span');
                    successMessage.textContent = successMessageText;
                    successMessage.classList.add('text-success', 'reset-success-message'); // Tambahkan kelas untuk warna teks hijau

                    // Sisipkan pesan di samping ikon reset
                    button.insertAdjacentElement('afterend', successMessage);

                    // Mengatur nilai default untuk password
                    passwordInput.value = 'bermusik';

                    // Hilangkan pesan setelah beberapa detik
                    setTimeout(function() {
                        successMessage.remove();
                    }, 3000); // lamanya pesan akan ditampilkan (dalam milidetik)
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