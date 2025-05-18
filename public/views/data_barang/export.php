<?php
session_name("dashboard_atk_session");
session_start();
include '../../../app/models/Hak_akses.php';
$user1 = $_SESSION['admin_username'];

// Query untuk mendapatkan semua data barang
$query = "SELECT * FROM tb_barang ORDER BY id_barang ASC";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($koneksi));
}

// Set header agar browser memahami ini adalah file CSV yang harus diunduh
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data_barang.csv');

// Buat file CSV
$output = fopen('php://output', 'w');

// Tulis header kolom CSV
fputcsv($output, array('No', 'Kode Barang', 'Nama Barang', 'Satuan', 'Harga', 'Status', 'Stok', 'Buffer', 'Keterangan'));

// Tulis data ke file CSV
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, array(
        $no++,
        $row['kode_barang'],
        $row['nama_barang'],
        $row['satuan'],
        number_format($row['harga'], 0, ',', '.'),
        $row['status_barang'],
        $row['stok_barang'],
        $row['buffer'],
        ($row['stok_barang'] <= $row['buffer']) ? 'Warning' : ''
    ));
}

// Tutup output stream
fclose($output);
exit;
