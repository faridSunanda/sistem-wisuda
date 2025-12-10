import { initDataTable } from "./table.js";
import { initFilters } from "./filter.js";
import { initExports } from "./export.js";
import { initActions } from "./actions.js";

window.initDataWisudawan = function (config) {
    try {
        const table = initDataTable(config);
        window.dataWisudawanTable = table;

        initFilters(config, table);
        initExports(config);
        initActions(config);
    } catch (error) {
        console.error("Error initializing Data Wisudawan:", error);
    }
};

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initialize);
} else {
    initialize();
}

function initialize() {
    if (
        typeof window.dataWisudawanConfig !== "undefined" &&
        typeof window.initDataWisudawan === "function"
    ) {
        window.initDataWisudawan(window.dataWisudawanConfig);
    }
}
