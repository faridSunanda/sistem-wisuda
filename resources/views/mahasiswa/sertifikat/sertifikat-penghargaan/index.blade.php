@extends('mahasiswa.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Notifikasi di atas -->
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
            <h1 class="text-xl md:text-2xl font-semibold text-gray-900 mb-2 pb-2 border-b-4 border-blue-900 inline-block">Sertifikat Penghargaan</h1>
            <div class="flex items-center bg-orange-50 border border-orange-200 rounded-lg px-4 py-3 mt-4">
                <svg class="w-5 h-5 text-orange-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-orange-800">Isi minimal 1 sertifikat dan maksimal 5 sertifikat.</p>
            </div>
        </div>

        <!-- Form -->
        <form id="form-penghargaan" action="{{ route('mahasiswa.sertifikat.penghargaan.store') }}" method="POST" class="p-6">
            @csrf
            
            <div id="sertifikat-container" class="space-y-6">
                @if($sertifikats->count() > 0)
                    @foreach($sertifikats as $index => $sertifikat)
                    <div class="sertifikat-item border border-gray-200 rounded-lg p-6 relative">
                        <!-- Tombol Hapus di pojok kanan atas -->
                        @if($sertifikats->count() > 1)
                        <button type="button" class="remove-sertifikat absolute -top-2 -right-2 w-8 h-8 bg-white border border-red-300 rounded-full flex items-center justify-center text-red-600 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        @endif
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sertifikat</label>
                                <input type="text" name="nama_sertifikat[]" 
                                       value="{{ $sertifikat->nama_sertifikat }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       placeholder="Contoh: Juara 1 Lomba Programming" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                                <input type="text" name="penerbit[]" 
                                       value="{{ $sertifikat->penerbit }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       placeholder="Contoh: Universitas Wahid Hasyim" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Terbit</label>
                                <input type="date" name="tanggal_terbit[]" 
                                       value="{{ $sertifikat->tanggal_terbit->format('Y-m-d') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="sertifikat-item border border-gray-200 rounded-lg p-6 relative">
                        <!-- Tombol Hapus  -->
                        <button type="button" class="remove-sertifikat absolute -top-2 -right-2 w-8 h-8 bg-white border border-red-300 rounded-full flex items-center justify-center text-red-600 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-200 shadow-sm opacity-0 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        
                        <!-- Form fields -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sertifikat</label>
                                <input type="text" name="nama_sertifikat[]" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       placeholder="Contoh: Juara 1 Lomba Programming" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                                <input type="text" name="penerbit[]" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       placeholder="Contoh: Universitas Wahid Hasyim" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Terbit</label>
                                <input type="date" name="tanggal_terbit[]" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mt-8 pt-6 border-t border-gray-200">
                <button type="button" id="tambah-sertifikat" 
                        class="flex items-center justify-center px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Sertifikat
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const maxSertifikat = 5;
        let sertifikatCount = {{ $sertifikats->count() ?: 1 }};
        const container = document.getElementById('sertifikat-container');
        const tambahBtn = document.getElementById('tambah-sertifikat');

        function updateRemoveButtons() {
            const items = container.querySelectorAll('.sertifikat-item');
            items.forEach((item, index) => {
                const removeBtn = item.querySelector('.remove-sertifikat');
                removeBtn.style.display = items.length > 1 ? 'flex' : 'none';
            });
            
            tambahBtn.style.display = items.length >= maxSertifikat ? 'none' : 'flex';
        }

        function addSertifikat() {
            if (sertifikatCount >= maxSertifikat) return;

            const newItem = document.createElement('div');
            newItem.className = 'sertifikat-item border border-gray-200 rounded-lg p-6 relative';
            newItem.innerHTML = `
                <button type="button" class="remove-sertifikat absolute -top-2 -right-2 w-8 h-8 bg-white border border-red-300 rounded-full flex items-center justify-center text-red-600 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-200 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sertifikat</label>
                        <input type="text" name="nama_sertifikat[]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="Contoh: Juara 1 Lomba Programming" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                        <input type="text" name="penerbit[]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="Contoh: Universitas Wahid Hasyim" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Terbit</label>
                        <input type="date" name="tanggal_terbit[]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                </div>
            `;

            container.appendChild(newItem);
            sertifikatCount++;
            updateRemoveButtons();
        }

        function initEventListeners() {
            tambahBtn.addEventListener('click', addSertifikat);

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-sertifikat') || 
                    e.target.closest('.remove-sertifikat')) {
                    
                    const removeBtn = e.target.classList.contains('remove-sertifikat') 
                        ? e.target 
                        : e.target.closest('.remove-sertifikat');
                    removeBtn.closest('.sertifikat-item').remove();
                    sertifikatCount--;
                    updateRemoveButtons();
                }
            });

            updateRemoveButtons();
        }

        document.addEventListener('DOMContentLoaded', initEventListeners);
    })();
</script>
@endpush