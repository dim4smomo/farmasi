<?php
session_start();
include '../config.php';
?>
<form id="formAddKelompok">
    <div class="mb-3">
        <label>Nama Sumber</label>
        <select class="form-control" name="id_sumber" required>
            <option value="">-- Pilih Sumber --</option>
            <?php
            $s = $conn->query("SELECT * FROM sumber ORDER BY nama_sumber");
            while($r = $s->fetch_assoc()){
                echo '<option value="'.$r['id_sumber'].'">'.$r['nama_sumber'].'</option>';
            }
            ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Nama Kelompok</label>
        <input type="text" name="nama_kelompok" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<script>
$('#formAddKelompok').submit(function(e){
    e.preventDefault();
    $.post('kelompok_add_save.php', $(this).serialize(), function(res){
        if(res === 'success'){
            // Tutup modal setelah sukses simpan
            $('#modalKelompok').modal('hide');
            // Reload datatable
            table.ajax.reload(null, false);
        } else {
            // Kalau ada error tampilkan alert sederhana
            alert(res);
        }
    });
});
</script>
