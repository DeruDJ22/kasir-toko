import Swal from 'sweetalert2';
import Alpine from 'alpinejs';

window.Swal = Swal;
window.Alpine = Alpine;

// Dark-mode aware SweetAlert defaults
const getSwalTheme = () => {
    const isDark = document.documentElement.classList.contains('dark');
    return {
        background: isDark ? '#1e293b' : '#ffffff',
        color: isDark ? '#f1f5f9' : '#0f172a',
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#64748b',
    };
};

// Global confirm for delete forms with .js-delete-form
document.addEventListener('submit', function (e) {
    const form = e.target.closest('.js-delete-form');
    if (!form) return;

    e.preventDefault();
    const msg = form.getAttribute('data-confirm') || 'Yakin ingin menghapus data ini?';
    const theme = getSwalTheme();

    Swal.fire({
        title: 'Konfirmasi',
        text: msg,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: theme.cancelButtonColor,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        background: theme.background,
        color: theme.color,
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});

// Helper for session alerts (callable from blade)
window.showToast = function (type, message) {
    const theme = getSwalTheme();
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: theme.background,
        color: theme.color,
    });
};

Alpine.start();
