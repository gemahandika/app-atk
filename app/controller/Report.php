<?php
require_once "../config/koneksi.php";
require_once "../assets/sweetalert/dist/func_sweetAlert.php";

if (isset($_POST['proses_pembayaran'])) {

    $invoice = trim(mysqli_real_escape_string($koneksi, $_POST['invoice']));
    $user_id = trim(mysqli_real_escape_string($koneksi, $_POST['user_id']));
    $nama_user = trim(mysqli_real_escape_string($koneksi, $_POST['nama_user']));
    $tgl_proses = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_proses']));
    $tgl_terima = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_terima']));
    $total_tagihan = trim(mysqli_real_escape_string($koneksi, $_POST['total_tagihan']));
    $pembayaran = trim(mysqli_real_escape_string($koneksi, $_POST['pembayaran']));

    //update tb keranjang (invoice)
    mysqli_query($koneksi, "UPDATE tb_pesanan SET pembayaran ='$pembayaran' WHERE invoice='$invoice'");
    showSweetAlert('success', 'Success', 'Pembayaran Berhasil', '#3085d6', '../../public/views/report/detail_invoice.php');
}
