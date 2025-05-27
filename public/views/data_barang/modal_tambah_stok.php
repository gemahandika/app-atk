<?php
include '../../../app/config/koneksi.php';
$id_barang = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE id_barang='$id_barang'"));

if (!$data) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
    exit;
}

$date = date("Y-m-d");
?>

<div class="modal-header bg-success text-white">
    <h5 class="modal-title">Tambah Stok Barang</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="../../../app/controller/Data_barang.php" method="post">
    <div class="modal-body">
        <input type="hidden" name="id_barang" value="<?= $data['id_barang'] ?>" readonly required>
        <!-- Isi form sama seperti sebelumnya, sesuaikan dengan data yang diambil -->
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Tgl Request:</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="tgl_request<?= $data['id_barang'] ?>" name="tgl_request" value="<?= $date ?>" required>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Stok Awal:</label>
            <div class="col-sm-8">
                <!-- Gunakan id yang unik untuk setiap modal -->
                <input type="number" class="form-control" id="stok_awal<?= $data['id_barang'] ?>" name="stok_awal" value="<?= $data['stok_barang'] ?? 0 ?>" readonly required style="background-color: #fffde7;">
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Kode:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="kode_barang<?= $data['id_barang'] ?>" name="kode_barang" value="<?= $data['kode_barang'] ?>" readonly required style="background-color: #fffde7;">
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Nama :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="nama_barang<?= $data['id_barang'] ?>" name="nama_barang" value="<?= $data['nama_barang'] ?>" readonly required style="background-color: #fffde7;">
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Satuan :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="satuan<?= $data['id_barang'] ?>" name="satuan" value="<?= $data['satuan'] ?>" readonly required style="background-color: #fffde7;">
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Permintaan :</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" id="permintaan<?= $data['id_barang'] ?>" name="permintaan" placeholder="0" required>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Diterima:</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" id="tambah_stok<?= $data['id_barang'] ?>" name="tambah_stok" placeholder="0" required oninput="hitungTotal(<?= $data['id_barang'] ?>)">
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Tgl Diterima</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="tgl_terima<?= $data['id_barang'] ?>" name="tgl_terima" value="<?= $date ?>" required>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">No Connote :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="awb<?= $data['id_barang'] ?>" name="awb" required>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Keterangan :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="keterangan<?= $data['id_barang'] ?>" name="keterangan" required>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Total Stok :</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" id="total_stok<?= $data['id_barang'] ?>" name="total_stok" placeholder="0" readonly required style="background-color: #fffde7;">
            </div>
        </div>
        <!-- Lanjutkan semua input field lain... -->
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="add_stok" class="btn btn-success">Tambah Stok</button>
    </div>
</form>