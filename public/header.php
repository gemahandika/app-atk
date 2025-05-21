<?php
if (!isset($_SESSION['admin_username'])) {
    header("location:../login/login.php");
}
include '../../../app/config/koneksi.php';
include '../../../app/models/Hak_akses.php';

$user1 = $_SESSION['admin_username'];
$sql = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$user1'") or die(mysqli_error($koneksi));
$data1 = $sql->fetch_array();
$data2 = $data1["nama_user"];
include '../../../app/models/header_models.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>E - ATK</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="../../../app/assets/img/JNE.png" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="../../../app/assets/css/custom_style.css" rel="stylesheet">
    <link href="../../../app/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../app/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../../../app/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../../../app/assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../../../app/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../../../app/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../../../app/assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <!-- Template Main CSS File -->
    <link href="../../../app/assets/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <div class="logo d-flex align-items-center">
                <img src="../../../app/assets/img/JNE.png" alt="">
                <span class="d-lg-block">E - ATK </span>
            </div>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->
        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle " href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li><!-- End Search Icon-->
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="../../../app/assets/img/kurir.png" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?= $user1 ?></span>
                    </a><!-- End Profile Iamge Icon -->
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="../login/logout.php">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>
                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->
            </ul>
        </nav><!-- End Icons Navigation -->
    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">
            <?php if (has_access($allowed_user)) { ?>
                <li class="nav-item" style="border-bottom: 1px solid rgba(0, 0, 0, 0.3);">
                    <a class="nav-link collapsed" href="../dashboard/home.php">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->

                <?php
                $akses = $_SESSION['admin_akses'];

                if (in_array("admin", $akses) || in_array("super_admin", $akses)) {
                    $link_pesanan = "../form_pesanan/form_admin.php";
                } else {
                    $link_pesanan = "../form_pesanan/index.php";
                }
                ?>
                <li class="nav-item" style="border-bottom: 1px solid rgba(0, 0, 0, 0.3);">
                    <a class="nav-link collapsed" href="<?= $link_pesanan ?>">
                        <i class="ri-shopping-cart-2-line"></i>
                        <span>Buat Pesanan</span>
                    </a>
                </li><!-- End Buat pesanan Nav -->
                <?php if (has_access($allowed_admin)) { ?>
                    <li class="nav-item" style="border-bottom: 1px solid rgba(0, 0, 0, 0.3); position: relative;">
                        <a class="nav-link collapsed" href="../proses_pesanan/index.php">
                            <i class="ri-shopping-basket-line"></i>
                            <span>Proses Pesanan</span>
                            <?php if ($dikirim > 0): ?>
                                <span class="badge bg-danger position-absolute translate-middle-y end-0" style="top: 20px; "><?php echo $dikirim; ?></span>
                            <?php endif; ?>
                        </a>
                    </li><!-- End Prosesn Nav -->
            <?php }
            } ?>
            <?php if (has_access($allowed_pickup)) { ?>
                <li class="nav-item" style="border-bottom: 1px solid rgba(0, 0, 0, 0.3); position: relative;">
                    <a class="nav-link collapsed" href="../pickup/index.php">
                        <i class="bx bxs-truck"></i>
                        <span>Pickup</span>
                        <?php if ($diproses > 0): ?>
                            <span class="badge bg-danger position-absolute translate-middle-y end-0" style="top: 20px; "><?php echo $diproses; ?></span>
                        <?php endif; ?>
                    </a>
                </li><!-- EndPickup Nav -->
            <?php } ?>
            <?php if (has_access($allowed_user)) { ?>
                <li class="nav-item" style="border-bottom: 1px solid rgba(0, 0, 0, 0.3);  position: relative;">
                    <a class="nav-link collapsed" href="../diterima/index.php">
                        <i class="ri-hand-coin-line"></i>
                        <span>Terima Pesanan</span>
                        <?php if ($dipickup > 0): ?>
                            <span class="badge bg-danger position-absolute translate-middle-y end-0" style="top: 20px; "><?php echo $dipickup; ?></span>
                        <?php endif; ?>
                    </a>
                </li><!-- EndPickup Nav -->
            <?php } ?>
            <?php if (has_access($allowed_user)) { ?>
                <li class="nav-item" style="border-bottom: 1px solid rgba(0, 0, 0, 0.3);">
                    <a class="nav-link collapsed" href="../cek_pesanan/index.php">
                        <i class="bi bi-search"></i>
                        <span>Cek Pesanan</span>
                    </a>
                </li><!-- End Cek Pesanan Nav -->
            <?php } ?>
            <?php if (has_access($allowed_admin)) { ?>
                <li class="nav-item" style="border-bottom: 1px solid rgba(0, 0, 0, 0.3);">
                    <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-menu-button-wide"></i><span>Data Barang ATK</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="../data_barang/index.php">
                                <i class="ri-database-line"></i>
                                <span>List Data ATK</span>
                            </a>
                        </li><!-- End Cek Pesanan Nav -->
                    </ul>
                    <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="../data_barang/atk_masuk.php">
                                <i class="ri-database-line"></i>
                                <span>ATK Masuk</span>
                            </a>
                        </li><!-- End Cek Pesanan Nav -->
                    </ul>
                </li>
            <?php } ?>
            <?php if (has_access($allowed_user)) { ?>
                <li class="nav-item" style="border-bottom: 1px solid rgba(0, 0, 0, 0.3);">
                    <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                        <i class="ri-bar-chart-2-line"></i><span>Report</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        <li class="nav-item" style="border-bottom: 1px solid rgba(0, 0, 0, 0.3);">
                            <a class="nav-link collapsed" href="../report/detail_invoice.php">
                                <i class="ri-bar-chart-2-line"></i>
                                <span>Detail Invoice Pesanan</span>
                            </a>
                        </li>
                    </ul>
                    <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        <li class="nav-item" style="border-bottom: 1px solid rgba(0, 0, 0, 0.3);">
                            <a class="nav-link collapsed" href="../report/index.php">
                                <i class="ri-bar-chart-2-line"></i>
                                <span>Detail Items Pesanan</span>
                            </a>
                        </li>
                    </ul>
                </li><!-- End Report Nav -->
            <?php } ?>
            <?php if (has_access($allowed_super_admin)) { ?>
                <li class="nav-item" style="border-bottom: 1px solid rgba(0, 0, 0, 0.3);">
                    <a class="nav-link collapsed" href="../user_app/index.php">
                        <i class="ri-settings-4-line"></i>
                        <span>Setting</span>
                    </a>
                </li><!-- End Setting Nav -->
            <?php } ?>
        </ul>

    </aside><!-- End Sidebar-->