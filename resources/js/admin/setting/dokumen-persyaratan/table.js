// DataTable Initialization
export function initDataTable(config) {
    const { getDataUrl } = config;

    return $('#dokumenPersyaratanTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: getDataUrl,
            error: function() {
                alert('Terjadi kesalahan saat memuat data. Silakan refresh halaman.');
            }
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            {data: 'tipe_dokumen', name: 'tipe_dokumen'},
            {data: 'nama_dokumen', name: 'nama_dokumen'},
            {data: 'keterangan', name: 'keterangan'},
            {
                data: 'berkas_link',
                name: 'berkas',
                orderable: false,
                searchable: false
            },
            {
                data: 'aksi',
                name: 'aksi',
                orderable: false,
                searchable: false,
                className: 'text-center'
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
