<?php
session_start();
include "../assets/koneksi.php";

if (isset($_POST['username']) && isset($_POST['password'])) {
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = validate($_POST['username']);
    $password = validate($_POST['password']);

    $koneksi = connectDB();

    // Mendapatkan hash kata sandi dari database untuk pengguna yang mencoba login
    $sql = "SELECT id, nama, katasandi, level FROM user WHERE nama='$username'";
    $result = $koneksi->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $db_password = $row['katasandi']; // Hash kata sandi dari database

        // Hash kata sandi yang dimasukkan oleh pengguna
        $hashed_input_password = md5($password);

        if ($hashed_input_password === $db_password) { // Bandingkan hash kata sandi
            // Login berhasil
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['id'] = $row['id'];
            $_SESSION['level'] = $row['level'];
            header("Location: ../beranda/home.php");
            exit();
        } else {
            header("Location: ../index.php?error=Kesalahan pada username atau password");
            exit();
        }
    } else {
        header("Location: ../index.php?error=Kesalahan pada username atau password");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
