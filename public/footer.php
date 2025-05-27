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

<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>

<!-- Tambahkan SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
            new DataTable('#example1', {
                paging: true,
                scrollCollapse: true,
                scrollY: '335px'
            });
        }

        if (document.querySelector('#example')) {
            new DataTable('#example', {
                paging: true,
                scrollCollapse: false
                // scrollY: '350px'
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

<!-- Script untuk ambil waktu sekarang saat tombol diklik -->
<script>
    document.getElementById('setTimeBtn').addEventListener('click', function() {
        const now = new Date();

        // Format: YYYY-MM-DD HH:mm:ss
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        const formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

        // Isi ke input
        document.getElementById('datetime').value = formattedDateTime;
    });
</script>



</body>

</html>