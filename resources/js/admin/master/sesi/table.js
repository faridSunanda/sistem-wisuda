export function initDataTable(config) {
    const { getDataUrl } = config;

    return $('#sesiTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: getDataUrl,
            error: function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error', 'Terjadi kesalahan saat memuat data.', 'error');
                } else {
                    alert('Terjadi kesalahan saat memuat data.');
                }
            }
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                className: 'text-center align-middle',
                orderable: false,
                searchable: false
            },
            {
                data: 'name',
                name: 'name',
                className: 'align-middle'
            },
            {
                data: 'keterangan',
                name: 'keterangan',
                className: 'align-middle'
            },
            {
                data: 'aksi',
                name: 'aksi',
                orderable: false,
                searchable: false,
                className: 'text-center align-middle'
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        scrollX: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[1, 'asc']],
        dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"mb-2 md:mb-0"l><"mb-2 md:mb-0"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"mb-2 md:mb-0"i><"mb-2 md:mb-0"p>>',
        drawCallback: function() {
            $('.dataTables_wrapper').addClass('w-full');
        }
    });
}
