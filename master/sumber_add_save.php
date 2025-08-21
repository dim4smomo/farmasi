<?php
session_start();
if(!isset($_SESSION['username'])){
    echo "Unauthorized";
    exit;
}

include '../config.php';

$nama_sumber = trim($_POST['nama_sumber'] ?? '');
if($nama_sumber === ''){
    echo "Nama sumber wajib diisi";
    exit;
}

// Cek duplikasi
$stmt = $conn->prepare("SELECT COUNT(*) as jml FROM sumber WHERE nama_sumber = ?");
$stmt->bind_param("s", $nama_sumber);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if($row['jml'] > 0){
    echo "Nama sumber sudah ada";
    exit;
}

// Insert
$stmt = $conn->prepare("INSERT INTO sumber (nama_sumber) VALUES (?)");
$stmt->bind_param("s", $nama_sumber);
$ok = $stmt->execute();
$stmt->close();

if($ok){
    echo "success";
} else {
    echo "Gagal simpan: " . $conn->error;
}
