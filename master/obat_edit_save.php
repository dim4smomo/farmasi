<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['username'])){
    echo json_encode(["status"=>"error","msg"=>"Unauthorized"]);
    exit;
}

include __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_obat     = $_POST['id_obat'] ?? null;
    $kode_obat   = trim($_POST['kode_obat'] ?? '');
    $nama_obat   = trim($_POST['nama_obat'] ?? '');
    $bentuk      = trim($_POST['bentuk'] ?? '');
    $dosis       = trim($_POST['dosis'] ?? '');
    $satuan      = trim($_POST['satuan'] ?? '');
    $pabrikan    = trim($_POST['pabrikan'] ?? '');
    $stok_minimal= (int)($_POST['stok_minimal'] ?? 0);
    $id_sumber   = $_POST['id_sumber'] ?? null;
    $id_kelompok = $_POST['id_kelompok'] ?? null;

    if (!$id_obat || !$kode_obat || !$nama_obat) {
        echo json_encode(["status"=>"error","msg"=>"Data wajib diisi"]);
        exit;
    }

    // 🔍 Cek duplikasi kode_obat selain dirinya sendiri
    $stmt = $conn->prepare("SELECT COUNT(*) FROM obat WHERE kode_obat = ? AND id_obat != ?");
    $stmt->bind_param("si", $kode_obat, $id_obat);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(["status"=>"error","msg"=>"Kode obat sudah digunakan"]);
        exit;
    }

    // 🚀 Update data obat
    $stmt = $conn->prepare("UPDATE obat 
        SET kode_obat=?, nama_obat=?, bentuk=?, dosis=?, satuan=?, pabrikan=?, stok_minimal=?, id_sumber=?, id_kelompok=?
        WHERE id_obat=?");
    $stmt->bind_param("ssssssiiii", 
        $kode_obat, $nama_obat, $bentuk, $dosis, $satuan, $pabrikan, 
        $stok_minimal, $id_sumber, $id_kelompok, $id_obat
    );

    if ($stmt->execute()) {
        echo json_encode(["status"=>"success","msg"=>"Data obat berhasil diperbarui"]);
    } else {
        echo json_encode(["status"=>"error","msg"=>"Gagal update data obat"]);
    }
    $stmt->close();
}
?>
