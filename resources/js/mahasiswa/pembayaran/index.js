document.addEventListener('DOMContentLoaded', function() {
    const paymentData = document.getElementById('payment-data');
    if (!paymentData) return;

    const brivaNumber = paymentData.dataset.brivaNumber;
    const checkStatusUrl = paymentData.dataset.checkStatusUrl;
    const csrfToken = paymentData.dataset.csrfToken;

    const checkStatusBtn = document.getElementById('check-status-btn');
    const toastContainer = document.getElementById('toast-container');
    const copyBrivaBtn = document.getElementById('copy-briva-btn');
    const copySuccess = document.getElementById('copy-success');
    const tutorialTitle = document.getElementById('tutorial-title');
    const tutorialSteps = document.getElementById('tutorial-steps');
    const tutorialNotes = document.getElementById('tutorial-notes');
    const notesList = document.getElementById('notes-list');

    const COMMON_NOTES = [
        'Periksa nama merchant dan jumlah sebelum konfirmasi.',
        'Simpan bukti pembayaran / screenshot sampai status di merchant terupdate.',
        'Nomor BRIVA bersifat unik — masukkan tanpa spasi.',
        'Jika gagal, cek kembali nomor dan saldo; hubungi bank atau merchant jika perlu.'
    ];

    const tutorialData = {
        'brimo': {
            title: 'BRImo (m-banking BRI)',
            steps: [
                'Buka aplikasi BRImo — login.',
                'Pilih menu Pembayaran → BRIVA (atau Virtual Account).',
                `Masukkan <strong class="font-mono bg-yellow-100 px-1 rounded">${brivaNumber}</strong> (kode BRIVA) lalu tekan Lanjut.`,
                'Periksa nama merchant dan jumlah. Jika benar, konfirmasi transaksi.',
                'Simpan screenshot atau unduh bukti pembayaran.',
                '<em>Catatan: Beberapa versi BRImo menampilkan menu "Virtual Account" atau "BRIVA" di halaman utama.</em>'
            ],
            notes: COMMON_NOTES
        },
        'atm': {
            title: 'ATM BRI',
            steps: [
                'Masukkan kartu dan PIN di mesin ATM BRI.',
                'Pilih Transaksi Lainnya → Transfer → Ke Rek BRI (atau Virtual Account jika tersedia).',
                `Masukkan nomor BRIVA (<strong class="font-mono bg-yellow-100 px-1 rounded">${brivaNumber}</strong>), tekan Benar.`,
                'Periksa detail (merchant & jumlah). Lanjutkan dan ambil struk sebagai bukti.'
            ],
            notes: COMMON_NOTES
        },
        'ibanking': {
            title: 'Internet Banking BRI',
            steps: [
                'Login ke Internet Banking BRI.',
                'Pilih menu Transfer → Virtual Account atau Transfer ke Rekening BRI.',
                `Masukkan kode BRIVA (<strong class="font-mono bg-yellow-100 px-1 rounded">${brivaNumber}</strong>), lalu ikuti instruksi konfirmasi.`,
                'Download/print bukti pembayaran jika perlu.'
            ],
            notes: COMMON_NOTES
        },
        'bank-lain': {
            title: 'm-banking / ATM Bank Lain',
            steps: [
                'Buka aplikasi m-banking bank kamu atau gunakan ATM.',
                'Pilih menu Transfer → Ke Rekening Bank Lain atau Virtual Account.',
                `Untuk beberapa bank: pilih Transfer > Virtual Account, lalu masukkan kode BRIVA (<strong class="font-mono bg-yellow-100 px-1 rounded">${brivaNumber}</strong>).`,
                'Periksa nama merchant dan jumlah, konfirmasi. Simpan bukti pembayaran.',
                '<em>Tip: Jika metode pembayaran tidak ada kata "BRIVA", pilih "Virtual Account" atau pilih transfer antar bank lalu masukkan BRIVA sebagai nomor rekening.</em>'
            ],
            notes: COMMON_NOTES
        },
        'teller': {
            title: 'Via Teller / Bank (Bayar langsung ke bank)',
            steps: [
                'Datang ke cabang bank, bilang mau bayar via BRIVA / Virtual Account.',
                `Berikan kode BRIVA (<strong class="font-mono bg-yellow-100 px-1 rounded">${brivaNumber}</strong>) dan jumlah kepada petugas.`,
                'Petugas akan memproses dan memberi struk; simpan sebagai bukti.'
            ],
            notes: COMMON_NOTES
        }
    };

    const TOAST_CONFIG = {
        'success': { 
            bgColor: 'bg-green-50', 
            borderColor: 'border-green-500', 
            iconColor: 'text-green-600', 
            textColor: 'text-green-800',
            icon: 'fa-check-circle'
        },
        'error': { 
            bgColor: 'bg-red-50', 
            borderColor: 'border-red-500', 
            iconColor: 'text-red-600', 
            textColor: 'text-red-800',
            icon: 'fa-exclamation-triangle'
        },
        'warning': { 
            bgColor: 'bg-orange-50', 
            borderColor: 'border-orange-500', 
            iconColor: 'text-orange-600', 
            textColor: 'text-orange-800',
            icon: 'fa-clock'
        },
        'info': { 
            bgColor: 'bg-blue-50', 
            borderColor: 'border-blue-500', 
            iconColor: 'text-blue-600', 
            textColor: 'text-blue-800',
            icon: 'fa-info-circle'
        }
    };

    const STATUS_CONFIG = {
        'paid': { type: 'success', title: 'Pembayaran Lunas' },
        'pending': { type: 'warning', title: 'Menunggu Verifikasi' },
        'unpaid': { type: 'info', title: 'Belum Bayar' },
        'expired': { type: 'error', title: 'Kedaluwarsa' },
        'failed': { type: 'error', title: 'Gagal' }
    };

    copyBrivaBtn.addEventListener('click', function() {
        const originalHtml = copyBrivaBtn.innerHTML;
        
        const showSuccess = () => {
            copySuccess.classList.remove('hidden');
            copyBrivaBtn.innerHTML = '<i class="fas fa-check"></i><span>Copied!</span>';
            copyBrivaBtn.classList.replace('bg-blue-600', 'bg-green-600');
            copyBrivaBtn.classList.replace('hover:bg-blue-700', 'hover:bg-green-700');
            
            setTimeout(() => {
                copyBrivaBtn.innerHTML = originalHtml;
                copyBrivaBtn.classList.replace('bg-green-600', 'bg-blue-600');
                copyBrivaBtn.classList.replace('hover:bg-green-700', 'hover:bg-blue-700');
                copySuccess.classList.add('hidden');
            }, 2000);
        };
        
        navigator.clipboard.writeText(brivaNumber)
            .then(showSuccess)
            .catch(() => {
                const textArea = document.createElement('textarea');
                textArea.value = brivaNumber;
                textArea.style.position = 'fixed';
                textArea.style.opacity = '0';
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showSuccess();
            });
    });

    const metodePembayaran = document.querySelectorAll('.metode-pembayaran');
    
    metodePembayaran.forEach(metode => {
        metode.addEventListener('click', function() {
            metodePembayaran.forEach(m => m.classList.remove('active'));
            this.classList.add('active');
            showTutorial(this.dataset.method);
        });
    });

    function showTutorial(method) {
        const data = tutorialData[method];
        if (!data) return;

        tutorialTitle.innerHTML = `<i class="fas fa-info-circle text-blue-600"></i><span>${data.title}</span>`;

        tutorialSteps.innerHTML = data.steps.map((step, index) => `
            <div class="flex items-start gap-3 p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                <span class="flex-shrink-0 w-7 h-7 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold shadow-sm">${index + 1}</span>
                <div class="flex-1 text-sm md:text-base leading-relaxed">${step}</div>
            </div>
        `).join('');

        if (data.notes?.length > 0) {
            notesList.innerHTML = data.notes.map(note => `<li>${note}</li>`).join('');
            tutorialNotes.classList.remove('hidden');
        } else {
            tutorialNotes.classList.add('hidden');
        }
    }

    function showToast(type, title, message, paidAt = null) {
        const config = TOAST_CONFIG[type] || TOAST_CONFIG['info'];
        const paidAtHtml = paidAt ? `<p class="text-xs ${config.textColor} mt-1">Dibayar pada: ${new Date(paidAt).toLocaleString('id-ID')}</p>` : '';
        
        const toast = document.createElement('div');
        toast.id = 'toast-' + Date.now();
        toast.className = `${config.bgColor} border-l-4 ${config.borderColor} rounded-lg shadow-lg p-3 md:p-4 mb-2 w-full transform transition-all duration-300 translate-x-full opacity-0`;
        
        const closeBtn = document.createElement('button');
        closeBtn.className = `ml-4 flex-shrink-0 ${config.textColor} hover:opacity-70`;
        closeBtn.innerHTML = '<i class="fas fa-times"></i>';
        closeBtn.addEventListener('click', () => {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        });
        
        toast.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas ${config.icon} ${config.iconColor} text-lg"></i>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="font-semibold ${config.textColor} mb-1">${title}</h4>
                    <p class="text-sm ${config.textColor}">${message}</p>
                    ${paidAtHtml}
                </div>
            </div>
        `;
        
        toast.querySelector('.flex.items-start').appendChild(closeBtn);
        toastContainer.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
        }, 10);
        
        setTimeout(() => {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    checkStatusBtn.addEventListener('click', function() {
        const btnText = checkStatusBtn.innerHTML;
        checkStatusBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memeriksa...';
        checkStatusBtn.disabled = true;

        fetch(checkStatusUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showStatusToast(data.status, data.message, data.paid_at);
            } else {
                showToast('error', 'Error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Error', 'Terjadi kesalahan saat memeriksa status');
        })
        .finally(() => {
            checkStatusBtn.innerHTML = btnText;
            checkStatusBtn.disabled = false;
        });
    });

    function showStatusToast(status, message, paidAt) {
        const config = STATUS_CONFIG[status] || STATUS_CONFIG['unpaid'];
        showToast(config.type, config.title, message, paidAt);
    }

    metodePembayaran[0]?.click();
});
