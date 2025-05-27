<?php
session_name("dashboard_atk_session");
session_start();

include '../../header.php';
if (!in_array("super_admin", $_SESSION['admin_akses']) && !in_array("admin", $_SESSION['admin_akses']) && !in_array("bag", $_SESSION['admin_akses'])) {
    header("Location:../page_error/error.php");
    exit();
} else {
    $date = date("Y-m-d");
    $time = date("H:i");
    // include 'modal.php';
?>

    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="border-bottom: 1px solid black;">List Data - Generate ATK </h5>
                            <div class="row mb-3 d-flex align-items-center">
                                <div class="col-sm-4 mt-2">
                                    <?php
                                    if (in_array("super_admin", $_SESSION['admin_akses']) || in_array("bag", $_SESSION['admin_akses'])) { ?>
                                        <a href="ho_bag.php" type="submit" class="btn btn-primary">Proses Bagging</a>
                                    <?php } ?>
                                    <a href="list_data_bag.php" type="submit" class="btn btn-secondary">List Data</a>
                                </div>
                            </div>
                            <table id="example1" class="display nowrap" style="width:100%">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th class="th-small text-center">NO</th>
                                        <th class="th-small text-center">NAMA</th>
                                        <th class="th-small text-center">INVOICE</th>
                                        <th class="th-small text-center">TOTAL ITEM</th>
                                        <th class="th-small text-center">TOTAL TAGIHAN</th>
                                        <th class="th-small text-center">USER ID</th>
                                        <th class="th-small text-center">TANGGAL</th>
                                        <th class="th-small text-center">STATUS</th>
                                        <th class="th-small text-center">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 0;
                                    $sql = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'GENERATE' ORDER BY id_pesanan ASC") or die(mysqli_error($koneksi));
                                    $result = array();
                                    while ($data = mysqli_fetch_array($sql)) {
                                        $result[] = $data;
                                    }
                                    foreach ($result as $data) {
                                        $no++;
                                        $printFormId = "printForm" . $no;
                                        $printBtnId = "printBtn" . $no;
                                    ?>
                                        <tr>
                                            <td class="th-small text-center"><?= $no; ?></td>
                                            <td class="th-small text-center"><?= $data['nama_user'] ?></td>
                                            <td class="th-small text-center"><?= $data['invoice'] ?></td>
                                            <td class="th-small text-center"><?= $data['total_item'] ?></td>
                                            <td class="th-small text-center"><?= $data['total_tagihan'] ?></td>
                                            <td class="th-small text-center"><?= $data['user_id'] ?></td>
                                            <td class="th-small text-center"><?= $data['date'] ?></td>
                                            <td class="th-small text-center"><?= $data['status'] ?></td>
                                            <td class="th-small text-center">
                                                <!-- Form tersembunyi untuk mengirim data POST -->
                                                <form id="<?= $printFormId ?>" method="POST" action="print.php" target="_blank" style="display: none;">
                                                    <input type="hidden" name="invoice" value="<?= $data['invoice']; ?>">
                                                </form>
                                                <!-- Tombol PRINT -->
                                                <button id="<?= $printBtnId ?>" class="btn btn-danger text-white btn-sm">PRINT</button>
                                            </td>
                                        </tr>

                                        <!-- Script untuk setiap tombol PRINT -->
                                        <script>
                                            document.getElementById("<?= $printBtnId ?>").addEventListener("click", function() {
                                                // Submit form tersembunyi dengan POST
                                                document.getElementById("<?= $printFormId ?>").submit();
                                            });
                                        </script>

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