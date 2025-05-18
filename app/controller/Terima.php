<?php
require_once "../config/koneksi.php";
require_once "../assets/sweetalert/dist/func_sweetAlert.php";
session_start(); // Mulai sesi

if (isset($_POST['terima'])) {
    $invoice = trim(mysqli_real_escape_string($koneksi, $_POST['invoice']));
    $status = trim(mysqli_real_escape_string($koneksi, $_POST['status']));
    $tgl_terima = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_terima']));

    mysqli_query($koneksi, "UPDATE tb_pesanan SET tgl_terima='$tgl_terima', status='$status' WHERE invoice='$invoice'");
    mysqli_query($koneksi, "UPDATE tb_keranjang SET status='$status' WHERE invoice='$invoice'");


    showSweetAlert('success', 'Success', 'ATK DIterima', '#3085d6', '../../public/views/diterima/index.php');
}
