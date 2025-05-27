<?php
header('Content-Type: application/json');
include '../../../app/config/koneksi.php';

// Cek apakah no_invoice dikirim
if (!isset($_POST['no_invoice'])) {
    echo json_encode(['success' => false, 'message' => 'No invoice not provided']);
    exit();
}

$invoice = $_POST['no_invoice'];
$result = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE invoice = '$invoice' AND status = 'DIBAGGING'");
$data = mysqli_fetch_assoc($result);

if ($data) {
    echo json_encode([
        'success' => true,
        'nama_user' => $data['nama_user'],
        'total_items' => $data['total_item']
    ]);
} else {

    echo json_encode(['success' => false, 'message' => 'Invoice not found']);
}
