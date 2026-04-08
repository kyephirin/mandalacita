<?php
require_once 'config/koneksi.php';

$sql = "SELECT * FROM camaba";
$result = $conn->query($sql);

echo "<h3>Data Camaba:</h3>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>No Pendaftaran</th><th>Nama</th><th>Email</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['no_pendaftaran'] . "</td>";
    echo "<td>" . $row['nama'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>