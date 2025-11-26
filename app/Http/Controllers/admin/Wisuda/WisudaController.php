<?php

namespace App\Http\Controllers\Admin\Wisuda;

use App\Http\Controllers\Controller;
use App\Models\Wisuda; // Pastikan Model ini benar
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Exports\WisudaExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class WisudaController extends Controller
{
    public function index()
    {
        return view('admin.wisuda.wisuda.index');
    }

    public function create()
    {
        return view('admin.wisuda.wisuda.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'angkatan'            => 'required|string|max:255',
            'status'              => 'required|in:dibuka,ditutup,selesai',
            'tanggal_pendaftaran' => 'required|date',
            'tanggal_penutupan'   => 'required|date|after:tanggal_pendaftaran',
            'kuota_wisudawan'     => 'required|integer|min:1',
        ]);

        $existing = Wisuda::where('angkatan', $validated['angkatan'])->exists();
        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data wisuda dengan angkatan ' . $validated['angkatan'] . ' sudah ada.');
        }

        try {
            Wisuda::create([
                'angkatan'            => $validated['angkatan'],
                'status'              => $validated['status'],
                'tanggal_pendaftaran' => $validated['tanggal_pendaftaran'],
                'tanggal_penutupan'   => $validated['tanggal_penutupan'],
                'kuota_wisudawan'     => $validated['kuota_wisudawan'],
            ]);

            return redirect()->route('admin.wisuda.wisuda.index')
                ->with('success', 'Data wisuda berhasil ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getData(Request $request)
    {
        try {
            $data = Wisuda::query()->orderBy('created_at', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('angkatan', function($row) {
                    return $row->angkatan;
                })
                ->addColumn('tanggal_pendaftaran', function($row) {
                    return Carbon::parse($row->tanggal_pendaftaran)->format('d F Y H:i') . ' WIB';
                })
                ->addColumn('tanggal_penutupan', function($row) {
                    return Carbon::parse($row->tanggal_penutupan)->format('d F Y H:i') . ' WIB';
                })
                ->addColumn('kuota_wisudawan', function($row) {
                    return number_format($row->kuota_wisudawan, 0, ',', '.');
                })
                ->addColumn('status_badge', function($row) {
                    return $this->getStatusBadge($row->status);
                })
                ->addColumn('aksi', function($row) {
                    return $this->getActionButtons($row->id);
                })
                ->rawColumns(['status_badge', 'aksi'])
                ->make(true);

        } catch (\Exception $e) {
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getStatusBadge($status)
    {
        $status = strtolower($status);
        $badgeClass = 'badge-secondary';
        $statusText = ucfirst($status);

        if ($status === 'dibuka') {
            $badgeClass = 'badge-success';
        } elseif ($status === 'ditutup') {
            $badgeClass = 'badge-danger';
        } elseif ($status === 'selesai') {
            $badgeClass = 'badge-info';
        }

        return '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';
    }

    private function getActionButtons($id)
    {
        return '<div class="flex items-center justify-center gap-2">' .
            '<button class="btn-action btn-view" onclick="lihatData(\'' . $id . '\')" title="Lihat">' .
            '<i class="fas fa-eye"></i>' .
            '</button>' .
            '<button class="btn-action btn-edit" onclick="editData(\'' . $id . '\')" title="Edit">' .
            '<i class="fas fa-pencil-alt"></i>' .
            '</button>' .
            '<button class="btn-action btn-delete" onclick="hapusData(\'' . $id . '\', this)" title="Hapus">' .
            '<i class="fas fa-trash"></i>' .
            '</button>' .
            '</div>';
    }

    public function show(string $id)
    {
        $wisuda = Wisuda::findOrFail($id);
        return view('admin.wisuda.wisuda.show', compact('wisuda'));
    }

    public function edit(string $id)
    {
        $wisuda = Wisuda::findOrFail($id);
        return view('admin.wisuda.wisuda.edit', compact('wisuda'));
    }

    public function update(Request $request, string $id)
    {
        $wisuda = Wisuda::findOrFail($id);

        $validated = $request->validate([
            'angkatan'            => 'required|string|max:255',
            'status'              => 'required|in:dibuka,ditutup,selesai',
            'tanggal_pendaftaran' => 'required|date',
            'tanggal_penutupan'   => 'required|date|after:tanggal_pendaftaran',
            'kuota_wisudawan'     => 'required|integer|min:1',
        ]);

        try {
            $wisuda->update([
                'angkatan'            => $validated['angkatan'],
                'status'              => $validated['status'],
                'tanggal_pendaftaran' => $validated['tanggal_pendaftaran'],
                'tanggal_penutupan'   => $validated['tanggal_penutupan'],
                'kuota_wisudawan'     => $validated['kuota_wisudawan'],
            ]);

            return redirect()->route('admin.wisuda.wisuda.index')
                ->with('success', 'Data wisuda berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $wisuda = Wisuda::findOrFail($id);
            $wisuda->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data wisuda berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportData(Request $request)
    {
        try {
            $data = Wisuda::query()
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($item) {
                    return [
                        'angkatan'            => $item->angkatan,
                        'tanggal_pendaftaran' => Carbon::parse($item->tanggal_pendaftaran)->format('d F Y H:i'),
                        'tanggal_penutupan'   => Carbon::parse($item->tanggal_penutupan)->format('d F Y H:i'),
                        'kuota_wisudawan'     => $item->kuota_wisudawan,
                        'status'              => ucfirst($item->status),
                    ];
                });

            return response()->json($data->toArray());

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        $data = Wisuda::all();
        return Excel::download(new WisudaExport($data), 'Data_Wisuda.xlsx');
    }

    public function exportPdf(Request $request)
    {
        try {
            $data = Wisuda::query()
                ->orderBy('created_at', 'desc')
                ->get();

            $fileName = 'Data_Wisuda_' . date('Y-m-d') . '.pdf';

            $html = view('admin.wisuda.wisuda.pdf', [
                'data' => $data,
                'title' => 'Data Wisuda'
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
