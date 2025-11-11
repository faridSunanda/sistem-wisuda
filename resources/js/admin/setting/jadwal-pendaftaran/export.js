export function initExports(config) {
    const { exportUrl, exportExcelBtn, exportPdfBtn, printBtn } = config;

    function handleExport($btn, exportType, callback) {
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');

        $.ajax({
            url: exportUrl,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Export response:', response);

                // Handle empty response
                if (!response || (Array.isArray(response) && response.length === 0)) {
                    alert('Tidak ada data untuk di-export.');
                    return;
                }

                if (!Array.isArray(response)) {
                    alert('Format data tidak valid: ' + JSON.stringify(response));
                    return;
                }

                const headers = ['No', 'Tahun Wisuda', 'Status', 'Waktu Buka', 'Waktu Tutup'];
                const data = response.map((row, index) => [
                    index + 1,
                    row.tahun_wisuda || '',
                    row.status || '',
                    row.waktu_buka_pendaftaran || '',
                    row.waktu_tutup_pendaftaran || ''
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

    // Excel Export
    $(exportExcelBtn).on('click', function() {
        const $btn = $(this);

        handleExport($btn, 'excel', function(headers, data) {
            try {
                const ws = XLSX.utils.aoa_to_sheet([headers, ...data]);
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Jadwal Pendaftaran');

                const fileName = 'Jadwal_Pendaftaran_' + new Date().toISOString().split('T')[0] + '.xlsx';
                XLSX.writeFile(wb, fileName);
            } catch (error) {
                console.error('Excel creation error:', error);
                alert('Terjadi kesalahan saat membuat file Excel.');
            }
        });
    });

    // PDF Export
    $(exportPdfBtn).on('click', function() {
        const $btn = $(this);

        handleExport($btn, 'pdf', function(headers, data) {
            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('l', 'mm', 'a4');

                doc.setFontSize(16);
                doc.text('Data Jadwal Pendaftaran Wisuda', 14, 15);

                doc.autoTable({
                    head: [headers],
                    body: data,
                    startY: 25,
                    theme: 'grid',
                    styles: { fontSize: 10, cellPadding: 3 },
                    headStyles: {
                        fillColor: [67, 94, 190], // #435ebe
                        textColor: 255,
                        fontStyle: 'bold'
                    },
                    alternateRowStyles: {
                        fillColor: [240, 240, 240]
                    }
                });

                const fileName = 'Jadwal_Pendaftaran_' + new Date().toISOString().split('T')[0] + '.pdf';
                doc.save(fileName);
            } catch (error) {
                console.error('PDF creation error:', error);
                alert('Terjadi kesalahan saat membuat file PDF.');
            }
        });
    });

    // Print
    $(printBtn).on('click', function() {
        const $btn = $(this);

        handleExport($btn, 'print', function(headers, data) {
            try {
                const printWindow = window.open('', '_blank');
                let html = `
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Jadwal Pendaftaran Wisuda</title>
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
                        <h1>Data Jadwal Pendaftaran Wisuda</h1>
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
