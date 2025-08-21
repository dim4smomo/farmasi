<?php
session_start();
if(!isset($_SESSION['username'])){
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(["error"=>"Unauthorized"]);
    exit;
}

include '../config.php';

// Ambil semua data sumber
$sql = "SELECT id_sumber, nama_sumber FROM sumber ORDER BY id_sumber DESC";
$result = $conn->query($sql);

$data = [];
while($row = $result->fetch_assoc()){
    $aksi = '
        <button class="btn btn-sm btn-warning btnEdit" data-id="'.$row['id_sumber'].'">✏️ Edit</button>
        <button class="btn btn-sm btn-danger btnDelete" data-id="'.$row['id_sumber'].'">🗑️ Hapus</button>
    ';
    $data[] = [
        "id_sumber"   => $row['id_sumber'],
        "nama_sumber" => htmlspecialchars($row['nama_sumber']),
        "aksi"        => $aksi
    ];
}

// DataTables butuh format {"data": [...]}
echo json_encode(["data"=>$data], JSON_UNESCAPED_UNICODE);
