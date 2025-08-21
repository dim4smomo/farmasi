<?php
include __DIR__ . '/../config.php';

// Ambil data sumber & kelompok untuk dropdown
$sumber   = $conn->query("SELECT * FROM sumber ORDER BY nama_sumber");
$kelompok = $conn->query("SELECT * FROM kelompok ORDER BY nama_kelompok");
?>

<div class="p-4">
    <h2 class="text-xl font-bold mb-4">Tambah Obat Baru</h2>
    <form id="formAddObat" class="space-y-3">

        <div>
            <label class="block mb-1 font-medium">Kode Obat</label>
            <input type="text" name="kode_obat" required
                class="w-full border px-3 py-2 rounded"/>
        </div>

        <div>
            <label class="block mb-1 font-medium">Nama Obat</label>
            <input type="text" name="nama_obat" required
                class="w-full border px-3 py-2 rounded"/>
        </div>

        <div>
            <label class="block mb-1 font-medium">Bentuk</label>
            <input type="text" name="bentuk"
                class="w-full border px-3 py-2 rounded"/>
        </div>

        <div>
            <label class="block mb-1 font-medium">Dosis</label>
            <input type="text" name="dosis"
                class="w-full border px-3 py-2 rounded"/>
        </div>

        <div>
            <label class="block mb-1 font-medium">Satuan</label>
            <input type="text" name="satuan"
                class="w-full border px-3 py-2 rounded"/>
        </div>

        <div>
            <label class="block mb-1 font-medium">Pabrikan</label>
            <input type="text" name="pabrikan"
                class="w-full border px-3 py-2 rounded"/>
        </div>

        <div>
            <label class="block mb-1 font-medium">Stok Minimal</label>
            <input type="number" name="stok_minimal" value="0"
                class="w-full border px-3 py-2 rounded"/>
        </div>

        <div>
            <label class="block mb-1 font-medium">Sumber</label>
            <select name="id_sumber" required class="w-full border px-3 py-2 rounded">
                <option value="">-- Pilih Sumber --</option>
                <?php while($row = $sumber->fetch_assoc()){ ?>
                    <option value="<?= $row['id_sumber'] ?>">
                        <?= htmlspecialchars($row['nama_sumber']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div>
            <label class="block mb-1 font-medium">Kelompok</label>
            <select name="id_kelompok" required class="w-full border px-3 py-2 rounded">
                <option value="">-- Pilih Kelompok --</option>
                <?php while($row = $kelompok->fetch_assoc()){ ?>
                    <option value="<?= $row['id_kelompok'] ?>">
                        <?= htmlspecialchars($row['nama_kelompok']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="flex justify-end space-x-2 mt-4">
            <button type="button" onclick="closeAddModal()" 
                class="bg-gray-400 px-4 py-2 rounded hover:bg-gray-500">Batal</button>
            <button type="submit" 
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Simpan</button>
        </div>
    </form>
</div>

<script>
document.getElementById('formAddObat').addEventListener('submit', async function(e){
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    try {
        const res = await fetch('obat_add_save.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if(data.status === 'success'){
            alert(data.msg);
            form.reset();
            closeAddModal();
            if(window.tabelObat){ tabelObat.ajax.reload(); } // refresh DataTables
        } else {
            alert(data.msg);
        }
    } catch(err){
        console.error(err);
        alert("Terjadi kesalahan koneksi");
    }
});

function closeAddModal(){
    document.getElementById('modalAddObat').classList.add('hidden');
}
</script>
