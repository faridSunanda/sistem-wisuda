// resources/js/admin/setting/jadwal-wisuda/actions.js

export function initActions(config) {
    const { detailUrl, editUrl, deleteUrl } = config;

    window.lihatData = function(id) {
        if (detailUrl) {
            window.location.href = detailUrl.replace(':id', id);
        } else {
            window.location.href = `/admin/setting/jadwal-wisuda/${id}`;
        }
    };

    window.editData = function(id) {
        if (editUrl) {
            window.location.href = editUrl.replace(':id', id);
        } else {
            window.location.href = `/admin/setting/jadwal-wisuda/${id}/edit`;
        }
    };

    window.hapusData = function(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus jadwal wisuda ini?')) {
            return;
        }

        const url = deleteUrl ? deleteUrl.replace(':id', id) : `/admin/setting/jadwal-wisuda/${id}`;
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token || '',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message || 'Jadwal wisuda berhasil dihapus.');

                // Refresh DataTable
                if (typeof window.jadwalWisudaTable !== 'undefined') {
                    window.jadwalWisudaTable.draw();
                } else {
                    location.reload();
                }
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat menghapus data.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Terjadi kesalahan saat menghapus data.');
        });
    };
}
