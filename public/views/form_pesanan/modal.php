<!-- Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchModalLabel">Search Dialog</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search input -->
                <input type="text" id="searchInput" class="form-control" placeholder="Search..." onkeyup="filterList()">

                <!-- List group for options -->
                <ul class="list-group mt-3" id="searchList">
                    <!-- This will be populated dynamically with items from the database -->
                    <?php
                    $sql = mysqli_query($koneksi, "SELECT * FROM user ORDER BY login_id ASC") or die(mysqli_error($koneksi));
                    $no_urut = 1; // Inisialisasi nomor urut
                    while ($data = mysqli_fetch_array($sql)) {
                        // Tampilkan nomor urut di depan username
                        echo '<li class="list-group-item" onclick="selectCustomer(\'' . $data['username'] . '\', \'' . $data['login_id'] . '\')" data-value="' . $data['login_id'] . '">' . $no_urut . '. ' . $data['username'] . '</li>';
                        $no_urut++; // Increment nomor urut
                    }
                    ?>

                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Filter the list based on search input
    function filterList() {
        var input = document.getElementById('searchInput');
        var filter = input.value.toUpperCase();
        var ul = document.getElementById("searchList");
        var li = ul.getElementsByTagName('li');

        // Loop through all list items, and hide those who don't match the search query
        for (var i = 0; i < li.length; i++) {
            var txtValue = li[i].textContent || li[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }

    // Function to handle item click
    function selectCustomer(customer) {
        // Set the clicked customer's value to the input field
        document.getElementById('customer').value = customer;

        // Close the modal after selection
        var modal = document.getElementById('searchModal');
        var modalInstance = bootstrap.Modal.getInstance(modal);
        modalInstance.hide();
    }
</script>