export function initActions(config) {
    const { detailUrl, editUrl, deleteUrl } = config;

    window.lihatData = function(id) {
        if (detailUrl) {
            window.location.href = detailUrl.replace(':id', id);
        } else {
            window.location.href = `/admin/setting/dokumen-persyaratan/${id}`;
        }
    };

    window.editData = function(id) {
        if (editUrl) {
            window.location.href = editUrl.replace(':id', id);
        } else {
            window.location.href = `/admin/setting/dokumen-persyaratan/${id}/edit`;
        }
    };

    window.hapusData = function(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen persyaratan ini?')) {
            return;
        }

        const url = deleteUrl ? deleteUrl.replace(':id', id) : `/admin/setting/dokumen-persyaratan/${id}`;
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
                alert(data.message || 'Dokumen persyaratan berhasil dihapus.');
                if (typeof window.dokumenPersyaratanTable !== 'undefined') {
                    window.dokumenPersyaratanTable.draw();
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
