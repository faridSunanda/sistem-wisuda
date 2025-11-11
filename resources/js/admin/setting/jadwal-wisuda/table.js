export function initDataTable(config) {
    const { getDataUrl } = config;

    return $('#jadwalWisudaTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: getDataUrl,
            error: function(xhr, error, thrown) {
                console.error('DataTable error:', error);
                alert('Terjadi kesalahan saat memuat data. Silakan refresh halaman.');
            }
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: '50px'
            },
            {
                data: 'nama_kegiatan',
                name: 'nama_kegiatan',
                orderable: true,
                searchable: true
            },
            {
                data: 'waktu_formatted',
                name: 'waktu_pelaksanaan',
                orderable: true,
                searchable: false
            },
            {
                data: 'tempat_pelaksanaan',
                name: 'tempat_pelaksanaan',
                orderable: true,
                searchable: true
            },
            {
                data: 'keterangan',
                name: 'keterangan',
                orderable: true,
                searchable: true,
                render: function(data, type, row) {
                    if (type === 'display' && data && data.length > 100) {
                        return data.substr(0, 100) + '...';
                    }
                    return data;
                }
            },
            {
                data: 'aksi',
                name: 'aksi',
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: '120px'
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        scrollX: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[2, 'desc']],
        dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"mb-2 md:mb-0"l><"mb-2 md:mb-0"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"mb-2 md:mb-0"i><"mb-2 md:mb-0"p>>',
        drawCallback: function() {
            $('.dataTables_wrapper').addClass('w-full');
        }
    });
}
