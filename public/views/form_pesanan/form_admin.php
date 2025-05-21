<?php
session_name("dashboard_atk_session");
session_start();
include '../../header.php';
include '../../../app/models/request_models.php';
include 'modal.php';
// session_start(); // Mulai sesi
$date = date("Y-m-day");
$time = date("H:i");
?>

<main id="main" class="main">
    <section class="section">
        <div class="row">

            <?php
            // Ambil status user dari database
            $status = $data1['status'];

            // Cek apakah user dinonaktifkan
            if ($status == 'NON AKTIF') {
                $showAlert = true;
            } else {
                $showAlert = false;
            }
            ?>

            <?php if ($showAlert): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    System Lock !! Silahkan Melakukan Pembayaran
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title" style="border-bottom: 1px solid black;">Form Pesanan Admin</h5>
                        <!-- General Form Elements -->
                        <form action="../../../app/controller/Request.php" method="post">
                            <input type="hidden" class="form-control" name="nama_user" value="<?= $data2 ?>" readonly>
                            <input type="hidden" class="form-control" name="user_id" value="<?= $user1 ?>" readonly>
                            <input type="hidden" class="form-control" name="katagori" value="0" readonly>
                            <input type="hidden" class="form-control" name="status" value="KERANJANG" readonly>




                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Barang <strong class="text-danger">*</strong></label>
                                <div class="col-sm-8">
                                    <select class="form-select select2" id="namaBarang" name="nama_barang" aria-label="Default select example" required>
                                        <option value="">- Pilih Barang -</option>
                                        <?php
                                        $sql = mysqli_query($koneksi, "SELECT * FROM tb_barang ORDER BY nama_barang ASC") or die(mysqli_error($koneksi));
                                        $result = array();
                                        while ($data = mysqli_fetch_array($sql)) {
                                            $result[] = $data;
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" class="form-control" name="kode_barang" id="kode_barang" readonly>

                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Satuan :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="satuan" id="satuan" readonly style="background-color: #fffde7;">
                                </div>
                            </div>

                            <input type="hidden" class="form-control" name="harga" id="harga" readonly>

                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Jumlah <strong class="text-danger">*</strong></label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" name="jumlah" id="jumlah" placeholder="0" min="0" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Total Harga :</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" name="total_harga" id="total_harga" value="0" readonly style="background-color: #fffde7;">
                                </div>
                            </div>
                            <?php
                            // Ambil status user dari database
                            $status = $data1['status'];

                            // Pengecekan status user, jika 'nonaktif' atau 'ditutup', tombol akan di-disable
                            $disabled = ($status == 'NON AKTIF') ? 'disabled' : '';
                            ?>
                            <div class="row mb-3">
                                <div class="col-sm-10">
                                    <!-- Tombol akan disabled jika user status 'nonaktif' atau 'ditutup' -->
                                    <button type="submit" name="add_keranjang" class="btn btn-primary" <?php echo $disabled; ?>>Masukan List</button>
                                </div>
                            </div>

                        </form><!-- End General Form Elements -->
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-danger" style="border-bottom: 2px solid red;">Invoice Pesanan</h5>
                            <!-- General Form Elements -->
                            <form action="../../../app/controller/Request.php" method="POST">
                                <?php
                                // Fungsi untuk generate nomor invoice baru
                                // Fungsi untuk generate nomor invoice baru
                                function generateInvoiceNumber()
                                {
                                    $prefix = 'INV'; // Prefix untuk nomor invoice
                                    $date = date('Ymd'); // Tanggal hari ini dalam format YYYYMMDD
                                    $randomNumber = mt_rand(1000, 9999); // Angka acak 4 digit
                                    return $prefix . $date . $randomNumber; // Gabungkan menjadi nomor invoice
                                }

                                // Cek apakah nomor invoice sudah ada di sesi
                                if (!isset($_SESSION['noInvoice'])) {
                                    // Jika belum ada, buat nomor invoice baru
                                    $_SESSION['noInvoice'] = generateInvoiceNumber();
                                }

                                // Ambil nomor invoice dari sesi
                                $noInvoice = $_SESSION['noInvoice'];
                                ?>
                                <input type="hidden" class="form-control" name="nama_user" value="<?= $data2 ?>" readonly>
                                <input type="hidden" class="form-control" name="user_id" value="<?= $user1 ?>" readonly>
                                <input type="hidden" class="form-control" name="status" value="KERANJANG" readonly>
                                <input type="hidden" class="form-control" name="status1" value="DIKIRIM" readonly>
                                <input type="hidden" class="form-control" name="date" value="<?= $date ?>" readonly>
                                <input type="hidden" class="form-control" name="invoice" value="<?php echo $noInvoice; ?>" readonly>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Total Items :</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="total_items" name="total_items" value="<?= $totalItems ?>" readonly required style="background-color: #fffde7;">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-3 col-form-label">Total Tagihan :</label>
                                    <div class="col-sm-8">
                                        <input type="number" id="totalTagihan" class="form-control" name="total_tagihan" value="<?= $totalTagihan ?>" readonly style="background-color: #fffde7;">
                                    </div>
                                </div>
                                <input type="hidden" id="pembayaran" name="pembayaran" readonly>

                                <div class="row mb-0">
                                    <div class="col-sm-10">
                                        <button type="submit" id="kirim_pesanan" name="kirim_pesanan" class="btn btn-success">Kirim Pesanan</button>
                                    </div>
                                </div>
                            </form><!-- End General Form Elements -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title" style="border-bottom: 1px solid black;">List Pesanan</h5>
                        <!-- Primary Color Bordered Table -->
                        <table id="example1" class="display nowrap" style="width:100%">
                            <thead>
                                <tr class="bg-secondary text-white">
                                    <th class="th-small text-center">NO</th>
                                    <th class="th-small text-center">NAMA PEMESAN</th>
                                    <!-- <th class="th-small text-center">KATAGORI</th> -->
                                    <th class="th-small text-center">BARANG</th>
                                    <th class="th-small text-center">SATUAN</th>
                                    <th class="th-small text-center">HARGA</th>
                                    <th class="th-small text-center">JUMLAH</th>
                                    <th class="th-small text-center">TOTAL</th>
                                    <th class="th-small text-center">ACTION</th>
                                </tr>
                            </thead>
                            <?php
                            $no = 0;
                            $sql = mysqli_query($koneksi, "SELECT * FROM tb_keranjang WHERE status = 'KERANJANG' AND user_id = '$user1' ORDER BY id_keranjang ASC") or die(mysqli_error($koneksi));
                            $result1 = array();
                            while ($data1 = mysqli_fetch_array($sql)) {
                                $result1[] = $data1;
                            }
                            foreach ($result1 as $data1) {
                                $no++;
                            ?>

                                <tr>
                                    <td><?= $no; ?></td>
                                    <td class="th-small text-center"><?= $data1['nama_user'] ?></td>
                                    <!-- <td class="th-small text-center"><?= $data1['katagori'] ?></td> -->
                                    <td class="th-small text-center"><?= $data1['nama_barang'] ?></td>
                                    <td class="th-small text-center"><?= $data1['satuan'] ?></td>
                                    <td class="th-small text-center"><?php echo number_format($data1['harga']) ?></td>
                                    <td class="th-small text-center"><?= $data1['jumlah'] ?></td>
                                    <td class="th-small text-center text-success"> <strong><?php echo number_format($data1['total_harga']) ?></strong></td>
                                    <td class="th-small text-left">
                                        <a href="delete.php?id=<?= $data1['id_keranjang'] ?>" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>

                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>



<?php
include '../../footer.php';
?>