export function initExports(config) {
    const { exportExcelUrl, exportPdfUrl, exportUrl, exportExcelBtn, exportPdfBtn, printBtn } = config;

    function handleExport($btn, callback) {
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');

        $.ajax({
            url: exportUrl,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!response || (Array.isArray(response) && response.length === 0)) {
                    showToast('Tidak ada data kuota wisuda untuk di-export.', 'info');
                    return;
                }

                if (!Array.isArray(response)) {
                    showToast('Format data tidak valid.', 'error');
                    return;
                }

                const headers = ['No', 'Tahun Wisuda', 'Periode Wisuda', 'Jumlah Kuota', 'Dibuat Pada'];

                const data = response.map((row, index) => [
                    index + 1,
                    row.tahun_wisuda || '',
                    row.jumlah_kuota ? row.jumlah_kuota.toString() : '0',
                    row.status_periode || '',
                    row.dibuat_pada || ''
                ]);

                callback(headers, data);
            },
            error: function(xhr) {
                console.error('Export error:', xhr.responseText);
                let errorMsg = 'Terjadi kesalahan saat mengambil data.';
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    errorMsg = errorResponse.error || errorMsg;
                } catch (e) {
                }
                showToast(errorMsg, 'error');
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
        handleExport($(this), function(headers, data) {
            try {
                const printWindow = window.open('', '_blank', 'width=1000,height=600');
                let html = `
                    <!DOCTYPE html>
                    <html><head><title>Cetak Kuota Wisuda</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { color: #435ebe; text-align: center; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #435ebe; color: white; }
                        tr:nth-child(even) { background-color: #f8f9fa; }
                        @media print {
                            .no-print { display: none; }
                        }
                    </style>
                    </head><body>
                    <h1>DATA KUOTA WISUDA</h1>
                    <table>
                        <thead>
                            <tr>${headers.map(h => `<th>${h}</th>`).join('')}</tr>
                        </thead>
                        <tbody>
                            ${data.map(row => `<tr>${row.map(cell => `<td>${cell}</td>`).join('')}</tr>`).join('')}
                        </tbody>
                    </table>
                    <script>
                        window.onload = function() {
                            window.print();
                            window.onafterprint = function() {
                                setTimeout(() => window.close(), 500);
                            };
                        };
                    <\/script>
                    </body></html>`;

                printWindow.document.write(html);
                printWindow.document.close();
            } catch (error) {
                console.error('Print error:', error);
                showToast('Terjadi kesalahan saat mencetak data.', 'error');
            }
        });
    });

    function showToast(message, type = 'info') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        } else {
            alert(message);
        }
    }
}
