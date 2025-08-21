<?php
include '../config.php';
$id_sumber = $_GET['id_sumber'] ?? '';
$data = $conn->query("SELECT * FROM sumber WHERE id_sumber='$id_sumber'")->fetch_assoc();
?>
<form id="formEditSumber">
    <input type="hidden" name="id_sumber" value="<?= $data['id_sumber'] ?>">
    <div class="mb-3">
        <label>Nama Sumber</label>
        <input type="text" name="nama_sumber" class="form-control" value="<?= htmlspecialchars($data['nama_sumber']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

<script>
$('#formEditSumber').submit(function(e){
    e.preventDefault();
    $.post('sumber_edit_save.php', $(this).serialize(), function(res){
        if(res === 'success'){
            $('#modalSumber').modal('hide');
            table.ajax.reload(null, false);
        } else {
            alert(res);
        }
    });
});
</script>
