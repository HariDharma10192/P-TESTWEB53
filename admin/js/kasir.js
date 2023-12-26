
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
