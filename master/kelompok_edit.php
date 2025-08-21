<?php
session_start();
include '../config.php';

$id_kelompok = $_GET['id_kelompok'] ?? '';
$data = $conn->query("SELECT * FROM kelompok WHERE id_kelompok='$id_kelompok'")->fetch_assoc();
?>
<form id="formEditKelompok">
    <input type="hidden" name="id_kelompok" value="<?= $data['id_kelompok'] ?>">
    
    <div class="mb-3">
        <label>Nama Sumber</label>
        <select class="form-control" name="id_sumber" required>
            <option value="">-- Pilih Sumber --</option>
            <?php
            $sumber = $conn->query("SELECT * FROM sumber ORDER BY nama_sumber");
            while($row = $sumber->fetch_assoc()){
                $sel = $row['id_sumber'] == $data['id_sumber'] ? 'selected' : '';
                echo '<option value="'.$row['id_sumber'].'" '.$sel.'>'.htmlspecialchars($row['nama_sumber']).'</option>';
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Nama Kelompok</label>
        <input type="text" name="nama_kelompok" class="form-control" 
               value="<?= htmlspecialchars($data['nama_kelompok']) ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>

<script>
$('#formEditKelompok').submit(function(e){
    e.preventDefault();
    $.post('kelompok_edit_save.php', $(this).serialize(), function(res){
        if(res === 'success'){
            $('#modalKelompok').modal('hide');
            table.ajax.reload(null, false);
        } else {
            alert(res);
        }
    });
});
</script>
