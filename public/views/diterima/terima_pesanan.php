<?php
session_name("dashboard_atk_session");
session_start();
include '../../header.php';
$date = date("Y-m-d");
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
                                    <!-- <td class="small text-left">
                                        <a href="#" class="btn btn-success btn-sm text-white" data-bs-toggle="modal" data-bs-target="#basicModal<?= $data1['id_keranjang'] ?>">Edit Jumlah</a>
                                    </td> -->
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="basicModal<?= $data1['id_keranjang'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="../../../app/controller/Request.php" method="post">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title ">Edit Jumlah Pesanan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="text" class="form-control" name="id" value="<?= $data1['id_keranjang'] ?>" readonly required>
                                                    <input type="text" class="form-control" name="invoice" value="<?= $data1['invoice'] ?>" readonly required>

                                                    <div class="row mb-3">
                                                        <label class="col-sm-3 col-form-label">Barang :</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" id="nama_barang_<?= $data1['id_keranjang'] ?>" name="nama_barang" value="<?= $data1['nama_barang'] ?>" readonly required>
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
                                                            <input type="number" class="form-control" id="harga_<?= $data1['id_keranjang'] ?>" name="harga" value="<?= $data1['harga'] ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputText" class="col-sm-3 col-form-label">Total Harga :</label>
                                                        <div class="col-sm-8">
                                                            <input type="number" class="form-control" name="total_harga" id="total_harga_<?= $data1['id_keranjang'] ?>" value="<?= $data1['total_harga'] ?>" readonly>
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
                                </div><!-- End Basic Modal-->
                            <?php } ?>
                        </table>
                        <form action="../../../app/controller/Terima.php" method="post">
                            <input type="hidden" name="tgl_terima" value="<?= $date ?>" readonly>
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



<?php
include '../../footer.php';
?>