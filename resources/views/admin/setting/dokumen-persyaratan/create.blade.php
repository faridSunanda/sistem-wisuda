@extends('admin.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Tambah Dokumen Persyaratan</h1>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:p-8">
            <form action="{{ route('admin.setting.dokumen-persyaratan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif --}}

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
                            <div>
                                <p class="font-semibold mb-1">Terjadi kesalahan:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                    <div class="md:col-span-2">
                        <label for="tipe_dokumen" class="block text-sm font-normal text-gray-600 mb-1.5">
                            Tipe Dokumen <span class="text-red-500">*</span>
                        </label>
                        <select id="tipe_dokumen" name="tipe_dokumen" required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:bg-white transition-all">
                            <option value="">Pilih Tipe Dokumen</option>

                            <option value="Dokumen Persyaratan"
                                {{ old('tipe_dokumen') == 'Dokumen Persyaratan' ? 'selected' : '' }}>
                                Dokumen Persyaratan
                            </option>

                            <option value="Tanda Terima" {{ old('tipe_dokumen') == 'Tanda Terima' ? 'selected' : '' }}>
                                Tanda Terima
                            </option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="nama_dokumen" class="block text-sm font-normal text-gray-600 mb-1.5">
                            Nama Dokumen <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_dokumen" name="nama_dokumen" value="{{ old('nama_dokumen') }}"
                            required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:bg-white transition-all"
                            placeholder="Masukkan nama dokumen">
                    </div>

                    <div class="md:col-span-2">
                        <label for="keterangan" class="block text-sm font-normal text-gray-600 mb-1.5">
                            Keterangan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="keterangan" name="keterangan" rows="4" required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:bg-white transition-all"
                            placeholder="Masukkan keterangan dokumen">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="berkas" class="block text-sm font-normal text-gray-600 mb-1.5">
                            Berkas <span class="text-red-500">*</span>
                        </label>
                        <input type="file" id="berkas" name="berkas" required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:bg-white transition-all"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <p class="text-xs text-gray-500 mt-1">Format yang didukung: PDF, DOC, DOCX, JPG, JPEG, PNG (Maks:
                            2MB)</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-300 flex gap-3">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-[#435ebe] text-white hover:bg-[#3a52a8] rounded-lg transition-colors">
                        <i class="fas fa-save"></i>
                        <span>Simpan Data</span>
                    </button>
                    <a href="{{ route('admin.setting.dokumen-persyaratan.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-white border border-[#435ebe] text-[#435ebe] hover:bg-[#435ebe] hover:text-white rounded-lg transition-all">
                        <span>Batal</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
