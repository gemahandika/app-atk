<?php
session_name("dashboard_atk_session");
session_start();
if (!in_array("super_admin", $_SESSION['admin_akses']) && !in_array("bag", $_SESSION['admin_akses'])) {
    header("Location:../page_error/error.php");
    exit();
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $_SESSION['invoices'] = []; // Reset invoice saat refresh halaman
    }
    if (!isset($_SESSION['invoices'])) {
        $_SESSION['invoices'] = [];
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete_invoice'])) {
            $delete = $_POST['delete_invoice'];
            $_SESSION['invoices'] = array_values(array_filter($_SESSION['invoices'], function ($inv) use ($delete) {
                return $inv !== $delete;
            }));
        } else {
            $invoice = trim($_POST['no_invoice']);
            if (!empty($invoice) && !in_array($invoice, $_SESSION['invoices'])) {
                $_SESSION['invoices'][] = $invoice;
            }
        }

        if (isset($_POST['ajax'])) {
            echo json_encode($_SESSION['invoices']);
            exit;
        }
    }

    include '../../header.php';

?>

    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="border-bottom: 1px solid black;">Form Scan Barcode Invoice</h5>
                            <form id="scanForm" method="POST" class="mb-4">
                                <div class="col-md-4 mb-3">
                                    <label for="no_invoice" class="form-label">Masukan No Invoice (scan barcode)</label>
                                    <input type="text" name="no_invoice" id="no_invoice" class="form-control" autofocus autocomplete="off">
                                </div>
                            </form>
                            <h5>Daftar Invoice</h5>
                            <table class="table table-bordered" id="invoiceTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Invoice</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['invoices'] as $index => $inv): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($inv) ?></td>
                                            <td><button class="btn btn-sm btn-danger delete-btn" data-invoice="<?= $inv ?>">Hapus</button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <form method="POST" action="../../../app/controller/Bagging.php" id="submitForm">
                                <div id="hiddenInputsContainer"></div>
                                <button type="submit" class="btn btn-success mt-3">SUBMIT</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main><!-- End #main -->

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

    <script>
        const form = document.getElementById('scanForm');
        const input = document.getElementById('no_invoice');
        const tableBody = document.querySelector('#invoiceTable tbody');

        // Fungsi render ulang tabel
        function renderTable(data) {
            tableBody.innerHTML = '';
            data.forEach((inv, idx) => {
                tableBody.innerHTML += `
                <tr>
                    <td>${idx + 1}</td>
                    <td>${inv}</td>
                    <td>
                        <button class="btn btn-sm btn-danger delete-btn" data-invoice="${inv}">Hapus</button>
                    </td>
                </tr>
            `;
            });

            // Tambahkan event listener untuk tombol hapus
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const invoiceToDelete = this.getAttribute('data-invoice');
                    const formData = new FormData();
                    formData.append('delete_invoice', invoiceToDelete);
                    formData.append('ajax', '1');

                    fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => renderTable(data))
                        .catch(err => console.error('Error:', err));
                });
            });
        }

        // Submit form untuk menambahkan invoice
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const invoice = input.value.trim();
            if (invoice === '') return;

            const formData = new FormData(form);
            formData.append('ajax', '1');

            fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(res => {
                    if (!res.ok) throw new Error("HTTP error " + res.status);
                    return res.json();
                })
                .then(data => {
                    renderTable(data);
                    input.value = '';
                    input.focus();
                })
                .catch(err => console.error('Error:', err));
        });

        // Submit saat tekan enter di field input
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                form.dispatchEvent(new Event('submit'));
            }
        });

        const submitForm = document.getElementById('submitForm');
        const hiddenInputsContainer = document.getElementById('hiddenInputsContainer');

        submitForm.addEventListener('submit', function(e) {
            // Hapus semua input lama
            hiddenInputsContainer.innerHTML = '';

            // Ambil semua invoice yang sedang ditampilkan di tabel
            const invoices = [];
            document.querySelectorAll('#invoiceTable tbody tr').forEach(row => {
                const invoice = row.children[1].textContent.trim();
                if (invoice) invoices.push(invoice);
            });

            // Tambahkan input hidden untuk tiap invoice
            invoices.forEach(inv => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'invoices[]';
                input.value = inv;
                hiddenInputsContainer.appendChild(input);
            });
        });
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

<?php } ?>
</body>

</html>