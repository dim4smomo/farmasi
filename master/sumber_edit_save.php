<?php
include '../config.php';

$id_sumber   = $_POST['id_sumber'] ?? '';
$nama_sumber = $_POST['nama_sumber'] ?? '';

if (empty($id_sumber) || empty($nama_sumber)) {
    echo "Data tidak lengkap";
    exit;
}

$stmt = $conn->prepare("UPDATE sumber SET nama_sumber=? WHERE id_sumber=?");
$stmt->bind_param("si", $nama_sumber, $id_sumber);
$ok = $stmt->execute();
$stmt->close();

echo $ok ? "success" : "Gagal update: " . $conn->error;
