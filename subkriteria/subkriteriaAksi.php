<?php
session_start();

// Pastikan pengguna telah login dan memiliki level akses yang sesuai
if (isset($_SESSION['id']) && isset($_SESSION['nama']) && $_SESSION['level'] !== 'juri') {

    // Memanggil Koneksi Database
    require_once "../assets/koneksi.php";

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Penilaian Lomba Paduan Suara</title>
        <!-- Icon Musik -->
        <link rel="shortcut icon" href="../assets/img/logoMusik.png">
    </head>

    <body>
        <link rel="stylesheet" href="../assets/sweetalert2/sweetalert2.min.css">
        <script src="../assets/sweetalert2/sweetalert2.all.min.js"></script>

        <body></body>

        <?php
        // Jika tombol simpan ditekan
        if (isset($_POST['bsimpan'])) {
            $nama_subkriteria = $_POST['tnama'];
            $nilai_subkriteria = $_POST['tnilai'];

            // Koneksi ke database
            $koneksi = connectDB();

            // Periksa apakah nama subkriteria sudah ada
            $sqlCheck = "SELECT * FROM subkriteria WHERE subkriteria_keterangan = ?";
            $stmtCheck = $koneksi->prepare($sqlCheck);
            $stmtCheck->bind_param("s", $nama_subkriteria);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();

            if ($resultCheck->num_rows > 0) {
                // Jika nama subkriteria sudah ada, tampilkan pesan kesalahan
        ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Nama Materi Penilaian sudah ada, silakan masukkan nama yang lain.'
                    }).then((result) => {
                        window.history.back();
                    });
                </script>
                <?php
            } else {
                // Query untuk menyimpan data subkriteria ke database
                $sql = "INSERT INTO subkriteria (subkriteria_keterangan, subkriteria_nilai) VALUES (?, ?)";
                $stmt = $koneksi->prepare($sql);
                $stmt->bind_param("ss", $nama_subkriteria, $nilai_subkriteria);

                if ($stmt->execute()) {
                    // Jika berhasil disimpan, tampilkan pesan sukses
                ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: 'Data berhasil ditambahkan!'
                        }).then((result) => {
                            window.location = 'subkriteriaView.php';
                        });
                    </script>
                <?php
                } else {
                    // Jika gagal disimpan, tampilkan pesan gagal
                ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal menambahkan data!'
                        }).then((result) => {
                            window.location = 'subkriteriaView.php';
                        });
                    </script>
                <?php
                }
            }
            $koneksi->close();
        }

        // Jika tombol ubah ditekan
        if (isset($_POST["bubah"])) {
            $subkriteria_id = $_POST['subkriteria_id'];
            $nama_subkriteria = $_POST['tnama'];
            $nilai_subkriteria = $_POST['tnilai'];

            // Koneksi ke database
            $koneksi = connectDB();

            // Query untuk mengubah data subkriteria
            $sql = "UPDATE subkriteria SET subkriteria_keterangan=?, subkriteria_nilai=? WHERE subkriteria_id=?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("ssi", $nama_subkriteria, $nilai_subkriteria, $subkriteria_id);

            if ($stmt->execute()) {
                // Jika berhasil diubah, tampilkan pesan sukses
                ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses',
                        text: 'Data berhasil diubah!'
                    }).then((result) => {
                        window.location = 'subkriteriaView.php';
                    });
                </script>
            <?php
            } else {
                // Jika gagal diubah, tampilkan pesan gagal
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal mengubah data!'
                    }).then((result) => {
                        window.location = 'subkriteriaView.php';
                    });
                </script>
            <?php
            }
            // Tutup statement dan koneksi
            $stmt->close();
            $koneksi->close();
        }

        // Jika tombol hapus ditekan
        if (isset($_POST["bhapus"])) {
            $subkriteria_id = $_POST['subkriteria_id'];

            // Koneksi ke database
            $koneksi = connectDB();

            // Query untuk menghapus data subkriteria
            $sql = "DELETE FROM subkriteria WHERE subkriteria_id=?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("i", $subkriteria_id);

            if ($stmt->execute()) {
                // Jika berhasil dihapus, tampilkan pesan sukses
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses',
                        text: 'Data berhasil dihapus!'
                    }).then((result) => {
                        window.location = 'subkriteriaView.php';
                    });
                </script>
            <?php
            } else {
                // Jika gagal dihapus, tampilkan pesan gagal
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal menghapus data!'
                    }).then((result) => {
                        window.location = 'subkriteriaView.php';
                    });
                </script>
    <?php
            }
            // Tutup statement dan koneksi
            $stmt->close();
            $koneksi->close();
        }
    } else {
        // Jika tidak ada sesi atau level tidak sesuai, redirect ke halaman subkriteriaView.php
        header("Location: subkriteriaView.php");
        exit();
    }
    ?>