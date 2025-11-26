import { initDataTable } from './table.js';
import { initActions } from './actions.js';

window.initDownloadPpt = function(config) {
    try {
        const table = initDataTable(config);
        window.downloadPptTable = table;

        initActions(config, table);
        initDropdownToggles();
    } catch (error) {
        console.error('Error initializing Download PPT:', error);
    }
};

/**
 * Initialize dropdown toggle functionality for Group and Sesi selects
 */
function initDropdownToggles() {
    initDropdownToggle('toggleGroupDropdown', 'targetGroupId', 'groupChevron');
    initDropdownToggle('toggleSesiDropdown', 'targetSesiId', 'sesiChevron');
}

/**
 * Initialize dropdown toggle for a specific select element
 * @param {string} toggleBtnId - ID of the toggle button
 * @param {string} selectId - ID of the select element
 * @param {string} chevronId - ID of the chevron icon
 */
function initDropdownToggle(toggleBtnId, selectId, chevronId) {
    const toggleBtn = document.getElementById(toggleBtnId);
    const select = document.getElementById(selectId);
    const chevron = document.getElementById(chevronId);

    if (!toggleBtn || !select || !chevron) return;

    let isOpen = false;

    const updateChevron = (open) => {
        isOpen = open;
        chevron.classList.toggle('rotate-180', open);
    };

    const openDropdown = () => {
        select.focus();
        setTimeout(() => {
            select.click();
            const mouseDownEvent = new MouseEvent('mousedown', {
                bubbles: true,
                cancelable: true,
                view: window
            });
            select.dispatchEvent(mouseDownEvent);
        }, 10);
    };

    // Toggle button click handler
    toggleBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        updateChevron(!isOpen);
        openDropdown();
    });

    // Select element event handlers
    select.addEventListener('mousedown', () => updateChevron(true));
    select.addEventListener('click', () => updateChevron(true));
    select.addEventListener('focus', () => {
        if (!isOpen) updateChevron(true);
    });
    select.addEventListener('change', () => updateChevron(false));
    select.addEventListener('blur', () => {
        setTimeout(() => updateChevron(false), 200);
    });

    // Click outside handler
    document.addEventListener('click', (e) => {
        if (!select.contains(e.target) && !toggleBtn.contains(e.target)) {
            updateChevron(false);
        }
    });
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initialize);
} else {
    initialize();
}

function initialize() {
    if (typeof window.downloadPptConfig !== 'undefined' && typeof window.initDownloadPpt === 'function') {
        window.initDownloadPpt(window.downloadPptConfig);
    }
}

