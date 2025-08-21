<?php
include __DIR__ . '/../config.php';
$result = mysqli_query($conn, "SELECT * FROM sumber ORDER BY nama_sumber ASC");
$i=1;
while($row = mysqli_fetch_assoc($result)):
?>
<tr class="text-center border-b hover:bg-gray-50">
    <td class="py-2 px-4"><?= $i++; ?></td>
    <td class="py-2 px-4"><?= htmlspecialchars($row['nama_sumber']) ?></td>
    <td class="py-2 px-4">
        <button onclick="openEditModal(<?= $row['id_sumber'] ?>)" class="text-blue-500 hover:underline mr-2">Edit</button>
        <button onclick="openHapusModal(<?= $row['id_sumber'] ?>,'<?= addslashes($row['nama_sumber']) ?>')" class="text-red-500 hover:underline">Hapus</button>
    </td>
</tr>
<?php endwhile; ?>
