<?php
session_start();
if(!isset($_SESSION['username'])){
    echo "Unauthorized";
    exit;
}

include '../config.php';

$id_sumber = intval($_POST['id_sumber'] ?? 0);
$nama_kelompok = trim($_POST['nama_kelompok'] ?? '');

if($id_sumber <= 0 || $nama_kelompok === ''){
    echo "Data tidak lengkap";
    exit;
}

// Cek duplikasi: nama kelompok dalam 1 sumber tidak boleh sama
$stmt = $conn->prepare("SELECT COUNT(*) as jml 
                        FROM kelompok 
                        WHERE nama_kelompok = ? AND id_sumber = ?");
$stmt->bind_param("si", $nama_kelompok, $id_sumber);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if($row['jml'] > 0){
    echo "Nama kelompok sudah ada di sumber ini";
    exit;
}

// Insert
$stmt = $conn->prepare("INSERT INTO kelompok (id_sumber, nama_kelompok) VALUES (?, ?)");
$stmt->bind_param("is", $id_sumber, $nama_kelompok);
$ok = $stmt->execute();
$stmt->close();

if($ok){
    echo "success";
} else {
    echo "Gagal simpan: " . $conn->error;
}
