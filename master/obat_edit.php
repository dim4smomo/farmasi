<?php
include __DIR__ . '/../config.php';

$id = $_GET['id'] ?? null;
if(!$id){
    echo "<p>ID obat tidak ditemukan</p>";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM obat WHERE id_obat = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

$sumber   = $conn->query("SELECT * FROM sumber ORDER BY nama_sumber");
$kelompok = $conn->query("SELECT * FROM kelompok ORDER BY nama_kelompok");
?>

<div class="p-4">
    <h2 class="text-xl font-bold mb-4">Edit Obat</h2>
    <form id="formEditObat" class="space-y-3">
        <input type="hidden" name="id_obat" value="<?= $data['id_obat'] ?>">

        <div>
            <label class="block mb-1 font-medium">Kode Obat</label>
            <input type="text" name="kode_obat" value="<?= htmlspecialchars($data['kode_obat']) ?>" required
                class="w-full border px-3 py-2 rounded"/>
        </div>

        <div>
            <label class="block mb-1 font-medium">Nama Obat</label>
            <input type="text" name="nama_obat" value="<?= htmlspecialchars($data['nama_obat']) ?>" required
                class="w-full border px-3 py-2 rounded"/>
        </div>

        <div>
            <label class="block mb-1 font-medium">Bentuk</label>
            <input type="text" name="bentuk" value="<?= htmlspecialchars($data['bentuk']) ?>"
                class="w-full border px-3 py-2 rounded"/>
        </div>

        <div>
            <label class="block mb-1 font-medium">Dosis</label>
            <input type="text" name="dosis" value="<?= htmlspecialchars($data['dosis']) ?>"
                class="w-full border px-3 py-2 rounded"/>
        </div>

        <div>
            <label class="block mb-1 font-medium">Satuan</label>
            <input type="text" name="satuan" value="<?= htmlspecialchars($data['satuan']) ?>"
                class="w-full border px-3 py-2 rounded"/>
        </div>

        <div>
            <label class="block mb-1 font-medium">Pabrikan</label>
            <input type="text" name="pabrikan" value="<?= htmlspecialchars($data['pabrikan']) ?>"
                class="w-full border px-3 py-2 rounded"/>
        </div>

        <div>
            <label class="block mb-1 font-medium">Stok Minimal</label>
            <input type="number" name="stok_minimal" value="<?= htmlspecialchars($data['stok_minimal']) ?>"
                class="w-full border px-3 py-2 rounded"/>
        </div>

        <div>
            <label class="block mb-1 font-medium">Sumber</label>
            <select name="id_sumber" required class="w-full border px-3 py-2 rounded">
                <?php while($row = $sumber->fetch_assoc()){ ?>
                    <option value="<?= $row['id_sumber'] ?>" 
                        <?= $row['id_sumber']==$data['id_sumber']?'selected':'' ?>>
                        <?= htmlspecialchars($row['nama_sumber']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div>
            <label class="block mb-1 font-medium">Kelompok</label>
            <select name="id_kelompok" required class="w-full border px-3 py-2 rounded">
                <?php while($row = $kelompok->fetch_assoc()){ ?>
                    <option value="<?= $row['id_kelompok'] ?>" 
                        <?= $row['id_kelompok']==$data['id_kelompok']?'selected':'' ?>>
                        <?= htmlspecialchars($row['nama_kelompok']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="flex justify-end space-x-2 mt-4">
            <button type="button" onclick="closeModal()" 
                class="bg-gray-400 px-4 py-2 rounded hover:bg-gray-500">Batal</button>
            <button type="submit" 
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update</button>
        </div>
    </form>
</div>

<script>
document.getElementById('formEditObat').addEventListener('submit', async function(e){
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    try {
        const res = await fetch('obat_edit_save.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if(data.status === 'success'){
            alert(data.msg);
            closeModal();
            if(window.tabelObat){ tabelObat.ajax.reload(); } // refresh DataTables kalau ada
        } else {
            alert(data.msg);
        }
    } catch(err){
        console.error(err);
        alert("Terjadi kesalahan koneksi");
    }
});

function closeModal(){
    document.getElementById('modalObat').classList.add('hidden');
}
</script>
