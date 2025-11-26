export function initExports(config) {
    const { exportExcelUrl, exportPdfUrl, exportUrl, exportExcelBtn, exportPdfBtn, printBtn } = config;

    function showMessage(title, message, icon) {
        if (typeof Swal !== 'undefined') {
            Swal.fire(title, message, icon);
        } else {
            alert(`${title}: ${message}`);
        }
    }

    function handleExport($btn, callback) {
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');

        $.ajax({
            url: exportUrl,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!response || (Array.isArray(response) && response.length === 0)) {
                    showMessage('Info', 'Tidak ada data Wisuda untuk di-export.', 'info');
                    return;
                }

                const headers = [
                    'No',
                    'Angkatan',
                    'Tgl Pendaftaran',
                    'Tgl Penutupan',
                    'Kuota',
                    'Status'
                ];

                const data = response.map((row, index) => [
                    index + 1,
                    row.angkatan || '-',
                    row.tanggal_pendaftaran || '-',
                    row.tanggal_penutupan || '-',
                    row.kuota_wisudawan || '0',
                    row.status || '-'
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
                // Kembalikan tombol ke keadaan semula
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

        handleExport($btn, function(headers, data) {
            try {
                const printWindow = window.open('', '_blank');

                let html = `
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Data Wisuda</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 20px; font-size: 12px; }
                            h1 { text-align: center; margin-bottom: 20px; color: #333; font-size: 18px; text-transform: uppercase; }
                            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                            th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; vertical-align: top; }
                            th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
                            tr:nth-child(even) { background-color: #f9f9f9; }
                            .text-center { text-align: center; }
                            .text-right { text-align: right; }
                            @media print {
                                @page { size: landscape; margin: 1cm; }
                                th { background-color: #ccc !important; -webkit-print-color-adjust: exact; }
                            }
                        </style>
                    </head>
                    <body>
                        <h1>Laporan Data Jadwal Wisuda</h1>
                        <p style="text-align: right; font-size: 10px;">Dicetak pada: ${new Date().toLocaleString('id-ID')}</p>
                        <table>
                            <thead>
                                <tr>
                                    ${headers.map(h => `<th>${h}</th>`).join('')}
                                </tr>
                            </thead>
                            <tbody>
                                ${data.map(row => `
                                    <tr>
                                        <td class="text-center">${row[0]}</td> <!-- No -->
                                        <td>${row[1]}</td> <!-- Angkatan -->
                                        <td>${row[2]}</td> <!-- Tgl Pendaftaran -->
                                        <td>${row[3]}</td> <!-- Tgl Penutupan -->
                                        <td class="text-right">${row[4]}</td> <!-- Kuota -->
                                        <td class="text-center">${row[5]}</td> <!-- Status -->
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                        <script>
                            window.onload = function() {
                                window.print();
                                // Opsional: tutup window setelah print (tapi browser modern kadang memblokir ini jika print belum selesai)
                                // setTimeout(() => window.close(), 1000);
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
