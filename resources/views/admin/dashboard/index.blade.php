@extends('admin.layouts.app')

@section('content')
<div class="space-y-4 md:space-y-6 pb-10">
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard</h1>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <h3 class="text-xs md:text-sm font-medium text-gray-600 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-green-600"></i>
                    <span>Wisudawan Terverifikasi</span>
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
                    <span>Wisudawan Belum Terverifikasi</span>
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
    
    @php
        $fakultasLabels = $charts['fakultas']['labels'] ?? ['', '', '', ''];
        $fakultasValues = $charts['fakultas']['data'] ?? [0, 0, 0, 0];
        $tahunMasukLabels = $charts['tahun_masuk']['labels'] ?? ['', '', '', ''];
        $tahunMasukValues = $charts['tahun_masuk']['data'] ?? [0, 0, 0, 0];
        $jenjangLabels = $charts['jenjang']['labels'] ?? ['S1', 'S2', 'S3'];
        $jenjangValues = $charts['jenjang']['data'] ?? [0, 0, 0];
        
        $fakultasColors = ['#3b82f6', '#10b981', '#eab308', '#ef4444'];
        $tahunMasukColors = ['#10b981', '#3b82f6', '#ef4444', '#eab308'];
        $jenjangColors = ['#a855f7', '#ec4899', '#eab308'];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">Wisudawan Berdasarkan Fakultas</h3>
            <div class="h-48 md:h-64 flex items-center justify-center">
                <canvas id="fakultasChart"></canvas>
            </div>
            <div class="mt-3 md:mt-4 space-y-1 md:space-y-2">
                @foreach($fakultasLabels as $index => $label)
                    @if($label)
                        <div class="flex items-center gap-2 text-xs md:text-sm">
                            <div class="w-3 h-3 md:w-4 md:h-4 rounded flex-shrink-0" style="background-color: {{ $fakultasColors[$index] ?? '#3b82f6' }}"></div>
                            <span>{{ $label }}</span>
                        </div>
                    @endif
                @endforeach
                @if(empty(array_filter($fakultasLabels)))
                    <p class="text-xs md:text-sm text-gray-500">Belum ada data</p>
                @endif
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">Wisudawan Berdasarkan Tahun Masuk</h3>
            <div class="h-48 md:h-64 flex items-center justify-center">
                <canvas id="tahunMasukChart"></canvas>
            </div>
            <div class="mt-3 md:mt-4 space-y-1 md:space-y-2">
                @foreach($tahunMasukLabels as $index => $label)
                    @if($label)
                        <div class="flex items-center gap-2 text-xs md:text-sm">
                            <div class="w-3 h-3 md:w-4 md:h-4 rounded flex-shrink-0" style="background-color: {{ $tahunMasukColors[$index] ?? '#10b981' }}"></div>
                            <span>{{ $label }}</span>
                        </div>
                    @endif
                @endforeach
                @if(empty(array_filter($tahunMasukLabels)))
                    <p class="text-xs md:text-sm text-gray-500">Belum ada data</p>
                @endif
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


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const fakultasData = {
        labels: @json($fakultasLabels),
        datasets: [{
            data: @json($fakultasValues),
            backgroundColor: @json($fakultasColors)
        }]
    };

    const tahunMasukData = {
        labels: @json($tahunMasukLabels),
        datasets: [{
            data: @json($tahunMasukValues),
            backgroundColor: @json($tahunMasukColors)
        }]
    };

    const jenjangData = {
        labels: @json($jenjangLabels),
        datasets: [{
            data: @json($jenjangValues),
            backgroundColor: @json($jenjangColors)
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
