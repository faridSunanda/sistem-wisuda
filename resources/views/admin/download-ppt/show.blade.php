@extends('admin.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Detail Wisudawan</h1>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.download-ppt.edit', $user->id) }}"
                    class="flex items-center gap-2 px-4 py-2 text-sm bg-[#f59e0b] text-white hover:bg-[#d97706] rounded-lg transition-all">
                    <i class="fas fa-pencil-alt"></i>
                    <span>Edit</span>
                </a>
                <a href="{{ route('admin.download-ppt.index') }}"
                    class="flex items-center gap-2 px-4 py-2 text-sm bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg transition-all">
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <p class="text-gray-900">{{ $user->name_lengkap ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">NIM</label>
                    <p class="text-gray-900">{{ $user->nim ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Angkatan</label>
                    <p class="text-gray-900">{{ $user->tahun_masuk ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Group</label>
                    <p class="text-gray-900">{{ $user->group_name ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Sesi</label>
                    <p class="text-gray-900">{{ $user->sesi_name ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">No Urut</label>
                    <p class="text-gray-900">{{ $user->nomor_urut ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jenjang</label>
                    <p class="text-gray-900">S1</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Fakultas</label>
                    <p class="text-gray-900">{{ $user->fakultas ?? '-' }}</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Program Studi</label>
                    <p class="text-gray-900">{{ $user->program_studi ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

