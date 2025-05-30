<?php include '../../../app/config/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Print Request Barang ATK</title>
    <link href="../../../app/assets/img/JNE.png" rel="icon">
    <link href="../../../app/assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">

    <!-- Custom Print Styling -->
    <style>
        @media print {
            body {
                font-size: 12pt;
            }

            .btn,
            .dataTables_wrapper,
            .buttons-html5,
            .no-print {
                display: none !important;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            table th,
            table td {
                border: 1px solid #000;
                padding: 8px;
                text-align: center;
            }

            h2,
            svg {
                text-align: center;
                display: block;
                margin: auto;
            }
        }

        .barcode-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 30px 0;
        }

        .barcode-header h2 {
            margin: 0;
            font-weight: bold;
        }

        .signature-box td {
            height: 100px;
            vertical-align: bottom;
            text-align: center;
        }

        .table th {
            background-color: #f8f9fa;
        }
    </style>

    <!-- JsBarcode -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</head>

<body>
    <?php
    $invoice = isset($_POST['invoice']) ? $_POST['invoice'] : '';

    if ($invoice) {
        $query = "SELECT status FROM tb_pesanan WHERE invoice = '$invoice'";
        $result = mysqli_query($koneksi, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $status = $row['status'];

            if ($status === 'DIPESAN') {
                echo "<script>alert('PESANAN BELUM DIGENERATE, SILAHKAN GENERATE TERLEBIH DULU.'); window.close();</script>";
                exit;
            } elseif ($status !== 'GENERATE') {
                echo "<script>alert('Status pesanan tidak valid.'); window.close();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Invoice tidak ditemukan.'); window.close();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Invoice tidak valid.'); window.close();</script>";
        exit;
    }

    // Ambil tgl_pesan dari tb_pesanan
    $tgl_pesan_result = mysqli_query($koneksi, "SELECT date FROM tb_pesanan WHERE invoice = '$invoice' LIMIT 1");
    $tgl_pesan = '';
    if ($tgl_pesan_result && mysqli_num_rows($tgl_pesan_result) > 0) {
        $row = mysqli_fetch_assoc($tgl_pesan_result);
        $tgl_datetime = strtotime($row['date']);
        $tgl_pesan = date('d M Y | H:i', $tgl_datetime) . ' WIB';
    }
    ?>



    <div class="container">
        <div class="barcode-header">
            <h2>Detail Request Barang ATK</h2>
            <svg id="barcode"></svg>
        </div>
        <p><strong>Tanggal Pemesanan:</strong> <?= $tgl_pesan ?></p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>INVOICE</th>
                    <th>NAMA BARANG</th>
                    <th>SATUAN</th>
                    <th>JUMLAH</th>
                    <th>TOTAL HARGA</th>
                    <th>NAMA AGEN / KP</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = mysqli_query($koneksi, "SELECT * FROM tb_keranjang WHERE invoice='$invoice' AND status='GENERATE'") or die(mysqli_error($koneksi));
                $grand_total = 0;
                while ($data = mysqli_fetch_array($sql)) :
                    $grand_total += $data['total_harga'];
                ?>
                    <tr>
                        <td><?= $data['invoice'] ?></td>
                        <td><?= $data['nama_barang'] ?></td>
                        <td><?= $data['satuan'] ?></td>
                        <td><?= $data['jumlah'] ?></td>
                        <td><?= number_format($data['total_harga'], 0, ',', '.') ?></td>
                        <td><?= $data['nama_user'] ?></td>
                    </tr>
                <?php endwhile; ?>
                <!-- Tambahkan baris untuk Grand Total -->
                <tr>
                    <td colspan="4" class="text-right font-weight-bold">TOTAL TAGIHAN</td>
                    <td class="font-weight-bold">Rp. <?= number_format($grand_total, 0, ',', '.') ?></td>
                    <td></td>
                </tr>
            </tbody>

        </table>

        <table class="table table-bordered signature-box mt-4">
            <thead>
                <tr>
                    <th class="text-center">Distribusi</th>
                    <th class="text-center">Tim Pickup</th>
                    <th class="text-center">Diterima</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>(_________________)</td>
                    <td>(_________________)</td>
                    <td>(_________________)</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Optional: Auto Print -->
    <script>
        JsBarcode("#barcode", "<?= $invoice ?>", {
            format: "CODE128",
            lineColor: "#000",
            width: 4,
            height: 60,
            displayValue: true
        });

        window.onload = function() {
            window.print();
        };
    </script>

    <!-- Bootstrap & jQuery (optional if needed) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>