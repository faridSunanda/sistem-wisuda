// Export Functions
export function initExports(config) {
    const { exportUrl, exportExcelBtn, exportPdfBtn, printBtn } = config;

    function getTableData(callback) {
        const filters = {
            fakultas: $('#filterFakultas').val(),
            prodi: $('#filterProdi').val(),
            tahun_masuk: $('#filterTahunMasuk').val(),
            jenjang: $('#filterJenjang').val()
        };
        
        $.ajax({
            url: exportUrl,
            method: 'GET',
            data: filters,
            success: function(response) {
                const headers = ['No', 'Nama Lengkap', 'NIM', 'Tahun Masuk', 'Jenjang', 'Fakultas', 'Prodi'];
                const data = response.map((row, index) => [
                    index + 1,
                    row.nama || '',
                    row.nim || '',
                    row.tahun_masuk || '',
                    row.jenjang || '',
                    row.fakultas || '',
                    row.prodi || ''
                ]);
                callback({ headers, data });
            },
            error: function() {
                alert('Terjadi kesalahan saat mengambil data untuk export.');
            }
        });
    }

    // Excel Export
    $(exportExcelBtn).on('click', function() {
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');
        
        getTableData(function({ headers, data }) {
            const ws = XLSX.utils.aoa_to_sheet([headers, ...data]);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Data Wisudawan');
            
            const fileName = 'Data_Wisudawan_' + new Date().toISOString().split('T')[0] + '.xlsx';
            XLSX.writeFile(wb, fileName);
            
            $btn.prop('disabled', false).html('<i class="fas fa-file-excel text-green-600"></i> <span>Excel</span>');
        });
    });

    // Print
    $(printBtn).on('click', function() {
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');
        
        getTableData(function({ headers, data }) {
            const printWindow = window.open('', '_blank');
            let html = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Data Wisudawan</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { text-align: center; margin-bottom: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; font-weight: bold; }
                        @media print {
                            @page { margin: 1cm; }
                        }
                    </style>
                </head>
                <body>
                    <h1>Data Wisudawan</h1>
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
                </body>
                </html>
            `;
            
            printWindow.document.write(html);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
            
            $btn.prop('disabled', false).html('<i class="fas fa-print text-gray-600"></i> <span>Print</span>');
        });
    });

    // PDF Export
    $(exportPdfBtn).on('click', function() {
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');
        
        getTableData(function({ headers, data }) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4');
            
            doc.autoTable({
                head: [headers],
                body: data,
                theme: 'striped',
                styles: { fontSize: 9 },
                headStyles: { fillColor: [59, 130, 246] },
                margin: { top: 20 }
            });
            
            const fileName = 'Data_Wisudawan_' + new Date().toISOString().split('T')[0] + '.pdf';
            doc.save(fileName);
            
            $btn.prop('disabled', false).html('<i class="fas fa-file-pdf text-red-600"></i> <span>PDF</span>');
        });
    });
}

