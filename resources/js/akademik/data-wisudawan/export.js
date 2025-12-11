export function initExports(config) {
    const {
        exportExcelUrl,
        exportPdfUrl,
        exportUrl,
        exportExcelBtn,
        exportPdfBtn,
        printBtn,
    } = config;

    $(exportExcelBtn).on("click", function () {
        handleExport(this, exportExcelUrl, "Excel");
    });

    $(exportPdfBtn).on("click", function () {
        handleExport(this, exportPdfUrl, "PDF");
    });

    $(printBtn).on("click", function () {
        handlePrint(this, exportUrl);
    });
}

function handleExport(buttonEl, url, type) {
    const $btn = $(buttonEl);
    const originalHtml = $btn.html();
    $btn.prop("disabled", true).html(
        '<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>'
    );

    const filters = getFilters();
    const queryString = $.param(filters);
    const fullUrl = url + (queryString ? "?" + queryString : "");

    window.location.href = fullUrl;

    setTimeout(() => {
        $btn.prop("disabled", false).html(originalHtml);
    }, 2000);
}

function handlePrint(buttonEl, exportUrl) {
    const $btn = $(buttonEl);
    $btn.prop("disabled", true).html(
        '<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>'
    );

    getTableData(exportUrl, function ({ headers, data }) {
        const printWindow = window.open("", "_blank");
        const html = generatePrintHtml(headers, data);

        printWindow.document.write(html);
        printWindow.document.close();
        printWindow.focus();

        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 250);

        $btn.prop("disabled", false).html(
            '<i class="fas fa-print text-gray-600"></i> <span>Print</span>'
        );
    });
}

function getFilters() {
    return {
        fakultas: $("#filterFakultas").val(),
        prodi: $("#filterProdi").val(),
        tahun_masuk: $("#filterTahunMasuk").val(),
        jenjang: $("#filterJenjang").val(),
    };
}

function getTableData(exportUrl, callback) {
    $.ajax({
        url: exportUrl,
        method: "GET",
        data: getFilters(),
        success: function (response) {
            const headers = [
                "No",
                "Nama Lengkap",
                "NIM",
                "Tahun Masuk",
                "Jenjang",
                "Fakultas",
                "Prodi",
            ];
            const data = response.map((row, index) => [
                index + 1,
                row.nama || "",
                row.nim || "",
                row.tahun_masuk || "",
                row.jenjang || "",
                row.fakultas || "",
                row.prodi || "",
            ]);
            callback({ headers, data });
        },
        error: function () {
            alert("Terjadi kesalahan saat mengambil data untuk export.");
        },
    });
}

function generatePrintHtml(headers, data) {
    const isCenterColumn = (index) => [0, 2, 3, 4, 5, 6].includes(index);
    const formatDate = () => {
        const now = new Date();
        const bulan = [
            "",
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];
        return (
            now.getDate() +
            " " +
            bulan[now.getMonth()] +
            " " +
            now.getFullYear()
        );
    };

    return `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Laporan Data Wisudawan</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; font-size: 10px; }
                h1 { text-align: center; margin-bottom: 10px; font-size: 16px; }
                .date { text-align: center; margin-bottom: 20px; font-size: 11px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { border: 1px solid #000; padding: 6px; text-align: left; }
                th { background-color: #3b82f6; color: white; font-weight: bold; text-align: center; }
                td.text-center { text-align: center; }
                tr:nth-child(even) { background-color: #f9fafb; }
                @media print { @page { margin: 1cm; } }
            </style>
        </head>
        <body>
            <h1>Laporan Data Wisudawan</h1>
            <div class="date">Tanggal: ${formatDate()}</div>
            <table>
                <thead>
                    <tr>
                        ${headers
                            .map(
                                (h, index) =>
                                    `<th class="${
                                        isCenterColumn(index)
                                            ? "text-center"
                                            : ""
                                    }">${h}</th>`
                            )
                            .join("")}
                    </tr>
                </thead>
                <tbody>
                    ${data
                        .map(
                            (row) =>
                                `<tr>${row
                                    .map(
                                        (cell, index) =>
                                            `<td class="${
                                                isCenterColumn(index)
                                                    ? "text-center"
                                                    : ""
                                            }">${cell}</td>`
                                    )
                                    .join("")}</tr>`
                        )
                        .join("")}
                </tbody>
            </table>
        </body>
        </html>
    `;
}
