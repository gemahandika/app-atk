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
    $date = date("Y-m-d");
    $time = date("H:i");
?>

    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="border-bottom: 1px solid black;">Data Barang ATK</h5>
                            <!-- Primary Color Bordered Table -->
                            <div class="row mb-3 d-flex align-items-center">
                                <div class="col-sm-4 mt-2">
                                    <button type="submit" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#basicModal">Tambah Data</button>
                                    <a href="export.php" type="button" class="btn btn-primary">Download</a>
                                    <?php if (has_access($allowed_super_admin)) { ?>
                                        <a href="bulk_insert_barang.php" type="button" class="btn btn-primary">Upload Data</a>
                                    <?php } ?>
                                </div>

                                <table id="example1" class="display nowrap" style="width:100%">
                                    <thead>
                                        <tr class="bg-primary text-white">
                                            <th class="th-small text-center">NO</th>
                                            <th class="th-small text-center">KODE_BARANG</th>
                                            <th class="th-small text-center">NAMA BARANG</th>
                                            <th class="th-small text-center">SATUAN</th>
                                            <th class="th-small text-center">HARGA</th>
                                            <!-- <th class="th-small text-center">STATUS</th> -->
                                            <th class="th-small text-center">STOK</th>
                                            <!-- <th class="th-small text-center">MIN. ORDER</th> -->
                                            <th class="th-small text-center">BUFFER</th>
                                            <th class="th-small text-center">KETERANGAN</th>
                                            <th class="th-small text-center">ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $sql = mysqli_query($koneksi, "SELECT * FROM tb_barang ORDER BY id_barang ASC") or die(mysqli_error($koneksi));
                                        $result = array();
                                        while ($data = mysqli_fetch_array($sql)) {
                                            $result[] = $data;
                                        }
                                        foreach ($result as $data) {
                                            $no++;
                                            $stok_barang = $data['stok_barang'];
                                            $buffer = $data['buffer'];

                                            // Tentukan keterangan berdasarkan stok dan buffer
                                            if ($stok_barang <= $buffer) {
                                                $keterangan = '<h6 class=" text-danger">warning</h6>';
                                            } else {
                                                $keterangan = ''; // Kosongkan jika stok masih aman
                                            }
                                        ?>
                                            <tr>
                                                <td class="th-small text-center"><?= $no; ?></td>
                                                <td class="th-small text-center"><?= $data['kode_barang'] ?></td>
                                                <td class="th-small text-center"><?= $data['nama_barang'] ?></td>
                                                <td class="th-small text-center"><?= $data['satuan'] ?></td>
                                                <td class="th-small text-center"><?= number_format($data['harga'], 0, ',', '.') ?></td>
                                                <!-- <td class="th-small text-center"><?= $data['status_barang'] ?></td> -->
                                                <td class="th-small text-center"><?= $stok_barang ?></td>
                                                <!-- <td class="th-small text-center"><?= $data['min_order'] ?></td> -->
                                                <td class="th-small text-center"><?= $buffer ?></td>
                                                <td class="th-small text-center"><?= $keterangan ?></td>
                                                <td class="th-small text-center">
                                                    <div class="d-flex justify-content-center">
                                                        <a href="#"
                                                            class="btn btn-success btn-sm open-stok-modal"
                                                            data-id="<?= $data['id_barang'] ?>"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalStok"
                                                            style="margin-right: 5px;">Stok</a>

                                                        <a href="edit.php?id=<?= $data['id_barang'] ?>" class="btn btn-warning btn-sm text-white ">Edit</a>
                                                        <?php if (has_access($allowed_super_admin)) { ?>
                                                            <a href="delete.php?id=<?= $data['id_barang'] ?>" class="btn btn-danger btn-sm ms-1"><i class="bi bi-trash"></i></a>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <div class="modal fade" id="modalStok" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content" id="modalStokContent">
                                            <!-- Konten akan dimuat dengan AJAX -->
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    <script>
        function hitungTotal(id_barang) {
            // Ambil nilai stok awal dan nilai tambah berdasarkan id_barang
            var stokAwal = parseInt(document.getElementById('stok_awal' + id_barang).value) || 0;
            var tambahStok = parseInt(document.getElementById('tambah_stok' + id_barang).value) || 0;

            // Hitung total
            var totalStok = stokAwal + tambahStok;

            // Masukkan hasil ke input total stok
            document.getElementById('total_stok' + id_barang).value = totalStok;
        }
    </script>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.open-stok-modal').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var id_barang = this.getAttribute('data-id');
                    fetch('modal_tambah_stok.php?id=' + id_barang)
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById('modalStokContent').innerHTML = html;
                        });
                });
            });
        });
    </script>

<?php } ?>
</body>

</html>