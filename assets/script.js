// Minimal + interaktif untuk semua halaman
document.addEventListener('DOMContentLoaded', () => {

    // Log debug untuk memastikan script aktif
    console.log("Dashboard Farmasi siap!"); 

    // -------------------- Tooltip & Hover Card --------------------
    const cards = document.querySelectorAll('.card[data-tooltip]');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transition = 'transform 0.3s';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });

    // -------------------- Button hover (opsional) --------------------
    const buttons = document.querySelectorAll('.btn, .logout-btn, .login-box button');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', () => btn.style.cursor = 'pointer');
    });

    // -------------------- Tempat untuk animasi tambahan --------------------
    // Contoh: bisa ditambahkan efek animasi tabel, modal, dsb.
});
