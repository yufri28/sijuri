<?php
// Fungsi untuk mendapatkan data user (juri)
function getUser($koneksi)
{
    $sql = "SELECT id, nama_lengkap FROM user WHERE level = 'juri'";
    $result = $koneksi->query($sql);

    if ($result && $result->num_rows > 0) {
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = [
                'id' => $row['id'],
                'nama_lengkap' => $row['nama_lengkap'],
            ];
        }
        return $users;
    } else {
        return [];
    }
}

// Fungsi untuk mendapatkan data alternatif (peserta lomba)
function getAlternatif($koneksi)
{
    $sql = "SELECT DISTINCT a.id_alternatif, a.nama_alternatif
            FROM penilaian AS p
            JOIN alternatif AS a ON p.id_alternatif = a.id_alternatif
            ORDER BY 
                CASE 
                    WHEN a.nomor_urut = 0 THEN 1 
                    ELSE 0 
                END, 
                a.nomor_urut"; // Urutkan berdasarkan nomor_urut, dengan 0 di akhir
    $result = $koneksi->query($sql);

    if ($result && $result->num_rows > 0) {
        $alternatif = [];
        while ($row = $result->fetch_assoc()) {
            $alternatif[] = [
                'id_alternatif' => $row['id_alternatif'],
                'nama_alternatif' => $row['nama_alternatif'],
            ];
        }
        return $alternatif;
    } else {
        return [];
    }
}


// Fungsi untuk mendapatkan data kriteria
function getKriteria($koneksi)
{
    $sql = "SELECT * FROM kriteria";
    $result = $koneksi->query($sql);

    if ($result && $result->num_rows > 0) {
        $kriteria = [];
        while ($row = $result->fetch_assoc()) {
            $kriteria[] = [
                'id_kriteria' => $row['id_kriteria'],
                'kode_kriteria' => $row['kode_kriteria'],
                'nama_kriteria' => $row['nama_kriteria'],
                'ket_kriteria' => $row['ket_kriteria'],
            ];
        }
        return $kriteria;
    } else {
        return [];
    }
}

// Fungsi untuk mendapatkan data subkriteria
function getSubkriteria($koneksi)
{
    $sql = "SELECT * FROM subkriteria";
    $result = $koneksi->query($sql);

    if ($result && $result->num_rows > 0) {
        $subkriteria = [];
        while ($row = $result->fetch_assoc()) {
            $subkriteria[] = [
                'subkriteria_id' => $row['subkriteria_id'],
                'subkriteria_keterangan' => $row['subkriteria_keterangan'],
                'subkriteria_nilai' => $row['subkriteria_nilai'],
            ];
        }
        return $subkriteria;
    } else {
        return [];
    }
}

// Fungsi untuk mendapatkan nilai akhir dari setiap alternatif dan membaginya dengan 5
function getNilaiAkhir($koneksi)
{
    $nilai_akhir = [];
    // Query untuk menghitung jumlah nilai_akhir unik per id_alternatif dan id_user
    $sql = "SELECT id_alternatif, SUM(DISTINCT nilai_akhir) / 5 AS nilai_akhir
            FROM penilaian 
            GROUP BY id_alternatif"; // Mengelompokkan berdasarkan id_alternatif saja
    $result = $koneksi->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Menyimpan nilai_akhir yang telah dijumlahkan dan dibagi 5 untuk tiap id_alternatif
            $nilai_akhir[$row['id_alternatif']] = $row['nilai_akhir'];
        }
    }

    return $nilai_akhir;
}


// Fungsi untuk mendapatkan peringkat berdasarkan nilai hasil akhir
// Fungsi untuk memberikan peringkat pada data
function berikanPeringkat($nilai_akhir, $alternatif)
{
    // Mengurutkan alternatif berdasarkan nilai akhir
    usort($alternatif, function ($a, $b) use ($nilai_akhir) {
        // Jika nilai akhir sama, bandingkan nama alternatif untuk memastikan urutan yang konsisten
        if ($nilai_akhir[$b['id_alternatif']] == $nilai_akhir[$a['id_alternatif']]) {
            return strcmp($a['nama_alternatif'], $b['nama_alternatif']);
        }
        return $nilai_akhir[$b['id_alternatif']] <=> $nilai_akhir[$a['id_alternatif']];
    });

    // Memberikan peringkat pada setiap alternatif
    $peringkat = 1;
    $prev_score = null;
    $prev_same_score_rank = null; // Menyimpan peringkat untuk nilai yang sama
    foreach ($alternatif as $key => $row) {
        if ($nilai_akhir[$row['id_alternatif']] != $prev_score) {
            $prev_score = $nilai_akhir[$row['id_alternatif']];
            $prev_same_score_rank = $peringkat; // Simpan peringkat terakhir untuk nilai yang sama
        }
        $alternatif[$key]['peringkat'] = $prev_same_score_rank; // Gunakan peringkat yang sama jika nilai sama
        $peringkat = $prev_same_score_rank + 1; // Set peringkat berikutnya untuk nilai berbeda
    }

    return $alternatif;
}

// Fungsi untuk mendapatkan kelompok berdasarkan nilai
function getKelompok($nilai)
{
    if ($nilai >= 80) {
        return 'Gold';
    } elseif ($nilai >= 70) {
        return 'Silver';
    } elseif ($nilai >= 60) {
        return 'Bronze';
    } elseif ($nilai >= 40) {
        return 'Diploma';
    } else {
        return 'Diploma';
    }
}
