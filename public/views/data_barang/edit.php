<?php
session_name("dashboard_atk_session");
session_start();
if (!isset($_SESSION['admin_akses'])) {
    header("Location:../page_error/error.php");
    exit();
} else if (!in_array("super_admin", $_SESSION['admin_akses']) && !in_array("admin", $_SESSION['admin_akses'])) {
    header("Location:../page_error/error.php");
    exit();
} else {
    include '../../header.php';
    $id_barang = $_GET['id'];
    $sql = mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE id_barang = '$id_barang'") or die(mysqli_error($koneksi));
    $data = mysqli_fetch_array($sql);
?>

    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="border-bottom: 1px solid black;">Form Edit Barang</h5>
                            <!-- General Form Elements -->
                            <form action="../../../app/controller/Data_barang.php" method="post" id="formEditBarang">
                                <input type="hidden" name="id" value="<?= $data['id_barang'] ?>">
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-3 col-form-label">Kode Barang :</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="kode_barang" value="<?= $data['kode_barang'] ?>" style="text-transform: uppercase;">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Nama Barang :</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="nama_barang" value="<?= $data['nama_barang'] ?>" style="text-transform: uppercase;">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-3 col-form-label">Satuan :</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="satuan" value="<?= $data['satuan'] ?>" style="text-transform: uppercase;">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-3 col-form-label">Harga :</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="harga" id="harga" value="<?= number_format($data['harga'], 0, ',', '.') ?>" min="0" style="text-transform: uppercase;">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-3 col-form-label">Status Barang :</label>
                                    <div class="col-sm-8">
                                        <select class="form-select" aria-label="Default select example" name="status_barang">
                                            <option value="<?= $data['status_barang'] ?>"><?= $data['status_barang'] ?></option>
                                            <option value="HANYA GA">HANYA GA</option>
                                            <option value="KCU">KCU</option>
                                            <option value="AGEN DAN KCU">AGEN DAN KCU</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-3 col-form-label">Katagori :</label>
                                    <div class="col-sm-8">
                                        <select class="form-select" aria-label="Default select example" name="katagori" id="katagori" required>
                                            <option value="<?= $data['katagori'] ?>"><?= $data['katagori'] ?></option>
                                            <option value="GRATIS">GRATIS</option>
                                            <option value="BERBAYAR">BERBAYAR</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-3 col-form-label">Buffer :</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="buffer" value="<?= $data['buffer'] ?>" min="0" style="text-transform: uppercase;" placeholder="0">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-10">
                                        <button type="submit" name="edit_barang" class="btn btn-primary" id="btnUpdate">Update Data</button>
                                    </div>
                                </div>
                            </form><!-- End General Form Elements -->
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->


<?php
    include '../../footer.php';
}
?>