<?php
session_name("dashboard_atk_session");
session_start();
include '../../header.php';
include '../../../app/models/home_models.php';
?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Dashboard</h1>
  </div>

  <section class="section dashboard">
    <div class="row">
      <div class="col-lg-12">
        <div class="row">
          <div class="col-xxl-3 col-md-6">
            <div class="card info-card sales-card">
              <div class="card-body">
                <h5 class="card-title" style="border-bottom: 4px solid blue;"><?php if (has_access($allowed_admin)) { ?> ATK Masuk <?php } ?> <?php if (has_access($allowed_agen)) { ?> Pesanan <?php } ?> </h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-cart"></i>
                  </div>
                  <div class="ps-3">
                    <?php
                    if (has_access($allowed_admin)) { ?>
                      <a href="../proses_pesanan/index.php">
                      <?php  }
                    if (has_access($allowed_agen)) { ?>
                        <a href="../form_pesanan/list_data.php">
                        <?php  }
                        ?>
                        <h6><?php
                            if (has_access($allowed_agen)) {
                              echo $data_keranjang;
                            }
                            if (has_access($allowed_admin)) {
                              echo $dikirim;
                            }
                            ?>
                        </h6>
                        </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xxl-3 col-md-6">
            <div class="card info-card revenue-card">
              <div class="card-body">
                <h5 class="card-title" style="border-bottom: 4px solid green;">ATK Diproses</h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="ri-draft-line"></i>
                  </div>
                  <div class="ps-3">
                    <a href="../proses_pesanan/list_data.php">
                      <h6><?php
                          if (has_access($allowed_agen)) {
                            echo $data_generate;
                          }
                          if (has_access($allowed_admin)) {
                            echo $diproses;
                          }
                          ?></h6>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xxl-3 col-md-6">
            <div class="card info-card customers-card">
              <div class="card-body">
                <h5 class="card-title" style="border-bottom: 4px solid orange;">ATK Dibagging</h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="ri-box-3-line"></i>
                  </div>
                  <div class="ps-3">
                    <a href="../bagging/list_data_bag.php">
                      <h6><?php
                          if (has_access($allowed_agen)) {
                            echo $data_bagging;
                          }
                          if (has_access($allowed_admin)) {
                            echo $dibagging;
                          }
                          ?></h6>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Pickup -->
          <div class="col-xxl-3 col-xl-12">
            <div class="card info-card customers-card">
              <div class="card-body">
                <h5 class="card-title" style="border-bottom: 4px solid orange;">ATK Dipickup</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bx bxs-truck"></i>
                  </div>
                  <div class="ps-3">
                    <a href="../pickup/list_data.php">
                      <h6><?php
                          if (has_access($allowed_agen)) {
                            // Pastikan $data_keranjang sudah didefinisikan sebelumnya
                            echo $data_pickup;
                          }

                          if (has_access($allowed_admin)) {
                            // Pastikan $dikirim sudah didefinisikan sebelumnya
                            echo $dipickup;
                          }
                          ?></h6>
                      </h6>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Pickup -->

          <!-- Customers Card -->
          <div class="col-xxl-3 col-xl-12">
            <div class="card info-card sales-card">
              <div class="card-body">
                <h5 class="card-title" style="border-bottom: 4px solid red;">ATK Diterima</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="ri-hand-coin-line"></i>
                  </div>
                  <div class="ps-3">
                    <a href="../diterima/list_data.php">
                      <h6><?php
                          if (has_access($allowed_agen)) {
                            // Pastikan $data_keranjang sudah didefinisikan sebelumnya
                            echo $data_terima;
                          }

                          if (has_access($allowed_admin)) {
                            // Pastikan $dikirim sudah didefinisikan sebelumnya
                            echo $diterima;
                          }
                          ?></h6>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End DI terima -->

          <!-- Total Belanja -->
          <div class="col-xxl-3 col-xl-12">
            <div class="card info-card sales-card">
              <div class="card-body">
                <h5 class="card-title" style="border-bottom: 4px solid black;">Total Invoice</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6>INV</h6>
                  </div>
                  <div class="ps-3">
                    <a href="../report/detail_invoice.php">
                      <h6><?php
                          if (has_access($allowed_agen)) {
                            // Pastikan $data_keranjang sudah didefinisikan sebelumnya
                            echo number_format($total_invoice_user, 0, ',', '.');
                          }

                          if (has_access($allowed_admin)) {
                            // Pastikan $dikirim sudah didefinisikan sebelumnya
                            echo number_format($total_invoice_semua, 0, ',', '.');
                          }
                          ?></h6>

                    </a>
                  </div>
                </div>
                <!-- <div class="text-center">
                  <span class="text-danger small pt-1 fw-bold ">
                    <?php
                    if (has_access($allowed_agen)) {
                      echo $total_keranjang_user;
                    }
                    if (has_access($allowed_admin)) {
                      echo $total_keranjang_semua;
                    }
                    ?></span> <span class="text-muted small pt-2 ps-1">Items</span>
                </div> -->
              </div>
            </div>
          </div><!-- End Belanja-->

          <!-- Total Invoice -->
          <div class="col-xxl-3 col-xl-12">
            <div class="card info-card sales-card">
              <div class="card-body">
                <h5 class="card-title" style="border-bottom: 4px solid black;">Total Belanja</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6>Rp.</h6>
                  </div>
                  <div class="ps-3">
                    <a href="../report/detail_invoice.php">
                      <h6><?php
                          if (has_access($allowed_agen)) {
                            // Pastikan $data_keranjang sudah didefinisikan sebelumnya
                            echo number_format($total_tagihan_user, 0, ',', '.');
                          }

                          if (has_access($allowed_admin)) {
                            // Pastikan $dikirim sudah didefinisikan sebelumnya
                            echo number_format($total_tagihan_semua, 0, ',', '.');
                          }
                          ?></h6>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Invoice -->

          <!-- Customers Card -->
          <div class="col-xxl-3 col-xl-12">
            <div class="card info-card sales-card">
              <div class="card-body">
                <h5 class="card-title" style="border-bottom: 4px solid red;">Total OTS</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6>Rp.</h6>
                  </div>
                  <div class="ps-3">
                    <a href="../report/detail_invoice.php">
                      <h6><?php
                          if (has_access($allowed_agen)) {
                            // Pastikan $data_keranjang sudah didefinisikan sebelumnya
                            echo number_format($total_ots_user, 0, ',', '.');
                          }

                          if (has_access($allowed_admin)) {
                            // Pastikan $dikirim sudah didefinisikan sebelumnya
                            echo number_format($total_ots_semua, 0, ',', '.');
                          }
                          ?></h6>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End DI terima -->

        </div>
      </div><!-- End Left side columns -->
    </div>
  </section>
</main><!-- End #main -->
<!-- ======= Footer ======= -->
<!-- Vendor JS Files -->
<script src="../../../app/assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="../../../app/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../../app/assets/vendor/chart.js/chart.umd.js"></script>
<script src="../../../app/assets/vendor/echarts/echarts.min.js"></script>
<script src="../../../app/assets/vendor/quill/quill.js"></script>
<script src="../../../app/assets/vendor/tinymce/tinymce.min.js"></script>
<script src="../../../app/assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="../../../app/assets/js/main.js"></script>

<!-- CDN JS Libraries -->

<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>

<!-- Tambahkan SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>