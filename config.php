<?php
$conn = new mysqli("localhost","root","","farmasi_db");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
