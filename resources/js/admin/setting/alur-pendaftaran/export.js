export function initExports(config) {
    const { exportUrl, exportExcelBtn, exportPdfBtn, printBtn } = config;

    // Excel Export
    $(exportExcelBtn).on('click', function() {
        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');

        $.ajax({
            url: exportUrl,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!Array.isArray(response)) {
                    throw new Error('Invalid response format');
                }

                const headers = ['No', 'No Urut', 'Judul', 'Keterangan'];
                const data = response.map((row, index) => [
                    index + 1,
                    row.no_urut || '',
                    row.judul || '',
                    row.keterangan || ''
                ]);

                const ws = XLSX.utils.aoa_to_sheet([headers, ...data]);
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Alur Pendaftaran');

                const fileName = 'Alur_Pendaftaran_' + new Date().toISOString().split('T')[0] + '.xlsx';
                XLSX.writeFile(wb, fileName);
            },
            error: function(xhr, status, error) {
                console.error('Export error:', error);
                alert('Terjadi kesalahan saat mengambil data untuk export.');
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    // PDF Export
    $(exportPdfBtn).on('click', function() {
        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');

        $.ajax({
            url: exportUrl,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!Array.isArray(response)) {
                    throw new Error('Invalid response format');
                }

                const headers = ['No', 'No Urut', 'Judul', 'Keterangan'];
                const data = response.map((row, index) => [
                    index + 1,
                    row.no_urut || '',
                    row.judul || '',
                    row.keterangan || ''
                ]);

                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('l', 'mm', 'a4');

                doc.setFontSize(16);
                doc.text('Data Alur Pendaftaran', 14, 15);

                doc.autoTable({
                    head: [headers],
                    body: data,
                    startY: 25,
                    theme: 'grid',
                    styles: { fontSize: 10, cellPadding: 3 },
                    headStyles: { fillColor: [59, 130, 246], textColor: 255, fontStyle: 'bold' }
                });

                const fileName = 'Alur_Pendaftaran_' + new Date().toISOString().split('T')[0] + '.pdf';
                doc.save(fileName);
            },
            error: function(xhr, status, error) {
                console.error('Export error:', error);
                alert('Terjadi kesalahan saat mengambil data untuk export.');
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    // Print
    $(printBtn).on('click', function() {
        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');

        $.ajax({
            url: exportUrl,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!Array.isArray(response)) {
                    throw new Error('Invalid response format');
                }

                const headers = ['No', 'No Urut', 'Judul', 'Keterangan'];
                const data = response.map((row, index) => [
                    index + 1,
                    row.no_urut || '',
                    row.judul || '',
                    row.keterangan || ''
                ]);

                const printWindow = window.open('', '_blank');
                let html = `
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Alur Pendaftaran</title>
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
                                setTimeout(() => window.close(), 500);
                            };
                        <\/script>
                    </body>
                    </html>
                `;

                printWindow.document.write(html);
                printWindow.document.close();
            },
            error: function(xhr, status, error) {
                console.error('Export error:', error);
                alert('Terjadi kesalahan saat mengambil data untuk export.');
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
}
