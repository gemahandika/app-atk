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

// Inisialisasi total tagihan
$total_tagihan = 0;

// Ambil data dari tb_keranjang berdasarkan invoice
$sql = mysqli_query($koneksi, "SELECT * FROM tb_keranjang WHERE status IN ('DIPESAN', 'GENERATE') AND invoice = '$invoice' ORDER BY id_keranjang ASC") or die(mysqli_error($koneksi));
$result1 = array();
while ($data1 = mysqli_fetch_array($sql)) {
    $result1[] = $data1;
    // Tambahkan total harga dari setiap item ke total tagihan
    $total_tagihan += $data1['total_harga'];
}
?>

<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title" style="border-bottom: 1px solid black;">
                            Detail ATK <?= htmlspecialchars($data['nama_user'] ?? '-') ?> - Invoice : <?= htmlspecialchars($invoice ?? '-') ?>
                            <?php if (has_access($allowed_admin) && !empty($data['keterangan'])): ?>
                                - <?= htmlspecialchars($data['keterangan']) ?>
                            <?php endif; ?>
                        </h5>

                        <?php
                        // Ambil status pesanan dari tb_pesanan berdasarkan invoice
                        $invoice = $data['invoice'];
                        $query_status = mysqli_query($koneksi, "SELECT status FROM tb_pesanan WHERE invoice = '$invoice'");
                        $status_pesanan = mysqli_fetch_assoc($query_status)['status'];

                        // Cek apakah statusnya GENERATE
                        $disabled_generate = ($status_pesanan === 'GENERATE') ? 'disabled' : '';
                        ?>
                        <table id="example1" class="display nowrap" style="width:100%">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="small text-center">No</th>
                                    <th class="small text-center">Kode Barang</th>
                                    <th class="small text-center">Katagori</th>
                                    <th class="small text-center">Nama Barang</th>
                                    <th class="small text-center">Satuan</th>
                                    <th class="small text-center">Harga</th>
                                    <th class="small text-center">Jumlah</th>
                                    <th class="small text-center">Total</th>
                                    <th class="small text-center">Stok</th> <!-- Tambahkan kolom stok -->
                                    <th class="small text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 0;
                                foreach ($result1 as $data1) {
                                    $no++;
                                    // Ambil data dari tb_barang berdasarkan kode_barang
                                    $kode_barang = $data1['kode_barang'];
                                    $sql_barang = mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE kode_barang = '$kode_barang'") or die(mysqli_error($koneksi));
                                    $data_barang = mysqli_fetch_array($sql_barang);
                                    $stok_barang = $data_barang['stok_barang'];
                                ?>
                                    <tr>
                                        <td><?= $no; ?></td>
                                        <td class="small text-center"><?= $data1['kode_barang'] ?></td>
                                        <td class="small text-center"><?= $data1['katagori'] ?></td>
                                        <td class="small text-center"><?= $data1['nama_barang'] ?></td>
                                        <td class="small text-center"><?= $data1['satuan'] ?></td>
                                        <td class="small text-center"><?php echo number_format($data1['harga']) ?></td>
                                        <td class="small text-center"><?= $data1['jumlah'] ?></td>
                                        <td class="small text-center text-success"><strong><?php echo number_format($data1['total_harga']) ?></strong></td>
                                        <td class="small text-center"><?= $stok_barang ?></td> <!-- Tampilkan stok dari tb_barang -->
                                        <td class="small text-center">
                                            <a href="#" class="btn btn-success btn-sm text-white <?= $disabled_generate; ?>" data-bs-toggle="modal" data-bs-target="#basicModal<?= $data1['id_keranjang'] ?>">Edit Jumlah</a>
                                        </td>
                                    </tr>

                                    <!-- Modal for editing quantity -->
                                    <div class="modal fade" id="basicModal<?= $data1['id_keranjang'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="../../../app/controller/Request.php" method="post">
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title ">Edit Jumlah Pesanan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" class="form-control" name="id" value="<?= $data1['id_keranjang'] ?>" readonly required>
                                                        <input type="hidden" class="form-control" name="invoice" value="<?= $data1['invoice'] ?>" readonly required>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-3 col-form-label">Barang :</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="nama_barang_<?= $data1['id_keranjang'] ?>" name="nama_barang" value="<?= $data1['nama_barang'] ?>" style="background-color: #fffde7;" readonly required>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-sm-3 col-form-label">Jumlah :</label>
                                                            <div class="col-sm-8">
                                                                <input type="number" class="form-control" id="jumlah_<?= $data1['id_keranjang'] ?>" name="jumlah" value="<?= $data1['jumlah'] ?>" required oninput="hitungTotal(<?= $data1['id_keranjang'] ?>)">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="inputText" class="col-sm-3 col-form-label">Harga :</label>
                                                            <div class="col-sm-8">
                                                                <input type="number" class="form-control" id="harga_<?= $data1['id_keranjang'] ?>" name="harga" value="<?= $data1['harga'] ?>" style="background-color: #fffde7;" style="background-color: #fffde7;" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="inputText" class="col-sm-3 col-form-label">Total Harga :</label>
                                                            <div class="col-sm-8">
                                                                <input type="number" class="form-control" name="total_harga" id="total_harga_<?= $data1['id_keranjang'] ?>" value="<?= $data1['total_harga'] ?>" style="background-color:rgb(255, 239, 231);" readonly>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" name="edit" class="btn btn-success">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div><!-- End Modal -->
                                <?php } ?>
                            </tbody>
                        </table>

                        <form action="../../../app/controller/Proses.php" method="post">
                            <input type="hidden" class="form-control" id="datetime" name="date" readonly>
                            <input type="hidden" name="status" value="GENERATE" readonly>
                            <input type="hidden" name="invoice" value="<?= $data1['invoice'] ?>" readonly required>
                            <input type="hidden" name="total_tagihan" id="total_tagihan" value="<?= $total_tagihan ?>" readonly>
                            <input type="hidden" name="total_items" id="total_items" value="<?= $total_items ?>" readonly>

                            <div class="row mb-3 d-flex justify-content-end">
                                <div class="col-sm-4 mt-2 d-flex justify-content-end me-3">
                                    <!-- Tombol GENERATE akan dinonaktifkan jika status pesanan adalah 'GENERATE' -->
                                    <button type="submit" id="setTimeBtn" class="btn btn-success me-2" name="generate" <?= $disabled_generate; ?>>GENERATE</button>
                                </div>
                            </div>
                        </form>

                        <!-- Tombol PRINT menggunakan form POST -->
                        <form id="printForm" method="POST" action="export.php" target="_blank" style="display: none;">
                            <input type="hidden" name="invoice" id="invoiceInput" value="<?= $data['invoice']; ?>">
                        </form>
                        <button id="printBtn" class="btn btn-danger text-white">PRINT</button>

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
</script>

<script>
    document.getElementById("printBtn").addEventListener("click", function() {
        // Submit form tersembunyi dengan POST
        document.getElementById("printForm").submit();
    });
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
    document.getElementById('setTimeBtn').addEventListener('click', function() {
        const now = new Date();

        // Format: YYYY-MM-DD HH:mm:ss
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        const formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

        // Isi ke input
        document.getElementById('datetime').value = formattedDateTime;
    });
</script>


</body>

</html>