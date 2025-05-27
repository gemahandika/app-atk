<?php
session_name("dashboard_atk_session");
session_start();
include '../../header.php';
$date = date("Y-m-d");
$time = date("H:i");
// include 'modal.php';
?>

<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title" style="border-bottom: 1px solid black;">List Data - Proses ATK <?php if (has_access($allowed_admin)) { ?> Masuk <?php } ?></h5>
                        <table id="example1" class="display nowrap" style="width:100%">
                            <thead>
                                <tr class="bg-success text-white">
                                    <th class="th-small text-center">NO</th>
                                    <th class="th-small text-center">NAMA</th>
                                    <th class="th-small text-center">INVOICE</th>
                                    <th class="th-small text-center">TOTAL ITEM</th>
                                    <th class="th-small text-center">TOTAL TAGIHAN</th>
                                    <th class="th-small text-center">USER ID</th>
                                    <th class="th-small text-center">TANGGAL</th>
                                    <th class="th-small text-center">STATUS</th>
                                    <?php if (has_access($allowed_admin)) { ?>
                                        <th class="th-small text-center">ACTION</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 0;
                                if (has_access($allowed_admin)) {
                                    $sql = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'GENERATE' ORDER BY id_pesanan ASC") or die(mysqli_error($koneksi));
                                }
                                if (has_access($allowed_agen)) {
                                    $sql = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'GENERATE' AND user_id = '$user1' ORDER BY id_pesanan ASC") or die(mysqli_error($koneksi));
                                }
                                $result = array();
                                while ($data = mysqli_fetch_array($sql)) {
                                    $result[] = $data;
                                }
                                foreach ($result as $data) {
                                    $no++;
                                    $printFormId = "printForm" . $no;
                                    $printBtnId = "printBtn" . $no;
                                ?>
                                    <tr>
                                        <td class="th-small text-center"><?= $no; ?></td>
                                        <td class="th-small text-center"><?= $data['nama_user'] ?></td>
                                        <td class="th-small text-center"><?= $data['invoice'] ?></td>
                                        <td class="th-small text-center"><?= $data['total_item'] ?></td>
                                        <td class="th-small text-center"><?= $data['total_tagihan'] ?></td>
                                        <td class="th-small text-center"><?= $data['user_id'] ?></td>
                                        <td class="th-small text-center"><?= $data['date'] ?></td>
                                        <td class="th-small text-center"><?= $data['status'] ?></td>
                                        <?php if (has_access($allowed_admin)) { ?>
                                            <td class="th-small text-center">
                                                <!-- Form tersembunyi untuk mengirim data POST -->
                                                <form id="<?= $printFormId ?>" method="POST" action="export.php" target="_blank" style="display: none;">
                                                    <input type="hidden" name="invoice" value="<?= $data['invoice']; ?>">
                                                </form>
                                                <!-- Tombol PRINT -->
                                                <button id="<?= $printBtnId ?>" class="btn btn-danger text-white btn-sm">PRINT</button>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                    <!-- Script untuk setiap tombol PRINT -->
                                    <script>
                                        document.getElementById("<?= $printBtnId ?>").addEventListener("click", function() {
                                            // Submit form tersembunyi dengan POST
                                            document.getElementById("<?= $printFormId ?>").submit();
                                        });
                                    </script>
                                <?php } ?>
                            </tbody>
                        </table>
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