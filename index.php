<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Penilaian Lomba Paduan Suara</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="shortcut icon" href="assets/img/logoMusik.png">
</head>

<body>
    <div class="bg">
        <form action="login/login.php" method="post">
            <div class="wrapper">
                <div class="login-definisi">
                    <center>
                        <h3>Sistem Informasi Penilaian</h3>
                        <p>Pesparawi Mahasiswa Tingkat Nasional</p>
                        <p>Tahun 2024</p>
                        <b style="font-family: Arial, sans-serif;">
                            <small style="font-family: Arial, sans-serif;">Tema:</small><br>
                            <small style="font-family: Arial, sans-serif;">&ldquo;Dengan Bernyanyi Kita Memuji Kemuliaan Tuhan&rdquo;</small>
                        </b>

                    </center>
                </div>
                <div class="login-box">
                    <div class="login-header">
                        <span>Login</span>
                    </div>
                    <?php if (isset($_GET['error'])) { ?>
                        <p id="error" class="error"><?php echo $_GET['error']; ?></p>
                    <?php } ?>
                    <div class="input-box">
                        <input type="text" name="username" id="user" class="input-field" required pattern="[a-z0-9]{1,}$" title="Menggunakan huruf kecil dan tidak menggunakan spasi" oninvalid="this.setCustomValidity('Menggunakan huruf kecil dan tidak menggunakan spasi')" onchange="try{setCustomValidity('')}catch(e){}" autocomplete="off" />
                        <label for="user" class="label">Username</label>
                        <i class="bx bx-user icon"></i>
                    </div>
                    <div class="input-box">
                        <input type="password" name="password" id="user" class="input-field" required title="Mohon diisi" oninvalid="this.setCustomValidity('Password belum diisi')" onchange="try{setCustomValidity('')}catch(e){}" />
                        <label for="pass" class="label">Password</label>
                        <i class="bx bx-lock-alt icon"></i>
                        <span class="show-hide">
                            <i class="bx bx-show"></i>
                        </span>
                    </div>
                    <div class="input-box">
                        <input type="submit" class="input-submit" value="Login" />
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <script>
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
    </script>
    <script>
        // Ambil elemen pesan error
        var errorElement = document.getElementById('error');

        // Periksa jika pengguna mulai mengetik
        document.addEventListener('keydown', function(event) {
            // Jika pengguna mulai mengetik, sembunyikan pesan error
            errorElement.style.display = 'none';
        });
    </script>
</body>

</html>