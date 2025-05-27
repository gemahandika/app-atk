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
                                                                    <select class="form-control" name="role" type="text" id="role" required>
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

<?php
    include '../../footer.php';
}
?>