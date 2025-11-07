@extends('admin.layouts.app')

{{-- Set Judul Halaman --}}
@section('title', 'Dashboard')

{{-- Konten Utama --}}
@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Dashboard</h1>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
            <!-- Card 1: Verified -->
            <div
                class="bg-linear-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-green-200">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-user-check text-white text-lg"></i>
                            </div>
                            <span
                                class="text-xs font-medium text-green-700 bg-green-200 px-2 py-1 rounded-full">Verified</span>
                        </div>
                        <p class="text-sm font-medium text-green-700 mb-1">Wisudawan Terverifikasi</p>
                        <p class="text-3xl sm:text-4xl font-bold text-green-900">128</p>
                        <div class="mt-3 flex items-center gap-1 text-xs text-green-600">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Siap wisuda</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Not Verified -->
            <div
                class="bg-linear-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-blue-200">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-user-clock text-white text-lg"></i>
                            </div>
                            <span
                                class="text-xs font-medium text-blue-700 bg-blue-200 px-2 py-1 rounded-full">Pending</span>
                        </div>
                        <p class="text-sm font-medium text-blue-700 mb-1">Belum Terverifikasi</p>
                        <p class="text-3xl sm:text-4xl font-bold text-blue-900">98</p>
                        <div class="mt-3 flex items-center gap-1 text-xs text-blue-600">
                            <i class="fa-solid fa-clock"></i>
                            <span>Dalam proses</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Total Lulus -->
            <div
                class="bg-linear-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-purple-200 sm:col-span-2 lg:col-span-1">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-users text-white text-lg"></i>
                            </div>
                            <span
                                class="text-xs font-medium text-purple-700 bg-purple-200 px-2 py-1 rounded-full">Total</span>
                        </div>
                        <p class="text-sm font-medium text-purple-700 mb-1">Mahasiswa Lulus</p>
                        <p class="text-3xl sm:text-4xl font-bold text-purple-900">1,020</p>
                        <div class="mt-3 flex items-center gap-1 text-xs text-purple-600">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <span>Total keseluruhan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Chart 1: Fakultas -->
            <div
                class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 border border-slate-200">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Berdasarkan Fakultas</h2>
                        <p class="text-sm text-slate-500 mt-1">Distribusi per fakultas</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-building-columns text-blue-600"></i>
                    </div>
                </div>
                <div class="h-64 sm:h-72 flex items-center justify-center">
                    <canvas id="fakultasChart"></canvas>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">Total Fakultas</span>
                        <span class="font-semibold text-slate-800">4 Fakultas</span>
                    </div>
                </div>
            </div>

            <!-- Chart 2: Tahun Masuk -->
            <div
                class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 border border-slate-200">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Berdasarkan Tahun Masuk</h2>
                        <p class="text-sm text-slate-500 mt-1">Distribusi per angkatan</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-calendar-days text-green-600"></i>
                    </div>
                </div>
                <div class="h-64 sm:h-72 flex items-center justify-center">
                    <canvas id="tahunChart"></canvas>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">Angkatan Terbanyak</span>
                        <span class="font-semibold text-slate-800">2021 (45%)</span>
                    </div>
                </div>
            </div>

            <!-- Chart 3: Jenjang -->
            <div
                class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 border border-slate-200 lg:col-span-2 xl:col-span-1">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Berdasarkan Jenjang</h2>
                        <p class="text-sm text-slate-500 mt-1">Distribusi per tingkat</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-layer-group text-purple-600"></i>
                    </div>
                </div>
                <div class="h-64 sm:h-72 flex items-center justify-center">
                    <canvas id="jenjangChart"></canvas>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">Jenjang Terbanyak</span>
                        <span class="font-semibold text-slate-800">S1 (85%)</span>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    {{-- PUSH SCRIPT UNTUK CHART.JS --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Color palette
                const colors = {
                    blue: '#3b82f6',
                    green: '#22c55e',
                    amber: '#f59e0b',
                    red: '#ef4444',
                    purple: '#8b5cf6',
                    pink: '#ec4899',
                    cyan: '#06b6d4',
                    indigo: '#6366f1',
                };

                // Chart default options
                const defaultOptions = {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: {
                                    size: 12,
                                    family: "'Inter', sans-serif"
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    label += value + ' (' + percentage + '%)';
                                    return label;
                                }
                            }
                        }
                    }
                };

                // 1. Chart Fakultas (Pie)
                const ctxFakultas = document.getElementById('fakultasChart').getContext('2d');
                new Chart(ctxFakultas, {
                    type: 'pie',
                    data: {
                        labels: ['Fakultas Teknik', 'Fakultas Hukum', 'Fakultas Ekonomi',
                            'Fakultas Kedokteran'
                        ],
                        datasets: [{
                            data: [68, 56, 45, 31], // Ganti dengan data asli dari backend
                            backgroundColor: [colors.blue, colors.green, colors.amber, colors.red],
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        ...defaultOptions,
                        plugins: {
                            ...defaultOptions.plugins,
                            title: {
                                display: false
                            }
                        }
                    }
                });

                // 2. Chart Tahun Masuk (Pie)
                const ctxTahun = document.getElementById('tahunChart').getContext('2d');
                new Chart(ctxTahun, {
                    type: 'pie',
                    data: {
                        labels: ['2021', '2020', '2019', '2018'],
                        datasets: [{
                            data: [102, 90, 18, 16], // Ganti dengan data asli dari backend
                            backgroundColor: [colors.green, colors.blue, colors.purple, colors.amber],
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        ...defaultOptions,
                        plugins: {
                            ...defaultOptions.plugins,
                            title: {
                                display: false
                            }
                        }
                    }
                });

                // 3. Chart Jenjang (Pie)
                const ctxJenjang = document.getElementById('jenjangChart').getContext('2d');
                new Chart(ctxJenjang, {
                    type: 'pie',
                    data: {
                        labels: ['S1', 'S2', 'S3'],
                        datasets: [{
                            data: [192, 23, 11], // Ganti dengan data asli dari backend
                            backgroundColor: [colors.purple, colors.pink, colors.cyan],
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        ...defaultOptions,
                        plugins: {
                            ...defaultOptions.plugins,
                            title: {
                                display: false
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
