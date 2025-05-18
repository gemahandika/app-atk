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
                        <h5 class="card-title" style="border-bottom: 1px solid black;">List Data - Pesanan</h5>
                        <table id="example" class="table" style="width:100%">
                            <thead>
                                <tr class="table-info">
                                    <th class="th-small text-center">No</th>
                                    <th class="th-small text-center">NAMA</th>
                                    <th class="th-small text-center">INVOICE</th>
                                    <th class="th-small text-center">TOTAL ITEM</th>
                                    <th class="th-small text-center">TOTAL TAGIHAN</th>
                                    <th class="th-small text-center">USER ID</th>
                                    <th class="th-small text-center">TANGGAL</th>
                                    <th class="th-small text-center">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 0;
                                if (has_access($allowed_admin)) {
                                    $sql = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'DIKIRIM' ORDER BY id_pesanan ASC") or die(mysqli_error($koneksi));
                                }
                                if (has_access($allowed_agen)) {
                                    $sql = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'DIKIRIM' AND user_id = '$user1' ORDER BY id_pesanan ASC") or die(mysqli_error($koneksi));
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
                                        <td class="th-small text-center"><?= $data['user_id'] ?></td>
                                        <td class="th-small text-center"><?= $data['date'] ?></td>
                                        <td class="th-small text-center"><?= $data['status'] ?></td>
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