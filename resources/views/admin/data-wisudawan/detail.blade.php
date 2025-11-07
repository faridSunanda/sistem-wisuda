@extends('admin.layouts.app')

@section('content')
<div class="space-y-4 md:space-y-6 pb-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Detail Data Wisudawan</h1>
        <a href="{{ route('admin.data-wisudawan.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg transition-all">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
            <div>
                <label class="block text-sm font-normal text-gray-600 mb-1.5">Nama Lengkap</label>
                <p class="text-base text-gray-900 font-bold">{{ $user->name_lengkap }}</p>
            </div>
            <div>
                <label class="block text-sm font-normal text-gray-600 mb-1.5">Email</label>
                <p class="text-base text-gray-900 font-bold">{{ $user->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-normal text-gray-600 mb-1.5">NIM</label>
                <p class="text-base text-gray-900 font-bold">{{ $user->biodata->nim ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-normal text-gray-600 mb-1.5">Tahun Masuk</label>
                <p class="text-base text-gray-900 font-bold">{{ $user->biodata->tahun_masuk ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-normal text-gray-600 mb-1.5">Fakultas</label>
                <p class="text-base text-gray-900 font-bold">{{ $user->biodata->fakultas ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-normal text-gray-600 mb-1.5">Program Studi</label>
                <p class="text-base text-gray-900 font-bold">{{ $user->biodata->program_studi ?? '-' }}</p>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-300">
            <a href="{{ route('admin.data-wisudawan.edit', $user->id) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-orange-500 text-white hover:bg-orange-600 rounded-lg transition-colors">
                <i class="fas fa-pencil-alt"></i>
                <span>Edit</span>
            </a>
        </div>
    </div>
</div>
@endsection

