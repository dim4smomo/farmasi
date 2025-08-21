// Contoh interaktivitas sederhana
document.addEventListener('DOMContentLoaded', function() {
    // Confirm delete
    const deleteLinks = document.querySelectorAll('.delete');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e){
            if(!confirm('Yakin hapus data ini?')) e.preventDefault();
        });
    });

    // Dropdown menu
    const dropdownBtns = document.querySelectorAll('.dropdown-btn');
    dropdownBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            this.nextElementSibling.classList.toggle('hidden');
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const deleteLinks = document.querySelectorAll('.btn-delete');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e){
            if(!confirm('Yakin hapus obat ini?')) e.preventDefault();
        });
    });
});

<script>
function openHapusModal(id, nama) {
    const modal = document.getElementById('modalHapus');
    const text = document.getElementById('hapusText');
    const link = document.getElementById('hapusLink');

    text.textContent = `Apakah Anda yakin ingin menghapus sumber "${nama}"?`;
    link.href = `master/sumber_delete.php?id=${id}`;

    modal.classList.remove('hidden');
}

function closeHapusModal() {
    const modal = document.getElementById('modalHapus');
    modal.classList.add('hidden');
}
</script>
