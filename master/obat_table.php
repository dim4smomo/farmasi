<?php
session_start();
if(!isset($_SESSION['username'])){
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(["error"=>"Unauthorized"]);
    exit;
}

include '../config.php';

$sql = "SELECT o.id_obat, o.kode_obat, o.nama_obat, o.bentuk, o.dosis, o.satuan, 
               o.pabrikan, o.stok_minimal, s.nama_sumber, k.nama_kelompok
        FROM obat o
        LEFT JOIN sumber s ON o.id_sumber = s.id_sumber
        LEFT JOIN kelompok k ON o.id_kelompok = k.id_kelompok
        ORDER BY o.id_obat DESC";
$result = $conn->query($sql);

$data = [];
while($row = $result->fetch_assoc()){
    $aksi = '
        <button class="btnEdit bg-yellow-400 px-2 py-1 rounded text-white hover:bg-yellow-500" data-id="'.$row['id_obat'].'">✏️ Edit</button>
        <button class="btnDelete bg-red-500 px-2 py-1 rounded text-white hover:bg-red-600" data-id="'.$row['id_obat'].'">🗑️ Hapus</button>
    ';
    $data[] = [
        "kode_obat"     => htmlspecialchars($row['kode_obat']),
        "nama_obat"     => htmlspecialchars($row['nama_obat']),
        "bentuk"        => htmlspecialchars($row['bentuk']),
        "dosis"         => htmlspecialchars($row['dosis']),
        "satuan"        => htmlspecialchars($row['satuan']),
        "pabrikan"      => htmlspecialchars($row['pabrikan']),
        "stok_minimal"  => htmlspecialchars($row['stok_minimal']),
        "nama_sumber"   => htmlspecialchars($row['nama_sumber']),
        "nama_kelompok" => htmlspecialchars($row['nama_kelompok']),
        "aksi"          => $aksi
    ];
}

header('Content-Type: application/json');
echo json_encode(["data"=>$data], JSON_UNESCAPED_UNICODE);
