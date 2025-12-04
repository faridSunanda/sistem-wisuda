export function initFilters(config, table) {
    const { resetFiltersBtn, filterSelectors } = config;

    function isAnyFilterActive() {
        return $(filterSelectors).filter(function() {
            return $(this).val() !== '';
        }).length > 0;
    }

    function updateResetState() {
        $(resetFiltersBtn).prop('disabled', !isAnyFilterActive());
    }

    $(filterSelectors).on('change', function() {
        table.draw();
        updateResetState();
    });

    $(resetFiltersBtn).on('click', function() {
        $(filterSelectors).val('');
        table.draw();
        updateResetState();
    });

    updateResetState();
}
