<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../login.php");
    exit;
}
include '../config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelompok Obat - Dashboard Modern</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root {
  --primary: #4f46e5;
  --secondary: #6366f1;
  --bg-card: rgba(255,255,255,0.85);
  --hover-shadow: 0 15px 40px rgba(0,0,0,0.2);
}
body {
  font-family: 'Inter', sans-serif;
  background: linear-gradient(135deg,#e0e7ff,#f0f9ff);
  padding: 20px;
}
h2 { margin-bottom: 20px; font-weight: 700; color: var(--primary); }
.btn-primary {
  background: linear-gradient(90deg,#4f46e5,#6366f1);
  border: none;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  transition: all 0.3s;
}
.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: var(--hover-shadow);
}
.container {
  max-width: 1000px;
  margin: auto;
  background: var(--bg-card);
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}
.dataTables_wrapper .dataTables_filter input {
  border-radius:8px; padding:5px;
}
.modal-content {
  border-radius: 16px;
  backdrop-filter: blur(10px);
  box-shadow: var(--hover-shadow);
}
</style>
</head>
<body>
<div class="container">
  <h2>📂 Data Kelompok Obat</h2>
  <div class="mb-3">
    <button class="btn btn-primary" id="btnTambah">➕ Tambah Kelompok</button>
    <a href="../index.php" class="btn btn-secondary">⬅️ Kembali Dashboard</a>
  </div>
  <table id="tabelKelompok" class="display table table-striped" style="width:100%">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Kelompok</th>
        <th>Aksi</th>
      </tr>
    </thead>
  </table>
</div>

<!-- Modal Add/Edit -->
<div class="modal fade" id="modalKelompok" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalKelompokTitle">Form Kelompok</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="formContent"></div>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Delete -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin menghapus data ini?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="btnConfirmDelete">Ya, Hapus</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Peringatan -->
<div class="modal fade" id="modalPeringatan" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Peringatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modalPeringatanBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
let table;
let hapusId = null;

function reloadTable(){
  table.ajax.reload(null, false);
}

$(document).ready(function(){
  table = $('#tabelKelompok').DataTable({
    "ajax": "kelompok_table.php",
    "columns": [
      { "data": null, "render": function(data, type, row, meta){ return meta.row+1; } },
      { "data": "nama_kelompok" },
      { "data": "aksi" }
    ]
  });

  // Tambah
  $("#btnTambah").click(function(){
    $("#modalKelompokTitle").text("Tambah Kelompok");
    $("#formContent").load("kelompok_add.php", function(){
      $("#modalKelompok").modal("show");
    });
  });

  // Edit
  $(document).on("click",".btnEdit",function(){
    var id = $(this).data("id");
    $("#modalKelompokTitle").text("Edit Kelompok");
    $("#formContent").load("kelompok_edit.php?id_kelompok="+id,function(){
      $("#modalKelompok").modal("show");
    });
  });

  // Delete (show konfirmasi modal dulu)
  $(document).on("click",".btnDelete",function(){
    hapusId = $(this).data("id");
    var modal = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));
    modal.show();
  });

  // Confirm Delete
  $("#btnConfirmDelete").click(function(){
    if(hapusId){
      $.post("kelompok_delete.php",{id_kelompok:hapusId}, function(res){
          let data = JSON.parse(res);
          if(data.status === "success"){
              reloadTable();
              var modalKonf = bootstrap.Modal.getInstance(document.getElementById('modalKonfirmasi'));
              modalKonf.hide();
          } else if(data.status === "error"){
              // tutup modal konfirmasi dulu
              var modalKonf = bootstrap.Modal.getInstance(document.getElementById('modalKonfirmasi'));
              if(modalKonf) modalKonf.hide();

              // tampilkan modal peringatan
              $("#modalPeringatanBody").text(data.msg);
              var modalP = new bootstrap.Modal(document.getElementById('modalPeringatan'));
              modalP.show();
          }
      });
    }
  });
});
</script>
</body>
</html>
