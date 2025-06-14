<?php
session_name("dashboard_atk_session");
session_start();
include '../../header.php';
include 'modal.php';
// include '../../../app/models/Report_models.php';
$date = date("Y-m-d");
$time = date("H:i");
?>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const fromDateInput = document.querySelector('input[name="dari"]');
        const thruDateInput = document.querySelector('input[name="ke"]');

        fromDateInput.addEventListener('change', () => {
            if (fromDateInput.value) {
                thruDateInput.min = fromDateInput.value;
            } else {
                thruDateInput.removeAttribute('min');
            }
        });

        thruDateInput.addEventListener('change', () => {
            if (thruDateInput.value < fromDateInput.value) {
                alert("Tidak Bisa Memilih Tanggal Sebelum Tanggal From");
                thruDateInput.value = '';
            }
        });
    });
</script>
<?php
// Mengambil parameter dari form (jika ada)
$dari = isset($_GET['dari']) ? $_GET['dari'] : '';
$ke = isset($_GET['ke']) ? $_GET['ke'] : '';
$user_id1 = isset($_GET['user_id']) ? mysqli_real_escape_string($koneksi, $_GET['user_id']) : '';
?>
<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title" style="border-bottom: 1px solid black;">Detail Items Pesanan</h5>
                        <!-- Primary Color Bordered Table -->

                        <form action="index.php" method="get">
                            <div class="row mb-3 d-flex align-items-center">
                                <div class="col-sm-2 mt-2">
                                    <input type="date" class="form-control" name="dari" value="<?= isset($_GET['dari']) ? $_GET['dari'] : '' ?>" required>
                                </div>
                                <div class="col-sm-2 mt-2">
                                    <input type="date" class="form-control" name="ke" value="<?= isset($_GET['ke']) ? $_GET['ke'] : '' ?>" required />
                                </div>
                                <?php
                                if (has_access($allowed_admin)) { ?>
                                    <div class="col-sm-2 mt-2">
                                        <input type="text" class="form-control" id="user_id" name="user_id" value="<?= $user_id1 ?>">
                                    </div>
                                    <div class="col-sm-2 mt-2">
                                        <!-- Tombol untuk membuka modal -->
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchModal">
                                            Pilih User
                                        </button>
                                    </div>
                                <?php } ?>
                                <div class="col-sm-4 mt-2">
                                    <button type="submit" class="btn btn-success">Cari Data</button>
                                    <a href="index.php" type="button" class="btn btn-secondary">Refresh</a>
                                    <?php
                                    if (has_access($allowed_admin)) { ?>
                                        <a href="export_keranjang.php?dari=<?= isset($_GET['dari']) ? $_GET['dari'] : '' ?>&ke=<?= isset($_GET['ke']) ? $_GET['ke'] : '' ?>&user_id=<?= isset($_GET['user_id']) ? $_GET['user_id'] : '' ?>" type="button" class="btn btn-primary">Download</a>
                                    <?php }
                                    if (has_access($allowed_agen)) { ?>
                                        <a href="export_keranjang.php?dari=<?= isset($_GET['dari']) ? $_GET['dari'] : '' ?>&ke=<?= isset($_GET['ke']) ? $_GET['ke'] : '' ?>" type="button" class="btn btn-primary">Download</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>

                        <table id="example1" class="display nowrap" style="width:100%">
                            <thead>
                                <tr class="bg-info text-white">
                                    <th class="small text-center" style="font-size: 12px;">NO</th>
                                    <th class="small text-center" style="font-size: 12px;">KODE BARANG</th>
                                    <!-- <th class="small text-center" style="font-size: 12px;">KATAGORI</th> -->
                                    <th class="small text-center" style="font-size: 12px;">TGL PESAN</th>
                                    <th class="small text-center" style="font-size: 12px;">NAMA_BARANG</th>
                                    <th class="small text-center" style="font-size: 12px;">SATUAN</th>
                                    <th class="small text-center" style="font-size: 12px;">JUMLAH</th>
                                    <th class="small text-center" style="font-size: 12px;">HARGA</th>
                                    <th class="small text-center" style="font-size: 12px;">TOTAL HARGA</th>
                                    <th class="small text-center" style="font-size: 12px;">USER ID</th>
                                    <th class="small text-center" style="font-size: 12px;">NAMA PEMESAN</th>
                                    <th class="small text-center" style="font-size: 12px;">INVOICE</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Ambil parameter dari GET
                                $dari = isset($_GET['dari']) ? mysqli_real_escape_string($koneksi, $_GET['dari']) : null;
                                $ke = isset($_GET['ke']) ? mysqli_real_escape_string($koneksi, $_GET['ke']) : null;
                                $user_id1 = isset($_GET['user_id']) ? mysqli_real_escape_string($koneksi, $_GET['user_id']) : '';

                                // Jika user_id adalah 'All', kosongkan variabel untuk menghindari filter user
                                if ($user_id1 === 'All') {
                                    $user_id1 = ''; // Set kosong untuk mengindikasikan semua data
                                }

                                // Cek akses pengguna
                                if (!has_access($allowed_admin) && !has_access($allowed_agen)) {
                                    die("Akses ditolak.");
                                }

                                // Buat query berdasarkan akses dan parameter
                                if (!$dari || !$ke) {
                                    // Jika tanggal tidak dipilih, ambil semua data
                                    if (has_access($allowed_admin)) {
                                        $query = $user_id1 ?
                                            "SELECT * FROM tb_keranjang WHERE user_id = '$user_id1' ORDER BY id_keranjang DESC" :
                                            "SELECT * FROM tb_keranjang ORDER BY id_keranjang DESC";
                                    } elseif (has_access($allowed_agen)) {
                                        $query = "SELECT * FROM tb_keranjang WHERE user_id = '$user1' ORDER BY id_keranjang DESC";
                                    }
                                } else {
                                    // Jika tanggal dipilih, ambil data berdasarkan rentang tanggal
                                    if (has_access($allowed_admin)) {
                                        $query = $user_id1 ?
                                            "SELECT * FROM tb_keranjang WHERE user_id = '$user_id1' AND tgl_pesan BETWEEN '$dari' AND '$ke' ORDER BY id_keranjang DESC" :
                                            "SELECT * FROM tb_keranjang WHERE tgl_pesan BETWEEN '$dari' AND '$ke' ORDER BY id_keranjang DESC";
                                    } elseif (has_access($allowed_agen)) {
                                        $query = "SELECT * FROM tb_keranjang WHERE user_id = '$user1' AND tgl_pesan BETWEEN '$dari' AND '$ke' ORDER BY id_keranjang DESC";
                                    }
                                }

                                // Eksekusi query
                                $sql_keranjang = mysqli_query($koneksi, $query);

                                // Cek apakah query berhasil dijalankan
                                if (!$sql_keranjang) {
                                    die("Error dalam query: " . mysqli_error($koneksi));
                                }

                                // Cek apakah ada data yang ditemukan
                                if (mysqli_num_rows($sql_keranjang) > 0) {
                                    // Perulangan untuk menampilkan data
                                    $no = 1;
                                    while ($data = mysqli_fetch_array($sql_keranjang)) {
                                        // Tampilkan data pesanan
                                ?>
                                        <tr>
                                            <td class="th-small text-center" style="font-size: 12px;"><?= $no++; ?></td>
                                            <td class="th-small text-center" style="font-size: 12px;"><?= $data['kode_barang']; ?></td>
                                            <!-- <td class="th-small text-center" style="font-size: 12px;"><?= $data['katagori']; ?></td> -->
                                            <td class="th-small text-center" style="font-size: 12px;"><?= $data['tgl_pesan']; ?></td>
                                            <td class="th-small text-center" style="font-size: 12px;"><?= $data['nama_barang']; ?></td>
                                            <td class="th-small text-center" style="font-size: 12px;"><?= $data['satuan']; ?></td>
                                            <td class="th-small text-center" style="font-size: 12px;"><?= $data['jumlah']; ?></td>
                                            <td class="th-small text-center" style="font-size: 12px;"><?= number_format($data['harga'], 0, ',', '.'); ?></td>
                                            <td class="th-small text-center" style="font-size: 12px;"><?= number_format($data['total_harga'], 0, ',', '.'); ?></td>
                                            <td class="th-small text-center" style="font-size: 12px;"><?= $data['user_id']; ?></td>
                                            <td class="th-small text-center" style="font-size: 12px;"><?= $data['nama_user']; ?></td>
                                            <td class="th-small text-center" style="font-size: 12px;"><?= $data['invoice']; ?></td>

                                        </tr>
                                <?php  }
                                }  ?>
                            </tbody>
                        </table>
                        <!-- End Primary Color Bordered Table -->
                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- End #main -->

<!-- ======= Footer ======= -->
<!-- Vendor JS Files -->
<script src="../../../app/assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="../../../app/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../../app/assets/vendor/chart.js/chart.umd.js"></script>
<script src="../../../app/assets/vendor/echarts/echarts.min.js"></script>
<script src="../../../app/assets/vendor/quill/quill.js"></script>
<script src="../../../app/assets/vendor/tinymce/tinymce.min.js"></script>
<script src="../../../app/assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="../../../app/assets/js/main.js"></script>

<!-- CDN JS Libraries -->

<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>

<!-- Tambahkan SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Inisialisasi DataTables -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('#example1')) {
            new DataTable('#example1', {
                paging: true,
                scrollCollapse: true,
                scrollY: '335px'
            });
        }

        if (document.querySelector('#example')) {
            new DataTable('#example', {
                paging: true,
                scrollCollapse: false
                // scrollY: '350px'
            });
        }
    });
</script>
</body>

</html>