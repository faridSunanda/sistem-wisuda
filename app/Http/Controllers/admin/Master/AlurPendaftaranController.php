<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\AlurPendaftaran;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use App\Exports\AlurPendaftaranExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class AlurPendaftaranController extends Controller
{
    public function index()
    {
        return view('admin.master.alur-pendaftaran.index');
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
        return view('admin.master.alur-pendaftaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_urut' => 'required|integer|unique:alur_pendaftarans,no_urut',
            'judul' => 'required|string|max:255',
            'keterangan' => 'required|string',
        ]);

        AlurPendaftaran::create($validated);

        return redirect()->route('admin.master.alur-pendaftaran.index')
            ->with('success', 'Alur pendaftaran baru berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $alurPendaftaran = AlurPendaftaran::findOrFail($id);

        return view('admin.master.alur-pendaftaran.show', [
            'alurPendaftaran' => $alurPendaftaran
        ]);
    }

    public function edit(string $id)
    {
        $alur_pendaftaran = AlurPendaftaran::findOrFail($id);

        return view('admin.master.alur-pendaftaran.edit', [
            'alur' => $alur_pendaftaran
        ]);
    }

    public function update(Request $request, string $id)
    {
        $alur_pendaftaran = AlurPendaftaran::findOrFail($id);

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

        return redirect()->route('admin.master.alur-pendaftaran.index')
            ->with('success', 'Alur pendaftaran berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        try {
            // Menggunakan withTrashed() untuk memastikan bisa menghapus data yang sudah di-soft delete
            // dan forceDelete() untuk benar-benar menghapus dari database
            $alur_pendaftaran = AlurPendaftaran::withTrashed()->findOrFail($id);
            $alur_pendaftaran->forceDelete();

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

    public function exportExcel(Request $request)
    {
        try {
            $data = AlurPendaftaran::query()
                ->orderBy('no_urut', 'asc')
                ->get();

            $fileName = 'Alur_Pendaftaran_' . date('Y-m-d') . '.xlsx';

            return Excel::download(new AlurPendaftaranExport($data), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $data = AlurPendaftaran::query()
                ->orderBy('no_urut', 'asc')
                ->get();

            $fileName = 'Alur_Pendaftaran_' . date('Y-m-d') . '.pdf';

            $html = view('admin.master.alur-pendaftaran.pdf', [
                'data' => $data,
                'title' => 'Alur Pendaftaran'
            ])->render();

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('a4', 'landscape');
            $dompdf->render();

            return response()->streamDownload(function() use ($dompdf) {
                echo $dompdf->output();
            }, $fileName, [
                'Content-Type' => 'application/pdf',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export PDF: ' . $e->getMessage());
        }
    }
}
