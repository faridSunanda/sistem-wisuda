@extends('admin.layouts.app')

@section('content')
<div class="space-y-4 md:space-y-6 pb-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Alur Pendaftaran</h1>
    </div>
</div>
@endsection

@push('styles')
@vite('resources/css/admin/alur-pendaftaran.css')
@endpush

@push('scripts')
<script>
    window.alurPendaftaranConfig = {
        // Add your config here
        // apiUrl: "{{ route('admin.setting.alur-pendaftaran.api') }}",
    };
</script>
@vite('resources/js/admin/setting/alur-pendaftaran/index.js')
@endpush

