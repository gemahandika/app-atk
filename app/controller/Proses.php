<?php
require_once "../config/koneksi.php";
require_once "../assets/sweetalert/dist/func_sweetAlert.php";
session_start(); // Mulai sesi

if (isset($_POST['generate'])) {
    $tgl_proses = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_proses']));
    $status = trim(mysqli_real_escape_string($koneksi, $_POST['status']));
    $invoice = trim(mysqli_real_escape_string($koneksi, $_POST['invoice']));
    $total_tagihan = trim(mysqli_real_escape_string($koneksi, $_POST['total_tagihan']));
    $total_items = trim(mysqli_real_escape_string($koneksi, $_POST['total_items']));

    // Ambil data barang dari tb_keranjang berdasarkan invoice
    $result = mysqli_query($koneksi, "SELECT kode_barang, jumlah FROM tb_keranjang WHERE invoice='$invoice'");

    $isStokCukup = true; // Variabel untuk cek stok
    $pesan_stok = ""; // Variabel untuk menyimpan pesan stok

    // Loop untuk mengecek stok barang di tb_barang sebelum proses
    while ($row = mysqli_fetch_assoc($result)) {
        $kode_barang = $row['kode_barang'];
        $jumlah = $row['jumlah'];

        // Ambil stok barang dari tb_barang
        $cek_stok = mysqli_query($koneksi, "SELECT stok_barang FROM tb_barang WHERE kode_barang='$kode_barang'");
        $data_stok = mysqli_fetch_assoc($cek_stok);
        $stok_tersedia = $data_stok['stok_barang'];

        // Cek apakah stok mencukupi
        if ($stok_tersedia < $jumlah) {
            $isStokCukup = false;
            $pesan_stok .= "Stok untuk Kode Barang: $kode_barang tidak mencukupi (tersisa: $stok_tersedia).<br>";
        }
    }

    // Jika stok mencukupi, proses pesanan
    if ($isStokCukup) {
        // Reset result untuk diproses
        $result = mysqli_query($koneksi, "SELECT kode_barang, jumlah FROM tb_keranjang WHERE invoice='$invoice'");

        // Update status di tabel tb_pesanan dan tb_keranjang
        mysqli_query($koneksi, "UPDATE tb_pesanan SET tgl_proses='$tgl_proses', total_item=$total_items, total_tagihan=$total_tagihan, status='$status' WHERE invoice='$invoice'");
        mysqli_query($koneksi, "UPDATE tb_keranjang SET status='$status' WHERE invoice='$invoice'");

        // Loop untuk mengurangi stok barang di tb_barang
        while ($row = mysqli_fetch_assoc($result)) {
            $kode_barang = $row['kode_barang'];
            $jumlah = $row['jumlah'];

            // Kurangi stok di tb_barang
            mysqli_query($koneksi, "UPDATE tb_barang SET stok_barang = stok_barang - $jumlah WHERE kode_barang = '$kode_barang'");
        }

        // Tampilkan pesan sukses
        showSweetAlert('success', 'Berhasil', 'Stok berhasil diperbarui dan pesanan berhasil di-generate!', '#3085d6', '../../public/views/proses_pesanan/generate_pesanan.php?invoice=' . $invoice);
    } else {
        // Tampilkan pesan error jika stok tidak mencukupi
        showSweetAlert('error', 'Stok Tidak Cukup', $pesan_stok, '#d33', '../../public/views/proses_pesanan/generate_pesanan.php?invoice=' . $invoice);
    }
}
