<?php
include '../../../app/config/koneksi.php';
if (!isset($_SESSION['admin_akses'])) {
    $eror = "Ooopss!! Erorr";
} else if (!in_array("super_admin", $_SESSION['admin_akses'])) {
    $eror = "Ooopss!! Erorr";
} else {
    $eror = ""; // Kosongkan variabel error jika user memiliki akses
}
$date = date("Y-m-d");
$time = date("H:i");
?>
<!-- Modal Create -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="../../../app/controller/User_app.php" method="post">
            <div class="modal-content">
                <div class="modal-header btn btn-info text-white">
                    <h5 class="modal-title fs-5" id="exampleModalLabel"><?php if ($eror) : ?>
                            <h5><?= $eror ?></h5>
                            <?php exit(); ?>
                        <?php endif; ?>
                        Tambah Data User App
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="report-it">
                        <div class="form-group mb-4">
                            <label for="nip"><strong>NIK</strong> <strong class="text-danger">*</strong></label><br>
                            <input type="text" class="form-control" name="nip" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group mb-4">
                            <label for="fullname"><strong>Fullname</strong> <strong class="text-danger">*</strong></label><br>
                            <input type="text" class="form-control" id="report2" name="fullname" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group mb-4">
                            <label class="control-label"><strong>BRANCH</strong> <strong class="text-danger">*</strong></label>
                            <select class="form-control" id="branch" name="branch" aria-label="Default select example" required>
                                <option value="">- Pilih Cabang -</option>
                                <?php
                                $no = 1;
                                $sql = mysqli_query($koneksi, "SELECT * FROM tb_cabang") or die(mysqli_error($koneksi));
                                $result = array();
                                while ($data = mysqli_fetch_array($sql)) {
                                    $result[] = $data;
                                }
                                foreach ($result as $data) {
                                ?>
                                    <option value="<?= $data['nama_cabang'] ?>"><?= $no++; ?>. <?= $data['nama_cabang'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label class="control-label"><strong>UNIT</strong> <strong class="text-danger">*</strong></label><br>
                            <select class="form-control" name="unit" type="text" id="unit" required>
                                <option value="">Pilih Unit</option>
                                <option value="OUTBOUND">OUTBOUND</option>
                                <option value="INBOUND">INBOUND</option>
                                <option value="GA">GA</option>
                                <option value="HC">HC</option>
                                <option value="CS">CS</option>
                                <option value="SALES">SALES</option>
                                <option value="HEAVY CARGO">HEAVY CARGO</option>
                                <option value="JTR">JTR</option>
                                <option value="CCC">CCC</option>
                                <option value="IT">IT</option>
                                <option value="FINANCE">FINANCE</option>
                                <option value="FULLFILMENT">FULLFILMENT</option>
                                <option value="CR3">CR3</option>
                                <option value="PICKUP">PICKUP</option>
                                <option value="KP ATC">KP ATC</option>
                                <option value="KP MEDAN BARAT">KP MEDAN BARAT</option>
                                <option value="KP MEDAN TIMUR">KP MEDAN TIMUR</option>
                                <option value="KP PELANGI">KP PELANGI</option>
                                <option value="KP JUANDA">KP JUANDA</option>
                                <option value="KP WAHID HASYM">KP WAHID HASYM</option>
                                <option value="KP TOMANG">KP TOMANG</option>
                                <option value="KP MARELAN">KP MARELAN</option>
                                <option value="KP THAMRIN">KP THAMRIN</option>
                                <option value="LAINNYA">LAINNYA</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="user_id"><strong>User ID</strong> <strong class="text-danger">*</strong></label><br>
                            <input type="text" class="form-control" id="report" name="user_id" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group mb-4">
                            <label for="password"><strong>Password</strong> <strong class="text-danger">*</strong></label><br>
                            <input type="text" class="form-control" id="report1" name="password" required style="text-transform: uppercase;">
                        </div>
                        <input type="hidden" id="status" name="status" value="NON AKTIF" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info text-white" name="add">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>