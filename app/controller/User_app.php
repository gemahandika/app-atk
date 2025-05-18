<?php
require_once "../config/koneksi.php";
require_once "../assets/sweetalert/dist/func_sweetAlert.php";

if (isset($_POST['add'])) {
    $nip = trim(mysqli_real_escape_string($koneksi, $_POST['nip']));
    $userid = trim(mysqli_real_escape_string($koneksi, $_POST['user_id']));
    $password = trim(mysqli_real_escape_string($koneksi, $_POST['password']));
    $fullname = trim(mysqli_real_escape_string($koneksi, $_POST['fullname']));
    $cabang = trim(mysqli_real_escape_string($koneksi, $_POST['branch']));
    $unit = trim(mysqli_real_escape_string($koneksi, $_POST['unit']));
    $status = trim(mysqli_real_escape_string($koneksi, $_POST['status']));
    // Enkripsi password menggunakan MD5
    $password_md5 = md5($password);
    mysqli_query($koneksi, "INSERT INTO user (nik, username, password, cabang, unit, nama_user, status) 
    VALUES('$nip' , '$userid', '$password_md5', '$cabang', '$unit', '$fullname', '$status')");
    showSweetAlert('success', 'Sukses', $pesan_ok, '#3085d6', '../../public/views/user_app/aktivasi.php');
} else if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nik = trim(mysqli_real_escape_string($koneksi, $_POST['nik']));
    $fullname = trim(mysqli_real_escape_string($koneksi, $_POST['fullname']));
    $username = trim(mysqli_real_escape_string($koneksi, $_POST['username']));
    $cabang = trim(mysqli_real_escape_string($koneksi, $_POST['cabang']));
    $unit = trim(mysqli_real_escape_string($koneksi, $_POST['unit']));
    mysqli_query($koneksi, "UPDATE user SET nik='$nik', nama_user='$fullname', username='$username' , cabang='$cabang' , unit='$unit' 
    WHERE login_id='$id'");
    showSweetAlert('success', 'Success', $pesan_update, '#ffc107', '../../public/views/user_app/index.php');
} else if (isset($_POST['aktif_user'])) {
    $id = $_POST['id'];
    $password = $_POST['password'];
    $status = $_POST['role'];
    mysqli_query($koneksi, "UPDATE user SET status='$status' 
    WHERE login_id='$id'");
    $id = trim(mysqli_real_escape_string($koneksi, $_POST['id']));
    $akses = trim(mysqli_real_escape_string($koneksi, $_POST['role']));
    mysqli_query($koneksi, "INSERT INTO admin_akses (login_id, akses_id) VALUES('$id', '$akses')");
    showSweetAlert('success', 'Success', 'User Berhasil Di Aktifkan', '#3085d6', '../../public/views/user_app/aktivasi.php');
} else if (isset($_POST['nonaktif_user'])) {
    $id = $_POST['id'];
    // $password = $_POST['password'];
    $status = $_POST['role'];
    mysqli_query($koneksi, "UPDATE user SET status='$status' WHERE login_id='$id'");
    $sql = mysqli_query($koneksi, "DELETE FROM admin_akses WHERE login_id = '$id'") or die(mysqli_error($koneksi));
    showSweetAlert('success', 'Success', 'User Berhasil Di Non Aktifkan', '#dc3545', '../../public/views/user_app/aktivasi.php');
} else if (isset($_POST['reset'])) {
    $id = $_POST['id'];
    $password = $_POST['password'];
    mysqli_query($koneksi, "UPDATE user SET password=MD5('$password') WHERE login_id='$id'");
    showSweetAlert('success', 'Success', 'Password Berhasil Di Reset', '#3085d6', '../../public/views/user_app/');
} else if (isset($_POST['reset_profile'])) {
    $id = $_POST['id'];
    $password = $_POST['password_new'];
    mysqli_query($koneksi, "UPDATE user SET password=MD5('$password') WHERE login_id='$id'");
    showSweetAlert('success', 'Success', 'Password Berhasil Di Rubah', '#3085d6', '../../public/views/user_app/profile.php');
}
