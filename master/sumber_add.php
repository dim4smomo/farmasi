<form id="formAddSumber">
    <div class="mb-3">
        <label>Nama Sumber</label>
        <input type="text" name="nama_sumber" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<script>
$('#formAddSumber').submit(function(e){
    e.preventDefault();
    $.post('sumber_add_save.php', $(this).serialize(), function(res){
        if(res === 'success'){
            $('#modalSumber').modal('hide');
            table.ajax.reload(null, false);
        } else {
            alert(res);
        }
    });
});
</script>
