<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Penilaian Lomba Paduan Suara</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css" />
    <!-- Icons Fontawesome -->
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="shortcut icon" href="assets/img/logoMusik.png">

</head>

<body>
    <div class="bg">
        <div class="wrapper">
            <div class="login-definisi">
                <center>
                    <h2 class="garamond-font">Sistem Informasi Penilaian Juri</h2>
                    <p>Pesparawi Mahasiswa Tingkat Nasional</p>
                    <p class="small-spacing">Tahun 2024</p><br>
                    <b style="font-family: Arial, sans-serif;">
                        <small style="font-family: Arial, sans-serif;">Tema:</small><br>
                        <small style="font-family: Arial, sans-serif;">&ldquo;Dengan Bernyanyi Kita Memuji Kemuliaan Tuhan&rdquo;</small>
                    </b>
                    <br><br>
                    <!-- Tombol untuk membuka modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                        Login
                    </button>
                </center>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="login-header" id="loginModalLabel">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-bodylogin">
                    <form id="loginForm" action="login/login.php" method="post">
                        <?php if (isset($_GET['error'])) { ?>
                            <p id="error" class="error"><?php echo $_GET['error']; ?></p>
                        <?php } ?>
                        <div class="input-box">
                            <input type="text" name="username" id="user" class="input-field" required pattern="[a-z0-9]{1,}$" title="Menggunakan huruf kecil dan tidak menggunakan spasi" oninvalid="this.setCustomValidity('Menggunakan huruf kecil dan tidak menggunakan spasi')" onchange="try{setCustomValidity('')}catch(e){}" autocomplete="off" />
                            <label for="user" class="label">Username</label>
                            <i class="bx bx-user icon"></i>
                        </div>
                        <div class="input-box">
                            <input type="password" name="password" id="pass" class="input-field" required title="Mohon diisi" oninvalid="this.setCustomValidity('Password belum diisi')" onchange="try{setCustomValidity('')}catch(e){}" />
                            <label for="pass" class="label">Password</label>
                            <i class="bx bx-lock-alt icon"></i>
                            <span class="show-hide">
                                <i class="bx bx-show"></i>
                            </span>
                        </div>
                        <div class="input-box">
                            <input type="submit" class="input-submit" value="Login" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <script>
        // Fungsi untuk mereset form saat modal ditutup
        document.getElementById('loginModal').addEventListener('hidden.bs.modal', function(event) {
            // Dapatkan form di dalam modal
            var form = document.getElementById('loginForm');
            // Reset form
            form.reset();
            // Hapus pesan error jika ada
            var errorElement = document.getElementById('error');
            if (errorElement) {
                errorElement.style.display = 'none';
            }
        });

        // Show/Hide password
        const password = document.querySelector("input[type='password']");
        const btn_show = document.querySelector(".show-hide i");

        btn_show.addEventListener("click", function() {
            if (password.type === "password") {
                password.type = "text";
                btn_show.classList.replace("bx-show", "bx-hide");
            } else {
                password.type = "password";
                btn_show.classList.replace("bx-hide", "bx-show");
            }
        });

        // Cek jika pesan error ada dan pastikan modal tetap terbuka
        document.addEventListener('DOMContentLoaded', function() {
            var errorElement = document.getElementById('error');
            if (errorElement) {
                var modal = new bootstrap.Modal(document.getElementById('loginModal'));
                modal.show(); // Tampilkan modal jika ada pesan error
            }
        });

        // Sembunyikan pesan error saat mengetik di input
        document.getElementById('loginForm').addEventListener('input', function(event) {
            var errorElement = document.getElementById('error');
            if (errorElement) {
                errorElement.style.display = 'none'; // Sembunyikan pesan error
            }
        });
    </script>

</body>

</html>