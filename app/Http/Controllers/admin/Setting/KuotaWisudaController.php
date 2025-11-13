<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\KuotaWisudawan;
use App\Models\JadwalPendaftaran;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Exports\KuotaWisudaExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class KuotaWisudaController extends Controller
{
    public function index()
    {
        return view('admin.setting.kuota-wisuda.index');
    }

    public function getData(Request $request)
    {
        try {
            $data = KuotaWisudawan::query()
                ->join('pendaftaran_wisudas', 'kuota_wisudawans.pendaftaran_wisuda_id', '=', 'pendaftaran_wisudas.id')
                ->select(
                    'kuota_wisudawans.*',
                    'pendaftaran_wisudas.tahun_wisuda',
                    'pendaftaran_wisudas.status'
                )
                ->orderBy('kuota_wisudawans.created_at', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tahun_wisuda', function($row) {
                    return $row->tahun_wisuda ?? 'N/A';
                })
                ->addColumn('jumlah_kuota_formatted', function($row) {
                    return number_format($row->jumlah_kuota, 0, ',', '.') . ' orang';
                })
                ->addColumn('status_periode', function($row) {
                    $status = $row->status ?? 'Tidak Aktif';
                    $badgeClass = $status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                    return '<span class="px-2 py-1 text-xs font-medium rounded-full ' . $badgeClass . '">' . $status . '</span>';
                })
                ->addColumn('aksi', function($row) {
                    $id = $row->id;
                    $btn = '<div class="flex items-center justify-center gap-2">';
                    $btn .= '<button class="btn-action btn-view" onclick="lihatData(\''.$id.'\')" title="Lihat"><i class="fas fa-eye"></i></button>';
                    $btn .= '<button class="btn-action btn-edit" onclick="editData(\''.$id.'\')" title="Edit"><i class="fas fa-pencil-alt"></i></button>';
                    $btn .= '<button class="btn-action btn-delete" onclick="hapusData(event, \''.$id.'\')" title="Hapus"><i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status_periode', 'aksi'])
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

    public function exportData(Request $request)
    {
        try {
            $data = KuotaWisudawan::with(['jadwalPendaftaran'])
                ->get()
                ->map(function($item) {
                    $tahun = $item->jadwalPendaftaran->tahun_wisuda ?? null;
                    $status = $item->jadwalPendaftaran->status ?? 'Tidak Aktif';

                    return [
                        'tahun_wisuda' => $tahun,
                        'jumlah_kuota' => $item->jumlah_kuota,
                        'status_periode' => $status,
                        'dibuat_pada' => $item->created_at->format('d F Y H:i')
                    ];
                })
                ->sortByDesc('tahun_wisuda');

            if ($data->isEmpty()) {
                return response()->json([]);
            }

            return response()->json($data->values());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $jadwalPendaftarans = JadwalPendaftaran::aktif()
            ->whereDoesntHave('kuotaWisudawan')
            ->get();

        if ($jadwalPendaftarans->isEmpty()) {
            $jadwalPendaftarans = JadwalPendaftaran::aktif()
                ->whereNotIn('id', function($query) {
                    $query->select('pendaftaran_wisuda_id')
                        ->from('kuota_wisudawans');
                })
                ->get();
        }

        if ($jadwalPendaftarans->isEmpty()) {
            $jadwalPendaftarans = JadwalPendaftaran::aktif()->get();
        }

        return view('admin.setting.kuota-wisuda.create', compact('jadwalPendaftarans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pendaftaran_wisuda_id' => 'required|uuid|exists:pendaftaran_wisudas,id',
            'jumlah_kuota' => 'required|integer|min:1|max:10000',
        ]);

        $existingKuota = KuotaWisudawan::where('pendaftaran_wisuda_id', $validated['pendaftaran_wisuda_id'])->first();
        if ($existingKuota) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Periode pendaftaran ini sudah memiliki kuota.');
        }

        try {
            KuotaWisudawan::create($validated);

            return redirect()->route('admin.setting.kuota-wisuda.index')
                ->with('success', 'Kuota wisuda berhasil ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $kuota = KuotaWisudawan::with(['jadwalPendaftaran'])
            ->findOrFail($id);

        return view('admin.setting.kuota-wisuda.show', compact('kuota'));
    }

   public function edit(string $id)
    {
        $kuota = KuotaWisudawan::with(['jadwalPendaftaran'])
            ->findOrFail($id);

        $jadwalPendaftarans = JadwalPendaftaran::aktif()->get();

        return view('admin.setting.kuota-wisuda.edit', compact('kuota', 'jadwalPendaftarans'));
    }

    public function update(Request $request, string $id)
    {
        $kuota = KuotaWisudawan::findOrFail($id);

        $validated = $request->validate([
            'pendaftaran_wisuda_id' => 'required|uuid|exists:pendaftaran_wisudas,id',
            'jumlah_kuota' => 'required|integer|min:1|max:10000',
        ]);

        $existingKuota = KuotaWisudawan::where('pendaftaran_wisuda_id', $validated['pendaftaran_wisuda_id'])
            ->where('id', '!=', $id)
            ->first();

        if ($existingKuota) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Periode pendaftaran ini sudah memiliki kuota.');
        }

        try {
            $kuota->update($validated);

            return redirect()->route('admin.setting.kuota-wisuda.index')
                ->with('success', 'Kuota wisuda berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $kuota = KuotaWisudawan::findOrFail($id);
            $kuota->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kuota wisuda berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getByPendaftaran(string $pendaftaranId)
    {
        try {
            $kuota = KuotaWisudawan::with(['jadwalPendaftaran'])
                ->where('pendaftaran_wisuda_id', $pendaftaranId)
                ->first();

            if (!$kuota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kuota tidak ditemukan untuk periode ini'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $kuota
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getKuotaTersedia()
    {
        try {
            $kuotaTersedia = KuotaWisudawan::with(['jadwalPendaftaran'])
                ->whereHas('jadwalPendaftaran', function ($query) {
                    $query->where('status', 'Aktif');
                })
                ->get()
                ->map(function($item) {
                    if (!$item->jadwalPendaftaran) {
                        return null;
                    }

                    return [
                        'id' => $item->id,
                        'tahun_wisuda' => $item->jadwalPendaftaran->tahun_wisuda,
                        'jumlah_kuota' => $item->jumlah_kuota,
                        'sisa_kuota' => $item->sisa_kuota,
                        'status' => $item->isKuotaTersedia() ? 'Tersedia' : 'Habis'
                    ];
                })
                ->filter();

            return response()->json([
                'success' => true,
                'data' => $kuotaTersedia->values()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $data = KuotaWisudawan::with(['jadwalPendaftaran'])
                ->get()
                ->map(function($item) {
                    return (object)[
                        'tahun_wisuda' => $item->jadwalPendaftaran->tahun_wisuda ?? '',
                        'jumlah_kuota' => $item->jumlah_kuota,
                        'status_periode' => $item->jadwalPendaftaran->status ?? 'Tidak Aktif'
                    ];
                });

            $fileName = 'Kuota_Wisuda_' . date('Y-m-d') . '.xlsx';

            return Excel::download(new KuotaWisudaExport($data), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $data = KuotaWisudawan::with(['jadwalPendaftaran'])
                ->get()
                ->map(function($item) {
                    return (object)[
                        'tahun_wisuda' => $item->jadwalPendaftaran->tahun_wisuda ?? '',
                        'jumlah_kuota' => $item->jumlah_kuota,
                        'status_periode' => $item->jadwalPendaftaran->status ?? 'Tidak Aktif'
                    ];
                });

            $fileName = 'Kuota_Wisuda_' . date('Y-m-d') . '.pdf';

            $html = view('admin.setting.kuota-wisuda.pdf', [
                'data' => $data,
                'title' => 'Kuota Wisuda'
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
