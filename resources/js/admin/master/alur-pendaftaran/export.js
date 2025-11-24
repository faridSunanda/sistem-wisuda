export function initExports(config) {
    const { exportExcelUrl, exportPdfUrl, exportUrl, exportExcelBtn, exportPdfBtn, printBtn } = config;

    function showMessage(title, message, icon) {
        if (typeof Swal !== 'undefined') {
            Swal.fire(title, message, icon);
        } else {
            alert(`${title}: ${message}`);
        }
    }

    function handleExport($btn, exportType, callback) {
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');

        $.ajax({
            url: exportUrl,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!response || (Array.isArray(response) && response.length === 0)) {
                    showMessage('Info', 'Tidak ada data untuk di-export.', 'info');
                    return;
                }

                if (!Array.isArray(response)) {
                    showMessage('Error', 'Format data tidak valid.', 'error');
                    return;
                }

                const headers = ['No', 'No Urut', 'Judul', 'Keterangan'];
                const data = response.map((row, index) => [
                    index + 1,
                    row.no_urut || '',
                    row.judul || '',
                    row.keterangan || ''
                ]);

                callback(headers, data);
            },
            error: function(xhr) {
                console.error('Export error:', xhr.responseText);
                let errorMsg = 'Terjadi kesalahan saat mengambil data.';
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    errorMsg = errorResponse.error || errorMsg;
                } catch (e) {}

                showMessage('Error', errorMsg, 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    }

    $(exportExcelBtn).on('click', function() {
        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');

        window.location.href = exportExcelUrl;

        setTimeout(() => {
            $btn.prop('disabled', false).html(originalHtml);
        }, 3000);
    });

    $(exportPdfBtn).on('click', function() {
        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');

        window.location.href = exportPdfUrl;

        setTimeout(() => {
            $btn.prop('disabled', false).html(originalHtml);
        }, 3000);
    });

    $(printBtn).on('click', function() {
        const $btn = $(this);

        handleExport($btn, 'print', function(headers, data) {
            try {
                const printWindow = window.open('', '_blank');
                let html = `
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Alur Pendaftaran</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 20px; font-size: 12px; }
                            h1 { text-align: center; margin-bottom: 20px; color: #333; font-size: 18px; }
                            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                            th { background-color: #f2f2f2; font-weight: bold; color: #333; }
                            tr:nth-child(even) { background-color: #f9f9f9; }
                            .no-print { display: none; }
                        </style>
                    </head>
                    <body>
                        <h1>Data Alur Pendaftaran</h1>
                        <table>
                            <thead>
                                <tr>
                                    ${headers.map(h => `<th>${h}</th>`).join('')}
                                </tr>
                            </thead>
                            <tbody>
                                ${data.map(row => `<tr>${row.map(cell => `<td>${cell}</td>`).join('')}</tr>`).join('')}
                            </tbody>
                        </table>
                        <script>
                            window.onload = function() {
                                window.print();
                                setTimeout(() => window.close(), 1000);
                            };
                        <\/script>
                    </body>
                    </html>
                `;

                printWindow.document.write(html);
                printWindow.document.close();
            } catch (error) {
                console.error('Print error:', error);
                showMessage('Error', 'Terjadi kesalahan saat mencetak data.', 'error');
            }
        });
    });
}
