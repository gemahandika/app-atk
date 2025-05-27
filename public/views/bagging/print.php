<?php include '../../../app/config/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Print Request Barang ATK</title>
    <link href="../../../app/assets/img/favicon.png" rel="icon">
    <link href="../../../app/assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../app/assets/css/style_export.css">

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
    ?>

    <div class="container">
        <div class="barcode-header">
            <h2>Detail Request Barang ATK</h2>
            <svg id="barcode"></svg>
        </div>

        <table class="table table-bordered">
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
                $sql = mysqli_query($koneksi, "SELECT * FROM tb_keranjang WHERE invoice='$invoice' AND status='GENERATE'") or die(mysqli_error($koneksi));
                while ($data = mysqli_fetch_array($sql)) :
                ?>
                    <tr>
                        <td><?= $data['invoice'] ?></td>
                        <td><?= $data['nama_barang'] ?></td>
                        <td><?= $data['satuan'] ?></td>
                        <td><?= $data['jumlah'] ?></td>
                        <td><?= $data['nama_user'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <table class="table table-bordered signature-box mt-4">
            <thead>
                <tr>
                    <th>Distribusi</th>
                    <th>Tim Pickup</th>
                    <th>Diterima</th>
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