export function initFilters(config, table) {
    const { resetFiltersBtn, filterSelectors } = config;
    const konfirmasiSemuaContainer = $('#konfirmasiSemuaContainer');
    const prosesSemuaContainer = $('#prosesSemuaContainer');

    function isAnyFilterActive() {
        return $(filterSelectors).filter(function() {
            return $(this).val() !== '';
        }).length > 0;
    }

    function updateResetState() {
        $(resetFiltersBtn).prop('disabled', !isAnyFilterActive());
    }

    function toggleActionButtons() {
        const status = $('#filterStatus').val();
        if (status === 'menunggu_konfirmasi') {
            konfirmasiSemuaContainer.removeClass('hidden');
            prosesSemuaContainer.addClass('hidden');
        } else if (status === 'sudah_bayar') {
            konfirmasiSemuaContainer.addClass('hidden');
            prosesSemuaContainer.removeClass('hidden');
        } else {
            konfirmasiSemuaContainer.addClass('hidden');
            prosesSemuaContainer.addClass('hidden');
        }
    }

    $(filterSelectors).on('change', function() {
        table.draw();
        updateResetState();
        toggleActionButtons();
    });

    $(resetFiltersBtn).on('click', function() {
        $(filterSelectors).val('');
        table.draw();
        updateResetState();
        toggleActionButtons();
    });

    updateResetState();
    toggleActionButtons();
}
