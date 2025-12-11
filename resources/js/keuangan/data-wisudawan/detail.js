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
    
    $('#paymentTable_wrapper')[0]?.offsetHeight;
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

$(document).ready(function() {
    // Initialize Payment DataTable
    if ($('#paymentTable').length && typeof window.paymentDataConfig !== 'undefined') {
        const paymentTable = $('#paymentTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: window.paymentDataConfig.getDataUrl,
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
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    width: '80px'
                },
                {
                    data: 'pembayaran',
                    name: 'pembayaran',
                    className: 'text-center',
                    width: '120px'
                },
                {
                    data: 'nominal',
                    name: 'nominal',
                    className: 'text-center',
                    width: '150px'
                },
                {
                    data: 'status_badge',
                    name: 'status',
                    className: 'text-center',
                    width: '140px',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'tanggal_transaksi',
                    name: 'tanggal_transaksi',
                    className: 'text-center',
                    width: '180px'
                },
                {
                    data: 'bank',
                    name: 'bank',
                    className: 'text-center',
                    width: '100px'
                },
                {
                    data: 'va_code',
                    name: 'va_code',
                    className: 'text-center',
                    width: '180px',
                    render: function(data, type, row) {
                        return '<span class="font-mono text-xs">' + (data || '-') + '</span>';
                    }
                },
                {
                    data: 'semester',
                    name: 'semester',
                    className: 'text-center',
                    width: '100px'
                }
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            scrollX: true,
            scrollCollapse: true,
            autoWidth: false,
            order: [[0, 'asc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
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
    }

    // Verify form handler
    $('#verifyForm').on('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Verifikasi Pembayaran?',
            text: 'Apakah Anda yakin ingin memverifikasi pembayaran mahasiswa ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#435ebe',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Verifikasi',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Pembayaran berhasil diverifikasi',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan saat memverifikasi pembayaran'
                        });
                    }
                });
            }
        });
    });
});

