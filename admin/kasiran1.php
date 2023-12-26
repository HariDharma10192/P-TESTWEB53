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
                                    <button class="btn btn-success" onclick="bayar()">Bayar</button>
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
    function pilihItem(bara, nama, hbeli, hjual) {
        // Buat baris baru pada tabel kasiran
        var kasirTableBody = document.getElementById('kasirTableBody');
        var newRow = kasirTableBody.insertRow();

        var cellBara = newRow.insertCell(0);
        var cellNama = newRow.insertCell(1);
        var cellHjual = newRow.insertCell(2);
        var cellJumlah = newRow.insertCell(3);
        var cellTotal = newRow.insertCell(4);
        var cellRemove = newRow.insertCell(5);

        cellBara.innerHTML = bara;
        cellNama.innerHTML = nama;
        // cellHbeli.innerHTML = formatRupiah(hbeli);
        cellHjual.innerHTML = formatRupiah(hjual);

        cellJumlah.innerHTML = "<input type='number' min='1' max='100' value='1' class='form-control' onchange='updateTotal(this)'>";

        // Panggil updateTotal secara otomatis setelah menambahkan item ke tabel kasiran
        updateTotal(cellJumlah.querySelector('input'));

        cellRemove.innerHTML = "<button class='btn btn-danger btn-sm'  onclick='removeItemFromKasir(this)'>Remove</button>";
        updateTotal();
        hitungKembalian();

        cellRemove.innerHTML = "<button class='btn btn-danger btn-sm'  onclick='removeItemFromKasir(this)'>Remove</button>";
        hitungKembalian();

    }

    function updateTotal(input) {
        var row = input.parentNode.parentNode;
        var hargaJual = row.cells[2].innerText.replace(/[^\d]/g, '');
        var jumlah = parseInt(input.value);

        // Jika jumlah menjadi 0, hapus baris dari tabel kasiran
        if (jumlah === 0) {
            removeItemFromKasir(row);
            return;
        }

        var total = hargaJual * jumlah;
        row.cells[4].innerText = ' ' + formatRupiah(total);

        // Hitung total belanja
        var totalBelanja = 0;
        var kasirTableBody = document.getElementById('kasirTableBody');
        var rows = kasirTableBody.getElementsByTagName('tr');

        for (var i = 0; i < rows.length; i++) {
            var hargaJual = rows[i].cells[2].innerText.replace(/[^\d]/g, '');
            var jumlah = parseInt(rows[i].cells[3].querySelector('input').value);

            if (!isNaN(hargaJual)) {
                totalBelanja += hargaJual * jumlah;
            }
        }

        // Tampilkan total belanja di elemen dengan id 'totalBelanja'
        document.getElementById('totalBelanja').innerText = 'Rp ' + formatRupiah(totalBelanja);

    }

    // function refreshTotalBelanja() {
    //     // Ambil semua baris dari tabel kasiran
    //     var kasirTableBody = document.getElementById('kasirTableBody');
    //     var rows = kasirTableBody.getElementsByTagName('tr');

    //     // Hitung total belanja berdasarkan data saat ini di tabel kasiran
    //     var totalBelanja = 0;

    //     for (var i = 0; i < rows.length; i++) {
    //         var hargaJual = rows[i].cells[2].innerText.replace(/[^\d]/g, '');
    //         var jumlah = parseInt(rows[i].cells[3].querySelector('input').value, 10);

    //         if (!isNaN(hargaJual)) {
    //             totalBelanja += hargaJual * jumlah;
    //         }
    //     }

    //     // Tampilkan total belanja di elemen dengan id 'totalBelanja'
    //     document.getElementById('totalBelanja').innerText = 'Rp ' + formatRupiah(totalBelanja);

    //     // Hitung ulang kembalian
    //     hitungKembalian();
    // }



    function hitungKembalian() {
        var totalBelanjaElem = document.getElementById('totalBelanja');
        var jumlahUangElem = document.getElementById('jumlahUang');
        var kembalianElem = document.getElementById('kembalian');

        // Ambil nilai total belanja tanpa Rp ganda dan ubah ke dalam tipe data integer
        var totalBelanja = parseInt(totalBelanjaElem.innerText.replace(/[^\d]/g, ''));

        // Ambil nilai jumlah uang dan ubah ke dalam tipe data integer
        var jumlahUang = parseInt(jumlahUangElem.value);

        // Periksa apakah nilai totalBelanja dan jumlahUang adalah angka yang valid
        if (!isNaN(totalBelanja) && !isNaN(jumlahUang)) {
            var kembalian = jumlahUang - totalBelanja;

            // Jika kembalian kurang dari total belanja, tampilkan pesan khusus
            if (kembalian < 0) {
                kembalianElem.innerText = 'Kurang = Rp ' + formatRupiah(Math.abs(kembalian));
                kembalianElem.style.color = 'red';
            } else {
                // Tampilkan kembalian di elemen dengan id 'kembalian'
                kembalianElem.innerText = 'Rp ' + formatRupiah(Math.abs(kembalian));
                kembalianElem.style.color = 'blue';
            }
        } else {
            // Jika ada masalah dengan konversi ke integer, tampilkan pesan kesalahan
            console.error('Error: Gagal mengambil nilai total belanja atau jumlah uang.');

            // Reset kembalian jika terjadi kesalahan
            kembalianElem.innerText = 'Rp 0';
            kembalianElem.style.color = 'black';
        }
    }


    function bayar() {
        // Fungsi ini bisa diimplementasikan sesuai kebutuhan, misalnya menyimpan transaksi ke database, dll.
        // Di sini hanya menampilkan alert untuk demonstrasi.
        var kembalian = parseInt(document.getElementById('kembalian').innerText.replace(/[^\d]/g, ''));
        if (kembalian < 0) {
            alert('Uang yang dibayarkan kurang!');
        } else {
            alert('Pembayaran berhasil!');
        }
    }

    // Fungsi untuk menghapus item dari tabel kasiran
    function removeItemFromKasir(button) {
        var row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);

    }


    function formatRupiah(angka) {
        var number_string = angka.toString();
        var split = number_string.split(',');
        var sisa = split[0].length % 3;
        var rupiah = split[0].substr(0, sisa);
        var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return 'Rp ' + rupiah;
    }
</script>

<script>
    // Tambahkan variabel global untuk menyimpan referensi ke interval
    var autoRefreshInterval;

    // Fungsi untuk memulai autoclick setiap 500 milidetik
    function startAutoRefresh() {
        // Mulai autoclick setiap 500 milidetik
        autoRefreshInterval = setInterval(refreshTotalBelanja, 500);
    }

    // Fungsi untuk menghentikan autoclick
    function stopAutoRefresh() {
        // Hentikan autoclick
        clearInterval(autoRefreshInterval);
    }

    // Tambahkan tombol untuk memulai dan menghentikan autoclick
    document.addEventListener('DOMContentLoaded', function() {
        // Tombol Start AutoRefresh
        var startButton = document.createElement('button');
        startButton.className = 'btn btn-success btn-sm';
        startButton.textContent = 'Start AutoRefresh';
        startButton.addEventListener('click', startAutoRefresh);

        // Tombol Stop AutoRefresh
        var stopButton = document.createElement('button');
        stopButton.className = 'btn btn-danger btn-sm';
        stopButton.textContent = 'Stop AutoRefresh';
        stopButton.addEventListener('click', stopAutoRefresh);

        // Tempatkan tombol di dalam elemen dengan class 'col-12'
        var col12Element = document.querySelector('.col-12');
        col12Element.appendChild(startButton);
        col12Element.appendChild(stopButton);
    });

    // ...

    // Fungsi untuk merefresh total belanja
    function refreshTotalBelanja() {
        // Ambil semua baris dari tabel kasiran
        var kasirTableBody = document.getElementById('kasirTableBody');
        var rows = kasirTableBody.getElementsByTagName('tr');

        // Hitung total belanja berdasarkan data saat ini di tabel kasiran
        var totalBelanja = 0;

        for (var i = 0; i < rows.length; i++) {
            var hargaJual = rows[i].cells[2].innerText.replace(/[^\d]/g, '');
            var jumlah = parseInt(rows[i].cells[3].querySelector('input').value, 10);

            if (!isNaN(hargaJual)) {
                totalBelanja += hargaJual * jumlah;
            }
        }

        // Tampilkan total belanja di elemen dengan id 'totalBelanja'
        document.getElementById('totalBelanja').innerText = 'Rp ' + formatRupiah(totalBelanja);

        // Hitung ulang kembalian
        hitungKembalian();
    }

    // Panggil fungsi ini saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        setInterval(refreshTotalBelanja, 500); // Mulai autoclick setiap 500 milidetik
        refreshTotalBelanja(); // Panggil fungsi ini saat halaman dimuat
    });
</script>

<?php include 'footer/footer.php'; ?>