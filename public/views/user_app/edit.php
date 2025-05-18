<?php
session_name("dashboard_atk_session");
session_start();

if (!isset($_SESSION['admin_akses'])) {
    header("Location:../page_error/error.php");
    exit();
} elseif (!in_array("super_admin", $_SESSION['admin_akses'])) {
    header("Location:../page_error/error.php");
    exit();
} else {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
        $id = $_POST['id'];

        // Koneksi ke database
        include '../../../app/config/koneksi.php';

        // Query untuk mengambil data user termasuk cabang dan unit
        $query = "SELECT nama_user, username, cabang, unit, nik FROM user WHERE login_id = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($nama_user, $username, $cabang, $unit, $nik);
        $stmt->fetch();
        $stmt->close();

        // Ambil nama_cabang yang sesuai dengan id cabang user
        $queryCabangUser = "SELECT nama_cabang FROM tb_cabang WHERE id_cabang = ?";
        $stmtCabangUser = $koneksi->prepare($queryCabangUser);
        $stmtCabangUser->bind_param("i", $cabang);
        $stmtCabangUser->execute();
        $stmtCabangUser->bind_result($nama_cabang_user);
        $stmtCabangUser->fetch();
        $stmtCabangUser->close();

        // Query untuk mendapatkan semua data cabang
        $queryCabangAll = "SELECT id_cabang, nama_cabang FROM tb_cabang";
        $resultCabangAll = $koneksi->query($queryCabangAll);

        include '../../header.php';
?>
        <main id="main" class="main">
            <section class="section">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title" style="border-bottom: 1px solid black;">Edit Data User</h5>
                                <!-- Horizontal Form -->
                                <form action="../../../app/controller/User_app.php" method="post">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <div class="row mb-3">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Nik</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="nik" value="<?= $nik ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Fullname</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="fullname" value="<?= $nama_user ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="username" value="<?= $username ?>">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="cabang" class="col-sm-2 col-form-label">Cabang</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" name="cabang" id="cabang" required>
                                                <!-- Opsi default cabang yang terpilih dari data user -->
                                                <option value="<?= $cabang ?>"><?= htmlspecialchars($nama_cabang_user) ?></option>
                                                <?php
                                                // Loop untuk menampilkan semua cabang
                                                while ($row = $resultCabangAll->fetch_assoc()) {
                                                    // Jika id_cabang sesuai dengan cabang user, skip agar tidak duplikat
                                                    if ($row['id_cabang'] != $cabang) {
                                                        echo "<option value='" . $row['id_cabang'] . "'>" . htmlspecialchars($row['nama_cabang']) . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Unit </label>
                                        <div class="col-sm-10">
                                            <select class="form-select" name="unit" type="text" id="unit" required>
                                                <option value="<?= $unit ?>"><?= $unit ?></option>
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
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary" name="edit">Submit</button>
                                        <button type="reset" class="btn btn-secondary">Reset</button>
                                    </div>
                                </form><!-- End Horizontal Form -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main><!-- End #main -->

<?php
        include '../../footer.php';
    }
}
?>