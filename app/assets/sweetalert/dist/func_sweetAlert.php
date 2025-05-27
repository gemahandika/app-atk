<!-- SweetAlert CSS -->
<link rel="stylesheet" href="../assets/sweetalert/dist/sweetalert2.min.css">

<!-- SweetAlert JS -->
<script src="../assets/sweetalert/dist/sweetalert2.all.min.js"></script>
<?php
$pesan = "Mohon maaf! Data sudah ada.";
$pesan_ok = "Data Berhasil di Tambah.";
$pesan_update = "Data Berhasil di Update.";
$pesan_destroy = "Data Berhasil di Destroy.";
$tujuan = "../../public/views/asset/index.php";
$tujuan_maintenance = "../../public/views/maintenance/add_maintenance.php";
$destroy = "../../public/views/asset/destroy.php";
$tujuan_3 = "../../public/views/data_anggota/index_nonaktif.php";
function showSweetAlert($icon, $title, $htmlContent, $confirmButtonColor, $tujuan)
{
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '$icon',
                title: '$title',
                html: `$htmlContent`, // ✅ gunakan html, bukan text
                confirmButtonColor: '$confirmButtonColor',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = '$tujuan';
                }
            });
        });
    </script>";
}
