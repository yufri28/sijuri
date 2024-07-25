<?php
// Fungsi untuk membuat koneksi ke database
function connectDB()
{
    $host = "localhost";
    $username = "sijuribi_musik";
    $password = "&53AqTciT5x~";
    $database = "sijuribi_musik";

    // Buat koneksi
    $koneksi = new mysqli($host, $username, $password, $database);

    // Periksa koneksi
    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    return $koneksi;
}