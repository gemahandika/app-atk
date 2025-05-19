<!-- SweetAlert CSS -->
<link rel="stylesheet" href="../../../app/assets/sweetalert/dist/sweetalert2.min.css">

<!-- SweetAlert JS -->
<script src="../../../app/assets/sweetalert/dist/sweetalert2.all.min.js"></script>
<?php

$tujuan_index = "index.php";
function showSweetAlert($icon, $title, $text, $confirmButtonColor, $tujuan)
{
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '$icon',
                title: '$title',
                text: '$text',
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

include '../../../app/config/koneksi.php';
mysqli_query($koneksi, "DELETE FROM user WHERE login_id= '$_GET[id]'") or die(mysqli_error($koneksi));
showSweetAlert('success', 'Success', 'Data Berhasil di Hapus', '#dc3545', 'aktivasi.php');
