<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pgweb-acara8";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil ID dari parameter URL
$id = $_GET['id'];

// Query untuk mendapatkan data berdasarkan ID
$sql = "SELECT * FROM penduduk WHERE id = $id";
$result = $conn->query($sql);
$data = $result->fetch_assoc();

$update_success = false; // Variabel untuk mendeteksi status update

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $kecamatan = $_POST['kecamatan'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $luas = $_POST['luas'];
    $jumlah_penduduk = $_POST['jumlah_penduduk'];

    // Query untuk update data
    $update_sql = "UPDATE penduduk SET 
        kecamatan='$kecamatan', 
        longitude='$longitude', 
        latitude='$latitude', 
        luas='$luas', 
        jumlah_penduduk='$jumlah_penduduk' 
        WHERE id=$id";

    if ($conn->query($update_sql) === TRUE) {
        $update_success = true; // Tandai bahwa update berhasil
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Penduduk</title>
    <script>
        // Fungsi untuk menampilkan popup jika update berhasil
        function showSuccessPopup() {
            alert("Data berhasil diperbarui!");
            window.location.href = "index.php"; // Kembali ke halaman utama
        }
    </script>
</head>
<body>
    <h2>Edit Data Penduduk</h2>

    <?php if ($update_success): ?>
        <script>
            // Tampilkan popup jika update berhasil
            showSuccessPopup();
        </script>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Kecamatan:</label><br>
        <input type="text" name="kecamatan" value="<?php echo $data['kecamatan']; ?>" required><br><br>

        <label>Longitude:</label><br>
        <input type="text" name="longitude" value="<?php echo $data['longitude']; ?>" required><br><br>

        <label>Latitude:</label><br>
        <input type="text" name="latitude" value="<?php echo $data['latitude']; ?>" required><br><br>

        <label>Luas:</label><br>
        <input type="number" name="luas" value="<?php echo $data['luas']; ?>" required><br><br>

        <label>Jumlah Penduduk:</label><br>
        <input type="number" name="jumlah_penduduk" value="<?php echo $data['jumlah_penduduk']; ?>" required><br><br>

        <button type="submit">Simpan Perubahan</button>
    </form>
</body>
</html>
