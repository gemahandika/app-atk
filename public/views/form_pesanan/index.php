<?php
session_name("dashboard_atk_session");
session_start();
include '../../header.php';
include '../../../app/models/request_models.php';
include 'modal.php';
$date_for_db = date("Y-m-d H:i:s");
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
                        <h5 class="card-title" style="border-bottom: 1px solid black;">Form Pesanan</h5>
                        <form action="../../../app/controller/Request.php" method="post">
                            <input type="hidden" class="form-control" name="nama_user" value="<?= $data2 ?>" readonly>
                            <input type="hidden" class="form-control" name="user_id" value="<?= $user1 ?>" readonly>
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

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Kategori</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="katagori" id="katagori" readonly style="background-color: #fffde7;">
                                </div>
                            </div>

                            <input type="hidden" class="form-control" name="kode_barang" id="kode_barang" readonly>

                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Satuan</label>
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
                                <label for="inputText" class="col-sm-3 col-form-label">Total Harga</label>
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
                                <input type="hidden" class="form-control" name="status1" value="DIPESAN" readonly>
                                <input type="hidden" class="form-control" id="datetime" name="date" readonly>
                                <input type="hidden" class="form-control" name="invoice" value="<?php echo $noInvoice; ?>" readonly>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Keterangan</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="keterangan" required placeholder="Masukan Nama Pemesan">
                                    </div>
                                </div>

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
                                        <button type="submit" id="setTimeBtn" name="kirim_pesanan" class="btn btn-success">Kirim Pesanan</button>
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
                                    <th class="th-small text-center">KATAGORI</th>
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
                                    <td class="th-small text-center"><?= $data1['katagori'] ?></td>
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
<!-- ======= Footer ======= -->
<!-- Vendor JS Files -->
<script src="../../../app/assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="../../../app/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../../app/assets/vendor/chart.js/chart.umd.js"></script>
<script src="../../../app/assets/vendor/echarts/echarts.min.js"></script>
<script src="../../../app/assets/vendor/quill/quill.js"></script>
<script src="../../../app/assets/vendor/tinymce/tinymce.min.js"></script>
<script src="../../../app/assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="../../../app/assets/js/main.js"></script>

<!-- CDN JS Libraries -->

<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>

<!-- Tambahkan SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script untuk ambil waktu sekarang saat tombol diklik -->
<script>
    document.getElementById('setTimeBtn').addEventListener('click', function() {
        const now = new Date();

        // Format: YYYY-MM-DD HH:mm:ss
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        const formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

        // Isi ke input
        document.getElementById('datetime').value = formattedDateTime;
    });
</script>
<!-- Kirim Pesanan Toggle Button -->
<script>
    $(document).ready(function() {
        function checkTotalItems() {
            var totalItems = parseInt($('#total_items').val()) || 0;
            $('#setTimeBtn').prop('disabled', totalItems === 0)
                .attr('title', totalItems === 0 ? 'Silahkan masukkan pesanan ke list' : '');
        }

        checkTotalItems();
        $('#total_items').on('input', checkTotalItems);
    });
</script>

<script>
    $(document).ready(function() {
        var allItems = <?php echo json_encode($result); ?>;

        // Tampilkan semua barang langsung saat halaman dimuat
        $('#namaBarang').empty().append('<option value="">- Pilih Barang -</option>');
        allItems.forEach(item => {
            $('#namaBarang').append(`<option value="${item.nama_barang}">${item.nama_barang} - Rp. ${parseInt(item.harga).toLocaleString('id-ID')}</option>`);
        });

        // Sembunyikan dropdown kategori jika tidak digunakan
        $('#katagori').closest('.form-group').hide(); // atau sesuaikan dengan struktur HTML

        // Ketika barang dipilih, isi field lainnya
        $('#namaBarang').change(function() {
            var selectedItemName = $(this).val().split(' - ')[0];
            var selectedItem = allItems.find(item => item.nama_barang === selectedItemName);

            if (selectedItem) {
                $('#kode_barang').val(selectedItem.kode_barang);
                $('#satuan').val(selectedItem.satuan);
                $('#harga').val(selectedItem.harga);

                // Isi kategori berdasarkan data barang
                $('#katagori').val(selectedItem.katagori);

                // Tampilkan input kategori jika disembunyikan
                $('#katagori').closest('.form-group, .row, .mb-3').show();

                calculateTotalHarga();
            } else {
                $('#kode_barang, #satuan, #harga, #katagori').val('');
                $('#total_harga').val(0);
                $('#katagori').closest('.form-group, .row, .mb-3').hide();
            }
        });


        $('#jumlah').on('input', calculateTotalHarga);

        function calculateTotalHarga() {
            var harga = parseFloat($('#harga').val()) || 0;
            var jumlah = parseInt($('#jumlah').val()) || 0;
            $('#total_harga').val(harga * jumlah);
        }
    });
</script>

<!-- Inisialisasi DataTables -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('#example1')) {
            new DataTable('#example1', {
                paging: true,
                scrollCollapse: true,
                scrollY: '335px'
            });
        }

        if (document.querySelector('#example')) {
            new DataTable('#example', {
                paging: true,
                scrollCollapse: false
                // scrollY: '350px'
            });
        }
    });
</script>
<!-- Select2 Initialization -->
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: '-- Pilih Data --',
            allowClear: true
        });
    });
</script>
<!-- Pembayaran Otomatis -->
<script>
    function updatePembayaran() {
        var totalTagihan = document.getElementById('totalTagihan').value;
        document.getElementById('pembayaran').value = totalTagihan > 0 ? 'OTS' : 'DONE';
    }

    window.onload = updatePembayaran;
</script>
</body>

</html>