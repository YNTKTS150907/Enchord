<?php
error_reporting(0); // Nonaktifkan error reporting di production
header('Content-Type: text/plain; charset=utf-8');

try {
    $connection = new mysqli("localhost", "root", "", "sekolah");
    $connection->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Koneksi database gagal: Silakan coba lagi nanti");
}

// Validasi input
if (empty($_POST['nama']) || empty($_POST['kelas'])) {
    die("Nama dan kelas harus diisi!");
}
// Asli, ga tau gwe ini penyebutannya apa
$nama = $connection->real_escape_string(htmlspecialchars($_POST['nama'], ENT_QUOTES, 'UTF-8'));// htmlspecialchars untuk Anti-XSS
$kelas = $connection->real_escape_string(htmlspecialchars($_POST['kelas'], ENT_QUOTES, 'UTF-8'));

// Prepared Statement (anti SQL Injection)
$stmt = $connection->prepare("INSERT INTO siswa (nama, kelas) VALUES (?, ?)");
$stmt->bind_param("ss", $nama, $kelas);

// Execution dengan error handling
if ($stmt->execute()) {
    // (anti form resubmission)
    header("Location: sukses.php?status=berhasil");
    exit();
} else {
    error_log("Error database: " . $stmt->error); // Log error tanpa tampilkan ke user
    die("Terjadi kesalahan saat menyimpan data");
}

// Cleanup
$stmt->close();
$connection->close();
?>