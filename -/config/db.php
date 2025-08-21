<?php
$host = "localhost";
$user = "root";       // username MySQL (default XAMPP: root)
$pass = "";           // password MySQL (default XAMPP: kosong)
$db   = "farmasi_db"; // nama database yang tadi kamu buat

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
