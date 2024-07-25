<?php
require_once "../assets/koneksi.php"; // Memanggil koneksi database
$koneksi = connectDB(); // Memeriksa koneksi database

if (isset($_SESSION['id']) && isset($_SESSION['nama'])) {
    // Mengambil ID pengguna yang sedang login dari sesi
    $id_user = $_SESSION['id'];

    // Query untuk mendapatkan informasi pengguna dari database
    $sql = "SELECT * FROM user WHERE id = $id_user";
    $result = $koneksi->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Mendapatkan informasi nama dan level pengguna dari hasil query
        $nama_pengguna = $user['nama'];
        $level = $user['level'];
    } else {
        echo "Data pengguna tidak ditemukan atau terjadi kesalahan.";
        exit();
    }
?>

    <div class="sidebar">
        <div class="logo-details">
            <div class="logo_name">Sistem Informasi Penilaian Pesparawi</div>
            <i class="bx bx-menu" id="btn"></i>
        </div>
        <ul class="nav-list">
            <li>
                <a href="../beranda/home.php">
                    <i class="fa-solid fa-house"></i>
                    <span class="links_name">Beranda</span>
                </a>
                <span class="tooltip">Beranda</span>
            </li>
            <li>
                <a href="../kriteria/kriteriaView.php">
                    <i class="fa-solid fa-box"></i>
                    <span class="links_name">Data Lagu</span>
                </a>
                <span class="tooltip">Data Lagu</span>
            </li>
            <li>
                <a href="../subkriteria/subkriteriaView.php">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span class="links_name">Data Materi Penilaian</span>
                </a>
                <span class="tooltip">Data Materi Penilaian</span>
            </li>
            <li>
                <a href="../alternatif/alternatifView.php">
                    <i class="fa-solid fa-users"></i>
                    <span class="links_name">Data Peserta Lomba</span>
                </a>
                <span class="tooltip">Data Peserta Lomba</span>
            </li>
            <?php if ($_SESSION['level'] == 'admin') : ?>
                <li>
                    <a href="../dataPeriode/dataPeriodeView.php">
                        <i class="fa-regular fa-calendar-check"></i>
                        <span class="links_name">Data Periode</span>
                    </a>
                    <span class="tooltip">Data Periode</span>
                </li>
            <?php endif; ?>
            <?php if ($_SESSION['level'] == 'juri') : ?>
                <li>
                    <a href="../penilaian/penilaianView.php">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span class="links_name">Data Penilaian</span>
                    </a>
                    <span class="tooltip">Data Penilaian</span>
                </li>
                <li>
                    <a href="../perhitungan/perhitunganView.php">
                        <i class="fa-solid fa-calculator"></i>
                        <span class="links_name">Data Perhitungan</span>
                    </a>
                    <span class="tooltip">Data Perhitungan</span>
                </li>
            <?php endif; ?>
            <li>
                <a href="../hasilAkhir/hasilakhirView.php">
                    <i class="fa-solid fa-chart-column"></i>
                    <span class="links_name">Data Hasil Akhir</span>
                </a>
                <span class="tooltip">Data Hasil Akhir</span>
            </li>
            <?php if ($_SESSION['level'] == 'admin') : ?>
                <li>
                    <a href="../dataUser/datauserView.php">
                        <i class="fa-regular fa-address-card"></i>
                        <span class="links_name">Data User</span>
                    </a>
                    <span class="tooltip">Data User</span>
                </li>
            <?php endif; ?>
            <li class="profile">
                <div class="profile-details">
                    <a href="../profile/profileView.php">
                        <div class="name_level">
                            <div class="name">&nbsp;&nbsp;Halo, <?php echo $nama_pengguna; ?>!</div>
                            <div class="level">&nbsp;&nbsp;<?php echo $level; ?></div>
                        </div>
                    </a>
                    <div class="profile-logout">
                        <a href="../login/logout.php">
                            <i class="bx bx-log-out" id="log_out"></i>
                        </a>
                    </div>
                </div>
            </li>
        </ul>
    </div>

<?php
} else {
    header("Location: ../index.php");
    exit();
}
?>