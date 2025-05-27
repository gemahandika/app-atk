<?php
session_name("dashboard_atk_session");
session_start();

if (!isset($_SESSION['admin_akses'])) {
    header("location:../page_error/error.php");
    exit();
} else if (!in_array("super_admin", $_SESSION['admin_akses'])) {
    header("location:../page_error/error.php");
    exit();
} else {
    include '../../header.php';
    $eror = ""; // Kosongkan variabel error jika user memiliki akses
    include 'modal.php';
?>

    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="border-bottom: 1px solid black;">Data User Aplikasi</h5>
                            <table id="example1" class="display nowrap" style="width:100%">
                                <thead>
                                    <tr class="bg-secondary text-white">
                                        <th class="th-small text-center">No</th>
                                        <th class="th-small text-center">NIP</th>
                                        <th class="th-small text-center">NAMA USER</th>
                                        <th class="th-small text-center">USERNAME</th>
                                        <th class="th-small text-center">STATUS</th>
                                        <th class="th-small text-center">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 0;
                                    $sql = mysqli_query($koneksi, "SELECT * FROM user WHERE status = 'NON AKTIF' ORDER BY login_id ASC") or die(mysqli_error($koneksi));
                                    $result = array();
                                    while ($data = mysqli_fetch_array($sql)) {
                                        $result[] = $data;
                                    }
                                    foreach ($result as $data) {
                                        $no++;
                                    ?>
                                        <tr>
                                            <td class="th-small text-center"><?= $no; ?></td>
                                            <td class="th-small text-center"><?= $data['nik'] ?></td>
                                            <td class="th-small text-center"><?= $data['nama_user'] ?></td>
                                            <td class="th-small text-center"><?= $data['username'] ?></td>
                                            <td class="th-small text-center"><?= $data['status'] ?></td>
                                            <td class="th-small text-center">
                                                <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $data['login_id'] ?>">AKTIFKAN USER</a>
                                                <a href="delete.php?id=<?= $data['login_id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                                            </td>
                                        </tr>
                                        <!-- Modal Aktivasi User -->
                                        <div class="modal fade" id="editModal<?= $data['login_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form action="../../../app/controller/User_app.php" method="post">
                                                    <div class="modal-content">
                                                        <div class="modal-header btn btn-success text-white">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">AKTIFKAN USER</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="report-it">
                                                                <input type="hidden" name="id" value="<?= $data['login_id'] ?>">
                                                                <input type="hidden" name="password" value="<?= $data['password'] ?>">
                                                                <h6>Apakah Anda ingin mengaktifkan user atas nama <strong><?= $data['nama_user'] ?></strong> ?</h6>
                                                                <div class="form-group">
                                                                    <label class="control-label"><strong>- ROLE</strong> <strong class="text-danger">*</strong></label>
                                                                    <select class="form-control" name="role" type="text" id="role<?= $data['login_id'] ?>" required>
                                                                        <option value="admin">ADMIN</option>
                                                                        <option value="user">USER</option>
                                                                        <option value="pickup">PICKUP</option>
                                                                        <option value="bag">BAGGING</option>
                                                                    </select>
                                                                </div><br>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Tutup</button>
                                                                <input type="submit" name="aktif_user" class="btn btn-secondary" value="Aktifkan">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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
<?php

}
?>

</body>

</html>