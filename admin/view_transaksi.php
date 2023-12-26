<?php
// Establish database connection
include '../koneksi.php';
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the created_at parameter from the URL
$created_at = $_GET['created_at'];

// Query to retrieve data from history_transaksi table based on created_at
$query = "SELECT * FROM history_transaksi WHERE created_at = '$created_at'";
$result = $conn->query($query);

// Check if there are rows in the result set
if ($result->num_rows > 0) {
    echo "<h2>Detail Transaksi:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>BARA</th><th>NAMA</th><th>HJUAL</th><th>Jumlah</th><th>Total</th><th>Created At</th></tr>";

    // Variables to store values
    $totalBelanja = 0;
    $jumlahUang = 0;
    $kembalian = 0;

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['bara'] . "</td>";
        echo "<td>" . $row['nama'] . "</td>";
        echo "<td>Rp " . number_format($row['hjual'], 0, ',', '.') . "</td>";
        echo "<td>" . $row['jumlah'] . "</td>";
        echo "<td>Rp " . number_format($row['total'], 0, ',', '.') . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "</tr>";

        // Accumulate values for Total Belanja, Jumlah Uang, and Kembalian
        $totalBelanja = $row['total_belanja'];
        $jumlahUang = $row['jumlah_uang'];
        $kembalian = $row['kembalian'];
    }
    echo "</table>";

    // Display Total Belanja, Jumlah Uang, and Kembalian outside the table with Indonesian formatting
    if ($result->num_rows > 1) {
        echo "<p>Total Belanja: Rp " . number_format($totalBelanja, 0, ',', '.') . "</p>";
        echo "<p>Jumlah Uang: Rp " . number_format($jumlahUang, 0, ',', '.') . "</p>";
        echo "<p>Kembalian: Rp " . number_format($kembalian, 0, ',', '.') . "</p>";
    }

    // Back link to struk.php
    echo '<a href="struk.php">Back to Struk</a>';
} else {
    echo "<h2>No records found for the selected date</h2>";

    // Back link to struk.php
    echo '<a href="struk.php">Back to Struk</a>';
}

// Close the database connection
$conn->close();
