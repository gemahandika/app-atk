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
                        <h5 class="card-title" style="border-bottom: 1px solid black;">Detail Invoice Pesanan</h5>
                        <!-- Primary Color Bordered Table -->

                        <form action="detail_invoice.php" method="get">
                            <div class="row mb-3 d-flex align-items-center">
                                <div class="col-sm-2 mt-2">
                                    <input type="date" class="form-control" name="dari" value="<?= isset($_GET['dari']) ? $_GET['dari'] : '' ?>" required>
                                </div>
                                <div class="col-sm-2 mt-2">
                                    <input type="date" class="form-control" name="ke" value="<?= isset($_GET['ke']) ? $_GET['ke'] : '' ?>" required>
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
                                    <a href="detail_invoice.php" type="button" class="btn btn-secondary">Refresh</a>
                                    <?php
                                    if (has_access($allowed_admin)) { ?>
                                        <a href="export.php?dari=<?= isset($_GET['dari']) ? $_GET['dari'] : '' ?>&ke=<?= isset($_GET['ke']) ? $_GET['ke'] : '' ?>&user_id=<?= isset($_GET['user_id']) ? $_GET['user_id'] : '' ?>" type="button" class="btn btn-primary">Download</a>
                                    <?php }
                                    if (has_access($allowed_agen)) { ?>
                                        <a href="export.php?dari=<?= isset($_GET['dari']) ? $_GET['dari'] : '' ?>&ke=<?= isset($_GET['ke']) ? $_GET['ke'] : '' ?>" type="button" class="btn btn-primary">Download</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>


                        <table id="example1" class="display nowrap" style="width:100%">
                            <thead>
                                <tr class="bg-info text-white">
                                    <th class="th-small text-center" style="font-size: 12px;">NO</th>
                                    <th class="th-small text-center" style="font-size: 12px;">NO INVOICE</th>
                                    <th class="th-small text-center" style="font-size: 12px;">TGL PESAN</th>
                                    <th class="th-small text-center" style="font-size: 12px;">USER ID</th>
                                    <th class="th-small text-center" style="font-size: 12px;">TOTAL ITEM</th>
                                    <th class="th-small text-center" style="font-size: 12px;">TOTAL TAGIHAN</th>
                                    <th class="th-small text-center" style="font-size: 12px;">STATUS</th>
                                    <th class="th-small text-center" style="font-size: 12px;">PEMBAYARAN</th>
                                    <?php if (has_access($allowed_super_admin)) { ?>
                                        <th class="small text-center" style="font-size: 12px;">ACTION</th>
                                    <?php } ?>
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
                                            "SELECT * FROM tb_pesanan WHERE user_id = '$user_id1' ORDER BY id_pesanan DESC" :
                                            "SELECT * FROM tb_pesanan ORDER BY id_pesanan DESC";
                                    } elseif (has_access($allowed_agen)) {
                                        $query = "SELECT * FROM tb_pesanan WHERE user_id = '$user1' ORDER BY id_pesanan DESC";
                                    }
                                } else {
                                    // Jika tanggal dipilih, ambil data berdasarkan rentang tanggal
                                    if (has_access($allowed_admin)) {
                                        $query = $user_id1 ?
                                            "SELECT * FROM tb_pesanan WHERE user_id = '$user_id1' AND date BETWEEN '$dari' AND '$ke' ORDER BY id_pesanan DESC" :
                                            "SELECT * FROM tb_pesanan WHERE date BETWEEN '$dari' AND '$ke' ORDER BY id_pesanan DESC";
                                    } elseif (has_access($allowed_agen)) {
                                        $query = "SELECT * FROM tb_pesanan WHERE user_id = '$user1' AND date BETWEEN '$dari' AND '$ke' ORDER BY id_pesanan DESC";
                                    }
                                }

                                // Eksekusi query
                                $sql_pesanan = mysqli_query($koneksi, $query);

                                // Cek apakah query berhasil dijalankan
                                if (!$sql_pesanan) {
                                    die("Error dalam query: " . mysqli_error($koneksi));
                                }

                                // Cek apakah ada data yang ditemukan
                                if (mysqli_num_rows($sql_pesanan) > 0) {
                                    // Perulangan untuk menampilkan data
                                    $no = 1;
                                    while ($data = mysqli_fetch_array($sql_pesanan)) {
                                        // Tampilkan data pesanan
                                ?>
                                        <tr>
                                            <td class="th-small text-center" style="font-size: 12px;"><?= $no++; ?></td>
                                            <td class="th-small text-center" style="font-size: 12px;"><?= $data['invoice']; ?></td>
                                            <td class="th-small text-center" style="font-size: 12px;"><?= $data['date']; ?></td>
                                            <td class="th-small text-center" style="font-size: 12px;"><?= $data['user_id']; ?></td>
                                            <td class="th-small text-center" style="font-size: 12px;"><?= $data['total_item']; ?></td>
                                            <!-- <td class="th-small text-center"><?= $data['total_tagihan']; ?></td> -->
                                            <td class="th-small text-center" style="font-size: 12px;"><?= number_format($data['total_tagihan'], 0, ',', '.'); ?></td>
                                            <td class="th-small text-center" style="font-size: 12px;"><?= $data['status']; ?></td>
                                            <td class="th-small text-center" style="font-size: 12px;">
                                                <?php
                                                // Cek apakah pengguna memiliki akses admin atau super_admin
                                                $is_admin = in_array("admin", $_SESSION['admin_akses']) || in_array("super_admin", $_SESSION['admin_akses']);
                                                ?>

                                                <?php if ($data['pembayaran'] == 'OTS') { ?>
                                                    <!-- Jika user adalah admin atau super_admin, tombol OTS bisa diklik -->
                                                    <?php if ($is_admin) { ?>
                                                        <a href="#" class="btn btn-danger btn-sm getInvoice" data-invoice="<?= $data['invoice']; ?>">OTS</a>
                                                    <?php } else { ?>
                                                        <!-- Jika user bukan admin atau super_admin, tombol OTS dinonaktifkan -->
                                                        <button class="btn btn-danger btn-sm" disabled>OTS</button>
                                                    <?php } ?>
                                                <?php } elseif ($data['pembayaran'] == 'DONE') { ?>
                                                    <button class="btn btn-success btn-sm custom-btn">success</button>
                                                <?php } ?>

                                            </td>
                                            <?php if (has_access($allowed_super_admin)) { ?>
                                                <td class="th-small text-left">
                                                    <a href="delete.php?invoice=<?= $data['invoice'] ?>" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
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

<script>
    document.querySelectorAll('.getInvoice').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            // Ambil nomor invoice
            const invoice = this.getAttribute('data-invoice');

            // Buat form secara dinamis
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'approve_pembayaran.php';

            // Buat input hidden untuk invoice
            const inputInvoice = document.createElement('input');
            inputInvoice.type = 'hidden';
            inputInvoice.name = 'invoice';
            inputInvoice.value = invoice;
            form.appendChild(inputInvoice);

            // Tambahkan form ke body dan submit
            document.body.appendChild(form);
            form.submit();
        });
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
</body>

</html>