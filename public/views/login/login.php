<?php
session_name("dashboard_atk_session");
session_start();

// Jika sudah login, redirect ke halaman index
if (isset($_SESSION['admin_username'])) {
    header("location:../index.php");
    exit();
}

include '../../../app/config/koneksi.php';

$username = "";
$password = "";
$err = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi input kosong
    if (empty($username) || empty($password)) {
        $err = "Silahkan Masukan Username dan Password";
    } else {
        // Query untuk mendapatkan user berdasarkan username
        $sql1 = "SELECT * FROM user WHERE username = ?";
        $stmt1 = mysqli_prepare($koneksi, $sql1);

        if ($stmt1) {
            mysqli_stmt_bind_param($stmt1, "s", $username);
            mysqli_stmt_execute($stmt1);

            $result1 = mysqli_stmt_get_result($stmt1);

            // Validasi akun ditemukan dan password sesuai
            if ($result1->num_rows === 0) {
                $err = "Akun Anda Tidak ditemukan";
            } else {
                $row = $result1->fetch_assoc();

                // Periksa password menggunakan md5
                if ($row['password'] !== md5($password)) {
                    $err = "Password Anda Salah";
                } else {
                    // Query untuk mendapatkan akses user
                    $login_id = $row['login_id'];
                    $sql2 = "SELECT akses_id FROM admin_akses WHERE login_id = ?";
                    $stmt2 = mysqli_prepare($koneksi, $sql2);

                    if ($stmt2) {
                        mysqli_stmt_bind_param($stmt2, "s", $login_id);
                        mysqli_stmt_execute($stmt2);

                        $result2 = mysqli_stmt_get_result($stmt2);

                        $akses = [];
                        while ($row2 = $result2->fetch_assoc()) {
                            $akses[] = $row2['akses_id'];
                        }

                        // Validasi akses tidak kosong
                        if (empty($akses)) {
                            $err = "Kamu Tidak Punya Akses";
                        } else {
                            // Set session dan redirect
                            $_SESSION['admin_username'] = $username;
                            $_SESSION['admin_akses'] = $akses;
                            header("location:../index.php");
                            exit();
                        }
                    } else {
                        $err = "Kesalahan pada prepared statement 2";
                    }
                }
            }
        } else {
            $err = "Kesalahan pada prepared statement 1";
        }
    }
}
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
    <link href="../../../app/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../app/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../../../app/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../../../app/assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../../../app/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../../../app/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../../../app/assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="../../../app/assets/css/style.css" rel="stylesheet">
</head>

<body>

    <main>
        <div class="container">

            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <img src="../../../app/assets/img/JNE.png" alt="JNE Logo" width="90">
                                <span class="d-none d-lg-block fw-bold ms-3" style="font-size: 1.5rem; color: #012970;">E - ATK</span>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Login Account</h5>
                                        <p class="text-center small text-danger">
                                            <?php
                                            if ($err) {
                                                echo "$err";
                                            }
                                            ?>
                                        </p>
                                    </div>

                                    <form action="" method="post" class="row g-3 needs-validation" novalidate>

                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Username</label>
                                            <div class="input-group has-validation">
                                                <input type="text" name="username" class="form-control" id="yourUsername" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>" autofocus>
                                                <div class="invalid-feedback">Please enter your username.</div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourPassword" class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" id="yourPassword" required>
                                            <div class="invalid-feedback">Please enter your password!</div>
                                        </div>

                                        <!-- <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                                                <label class="form-check-label" for="rememberMe">Remember me</label>
                                            </div>
                                        </div> -->
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit" name="login">Login</button>
                                        </div>
                                        <!-- <div class="col-12">
                                            <p class="small mb-0">Don't have account? <a href="pages-register.html">Create an account</a></p>
                                        </div> -->
                                    </form>

                                </div>
                            </div>

                            <div class="credits">
                                <!-- All the links in the footer should remain intact. -->
                                <!-- You can delete the links only if you purchased the pro version. -->
                                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                                Copyright By <a target="_blank" href="https://www.jne.co.id/">@Mes_IT_Jne</a>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="../../../app/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="../../../app/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../app/assets/vendor/chart.js/chart.umd.js"></script>
    <script src="../../../app/assets/vendor/echarts/echarts.min.js"></script>
    <script src="../../../app/assets/vendor/quill/quill.js"></script>
    <script src="../../../app/assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="../../../app/assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="../../../app/assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="../../../app/assets/js/main.js"></script>

</body>

</html>