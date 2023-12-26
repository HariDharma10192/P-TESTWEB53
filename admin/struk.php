<?php

include '../koneksi.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve data from history_transaksi table
// Query to retrieve data from history_transaksi table in descending order
$query = "SELECT * FROM history_transaksi ORDER BY created_at DESC, id";
$result = $conn->query($query);


// Check if there are rows in the result set
if ($result->num_rows > 0) {
    echo "<h2>History Transaksi:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>BARA</th><th>NAMA</th><th>HJUAL</th><th>Jumlah</th><th>Total</th><th>Total Belanja</th><th>Jumlah Uang</th><th>Kembalian</th><th>Created At</th><th>View</th></tr>";

    // Variables to track the current date
    $currentDate = null;
    $idArray = [];

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $idArray[] = $row['id'];

        if ($currentDate != $row['created_at'] || $result->num_rows === count($idArray)) {
            echo "<tr>";
            echo "<td>" . implode(", ", $idArray) . "</td>";
            echo "<td>" . $row['bara'] . "</td>";
            echo "<td>" . $row['nama'] . "</td>";
            echo "<td>" . $row['hjual'] . "</td>";
            echo "<td>" . $row['jumlah'] . "</td>";
            echo "<td>" . $row['total'] . "</td>";
            echo "<td>" . $row['total_belanja'] . "</td>";
            echo "<td>" . $row['jumlah_uang'] . "</td>";
            echo "<td>" . $row['kembalian'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";

            // Add the View button with a link to view_transaksi.php and pass created_at as a parameter
            echo "<td><a href='view_transaksi.php?created_at=" . $row['created_at'] . "'>View</a></td>";

            echo "</tr>";

            // Reset the variables for the next group
            $currentDate = $row['created_at'];
            $idArray = [$row['id']];
        }
    }
    echo "</table>";

    // Back link to kasir.php
    echo '<a href="kasir.php">Back to Kasir</a>';
} else {
    echo "<h2>No records found in history_transaksi</h2>";

    // Back link to kasir.php
    echo '<a href="kasir.php">Back to Kasir</a>';
}

// Close the database connection
$conn->close();
