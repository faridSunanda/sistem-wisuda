// DataTable Initialization
export function initDataTable(config) {
    const { getDataUrl } = config;
    
    return $('#dataWisudawanTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: getDataUrl,
            data: function(d) {
                d.fakultas = $('#filterFakultas').val();
                d.prodi = $('#filterProdi').val();
                d.tahun_masuk = $('#filterTahunMasuk').val();
                d.jenjang = $('#filterJenjang').val();
            },
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
            {data: 'nama', name: 'nama'},
            {data: 'nim', name: 'nim'},
            {data: 'tahun_masuk', name: 'tahun_masuk', className: 'text-center'},
            {data: 'jenjang', name: 'jenjang', className: 'text-center'},
            {data: 'fakultas', name: 'fakultas'},
            {data: 'prodi', name: 'prodi'},
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
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[1, 'asc']],
        dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"mb-2 md:mb-0"l><"mb-2 md:mb-0"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"mb-2 md:mb-0"i><"mb-2 md:mb-0"p>>',
        drawCallback: function() {
            $('.dataTables_wrapper').addClass('w-full');
        }
    });
}

