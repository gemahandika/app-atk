<?php
require_once "../config/koneksi.php";
require_once "../assets/sweetalert/dist/func_sweetAlert.php";
session_name("dashboard_atk_session");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['invoices'])) {
    $invoices = $_POST['invoices'];
    $updated = 0;
    $notFound = [];

    foreach ($invoices as $invoice) {
        $invoice = mysqli_real_escape_string($koneksi, trim($invoice));

        // Cek apakah invoice valid
        $cek = mysqli_query($koneksi, "SELECT invoice FROM tb_pesanan WHERE invoice = '$invoice'");
        if (mysqli_num_rows($cek) > 0) {
            // Jalankan dua query update
            $sql1 = "UPDATE tb_pesanan SET status = 'DIBAGGING', tgl_bag = NOW() WHERE invoice = '$invoice'";
            $sql2 = "UPDATE tb_keranjang SET status = 'DIBAGGING' WHERE invoice = '$invoice'";

            if (mysqli_query($koneksi, $sql1) && mysqli_query($koneksi, $sql2)) {
                $updated++;
            }
        } else {
            $notFound[] = $invoice;
        }
    }

    unset($_SESSION['invoices']);

    if (count($notFound) > 0) {
        $list = implode(', ', $notFound);
        showSweetAlert(
            'warning',
            'Sebagian Gagal',
            "Invoice berikut tidak ditemukan: <br><strong>$list</strong>",
            '#d33',
            '../../public/views/bagging/index.php'
        );
    } else {
        showSweetAlert(
            'success',
            'Berhasil',
            "$updated invoice berhasil DIBAGGING.",
            '#3085d6',
            '../../public/views/bagging/index.php'
        );
    }
} else {
    echo "<script>alert('Tidak ada data invoice yang dikirim.'); window.history.back();</script>";
}
