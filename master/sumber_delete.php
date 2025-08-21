<?php
include '../config.php';

$id_sumber = $_POST['id_sumber'] ?? '';

if(empty($id_sumber)){
    echo json_encode(["status"=>"error","msg"=>"ID tidak ditemukan"]);
    exit;
}

// --- cek relasi dengan kelompok
$stmt = $conn->prepare("SELECT COUNT(*) as jml FROM kelompok WHERE id_sumber=?");
$stmt->bind_param("i", $id_sumber);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$stmt->close();

if($res['jml'] > 0){
    echo json_encode([
        "status" => "error",
        "msg"    => "Data sumber ini masih digunakan di tabel kelompok, tidak bisa dihapus."
    ]);
    exit;
}

// --- hapus sumber
$stmt = $conn->prepare("DELETE FROM sumber WHERE id_sumber=?");
$stmt->bind_param("i", $id_sumber);
$ok = $stmt->execute();
$stmt->close();

if($ok){
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode([
        "status"=>"error",
        "msg"=>"Gagal menghapus: ".$conn->error
    ]);
}
