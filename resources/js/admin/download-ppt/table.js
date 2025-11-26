export function initDataTable(config) {
    const { getDataUrl } = config;

    return $('#downloadPptTable').DataTable({
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
                data: 'checkbox',
                name: 'checkbox',
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: '50px'
            },
            {
                data: 'no_urut',
                name: 'nomor_urut',
                className: 'text-center',
                width: '80px'
            },
            {
                data: 'nama_lengkap',
                name: 'name_lengkap',
                width: '200px'
            },
            {
                data: 'nim',
                name: 'nim',
                width: '120px'
            },
            {
                data: 'angkatan',
                name: 'angkatan',
                className: 'text-center',
                width: '100px'
            },
            {
                data: 'group',
                name: 'group',
                className: 'text-center',
                width: '100px'
            },
            {
                data: 'sesi',
                name: 'sesi',
                className: 'text-center',
                width: '100px'
            },
            {
                data: 'jenjang',
                name: 'jenjang',
                className: 'text-center',
                width: '80px'
            },
            {
                data: 'fakultas',
                name: 'fakultas',
                width: '150px'
            },
            {
                data: 'prodi',
                name: 'prodi',
                width: '200px'
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
        order: [[1, 'asc']],
        dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"mb-2 md:mb-0"l><"mb-2 md:mb-0"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"mb-2 md:mb-0"i><"mb-2 md:mb-0"p>>',
        drawCallback: function() {
            $('.dataTables_wrapper').addClass('w-full');
        }
    });
}

