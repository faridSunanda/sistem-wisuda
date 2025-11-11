export function initActions(config) {
    const { detailUrl, editUrl, deleteUrl } = config;

    window.lihatData = function(id) {
        if (detailUrl) {
            window.location.href = detailUrl.replace(':id', id);
        } else {
            window.location.href = `/admin/setting/alur-pendaftaran/${id}`;
        }
    };

    window.editData = function(id) {
        if (editUrl) {
            window.location.href = editUrl.replace(':id', id);
        } else {
            window.location.href = `/admin/setting/alur-pendaftaran/${id}/edit`;
        }
    };

   window.hapusData = function (id, buttonEl) {
    Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: "Anda tidak akan dapat mengembalikan data ini!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const url = deleteUrl ? deleteUrl.replace(':id', id) : `/admin/setting/alur-pendaftaran/${id}`;
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // 👇 penting: gunakan buttonEl langsung, bukan event
            const button = buttonEl;
            if (!button) {
                console.warn('⚠️ Button element tidak ditemukan.');
                return;
            }

            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token || '',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Data berhasil dihapus.', 'success');
                        if (typeof window.alurPendaftaranTable !== 'undefined') {
                            window.alurPendaftaranTable.draw();
                        } else {
                            location.reload();
                        }
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan saat menghapus data.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast(error.message || 'Terjadi kesalahan.', 'error');
                })
                .finally(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }
    });
};



    // 2. TAMBAHKAN HELPER INI: Untuk notifikasi 'success' dan 'error'
    function showToast(message, type = 'info') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        } else {
            alert(message);
        }
    }
}
