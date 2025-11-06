@extends('admin.layouts.app')

@section('content')
<div class="space-y-4 md:space-y-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Data Wisudawan</h1>
    </div>

    <div class="bg-white border border-gray-300 p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="filterFakultas" class="block text-sm font-medium text-gray-900 mb-1.5">Fakultas</label>
                <select id="filterFakultas" class="w-full px-3 py-2 text-sm border border-gray-300 bg-white focus:outline-none focus:border-gray-400">
                    <option value="">Semua Fakultas</option>
                    <option value="Teknik">Fakultas Teknik</option>
                    <option value="Hukum">Fakultas Hukum</option>
                    <option value="Ekonomi">Fakultas Ekonomi</option>
                    <option value="Kedokteran">Fakultas Kedokteran</option>
                </select>
            </div>
            <div>
                <label for="filterProdi" class="block text-sm font-medium text-gray-900 mb-1.5">Prodi</label>
                <select id="filterProdi" class="w-full px-3 py-2 text-sm border border-gray-300 bg-white focus:outline-none focus:border-gray-400">
                    <option value="">Semua Prodi</option>
                    <option value="Teknik Informatika">Teknik Informatika</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Teknik Komputer">Teknik Komputer</option>
                </select>
            </div>
            <div>
                <label for="filterTahunMasuk" class="block text-sm font-medium text-gray-900 mb-1.5">Tahun Masuk</label>
                <select id="filterTahunMasuk" class="w-full px-3 py-2 text-sm border border-gray-300 bg-white focus:outline-none focus:border-gray-400">
                    <option value="">Semua Tahun</option>
                    <option value="2021">2021</option>
                    <option value="2020">2020</option>
                    <option value="2019">2019</option>
                    <option value="2018">2018</option>
                </select>
            </div>
            <div>
                <label for="filterJenjang" class="block text-sm font-medium text-gray-900 mb-1.5">Jenjang</label>
                <select id="filterJenjang" class="w-full px-3 py-2 text-sm border border-gray-300 bg-white focus:outline-none focus:border-gray-400">
                    <option value="">Semua Jenjang</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                    <option value="S3">S3</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-300 overflow-hidden">
        <div class="p-4 border-b border-gray-300">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Wisudawan</h2>
            </div>
        </div>
        <div class="p-4">
            <table id="dataWisudawanTable" class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-900 border border-gray-300 bg-white">No</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-900 border border-gray-300 bg-white">Nama Lengkap</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-900 border border-gray-300 bg-white">NIM</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-900 border border-gray-300 bg-white">Tahun Masuk</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-900 border border-gray-300 bg-white">Jenjang</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-900 border border-gray-300 bg-white">Fakultas</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-900 border border-gray-300 bg-white">Prodi</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-900 border border-gray-300 bg-white">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    const table = $('#dataWisudawanTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.data-wisudawan.get-data') }}",
            data: function(d) {
                d.fakultas = $('#filterFakultas').val();
                d.prodi = $('#filterProdi').val();
                d.tahun_masuk = $('#filterTahunMasuk').val();
                d.jenjang = $('#filterJenjang').val();
            },
            error: function(xhr, error, thrown) {
                console.error('DataTables error:', error);
                alert('Terjadi kesalahan saat memuat data. Silakan refresh halaman.');
            }
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            {data: 'nama', name: 'nama'},
            {data: 'nim', name: 'nim'},
            {data: 'tahun_masuk', name: 'tahun_masuk', className: 'text-center'},
            {data: 'jenjang', name: 'jenjang', className: 'text-center'},
            {data: 'fakultas', name: 'fakultas'},
            {data: 'prodi', name: 'prodi'},
            {
                data: 'aksi',
                name: 'aksi',
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[1, 'asc']],
        dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"mb-2 md:mb-0"l><"mb-2 md:mb-0"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"mb-2 md:mb-0"i><"mb-2 md:mb-0"p>>',
        drawCallback: function() {
            $('.dataTables_wrapper').addClass('w-full');
        }
    });

    $('#filterFakultas, #filterProdi, #filterTahunMasuk, #filterJenjang').on('change', function() {
        table.draw();
    });
});

function lihatData(id) {
    console.log('Lihat data:', id);
}
</script>
@endpush

