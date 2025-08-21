<?php
session_start();
if(!isset($_SESSION['username'])){
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(["status"=>"error","msg"=>"Unauthorized"]);
    exit;
}

include __DIR__ . '/../config.php';

// Ambil data
$kode       = trim($_POST['kode_obat'] ?? '');
$nama       = trim($_POST['nama_obat'] ?? '');
$bentuk     = trim($_POST['bentuk'] ?? '');
$dosis      = trim($_POST['dosis'] ?? '');
$satuan     = trim($_POST['satuan'] ?? '');
$pabrikan   = trim($_POST['pabrikan'] ?? '');
$stok_min   = intval($_POST['stok_minimal'] ?? 0);
$id_sumber  = intval($_POST['id_sumber'] ?? 0);
$id_kelompok= intval($_POST['id_kelompok'] ?? 0);

// Validasi input kosong
if(empty($kode) || empty($nama) || !$id_sumber || !$id_kelompok){
    echo json_encode(["status"=>"error","msg"=>"Data wajib diisi lengkap"]);
    exit;
}

// Validasi duplikasi kode_obat
$cek = $conn->prepare("SELECT COUNT(*) as jml FROM obat WHERE kode_obat=?");
$cek->bind_param("s", $kode);
$cek->execute();
$hasil = $cek->get_result()->fetch_assoc();
$cek->close();

if($hasil['jml'] > 0){
    echo json_encode(["status"=>"error","msg"=>"Kode Obat '$kode' sudah ada, gunakan kode lain"]);
    exit;
}

// Insert data
$stmt = $conn->prepare("INSERT INTO obat 
    (kode_obat, nama_obat, bentuk, dosis, satuan, pabrikan, stok_minimal, id_sumber, id_kelompok)
    VALUES (?,?,?,?,?,?,?,?,?)");
$stmt->bind_param("ssssssiii", $kode, $nama, $bentuk, $dosis, $satuan, $pabrikan, $stok_min, $id_sumber, $id_kelompok);

if($stmt->execute()){
    echo json_encode(["status"=>"success","msg"=>"Obat berhasil ditambahkan"]);
}else{
    echo json_encode(["status"=>"error","msg"=>"Gagal tambah obat: ".$stmt->error]);
}
$stmt->close();
$conn->close();
