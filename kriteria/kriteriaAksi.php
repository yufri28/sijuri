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
            $kode = $_POST['tkode'];
            $nama_kriteria = $_POST['tnama'];
            $ket = $_POST['tket'];

            // Koneksi ke database
            $koneksi = connectDB();

            // Periksa apakah nama kriteria sudah ada di database
            $sqlCheck = "SELECT * FROM kriteria WHERE nama_kriteria = '$nama_kriteria'";
            $resultCheck = $koneksi->query($sqlCheck);

            if ($resultCheck->num_rows > 0) {
                // Jika nama kriteria sudah ada, kembali ke form dengan pesan kesalahan
        ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Nama lagu sudah ada, silakan masukkan nama lagu yang lain.'
                    }).then((result) => {
                        window.history.back();
                    })
                </script>
                <?php
            } else {
                // Query untuk menyimpan data ke dalam tabel kriteria
                $simpan = "INSERT INTO kriteria (kode_kriteria, nama_kriteria, ket_kriteria)
                            VALUES ('$kode', '$nama_kriteria', '$ket')";

                // Menjalankan query
                if ($koneksi->query($simpan) === TRUE) {
                    $id_kriteria = $koneksi->insert_id;

                    ?>
                    <!-- Jika simpan sukses -->
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: 'Simpan data berhasil!'
                        }).then((result) => {
                            window.location = 'kriteriaView.php';
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
                            window.location = 'kriteriaView.php';
                        })
                    </script>
                <?php
                }
            }
            // Menutup koneksi ke database
            $koneksi->close();
        }

        // Uji jika tombol ubah di klik
        if (isset($_POST['bubah'])) {
            // Mengambil data dari form
            $id_kriteria = $_POST['id_kriteria'];
            $kode = $_POST['tkode'];
            $nama_kriteria = $_POST['tnama'];
            $ket = $_POST['tket'];


            // Koneksi ke database
            $koneksi = connectDB();

            // Query untuk mengubah data di tabel kriteria
            $ubah = "UPDATE kriteria SET 
                                        kode_kriteria = '$kode', 
                                        nama_kriteria = '$nama_kriteria', 
                                        ket_kriteria = '$ket'
                                    WHERE id_kriteria = '$id_kriteria'";

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
                        window.location = 'kriteriaView.php';
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
                        window.location = 'kriteriaView.php';
                    })
                </script>
                <?php
            }
            // Menutup koneksi ke database
            $koneksi->close();
        }

        // Uji jika tombol hapus di klik
        if (isset($_POST['bhapus'])) {
            $id_kriteria = $_POST['id_kriteria'];

            // Koneksi ke database
            $koneksi = connectDB();

            // Ambil kode kriteria yang akan dihapus
            $sql_get_kode = "SELECT kode_kriteria FROM kriteria WHERE id_kriteria = $id_kriteria";
            $result_get_kode = $koneksi->query($sql_get_kode);
            if ($result_get_kode && $result_get_kode->num_rows > 0) {
                $row_get_kode = $result_get_kode->fetch_assoc();
                $kode_kriteria_hapus = $row_get_kode['kode_kriteria'];

                // Hapus data kriteria dari database
                $sql_delete = "DELETE FROM kriteria WHERE id_kriteria = $id_kriteria";
                if ($koneksi->query($sql_delete) === TRUE) {
                    // Perbarui urutan kode kriteria di database
                    $sql_update = "UPDATE kriteria SET kode_kriteria = CONCAT('K', CAST(SUBSTRING(kode_kriteria, 2) - 1 AS CHAR))
                                    WHERE CAST(SUBSTRING(kode_kriteria, 2) AS UNSIGNED) > CAST(SUBSTRING('$kode_kriteria_hapus', 2) AS UNSIGNED)";
                    $koneksi->query($sql_update);
                    // Redirect atau tampilkan pesan sukses
                ?>
                    <!-- Jika hapus sukses -->
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: 'Hapus data berhasil!'
                        }).then((result) => {
                            window.location = 'kriteriaView.php';
                        })
                    </script>
                <?php
                } else {
                    // Redirect atau tampilkan pesan error
                ?>
                    <!-- Jika hapus gagal -->
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Hapus data gagal!'
                        }).then((result) => {
                            window.location = 'kriteriaView.php';
                        })
                    </script>
        <?php
                }
                // Menutup koneksi ke database
                $koneksi->close();
            } else {
                // Redirect atau tampilkan pesan error
            }
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