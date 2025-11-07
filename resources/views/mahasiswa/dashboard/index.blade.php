@extends('mahasiswa.layouts.app')

@section('content')
<div class="space-y-4 md:space-y-6 pb-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <h3 class="text-xs md:text-sm font-medium text-gray-600 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-green-600"></i>
                    <span>Wisudawan Verified</span>
                </h3>
            </div>
            <div class="flex items-baseline">
                <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $stats['verified'] ?? 128 }}</p>
            </div>
            <div class="mt-3 md:mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php
                        $verifiedPercent = (($stats['verified'] ?? 128) / (($stats['total'] ?? 226) > 0 ? ($stats['total'] ?? 226) : 1)) * 100;
                    @endphp
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $verifiedPercent }}%"></div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <h3 class="text-xs md:text-sm font-medium text-gray-600 flex items-center gap-2">
                    <i class="fa-solid fa-circle-xmark text-blue-500"></i>
                    <span>Wisudawan Not Verified</span>
                </h3>
            </div>
            <div class="flex items-baseline">
                <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $stats['not_verified'] ?? 98 }}</p>
            </div>
            <div class="mt-3 md:mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php
                        $notVerifiedPercent = (($stats['not_verified'] ?? 98) / (($stats['total'] ?? 226) > 0 ? ($stats['total'] ?? 226) : 1)) * 100;
                    @endphp
                    <div class="bg-blue-400 h-2 rounded-full" style="width: {{ $notVerifiedPercent }}%"></div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200 sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <h3 class="text-xs md:text-sm font-medium text-gray-600 flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-pink-500"></i>
                    <span>Mahasiswa Lulus</span>
                </h3>
            </div>
            <div class="flex items-baseline">
                <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $stats['graduated'] ?? 1020 }}</p>
            </div>
            <div class="mt-3 md:mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-pink-500 h-2 rounded-full" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">Wisudawan Berdasarkan Fakultas</h3>
            <div class="h-48 md:h-64 flex items-center justify-center">
                <canvas id="fakultasChart"></canvas>
            </div>
            <div class="mt-3 md:mt-4 space-y-1 md:space-y-2">
                <div class="flex items-center gap-2 text-xs md:text-sm">
                    <div class="w-3 h-3 md:w-4 md:h-4 bg-blue-500 rounded flex-shrink-0"></div>
                    <span>Fakultas Teknik</span>
                </div>
                <div class="flex items-center gap-2 text-xs md:text-sm">
                    <div class="w-3 h-3 md:w-4 md:h-4 bg-green-500 rounded flex-shrink-0"></div>
                    <span>Fakultas Hukum</span>
                </div>
                <div class="flex items-center gap-2 text-xs md:text-sm">
                    <div class="w-3 h-3 md:w-4 md:h-4 bg-yellow-500 rounded flex-shrink-0"></div>
                    <span>Fakultas Ekonomi</span>
                </div>
                <div class="flex items-center gap-2 text-xs md:text-sm">
                    <div class="w-3 h-3 md:w-4 md:h-4 bg-red-500 rounded flex-shrink-0"></div>
                    <span>Fakultas Kedokteran</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">Wisudawan Berdasarkan Tahun Masuk</h3>
            <div class="h-48 md:h-64 flex items-center justify-center">
                <canvas id="tahunMasukChart"></canvas>
            </div>
            <div class="mt-3 md:mt-4 space-y-1 md:space-y-2">
                <div class="flex items-center gap-2 text-xs md:text-sm">
                    <div class="w-3 h-3 md:w-4 md:h-4 bg-green-500 rounded flex-shrink-0"></div>
                    <span>2021</span>
                </div>
                <div class="flex items-center gap-2 text-xs md:text-sm">
                    <div class="w-3 h-3 md:w-4 md:h-4 bg-blue-500 rounded flex-shrink-0"></div>
                    <span>2020</span>
                </div>
                <div class="flex items-center gap-2 text-xs md:text-sm">
                    <div class="w-3 h-3 md:w-4 md:h-4 bg-red-500 rounded flex-shrink-0"></div>
                    <span>2019</span>
                </div>
                <div class="flex items-center gap-2 text-xs md:text-sm">
                    <div class="w-3 h-3 md:w-4 md:h-4 bg-yellow-500 rounded flex-shrink-0"></div>
                    <span>2018</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">Wisudawan Berdasarkan Jenjang</h3>
            <div class="h-48 md:h-64 flex items-center justify-center">
                <canvas id="jenjangChart"></canvas>
            </div>
            <div class="mt-3 md:mt-4 space-y-1 md:space-y-2">
                <div class="flex items-center gap-2 text-xs md:text-sm">
                    <div class="w-3 h-3 md:w-4 md:h-4 bg-purple-500 rounded flex-shrink-0"></div>
                    <span>S1</span>
                </div>
                <div class="flex items-center gap-2 text-xs md:text-sm">
                    <div class="w-3 h-3 md:w-4 md:h-4 bg-pink-500 rounded flex-shrink-0"></div>
                    <span>S2</span>
                </div>
                <div class="flex items-center gap-2 text-xs md:text-sm">
                    <div class="w-3 h-3 md:w-4 md:h-4 bg-yellow-500 rounded flex-shrink-0"></div>
                    <span>S3</span>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $fakultasData = $charts['fakultas'] ?? [35, 30, 25, 10];
    $tahunMasukData = $charts['tahun_masuk'] ?? [45, 25, 20, 10];
    $jenjangData = $charts['jenjang'] ?? [70, 20, 10];
@endphp

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const fakultasData = {
        labels: ['Fakultas Teknik', 'Fakultas Hukum', 'Fakultas Ekonomi', 'Fakultas Kedokteran'],
        datasets: [{
            data: @json($fakultasData),
            backgroundColor: ['#3b82f6', '#10b981', '#eab308', '#ef4444']
        }]
    };

    const tahunMasukData = {
        labels: ['2021', '2020', '2019', '2018'],
        datasets: [{
            data: @json($tahunMasukData),
            backgroundColor: ['#10b981', '#3b82f6', '#ef4444', '#eab308']
        }]
    };

    const jenjangData = {
        labels: ['S1', 'S2', 'S3'],
        datasets: [{
            data: @json($jenjangData),
            backgroundColor: ['#a855f7', '#ec4899', '#eab308']
        }]
    };

    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    };

    new Chart(document.getElementById('fakultasChart'), {
        type: 'pie',
        data: fakultasData,
        options: chartOptions
    });

    new Chart(document.getElementById('tahunMasukChart'), {
        type: 'pie',
        data: tahunMasukData,
        options: chartOptions
    });

    new Chart(document.getElementById('jenjangChart'), {
        type: 'pie',
        data: jenjangData,
        options: chartOptions
    });
</script>
@endpush
@endsection

