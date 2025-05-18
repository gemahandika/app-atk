<?php
require_once "../config/koneksi.php";
require_once "../assets/sweetalert/dist/func_sweetAlert.php";
session_name("dashboard_atk_session");
session_start(); // Mulai sesi

// Ambil username dari session
if (!isset($_SESSION['admin_username'])) {
    header("location:../login/login.php");
    exit();
}

$user1 = $_SESSION['admin_username'];

// Cek status user di database
$sql = mysqli_query($koneksi, "SELECT status FROM user WHERE username='$user1'") or die(mysqli_error($koneksi));
$user_data = $sql->fetch_array();

// Jika status user adalah nonaktif atau ditutup, kembali ke halaman sebelumnya
if ($user_data['status'] == 'NON AKTIF') {
    showSweetAlert('error', 'Akses Ditolak', 'Akun Anda telah dinonaktifkan. Silakan hubungi admin.', '#d33', '../../public/views/form_pesanan/index.php');
    exit();
}


function generateInvoiceNumber()
{
    $prefix = 'INV'; // Prefix untuk nomor invoice
    $date = date('Ymd'); // Tanggal hari ini dalam format YYYYMMDD
    $randomNumber = mt_rand(1000, 9999); // Angka acak 4 digit
    return $prefix . $date . $randomNumber; // Gabungkan menjadi nomor invoice
}

if (isset($_POST['add_keranjang'])) {
    $kode_barang = trim(mysqli_real_escape_string($koneksi, $_POST['kode_barang']));
    $katagori = trim(mysqli_real_escape_string($koneksi, $_POST['katagori']));
    $nama_barang = trim(mysqli_real_escape_string($koneksi, $_POST['nama_barang']));
    $satuan = trim(mysqli_real_escape_string($koneksi, $_POST['satuan']));
    $harga = trim(mysqli_real_escape_string($koneksi, $_POST['harga']));
    $jumlah = trim(mysqli_real_escape_string($koneksi, $_POST['jumlah']));
    $total_harga = trim(mysqli_real_escape_string($koneksi, $_POST['total_harga']));
    $user_id = trim(mysqli_real_escape_string($koneksi, $_POST['user_id']));
    $nama_user = trim(mysqli_real_escape_string($koneksi, $_POST['nama_user']));
    $status = trim(mysqli_real_escape_string($koneksi, $_POST['status']));

    // Validasi NIK agar tidak ganda
    $check_query = "SELECT * FROM tb_keranjang WHERE kode_barang = '$kode_barang' AND status = '$status' AND user_id = '$user_id'";
    $check_result = $koneksi->query($check_query);
    if ($check_result->num_rows > 0) {
        showSweetAlert('warning', 'Oops...', 'Maaf ! Barang Sudah Ada di Keranjang', '#3085d6', '../../public/views/form_pesanan/index.php');
    } else {
        // Masukan data ke tabel keranjang
        mysqli_query($koneksi, "INSERT INTO tb_keranjang (kode_barang, katagori, nama_barang, satuan, jumlah, harga, total_harga, user_id, nama_user, status) 
    VALUES('$kode_barang', '$katagori', '$nama_barang', '$satuan', $jumlah, $harga, $total_harga, '$user_id', '$nama_user', '$status')");
        showSweetAlert('success', 'Sukses', $pesan_ok, '#3085d6', '../../public/views/form_pesanan/index.php');
    }
} else if (isset($_POST['kirim_pesanan'])) {
    $invoice = isset($_SESSION['noInvoice']) ? $_SESSION['noInvoice'] : generateInvoiceNumber();

    $nama_user = trim(mysqli_real_escape_string($koneksi, $_POST['nama_user']));
    $user_id = trim(mysqli_real_escape_string($koneksi, $_POST['user_id']));
    $total_items = trim(mysqli_real_escape_string($koneksi, $_POST['total_items']));
    $total_tagihan = trim(mysqli_real_escape_string($koneksi, $_POST['total_tagihan']));
    $date = trim(mysqli_real_escape_string($koneksi, $_POST['date']));
    $status = trim(mysqli_real_escape_string($koneksi, $_POST['status']));
    $status1 = trim(mysqli_real_escape_string($koneksi, $_POST['status1']));
    $pembayaran = trim(mysqli_real_escape_string($koneksi, $_POST['pembayaran']));

    //update tb keranjang (invoice)
    mysqli_query($koneksi, "UPDATE tb_keranjang SET invoice ='$invoice' , tgl_pesan = '$date' WHERE status='$status' AND user_id='$user_id'");
    mysqli_query($koneksi, "UPDATE tb_keranjang SET status ='$status1' WHERE invoice='$invoice' AND user_id='$user_id'");
    // insert tb_pesanan / proses
    mysqli_query($koneksi, "INSERT INTO tb_pesanan (nama_user, user_id, total_item, total_tagihan, invoice, status, date, pembayaran) 
    VALUES('$nama_user','$user_id', $total_items, $total_tagihan, '$invoice', '$status1', '$date' ,'$pembayaran')");
    showSweetAlert('success', 'Success', $pesan_ok, '#3085d6', '../../public/views/form_pesanan/index.php');
    $_SESSION['noInvoice'] = generateInvoiceNumber();
} else if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $invoice = trim(mysqli_real_escape_string($koneksi, $_POST['invoice']));
    $jumlah = trim(mysqli_real_escape_string($koneksi, $_POST['jumlah']));
    $total_harga = trim(mysqli_real_escape_string($koneksi, $_POST['total_harga']));

    mysqli_query($koneksi, "UPDATE tb_keranjang SET jumlah='$jumlah', total_harga='$total_harga' WHERE id_keranjang='$id'");

    showSweetAlert('success', 'Success', $pesan_update, '#3085d6', '../../public/views/proses_pesanan/generate_pesanan.php?invoice=' . $invoice);
}

//  else if (isset($_POST['ambil'])) {
//     $id = $_POST['id'];
//     $saldo_update = trim(mysqli_real_escape_string($koneksi, $_POST['saldo_update']));

//     $jenis_bukubesar = trim(mysqli_real_escape_string($koneksi, $_POST['jenis_bukubesar']));
//     $tgl_bukubesar = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_bukubesar']));
//     $keterangan = trim(mysqli_real_escape_string($koneksi, $_POST['keterangan']));
//     $debit_bukubesar = trim(mysqli_real_escape_string($koneksi, $_POST['debit_bukubesar']));
//     $kredit_bukubesar = trim(mysqli_real_escape_string($koneksi, $_POST['kredit_bukubesar']));

//     mysqli_query($koneksi, "UPDATE tb_anggota SET saldo='$saldo_update' WHERE id_anggota='$id'");
//     // Masukan data ke table bukubesar
//     mysqli_query($koneksi, "INSERT INTO tb_bukubesar(jenis_bukubesar, tgl_bukubesar, keterangan, debit_bukubesar, kredit_bukubesar) 
//     VALUES('$jenis_bukubesar', '$tgl_bukubesar', '$keterangan', '$debit_bukubesar', '$kredit_bukubesar')");
//     showSweetAlert('success', 'Success', $pesan_ambil, '#3085d6', $tujuan_3);
