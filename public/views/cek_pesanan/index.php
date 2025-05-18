<?php
session_name("dashboard_atk_session");
session_start();
include '../../header.php';
include '../../../app/models/cek_pesanan_models.php';
?>

<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title" style="border-bottom: 1px solid black;">Cek Pesanan</h5>
                        <form action="" method="get">
                            <div class="row mb-3 d-flex align-items-center">
                                <label for="invoice" class="col-sm-2 col-form-label">Masukan No Invoice:</label>
                                <div class="col-sm-4 mt-2">
                                    <input type="text" class="form-control" id="invoice" name="invoice" required>
                                </div>
                                <div class="col-sm-4 mt-2">
                                    <button type="submit" class="btn btn-success">Lihat Pesanan</button>
                                    <a href="index.php" class="btn btn-secondary">Refresh</a>
                                </div>
                            </div>
                        </form>
                        <!-- Default Tabs -->
                        <div class="tab-content pt-2" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="table-secondary">
                                            <th class="small text-center">No Pesanan</th>
                                            <th class="small text-center">Tgl Pesan</th>
                                            <th class="small text-center">Tgl Proses</th>
                                            <th class="small text-center">Tgl Pickup</th>
                                            <th class="small text-center">Tgl Terima</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center small"><?= $invoice ?></td>
                                            <td class="text-center small"><?= $tgl_pesanan ?></td>
                                            <td class="text-center small"><?= $tgl_proses ?></td>
                                            <td class="text-center small"><?= $tgl_pickup ?></td>
                                            <td class="text-center small"><?= $tgl_terima ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- End Default Tabs -->

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detail Users & Invoice</h5>
                        <!-- Bordered Tabs -->
                        <div class="tab-content pt-2" id="borderedTabContent">
                            <div class="tab-pane fade show active" id="bordered-home" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="table-primary">
                                            <th class="small text-center">User Request</th>
                                            <th class="small text-center">User Proses</th>
                                            <th class="small text-center">Pickup Name</th>
                                            <th class="small text-center">Total Pesanan</th>
                                            <!-- <th class="small text-center">Invoice</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center small"><?= $user_id ?> </td>
                                            <td class="text-center small">MES GA</td>
                                            <td class="text-center small"><?= $nama_pickup ?></td>
                                            <td class="text-center small"><?= $total_item ?></td>
                                            <!-- <td class="text-center small">2016-05-25</td> -->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- End Bordered Tabs -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php
include '../../footer.php';
?>