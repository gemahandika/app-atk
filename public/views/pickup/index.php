<?php
session_name("dashboard_atk_session");
session_start();
if (!in_array("super_admin", $_SESSION['admin_akses']) && !in_array("pickup", $_SESSION['admin_akses'])) {
    header("Location:../page_error/error.php");
    exit();
} else {
    include '../../header.php';
?>

    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="border-bottom: 1px solid black;">Pickup Pesanan</h5>
                            <form id="invoiceForm" action="../../../app/controller/Pickup.php" method="POST" class="mb-4">
                                <div class="col-md-12 mb-3">
                                    <label for="no_invoice" class="form-label">Masukan No Invoice (scan barcode)</label>
                                    <input type="text" name="no_invoice" id="no_invoice" class="form-control" autofocus autocomplete="off" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="no_invoice" class="form-label">Nama Pemesan</label>
                                    <input type="text" class="form-control" name="nama_user" id="nama_user" readonly required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="no_invoice" class="form-label">Total Item</label>
                                    <input type="text" class="form-control" name="total_items" id="total_items" readonly required>
                                </div>

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
<?php
}
?>

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

<?php
include '../../../app/script/pickup.php';
?>

</body>

</html>