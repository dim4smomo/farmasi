<?php
include __DIR__ . '/config.php';

$result = $conn->query("SELECT * FROM log_sumber ORDER BY created_at DESC");
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([ "data" => $data ]);
