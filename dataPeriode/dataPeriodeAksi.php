<?php
// Memanggil Koneksi Database
include "../assets/koneksi.php";
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama']) && $_SESSION['level'] !== 'juri') {
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
        if (isset($_POST['action']) && $_POST['action'] === 'activate') {
            // Memeriksa apakah permintaan untuk mengaktifkan periode
            if (isset($_POST['id_periode'])) {
                $id_periode = $_POST['id_periode'];
                // Koneksi ke database
                $koneksi = connectDB();
                // Update status semua periode menjadi "Tidak Aktif" kecuali periode yang diaktifkan
                $updateStatusNonActive = "UPDATE periode SET status_periode = 'Tidak Aktif' WHERE id_periode != $id_periode";
                $koneksi->query($updateStatusNonActive);

                // Update status periode yang diaktifkan menjadi "Aktif"
                $updateStatusActive = "UPDATE periode SET status_periode = 'Aktif' WHERE id_periode = $id_periode";
                if ($koneksi->query($updateStatusActive)) {
                    header("Location: dataPeriodeView.php");
                    exit();
                } else {
                    echo "Error updating record: " . $koneksi->error;
                }
                // Menutup koneksi ke database
                $koneksi->close();
            } else {
            }
        }

        // Uji jika tombol simpan di klik
        if (isset($_POST['bsimpan'])) {
            // Mengambil data dari form
            $tahun = $_POST['ttahun'];
            $bulan = $_POST['tbulan'];

            // Koneksi ke database
            $koneksi = connectDB();

            // Periksa apakah kombinasi tahun dan bulan periode sudah ada
            $sqlCheck = "SELECT * FROM periode WHERE tahun_periode = ? AND bulan_periode = ?";
            $stmtCheck = $koneksi->prepare($sqlCheck);
            $stmtCheck->bind_param("ss", $tahun, $bulan);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();

            if ($resultCheck->num_rows > 0) {
                // Jika kombinasi sudah ada, tampilkan pesan kesalahan
        ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Data periode sudah ada, silakan masukkan data periode yang berbeda.'
                    }).then((result) => {
                        window.history.back();
                    })
                </script>
                <?php
            } else {
                // Ambil semua periode yang sudah ada
                $sql = "SELECT id_periode FROM periode";
                $result = $koneksi->query($sql);

                if ($result->num_rows > 0) {
                    // Ubah status semua periode yang ada menjadi "Tidak Aktif"
                    $updateStatus = "UPDATE periode SET status_periode = 'Tidak Aktif'";
                    $koneksi->query($updateStatus);
                }

                // Persiapan simpan data baru
                $simpan = "INSERT INTO periode (tahun_periode, bulan_periode, status_periode)
                    VALUES ('$tahun', '$bulan', 'Aktif')";

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
                            window.location = 'dataPeriodeView.php';
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
                            window.location = 'dataPeriodeView.php';
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
            $id_periode = $_POST['id_periode'];
            $tahun = $_POST['ttahun'];
            $bulan = $_POST['tbulan'];

            // Koneksi ke database
            $koneksi = connectDB();

            //Persiapan ubah data
            // Query untuk mengubah data ke dalam tabel kriteria
            $ubah = "UPDATE periode SET 
                                    tahun_periode = '$tahun',
                                    bulan_periode = '$bulan'
                                WHERE id_periode = '$id_periode'
                                    ";

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
                        window.location = 'dataPeriodeView.php';
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
                        window.location = 'dataPeriodeView.php';
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
            $id_periode = $_POST['id_periode'];

            // Koneksi ke database
            $koneksi = connectDB();

            // Query untuk menghapus data pada tabel kriteria
            $hapus = "DELETE FROM periode WHERE id_periode = '$id_periode'";

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
                        window.location = 'dataPeriodeView.php';
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
                        window.location = 'dataPeriodeView.php';
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

    </body>

    </html>
<?php
} else {
    header("Location: dataPeriodeView.php");
    exit();
}
?>