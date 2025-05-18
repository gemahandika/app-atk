<?php
session_name("dashboard_atk_session");
session_start();
include '../../header.php';
include '../../../app/models/home_models.php';
?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <!-- <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol> -->
    </nav>
  </div><!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-12">
        <div class="row">

          <!-- Atk masuk -->
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
                              // Pastikan $data_keranjang sudah didefinisikan sebelumnya
                              echo $data_keranjang;
                            }

                            if (has_access($allowed_admin)) {
                              // Pastikan $dikirim sudah didefinisikan sebelumnya
                              echo $dikirim;
                            }
                            ?>
                        </h6>
                        </a>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Sales Card -->


          <!-- Proses -->
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
                            // Pastikan $data_keranjang sudah didefinisikan sebelumnya
                            echo $data_generate;
                          }

                          if (has_access($allowed_admin)) {
                            // Pastikan $dikirim sudah didefinisikan sebelumnya
                            echo $diproses;
                          }
                          ?></h6>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Revenue Card -->

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

<?php
include '../../footer.php';
?>