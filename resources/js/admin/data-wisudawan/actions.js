// Action Handlers for Data Wisudawan
export function initActions(config) {
    const { detailUrl, editUrl, deleteUrl } = config;

    window.lihatData = function(id) {
        if (detailUrl) {
            window.location.href = detailUrl.replace(':id', id);
        } else {
            window.location.href = `/admin/data-wisudawan/${id}`;
        }
    };

    window.editData = function(id) {
        if (editUrl) {
            window.location.href = editUrl.replace(':id', id);
        } else {
            window.location.href = `/admin/data-wisudawan/${id}/edit`;
        }
    };

    window.hapusData = function(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            return;
        }

        const url = deleteUrl ? deleteUrl.replace(':id', id) : `/admin/data-wisudawan/${id}`;
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

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
                alert(data.message || 'Data berhasil dihapus.');
                if (typeof window.dataWisudawanTable !== 'undefined') {
                    window.dataWisudawanTable.draw();
                } else {
                    location.reload();
                }
            } else {
                alert(data.message || 'Terjadi kesalahan saat menghapus data.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data.');
        });
    };
}

