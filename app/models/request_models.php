<?php
include '../../../app/config/koneksi.php';

// Query untuk menghitung total item (jumlah item yang dipesan)
$queryTotalItems = mysqli_query($koneksi, "SELECT SUM(jumlah) AS total_items FROM tb_keranjang WHERE status = 'KERANJANG' AND user_id = '$user1'");
$totalItemsResult = mysqli_fetch_assoc($queryTotalItems);
$totalItems = $totalItemsResult['total_items'] ? $totalItemsResult['total_items'] : 0;

// Query untuk menghitung total tagihan (jumlah dari kolom total_harga)
$queryTotalTagihan = mysqli_query($koneksi, "SELECT SUM(total_harga) AS total_tagihan FROM tb_keranjang WHERE status = 'KERANJANG' AND user_id = '$user1'");
$totalTagihanResult = mysqli_fetch_assoc($queryTotalTagihan);
$totalTagihan = $totalTagihanResult['total_tagihan'] ? $totalTagihanResult['total_tagihan'] : 0;
