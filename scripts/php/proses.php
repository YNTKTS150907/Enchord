<?php
$koneksi = new mysqli("localhost", "root", "", "sekolah");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$nama = $_POST['nama'];
$kelas = $_POST['kelas'];

$sql = "INSERT INTO siswa (nama, kelas) VALUES ('$nama', '$kelas')";

if ($koneksi->query($sql) === TRUE) {
    echo "Data berhasil disimpan!";
} else {
    echo "Error: " . $sql . "<br>" . $koneksi->error;
}

$koneksi->close();
?>
