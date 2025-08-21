<?php
session_start();
include __DIR__ . '/../config.php';

$id = $_POST['id'] ?? 0;
if(!$id){
    echo json_encode(["status"=>"error","msg"=>"ID tidak valid"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM obat WHERE id_obat=?");
$stmt->bind_param("i", $id);

if($stmt->execute()){
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error","msg"=>$conn->error]);
}
$stmt->close();
