<?php
session_name("dashboard_atk_session");
session_start();
if (!isset($_SESSION['admin_akses'])) {
    header("location:../page_error/error.php");
    exit();
} else if (!in_array("super_admin", $_SESSION['admin_akses']) && !in_array("admin", $_SESSION['admin_akses'])) {
    header("location:../page_error/error.php");
    exit();
} else {

    include '../../header.php';
    include '../../../app/models/request_models.php';
    // include 'modal.php';
    // session_start(); // Mulai sesi
    $date = date("Y-m-d");
    $time = date("H:i");
?>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $invoice = isset($_POST['invoice']) ? $_POST['invoice'] : null;

        // Lakukan proses approve pembayaran dengan $invoice
        if ($invoice) {
            echo "Invoice: $invoice berhasil diproses.";
        } else {
            echo "Nomor invoice tidak ditemukan.";
        }
    } else {
        echo "Invalid request.";
    }
    ?>

    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="border-bottom: 1px solid black;">Form Pesanan</h5>
                            <!-- General Form Elements -->
                            <form action="../../../app/controller/Report.php" method="post">

                                <?php
                                $no = 0;
                                $sql = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE invoice = '$invoice' ORDER BY id_pesanan ASC") or die(mysqli_error($koneksi));
                                $result = array();
                                while ($data = mysqli_fetch_array($sql)) {
                                    $result[] = $data;
                                }
                                foreach ($result as $data) {
                                ?>
                                    <input type="hidden" name="pembayaran" value="DONE" readonly>
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label fw-bold">Invoice :</strong></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="invoice" value="<?= $invoice ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-3 col-form-label fw-bold">User ID : </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="user_id" id="user_id" value="<?= $data['user_id'] ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-3 col-form-label fw-bold">Nama User : </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="nama_user" id="nama_user" value="<?= $data['nama_user'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-3 col-form-label fw-bold">Tanggal Pesan :</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="tgl_proses" id="tgl_proses" value="<?= $data['tgl_proses'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class=" row mb-3">
                                        <label for="inputText" class="col-sm-3 col-form-label fw-bold">Tanggal Terima :</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="tgl_terima" id="tgl_terima" value="<?= $data['tgl_terima'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-3 col-form-label fw-bold">Total Tagihan :</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control fw-bold" name="total_tagihan" id="total_tagihan" value="<?= number_format($data['total_tagihan']) ?>" readonly style="background-color: #fffde7;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <hr style="border-top: 2px solid #000; width: 100%;">
                                        </div>
                                    </div>
                                    <div class=" row mb-3">
                                        <div class="col-sm-10 text-end">
                                            <button type="submit" name="proses_pembayaran" class="btn btn-success">Proses Pembayaran</button>
                                        </div>
                                    </div>
                                <?php } ?>
                            </form><!-- End General Form Elements -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main><!-- End #main -->


<?php
    include '../../footer.php';
}
?>