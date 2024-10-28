<?php
// Memeriksa apakah parameter id tersedia
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    var_dump($_GET['id']); // Untuk memastikan ID terkirim


    // Konfigurasi koneksi ke database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pgweb-acara8";

    // Buat koneksi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Cek koneksi
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Siapkan query untuk menghapus data
    $stmt = $conn->prepare("DELETE FROM penduduk WHERE id = ?");
    $stmt->bind_param("i", $id); // i = integer

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href = 'index.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
} else {
    echo "ID tidak ditemukan.";
}
?>
