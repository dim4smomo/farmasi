<?php
session_start();
include '../config.php';

$id = $_POST['id_kelompok'];
$id_sumber_baru = $_POST['id_sumber'];
$nama_baru = $_POST['nama_kelompok'];

$old = $conn->query("SELECT * FROM kelompok WHERE id_kelompok='$id'")->fetch_assoc();
$id_sumber_lama = $old['id_sumber'];
$nama_lama = $old['nama_kelompok'];

// Update
$stmt = $conn->prepare("UPDATE kelompok SET id_sumber=?, nama_kelompok=? WHERE id_kelompok=?");
$stmt->bind_param("isi", $id_sumber_baru, $nama_baru, $id);

if($stmt->execute()){
    $user = $_SESSION['username'];
    $conn->query("INSERT INTO log_kelompok (aksi, kelompok_id, id_sumber_lama, id_sumber_baru, nama_lama, nama_baru, user)
                  VALUES ('UPDATE', $id, $id_sumber_lama, $id_sumber_baru, '$nama_lama', '$nama_baru', '$user')");
    echo "success";
} else {
    echo $conn->error;
}
?>
