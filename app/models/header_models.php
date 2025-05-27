<?php
// Query pesanan dengan status DIKIRIM
$sql1 = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'DIPESAN'")
    or die(mysqli_error($koneksi));
$dipesan = mysqli_num_rows($sql1);

// Query pesanan dengan status GENERATE
$sql_proses = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'GENERATE'")
    or die(mysqli_error($koneksi));
$diproses = mysqli_num_rows($sql_proses);

// Query pesanan dengan status BAGGING
$sql_bagging = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'DIBAGGING'")
    or die(mysqli_error($koneksi));
$dibagging = mysqli_num_rows($sql_bagging);

// Query pesanan dengan status DIPICKUP
$pickup_query = "SELECT * FROM tb_pesanan WHERE status = 'DIPICKUP'";
if (!has_access($allowed_admin)) {
    $pickup_query .= " AND user_id = '$user1'";
}
$sql_pickup = mysqli_query($koneksi, $pickup_query) or die(mysqli_error($koneksi));
$dipickup = mysqli_num_rows($sql_pickup);
