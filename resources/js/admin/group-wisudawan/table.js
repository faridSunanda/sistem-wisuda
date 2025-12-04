// Sync column widths
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
    
    $('#downloadPptTable_wrapper')[0]?.offsetHeight;
}

// Setup scroll sync
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

// Initialize DataTable
export function initDataTable(config) {
    const { getDataUrl } = config;

    const table = $('#downloadPptTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: getDataUrl,
            type: 'GET',
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
                data: 'checkbox',
                name: 'checkbox',
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: '80px'
            },
            {
                data: 'no_urut',
                name: 'nomor_urut',
                className: 'text-center',
                width: '105px'
            },
            {
                data: 'nama_lengkap',
                name: 'name_lengkap',
                width: '300px'
            },
            {
                data: 'nim',
                name: 'nim',
                className: 'text-center',
                width: '200px'
            },
            {
                data: 'angkatan',
                name: 'angkatan',
                className: 'text-center',
                width: '150px'
            },
            {
                data: 'group',
                name: 'group',
                className: 'text-center',
                width: '150px'
            },
            {
                data: 'sesi',
                name: 'sesi',
                className: 'text-center',
                width: '150px'
            },
            {
                data: 'jenjang',
                name: 'jenjang',
                className: 'text-center',
                width: '150px'
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
