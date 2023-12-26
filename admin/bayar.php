<?php
// Function to check if the table exists
function tableExists($conn, $tableName)
{
    $result = $conn->query("SHOW TABLES LIKE '$tableName'");
    return $result->num_rows > 0;
}

// Function to create the history_transaksi table if it doesn't exist
function createHistoryTransaksiTable($conn)
{
    $tableName = "history_transaksi";
    if (!tableExists($conn, $tableName)) {
        $createTableQuery = "CREATE TABLE $tableName (
            id INT PRIMARY KEY AUTO_INCREMENT,
            bara VARCHAR(255) NOT NULL,
            nama VARCHAR(255) NOT NULL,
            hjual INT NOT NULL,
            jumlah INT NOT NULL,
            total INT NOT NULL,
            total_belanja INT NOT NULL,
            jumlah_uang INT NOT NULL,
            kembalian INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        if ($conn->query($createTableQuery) === FALSE) {
            die("Error creating table: " . $conn->error);
        }
    }
}

// Function to save data to database using MySQLi
function saveToDatabase($conn, $bara, $nama, $hjual, $jumlah, $total, $totalBelanja, $jumlahUang, $kembalian)
{
    $hjual = (int)preg_replace('/[^0-9]/', '', $hjual);
    $total = (int)preg_replace('/[^0-9]/', '', $total);
    $totalBelanja = (int)preg_replace('/[^0-9]/', '', $totalBelanja);
    $kembalian = (int)preg_replace('/[^0-9]/', '', $kembalian);

    // Prepare and bind the statement
    $stmt = $conn->prepare("INSERT INTO history_transaksi (bara, nama, hjual, jumlah, total, total_belanja, jumlah_uang, kembalian) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiiiii", $bara, $nama, $hjual, $jumlah, $total, $totalBelanja, $jumlahUang, $kembalian);

    // Execute the statement
    $stmt->execute();

    // Close the statement
    $stmt->close();
}

// Check if the data parameter is set in the URL
if (isset($_GET['data'])) {
    // Decode the JSON data
    $jsonData = urldecode($_GET['data']);
    $data = json_decode($jsonData, true);

    // Retrieve other relevant values
    $totalBelanja = isset($_GET['totalBelanja']) ? $_GET['totalBelanja'] : 0;
    $jumlahUang = isset($_GET['jumlahUang']) ? $_GET['jumlahUang'] : 0;
    $kembalian = isset($_GET['kembalian']) ? $_GET['kembalian'] : 0;

    // Establish database connection
    include '../koneksi.php';

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the table exists, if not, create it
    createHistoryTransaksiTable($conn);

    // Display the received data in a table
    echo "<h2>Items to be paid:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>BARA</th><th>NAMA</th><th>HJUAL</th><th>Jumlah</th><th>Total</th></tr>";
    foreach ($data as $item) {
        echo "<tr>";
        echo "<td>" . $item['bara'] . "</td>";
        echo "<td>" . $item['nama'] . "</td>";
        echo "<td>" . $item['hjual'] . "</td>";
        echo "<td>" . $item['jumlah'] . "</td>";
        echo "<td>" . $item['total'] . "</td>";
        echo "</tr>";

        // Save data to the database for each item
        saveToDatabase($conn, $item['bara'], $item['nama'], $item['hjual'], $item['jumlah'], $item['total'], $totalBelanja, $jumlahUang, $kembalian);
    }
    echo "</table>";

    // Display totalBelanja, jumlahUang, and kembalian
    echo "<h3>Total Belanja: Rp " . $totalBelanja . "</h3>";
    echo "<h3>Jumlah Uang: Rp " . $jumlahUang . "</h3>";
    echo "<h3>Kembalian: Rp " . $kembalian . "</h3>";

    // Close the database connection
    $conn->close();

    // Back link to kasir.php
    echo '<a href="kasir.php">Back to Kasir</a>';
} else {
    // Handle the case when no data is provided
    echo "<h2>No data received for payment.</h2>";
    // Back link to kasir.php
    echo '<a href="kasir.php">Back to Kasir</a>';
}
