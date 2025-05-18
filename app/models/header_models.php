<?php
$sql1 = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status = 'DIKIRIM'") or die(mysqli_error($koneksi));
$dikirim = mysqli_num_rows($sql1);
