<?php
session_start();
if(!isset($_SESSION['username'])){
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(["error"=>"Unauthorized"]);
    exit;
}

include '../config.php';

$sql = "SELECT k.id_kelompok, k.nama_kelompok, s.nama_sumber
        FROM kelompok k
        LEFT JOIN sumber s ON k.id_sumber = s.id_sumber
        ORDER BY k.id_kelompok DESC";
$result = $conn->query($sql);

$data = [];
while($row = $result->fetch_assoc()){
    $aksi = '
        <button class="btn btn-sm btn-warning btnEdit" data-id="'.$row['id_kelompok'].'">✏️ Edit</button>
        <button class="btn btn-sm btn-danger btnDelete" data-id="'.$row['id_kelompok'].'">🗑️ Hapus</button>
    ';
    $data[] = [
        "id_kelompok"  => $row['id_kelompok'],
        "nama_sumber"  => htmlspecialchars($row['nama_sumber']),
        "nama_kelompok"=> htmlspecialchars($row['nama_kelompok']),
        "aksi"         => $aksi
    ];
}

// DataTables expects: {"data": [...]}
echo json_encode(["data"=>$data], JSON_UNESCAPED_UNICODE);
