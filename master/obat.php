<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>📋 Data Obat</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">📋 Daftar Obat</h1>

    <div class="mb-4 flex justify-between">
        <button id="btnTambahObat" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Tambah Obat</button>
        <a href="../index.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali ke Menu</a>
    </div>

    <div class="overflow-x-auto">
        <table id="tabelObat" class="min-w-full bg-white rounded-lg shadow">
            <thead>
                <tr class="bg-gray-200 text-gray-700 text-center">
                    <th class="py-2 px-4 border">ID</th>
                    <th class="py-2 px-4 border">Kode</th>
                    <th class="py-2 px-4 border">Nama Obat</th>
                    <th class="py-2 px-4 border">Bentuk</th>
                    <th class="py-2 px-4 border">Dosis</th>
                    <th class="py-2 px-4 border">Satuan</th>
                    <th class="py-2 px-4 border">Pabrikan</th>
                    <th class="py-2 px-4 border">Stok Min</th>
                    <th class="py-2 px-4 border">Sumber</th>
                    <th class="py-2 px-4 border">Kelompok</th>
                    <th class="py-2 px-4 border">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal Obat -->
<div id="modalObat" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg p-6 w-3/4 relative max-h-[90vh] overflow-auto">
        <span id="closeModal" class="absolute top-2 right-4 text-gray-500 text-2xl cursor-pointer">&times;</span>
        <div id="modalContent">Loading...</div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
  <div class="bg-white rounded-lg p-6 w-96 text-center">
    <h2 class="text-lg font-bold mb-4">Konfirmasi Hapus</h2>
    <p class="mb-6">Apakah Anda yakin ingin menghapus data ini?</p>
    <div class="flex justify-center space-x-4">
      <button id="btnConfirmYes" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Iya</button>
      <button id="btnConfirmNo" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Batal</button>
    </div>
  </div>
</div>

<script>
let deleteId = null;

// Inisialisasi DataTables
const table = $('#tabelObat').DataTable({
    ajax: "obat_table.php",
    columns: [
        { data: "id_obat" },
        { data: "kode_obat" },
        { data: "nama_obat" },
        { data: "bentuk" },
        { data: "dosis" },
        { data: "satuan" },
        { data: "pabrikan" },
        { data: "stok_minimal" },
        { data: "nama_sumber" },
        { data: "nama_kelompok" },
        { data: "aksi", orderable:false, searchable:false }
    ]
});

// Tambah Obat
document.getElementById('btnTambahObat').addEventListener('click', function(){
    modalObat.classList.remove('hidden');
    fetch('obat_add.php')
        .then(res => res.text())
        .then(html => { document.getElementById('modalContent').innerHTML = html; });
});

// Edit Obat
function openEditModal(id){
    modalObat.classList.remove('hidden');
    fetch('obat_edit.php?id='+id)
        .then(res => res.text())
        .then(html => { document.getElementById('modalContent').innerHTML = html; });
}
window.openEditModal = openEditModal;

// Hapus Obat
function openDeleteModal(id){
    deleteId = id;
    document.getElementById('confirmModal').classList.remove('hidden');
}
window.openDeleteModal = openDeleteModal;

// Konfirmasi hapus
document.getElementById('btnConfirmYes').addEventListener('click', function(){
    if(deleteId){
        fetch('obat_delete.php?id='+deleteId)
            .then(res => res.json())
            .then(data => {
                alert(data.msg);
                if(data.status==="success"){
                    table.ajax.reload();
                }
            });
    }
    document.getElementById('confirmModal').classList.add('hidden');
});
document.getElementById('btnConfirmNo').addEventListener('click', function(){
    document.getElementById('confirmModal').classList.add('hidden');
});

// Tutup modal
document.getElementById('closeModal').addEventListener('click', function(){
    modalObat.classList.add('hidden');
});
</script>

</body>
</html>
