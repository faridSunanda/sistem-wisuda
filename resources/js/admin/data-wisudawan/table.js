function syncColumnWidths(api) {
    api.columns().every(function() {
        const columnIndex = this.index();
        const $headerCell = $(this.header());
        const $bodyCells = $(api.cells(null, columnIndex).nodes());
        
        if ($bodyCells.length === 0) return;

        const bodyCellWidth = $bodyCells.first().outerWidth();
        const width = `${bodyCellWidth}px`;
        
        $headerCell.css({
            'width': width,
            'min-width': width,
            'max-width': width
        });
        
        const $headerTable = $headerCell.closest('table');
        const $bodyTable = $bodyCells.first().closest('table');
        
        if ($headerTable.length && $bodyTable.length) {
            const $headerCol = $headerTable.find('colgroup col').eq(columnIndex);
            const $bodyCol = $bodyTable.find('colgroup col').eq(columnIndex);
            
            if ($headerCol.length) {
                $headerCol.css('width', width);
            }
            if ($bodyCol.length) {
                $bodyCol.css('width', width);
            }
        }
    });
    
    $('#dataWisudawanTable_wrapper')[0]?.offsetHeight;
}

function setupScrollSync() {
    const $scrollBody = $('.dataTables_scrollBody');
    const $scrollHead = $('.dataTables_scrollHead');
    const $scrollHeadInner = $('.dataTables_scrollHeadInner');
    
    if (!$scrollBody.length || !$scrollHead.length) return;

    $scrollBody.off('scroll.sync');
    
    $scrollBody.on('scroll.sync', function() {
        const scrollLeft = $(this).scrollLeft();
        $scrollHead.scrollLeft(scrollLeft);
        $scrollHeadInner.scrollLeft(scrollLeft);
    });
    
    $scrollHead.on('scroll.sync', function() {
        const scrollLeft = $(this).scrollLeft();
        $scrollBody.scrollLeft(scrollLeft);
    });
}

export function initDataTable(config) {
    const { getDataUrl } = config;

    const table = $('#dataWisudawanTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: getDataUrl,
            type: 'GET',
            data: function(d) {
                d.fakultas = $('#filterFakultas').val();
                d.prodi = $('#filterProdi').val();
                d.tahun_masuk = $('#filterTahunMasuk').val();
                d.jenjang = $('#filterJenjang').val();
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr.responseText);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat memuat data. Silakan refresh halaman.',
                        confirmButtonColor: '#435ebe'
                    });
                } else {
                    alert('Terjadi kesalahan saat memuat data. Silakan refresh halaman.');
                }
            }
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: '80px'
            },
            {
                data: 'nama',
                name: 'nama',
                width: '300px',
                className: 'text-center',
            },
            {
                data: 'nim',
                name: 'nim',
                className: 'text-center',
                width: '200px'
            },
            {
                data: 'tahun_masuk',
                name: 'tahun_masuk',
                className: 'text-center',
                width: '150px'
            },
            {
                data: 'jenjang',
                name: 'jenjang',
                className: 'text-center',
                width: '130px',
            },
            {
                data: 'fakultas',
                name: 'fakultas',
                width: '200px',
                className: 'text-center',
            },
            {
                data: 'prodi',
                name: 'prodi',
                width: '230px',
                className: 'text-center',
            },
            {
                data: 'aksi',
                name: 'aksi',
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: '150px'
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        scrollX: true,
        scrollCollapse: true,
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[1, 'asc']],
        dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"mb-2 md:mb-0"l><"mb-2 md:mb-0"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"mb-2 md:mb-0"i><"mb-2 md:mb-0"p>>',
        drawCallback: function() {
            const api = this.api();
            $('.dataTables_wrapper').addClass('w-full');
            
            setTimeout(() => {
                syncColumnWidths(api);
                setupScrollSync();
            }, 50);
        },
        initComplete: function() {
            const api = this.api();
            
            setTimeout(() => {
                syncColumnWidths(api);
                setupScrollSync();
                
                setTimeout(() => {
                    syncColumnWidths(api);
                }, 200);
            }, 100);
        }
    });

    return table;
}
