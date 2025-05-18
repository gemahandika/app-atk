<?php
session_name("dashboard_atk_session");
session_start();
if (!isset($_SESSION['admin_akses'])) {
    header("Location:../page_error/error.php");
    exit();
} else if (!in_array("super_admin", $_SESSION['admin_akses']) && !in_array("admin", $_SESSION['admin_akses']) && !in_array("pickup", $_SESSION['admin_akses'])) {
    header("Location:../page_error/error.php");
    exit();
} else {
    include '../../header.php';
    include '../../../app/models/request_models.php';
    // session_start(); // Mulai sesi
    $tgl_pickup = date("Y-m-d");
    $time = date("H:i");
?>

    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="border-bottom: 1px solid black;">Pickup Pesanan</h5>
                            <!-- General Form Elements -->
                            <form action="../../../app/controller/Pickup.php" method="post">
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Agen / KP :</label>
                                    <div class="col-sm-8">
                                        <select class="form-select" id="namaAgen" name="nama_barang" aria-label="Default select example" required>
                                            <option value="">- Pilih Agen -</option>
                                            <?php
                                            $sql = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'GENERATE' ORDER BY nama_user ASC") or die(mysqli_error($koneksi));
                                            $result = array();
                                            while ($data = mysqli_fetch_array($sql)) {
                                            ?>
                                                <option value="<?= $data['invoice'] ?>" data-total="<?= $data['total_item'] ?>" data-invoice="<?= $data['invoice'] ?>">
                                                    <?= $data['nama_user'] ?> - <?= $data['invoice'] ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="total_item" class="col-sm-3 col-form-label">Total Items :</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="total_item" id="total_item" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="invoice" class="col-sm-3 col-form-label">No Pesanan :</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="invoice" id="invoice" readonly>
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" name="tgl_pickup" value="<?= $tgl_pickup ?>" readonly>
                                <input type="hidden" class="form-control" name="status" value="DIPICKUP" readonly>
                                <input type="hidden" class="form-control" name="nama_pickup" value="<?= $user1 ?>" readonly>
                                <div class="row mb-3">
                                    <div class="col-sm-10">
                                        <button type="submit" name="pickup" class="btn btn-success">Pickup Sekarang</button>
                                    </div>
                                </div>
                            </form><!-- End General Form Elements -->

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main><!-- End #main -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Menggunakan jQuery untuk mendeteksi perubahan pada dropdown Agen
        $(document).ready(function() {
            $('#namaAgen').change(function() {
                // Ambil nilai dari opsi yang dipilih
                var selectedOption = $(this).find('option:selected');

                // Ambil data total items dan invoice dari atribut data
                var totalItems = selectedOption.data('total');
                var invoice = selectedOption.data('invoice');

                // Isi input total_item dan invoice dengan data yang sesuai
                $('#total_item').val(totalItems);
                $('#invoice').val(invoice);
            });
        });
    </script>
<?php
    include '../../footer.php';
}
?>