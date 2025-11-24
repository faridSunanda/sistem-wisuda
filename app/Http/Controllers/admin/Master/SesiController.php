<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Sesi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\SesiExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class SesiController extends Controller
{
    public function index()
    {
        return view('admin.master.sesi.index');
    }

    public function getData(Request $request)
    {
        // Mengambil data Sesi, diurutkan berdasarkan nama
        $data = Sesi::query()->orderBy('name', 'asc');

        return DataTables::of($data)
            ->addIndexColumn()
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
            $data = Sesi::query()
                ->select('name', 'keterangan')
                ->orderBy('name', 'asc')
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
        return view('admin.master.sesi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        Sesi::create($validated);

        return redirect()->route('admin.master.sesi.index')
            ->with('success', 'Data sesi baru berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $sesi = Sesi::findOrFail($id);

        return view('admin.master.sesi.show', [
            'sesi' => $sesi
        ]);
    }

    public function edit(string $id)
    {
        $sesi = Sesi::findOrFail($id);

        return view('admin.master.sesi.edit', [
            'sesi' => $sesi
        ]);
    }

    public function update(Request $request, string $id)
    {
        $sesi = Sesi::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $sesi->update($validated);

        return redirect()->route('admin.master.sesi.index')
            ->with('success', 'Data sesi berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        try {
            $sesi = Sesi::findOrFail($id);
            $sesi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data sesi berhasil dihapus.'
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
            $data = Sesi::query()
                ->orderBy('name', 'asc')
                ->get();

            $fileName = 'Data_Sesi_' . date('Y-m-d') . '.xlsx';

            return Excel::download(new SesiExport($data), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $data = Sesi::query()
                ->orderBy('name', 'asc')
                ->get();

            $fileName = 'Data_Sesi_' . date('Y-m-d') . '.pdf';

            $html = view('admin.master.sesi.pdf', [
                'data' => $data,
                'title' => 'Data Sesi'
            ])->render();

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('a4', 'portrait');
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
