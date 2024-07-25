<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['nama'])) {

    // Memanggil Koneksi Database
    require_once "../assets/koneksi.php";
    $koneksi = connectDB(); // Menggunakan fungsi connectDB() untuk mendapatkan koneksi database

    require_once "../perhitungan/PerhitunganAksi.php";

    // Mendapatkan ID periode aktif
    $sql_periode_aktif = "SELECT id_periode FROM periode WHERE status_periode = 'Aktif'";
    $result_periode_aktif = $koneksi->query($sql_periode_aktif);
    if ($result_periode_aktif && $result_periode_aktif->num_rows > 0) {
        $row_periode_aktif = $result_periode_aktif->fetch_assoc();
        $id_periode_aktif = $row_periode_aktif['id_periode'];
    }

    // Mendapatkan data alternatif (peserta lomba)
    $alternatif = getAlternatif($koneksi);

    // Mendapatkan data user (juri)
    $users = getUser($koneksi);

    // Mendapatkan nilai akhir dari setiap alternatif berdasarkan periode aktif
    $nilai_akhir = getNilaiAkhir($koneksi, $id_periode_aktif);

    // Mendapatkan peringkat berdasarkan nilai rata-rata akhir
    $alternatif_dengan_peringkat = berikanPeringkat($nilai_akhir, $alternatif);

?>

    <!DOCTYPE html>
    <html lang="en" dir="ltr">

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
        <!-- Tables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css">
        <!-- Chart -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>

    <body>
        <?php include "../assets/basic/sidebar.php"; ?>
        <!-- START SECTION -->
        <section class="home-section">
            <div class="text">Data Hasil Akhir
                <div class="container-hasil">
                    <div class="card mt-4">
                        <div class="card-header fa-regular fa-rectangle-list">
                            <span><b>Daftar Hasil Akhir - Periode
                                    <?php
                                    // Ambil tahun dan bulan periode yang aktif dari database
                                    $sql = "SELECT tahun_periode, bulan_periode FROM periode WHERE status_periode = 'Aktif'";
                                    $result = $koneksi->query($sql);

                                    if ($result && $result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        echo $row['tahun_periode'] . ' ' . $row['bulan_periode'];
                                    } else {
                                        echo "Tidak ada periode aktif";
                                    }
                                    ?>
                                </b></span>
                        </div>
                        <div class="card-body">
                            <!-- Button Cetak -->
                            <div class="periode-cetak">
                                <label for="selectPeriode" class="form-label">Pilih Periode Cetak:</label>
                                <select class="form-select" id="selectPeriode">
                                    <!-- Opsi pilihan periode disini -->
                                    <?php
                                    // Query untuk mendapatkan daftar periode
                                    $sql_periode = "SELECT id_periode, tahun_periode, bulan_periode, status_periode FROM periode";
                                    $result_periode = $koneksi->query($sql_periode);
                                    if ($result_periode && $result_periode->num_rows > 0) {
                                        while ($row_periode = $result_periode->fetch_assoc()) {
                                            $periode_label = $row_periode['tahun_periode'] . " " . $row_periode['bulan_periode'];
                                            if ($row_periode['status_periode'] == 'Aktif') {
                                                // Tandai periode yang aktif sebagai yang dipilih dan tambahkan kata (Aktif)
                                                $periode_label .= " (Aktif)";
                                                echo "<option value='" . $row_periode['id_periode'] . "' selected>" . $periode_label . "</option>";
                                            } else {
                                                echo "<option value='" . $row_periode['id_periode'] . "'>" . $periode_label . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                <button type="button" id="btnCetakData" class="btn btn-success">
                                    <div class="fa-solid fa-print"></div><span> Cetak Data</span>
                                </button>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button id="resetButton" class="btn btn-primary">Tampilkan semua grafik</button>
                            </div>
                            <div class="container">
                                <canvas id="grafikHasilAkhir" width="800" height="400"></canvas>
                            </div>
                            <!-- Menampilkan daftar hasil akhir -->
                            <?php
                            if (!empty($alternatif_dengan_peringkat)) :
                            ?>
                                <table id="example" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No</th>
                                            <th style="text-align: center;">Nama Perguruan Tinggi</th>
                                            <?php $i = 1;
                                            foreach ($users as $user) : ?>
                                                <th style="text-align: center;" title="Nilai dari <?= $user['nama_lengkap'] ?>">Juri <?= $i++ ?></th>
                                            <?php endforeach; ?>
                                            <th style="text-align: center;" title="Gabungan nilai semua juri">Hasil Akhir</th>
                                            <th style="text-align: center;">Kelompok</th>
                                            <th style="text-align: center;">Peringkat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($alternatif_dengan_peringkat as $index => $alt) : ?>
                                            <tr>
                                                <td style="text-align: center;"><?= $index + 1 ?></td>
                                                <td style="text-align: left;"><?= $alt['nama_alternatif'] ?></td>
                                                <?php foreach ($users as $user) : ?>
                                                    <?php
                                                    $id_user = $user['id'];
                                                    $sql_nilai = "SELECT nilai_akhir FROM penilaian 
                                                                    WHERE id_alternatif = {$alt['id_alternatif']} 
                                                                    AND id = $id_user 
                                                                    AND id_periode = $id_periode_aktif";
                                                    $result_nilai = $koneksi->query($sql_nilai);
                                                    $nilai = ($result_nilai && $result_nilai->num_rows > 0) ? $result_nilai->fetch_assoc()['nilai_akhir'] : '-';
                                                    ?>
                                                    <td style="text-align: center;">
                                                        <?php if ($nilai != '-') : ?>
                                                            <?php echo number_format($nilai, 2); ?>
                                                        <?php else : ?>
                                                            <div class="alert alert-danger alert-dismissible fade show py-1 px-2 m-0" role="alert" style="font-size: 0.8rem;">
                                                                Belum diberikan nilai
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                <?php endforeach; ?>
                                                <td style="text-align: center;"><?= number_format($nilai_akhir[$alt['id_alternatif']], 2) ?></td>
                                                <td style="text-align: center;"><?= getKelompok(number_format($nilai_akhir[$alt['id_alternatif']], 2)) ?></td>
                                                <td style="text-align: center;"><?= $alt['peringkat'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <div class="alert alert-warning" role="alert">
                                    Tidak ada hasil akhir.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END SECTION -->

        <script src="//code.jquery.com/jquery-3.7.1.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="//cdn.datatables.net/2.0.2/js/dataTables.js"></script>
        <script src="//cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>
        <script src="../assets/js/script.js"></script>
        <script src="../bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>

        <script>
            new DataTable('#example');
        </script>
        <script>
            // Ketika dokumen siap
            $(document).ready(function() {
                // Ketika tombol cetak data diklik
                $("#btnCetakData").click(function() {
                    // Mendapatkan nilai ID periode yang dipilih
                    var periodeValue = $("#selectPeriode").val();
                    var selectedOptionText = $("#selectPeriode option:selected").text();
                    var tahunBulan = selectedOptionText.split(" ");
                    var tahun = tahunBulan[0];
                    var bulan = tahunBulan[1];
                    // Mengarahkan ke halaman hasilakhirCetak.php dengan menyertakan tahun dan bulan periode dalam URL
                    window.location.href = "hasilakhirCetak.php?tahun_periode=" + tahun + "&bulan_periode=" + bulan;
                });
            });
        </script>
        <script>
            // Ketika dokumen siap
            $(document).ready(function() {
                // Ketika tombol cetak data diklik
                $("#btnCetakData").click(function() {
                    // Mendapatkan nilai ID periode yang dipilih
                    var periodeValue = $("#selectPeriode").val();
                    var selectedOptionText = $("#selectPeriode option:selected").text();
                    var tahunBulan = selectedOptionText.split(" ");
                    var tahun = tahunBulan[0];
                    var bulan = tahunBulan[1];
                    // Mengarahkan ke halaman hasilakhirCetak.php dengan menyertakan tahun dan bulan periode dalam URL
                    window.location.href = "hasilakhirCetak.php?tahun_periode=" + tahun + "&bulan_periode=" + bulan;
                });
            });

            // Data untuk kategori dan warnanya
            var categories = ['Gold', 'Silver', 'Bronze', 'Diploma'];
            var categoryColors = {
                'Gold': 'rgba(255, 215, 0, 0.5)',
                'Silver': 'rgba(184, 224, 210, 0.5)',
                'Bronze': 'rgba(205, 127, 50, 0.5)',
                'Diploma': 'rgba(128, 128, 128, 0.5)'
            };

            var categoryData = {
                'Gold': [],
                'Silver': [],
                'Bronze': [],
                'Diploma': []
            };

            // Iterasi melalui data untuk mengisi array
            <?php foreach ($alternatif_dengan_peringkat as $alternatif_item) : ?>
                var nilai = <?php echo isset($nilai_akhir[$alternatif_item['id_alternatif']]) ? number_format($nilai_akhir[$alternatif_item['id_alternatif']], 2) : 0; ?>;
                var category = getCategory(nilai);
                categoryData[category].push({
                    label: '<?php echo $alternatif_item['nama_alternatif']; ?>',
                    nilai: nilai,
                    peringkat: '<?php echo isset($alternatif_item['peringkat']) ? $alternatif_item['peringkat'] : '-'; ?>'
                });
            <?php endforeach; ?>

            // Fungsi untuk mendapatkan kategori berdasarkan nilai
            function getCategory(nilai) {
                if (nilai >= 80) {
                    return 'Gold';
                } else if (nilai >= 70) {
                    return 'Silver';
                } else if (nilai >= 60) {
                    return 'Bronze';
                } else {
                    return 'Diploma';
                }
            }

            // Fungsi untuk membuat grafik awal
            function createInitialChart() {
                return new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: categories,
                        datasets: [{
                            label: 'Jumlah Peserta',
                            data: categories.map(function(cat) {
                                return categoryData[cat].length;
                            }),
                            backgroundColor: categories.map(function(cat) {
                                return categoryColors[cat];
                            }),
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        onClick: function(event, elements) {
                            if (elements.length > 0) {
                                var clickedLabel = this.data.labels[elements[0].index];
                                filterData(clickedLabel);
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    callback: function(value) {
                                        return value;
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            // Inisialisasi grafik pada elemen canvas
            var ctx = document.getElementById('grafikHasilAkhir').getContext('2d');
            var grafikHasilAkhir = createInitialChart();

            // Fungsi untuk menyaring data berdasarkan kategori
            function filterData(category) {
                var filteredLabels = [];
                var filteredData = [];
                var filteredColors = [];

                categoryData[category].forEach(function(item) {
                    filteredLabels.push(category + ': ' + item.label + ' (Peringkat ' + item.peringkat + ')');
                    filteredData.push(item.nilai);
                    filteredColors.push(categoryColors[category]);
                });

                var dataset = grafikHasilAkhir.data.datasets[0];
                dataset.label = 'Nilai';
                dataset.data = filteredData;
                grafikHasilAkhir.data.labels = filteredLabels;
                grafikHasilAkhir.data.datasets[0].data = filteredData;
                grafikHasilAkhir.data.datasets[0].backgroundColor = filteredColors;

                // Ubah stepSize menjadi 10 untuk grafik kedua
                grafikHasilAkhir.options.scales.y.ticks.stepSize = 10;

                grafikHasilAkhir.options.plugins.legend.display = true;
                grafikHasilAkhir.options.plugins.legend.labels.generateLabels = function(chart) {
                    return [{
                        text: category,
                        fillStyle: categoryColors[category],
                    }];
                };

                grafikHasilAkhir.update();
            }

            // Event listener untuk tombol reset grafik
            document.getElementById('resetButton').addEventListener('click', function() {
                grafikHasilAkhir.destroy();
                grafikHasilAkhir = createInitialChart();
            });
        </script>



    </body>

    </html>

<?php
} else {
    header("Location: ../index.php");
    exit();
}
?>