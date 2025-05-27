<script>
    $(document).ready(function() {
        // Saat no_invoice berubah (misal scan selesai)
        $('#no_invoice').on('change', function() {
            var invoice = $(this).val().trim();
            if (invoice !== '') {
                $.ajax({
                    url: 'get_invoice_data.php',
                    method: 'POST',
                    data: {
                        no_invoice: invoice
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#nama_user').val(response.nama_user);
                            $('#total_items').val(response.total_items);
                        } else {
                            alert('Data invoice tidak ditemukan!');
                            window.location.href = window.location.href; // ⬅️ Refresh ke halaman ini
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        alert("Terjadi kesalahan saat mengambil data invoice.");
                        window.location.href = window.location.href; // ⬅️ Refresh ke halaman ini
                        $('#nama_user').val('');
                        $('#total_items').val('');
                    }
                });
            } else {
                // Jika input invoice dikosongkan, kosongkan field lain juga
                $('#nama_user').val('');
                $('#total_items').val('');
            }
        });

    });
</script>