<?php
include __DIR__ . '/../config.php';
$q = $conn->query("SELECT id_sumber, nama_sumber FROM sumber ORDER BY nama_sumber ASC");
while($row = $q->fetch_assoc()){
    echo "<option value='{$row['id_sumber']}'>".htmlspecialchars($row['nama_sumber'])."</option>";
}
