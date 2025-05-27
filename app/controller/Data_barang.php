<?php
require_once "../config/koneksi.php";
require_once "../assets/sweetalert/dist/func_sweetAlert.php";

if (isset($_POST['add'])) {
    $kode_barang = trim(mysqli_real_escape_string($koneksi, $_POST['kode_barang']));
    $nama_barang = trim(mysqli_real_escape_string($koneksi, $_POST['nama_barang']));
    $satuan = trim(mysqli_real_escape_string($koneksi, $_POST['satuan']));
    $katagori = trim(mysqli_real_escape_string($koneksi, $_POST['katagori']));
    $harga = trim(mysqli_real_escape_string($koneksi, $_POST['harga']));
    $status_barang = trim(mysqli_real_escape_string($koneksi, $_POST['status_barang']));
    $stok = trim(mysqli_real_escape_string($koneksi, $_POST['stok']));

    // Validasi NIK agar tidak ganda
    $check_query = "SELECT * FROM tb_barang WHERE kode_barang = '$kode_barang'";
    $check_result = $koneksi->query($check_query);
    if ($check_result->num_rows > 0) {
        showSweetAlert('warning', 'Oops...', 'Maaf ! Barang Sudah Ada di Database', '#3085d6', '../../public/views/data_barang/index.php');
    } else {
        // Masukan data ke tabel keranjang
        mysqli_query($koneksi, "INSERT INTO tb_barang (kode_barang, nama_barang, satuan, harga, status_barang, stok_barang, katagori) 
    VALUES('$kode_barang', '$nama_barang', '$satuan', $harga, '$status_barang', $stok, '$katagori')");
        showSweetAlert('success', 'Sukses', $pesan_ok, '#3085d6', '../../public/views/data_barang/index.php');
    }
} else if (isset($_POST['add_stok'])) {
    $id = $_POST['id_barang'];
    $total_stok = trim(mysqli_real_escape_string($koneksi, $_POST['total_stok']));

    $tgl_request = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_request']));
    $kode_barang = trim(mysqli_real_escape_string($koneksi, $_POST['kode_barang']));
    $nama_barang = trim(mysqli_real_escape_string($koneksi, $_POST['nama_barang']));
    $satuan = trim(mysqli_real_escape_string($koneksi, $_POST['satuan']));
    $permintaan = trim(mysqli_real_escape_string($koneksi, $_POST['permintaan']));
    $tambah_stok = trim(mysqli_real_escape_string($koneksi, $_POST['tambah_stok']));
    $tgl_terima = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_terima']));
    $awb = trim(mysqli_real_escape_string($koneksi, $_POST['awb']));
    $keteranagn = trim(mysqli_real_escape_string($koneksi, $_POST['keterangan']));

    //update tb keranjang (invoice)
    mysqli_query($koneksi, "UPDATE tb_barang SET stok_barang ='$total_stok' WHERE id_barang='$id'");

    mysqli_query($koneksi, "INSERT INTO atk_masuk (tgl_request, kode_barang, nama_barang, satuan, jumlah_permintaan, jumlah_terima, tgl_terima, awb, keterangan) 
    VALUES('$tgl_request','$kode_barang', '$nama_barang', '$satuan', $permintaan, $tambah_stok, '$tgl_terima', '$awb', '$keteranagn')");

    showSweetAlert('success', 'Success', 'Stok Berhasil ditambah', '#3085d6', '../../public/views/data_barang/index.php');
    // $_SESSION['noInvoice'] = generateInvoiceNumber();
} else if (isset($_POST['edit_barang'])) {
    $id = $_POST['id'];
    $kode_barang = trim(mysqli_real_escape_string($koneksi, $_POST['kode_barang']));
    $nama_barang = trim(mysqli_real_escape_string($koneksi, $_POST['nama_barang']));
    $satuan = trim(mysqli_real_escape_string($koneksi, $_POST['satuan']));
    $harga = trim(mysqli_real_escape_string($koneksi, $_POST['harga']));
    $status_barang = trim(mysqli_real_escape_string($koneksi, $_POST['status_barang']));
    $katagori = trim(mysqli_real_escape_string($koneksi, $_POST['katagori']));
    $buffer = trim(mysqli_real_escape_string($koneksi, $_POST['buffer']));
    $stok_barang = trim(mysqli_real_escape_string($koneksi, $_POST['stok_barang']));


    mysqli_query($koneksi, "UPDATE tb_barang SET kode_barang='$kode_barang', nama_barang='$nama_barang', satuan='$satuan', harga='$harga', status_barang='$status_barang', 
    katagori='$katagori', buffer='$buffer' , stok_barang='$stok_barang' WHERE id_barang='$id'");

    showSweetAlert('success', 'Success', 'Data Berhasil di Edit', '#3085d6', '../../public/views/data_barang/index.php');
}
