@extends('mahasiswa.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    
    @if(session('success'))
    <div class="mb-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-xl md:text-2xl font-semibold text-gray-900 mb-2 pb-2 border-b-4 border-blue-900 inline-block">Pembayaran Wisuda</h1>
            
            @if($error)
            <div class="flex items-center bg-red-50 border border-red-200 rounded-lg px-4 py-3 mt-4">
                <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-red-800">{{ $error }}</p>
            </div>
            @endif

            <div class="flex items-center bg-orange-50 border border-orange-200 rounded-lg px-4 py-3 mt-4">
                <svg class="w-5 h-5 text-orange-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-orange-800">Lakukan pembayaran sebesar <strong>Rp{{ number_format($tagihan, 0, ',', '.') }}</strong> untuk melanjutkan tahap pendaftaran wisuda.</p>
            </div>
        </div>

        <!-- Detail Pembayaran -->
        <div class="p-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-lg font-semibold text-gray-900 mb-2">Total Tagihan</h2>
                        <p class="text-2xl font-bold text-blue-900">Rp{{ number_format($tagihan, 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-lg font-semibold text-gray-900 mb-2">No BRIVA</h2>
                        <p class="text-xl font-mono text-gray-800 bg-white px-3 py-2 rounded border">{{ $brivaNumber }}</p>
                    </div>
                    
                    <div>
                        <button id="check-status-btn" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium shadow-md">
                            Cek Status Pembayaran
                        </button>
                    </div>
                </div>
            </div>

            <!-- Status Pembayaran -->
            <div id="payment-status" class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8 hidden">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Pembayaran</h2>
                <div class="flex items-center">
                    <div id="status-icon" class="w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div>
                        <p id="status-text" class="text-gray-700 font-medium">Memeriksa status...</p>
                        <p id="status-message" class="text-sm text-gray-500">Sedang memeriksa status pembayaran terbaru.</p>
                        <p id="paid-time" class="text-sm text-green-600 font-medium hidden"></p>
                    </div>
                </div>
            </div>

            <!-- Tutorial Pembayaran BRIVA -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Tutorial Pembayaran BRIVA</h2>
                <p class="text-gray-700 mb-6">Pilih metode pembayaran, lalu ikuti langkahnya. Simpan bukti/struk setelah bayar.</p>
                
                <!-- Panel Kode BRIVA Compact -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-receipt text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-700">Kode BRIVA</h3>
                                <p class="text-lg font-mono text-gray-800">{{ $brivaNumber }}</p>
                            </div>
                        </div>
                        <button id="copy-briva-btn" class="flex items-center gap-2 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            <i class="fas fa-copy"></i>
                            <span>Copy</span>
                        </button>
                    </div>
                    <div id="copy-success" class="mt-2 text-center text-sm text-green-600 hidden">
                        <i class="fas fa-check-circle mr-1"></i>
                        Kode BRIVA berhasil disalin!
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                    <div class="metode-pembayaran border border-gray-300 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors cursor-pointer" data-method="brimo">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-mobile-alt text-blue-600"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-700">BRImo (m-banking BRI)</p>
                    </div>
                    
                    <div class="metode-pembayaran border border-gray-300 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors cursor-pointer" data-method="atm">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-credit-card text-blue-600"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-700">ATM BRI</p>
                    </div>
                    
                    <div class="metode-pembayaran border border-gray-300 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors cursor-pointer" data-method="ibanking">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-globe text-blue-600"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Internet Banking BRI</p>
                    </div>
                    
                    <div class="metode-pembayaran border border-gray-300 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors cursor-pointer" data-method="bank-lain">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-university text-blue-600"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-700">m-banking / ATM Bank Lain</p>
                    </div>
                    
                    <div class="metode-pembayaran border border-gray-300 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors cursor-pointer" data-method="teller">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-user-tie text-blue-600"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Teller / Bank</p>
                    </div>
                </div>
                
                <!-- Deskripsi Tutorial -->
                <div id="tutorial-content" class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <h3 id="tutorial-title" class="text-lg font-semibold text-gray-900 mb-4">Pilih Metode Pembayaran</h3>
                    <div id="tutorial-steps" class="text-gray-700">
                        <p class="text-center text-gray-500">Klik salah satu metode pembayaran di atas untuk melihat panduan lengkap</p>
                    </div>
                    <div id="tutorial-notes" class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg hidden">
                        <h4 class="font-semibold text-yellow-800 mb-2">Catatan Penting:</h4>
                        <ul id="notes-list" class="list-disc list-inside text-sm text-yellow-700 space-y-1">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .metode-pembayaran.active {
        border-color: #1e40af;
        background-color: #eff6ff;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkStatusBtn = document.getElementById('check-status-btn');
        const paymentStatus = document.getElementById('payment-status');
        const statusIcon = document.getElementById('status-icon');
        const statusText = document.getElementById('status-text');
        const statusMessage = document.getElementById('status-message');
        const paidTime = document.getElementById('paid-time');
        
        const tutorialTitle = document.getElementById('tutorial-title');
        const tutorialSteps = document.getElementById('tutorial-steps');
        const tutorialNotes = document.getElementById('tutorial-notes');
        const notesList = document.getElementById('notes-list');

        // Elements untuk copy BRIVA
        const copyBrivaBtn = document.getElementById('copy-briva-btn');
        const copySuccess = document.getElementById('copy-success');

        // Data tutorial untuk setiap metode pembayaran
        const tutorialData = {
            'brimo': {
                title: 'BRImo (m-banking BRI)',
                steps: [
                    'Buka aplikasi BRImo — login.',
                    'Pilih menu Pembayaran → BRIVA (atau Virtual Account).',
                    `Masukkan <strong class="font-mono bg-yellow-100 px-1 rounded">{{ $brivaNumber }}</strong> (kode BRIVA) lalu tekan Lanjut.`,
                    'Periksa nama merchant dan jumlah. Jika benar, konfirmasi transaksi.',
                    'Simpan screenshot atau unduh bukti pembayaran.',
                    '<em>Catatan: Beberapa versi BRImo menampilkan menu "Virtual Account" atau "BRIVA" di halaman utama.</em>'
                ],
                notes: [
                    'Periksa nama merchant dan jumlah sebelum konfirmasi.',
                    'Simpan bukti pembayaran / screenshot sampai status di merchant terupdate.',
                    'Nomor BRIVA bersifat unik — masukkan tanpa spasi.',
                    'Jika gagal, cek kembali nomor dan saldo; hubungi bank atau merchant jika perlu.'
                ]
            },
            'atm': {
                title: 'ATM BRI',
                steps: [
                    'Masukkan kartu dan PIN di mesin ATM BRI.',
                    'Pilih Transaksi Lainnya → Transfer → Ke Rek BRI (atau Virtual Account jika tersedia).',
                    `Masukkan nomor BRIVA (<strong class="font-mono bg-yellow-100 px-1 rounded">{{ $brivaNumber }}</strong>), tekan Benar.`,
                    'Periksa detail (merchant & jumlah). Lanjutkan dan ambil struk sebagai bukti.'
                ],
                notes: [
                    'Periksa nama merchant dan jumlah sebelum konfirmasi.',
                    'Simpan bukti pembayaran / screenshot sampai status di merchant terupdate.',
                    'Nomor BRIVA bersifat unik — masukkan tanpa spasi.',
                    'Jika gagal, cek kembali nomor dan saldo; hubungi bank atau merchant jika perlu.'
                ]
            },
            'ibanking': {
                title: 'Internet Banking BRI',
                steps: [
                    'Login ke Internet Banking BRI.',
                    'Pilih menu Transfer → Virtual Account atau Transfer ke Rekening BRI.',
                    `Masukkan kode BRIVA (<strong class="font-mono bg-yellow-100 px-1 rounded">{{ $brivaNumber }}</strong>), lalu ikuti instruksi konfirmasi.`,
                    'Download/print bukti pembayaran jika perlu.'
                ],
                notes: [
                    'Periksa nama merchant dan jumlah sebelum konfirmasi.',
                    'Simpan bukti pembayaran / screenshot sampai status di merchant terupdate.',
                    'Nomor BRIVA bersifat unik — masukkan tanpa spasi.',
                    'Jika gagal, cek kembali nomor dan saldo; hubungi bank atau merchant jika perlu.'
                ]
            },
            'bank-lain': {
                title: 'm-banking / ATM Bank Lain',
                steps: [
                    'Buka aplikasi m-banking bank kamu atau gunakan ATM.',
                    'Pilih menu Transfer → Ke Rekening Bank Lain atau Virtual Account.',
                    `Untuk beberapa bank: pilih Transfer > Virtual Account, lalu masukkan kode BRIVA (<strong class="font-mono bg-yellow-100 px-1 rounded">{{ $brivaNumber }}</strong>).`,
                    'Periksa nama merchant dan jumlah, konfirmasi. Simpan bukti pembayaran.',
                    '<em>Tip: Jika metode pembayaran tidak ada kata "BRIVA", pilih "Virtual Account" atau pilih transfer antar bank lalu masukkan BRIVA sebagai nomor rekening.</em>'
                ],
                notes: [
                    'Periksa nama merchant dan jumlah sebelum konfirmasi.',
                    'Simpan bukti pembayaran / screenshot sampai status di merchant terupdate.',
                    'Nomor BRIVA bersifat unik — masukkan tanpa spasi.',
                    'Jika gagal, cek kembali nomor dan saldo; hubungi bank atau merchant jika perlu.'
                ]
            },
            'teller': {
                title: 'Via Teller / Bank (Bayar langsung ke bank)',
                steps: [
                    'Datang ke cabang bank, bilang mau bayar via BRIVA / Virtual Account.',
                    `Berikan kode BRIVA (<strong class="font-mono bg-yellow-100 px-1 rounded">{{ $brivaNumber }}</strong>) dan jumlah kepada petugas.`,
                    'Petugas akan memproses dan memberi struk; simpan sebagai bukti.'
                ],
                notes: [
                    'Periksa nama merchant dan jumlah sebelum konfirmasi.',
                    'Simpan bukti pembayaran / screenshot sampai status di merchant terupdate.',
                    'Nomor BRIVA bersifat unik — masukkan tanpa spasi.',
                    'Jika gagal, cek kembali nomor dan saldo; hubungi bank atau merchant jika perlu.'
                ]
            }
        };

        // Fungsi copy BRIVA code
        copyBrivaBtn.addEventListener('click', function() {
            const brivaText = '{{ $brivaNumber }}';
            
            // Menggunakan Clipboard API
            navigator.clipboard.writeText(brivaText).then(function() {
                // Show success message
                copySuccess.classList.remove('hidden');
                
                // Change button text temporarily
                const originalHtml = copyBrivaBtn.innerHTML;
                copyBrivaBtn.innerHTML = '<i class="fas fa-check"></i><span>Copied!</span>';
                copyBrivaBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                copyBrivaBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                
                // Reset button after 2 seconds
                setTimeout(function() {
                    copyBrivaBtn.innerHTML = originalHtml;
                    copyBrivaBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                    copyBrivaBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    copySuccess.classList.add('hidden');
                }, 2000);
            }).catch(function(err) {
                console.error('Failed to copy: ', err);
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = brivaText;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                // Show success message even with fallback
                copySuccess.classList.remove('hidden');
                setTimeout(function() {
                    copySuccess.classList.add('hidden');
                }, 2000);
            });
        });

        // Menambahkan interaksi pada metode pembayaran
        const metodePembayaran = document.querySelectorAll('.metode-pembayaran');
        
        metodePembayaran.forEach(metode => {
            metode.addEventListener('click', function() {
                const method = this.dataset.method;
                
                // Hapus active class dari semua metode
                metodePembayaran.forEach(m => m.classList.remove('active'));
                // Tambahkan active class ke metode yang diklik
                this.classList.add('active');
                
                // Tampilkan tutorial yang sesuai
                showTutorial(method);
            });
        });

        function showTutorial(method) {
            const data = tutorialData[method];
            
            if (!data) return;

            // Update title
            tutorialTitle.textContent = data.title;

            // Update steps
            tutorialSteps.innerHTML = '';
            data.steps.forEach((step, index) => {
                const stepElement = document.createElement('div');
                stepElement.className = 'mb-3 flex items-start';
                stepElement.innerHTML = `
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-sm font-medium mr-3 mt-1">${index + 1}</span>
                    <div class="flex-1">${step}</div>
                `;
                tutorialSteps.appendChild(stepElement);
            });

            // Update notes
            if (data.notes && data.notes.length > 0) {
                notesList.innerHTML = '';
                data.notes.forEach(note => {
                    const noteItem = document.createElement('li');
                    noteItem.textContent = note;
                    notesList.appendChild(noteItem);
                });
                tutorialNotes.classList.remove('hidden');
            } else {
                tutorialNotes.classList.add('hidden');
            }
        }

        // Cek status pembayaran
        checkStatusBtn.addEventListener('click', function() {
            const btnText = checkStatusBtn.innerHTML;
            checkStatusBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memeriksa...';
            checkStatusBtn.disabled = true;

            fetch('{{ route("mahasiswa.pembayaran.check-status") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                paymentStatus.classList.remove('hidden');
                
                if (data.success) {
                    updateStatusDisplay(data.status, data.message, data.paid_at);
                } else {
                    statusIcon.className = 'w-10 h-10 rounded-full bg-red-500 flex items-center justify-center mr-4';
                    statusIcon.innerHTML = '<i class="fas fa-exclamation-triangle text-white"></i>';
                    statusText.textContent = 'Error';
                    statusMessage.textContent = data.message;
                    paidTime.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                paymentStatus.classList.remove('hidden');
                statusIcon.className = 'w-10 h-10 rounded-full bg-red-500 flex items-center justify-center mr-4';
                statusIcon.innerHTML = '<i class="fas fa-exclamation-triangle text-white"></i>';
                statusText.textContent = 'Error';
                statusMessage.textContent = 'Terjadi kesalahan saat memeriksa status';
                paidTime.classList.add('hidden');
            })
            .finally(() => {
                checkStatusBtn.innerHTML = btnText;
                checkStatusBtn.disabled = false;
            });
        });

        function updateStatusDisplay(status, message, paidAt) {
            const statusConfig = {
                'paid': { color: 'bg-green-500', icon: 'fa-check', text: 'Lunas' },
                'pending': { color: 'bg-orange-500', icon: 'fa-clock', text: 'Menunggu Verifikasi' },
                'unpaid': { color: 'bg-gray-500', icon: 'fa-times', text: 'Belum Bayar' },
                'expired': { color: 'bg-red-500', icon: 'fa-exclamation-triangle', text: 'Kedaluwarsa' },
                'failed': { color: 'bg-red-500', icon: 'fa-times-circle', text: 'Gagal' }
            };

            const config = statusConfig[status] || statusConfig['unpaid'];
            
            statusIcon.className = `w-10 h-10 rounded-full ${config.color} flex items-center justify-center mr-4`;
            statusIcon.innerHTML = `<i class="fas ${config.icon} text-white"></i>`;
            statusText.textContent = config.text;
            statusMessage.textContent = message;

            if (status === 'paid' && paidAt) {
                paidTime.textContent = `Dibayar pada: ${new Date(paidAt).toLocaleString('id-ID')}`;
                paidTime.classList.remove('hidden');
            } else {
                paidTime.classList.add('hidden');
            }
        }

        // Auto check status every 30 seconds if payment is pending
        setInterval(() => {
            if (paymentStatus.classList.contains('hidden') === false) {
                const currentStatus = statusText.textContent;
                if (currentStatus === 'Menunggu Verifikasi' || currentStatus === 'Belum Bayar') {
                    checkStatusBtn.click();
                }
            }
        }, 30000);

        // Default: pilih metode pertama
        if (metodePembayaran.length > 0) {
            metodePembayaran[0].click();
        }
    });
</script>
@endpush