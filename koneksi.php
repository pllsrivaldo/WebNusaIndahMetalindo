<?php
$host = "localhost";
$user = "root";
$pass = ""; // Kosongkan jika password bawaan XAMPP tidak diubah
$db   = "website_nim";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>