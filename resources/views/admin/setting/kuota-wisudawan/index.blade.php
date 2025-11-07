@extends('admin.layouts.app')

@section('content')
<div class="space-y-4 md:space-y-6 pb-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Kuota Wisudawan</h1>
    </div>
</div>
@endsection

@push('styles')
@vite('resources/css/admin/kuota-wisudawan.css')
@endpush

@push('scripts')
<script>
    window.kuotaWisudawanConfig = {
        // Add your config here
        // apiUrl: "{{ route('admin.setting.kuota-wisudawan.api') }}",
    };
</script>
@vite('resources/js/admin/setting/kuota-wisudawan/index.js')
@endpush

