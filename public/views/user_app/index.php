<?php
session_name("dashboard_atk_session");
session_start();
if (!isset($_SESSION['admin_akses'])) {
    header("Location:../page_error/error.php");
    exit();
} else if (!in_array("super_admin", $_SESSION['admin_akses'])) {
    header("Location:../page_error/error.php");

    exit();
} else {

    include '../../header.php';
    include 'modal.php';

?>

    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="border-bottom: 1px solid black;">Data User Aplikasi</h5>
                            <!-- Primary Color Bordered Table -->
                            <div class="row mb-3 d-flex align-items-center">
                                <div class="col-sm-4 mt-2">
                                    <button type="submit" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#basicModal">Tambah Data</button>
                                    <a href="aktivasi.php" type="button" class="btn btn-primary text-white">Aktivasi users</a>
                                </div>
                            </div>
                            <table id="example" class="table" style="width:100%">
                                <thead>
                                    <tr class="table-secondary">
                                        <th class="th-small text-center">NO</th>
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
                                    $sql = mysqli_query($koneksi, "SELECT * FROM user WHERE status != 'NON AKTIF' ORDER BY login_id ASC") or die(mysqli_error($koneksi));
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
                                            <td class="th-small text-center"><?= strtoupper($data['status']) ?></td>
                                            <td class="th-small text-center">
                                                <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#aksesModal<?= $data['login_id'] ?>">Nonaktif</a>
                                                <form action="edit.php" method="post" style="display:inline;">
                                                    <input type="hidden" name="id" value="<?= $data['login_id'] ?>">
                                                    <button type="submit" class="btn btn-warning btn-sm">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="aksesModal<?= $data['login_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form action="../../../app/controller/User_app.php" method="post">
                                                    <div class="modal-content">
                                                        <div class="modal-header btn btn-danger">
                                                            <h5 class="modal-title fs-5" id="exampleModalLabel">Nonaktif User</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="report-it">

                                                                <h6>Apakah Anda ingin menon-aktifkan user atas nama <b><?= $data['nama_user'] ?></b> ?</h6>

                                                                <input type="hidden" name="id" value="<?= $data['login_id'] ?>" readonly>
                                                                <input type="hidden" id="user_id" name="user_id" value="<?= $data['username'] ?>" readonly>
                                                                <!-- <input type="hidden" id="password" name="password" value="123" readonly> -->
                                                                <input class="dept-1" name="role" type="hidden" value="NON AKTIF" readonly>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                <input type="submit" name="nonaktif_user" class="btn btn-danger" value="Non Aktif">
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