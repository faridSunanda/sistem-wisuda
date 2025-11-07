@extends('admin.layouts.app')

@section('content')
<div class="space-y-4 md:space-y-6 pb-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Edit Data Wisudawan</h1>
        <a href="{{ route('admin.data-wisudawan.show', $user->id) }}" class="flex items-center gap-2 px-4 py-2 text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg transition-all">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:p-8">
        <form action="{{ route('admin.data-wisudawan.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
                        <div>
                            <p class="font-semibold mb-1">Terjadi kesalahan:</p>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                <div>
                    <label for="name_lengkap" class="block text-sm font-normal text-gray-600 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name_lengkap" name="name_lengkap" value="{{ old('name_lengkap', $user->name_lengkap) }}" required
                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label for="email" class="block text-sm font-normal text-gray-600 mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label for="nim" class="block text-sm font-normal text-gray-600 mb-1.5">NIM</label>
                    <input type="text" id="nim" name="nim" value="{{ old('nim', $user->biodata->nim ?? '') }}"
                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label for="tahun_masuk" class="block text-sm font-normal text-gray-600 mb-1.5">Tahun Masuk</label>
                    <input type="number" id="tahun_masuk" name="tahun_masuk" value="{{ old('tahun_masuk', $user->biodata->tahun_masuk ?? '') }}"
                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label for="fakultas" class="block text-sm font-normal text-gray-600 mb-1.5">Fakultas</label>
                    <input type="text" id="fakultas" name="fakultas" value="{{ old('fakultas', $user->biodata->fakultas ?? '') }}"
                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label for="program_studi" class="block text-sm font-normal text-gray-600 mb-1.5">Program Studi</label>
                    <input type="text" id="program_studi" name="program_studi" value="{{ old('program_studi', $user->biodata->program_studi ?? '') }}"
                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-300 flex gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-blue-500 text-white hover:bg-blue-600 rounded-lg transition-colors">
                    <i class="fas fa-save"></i>
                    <span>Simpan Perubahan</span>
                </button>
                <a href="{{ route('admin.data-wisudawan.show', $user->id) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors">
                    <span>Batal</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

