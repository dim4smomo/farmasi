<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Obat</title>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">📋 Daftar Obat</h1>

    <div class="mb-4 flex justify-between">
        <button id="btnTambah" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            ➕ Tambah Obat
        </button>
        <a href="../index.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            ⬅️ Kembali ke Menu
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="tabelObat" class="display w-full bg-white rounded-lg shadow">
            <thead>
                <tr class="bg-gray-200 text-gray-700 text-sm">
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Bentuk</th>
                    <th>Dosis</th>
                    <th>Satuan</th>
                    <th>Pabrikan</th>
                    <th>Stok Min</th>
                    <th>Sumber</th>
                    <th>Kelompok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modalAddObat" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl">
    <div class="flex justify-between items-center border-b px-4 py-2">
      <h3 class="text-lg font-bold">Tambah Obat</h3>
      <button onclick="$('#modalAddObat').addClass('hidden')">✖</button>
    </div>
    <div class="p-4">
      <div id="formAddObatContainer"></div>
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div id="modalEditObat" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl">
    <div class="flex justify-between items-center border-b px-4 py-2">
      <h3 class="text-lg font-bold">Edit Obat</h3>
      <button onclick="$('#modalEditObat').addClass('hidden')">✖</button>
    </div>
    <div class="p-4">
      <div id="formEditObatContainer"></div>
    </div>
  </div>
</div>

<!-- Modal Hapus -->
<div id="modalDeleteObat" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
    <div class="flex justify-between items-center border-b px-4 py-2">
      <h3 class="text-lg font-bold text-red-600">Konfirmasi Hapus</h3>
      <button onclick="$('#modalDeleteObat').addClass('hidden')">✖</button>
    </div>
    <div class="p-4">
      <p id="deleteMessage" class="mb-4">Apakah Anda yakin ingin menghapus obat ini?</p>
      <div class="flex justify-end space-x-2">
        <button onclick="$('#modalDeleteObat').addClass('hidden')" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Batal</button>
        <button id="btnConfirmDelete" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Hapus</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
    let table = $('#tabelObat').DataTable({
        ajax: 'obat_table.php',
        columns: [
            { data: 'kode_obat' },
            { data: 'nama_obat' },
            { data: 'bentuk' },
            { data: 'dosis' },
            { data: 'satuan' },
            { data: 'pabrikan' },
            { data: 'stok_minimal' },
            { data: 'nama_sumber' },
            { data: 'nama_kelompok' },
            { data: 'aksi' }
        ]
    });

    // Tambah Obat
    $("#btnTambah").on("click", function(){
        $("#modalAddObat").removeClass("hidden");
        $("#formAddObatContainer").load("obat_add.php");
    });

    // Edit Obat
    $(document).on("click", ".btnEdit", function(){
        let id = $(this).data("id");
        $("#modalEditObat").removeClass("hidden");
        $("#formEditObatContainer").load("obat_edit.php?id=" + id);
    });

    // Hapus Obat
    let deleteId = null;
    $(document).on("click", ".btnDelete", function(){
        deleteId = $(this).data("id");
        $("#modalDeleteObat").removeClass("hidden");
    });

    $("#btnConfirmDelete").on("click", function(){
        if(deleteId){
            $.post("obat_delete.php", {id: deleteId}, function(res){
                try {
                    let data = JSON.parse(res);
                    if(data.status === "success"){
                        alert(data.msg);
                        table.ajax.reload();
                        $("#modalDeleteObat").addClass("hidden");
                    } else {
                        alert(data.msg);
                    }
                } catch(e){
                    alert("Terjadi kesalahan server");
                }
            });
        }
    });
});
</script>
</body>
</html>
