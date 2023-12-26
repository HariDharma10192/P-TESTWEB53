<?php
include 'header/navbar.php';
include 'header/sidebar.php';

$query = "SELECT BARA, NAMA, HBELI, HJUAL FROM mstock";
$result = $conn->query($query);
?>
<div class="content-body">
    <div class="container-fluid mt-3">
        <input type="text" id="searchInput" placeholder="Search...">
        <table id="dataTable">
            <thead>
                <tr>
                    <th>No. </th>
                    <th>* * </th>
                    <th>BARA</th>
                    <th>NAMA</th>
                    <th>HBELI</th>
                    <th>HJUAL</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>&nbsp;</td>";
                    echo "<td>" . $row['BARA'] . "</td>";
                    echo "<td>" . $row['NAMA'] . "</td>";
                    echo "<td>" . $row['HBELI'] . "</td>";
                    echo "<td>" . $row['HJUAL'] . "</td>";
                    echo "<td>
                            <a href='edit_produk.php?bara=" . $row['BARA'] . "'>Edit</a>  || 
                            <a href='proses_delete_produk.php?bara=" . $row['BARA'] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus produk ini?\")'>Delete</a>
                        </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <form action="proses_tambah_produk.php" method="post">
        <!-- Input fields untuk setiap kolom seperti BARA, NAMA, HBELI, HJUAL -->
        <label for="bara">BARA:</label>
        <input type="text" name="bara" required>

        <label for="nama">NAMA:</label>
        <input type="text" name="nama" required>

        <label for="hbeli">HBELI:</label>
        <input type="text" name="hbeli" required>

        <label for="hjual">HJUAL:</label>
        <input type="text" name="hjual" required>

        <!-- Tambahkan input untuk kolom lainnya jika diperlukan -->

        <button type="submit">Tambah Produk</button>
    </form>
</div>

<script>
    document.getElementById("searchInput").addEventListener("input", function() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("dataTable");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) {
            var found = false;

            for (var j = 0; j < tr[i].cells.length - 1; j++) {
                td = tr[i].getElementsByTagName("td")[j];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }

            if (found) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    });
</script>

<?php include 'footer/footer.php'; ?>