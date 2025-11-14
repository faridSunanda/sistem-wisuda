<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\DokumenPersyaratan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Exports\DokumenPersyaratanExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class DokumenPersyaratanController extends Controller
{
    public function index()
    {
        return view('admin.setting.dokumen-persyaratan.index');
    }

    public function getData(Request $request)
    {
        $data = DokumenPersyaratan::query()->orderBy('created_at', 'desc');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function($row) {
                $id = $row->id;
                $btn = '<div class="flex items-center justify-center gap-2">';
                $btn .= '<button class="btn-action btn-view" onclick="lihatData(\''.$id.'\')" title="Lihat">';
                $btn .= '<i class="fas fa-eye"></i>';
                $btn .= '</button>';
                $btn .= '<button class="btn-action btn-edit" onclick="editData(\''.$id.'\')" title="Edit">';
                $btn .= '<i class="fas fa-pencil-alt"></i>';
                $btn .= '</button>';
                $btn .= '<button class="btn-action btn-delete" onclick="hapusData(\''.$row->id.'\', this)" title="Hapus">';
                $btn .= '<i class="fas fa-trash"></i>';
                $btn .= '</button>';
                $btn .= '</div>';
                return $btn;
            })
            ->addColumn('berkas_link', function($row) {
                if ($row->berkas) {
                    return '<a href="'.asset('storage/'.$row->berkas).'" target="_blank" class="text-[#435ebe] hover:underline text-sm">Lihat Berkas</a>';
                }
                return '-';
            })
            ->rawColumns(['aksi', 'berkas_link'])
            ->make(true);
    }

    public function exportData(Request $request)
    {
        try {
            $data = DokumenPersyaratan::query()
                ->select('tipe_dokumen', 'nama_dokumen', 'keterangan', 'berkas')
                ->orderBy('tipe_dokumen', 'asc')
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
        return view('admin.setting.dokumen-persyaratan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe_dokumen' => 'required|string|max:100',
            'nama_dokumen' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'berkas' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('berkas')) {
            $file = $request->file('berkas');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('dokumen_persyaratan', $fileName, 'public');
            $validated['berkas'] = $filePath;
        }

        DokumenPersyaratan::create($validated);

        return redirect()->route('admin.setting.dokumen-persyaratan.index')
            ->with('success', 'Dokumen persyaratan baru berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $dokumen = DokumenPersyaratan::findOrFail($id);

        return view('admin.setting.dokumen-persyaratan.show', [
            'dokumen' => $dokumen
        ]);
    }

    public function edit(string $id)
    {
        $dokumen = DokumenPersyaratan::findOrFail($id);

        return view('admin.setting.dokumen-persyaratan.edit', [
            'dokumen' => $dokumen
        ]);
    }

    public function update(Request $request, string $id)
    {
        $dokumen = DokumenPersyaratan::findOrFail($id);

        $validated = $request->validate([
            'tipe_dokumen' => 'required|string|max:100',
            'nama_dokumen' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'berkas' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('berkas')) {
            if ($dokumen->berkas && Storage::disk('public')->exists($dokumen->berkas)) {
                Storage::disk('public')->delete($dokumen->berkas);
            }

            $file = $request->file('berkas');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('dokumen_persyaratan', $fileName, 'public');
            $validated['berkas'] = $filePath;
        } else {
            $validated['berkas'] = $dokumen->berkas;
        }

        $dokumen->update($validated);

        return redirect()->route('admin.setting.dokumen-persyaratan.index')
            ->with('success', 'Dokumen persyaratan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        try {
            $dokumen = DokumenPersyaratan::findOrFail($id);

            if ($dokumen->berkas && Storage::disk('public')->exists($dokumen->berkas)) {
                Storage::disk('public')->delete($dokumen->berkas);
            }

            $dokumen->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen persyaratan berhasil dihapus.'
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
            $data = DokumenPersyaratan::query()
                ->orderBy('tipe_dokumen', 'asc')
                ->get();

            $fileName = 'Dokumen_Persyaratan_' . date('Y-m-d') . '.xlsx';

            return Excel::download(new DokumenPersyaratanExport($data), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $data = DokumenPersyaratan::query()
                ->orderBy('tipe_dokumen', 'asc')
                ->get();

            $fileName = 'Dokumen_Persyaratan_' . date('Y-m-d') . '.pdf';

            $html = view('admin.setting.dokumen-persyaratan.pdf', [
                'data' => $data,
                'title' => 'Dokumen Persyaratan'
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
