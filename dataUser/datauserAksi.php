<?php
// Memanggil Koneksi Database
include "../assets/koneksi.php";
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama']) && $_SESSION['level'] !== 'juri') {
?>

<!DOCTYPE html>
<html lang="en">

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
    <link rel="stylesheet" href="../assets/sweetalert2/sweetalert2.min.css">
    <script src="../assets/sweetalert2/sweetalert2.all.min.js"></script>

    <body></body>
    <?php

        // Uji jika tombol simpan di klik
        if (isset($_POST['bsimpan'])) {
            // Mengambil data dari form
            $nama = $_POST['tnama'];
            $namal = $_POST['tnamal'];
            $email = $_POST['temail'];
            $katasandi = $_POST['tpass'];
            $level = $_POST['tlevel'];

            // Koneksi ke database
            $koneksi = connectDB();

            // Periksa apakah email sudah ada dalam database
            $sqlCheck = "SELECT * FROM user WHERE email = ? AND level = ?";
            $stmtCheck = $koneksi->prepare($sqlCheck);
            $stmtCheck->bind_param("ss", $email, $level);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();

            if ($resultCheck->num_rows > 0) {
                // Jika email sudah ada, tampilkan pesan kesalahan
        ?>
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: 'Data sudah ada, silakan masukkan data user yang berbeda.'
    }).then((result) => {
        window.history.back();
    })
    </script>
    <?php
            } else {
                // Persiapan simpan data baru
                $simpan = "INSERT INTO user (nama, nama_lengkap, email, katasandi, level)
                VALUES ('$nama', '$namal', '$email', '$katasandi', '$level')";

                // Menjalankan query
                if ($koneksi->query($simpan) === TRUE) {
                ?>
    <!-- Jika simpan sukses -->
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: 'Simpan data berhasil!'
    }).then((result) => {
        window.location = 'datauserView.php';
    })
    </script>
    <?php
                } else {
                ?>
    <!-- Jika simpan gagal -->
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: 'Simpan data gagal!'
    }).then((result) => {
        window.location = 'datauserView.php';
    })
    </script>
    <?php
                }
            }

            // Menutup koneksi ke database
            $koneksi->close();
        } else {
            // Jika form tidak disubmit, kembali ke halaman sebelumnya atau tampilkan pesan error
        }

        //Uji jika tombol ubah di klik
        if (isset($_POST['bubah'])) {
            // Mengambil data dari form
            $id = $_POST['id'];
            $nama = $_POST['tnama'];
            $namal = $_POST['tnamal'];
            $email = $_POST['temail'];
            // $katasandi = md5($_POST['tpass']);
            $level = $_POST['tlevel'];

            // Koneksi ke database
            $koneksi = connectDB();

            // // Query untuk mengubah data ke dalam tabel user
            $ubah = "UPDATE user SET 
                                    nama = '$nama',
                                    nama_lengkap = '$namal',
                                    email = '$email',
                                    level = '$level'";

            // Memeriksa apakah kata sandi diubah
            if (isset($_POST['tpass']) && $_POST['tpass'] != "") {
                $katasandi = md5($_POST['tpass']);
                // Menambahkan bagian update untuk kata sandi jika ada
                $ubah .= ", katasandi='$katasandi'";
            }

            // Menambahkan klausa WHERE di akhir query
            $ubah .= " WHERE id=$id";

            // Menjalankan query
            if ($koneksi->query($ubah) === TRUE) {
                ?>
    <!-- Jika ubah sukses -->
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: 'Ubah data berhasil!'
    }).then((result) => {
        window.location = 'datauserView.php';
    })
    </script>
    <?php
            } else {
            ?>
    <!-- Jika ubah gagal -->
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: 'Ubah data gagal!'
    }).then((result) => {
        window.location = 'datauserView.php';
    })
    </script>
    <?php
            }
            // Menutup koneksi ke database
            $koneksi->close();
        } else {
            // Jika form tidak disubmit, kembali ke halaman sebelumnya atau tampilkan pesan error
        }

        //Uji jika tombol hapus di klik
        if (isset($_POST['bhapus'])) {
            // Mengambil data dari form
            $id = $_POST['id'];

            // Koneksi ke database
            $koneksi = connectDB();

            // Query untuk menghapus data pada tabel kriteria
            $hapus = "DELETE FROM user WHERE id = '$id'";

            // Menjalankan query
            if ($koneksi->query($hapus) === TRUE) {
            ?>
    <!-- Jika hapus sukses -->
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: 'Hapus data berhasil!'
    }).then((result) => {
        window.location = 'datauserView.php';
    })
    </script>
    <?php
            } else {
            ?>
    <!-- Jika hapus gagal -->
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: 'Hapus data gagal!'
    }).then((result) => {
        window.location = 'datauserView.php';
    })
    </script>
    <?php
            }
            // Menutup koneksi ke database
            $koneksi->close();
        } else {
            // Jika form tidak disubmit, kembali ke halaman sebelumnya atau tampilkan pesan error
        }
        ?>

    <script src="//code.jquery.com/jquery-3.7.1.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="//cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>

</body>

</html>

<?php
} else {
    header("Location: datauserView.php");
    exit();
}
?>