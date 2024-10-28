<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mengambil data dari form dan memeriksa keberadaannya
$kecamatan = isset($_POST['kecamatan']) ? $_POST['kecamatan'] : '';
$luas = isset($_POST['luas']) ? $_POST['luas'] : '';
$jumlah_penduduk = isset($_POST['jumlah_penduduk']) ? $_POST['jumlah_penduduk'] : '';
$longitude = isset($_POST['longitude']) ? $_POST['longitude'] : '';
$latitude = isset($_POST['latitude']) ? $_POST['latitude'] : '';

// Validasi input
if (empty($kecamatan) || !is_numeric($luas) || !is_numeric($jumlah_penduduk) || !is_numeric($longitude) || !is_numeric($latitude)) {
    die("Semua kolom harus diisi dan 'luas', 'jumlah penduduk', 'longitude', serta 'latitude' harus angka.");
}

// Validasi tambahan
if ($luas <= 0 || $jumlah_penduduk <= 0 || $longitude < -180 || $longitude > 180 || $latitude < -90 || $latitude > 90) {
    die("Data tidak valid. Luas dan jumlah penduduk harus lebih dari 0, longitude antara -180 dan 180, dan latitude antara -90 dan 90.");
}

// Sesuaikan dengan setting MySQL
$servername = "localhost";
$username = "root";
$password = ""; // Ganti dengan password yang sesuai, jika ada
$dbname = "pgweb-acara8"; // Pastikan nama database sesuai

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Menggunakan prepared statement untuk menghindari SQL Injection
$stmt = $conn->prepare("INSERT INTO penduduk (kecamatan, luas, jumlah_penduduk, longitude, latitude) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("siidd", $kecamatan, $luas, $jumlah_penduduk, $longitude, $latitude); // s = string, i = integer, d = double (float)

// Eksekusi statement
if ($stmt->execute()) {
    header("Location: index.php"); // Redirect ke success.php setelah berhasil
    exit; // Hentikan script setelah redirect
} else {
    echo "Error: " . $stmt->error;
}

// Tutup statement dan koneksi
$stmt->close();
$conn->close();


 
?>