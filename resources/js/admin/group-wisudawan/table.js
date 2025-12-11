const COLUMN_INDICES = {
    GROUP_WISUDAWAN_ID: 0,
    NOMOR_URUT: 1,
    GROUP_ID: 2,
    SESI_ID: 3,
    CHECKBOX: 4
};

function syncColumnWidths(api) {
    api.columns().every(function() {
        const columnIndex = this.index();
        const column = this;
        
        if (!column.visible()) return;
        
        const bodyCells = api.cells(null, columnIndex).nodes();
        if (!bodyCells || bodyCells.length === 0) return;
        
        const $headerCell = $(this.header());
        const $bodyCells = $(bodyCells);
        const width = `${$bodyCells.first().outerWidth()}px`;
        
        $headerCell.css({ width, 'min-width': width, 'max-width': width });
        
        const $headerTable = $headerCell.closest('table');
        const $bodyTable = $bodyCells.first().closest('table');
        
        if ($headerTable.length && $bodyTable.length) {
            const $headerCol = $headerTable.find('colgroup col').eq(columnIndex);
            const $bodyCol = $bodyTable.find('colgroup col').eq(columnIndex);
            
            if ($headerCol.length) $headerCol.css('width', width);
            if ($bodyCol.length) $bodyCol.css('width', width);
        }
    });
    
    $('#downloadPptTable_wrapper')[0]?.offsetHeight;
}

function setupScrollSync() {
    const $scrollBody = $('.dataTables_scrollBody');
    const $scrollHead = $('.dataTables_scrollHead');
    const $scrollHeadInner = $('.dataTables_scrollHeadInner');
    
    if (!$scrollBody.length || !$scrollHead.length) return;

    $scrollBody.off('scroll.sync').on('scroll.sync', function() {
        const scrollLeft = $(this).scrollLeft();
        $scrollHead.scrollLeft(scrollLeft);
        $scrollHeadInner.scrollLeft(scrollLeft);
    });
    
    $scrollHead.on('scroll.sync', function() {
        $scrollBody.scrollLeft($(this).scrollLeft());
    });
}

function showAjaxError() {
    const message = 'Terjadi kesalahan saat memuat data. Silakan refresh halaman.';
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
            confirmButtonColor: '#435ebe'
        });
    } else {
        alert(message);
    }
}

function initCheckboxHeader(api) {
    const checkboxHeader = $('#selectAllHeader');
    if (!checkboxHeader.length) return;
    
    const checkboxColumn = api.column(COLUMN_INDICES.CHECKBOX).header();
    if (!checkboxColumn || $(checkboxColumn).find('#selectAllHeader').length) return;
    
    $(checkboxColumn).html(checkboxHeader[0].outerHTML);
    
    $(document).off('change', '#selectAllHeader').on('change', '#selectAllHeader', function() {
        const isChecked = $(this).is(':checked');
        $('.wisudawan-checkbox:not(:disabled)').prop('checked', isChecked);
        $('#selectAllCheckbox').prop('checked', isChecked);
    });
}

function initializeTableLayout(api) {
    setTimeout(() => {
        initCheckboxHeader(api);
        syncColumnWidths(api);
        setupScrollSync();
    }, 50);
}

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
                showAjaxError();
            }
        },
        columns: [
            {
                data: 'group_wisudawan_id',
                name: 'group_wisudawan_id',
                title: '',
                visible: false
            },
            {
                data: 'nomor_urut',
                name: 'nomor_urut',
                title: '',
                visible: false
            },
            {
                data: 'group_id',
                name: 'group_id',
                title: '',
                visible: false
            },
            {
                data: 'sesi_id',
                name: 'sesi_id',
                title: '',
                visible: false
            },
            {
                data: 'checkbox',
                name: 'checkbox',
                title: '',
                orderable: false,
                searchable: false,
                className: 'text-center reorder',
                width: '130px'
            },
            {
                data: 'nama_lengkap',
                name: 'name_lengkap',
                title: 'Nama Lengkap',
                className: 'text-center',
                width: '300px'
            },
            {
                data: 'nim',
                name: 'nim',
                title: 'NIM',
                className: 'text-center',
                width: '200px'
            },
            {
                data: 'angkatan',
                name: 'angkatan',
                title: 'Angkatan',
                className: 'text-center',
                width: '150px'
            },
            {
                data: 'group',
                name: 'group',
                title: 'Group',
                className: 'text-center',
                width: '150px'
            },
            {
                data: 'sesi',
                name: 'sesi',
                title: 'Sesi',
                className: 'text-center',
                width: '150px'
            },
            {
                data: 'jenjang',
                name: 'jenjang',
                title: 'Jenjang',
                className: 'text-center',
                width: '150px'
            },
            {
                data: 'fakultas',
                name: 'fakultas',
                title: 'Fakultas',
                className: 'text-center',
                width: '150px',
            },
            {
                data: 'prodi',
                name: 'prodi',
                title: 'Prodi',
                className: 'text-center',
                width: '200px'
            },
            {
                data: 'aksi',
                name: 'aksi',
                title: 'Aksi',
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
        order: [[COLUMN_INDICES.NOMOR_URUT, 'asc']],
        dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"mb-2 md:mb-0"l><"mb-2 md:mb-0"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"mb-2 md:mb-0"i><"mb-2 md:mb-0"p>>',
        drawCallback: function() {
            const api = this.api();
            $('.dataTables_wrapper').addClass('w-full');
            initializeTableLayout(api);
        },
        initComplete: function() {
            const api = this.api();
            setTimeout(() => {
                initCheckboxHeader(api);
                syncColumnWidths(api);
                setupScrollSync();
                initRowSortable(table, config);
            }, 100);
        }
    });

    table.on('draw', function() {
        setTimeout(() => initRowSortable(table, config), 100);
    });

    return table;
}

function getGroupWisudawanId(row, data) {
    return data.group_wisudawan_id || $(row).find('.wisudawan-checkbox').data('group-wisudawan-id') || null;
}

function extractRowOrderData(table, tbody) {
    const orders = [];
    let groupId = null;
    let sesiId = null;
    
    tbody.find('tr').each(function(index) {
        const row = table.row(this);
        if (!row.length) return;
        
        const data = row.data();
        const groupWisudawanId = getGroupWisudawanId(this, data);
        
        if (!groupId && data.group_id) groupId = data.group_id;
        if (!sesiId && data.sesi_id) sesiId = data.sesi_id;
        
        if (groupWisudawanId) {
            orders.push({
                id: groupWisudawanId,
                nomor_urut: index + 1
            });
        }
    });
    
    return { orders, groupId, sesiId };
}

function updateUrutan(updateUrutanUrl, orders, groupId, sesiId, table) {
    $.ajax({
        url: updateUrutanUrl,
        type: 'POST',
        data: {
            orders,
            group_id: groupId,
            sesi_id: sesiId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                table.ajax.reload(null, false);
            } else {
                showUpdateError(response.message || 'Gagal memperbarui urutan.');
            }
        },
        error: function(xhr) {
            console.error('Error updating urutan:', xhr);
            showUpdateError('Terjadi kesalahan saat memperbarui urutan.');
            table.ajax.reload(null, false);
        }
    });
}

function showUpdateError(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
            confirmButtonColor: '#435ebe'
        });
    }
}

function initRowSortable(table, config) {
    const { updateUrutanUrl } = config;
    const tbody = $('#downloadPptTable tbody');
    
    if (tbody.hasClass('ui-sortable')) {
        tbody.sortable('destroy');
    }
    
    tbody.sortable({
        handle: 'td.reorder .drag-handle',
        axis: 'y',
        cursor: 'move',
        helper: function(e, tr) {
            const $originals = $(tr).children();
            const $helper = $(tr).clone();
            $helper.children().each(function(index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper[0];
        },
        placeholder: 'sortable-placeholder',
        start: function(e, ui) {
            ui.placeholder.height(ui.item.height());
            ui.item.css('background-color', '#f0f0f0');
        },
        stop: function(e, ui) {
            ui.item.css('background-color', '');
            
            if (!updateUrutanUrl) return;
            
            const { orders, groupId, sesiId } = extractRowOrderData(table, tbody);
            
            if (orders.length > 0 && groupId && sesiId) {
                updateUrutan(updateUrutanUrl, orders, groupId, sesiId, table);
            }
        }
    });
}
