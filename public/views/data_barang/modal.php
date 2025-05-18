<div class="modal fade" id="basicModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="../../../app/controller/Data_barang.php" method="post">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Tambah Stok Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label class="col-form-label">Kode Barang:</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" name="kode_barang" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class=" col-form-label">Nama Barang:</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" name="nama_barang" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class=" col-form-label">Satuan:</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" name="satuan" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-form-label">Katagori :</label>
                        <div class="col-sm-12">
                            <select class="form-select" name="katagori" id="katagoriSelect" aria-label="Default select example" required onchange="toggleHarga()">
                                <option value="GRATIS">GRATIS</option>
                                <option value="BERBAYAR">BERBAYAR</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3" id="hargaRow" style="display: none;"> <!-- Set hidden by default -->
                        <label class="col-form-label">Harga :</label>
                        <div class="col-sm-12">
                            <input type="number" class="form-control" name="harga" value="0" placeholder="0">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class=" col-form-label">Status Barang :</label>
                        <div class="col-sm-12">
                            <select class="form-select" name="status_barang" aria-label="Default select example" required>
                                <option value="HANYA GA">HANYA GA</option>
                                <option value="KCU">KCU</option>
                                <option value="AGEN DAN KCU">AGEN DAN KCU</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class=" col-form-label">Stok :</label>
                        <div class="col-sm-12">
                            <input type="number" class="form-control" name="stok" placeholder="0" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add" class="btn btn-info text-white">Tambah Barang</button>
                </div>
            </form>
        </div>
    </div>
</div><!-- End Basic Modal-->

<script>
    function toggleHarga() {
        var katagori = document.getElementById('katagoriSelect').value;
        var hargaRow = document.getElementById('hargaRow');

        if (katagori === 'BERBAYAR') {
            hargaRow.style.display = 'block'; // Show the price input
        } else {
            hargaRow.style.display = 'none'; // Hide the price input
        }
    }

    // Run the function initially to set the correct visibility based on the default selection
    document.addEventListener('DOMContentLoaded', function() {
        toggleHarga();
    });
</script>