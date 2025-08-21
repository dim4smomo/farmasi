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
    <title>Log All</title>
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
        .filter-box { margin-bottom:15px; }
    </style>
</head>
<body>

<a href="../index.php" class="back-btn">⬅ Kembali ke Dashboard</a>
<h2>Log Aktivitas - Semua</h2>

<div class="filter-box">
    <label for="filter">Filter Tabel: </label>
    <select id="filter">
        <option value="">Semua</option>
        <option value="Sumber">Sumber</option>
        <option value="Kelompok">Kelompok</option>
    </select>
</div>

<table id="logTable" class="display nowrap" style="width:100%">
    <thead>
        <tr>
            <th>ID Log</th>
            <th>Tabel</th>
            <th>Aksi</th>
            <th>ID Referensi</th>
            <th>Nama Lama</th>
            <th>Nama Baru</th>
            <th>Sumber Lama</th>
            <th>Sumber Baru</th>
            <th>User</th>
            <th>Waktu</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Gabungkan log_sumber + log_kelompok dengan JOIN
        $sql = "
            SELECT 
                ls.id_log, 
                'Sumber' AS tabel_asal, 
                ls.aksi, 
                ls.sumber_id AS id_ref, 
                ls.nama_lama, 
                ls.nama_baru, 
                NULL AS sumber_lama, 
                NULL AS sumber_baru, 
                ls.user, 
                ls.created_at
            FROM log_sumber ls

            UNION ALL

            SELECT 
                lk.id_log, 
                'Kelompok' AS tabel_asal, 
                lk.aksi, 
                lk.kelompok_id AS id_ref, 
                lk.nama_lama, 
                lk.nama_baru, 
                sl.nama_sumber AS sumber_lama, 
                sb.nama_sumber AS sumber_baru, 
                lk.user, 
                lk.created_at
            FROM log_kelompok lk
            LEFT JOIN sumber sl ON lk.id_sumber_lama = sl.id_sumber
            LEFT JOIN sumber sb ON lk.id_sumber_baru = sb.id_sumber

            ORDER BY created_at DESC
        ";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['id_log'] ?></td>
            <td><?= $row['tabel_asal'] ?></td>
            <td><?= htmlspecialchars($row['aksi']) ?></td>
            <td><?= $row['id_ref'] ?></td>
            <td><?= htmlspecialchars($row['nama_lama']) ?></td>
            <td><?= htmlspecialchars($row['nama_baru']) ?></td>
            <td><?= $row['sumber_lama'] ?? '-' ?></td>
            <td><?= $row['sumber_baru'] ?? '-' ?></td>
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
    var table = $('#logTable').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'csv', 'pdf', 'print'
        ]
    });

    // Filter dropdown
    $('#filter').on('change', function() {
        var val = $(this).val();
        if (val) {
            table.column(1).search('^' + val + '$', true, false).draw();
        } else {
            table.column(1).search('').draw();
        }
    });
});
</script>

</body>
</html>
