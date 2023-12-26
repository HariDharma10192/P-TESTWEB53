<?php
$servername = "localhost"; // Ganti nama_server dengan nama server MySQL Anda
$database = "sm"; // Ganti nama_database dengan nama database Anda
$username = "root"; // Ganti root dengan nama pengguna MySQL Anda
$password = ""; // Password kosong

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
