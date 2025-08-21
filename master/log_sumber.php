<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}
include '../config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Log Sumber</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; background:#f4f6f9; margin:0; padding:20px; }
        h2 { margin-bottom:20px; }
        .back-btn {
            display:inline-block; margin-bottom:15px;
            padding:8px 15px; background:#2575fc; color:#fff;
            border-radius:5px; text-decoration:none;
        }
        .back-btn:hover { background:#1258c9; }
    </style>
</head>
<body>

<a href="../index.php" class="back-btn">⬅ Kembali ke Dashboard</a>
<h2>Log Aktivitas - Sumber</h2>

<table id="logTable" class="display nowrap" style="width:100%">
    <thead>
        <tr>
            <th>ID Log</th>
            <th>Aksi</th>
            <th>ID Sumber</th>
            <th>Nama Lama</th>
            <th>Nama Baru</th>
            <th>User</th>
            <th>Waktu</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM log_sumber ORDER BY created_at DESC";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['id_log'] ?></td>
            <td><?= htmlspecialchars($row['aksi']) ?></td>
            <td><?= $row['sumber_id'] ?></td>
            <td><?= htmlspecialchars($row['nama_lama']) ?></td>
            <td><?= htmlspecialchars($row['nama_baru']) ?></td>
            <td><?= htmlspecialchars($row['user']) ?></td>
            <td><?= $row['created_at'] ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#logTable').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'csv', 'pdf', 'print'
        ]
    });
});
</script>

</body>
</html>
