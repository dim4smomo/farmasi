<?php
session_start();
if(!isset($_SESSION['username'])){
    echo json_encode(["status"=>"error","msg"=>"Unauthorized"]);
    exit;
}

include '../config.php';

$id_kelompok = intval($_POST['id_kelompok'] ?? 0);
if($id_kelompok <= 0){
    echo json_encode(["status"=>"error","msg"=>"ID tidak ditemukan"]);
    exit;
}

// --- cek relasi dengan obat
$stmt = $conn->prepare("SELECT COUNT(*) as jml FROM obat WHERE id_kelompok=?");
$stmt->bind_param("i", $id_kelompok);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$stmt->close();

if($res['jml'] > 0){
    echo json_encode([
        "status" => "error",
        "msg"    => "Data kelompok ini masih digunakan di tabel obat, tidak bisa dihapus."
    ]);
    exit;
}

// --- ambil data lama untuk log
$stmt = $conn->prepare("SELECT * FROM kelompok WHERE id_kelompok=?");
$stmt->bind_param("i", $id_kelompok);
$stmt->execute();
$old = $stmt->get_result()->fetch_assoc();
$stmt->close();

// --- hapus kelompok
$stmt = $conn->prepare("DELETE FROM kelompok WHERE id_kelompok=?");
$stmt->bind_param("i", $id_kelompok);
$ok = $stmt->execute();
$stmt->close();

if($ok){
    $user = $_SESSION['username'] ?? 'system';
    $conn->query("INSERT INTO log_kelompok 
        (aksi, kelompok_id, id_sumber_lama, id_sumber_baru, nama_lama, nama_baru, user)
        VALUES 
        ('DELETE', {$id_kelompok}, {$old['id_sumber']}, NULL, '{$old['nama_kelompok']}', NULL, '{$user}')");
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode([
        "status" => "error",
        "msg"    => "Gagal menghapus: ".$conn->error
    ]);
}
