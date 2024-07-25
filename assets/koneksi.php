<?php
// Fungsi untuk membuat koneksi ke database
function connectDB()
{
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "musik";

    // Buat koneksi
    $koneksi = new mysqli($host, $username, $password, $database);

    // Periksa koneksi
    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    return $koneksi;
}
