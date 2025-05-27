<?php
session_name("dashboard_atk_session");
session_start();
require_once "../config/koneksi.php";
require_once "../assets/sweetalert/dist/func_sweetAlert.php";

if (!isset($_POST['pickup']) || !isset($_POST['no_invoice'])) {
    header("Location:../../page_error/error.php");
    exit();
}

$no_invoice = mysqli_real_escape_string($koneksi, $_POST['no_invoice']);
$nama_user = isset($_POST['nama_user']) ? mysqli_real_escape_string($koneksi, $_POST['nama_user']) : '';
$pickup_by = $_SESSION['admin_username']; // sesuaikan nama session untuk username admin
$pickup_date = date("Y-m-d H:i:s");

// Query update untuk tb_pesanan
$query_pesanan = "
    UPDATE tb_pesanan
    SET status = 'DIPICKUP',
        nama_pickup = '$pickup_by',
        tgl_pickup = '$pickup_date'
    WHERE invoice = '$no_invoice'
";

// Query update untuk tb_keranjang
$query_keranjang = "
    UPDATE tb_keranjang
    SET status = 'DIPICKUP'
    WHERE invoice = '$no_invoice'
";

// Eksekusi kedua query
if (mysqli_query($koneksi, $query_pesanan) && mysqli_query($koneksi, $query_keranjang)) {
    showSweetAlert(
        'success',
        'Sukses',
        'Invoice <b>' . $no_invoice . '</b> berhasil di-pickup',
        '#3085d6',
        '../../public/views/pickup/index.php'
    );
    exit();
} else {
    echo "Gagal melakukan pickup. Error: " . mysqli_error($koneksi);
}
