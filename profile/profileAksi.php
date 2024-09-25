<?php
// Memanggil Koneksi Database
include "../assets/koneksi.php";
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama'])) {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kriteria - Penilaian Kinerja Karyawan Kontrak</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <!--Bootstrap -->
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css" />
    <!-- Boxicons CDN Link -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <!-- Icons Fontawesome -->
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <!-- Icon Sahid -->
    <link rel="shortcut icon" href="../assets/img/logoMusik.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css">
</head>

<body>
    <link rel="stylesheet" href="../assets/sweetalert2/sweetalert2.min.css">
    <script src="../assets/sweetalert2/sweetalert2.all.min.js"></script>

    <body></body>
    <?php

        // Memeriksa apakah tombol "Ubah data" diklik
        if (isset($_POST['bubah'])) {
            // Mengambil data yang dikirim melalui formulir
            $nama = $_POST['nama'];
            $namal = $_POST['namal'];
            $email = $_POST['email'];
            $level = $_POST['level'];

            // Koneksi ke database
            $koneksi = connectDB();

            // Memperbarui data pengguna dalam database
            $id_user = $_SESSION['id'];

            // Mulai query UPDATE tanpa bagian kata sandi
            $ubah = "UPDATE user SET nama='$nama', nama_lengkap='$namal', email='$email', level='$level'";

            // Memeriksa apakah kata sandi diubah
            if (isset($_POST['katasandi']) && $_POST['katasandi'] != "") {
                $katasandi = md5($_POST['katasandi']);
                // Menambahkan bagian update untuk kata sandi jika ada
                $ubah .= ", katasandi='$katasandi'";
            }

            // Menambahkan klausa WHERE di akhir query
            $ubah .= " WHERE id=$id_user";
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
        window.location = 'profileView.php';
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
        window.location = 'profileView.php';
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
    header("Location: kriteriaView.php");
    exit();
}
?>