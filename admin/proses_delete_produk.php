<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['bara'])) {
    $bara = $_GET['bara'];

    // Query untuk menghapus data produk berdasarkan BARA
    $deleteQuery = "DELETE FROM mstock WHERE BARA = '$bara'";

    if ($conn->query($deleteQuery) === TRUE) {
        // Jika penghapusan berhasil, arahkan kembali ke halaman produk.php
        header("Location: produk.php");
        exit();
    } else {
        // Jika terjadi kesalahan, tampilkan pesan kesalahan
        echo "Error: " . $deleteQuery . "<br>" . $conn->error;
    }
} else {
    // Jika parameter tidak valid, tampilkan pesan kesalahan
    echo "Invalid request.";
}

$conn->close();
