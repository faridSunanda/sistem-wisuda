@extends('admin.layouts.app')

@section('content')
<div class="space-y-4 md:space-y-6 pb-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Jadwal Pendaftaran</h1>
    </div>
</div>
@endsection

@push('styles')
@vite('resources/css/admin/jadwal-pendaftaran.css')
@endpush

@push('scripts')
<script>
    window.jadwalPendaftaranConfig = {
        // Add your config here
        // apiUrl: "{{ route('admin.setting.jadwal-pendaftaran.api') }}",
    };
</script>
@vite('resources/js/admin/setting/jadwal-pendaftaran/index.js')
@endpush

