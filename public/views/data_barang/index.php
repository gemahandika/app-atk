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
    $date = date("Y-m-d");
    $time = date("H:i");
?>

    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="border-bottom: 1px solid black;">Data Barang ATK</h5>
                            <!-- Primary Color Bordered Table -->
                            <div class="row mb-3 d-flex align-items-center">
                                <div class="col-sm-4 mt-2">
                                    <button type="submit" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#basicModal">Tambah Data</button>
                                    <a href="export.php" type="button" class="btn btn-primary">Download</a>
                                    <a href="bulk_insert_barang.php" type="button" class="btn btn-primary">Upload Data</a>
                                </div>

                                <table id="example1" class="display nowrap" style="width:100%">
                                    <thead>
                                        <tr class="bg-primary text-white">
                                            <th class="th-small text-center">NO</th>
                                            <th class="th-small text-center">KODE_BARANG</th>
                                            <th class="th-small text-center">NAMA BARANG</th>
                                            <th class="th-small text-center">SATUAN</th>
                                            <th class="th-small text-center">HARGA</th>
                                            <!-- <th class="th-small text-center">STATUS</th> -->
                                            <th class="th-small text-center">STOK</th>
                                            <th class="th-small text-center">MIN. ORDER</th>
                                            <th class="th-small text-center">BUFFER</th>
                                            <th class="th-small text-center">KETERANGAN</th>
                                            <th class="th-small text-center">ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $sql = mysqli_query($koneksi, "SELECT * FROM tb_barang ORDER BY id_barang ASC") or die(mysqli_error($koneksi));
                                        $result = array();
                                        while ($data = mysqli_fetch_array($sql)) {
                                            $result[] = $data;
                                        }
                                        foreach ($result as $data) {
                                            $no++;
                                            $stok_barang = $data['stok_barang'];
                                            $buffer = $data['buffer'];

                                            // Tentukan keterangan berdasarkan stok dan buffer
                                            if ($stok_barang <= $buffer) {
                                                $keterangan = '<h6 class=" text-danger">warning</h6>';
                                            } else {
                                                $keterangan = ''; // Kosongkan jika stok masih aman
                                            }
                                        ?>
                                            <tr>
                                                <td class="th-small text-center"><?= $no; ?></td>
                                                <td class="th-small text-center"><?= $data['kode_barang'] ?></td>
                                                <td class="th-small text-center"><?= $data['nama_barang'] ?></td>
                                                <td class="th-small text-center"><?= $data['satuan'] ?></td>
                                                <td class="th-small text-center"><?= number_format($data['harga'], 0, ',', '.') ?></td>
                                                <!-- <td class="th-small text-center"><?= $data['status_barang'] ?></td> -->
                                                <td class="th-small text-center"><?= $stok_barang ?></td>
                                                <td class="th-small text-center"><?= $data['min_order'] ?></td>
                                                <td class="th-small text-center"><?= $buffer ?></td>
                                                <td class="th-small text-center"><?= $keterangan ?></td>
                                                <td class="th-small text-center">
                                                    <div class="d-flex justify-content-center">
                                                        <a href="#" class="btn btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#basicModal<?= $data['id_barang'] ?>" style="margin-right: 5px;">Stok</a>
                                                        <a href="edit.php?id=<?= $data['id_barang'] ?>" class="btn btn-warning btn-sm text-white">Edit</a>
                                                    </div>
                                                </td>
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
                                                                    <label class="col-sm-3 col-form-label">Tgl Request:</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="date" class="form-control" id="tgl_request<?= $data['id_barang'] ?>" name="tgl_request" value="<?= $date ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label class="col-sm-3 col-form-label">Stok Awal:</label>
                                                                    <div class="col-sm-8">
                                                                        <!-- Gunakan id yang unik untuk setiap modal -->
                                                                        <input type="number" class="form-control" id="stok_awal<?= $data['id_barang'] ?>" name="stok_awal" value="<?= $data['stok_barang'] ?>" readonly required style="background-color: #fffde7;">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label class="col-sm-3 col-form-label">Kode:</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" id="kode_barang<?= $data['id_barang'] ?>" name="kode_barang" value="<?= $data['kode_barang'] ?>" readonly required style="background-color: #fffde7;">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label class="col-sm-3 col-form-label">Nama :</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" id="nama_barang<?= $data['id_barang'] ?>" name="nama_barang" value="<?= $data['nama_barang'] ?>" readonly required style="background-color: #fffde7;">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label class="col-sm-3 col-form-label">Satuan :</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" id="satuan<?= $data['id_barang'] ?>" name="satuan" value="<?= $data['satuan'] ?>" readonly required style="background-color: #fffde7;">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label class="col-sm-3 col-form-label">Permintaan :</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="number" class="form-control" id="permintaan<?= $data['id_barang'] ?>" name="permintaan" placeholder="0" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label class="col-sm-3 col-form-label">Diterima:</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="number" class="form-control" id="tambah_stok<?= $data['id_barang'] ?>" name="tambah_stok" placeholder="0" required oninput="hitungTotal(<?= $data['id_barang'] ?>)">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label class="col-sm-3 col-form-label">Tgl Diterima</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="date" class="form-control" id="tgl_terima<?= $data['id_barang'] ?>" name="tgl_terima" value="<?= $date ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label class="col-sm-3 col-form-label">No Connote :</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" id="awb<?= $data['id_barang'] ?>" name="awb" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label class="col-sm-3 col-form-label">Keterangan :</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" id="keterangan<?= $data['id_barang'] ?>" name="keteranagn" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label class="col-sm-3 col-form-label">Total Stok :</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="number" class="form-control" id="total_stok<?= $data['id_barang'] ?>" name="total_stok" placeholder="0" readonly required style="background-color: #fffde7;">
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