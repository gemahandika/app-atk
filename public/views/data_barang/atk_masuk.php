<?php
session_name("dashboard_atk_session");
session_start();
if (!isset($_SESSION['admin_akses'])) {
    header("Location:../page_error/error.php");
    exit();
} else if (!in_array("super_admin", $_SESSION['admin_akses']) && !in_array("admin", $_SESSION['admin_akses'])) {
    header("Location:../page_error/error.php");

    exit();
} else {
    include '../../header.php';
    include 'modal.php';
?>

    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="border-bottom: 1px solid black;">Data ATK Masuk</h5>
                            <table id="example1" class="display nowrap" style="width:100%">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th class="text-center" style="font-size: 12px;">No</th>
                                        <th class="text-center" style="font-size: 12px;">TGL REQUEST</th>
                                        <th class="text-center" style="font-size: 12px;">KODE BARANG</th>
                                        <th class="text-center" style="font-size: 12px;">NAMA BARANG</th>
                                        <th class="text-center" style="font-size: 12px;">SATUAN</th>
                                        <th class="text-center" style="font-size: 12px;">JLH PERMINTAAN</th>
                                        <th class="text-center" style="font-size: 12px;">JLH DITERIMA</th>
                                        <th class="text-center" style="font-size: 12px;">TGL TERIMA</th>
                                        <th class="text-center" style="font-size: 12px;">AWB</th>
                                        <th class="text-center" style="font-size: 12px;">KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 0;
                                    $sql = mysqli_query($koneksi, "SELECT * FROM atk_masuk ORDER BY id_atkmasuk DESC") or die(mysqli_error($koneksi));
                                    $result = array();
                                    while ($data = mysqli_fetch_array($sql)) {
                                        $result[] = $data;
                                    }
                                    foreach ($result as $data) {
                                        $no++;
                                    ?>
                                        <tr>
                                            <td class="text-center" style="font-size: 12px;"><?= $no; ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['tgl_request'] ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['kode_barang'] ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['nama_barang'] ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['satuan'] ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['jumlah_permintaan'] ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['jumlah_terima'] ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['tgl_terima'] ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['awb'] ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['keterangan'] ?></td>
                                        </tr>
                                    <?php } ?>
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

    <!-- <script>
        function hitungTotal(id_barang) {
            // Ambil nilai stok awal dan nilai tambah berdasarkan id_barang
            var stokAwal = parseInt(document.getElementById('stok_awal' + id_barang).value) || 0;
            var tambahStok = parseInt(document.getElementById('tambah_stok' + id_barang).value) || 0;

            // Hitung total
            var totalStok = stokAwal + tambahStok;

            // Masukkan hasil ke input total stok
            document.getElementById('total_stok' + id_barang).value = totalStok;
        }
    </script> -->
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


<?php } ?>

</body>

</html>