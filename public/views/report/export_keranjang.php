<?php
session_name("dashboard_atk_session");
session_start();
include '../../../app/models/Hak_akses.php';
$user1 = $_SESSION['admin_username'];


// Ambil parameter dari GET
$dari = isset($_GET['dari']) ? mysqli_real_escape_string($koneksi, $_GET['dari']) : null;
$ke = isset($_GET['ke']) ? mysqli_real_escape_string($koneksi, $_GET['ke']) : null;
$user_id1 = isset($_GET['user_id']) ? mysqli_real_escape_string($koneksi, $_GET['user_id']) : '';

// Jika tidak memilih tanggal, unduh semua data
if (!$dari || !$ke) {
    // Query untuk mendapatkan semua data tanpa filter tanggal
    if (has_access($allowed_admin)) {
        // Jika ada user_id, tambahkan filter berdasarkan user_id
        if (!empty($user_id1)) {
            $query = "SELECT * FROM tb_keranjang WHERE user_id = '$user_id1' ORDER BY id_keranjang DESC";
        } else {
            $query = "SELECT * FROM tb_keranjang ORDER BY id_keranjang DESC";
        }
    }
    if (has_access($allowed_agen)) {
        $query = "SELECT * FROM tb_keranjang WHERE user_id = '$user1' ORDER BY id_keranjang DESC";
    }
} else {
    // Query untuk mendapatkan data berdasarkan rentang tanggal jika tanggal dipilih
    if (has_access($allowed_admin)) {
        // Jika ada user_id, tambahkan filter berdasarkan user_id dan rentang tanggal
        if (!empty($user_id1)) {
            $query = "SELECT * FROM tb_keranjang WHERE user_id = '$user_id1' AND tgl_pesan BETWEEN '$dari' AND '$ke' ORDER BY id_keranjang DESC";
        } else {
            $query = "SELECT * FROM tb_keranjang WHERE tgl_pesan  BETWEEN '$dari' AND '$ke' ORDER BY id_keranjang DESC";
        }
    }
    if (has_access($allowed_agen)) {
        $query = "SELECT * FROM tb_keranjang WHERE user_id = '$user1' AND tgl_pesan BETWEEN '$dari' AND '$ke' ORDER BY id_keranjang DESC";
    }
}

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($koneksi));
}

// Set header agar browser memahami ini adalah file CSV yang harus diunduh
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=DETAIL_ITEMS_' . ($dari ? $dari : 'all') . '_to_' . ($ke ? $ke : 'all') . '.csv');

// Buat file CSV
$output = fopen('php://output', 'w');

// Tulis header kolom CSV
fputcsv($output, array('No', 'Kode Barang', 'Kategori', 'Tanggal Pesan', 'Nama Barang', 'Satuan', 'Jumlah', 'Harga', 'Total Harga', 'User ID', 'Nama User', 'Invoice'));

// Tulis data ke file CSV
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, array(
        $no++,
        $row['kode_barang'],
        $row['katagori'],
        $row['tgl_pesan'],
        $row['nama_barang'],
        $row['satuan'],
        $row['jumlah'],
        number_format($row['harga'], 0, ',', '.'),
        number_format($row['total_harga'], 0, ',', '.'),
        $row['user_id'],
        $row['nama_user'],
        $row['invoice']
    ));
}

// Tutup output stream
fclose($output);
exit;
