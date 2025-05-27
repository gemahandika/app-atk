<?php
session_name("dashboard_atk_session");
session_start();
include '../../header.php';
$date_for_db = date("Y-m-d H:i:s");
$time = date("H:i");
// include 'cek_status.php';
$invoice = isset($_GET['invoice']) ? $_GET['invoice'] : '';
// include 'modal.php';

$sql_total_items = mysqli_query($koneksi, "SELECT SUM(jumlah) AS total_items FROM tb_keranjang WHERE invoice = '$invoice'") or die(mysqli_error($koneksi));
$data_total_items = mysqli_fetch_array($sql_total_items);
$total_items = $data_total_items['total_items']; // Hasil penjumlahan kolom jumlah

$invoice = $_GET['invoice'];
$status = $_GET['status'];
$sql = mysqli_query($koneksi, "SELECT * FROM tb_keranjang WHERE invoice = '$invoice'") or die(mysqli_error($koneksi));
$data = mysqli_fetch_array($sql);
$data2 = $data["invoice"];

?>

<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title" style="border-bottom: 1px solid black;">Detail Pesanan <?= $data['nama_user'] ?> - Invoice : <?= $invoice ?> </h5>
                        <div class="row mb-3 d-flex align-items-center">
                        </div>
                        <table id="example1" class="display nowrap" style="width:100%">
                            <thead>
                                <tr class="bg-success text-white">
                                    <th class="th-small text-center">NO</th>
                                    <th class="th-small text-center">KATAGORI</th>
                                    <th class="th-small text-center">NAMA BARANG</th>
                                    <th class="th-small text-center">SATUAN</th>
                                    <th class="th-small text-center">HARGA</th>
                                    <th class="th-small text-center">JUMLAH</th>
                                    <th class="th-small text-center">TOTAL</th>
                                    <!-- <th class="small text-center">Action</th> -->
                                </tr>
                            </thead>
                            <?php
                            $no = 0;
                            $sql = mysqli_query($koneksi, "SELECT * FROM tb_keranjang WHERE status= 'DIPICKUP' AND invoice = '$invoice' ORDER BY id_keranjang ASC") or die(mysqli_error($koneksi));
                            $result = array();
                            while ($data1 = mysqli_fetch_array($sql)) {
                                $result[] = $data1;
                            }
                            foreach ($result as $data1) {
                                $no++;
                            ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td class="th-small text-center"><?= $data1['katagori'] ?></td>
                                    <td class="th-small text-center"><?= $data1['nama_barang'] ?></td>
                                    <td class="th-small text-center"><?= $data1['satuan'] ?></td>
                                    <td class="th-small text-center"><?php echo number_format($data1['harga']) ?></td>
                                    <td class="th-small text-center"><?= $data1['jumlah'] ?></td>
                                    <td class="th-small text-center text-success"><strong><?php echo number_format($data1['total_harga']) ?></strong></td>
                                </tr>
                            <?php } ?>
                        </table>
                        <form action="../../../app/controller/Terima.php" method="post">
                            <input type="hidden" name="tgl_terima" value="<?= $date_for_db ?>" readonly>
                            <input type="hidden" name="status" value="DITERIMA" readonly>
                            <input type="hidden" name="invoice" value="<?= $data1['invoice'] ?>" readonly required>
                            <input type="hidden" name="total_tagihan" id="total_tagihan" value="<?= $total_tagihan ?>" readonly>
                            <input type="hidden" name="total_items" id="total_items" value="<?= $total_items ?>" readonly>
                            <div class="row mb-3 d-flex justify-content-end">
                                <div class="col-sm-4 mt-2 d-flex justify-content-end me-3">
                                    <button type="submit" class="btn btn-success me-2" name="terima">TERIMA</button>
                                    <!-- <a id="printBtn" href="export.php?invoice=<?= $data['invoice'] ?>" target="_blank" type="submit" class="btn btn-danger text-white" name="generate">PRINT</a> -->
                                </div>
                            </div>
                        </form>
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

<!-- barang terima -->
<!-- <script>
    function hitungTotal(id_keranjang) {
        var jumlah = document.getElementById('jumlah_' + id_keranjang).value;
        var harga = document.getElementById('harga_' + id_keranjang).value;
        var total_harga = jumlah * harga;

        // Tampilkan hasil di input total_harga
        document.getElementById('total_harga_' + id_keranjang).value = total_harga;

        // Update total tagihan setelah perubahan
        updateTotalTagihan();
    }

    function updateTotalTagihan() {
        var totalTagihan = 0;

        <?php foreach ($result1 as $data1) { ?>
            var totalHarga = document.getElementById('total_harga_<?= $data1['id_keranjang'] ?>').value;
            totalTagihan += parseFloat(totalHarga);
        <?php } ?>

        // Tampilkan total tagihan di input total_tagihan
        document.getElementById('total_tagihan').value = totalTagihan;
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

</body>

</html>