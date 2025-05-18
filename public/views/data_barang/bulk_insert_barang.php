<?php
session_name("dashboard_atk_session");
session_start();
if (!in_array("admin", $_SESSION['admin_akses']) && !in_array("super_admin", $_SESSION['admin_akses'])) {
    echo "Ooopss!! Kamu Tidak Punya Akses";
    exit();
}
include '../../header.php';
require '../../../vendor/autoload.php';
require '../../../app/config/koneksi.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if (isset($_POST['submit'])) {
    $file = $_FILES['excel_file']['tmp_name'];

    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    $isHeader = true;
    $inserted = 0;
    foreach ($rows as $row) {
        if ($isHeader) {
            $isHeader = false;
            continue;
        }

        $kode_barang = $koneksi->real_escape_string($row[1]);
        $nama_barang = $koneksi->real_escape_string($row[2]);
        $satuan = $koneksi->real_escape_string($row[3]);
        $harga = $koneksi->real_escape_string($row[4]);
        $status_barang = $koneksi->real_escape_string($row[5]);
        $stok_barang = $koneksi->real_escape_string($row[6]);
        $katagori = $koneksi->real_escape_string($row[7]);
        $min_order = $koneksi->real_escape_string($row[8]);
        $buffer = $koneksi->real_escape_string($row[9]);
        $keterangan = $koneksi->real_escape_string($row[10]);


        $sql = "INSERT INTO tb_barang (kode_barang, nama_barang, satuan, harga, status_barang, stok_barang, katagori, min_order, buffer, keterangan)
                VALUES ('$kode_barang', '$nama_barang', '$satuan', '$harga', '$status_barang', '$stok_barang', '$katagori', '$min_order', '$buffer', '$keterangan')";

        if ($koneksi->query($sql)) {
            $inserted++;
        }
    }

    echo "Berhasil insert $inserted data dari Excel!";
}
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
                            <div class="card-body">

                                <h4>Upload Excel (.xlsx) untuk Insert Data</h4>
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="file" name="excel_file" accept=".xlsx" required>
                                    <button type="submit" class="btn btn-success" name="submit">Upload dan Insert</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
include '../../footer.php';
?>