<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Toast / Alert Notifikasi Sukses
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: {!! json_encode(session('success')) !!},
                timer: 3500,
                timerProgressBar: true,
                showConfirmButton: false,
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-4 shadow-lg border-0'
                }
            });
        @endif

        // 2. Alert Notifikasi Error
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                text: {!! json_encode(session('error')) !!},
                confirmButtonColor: '#198754',
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-4 shadow-lg border-0'
                }
            });
        @endif

        // 3. Alert Notifikasi Warning
        @if (session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: {!! json_encode(session('warning')) !!},
                confirmButtonColor: '#198754',
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-4 shadow-lg border-0'
                }
            });
        @endif

        // 4. Alert Notifikasi Info
        @if (session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: {!! json_encode(session('info')) !!},
                confirmButtonColor: '#198754',
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-4 shadow-lg border-0'
                }
            });
        @endif

        // 5. Alert Validasi Form Error
        @if (isset($errors) && $errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Periksa Kembali Form',
                html: '<ul class="text-start mb-0 ps-3 small text-secondary">@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Tutup',
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-4 shadow-lg border-0'
                }
            });
        @endif

        // 6. Konfirmasi Hapus Otomatis untuk Semua Form DELETE
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const isDeleteForm = form.classList.contains('form-delete') ||
                form.querySelector('input[name="_method"][value="DELETE"]') !== null;

            if (isDeleteForm && !form.dataset.confirmed) {
                e.preventDefault();
                const itemTitle = form.getAttribute('data-item-title') || 'data ini';

                Swal.fire({
                    title: 'Hapus Data?',
                    text: `Apakah Anda yakin ingin menghapus ${itemTitle}? Tindakan ini tidak dapat dibatalkan.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-trash-fill me-1"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-4 shadow-lg border-0',
                        confirmButton: 'rounded-3 px-4',
                        cancelButton: 'rounded-3 px-4'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.dataset.confirmed = 'true';
                        form.submit();
                    }
                });
            }
        });
    });

    // Helper Global Window
    window.swalSuccess = function(title, text) {
        Swal.fire({
            icon: 'success',
            title: title || 'Berhasil!',
            text: text || '',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            customClass: {
                popup: 'rounded-4 shadow-lg border-0'
            }
        });
    };

    window.swalError = function(title, text) {
        Swal.fire({
            icon: 'error',
            title: title || 'Terjadi Kesalahan!',
            text: text || '',
            confirmButtonColor: '#198754',
            customClass: {
                popup: 'rounded-4 shadow-lg border-0'
            }
        });
    };
</script>
