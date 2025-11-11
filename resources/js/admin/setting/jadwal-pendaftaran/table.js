export function initDataTable(config) {
    const { getDataUrl } = config;

    return $('#jadwalPendaftaranTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: getDataUrl,
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('AJAX Error:', xhr.responseText);
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
                data: 'tahun_wisuda',
                name: 'tahun_wisuda',
                className: 'text-center',
                width: '100px'
            },
            {
                data: 'status_badge',
                name: 'status',
                orderable: true,
                searchable: true,
                width: '120px'
            },
            {
                data: 'waktu_buka_formatted',
                name: 'waktu_buka_pendaftaran',
                width: '180px'
            },
            {
                data: 'waktu_tutup_formatted',
                name: 'waktu_tutup_pendaftaran',
                width: '180px'
            },
            {
                data: 'status_waktu',
                name: 'status_waktu',
                orderable: false,
                searchable: false,
                width: '150px'
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
        order: [[1, 'desc']],
        dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"mb-2 md:mb-0"l><"mb-2 md:mb-0"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"mb-2 md:mb-0"i><"mb-2 md:mb-0"p>>',
        drawCallback: function() {
            $('.dataTables_wrapper').addClass('w-full');
        }
    });
}
