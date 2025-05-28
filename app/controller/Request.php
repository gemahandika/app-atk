<?php
require_once "../config/koneksi.php";
session_name("dashboard_atk_session");
session_start();

if (!isset($_SESSION['admin_username'])) {
    header("location:../login/login.php");
    exit();
}

$user1 = $_SESSION['admin_username'];
$sql = mysqli_query($koneksi, "SELECT status FROM user WHERE username='$user1'") or die(mysqli_error($koneksi));
$user_data = $sql->fetch_array();

if ($user_data['status'] == 'NON AKTIF') {
    $_SESSION['swal'] = [
        'icon' => 'error',
        'title' => 'Akses Ditolak',
        'text' => 'Akun Anda telah dinonaktifkan. Silakan hubungi admin.',
    ];
    header("location:../../public/views/form_pesanan/index.php");
    exit();
}

if (isset($_POST['add_keranjang'])) {
    $kode_barang = trim(mysqli_real_escape_string($koneksi, $_POST['kode_barang']));
    $katagori = trim(mysqli_real_escape_string($koneksi, $_POST['katagori']));
    $nama_barang = trim(mysqli_real_escape_string($koneksi, $_POST['nama_barang']));
    $satuan = trim(mysqli_real_escape_string($koneksi, $_POST['satuan']));
    $harga = (int) trim(mysqli_real_escape_string($koneksi, $_POST['harga']));
    $jumlah = (int) trim(mysqli_real_escape_string($koneksi, $_POST['jumlah']));
    $total_harga = $harga * $jumlah;
    $user_id = trim(mysqli_real_escape_string($koneksi, $_POST['user_id']));
    $nama_user = trim(mysqli_real_escape_string($koneksi, $_POST['nama_user']));
    $status = trim(mysqli_real_escape_string($koneksi, $_POST['status']));

    $check_query = "SELECT * FROM tb_keranjang WHERE kode_barang = '$kode_barang' AND status = '$status' AND user_id = '$user_id'";
    $check_result = $koneksi->query($check_query);

    if ($check_result->num_rows > 0) {
        $_SESSION['swal'] = [
            'icon' => 'warning',
            'title' => 'Oops...',
            'text' => 'Maaf! Barang sudah ada di keranjang.',
        ];
    } else {
        $insert_query = "INSERT INTO tb_keranjang 
        (kode_barang, katagori, nama_barang, satuan, jumlah, harga, total_harga, user_id, nama_user, status)
        VALUES
        ('$kode_barang', '$katagori', '$nama_barang', '$satuan', $jumlah, $harga, $total_harga, '$user_id', '$nama_user', '$status')";

        if (mysqli_query($koneksi, $insert_query)) {
            $_SESSION['swal'] = [
                'icon' => 'success',
                'title' => 'Sukses',
                'text' => 'Barang berhasil ditambahkan ke keranjang.',
            ];
        } else {
            $_SESSION['swal'] = [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Terjadi kesalahan saat menambahkan barang.',
            ];
        }
    }

    header("location:../../public/views/form_pesanan/index.php");
    exit();
} else if (isset($_POST['kirim_pesanan'])) {
    function generateInvoiceNumber()
    {
        $prefix = 'INV'; // Prefix untuk nomor invoice
        $date = date('Ymd'); // Tanggal hari ini dalam format YYYYMMDD
        $randomNumber = mt_rand(1000, 9999); // Angka acak 4 digit
        return $prefix . $date . $randomNumber; // Gabungkan menjadi nomor invoice
    }
    $invoice = isset($_SESSION['noInvoice']) ? $_SESSION['noInvoice'] : generateInvoiceNumber();

    $nama_user = trim(mysqli_real_escape_string($koneksi, $_POST['nama_user']));
    $user_id = trim(mysqli_real_escape_string($koneksi, $_POST['user_id']));
    $total_items = trim(mysqli_real_escape_string($koneksi, $_POST['total_items']));
    $total_tagihan = trim(mysqli_real_escape_string($koneksi, $_POST['total_tagihan']));
    $date = trim(mysqli_real_escape_string($koneksi, $_POST['date']));
    $status = trim(mysqli_real_escape_string($koneksi, $_POST['status']));
    $status1 = trim(mysqli_real_escape_string($koneksi, $_POST['status1']));
    $pembayaran = trim(mysqli_real_escape_string($koneksi, $_POST['pembayaran']));
    $keterangan = trim(mysqli_real_escape_string($koneksi, $_POST['keterangan']));

    // Jalankan query update dan insert
    $update1 = mysqli_query($koneksi, "UPDATE tb_keranjang SET invoice ='$invoice', tgl_pesan = '$date' WHERE status='$status' AND user_id='$user_id'");
    $update2 = mysqli_query($koneksi, "UPDATE tb_keranjang SET status ='$status1', keterangan = '$keterangan' WHERE invoice='$invoice' AND user_id='$user_id'");
    $insert = mysqli_query($koneksi, "INSERT INTO tb_pesanan (nama_user, user_id, total_item, total_tagihan, invoice, status, date, pembayaran, keterangan) 
        VALUES('$nama_user','$user_id', $total_items, $total_tagihan, '$invoice', '$status1', '$date' ,'$pembayaran' ,'$keterangan')");

    if ($update1 && $update2 && $insert) {
        $_SESSION['swal'] = [
            'icon' => 'success',
            'title' => 'Sukses',
            'text' => 'Pesanan berhasil dikirim!',
        ];
        $_SESSION['noInvoice'] = generateInvoiceNumber();
    } else {
        $_SESSION['swal'] = [
            'icon' => 'error',
            'title' => 'Gagal',
            'text' => 'Terjadi kesalahan saat mengirim pesanan.',
        ];
    }

    header("Location: ../../public/views/form_pesanan/index.php");
    exit();
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
