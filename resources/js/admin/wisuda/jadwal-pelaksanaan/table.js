export function initDataTable(config) {
    const { getDataUrl } = config;

    return $('#jadwalPelaksanaanTable').DataTable({
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
                data: 'nama_kegiatan',
                name: 'nama_kegiatan',
                width: '150px'
            },
            {
                data: 'sesi',
                name: 'sesi',
                className: 'text-center',
                width: '100px'
            },
            {
                data: 'waktu_pelaksanaan',
                name: 'waktu_pelaksanaan',
                className: 'text-center',
                width: '120px'
            },
            {
                data: 'tanggal_pelaksanaan',
                name: 'tanggal_pelaksanaan',
                width: '150px'
            },
            {
                data: 'tempat_pelaksanaan',
                name: 'tempat_pelaksanaan',
                width: '200px'
            },
            {
                data: 'keterangan',
                name: 'keterangan',
                width: '250px'
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
        order: [[4, 'asc']],
        dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"mb-2 md:mb-0"l><"mb-2 md:mb-0"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"mb-2 md:mb-0"i><"mb-2 md:mb-0"p>>',
        drawCallback: function() {
            $('.dataTables_wrapper').addClass('w-full');
        }
    });
}

