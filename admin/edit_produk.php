<?php
include 'header/navbar.php';
include 'header/sidebar.php';

// Ambil data BARA dari parameter URL
$bara = $_GET['bara'];

// Query untuk mendapatkan data produk berdasarkan BARA
$query = "SELECT BARA, NAMA, HBELI, HJUAL FROM mstock WHERE BARA = '$bara'";
$result = $conn->query($query);

// Pastikan data ditemukan
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Handle form submission untuk proses pengeditan
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // var_dump($_POST);  // Tambahkan ini untuk debugging
        // Ambil data yang di-submit dari formulir
        $nama = $_POST['nama'];
        $hbeli = $_POST['hbeli'];
        $hjual = $_POST['hjual'];

        // Lakukan update data produk ke database
        $updateQuery = "UPDATE mstock SET NAMA = '$nama', HBELI = '$hbeli', HJUAL = '$hjual' WHERE BARA = '$bara'";
        if ($conn->query($updateQuery) === TRUE) {
            echo '<script>window.location.href = "produk.php";</script>';
        } else {
            echo "Error: " . $updateQuery . "<br>" . $conn->error;
        }
    }

?>

    <div class="content-body">
        <div class="container-fluid mt-3">
            <form action="" method="post">
                <!-- Input fields untuk setiap kolom seperti BARA, NAMA, HBELI, HJUAL -->
                <label for="bara">BARA:</label>
                <!-- Tampilkan BARA sebagai teks tanpa memungkinkan pengeditan -->
                <input type="text" name="bara" value="<?php echo $row['BARA']; ?>" readonly>

                <label for="nama">NAMA:</label>
                <input type="text" name="nama" value="<?php echo $row['NAMA']; ?>" required>

                <label for="hbeli">HBELI:</label>
                <input type="text" name="hbeli" value="<?php echo $row['HBELI']; ?>" required>

                <label for="hjual">HJUAL:</label>
                <input type="text" name="hjual" value="<?php echo $row['HJUAL']; ?>" required>

                <!-- Tambahkan input untuk kolom lainnya jika diperlukan -->

                <button type="submit">Update Produk</button>
            </form>
        </div>
    </div>

<?php
} else {
    echo "Data produk tidak ditemukan.";
}

include 'footer/footer.php';
?>