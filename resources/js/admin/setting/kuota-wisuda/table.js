export function initDataTable(config) {
    const { getDataUrl } = config;

    return $('#kuotaWisudaTable').DataTable({
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
            {
                data: 'tahun_wisuda',
                name: 'pendaftaran_wisudas.tahun_wisuda',
                className: 'text-left'
            },
            {
                data: 'jumlah_kuota_formatted',
                name: 'jumlah_kuota',
                className: 'text-left'
            },
            {
                data: 'status_periode',
                name: 'pendaftaran_wisudas.status',
                className: 'text-center',
                orderable: true,
                searchable: true
            },
            {
                data: 'aksi',
                name: 'aksi',
                className: 'text-center',
                orderable: false,
                searchable: false
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        scrollX: true,
        scrollY: '400px',
        scrollCollapse: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[1, 'desc']],
        dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"mb-2 md:mb-0"l><"mb-2 md:mb-0"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"mb-2 md:mb-0"i><"mb-2 md:mb-0"p>>',
        drawCallback: function() {
            $('.dataTables_wrapper').addClass('w-full');
        }
    });
}
