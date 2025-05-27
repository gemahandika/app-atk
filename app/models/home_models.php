<?php

$sql_pesan_admin = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'DIPESAN'") or die(mysqli_error($koneksi));
$dikirim = mysqli_num_rows($sql_pesan_admin);
$sql_generate_admin = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'GENERATE'") or die(mysqli_error($koneksi));
$diproses = mysqli_num_rows($sql_generate_admin);
$sql_bagging_admin = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'DIBAGGING'") or die(mysqli_error($koneksi));
$dibagging = mysqli_num_rows($sql_bagging_admin);
$sql_pickup_admin = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'DIPICKUP'") or die(mysqli_error($koneksi));
$dipickup = mysqli_num_rows($sql_pickup_admin);
$sql_terima_admin = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'DITERIMA'") or die(mysqli_error($koneksi));
$diterima = mysqli_num_rows($sql_terima_admin);

// Dashboard Client Agen
$sql_keranjang = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'DIPESAN' AND user_id = '$user1'") or die(mysqli_error($koneksi));
$data_keranjang = mysqli_num_rows($sql_keranjang);
$sql_generate = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'GENERATE' AND user_id = '$user1'") or die(mysqli_error($koneksi));
$data_generate = mysqli_num_rows($sql_generate);
$sql_bagging = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'DIBAGGING' AND user_id = '$user1'") or die(mysqli_error($koneksi));
$data_bagging = mysqli_num_rows($sql_bagging);
$sql_pickup = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'DIPICKUP' AND user_id = '$user1'") or die(mysqli_error($koneksi));
$data_pickup = mysqli_num_rows($sql_pickup);
$sql_terima = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'DITERIMA' AND user_id = '$user1'") or die(mysqli_error($koneksi));
$data_terima = mysqli_num_rows($sql_terima);

// TOTAL BELANJA===========================================================================================
// Fungsi untuk menghitung total tagihan
function getTotalTagihan($koneksi, $condition = "")
{
    $sql = "SELECT SUM(total_tagihan) AS total_semua_tagihan FROM tb_pesanan $condition";
    $result = $koneksi->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_semua_tagihan'];
    }
    return 0;
}

// Menghitung total tagihan untuk user tertent 
$total_tagihan_user = getTotalTagihan($koneksi, "WHERE user_id = '$user1'");

// Menghitung total semua tagihan tanpa filter user
$total_tagihan_semua = getTotalTagihan($koneksi);

// Menampilkan hasil
echo number_format($total_tagihan_user, 0, ',', '.');
echo number_format($total_tagihan_semua, 0, ',', '.');

// Total Invoice ===========================================================================================
function getTotalInvoice($koneksi, $condition = "")
{
    $query = "SELECT * FROM tb_pesanan $condition";
    $result = mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));
    return mysqli_num_rows($result);
}
$total_invoice_user = getTotalInvoice($koneksi, "WHERE user_id = '$user1'");
$total_invoice_semua = getTotalInvoice($koneksi);

function getTotalkeranjang($koneksi, $condition = "")
{
    $query = "SELECT * FROM tb_keranjang $condition";
    $result = mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));
    return mysqli_num_rows($result);
}
$total_keranjang_user = getTotalkeranjang($koneksi, "WHERE user_id = '$user1'");
$total_keranjang_semua = getTotalkeranjang($koneksi);

// Total OTS ===================================================================================================
function getTotalOTS($koneksi, $condition = "")
{
    $sql = "SELECT SUM(total_tagihan) AS total_semua_ots FROM tb_pesanan $condition";
    $result = $koneksi->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_semua_ots'];
    }
    return 0;
}

// Menghitung total tagihan untuk user tertent 
$total_ots_user = getTotalOTS($koneksi, "WHERE user_id = '$user1' AND pembayaran = 'OTS'");

// Menghitung total semua tagihan tanpa filter user
$total_ots_semua = getTotalTagihan($koneksi, "WHERE pembayaran = 'OTS'");

// Menampilkan hasil
echo number_format($total_ots_user, 0, ',', '.');
echo number_format($total_ots_semua, 0, ',', '.');
