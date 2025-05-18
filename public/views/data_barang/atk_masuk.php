<?php
session_name("dashboard_atk_session");
session_start();
if (!isset($_SESSION['admin_akses'])) {
    header("Location:../page_error/error.php");
    exit();
} else if (!in_array("super_admin", $_SESSION['admin_akses']) && !in_array("admin", $_SESSION['admin_akses'])) {
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
                            <h5 class="card-title" style="border-bottom: 1px solid black;">Data ATK Masuk</h5>
                            <!-- Primary Color Bordered Table -->
                            <!-- <div class="row mb-3 d-flex align-items-center">
                            <div class="col-sm-4 mt-2">
                                <button type="submit" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#basicModal">Tambah Data</button>
                                <a href="index.php" type="button" class="btn btn-primary">Download</a>
                            </div>
                        </div> -->
                            <table id="example1" class="table datatable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="font-size: 12px;">No</th>
                                        <th class="text-center" style="font-size: 12px;">TGL REQUEST</th>
                                        <th class="text-center" style="font-size: 12px;">KODE BARANG</th>
                                        <th class="text-center" style="font-size: 12px;">NAMA BARANG</th>
                                        <th class="text-center" style="font-size: 12px;">SATUAN</th>
                                        <th class="text-center" style="font-size: 12px;">JLH PERMINTAAN</th>
                                        <th class="text-center" style="font-size: 12px;">JLH DITERIMA</th>
                                        <th class="text-center" style="font-size: 12px;">TGL TERIMA</th>
                                        <th class="text-center" style="font-size: 12px;">AWB</th>
                                        <th class="text-center" style="font-size: 12px;">KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 0;
                                    $sql = mysqli_query($koneksi, "SELECT * FROM atk_masuk ORDER BY id_atkmasuk DESC") or die(mysqli_error($koneksi));
                                    $result = array();
                                    while ($data = mysqli_fetch_array($sql)) {
                                        $result[] = $data;
                                    }
                                    foreach ($result as $data) {
                                        $no++;
                                    ?>
                                        <tr>
                                            <td class="text-center" style="font-size: 12px;"><?= $no; ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['tgl_request'] ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['kode_barang'] ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['nama_barang'] ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['satuan'] ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['jumlah_permintaan'] ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['jumlah_terima'] ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['tgl_terima'] ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['awb'] ?></td>
                                            <td class="text-center" style="font-size: 12px;"><?= $data['keterangan'] ?></td>
                                            <!-- <td class="text-center" style="font-size: 12px;">
                                            <div class="d-flex justify-content-center">
                                                <a href="#" class="btn btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#basicModal<?= $data['id_barang'] ?>" style="margin-right: 5px;">Stok</a>
                                                <a href="edit.php?id=<?= $data['id_barang'] ?>" class="btn btn-warning btn-sm ">Edit</a>
                                            </div>
                                        </td> -->
                                        </tr>

                                        <!-- Modal tambah stok -->
                                        <div class="modal fade" id="basicModal<?= $data['id_barang'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="../../../app/controller/Data_barang.php" method="post">
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title">Tambah Stok Barang</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Ganti name="id" menjadi name="id_barang" untuk menghindari konflik -->
                                                            <input type="hidden" class="form-control" name="id_barang" value="<?= $data['id_barang'] ?>" readonly required>
                                                            <div class="row mb-3">
                                                                <label class="col-sm-3 col-form-label">Stok Awal:</label>
                                                                <div class="col-sm-8">
                                                                    <!-- Gunakan id yang unik untuk setiap modal -->
                                                                    <input type="number" class="form-control" id="stok_awal<?= $data['id_barang'] ?>" name="stok_awal" value="<?= $data['stok_barang'] ?>" readonly required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label class="col-sm-3 col-form-label">Kode:</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" id="kode_barang<?= $data['id_barang'] ?>" name="kode_barang" value="<?= $data['kode_barang'] ?>" readonly required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label class="col-sm-3 col-form-label">Nama :</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" id="nama_barang<?= $data['id_barang'] ?>" name="nama_barang" value="<?= $data['nama_barang'] ?>" readonly required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label class="col-sm-3 col-form-label">Tambah :</label>
                                                                <div class="col-sm-8">
                                                                    <input type="number" class="form-control" id="tambah_stok<?= $data['id_barang'] ?>" name="tambah_stok" placeholder="0" required oninput="hitungTotal(<?= $data['id_barang'] ?>)">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label class="col-sm-3 col-form-label">Total :</label>
                                                                <div class="col-sm-8">
                                                                    <input type="number" class="form-control" id="total_stok<?= $data['id_barang'] ?>" name="total_stok" placeholder="0" readonly required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="add_stok" class="btn btn-success">Tambah Stok</button>
                                                        </div>
                                                    </form>
                                                </div>
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
    <script>
        function hitungTotal(id_barang) {
            // Ambil nilai stok awal dan nilai tambah berdasarkan id_barang
            var stokAwal = parseInt(document.getElementById('stok_awal' + id_barang).value) || 0;
            var tambahStok = parseInt(document.getElementById('tambah_stok' + id_barang).value) || 0;

            // Hitung total
            var totalStok = stokAwal + tambahStok;

            // Masukkan hasil ke input total stok
            document.getElementById('total_stok' + id_barang).value = totalStok;
        }
    </script>

<?php
    include '../../footer.php';
}
?>