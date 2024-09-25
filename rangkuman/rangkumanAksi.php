<?php
session_start();

// Memanggil Koneksi Database
require_once "../assets/koneksi.php";

if (isset($_POST['id_alternatif'])) {
    $_SESSION['id_alternatif'] = $_POST['id_alternatif'];
} else { // Jika tidak ada ID kriteria atau ID subkriteria
    header("Location: penilaianView.php");
    exit;
}

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

    <?php
    // Pastikan tombol 'bsimpan' telah diklik
    if (isset($_POST['bsimpan'])) {
        // Ambil data yang dikirimkan melalui form
        $id_alternatif = $_POST['id_alternatif'];
        $id_user = $_POST['id_user'];

        // Koneksi ke database
        $koneksi = connectDB();

        // Flag untuk menandai kesalahan dalam penyimpanan data
        $error_flag = false;
        $total_persentase = 0;
        $nilai_berbobot = 0;

        // Loop melalui setiap id_kriteria yang dikirimkan melalui form
        foreach ($_POST['nilai'] as $id_kriteria => $subkriteria) {
            // Ambil komentar untuk kriteria ini
            $komentar = isset($_POST['komentar'][$id_kriteria]) ? $_POST['komentar'][$id_kriteria] : '';

            // Loop melalui setiap subkriteria_id dan nilainya
            foreach ($subkriteria as $subkriteria_id => $nilai) {
                // Pastikan nilai yang dikirim sesuai dengan yang diharapkan (validasi di sini)
                $nilai = (float)$nilai; // Ubah nilai menjadi float untuk mendukung desimal

                $sql_subkriteria = "SELECT subkriteria_nilai 
            FROM subkriteria 
            WHERE subkriteria_id = $subkriteria_id";

                $result_subkriteria = $koneksi->query($sql_subkriteria);

                if ($result_subkriteria && $result_subkriteria->num_rows > 0) {
                    $subkriteria_data = $result_subkriteria->fetch_assoc();
                    $subkriteria_nilai = $subkriteria_data['subkriteria_nilai'];

                    // Hitung nilai berbobot
                    $nilai_berbobot = ($nilai * $subkriteria_nilai) / 100;

                    // Tambahkan nilai berbobot ke total persentase
                    $total_persentase += $nilai_berbobot;
                }

                // Query untuk menyimpan data penilaian ke dalam tabel penilaian
                $sql_insert_penilaian = "INSERT INTO penilaian (id_alternatif, id, id_kriteria, subkriteria_id, nilai, komentar) 
                                        VALUES ('$id_alternatif', '$id_user', '$id_kriteria', '$subkriteria_id', '$nilai', '$komentar')";
                $result_insert_penilaian = $koneksi->query($sql_insert_penilaian);

                // Periksa apakah query berhasil dijalankan
                if (!$result_insert_penilaian) {
                    // Jika gagal, set flag error menjadi true
                    $error_flag = true;
                }
            }
        }

        // Hitung nilai akhir berdasarkan total persentase dan jumlah kriteria
        $nilai_akhir = $total_persentase / 2;

        // Simpan nilai akhir ke tabel penilaian
        $sql_simpan_nilai_akhir = "UPDATE penilaian 
     SET nilai_akhir = $nilai_akhir 
     WHERE id_alternatif = $id_alternatif 
     AND id = $id_user";

        $result_simpan = $koneksi->query($sql_simpan_nilai_akhir);

        // Tutup koneksi ke database
        $koneksi->close();

        // Menampilkan pesan berdasarkan hasil operasi
        if ($error_flag || !$result_simpan) {
            echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Simpan data gagal!'
            }).then((result) => {
                window.location = 'penilaianView.php';
            })
        </script>";
        } else {
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: 'Simpan data berhasil!'
            }).then((result) => {
                window.location = 'penilaianView.php';
            })
        </script>";
        }
    }

    if (isset($_POST['bubah'])) {
        // Ambil data yang dikirimkan melalui form
        $id_alternatif = $_POST['id_alternatif'];
        $id_user = $_POST['id_user'];
    
        // Koneksi ke database
        $koneksi = connectDB();
    
        // Flag untuk menandai kesalahan dalam penyimpanan data
        $error_flag = false;
        $total_persentase = 0;
    
        // Loop melalui setiap id_kriteria yang dikirimkan melalui form
        foreach ($_POST['nilai'] as $id_kriteria => $nilai_subkriteria) {
            // Loop melalui setiap subkriteria_id dan nilainya
            foreach ($nilai_subkriteria as $subkriteria_id => $nilai) {
                $nilai = (float)$nilai; // Ubah nilai menjadi float untuk mendukung desimal
    
                if (!empty($nilai)) {
                    // Query untuk memeriksa apakah data penilaian sudah ada
                    $sql_check_exist = "SELECT * FROM penilaian WHERE id_alternatif = $id_alternatif AND id_kriteria = $id_kriteria AND id = $id_user AND subkriteria_id = $subkriteria_id";
                    $result_check_exist = $koneksi->query($sql_check_exist);
    
                    // Mendapatkan subkriteria_nilai untuk perhitungan nilai berbobot
                    $sql_subkriteria = "SELECT subkriteria_nilai FROM subkriteria WHERE subkriteria_id = $subkriteria_id";
                    $result_subkriteria = $koneksi->query($sql_subkriteria);
                    $subkriteria_nilai = ($result_subkriteria && $result_subkriteria->num_rows > 0) ? $result_subkriteria->fetch_assoc()['subkriteria_nilai'] : 0;
    
                    // Hitung nilai berbobot
                    $nilai_berbobot = ($nilai * $subkriteria_nilai) / 100;
                    $total_persentase += $nilai_berbobot;
    
                    if ($result_check_exist && $result_check_exist->num_rows > 0) {
                        // Jika entri penilaian sudah ada, lakukan operasi pembaruan data
                        $sql_update_penilaian = "UPDATE penilaian SET nilai = '$nilai' WHERE id_alternatif = $id_alternatif AND id_kriteria = $id_kriteria AND id = $id_user AND subkriteria_id = $subkriteria_id";
                        $result_update_penilaian = $koneksi->query($sql_update_penilaian);
                        if (!$result_update_penilaian) {
                            // Jika gagal operasi, set flag error
                            $error_flag = true;
                        }
                    } else {
                        // Jika entri penilaian belum ada, lakukan operasi penyimpanan data
                        $sql_insert_penilaian = "INSERT INTO penilaian (id_alternatif, id, id_kriteria, subkriteria_id, nilai) VALUES ('$id_alternatif', '$id_user', '$id_kriteria', '$subkriteria_id', '$nilai')";
                        $result_insert_penilaian = $koneksi->query($sql_insert_penilaian);
                        if (!$result_insert_penilaian) {
                            // Jika gagal operasi, set flag error
                            $error_flag = true;
                        }
                    }
                }
            }
    
            // Perbarui komentar jika ada
            if (isset($_POST['komentar'][$id_kriteria])) {
                $komentar = $_POST['komentar'][$id_kriteria]; // Ambil komentar dari form
                $sql_update_komentar = "UPDATE penilaian SET komentar = '$komentar' WHERE id_alternatif = $id_alternatif AND id_kriteria = $id_kriteria AND id = $id_user";
                $result_update_komentar = $koneksi->query($sql_update_komentar);
                if (!$result_update_komentar) {
                    $error_flag = true;
                }
            }
        }
    
        // Hitung nilai akhir berdasarkan total persentase
        $nilai_akhir = $total_persentase / 2;
    
        // Simpan nilai akhir ke tabel penilaian
        $sql_simpan_nilai_akhir = "UPDATE penilaian SET nilai_akhir = $nilai_akhir WHERE id_alternatif = $id_alternatif AND id = $id_user";
        $result_simpan = $koneksi->query($sql_simpan_nilai_akhir);
    
        // Tutup koneksi ke database
        $koneksi->close();
    
        // Menampilkan pesan berdasarkan hasil operasi
        if ($error_flag || !$result_simpan) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Ubah data gagal!'
                }).then((result) => {
                    window.location = 'penilaianView.php';
                })
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: 'Ubah data berhasil!'
                }).then((result) => {
                    window.location = 'penilaianView.php';
                })
            </script>";
        }
    }    
    ?>

</body>

</html>