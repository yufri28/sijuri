<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama'])) {
    // Memanggil Koneksi Database
    require_once "../assets/koneksi.php";
    $koneksi = connectDB(); // Menggunakan fungsi connectDB() untuk mendapatkan koneksi database

    // Fungsi untuk mendapatkan data user yang sedang login
    function getUser($koneksi, $id)
    {
        $sql = "SELECT id, nama, nama_lengkap, email, katasandi, level FROM user WHERE id = $id";
        $result = $koneksi->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
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
    <title>Penilaian Kinerja Karyawan Kontrak</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <link rel="shortcut icon" href="../assets/img/logoMusik.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css">
</head>

<body>
    <?php include "../assets/basic/sidebar.php"; ?>
    <section class="home-section">
        <div class="text">Data Profil
            <div class="container-profile">
                <div class="card mt-4">
                    <div class="card-header fa-regular fa-rectangle-list">
                        <span><b>Data Profil</b></span>
                    </div>
                    <div class="card-body">
                        <?php
                            $id_user = $_SESSION['id'];
                            $user = getUser($koneksi, $id_user);
                            ?>
                        <?php if (!empty($user)) : ?>
                        <form action="profileAksi.php" method="POST" class="row g-3">
                            <div class="col-md-6">
                                <label for="nama">Nama Pengguna</label>
                                <input type="text" class="form-control" pattern="[a-z0-9]{1,}$"
                                    title="Berawalan dengan huruf kapital dan tidak menggunakan spasi" required
                                    oninvalid="this.setCustomValidity('Berawalan dengan huruf kapital dan tidak menggunakan spasi')"
                                    onchange="try{setCustomValidity('')}catch(e){}" id="nama" name="nama"
                                    value="<?= $user['nama'] ?>" autocomplete="off">
                            </div>
                            <div class="col-md-6">
                                <label for="namal">Nama Lengkap</label>
                                <input type="text" class="form-control" pattern="[A-Z][a-zA-Z ,'.-]*$"
                                    title="Berawalan dengan huruf kapital dan bisa mengandung huruf, titik, koma, spasi, dan tanda baca lainnya"
                                    required
                                    oninvalid="this.setCustomValidity('Berawalan dengan huruf kapital dan bisa mengandung titik atau koma')"
                                    onchange="try{setCustomValidity('')}catch(e){}" id="namal" name="namal"
                                    value="<?= htmlspecialchars($user['nama_lengkap']) ?>" autocomplete="off">
                            </div>

                            <div class="col-md-6">
                                <label for="email" style="width: 486px;">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    pattern="[A-Za-z0-9_]+@[A-Za-z0-9]+\.[A-Za-z]{2,}$" required
                                    oninvalid="this.setCustomValidity('E-mail harus diisi dan tidak boleh ada spasi')"
                                    onchange="try{setCustomValidity('')}catch(e){}" value="<?= $user['email'] ?>"
                                    autocomplete="off">
                            </div>
                            <div class="col-md-6">
                                <label for="katasandi">Kata Sandi</label>
                                <small class="fs-6"><i>(Boleh diisi jika ingin mengubah password!)</i></small>
                                <input type="password" class="form-control" id="katasandi" name="katasandi"
                                    oninvalid="this.setCustomValidity('Kata sandi belum diisi')"
                                    onchange="try{setCustomValidity('')}catch(e){}">
                                <span class="show-hide-profil"><i class="bx bx-show"></i></span>
                            </div>
                            <?php if ($_SESSION['level'] == 'admin') : ?>
                            <div class="col-md-6">
                                <label for="level">Level</label>
                                <select class="form-select" id="level" name="level" required>
                                    <option value="<?= $user['level'] ?>" hidden><?= $user['level'] ?></option>
                                    <option value="admin">admin</option>
                                    <option value="juri">juri</option>
                                </select>
                            </div>
                            <?php elseif ($_SESSION['level'] == 'juri') : ?>
                            <div class="col-md-6">
                                <label for="level">Level</label>
                                <input type="text" name="level" class="form-control" value="<?= $user['level'] ?>"
                                    readonly style="background-color: #f0f0f0;">
                            </div>
                            <?php endif; ?>
                            <div class="footer">
                                <button type="submit" class="btn btn-primary float-end" name="bubah"><i
                                        class="fa-regular fa-floppy-disk"></i> Ubah data</button>
                            </div>
                        </form>
                        <?php else : ?>
                        <p>Data User tidak ditemukan.</p>
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
    const password = document.querySelector("input[type='password']");
    const btn_show = document.querySelector(".show-hide-profil i");

    btn_show.addEventListener("click", function() {
        if (password.type === "password") {
            password.type = "text";
            btn_show.classList.replace("bx-show", "bx-hide");
        } else {
            password.type = "password";
            btn_show.classList.replace("bx-hide", "bx-show");
        }
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