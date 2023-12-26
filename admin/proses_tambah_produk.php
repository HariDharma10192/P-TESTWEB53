<?php
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bara = $_POST["bara"];
    $nama = $_POST["nama"];
    $hbeli = $_POST["hbeli"];
    $hjual = $_POST["hjual"];

    // Tambahkan query untuk memasukkan data ke dalam tabel mstock
    $query = "INSERT INTO mstock (BARA, NAMA, HBELI, HJUAL) VALUES ('$bara', '$nama', '$hbeli', '$hjual')";
    $result = $conn->query($query);

    // Redirect kembali ke halaman admin/produk.php setelah penambahan
    header("Location: produk.php");
    exit;
}
