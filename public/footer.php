<!-- ======= Footer ======= -->

<!-- Vendor JS Files -->
<script src="../../../app/assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="../../../app/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../../app/assets/vendor/chart.js/chart.umd.js"></script>
<script src="../../../app/assets/vendor/echarts/echarts.min.js"></script>
<script src="../../../app/assets/vendor/quill/quill.js"></script>
<script src="../../../app/assets/vendor/tinymce/tinymce.min.js"></script>
<script src="../../../app/assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="../../../app/assets/js/main.js"></script>

<!-- CDN JS Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>

<!-- Tambahkan SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- barang terima -->
<script>
    function hitungTotal(id_keranjang) {
        var jumlah = document.getElementById('jumlah_' + id_keranjang).value;
        var harga = document.getElementById('harga_' + id_keranjang).value;
        var total_harga = jumlah * harga;

        // Tampilkan hasil di input total_harga
        document.getElementById('total_harga_' + id_keranjang).value = total_harga;

        // Update total tagihan setelah perubahan
        updateTotalTagihan();
    }

    function updateTotalTagihan() {
        var totalTagihan = 0;

        <?php foreach ($result1 as $data1) { ?>
            var totalHarga = document.getElementById('total_harga_<?= $data1['id_keranjang'] ?>').value;
            totalTagihan += parseFloat(totalHarga);
        <?php } ?>

        // Tampilkan total tagihan di input total_tagihan
        document.getElementById('total_tagihan').value = totalTagihan;
    }
</script>
<!-- ------------------------------- -->
<!-- Edit Data Barang -->
<script>
    $(document).ready(function() {
        $('#formEditBarang').on('submit', function(event) {
            var katagori = $('#katagori').val();
            var harga = parseInt($('#harga').val().replace(/\./g, ''));

            if (katagori === 'BERBAYAR' && (isNaN(harga) || harga === 0)) {
                // Jika kategori 'BERBAYAR' dan harga kosong atau 0, tampilkan notifikasi SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Harga tidak boleh kosong atau 0 untuk kategori BERBAYAR!',
                    confirmButtonText: 'OK'
                });
                event.preventDefault(); // Batalkan submit form
            }
        });
    });
</script>

<!-- Select2 Initialization -->
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: '-- Pilih Data --',
            allowClear: true
        });
    });
</script>

<!-- Dynamic Dropdown and Item Handling -->
<script>
    $(document).ready(function() {
        var allItems = <?php echo json_encode($result); ?>;

        $('#katagori').change(function() {
            var selectedCategory = $(this).val().toLowerCase();
            var filteredItems = allItems.filter(item => item.katagori.toLowerCase() === selectedCategory);

            $('#namaBarang').empty().append('<option value="">- Pilih Barang -</option>');
            filteredItems.forEach(item => {
                $('#namaBarang').append(`<option value="${item.nama_barang}">${item.nama_barang} - Rp. ${parseInt(item.harga).toLocaleString('id-ID')}</option>`);
            });
        });

        $('#namaBarang').change(function() {
            var selectedItemName = $(this).val().split(' - ')[0];
            var selectedItem = allItems.find(item => item.nama_barang === selectedItemName);

            if (selectedItem) {
                $('#kode_barang').val(selectedItem.kode_barang);
                $('#satuan').val(selectedItem.satuan);
                $('#harga').val(selectedItem.harga);
                calculateTotalHarga();
            } else {
                $('#kode_barang, #satuan, #harga').val('');
                $('#total_harga').val(0);
            }
        });

        $('#jumlah').on('input', calculateTotalHarga);

        function calculateTotalHarga() {
            var harga = parseFloat($('#harga').val()) || 0;
            var jumlah = parseInt($('#jumlah').val()) || 0;
            $('#total_harga').val(harga * jumlah);
        }
    });
</script>

<!-- Kirim Pesanan Toggle Button -->
<script>
    $(document).ready(function() {
        function checkTotalItems() {
            var totalItems = parseInt($('#total_items').val()) || 0;
            $('#kirim_pesanan').prop('disabled', totalItems === 0)
                .attr('title', totalItems === 0 ? 'Silahkan masukkan pesanan ke list' : '');
        }

        checkTotalItems();
        $('#total_items').on('input', checkTotalItems);
    });
</script>

<!-- Pembayaran Otomatis -->
<script>
    function updatePembayaran() {
        var totalTagihan = document.getElementById('totalTagihan').value;
        document.getElementById('pembayaran').value = totalTagihan > 0 ? 'OTS' : 'DONE';
    }

    window.onload = updatePembayaran;
</script>

<!-- Inisialisasi DataTables -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('#example1')) {
            new DataTable('#example1');
        }

        if (document.querySelector('#example')) {
            new DataTable('#example', {
                paging: false,
                scrollCollapse: true,
                scrollY: '335px'
            });
        }
    });
</script>

<!-- Export Button Initialization -->
<script>
    $(document).ready(function() {
        $('#mauexport').DataTable({
            dom: 'Blfrtip',
            buttons: [{
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Print',
                    className: 'btn btn-outline-secondary mb-3',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                    className: 'btn btn-outline-info mb-3',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ],
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            pageLength: 10
        });
    });

    function exportToExcel(tableID) {
        $('#' + tableID).DataTable().button('.buttons-excel').trigger();
    }

    function exportToPDF(tableID) {
        $('#' + tableID).DataTable().button('.buttons-pdf').trigger();
    }

    function exportToPrint(tableID) {
        $('#' + tableID).DataTable().button('.buttons-print').trigger();
    }
</script>

</body>

</html>