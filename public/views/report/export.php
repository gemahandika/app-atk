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
            $query = "SELECT * FROM tb_pesanan WHERE user_id = '$user_id1' ORDER BY id_pesanan DESC";
        } else {
            $query = "SELECT * FROM tb_pesanan ORDER BY id_pesanan DESC";
        }
    }
    if (has_access($allowed_agen)) {
        $query = "SELECT * FROM tb_pesanan WHERE user_id = '$user1' ORDER BY id_pesanan DESC";
    }
} else {
    // Query untuk mendapatkan data berdasarkan rentang tanggal jika tanggal dipilih
    if (has_access($allowed_admin)) {
        // Jika ada user_id, tambahkan filter berdasarkan user_id dan rentang tanggal
        if (!empty($user_id1)) {
            $query = "SELECT * FROM tb_pesanan WHERE user_id = '$user_id1' AND date BETWEEN '$dari' AND '$ke' ORDER BY id_pesanan DESC";
        } else {
            $query = "SELECT * FROM tb_pesanan WHERE date BETWEEN '$dari' AND '$ke' ORDER BY id_pesanan DESC";
        }
    }
    if (has_access($allowed_agen)) {
        $query = "SELECT * FROM tb_pesanan WHERE user_id = '$user1' AND date BETWEEN '$dari' AND '$ke' ORDER BY id_pesanan DESC";
    }
}

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($koneksi));
}

// Set header agar browser memahami ini adalah file CSV yang harus diunduh
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=DETAIL_INVOICE_' . ($dari ? $dari : 'all') . '_to_' . ($ke ? $ke : 'all') . '.csv');

// Buat file CSV
$output = fopen('php://output', 'w');

// Tulis header kolom CSV
fputcsv($output, array('No', 'Invoice', 'Tanggal', 'User ID', 'Total Item', 'Total Tagihan', 'Status', 'Pembayaran'));

// Tulis data ke file CSV
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, array(
        $no++,
        $row['invoice'],
        $row['date'],
        $row['user_id'],
        $row['total_item'],
        number_format($row['total_tagihan'], 0, ',', '.'),
        $row['status'],
        $row['pembayaran']
    ));
}

// Tutup output stream
fclose($output);
exit;
