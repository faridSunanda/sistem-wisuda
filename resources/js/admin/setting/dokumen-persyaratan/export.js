export function initExports(config) {
    const { exportExcelUrl, exportPdfUrl, exportUrl, exportExcelBtn, exportPdfBtn, printBtn } = config;

    function handleExport($btn, exportType, callback) {
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');

        $.ajax({
            url: exportUrl,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Export response:', response);

                if (!response || (Array.isArray(response) && response.length === 0)) {
                    alert('Tidak ada data untuk di-export.');
                    return;
                }

                if (!Array.isArray(response)) {
                    alert('Format data tidak valid: ' + JSON.stringify(response));
                    return;
                }

                const headers = ['No', 'Tipe Dokumen', 'Nama Dokumen', 'Keterangan', 'Berkas'];
                const data = response.map((row, index) => [
                    index + 1,
                    row.tipe_dokumen || '',
                    row.nama_dokumen || '',
                    row.keterangan || '',
                    row.berkas || ''
                ]);

                callback(headers, data);
            },
            error: function(xhr, status, error) {
                console.error('Export error:', xhr.responseText);
                let errorMsg = 'Terjadi kesalahan saat mengambil data.';

                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    errorMsg = errorResponse.error || errorMsg;
                } catch (e) {
                    alert(errorMsg);
                }

                alert(errorMsg);
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
        }, 2000);
    });

    $(exportPdfBtn).on('click', function() {
        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');
        
        window.location.href = exportPdfUrl;
        
        setTimeout(() => {
            $btn.prop('disabled', false).html(originalHtml);
        }, 2000);
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
                        <title>Dokumen Persyaratan</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 20px; }
                            h1 { text-align: center; margin-bottom: 20px; color: #333; }
                            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                            th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                            th { background-color: #f8f9fa; font-weight: bold; color: #333; }
                            tr:nth-child(even) { background-color: #f8f9fa; }
                        </style>
                    </head>
                    <body>
                        <h1>Data Dokumen Persyaratan</h1>
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
                                setTimeout(() => window.close(), 500);
                            };
                        <\/script>
                    </body>
                    </html>
                `;

                printWindow.document.write(html);
                printWindow.document.close();
            } catch (error) {
                console.error('Print error:', error);
                alert('Terjadi kesalahan saat mencetak data.');
            }
        });
    });
}
