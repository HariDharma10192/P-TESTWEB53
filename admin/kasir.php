<?php
include 'header/navbar.php';

// Fungsi pencarian
if (isset($_POST['search'])) {
    $keyword = $_POST['keyword'];
    // Query untuk mencari berdasarkan nama atau bara
    $sql = "SELECT BARA, NAMA, HBELI, HJUAL FROM mstock WHERE NAMA LIKE '%$keyword%' OR BARA LIKE '%$keyword%'";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error: " . $conn->error);
    }
} else {
    // Query untuk menampilkan semua data jika tidak ada pencarian
    $sql = "SELECT BARA, NAMA, HBELI, HJUAL FROM mstock";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error: " . $conn->error);
    }
}


include 'header/sidebar.php';
?>

<div class="content-body">
    <style>
        .harga-beli-tooltip {
            position: relative;
        }

        .harga-beli-tooltip:hover::after {
            content: attr(data-hbeli);
            /* Menampilkan nilai atribut data-hbeli sebagai konten tooltip */
            position: absolute;
            background-color: #f9f9f9;
            color: #333;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
            z-index: 1;
            display: block;
        }
    </style>
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!<h4 class="card-title mt-3">Tabel Kasiran</h4>
                            <table class="table table-sm table-striped   table-bordered " id="kasirTable" style="max-height: 20%;">
                                <!-- Isi tabel -->
                                <thead>
                                    <tr>
                                        <th>BARA</th>
                                        <th>NAMA</th>
                                        <!-- <th>HBELI</th> -->
                                        <th>HJUAL</th>
                                        <th>Jumlah</th>
                                        <th>Total</th>
                                        <th>Aksi</th> <!-- Kolom untuk tombol hapus item -->
                                    </tr>
                                </thead>
                                <tbody id="kasirTableBody">
                                    <!-- Tempat untuk menampilkan item yang ditambahkan ke tabel kasiran -->
                                </tbody>
                            </table>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <label for="jumlahUang">Jumlah Uang Pelanggan:</label>
                                    <input type='number' id='jumlahUang' class='form-control' oninput='hitungKembalian()' required>

                                </div>
                            </div>

                            <!-- Total Belanja -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h4>Total Belanja:</h4>
                                    <span id="totalBelanja">Rp 0</span>
                                </div>
                            </div>
                            <!-- Tombol Refresh Total Belanja -->
                            <!-- <div class="row mt-3">
                                <div class="col-12">
                                    <button class="btn btn-warning btn-sm" onclick="refreshTotalBelanja()">Refresh Total Belanja</button>
                                </div>
                            </div> -->

                            <!-- Kembalian -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h4>Kembalian:</h4>
                                    <span id="kembalian" style="color: black;">Rp 0</span>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button id="bayarButton" class="btn btn-success" onclick="prepareAndRedirect()">Bayar</button>
                                </div>
                            </div>




                            <hr>
                            <hr>

                            <h4 class="card-title mt-3">Data Table MStock</h4>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>BARA</th>
                                            <th>NAMA</th>
                                            <th class="harga-beli-tooltip">HBELI</th>
                                            <th>HJUAL</th>
                                            <th>Aksi</th> <!-- Kolom untuk tombol cetak struk -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Tampilkan data dari hasil query
                                        // Tampilkan data dari hasil query
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['BARA'] . "</td>";
                                            echo "<td>" . $row['NAMA'] . "</td>";
                                            echo "<td class='harga-beli-tooltip' data-hbeli='" . $row['HBELI'] . "'></td>";
                                            echo "<td>" . $row['HJUAL'] . "</td>";
                                            echo "<td><button class='btn btn-primary btn-sm' onclick='pilihItem(\"" . $row['BARA'] . "\", \"" . $row['NAMA'] . "\", \"" . $row['HBELI'] . "\", \"" . $row['HJUAL'] . "\")'>Pilih</button></td>";
                                            echo "</tr>";
                                        }

                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>BARA</th>
                                            <th>NAMA</th>
                                            <th class="harga-beli-tooltip" data-hbeli="Harga Beli">HBELI</th>
                                            <th>HJUAL</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function prepareAndRedirect() {
        // Gather data from DataTable
        var table = document.getElementById('kasirTable');
        var rows = table.getElementsByTagName('tr');
        var dataToSend = [];

        // Loop through rows (skip header row)
        for (var i = 1; i < rows.length; i++) {
            var cells = rows[i].getElementsByTagName('td');
            var rowData = {
                bara: cells[0].innerText,
                nama: cells[1].innerText,
                hjual: cells[2].innerText,
                jumlah: cells[3].querySelector('input').value,
                total: cells[4].innerText,


                // Add more properties as needed
            };
            dataToSend.push(rowData);
        }

        // Gather other relevant values
        var totalBelanja = document.getElementById('totalBelanja').innerText;
        var jumlahUang = document.getElementById('jumlahUang').value;
        var kembalian = document.getElementById('kembalian').innerText;

        // Convert data to JSON and encode for URL
        var jsonData = encodeURIComponent(JSON.stringify(dataToSend));

        // Redirect to bayar.php with data and other values as query parameters
        window.location.href = 'bayar.php?data=' + jsonData + '&totalBelanja=' + totalBelanja + '&jumlahUang=' + jumlahUang + '&kembalian=' + kembalian;
    }
</script>


<script src="js/kasir.js"></script>
<?php include 'footer/footer.php'; ?>