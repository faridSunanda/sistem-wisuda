<?php

namespace App\Http\Controllers\Admin\Wisuda;

use App\Http\Controllers\Controller;
use App\Models\PelaksanaanWisuda;
use App\Models\JadwalPendaftaran;
use App\Models\Sesi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Exports\JadwalWisudaExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class JadwalPelaksanaanController extends Controller
{
    public function index()
    {
        return view('admin.wisuda.jadwal-pelaksanaan.index');
    }

    public function create()
    {
        $jadwalPendaftarans = JadwalPendaftaran::aktif()->get();
        $sesis = Sesi::all();

        return view('admin.wisuda.jadwal-pelaksanaan.create', compact('jadwalPendaftarans', 'sesis'));
    }

    public function edit(string $id)
    {
        $jadwal = PelaksanaanWisuda::with('sesi')->findOrFail($id);
        $jadwalPendaftarans = JadwalPendaftaran::aktif()->get();
        $sesis = Sesi::all();

        return view('admin.wisuda.jadwal-pelaksanaan.edit', compact('jadwal', 'jadwalPendaftarans', 'sesis'));
    }

    public function getData(Request $request)
    {
        try {
            $data = PelaksanaanWisuda::query()
                ->with('sesi')
                ->orderBy('waktu_pelaksanaan', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_kegiatan', function($row) {
                    return $row->nama_kegiatan;
                })
                ->addColumn('sesi', function($row) {
                    if ($row->sesi) {
                        return $row->sesi->name;
                    }
                    // Fallback jika sesi_id adalah string langsung
                    if (isset($row->sesi_id) && is_string($row->sesi_id)) {
                        return $row->sesi_id === 'semua' ? 'Semua' : ucfirst($row->sesi_id);
                    }
                    return '-';
                })
                ->addColumn('waktu_pelaksanaan', function($row) {
                    return Carbon::parse($row->waktu_pelaksanaan)->format('H:i') . ' WIB';
                })
                ->addColumn('tanggal_pelaksanaan', function($row) {
                    return Carbon::parse($row->waktu_pelaksanaan)->format('d F Y');
                })
                ->addColumn('tempat_pelaksanaan', function($row) {
                    return $row->tempat_pelaksanaan;
                })
                ->addColumn('keterangan', function($row) {
                    return $row->keterangan;
                })
                ->addColumn('aksi', function($row) {
                    return $this->getActionButtons($row->id);
                })
                ->rawColumns(['aksi'])
                ->make(true);

        } catch (\Exception $e) {
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Terjadi kesalahan saat memuat data'
            ], 500);
        }
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

    public function exportData(Request $request)
    {
        try {
            $data = PelaksanaanWisuda::query()
                ->with('sesi')
                ->orderBy('waktu_pelaksanaan', 'asc')
                ->get()
                ->map(function($item) {
                    $sesiName = $item->sesi ? $item->sesi->name : (isset($item->sesi_id) && is_string($item->sesi_id) ? ($item->sesi_id === 'semua' ? 'Semua' : ucfirst($item->sesi_id)) : '-');
                    
                    return [
                        'nama_kegiatan' => $item->nama_kegiatan,
                        'sesi' => $sesiName,
                        'waktu_pelaksanaan' => Carbon::parse($item->waktu_pelaksanaan)->format('H:i') . ' WIB',
                        'tanggal_pelaksanaan' => Carbon::parse($item->waktu_pelaksanaan)->format('d F Y'),
                        'tempat_pelaksanaan' => $item->tempat_pelaksanaan,
                        'keterangan' => $item->keterangan
                    ];
                });

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

    public function show(string $id)
    {
        $jadwal = PelaksanaanWisuda::with(['sesi', 'jadwalPendaftaran'])->findOrFail($id);

        return view('admin.wisuda.jadwal-pelaksanaan.show', [
            'jadwal' => $jadwal
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pendaftaran_wisuda_id' => 'required|uuid|exists:pendaftaran_wisudas,id',
            'nama_kegiatan' => 'required|string|max:255',
            'sesi_id' => 'nullable|uuid|exists:sesi,id',
            'waktu_pelaksanaan' => 'required|date',
            'tempat_pelaksanaan' => 'required|string|max:255',
            'keterangan' => 'required|string',
        ]);

        try {
            PelaksanaanWisuda::create($validated);

            return redirect()->route('admin.wisuda.jadwal-pelaksanaan.index')
                ->with('success', 'Jadwal pelaksanaan berhasil ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        $jadwal = PelaksanaanWisuda::findOrFail($id);

        $validated = $request->validate([
            'pendaftaran_wisuda_id' => 'required|uuid|exists:pendaftaran_wisudas,id',
            'nama_kegiatan' => 'required|string|max:255',
            'sesi_id' => 'nullable|uuid|exists:sesi,id',
            'waktu_pelaksanaan' => 'required|date',
            'tempat_pelaksanaan' => 'required|string|max:255',
            'keterangan' => 'required|string',
        ]);

        try {
            $jadwal->update($validated);

            return redirect()->route('admin.wisuda.jadwal-pelaksanaan.index')
                ->with('success', 'Jadwal pelaksanaan berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $jadwal = PelaksanaanWisuda::findOrFail($id);
            $jadwal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal pelaksanaan berhasil dihapus.'
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
            $data = PelaksanaanWisuda::query()
                ->with('sesi')
                ->orderBy('waktu_pelaksanaan', 'asc')
                ->get();

            $fileName = 'Jadwal_Pelaksanaan_' . date('Y-m-d') . '.xlsx';

            return Excel::download(new JadwalWisudaExport($data), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $data = PelaksanaanWisuda::query()
                ->with('sesi')
                ->orderBy('waktu_pelaksanaan', 'asc')
                ->get();

            $fileName = 'Jadwal_Pelaksanaan_' . date('Y-m-d') . '.pdf';

            $html = view('admin.wisuda.jadwal-pelaksanaan.pdf', [
                'data' => $data,
                'title' => 'Jadwal Pelaksanaan Wisuda'
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

