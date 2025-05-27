<?php
include '../../../app/config/koneksi.php';
?>
<html>

<head>
    <title>Print Request Barang ATK</title>
    <link href="../../../app/assets/img/favicon.png" rel="icon">
    <link href="../../../app/assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link rel="stylesheet" href="../../../app/assets/css/style_export.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>

<?php


// Ambil nomor invoice dari POST
$invoice = isset($_POST['invoice']) ? $_POST['invoice'] : '';

if ($invoice) {
    // Query untuk cek status pesanan berdasarkan invoice
    $query = "SELECT status FROM tb_pesanan WHERE invoice = '$invoice'";
    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $status = $row['status'];

        // Cek status pesanan
        if ($status === 'DIPESAN') {
            // Jika status DIKIRIM, kembali ke halaman sebelumnya
            echo "<script>alert('PESANAN BELUM DIGENERATE, SILAHKAN GENERATE TERLEBIH DULU.'); window.close();</script>";
            exit;
        } elseif ($status === 'GENERATE') {
            // Jika status GENERATE, halaman dimuat
            // Lanjutkan eksekusi script untuk export
            // Contoh: Export ke PDF atau lainnya
            // ...
        } else {
            // Jika status lain yang tidak sesuai
            echo "<script>alert('Status pesanan tidak valid.'); window.close();</script>";
            exit;
        }
    } else {
        // Jika tidak ada data yang ditemukan berdasarkan invoice
        echo "<script>alert('Invoice tidak ditemukan.'); window.close();</script>";
        exit;
    }
} else {
    // Jika invoice tidak ada dalam POST
    echo "<script>alert('Invoice tidak valid.'); window.close();</script>";
    exit;
}


?>

<body>
    <div class="container">
        <h2>Detail Request Barang ATK</h2>
        <div class="data-tables datatable-dark">
            <table id="mauexport" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>INVOICE</th>
                        <th>NAMA BARANG</th>
                        <th>SATUAN</th>
                        <th>JUMLAH</th>
                        <th>NAMA AGEN / KP</th>
                    </tr>

                </thead>
                <tbody>
                    <?php
                    $no = 0;
                    $sql = mysqli_query($koneksi, "SELECT * FROM tb_keranjang WHERE invoice='$invoice' AND status='GENERATE'") or die(mysqli_error($koneksi));
                    $result = array();
                    while ($data = mysqli_fetch_array($sql)) {
                        $result[] = $data;
                    }
                    foreach ($result as $data) {
                        $no++;
                    ?>
                        <tr>
                            <td><?= $data['invoice'] ?></td>
                            <td><?= $data['nama_barang'] ?></td>
                            <td><?= $data['satuan'] ?></td>
                            <td><?= $data['jumlah'] ?></td>
                            <td><?= $data['nama_user'] ?></td>

                        </tr>
                    <?php } ?>
            </table>
            <table id="mauexport" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Distribusi:</th>
                        <th>Tim Pickup:</th>
                        <th>Diterima:</th>
                    </tr>

                </thead>
                <tbody>
                    <tr>
                        <td>
                            <br>
                            <br>
                            <br>
                            (_________________)
                        </td>
                        <td>
                            <br>
                            <br>
                            <br>
                            (_________________)
                        </td>
                        <td>
                            <br>
                            <br>
                            <br>
                            ( <?= $data['nama_user'] ?> )
                        </td>
            </table>
        </div>
    </div>
    <script>
        function handlePrintClick(closeTab) {
            // Buka halaman baru
            var newTab = window.open('export.php?invoice=<?= $invoice ?>', '_blank');

            // Jika status DIKIRIM, tutup tab baru dan arahkan kembali ke generate_pesanan.php
            if (closeTab) {
                newTab.onload = function() {
                    // Setelah halaman baru dimuat, tutup tab tersebut dan arahkan kembali ke generate_pesanan.php
                    newTab.close();
                    window.location.href = 'generate_pesanan.php';
                };
            }
        }
    </script>

    <!-- <script>
    $(document).ready(function() {
        $('#mauexport').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy','csv','excel', 'pdf', 'print'
            ]
        } );
    } );
    </script> -->

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
</body>

</html>
<?php


?>