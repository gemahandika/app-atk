<?php
if (isset($_GET['invoice'])) {
    // Mengamankan input agar terhindar dari SQL Injection
    $invoice = mysqli_real_escape_string($koneksi, $_GET['invoice']);

    // Eksekusi query untuk mengambil data pesanan berdasarkan nomor invoice
    $sql1 = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE invoice = '$invoice'") or die(mysqli_error($koneksi));

    if (mysqli_num_rows($sql1) > 0) {
        // Jika ditemukan, ambil data pesanan
        $data = mysqli_fetch_array($sql1);
        $tgl_pesanan = $data['date'];
        $tgl_proses = $data['tgl_proses'];
        $tgl_pickup = $data['tgl_pickup'];
        $tgl_terima = $data['tgl_terima'];
        $user_id = $data['user_id'];
        $nama_pickup = $data['nama_pickup'];
        $total_item = $data['total_item'];
    } else {
        // Jika data tidak ditemukan berdasarkan invoice
        echo "<p>Data tidak sesuai. No Invoice '$invoice' tidak ditemukan.</p>";
        $invoice = "Data Tidak Sesuai";
        $tgl_pesanan = "Data Tidak Sesuai";
        $tgl_proses = "Data Tidak Sesuai";
        $tgl_pickup = "Data Tidak Sesuai";
        $tgl_terima = "Data Tidak Sesuai";
        $user_id = "-";
        $nama_pickup = "-";
        $total_item = "-";
    }
} else {
    // Jika tidak ada parameter GET 'invoice'
    echo "<p>Silakan masukkan No Invoice untuk melihat pesanan.</p>";
    $invoice = "Belum Ada";
    $tgl_pesanan = "Belum Ada";
    $tgl_proses = "Belum Diproses";
    $tgl_pickup = "Belum Pickup";
    $tgl_terima = "Belum Diterima";
    $user_id = "-";
    $nama_pickup = "-";
    $total_item = "-";
}
