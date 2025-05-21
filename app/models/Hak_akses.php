<?php
include '../../../app/config/koneksi.php';
// Fungsi untuk memeriksa apakah pengguna memiliki akses
function has_access($roles)
{
    if (!isset($_SESSION['admin_akses'])) {
        return false; // Jika tidak ada akses sesi, kembalikan false
    }
    foreach ($roles as $role) {
        if (in_array($role, $_SESSION['admin_akses'])) {
            return true;
        }
    }
    return false;
}

// Daftar akses yang diperbolehkan
$allowed_super_admin = ["super_admin"];
$allowed_admin = ["super_admin", "admin"];
$allowed_user = ["super_admin", "admin", "user"];
$allowed_pickup = ["super_admin", "admin", "pickup"];




// Hak Akses Untuk Halaman Dashboard Home
$allowed_agen = ["user"];

// Ganti dengan salah satu variabel akses yang diinginkan, misalnya $allowed_admin
$allowed_roles = $allowed_admin;

if (!has_access($allowed_roles)) {
    $eror = "Ooopss!! Kamu Tidak Punya Akses";
} else {
    $eror = ""; // Kosongkan variabel error jika user memiliki akses
}

// Lanjutkan dengan proses lainnya jika diperlukan
