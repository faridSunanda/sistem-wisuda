<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\AlurPendaftaran;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class AlurPendaftaranController extends Controller
{
    public function index()
    {
        return view('admin.setting.alur-pendaftaran.index');
    }

    public function getData(Request $request)
    {
        $data = AlurPendaftaran::query()->orderBy('no_urut', 'asc');

        return DataTables::of($data)
            ->addColumn('aksi', function($row) {
                $id = $row->id;
                $baseStyles = 'flex items-center justify-center w-8 h-8 rounded text-white transition-all';
                $btn = '<div class="flex items-center justify-center gap-2">';

                $btn .= '<button class="' . $baseStyles . ' bg-[#435ebe] hover:bg-[#3a52a8]" onclick="lihatData(\''.$id.'\')" title="Lihat">';
                $btn .= '<i class="fas fa-eye"></i>';
                $btn .= '</button>';

                $btn .= '<button class="' . $baseStyles . ' bg-yellow-500 hover:bg-yellow-600" onclick="editData(\''.$id.'\')" title="Edit">';
                $btn .= '<i class="fas fa-pencil-alt"></i>';
                $btn .= '</button>';

                $btn .= '<button class="' . $baseStyles . ' bg-red-500 hover:bg-red-600" onclick="hapusData(\''.$id.'\', this)" title="Hapus">';
                $btn .= '<i class="fas fa-trash"></i>';
                $btn .= '</button>';

                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function exportData(Request $request)
    {
        try {
            $data = AlurPendaftaran::query()
                ->select('no_urut', 'judul', 'keterangan')
                ->orderBy('no_urut', 'asc')
                ->get();

            if ($data->isEmpty()) {
                return response()->json([]);
            }

            return response()->json($data->toArray());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        return view('admin.setting.alur-pendaftaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_urut' => 'required|integer|unique:alur_pendaftarans,no_urut',
            'judul' => 'required|string|max:255',
            'keterangan' => 'required|string',
        ]);

        AlurPendaftaran::create($validated);

        return redirect()->route('admin.setting.alur-pendaftaran.index')
            ->with('success', 'Alur pendaftaran baru berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $alurPendaftaran = AlurPendaftaran::findOrFail($id);

        return view('admin.setting.alur-pendaftaran.show', [
            'alurPendaftaran' => $alurPendaftaran
        ]);
    }

    public function edit(AlurPendaftaran $alur_pendaftaran)
    {
        return view('admin.setting.alur-pendaftaran.edit', [
            'alur' => $alur_pendaftaran
        ]);
    }

    public function update(Request $request, AlurPendaftaran $alur_pendaftaran)
    {
        $validated = $request->validate([
            'no_urut' => [
                'required',
                'integer',
                Rule::unique('alur_pendaftarans')->ignore($alur_pendaftaran->id),
            ],
            'judul' => 'required|string|max:255',
            'keterangan' => 'required|string',
        ]);

        $alur_pendaftaran->update($validated);

        return redirect()->route('admin.setting.alur-pendaftaran.index')
            ->with('success', 'Alur pendaftaran berhasil diperbarui.');
    }

    public function destroy(AlurPendaftaran $alur_pendaftaran)
    {
        try {
            $alur_pendaftaran->delete();

            return response()->json([
                'success' => true,
                'message' => 'Alur pendaftaran berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

}
