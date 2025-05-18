<?php
require_once "../config/koneksi.php";
require_once "../assets/sweetalert/dist/func_sweetAlert.php";
session_start(); // Mulai sesi

if (isset($_POST['pickup'])) {
    $invoice = trim(mysqli_real_escape_string($koneksi, $_POST['invoice']));
    $tgl_pickup = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_pickup']));
    $status = trim(mysqli_real_escape_string($koneksi, $_POST['status']));
    $nama_pickup = trim(mysqli_real_escape_string($koneksi, $_POST['nama_pickup']));

    mysqli_query($koneksi, "UPDATE tb_pesanan SET status='$status', tgl_pickup='$tgl_pickup', nama_pickup='$nama_pickup' WHERE invoice='$invoice'");
    mysqli_query($koneksi, "UPDATE tb_keranjang SET status='$status' WHERE invoice='$invoice'");

    showSweetAlert('success', 'Success', $pesan_update, '#3085d6', '../../public/views/pickup/index.php');
}
