@extends('admin.layouts.app')

@section('content')
<div class="space-y-4 md:space-y-6 pb-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Jadwal Wisuda</h1>
    </div>
</div>
@endsection

@push('styles')
@vite('resources/css/admin/jadwal-wisuda.css')
@endpush

@push('scripts')
<script>
    window.jadwalWisudaConfig = {
        // Add your config here
        // apiUrl: "{{ route('admin.setting.jadwal-wisuda.api') }}",
    };
</script>
@vite('resources/js/admin/setting/jadwal-wisuda/index.js')
@endpush

