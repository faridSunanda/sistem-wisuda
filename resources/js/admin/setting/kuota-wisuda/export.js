// Export Functions
export function initExports(config) {
    const { exportUrl, exportExcelBtn, exportPdfBtn, printBtn } = config;

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
                    // Jika tidak bisa parse JSON, gunakan default message
                }
                showToast(errorMsg, 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    }

    // --- Excel Export ---
    $(exportExcelBtn).on('click', function() {
        handleExport($(this), function(headers, data) {
            try {
                if (typeof XLSX === 'undefined') {
                    return showToast('Library Excel (XLSX) tidak terload.', 'error');
                }
                const ws = XLSX.utils.aoa_to_sheet([headers, ...data]);
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Kuota Wisuda');

                ws['!cols'] = [ {wch: 5}, {wch: 15}, {wch: 15}, {wch: 15}, {wch: 15}, {wch: 20} ];

                const fileName = `Kuota_Wisuda_${new Date().toISOString().split('T')[0]}.xlsx`;
                XLSX.writeFile(wb, fileName);
            } catch (error) {
                console.error('Excel creation error:', error);
                showToast('Terjadi kesalahan saat membuat file Excel.', 'error');
            }
        });
    });

    // --- PDF Export ---
    $(exportPdfBtn).on('click', function() {
        handleExport($(this), function(headers, data) {
            try {
                if (typeof jspdf === 'undefined' || typeof doc.autoTable === 'undefined') {
                    return showToast('Library PDF (jsPDF) tidak terload.', 'error');
                }
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('l', 'mm', 'a4');

                doc.setFontSize(16);
                doc.setTextColor(40);
                doc.text('DATA KUOTA WISUDA', 14, 15);

                doc.autoTable({
                    head: [headers],
                    body: data,
                    startY: 25,
                    theme: 'grid',
                    headStyles: {
                        fillColor: [67, 94, 190],
                        textColor: 255,
                        fontStyle: 'bold'
                    },
                    alternateRowStyles: {
                        fillColor: [240, 240, 240]
                    }
                });

                const fileName = `Kuota_Wisuda_${new Date().toISOString().split('T')[0]}.pdf`;
                doc.save(fileName);
            } catch (error) {
                console.error('PDF creation error:', error);
                showToast('Terjadi kesalahan saat membuat file PDF.', 'error');
            }
        });
    });

    // --- Print ---
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
