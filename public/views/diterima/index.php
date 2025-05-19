<?php
session_name("dashboard_atk_session");
session_start();
include '../../header.php';
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
                        <h5 class="card-title" style="border-bottom: 1px solid black;">Terima Pesanan</h5>
                        <table id="example1" class="display nowrap" style="width:100%">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="th-small text-center">No</th>
                                    <th class="th-small text-center">NAMA</th>
                                    <th class="th-small text-center">INVOICE</th>
                                    <th class="th-small text-center">TOTAL ITEM</th>
                                    <th class="th-small text-center">TOTAL TAGIHAN</th>
                                    <th class="th-small text-center">USER PICKUP</th>
                                    <th class="th-small text-center">TANGGAL</th>
                                    <!-- <th class="small text-center">STATUS</th> -->
                                    <th class="th-small text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 0;

                                if (has_access($allowed_admin)) {
                                    $sql = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'DIPICKUP' ORDER BY id_pesanan DESC") or die(mysqli_error($koneksi));
                                }
                                if (has_access($allowed_agen)) {
                                    $sql = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'DIPICKUP' AND user_id = '$user1' ORDER BY id_pesanan DESC") or die(mysqli_error($koneksi));
                                }
                                $result = array();
                                while ($data = mysqli_fetch_array($sql)) {
                                    $result[] = $data;
                                }
                                foreach ($result as $data) {
                                    $no++;
                                ?>
                                    <tr>
                                        <td class="th-small text-center"><?= $no; ?></td>
                                        <td class="th-small text-center"><?= $data['nama_user'] ?></td>
                                        <td class="th-small text-center"><?= $data['invoice'] ?></td>
                                        <td class="th-small text-center"><?= $data['total_item'] ?></td>
                                        <td class="th-small text-center"><?= $data['total_tagihan'] ?></td>
                                        <td class="th-small text-center"><?= $data['nama_pickup'] ?></td>
                                        <td class="th-small text-center"><?= $data['date'] ?></td>
                                        <!-- <td class="small text-center"><?= $data['status'] ?></td> -->
                                        <td class="th-small text-center">
                                            <a href="terima_pesanan.php?invoice=<?= $data['invoice'] ?>&status=<?= $data['status'] ?>" class="btn btn-success btn-sm">
                                                <i class="bi bi-search"></i> List Pesanan
                                            </a>
                                        </td>
                                    </tr>
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
?>