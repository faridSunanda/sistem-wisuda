// Export Functions
export function initExports(config) {
    const { exportExcelUrl, exportPdfUrl, exportUrl, exportExcelBtn, exportPdfBtn, printBtn } = config;

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

    // Excel Export - menggunakan Maatwebsite/Laravel-Excel
    $(exportExcelBtn).on('click', function() {
        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');
        
        const filters = {
            fakultas: $('#filterFakultas').val(),
            prodi: $('#filterProdi').val(),
            tahun_masuk: $('#filterTahunMasuk').val(),
            jenjang: $('#filterJenjang').val()
        };
        
        // Build URL with query parameters
        const queryString = $.param(filters);
        const url = exportExcelUrl + (queryString ? '?' + queryString : '');
        
        // Use window.location to trigger download
        window.location.href = url;
        
        // Reset button after a short delay
        setTimeout(() => {
            $btn.prop('disabled', false).html(originalHtml);
        }, 2000);
    });

    // PDF Export - menggunakan dompdf
    $(exportPdfBtn).on('click', function() {
        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');
        
        const filters = {
            fakultas: $('#filterFakultas').val(),
            prodi: $('#filterProdi').val(),
            tahun_masuk: $('#filterTahunMasuk').val(),
            jenjang: $('#filterJenjang').val()
        };
        
        // Build URL with query parameters
        const queryString = $.param(filters);
        const url = exportPdfUrl + (queryString ? '?' + queryString : '');
        
        // Use window.location to trigger download
        window.location.href = url;
        
        // Reset button after a short delay
        setTimeout(() => {
            $btn.prop('disabled', false).html(originalHtml);
        }, 2000);
    });

    // Print
    $(printBtn).on('click', function() {
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');
        
        getTableData(function({ headers, data }) {
            const printWindow = window.open('', '_blank');
            
            // Helper function to determine if column should be center-aligned
            const isCenterColumn = (index) => {
                // No (0), NIM (2), Tahun Masuk (3), Jenjang (4), Fakultas (5), Prodi (6) -> center
                return index === 0 || index === 2 || index === 3 || index === 4 || index === 5 || index === 6;
            };
            
            let html = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Laporan Data Wisudawan</title>
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            margin: 20px; 
                            font-size: 10px;
                        }
                        h1 { 
                            text-align: center; 
                            margin-bottom: 10px; 
                            font-size: 16px;
                        }
                        .date {
                            text-align: center;
                            margin-bottom: 20px;
                            font-size: 11px;
                        }
                        table { 
                            width: 100%; 
                            border-collapse: collapse; 
                            margin-top: 10px; 
                        }
                        th, td { 
                            border: 1px solid #000; 
                            padding: 6px; 
                            text-align: left; 
                        }
                        th { 
                            background-color: #3b82f6; 
                            color: white; 
                            font-weight: bold; 
                            text-align: center;
                        }
                        td.text-center {
                            text-align: center;
                        }
                        tr:nth-child(even) {
                            background-color: #f9fafb;
                        }
                        @media print {
                            @page { margin: 1cm; }
                        }
                    </style>
                </head>
                <body>
                    <h1>Laporan Data Wisudawan</h1>
                    <div class="date">Tanggal: ${(() => {
                        const now = new Date();
                        const bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                                      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        return now.getDate() + ' ' + bulan[now.getMonth()] + ' ' + now.getFullYear();
                    })()}</div>
                    <table>
                        <thead>
                            <tr>
                                ${headers.map((h, index) => 
                                    `<th class="${isCenterColumn(index) ? 'text-center' : ''}">${h}</th>`
                                ).join('')}
                            </tr>
                        </thead>
                        <tbody>
                            ${data.map(row => 
                                `<tr>${
                                    row.map((cell, index) => 
                                        `<td class="${isCenterColumn(index) ? 'text-center' : ''}">${cell}</td>`
                                    ).join('')
                                }</tr>`
                            ).join('')}
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
}

