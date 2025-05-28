<?php
session_name("dashboard_atk_session");
session_start();
include '../../../app/config/koneksi.php';

$tujuan_index = "index.php";

// Validasi ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['swal'] = [
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'ID tidak ditemukan!',
    ];
    header("Location: $tujuan_index");
    exit();
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

$query = mysqli_query($koneksi, "DELETE FROM tb_keranjang WHERE id_keranjang = '$id'");

if ($query) {
    $_SESSION['swal'] = [
        'icon' => 'success',
        'title' => 'Success',
        'text' => 'Data berhasil dihapus.',
    ];
} else {
    $_SESSION['swal'] = [
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Terjadi kesalahan saat menghapus data.',
    ];
}

header("Location: $tujuan_index");
exit();
