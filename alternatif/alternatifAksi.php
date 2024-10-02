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
   // Uji jika tombol simpan di klik
if (isset($_POST['bsimpan'])) {
    // Mengambil data dari form
    $nama_alternatif = $_POST['tnama'];
    $nomor_urut = $_POST['turut'];
    $lagupertama = $_POST['tlagupertama'];
    $lagukedua = $_POST['tlagukedua'];
    $durasi_pertama = $_POST['tdurasipertama'];
    $durasi_kedua = $_POST['tdurasikedua'];
    $dirigen = $_POST['tdirigen']; // Ambil data dirigen

    // Koneksi ke database
    $koneksi = connectDB();
    
    // Periksa apakah nama sudah ada untuk periode penilaian tertentu
    $sqlCheck = "SELECT * FROM alternatif WHERE nama_alternatif = ?";
    $stmtCheck = $koneksi->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $nama_alternatif);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        // Jika nama alternatif sudah ada, tampilkan pesan kesalahan
?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Data sudah ada, silakan masukkan data peserta lomba yang lain.'
            }).then((result) => {
                window.history.back();
            })
        </script>
<?php
    } else {
        // Menghitung total durasi
        list($menit_pertama, $detik_pertama) = explode(':', $durasi_pertama);
        list($menit_kedua, $detik_kedua) = explode(':', $durasi_kedua);
        
        $total_menit = $menit_pertama + $menit_kedua;
        $total_detik = $detik_pertama + $detik_kedua;

        // Menyesuaikan total durasi jika detik lebih dari 59
        if ($total_detik >= 60) {
            $total_menit += floor($total_detik / 60);
            $total_detik = $total_detik % 60;
        }

        // Format total durasi menjadi mm:ss
        $total_durasi = sprintf('%02d:%02d', $total_menit, $total_detik);

        // Persiapan simpan data baru
        $simpan = "INSERT INTO alternatif (nama_alternatif, nomor_urut, lagu_pertama, durasi_pertama, lagu_kedua, durasi_kedua, total_durasi, dirigen)
           VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // Menjalankan query
        $stmt = $koneksi->prepare($simpan);
        $stmt->bind_param("ssssssss", $nama_alternatif, $nomor_urut, $lagupertama, $durasi_pertama, $lagukedua, $durasi_kedua, $total_durasi, $dirigen); // Tambah $dirigen

        if ($stmt->execute()) {
?>
            <!-- Jika simpan sukses -->
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: 'Simpan data berhasil!'
                }).then((result) => {
                    window.location = 'alternatifView.php';
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
                    window.location = 'alternatifView.php';
                })
            </script>
<?php
        }
        $stmt->close();
    }

    // Menutup koneksi ke database
    $koneksi->close();
}

// Uji jika tombol ubah di klik
if (isset($_POST['bubah'])) {
    // Mengambil data dari form
    $id_alternatif = $_POST['id_alternatif'];
    $nama_alternatif = $_POST['tnama'];
    $nomor_urut = $_POST['turut'];
    $lagupertama = $_POST['tlagupertama'];
    $lagukedua = $_POST['tlagukedua'];
    $durasi_pertama = $_POST['tdurasipertama'];
    $durasi_kedua = $_POST['tdurasikedua'];
    $dirigen = $_POST['tdirigen']; // Ambil data dirigen

    // Koneksi ke database
    $koneksi = connectDB();
    
    // Menghitung total durasi
    list($menit_pertama, $detik_pertama) = explode(':', $durasi_pertama);
    list($menit_kedua, $detik_kedua) = explode(':', $durasi_kedua);
    
    $total_menit = $menit_pertama + $menit_kedua;
    $total_detik = $detik_pertama + $detik_kedua;

    // Menyesuaikan total durasi jika detik lebih dari 59
    if ($total_detik >= 60) {
        $total_menit += floor($total_detik / 60);
        $total_detik = $total_detik % 60;
    }

    // Format total durasi menjadi mm:ss
    $total_durasi = sprintf('%02d:%02d', $total_menit, $total_detik);

    // Persiapan update data
    $ubah = "UPDATE alternatif SET nama_alternatif = ?, nomor_urut = ?, lagu_pertama = ?, lagu_kedua = ?, durasi_pertama = ?, durasi_kedua = ?, total_durasi = ?, dirigen = ? WHERE id_alternatif = ?";

    // Menjalankan query
    $stmt = $koneksi->prepare($ubah);
    $stmt->bind_param("ssssssssi", $nama_alternatif, $nomor_urut, $lagupertama, $lagukedua, $durasi_pertama, $durasi_kedua, $total_durasi, $dirigen, $id_alternatif); // Tambah $dirigen

    if ($stmt->execute()) {
?>
        <!-- Jika ubah sukses -->
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: 'Ubah data berhasil!'
            }).then((result) => {
                window.location = 'alternatifView.php';
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
                window.location = 'alternatifView.php';
            })
        </script>
<?php
    }
    $stmt->close();

    // Menutup koneksi ke database
    $koneksi->close();
        } else {
        }
        //Uji jika tombol hapus di klik
        if (isset($_POST['bhapus'])) {
            // Mengambil data dari form
            $id_alternatif = $_POST['id_alternatif'];

            // Koneksi ke database
            $koneksi = connectDB();

            // Query untuk menghapus data pada tabel kriteria
            $hapus = "DELETE FROM alternatif WHERE id_alternatif = '$id_alternatif'";

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
                        window.location = 'alternatifView.php';
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
                        window.location = 'alternatifView.php';
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
    header("Location: alternatifView.php");
    exit();
}
?>