@extends('mahasiswa.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 md:p-6">
        <h2 class="text-xl md:text-2xl font-semibold text-gray-900 mb-4 md:mb-6">Sertifikat Organisasi</h2>
        <div class="space-y-4">
            <div class="text-center py-8 md:py-12 text-gray-500">
                <p class="text-sm md:text-base">Halaman Sertifikat Organisasi</p>
            </div>
        </div>
    </div>
</div> 
        <!-- Form -->
        <form id="form-organisasi" action="{{ route('mahasiswa.sertifikat.organisasi.store') }}" method="POST" class="p-6">
            @csrf
            
            <div id="sertifikat-container" class="space-y-6">
                @if($sertifikatOrganisasis->count() > 0)
                    @foreach($sertifikatOrganisasis as $index => $sertifikat)
                    <div class="sertifikat-item border border-gray-200 rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Sertifikat Organisasi</h3>
                            @if($sertifikatOrganisasis->count() > 1)
                            <button type="button" class="remove-sertifikat text-red-600 hover:text-red-700 text-sm font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus
                            </button>
                            @endif
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sertifikat</label>
                                <input type="text" name="sertifikat_organisasi[{{$index}}][nama_sertifikat]" 
                                       value="{{ $sertifikat->nama_sertifikat }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       placeholder="Contoh: Sertifikat Kepanitiaan Seminar Nasional" required>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                                    <input type="date" name="sertifikat_organisasi[{{$index}}][tanggal_mulai]" 
                                           value="{{ $sertifikat->tanggal_mulai->format('Y-m-d') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                                    <input type="date" name="sertifikat_organisasi[{{$index}}][tanggal_selesai]" 
                                           value="{{ $sertifikat->tanggal_selesai->format('Y-m-d') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="sertifikat-item border border-gray-200 rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Sertifikat Organisasi</h3>
                            <button type="button" class="remove-sertifikat text-red-600 hover:text-red-700 text-sm font-medium flex items-center opacity-0 pointer-events-none">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sertifikat</label>
                                <input type="text" name="sertifikat_organisasi[0][nama_sertifikat]" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       placeholder="Contoh: Sertifikat Kepanitiaan Seminar Nasional" required>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                                    <input type="date" name="sertifikat_organisasi[0][tanggal_mulai]" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                                    <input type="date" name="sertifikat_organisasi[0][tanggal_selesai]" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 mt-8 pt-6 border-t border-gray-200">
                <button type="button" id="tambah-sertifikat" 
                        class="flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Sertifikat
                </button>
                <button type="submit" 
                        class="flex-1 sm:flex-none px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
<div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
    {{ session('success') }}
</div>
@endif
@endsection

@push('scripts')
<script>
    (function() {
        const maxSertifikat = 5;
        let sertifikatCount = {{ $sertifikatOrganisasis->count() ?: 1 }};
        const container = document.getElementById('sertifikat-container');
        const tambahBtn = document.getElementById('tambah-sertifikat');

        function updateRemoveButtons() {
            const items = container.querySelectorAll('.sertifikat-item');
            items.forEach((item, index) => {
                const removeBtn = item.querySelector('.remove-sertifikat');
                const title = item.querySelector('h3');
                
                title.textContent = `Sertifikat Organisasi ${index + 1}`;
                removeBtn.style.display = items.length > 1 ? 'flex' : 'none';
                
                // Update input names
                const inputs = item.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    const name = input.name.replace(/\[\d+\]/, `[${index}]`);
                    input.name = name;
                });
            });
            
            tambahBtn.style.display = items.length >= maxSertifikat ? 'none' : 'flex';
        }

        function addSertifikat() {
            if (sertifikatCount >= maxSertifikat) return;

            const newItem = document.createElement('div');
            newItem.className = 'sertifikat-item border border-gray-200 rounded-lg p-6';
            newItem.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Sertifikat Organisasi</h3>
                    <button type="button" class="remove-sertifikat text-red-600 hover:text-red-700 text-sm font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sertifikat</label>
                        <input type="text" name="sertifikat_organisasi[${sertifikatCount}][nama_sertifikat]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="Contoh: Sertifikat Kepanitiaan Seminar Nasional" required>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                            <input type="date" name="sertifikat_organisasi[${sertifikatCount}][tanggal_mulai]" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                            <input type="date" name="sertifikat_organisasi[${sertifikatCount}][tanggal_selesai]" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
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
@endsection

