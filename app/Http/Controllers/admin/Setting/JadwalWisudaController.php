<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\PelaksanaanWisuda;
use App\Models\JadwalPendaftaran;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Exports\JadwalWisudaExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class JadwalWisudaController extends Controller
{

    public function index()
    {
        return view('admin.setting.jadwal-wisuda.index');
    }

    public function create()
    {
        $jadwalPendaftarans = JadwalPendaftaran::aktif()->get();

        return view('admin.setting.jadwal-wisuda.create', compact('jadwalPendaftarans'));
    }

    public function edit(string $id)
    {
        $jadwal = PelaksanaanWisuda::findOrFail($id);
        $jadwalPendaftarans = JadwalPendaftaran::aktif()->get();

        return view('admin.setting.jadwal-wisuda.edit', compact('jadwal', 'jadwalPendaftarans'));
    }

    public function getData(Request $request)
    {
        $data = PelaksanaanWisuda::query()
            ->orderBy('waktu_pelaksanaan', 'desc');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_kegiatan', function($row) {
                return $row->nama_kegiatan;
            })
            ->addColumn('waktu_formatted', function($row) {
                return $row->waktu_pelaksanaan->format('d F Y H:i');
            })
            ->addColumn('tempat_pelaksanaan', function($row) {
                return $row->tempat_pelaksanaan;
            })
            ->addColumn('keterangan', function($row) {
                return $row->keterangan;
            })
            ->addColumn('aksi', function($row) {
                $id = $row->id;

                $btn = '<div class="flex items-center justify-center gap-2">';

                $btn .= '<button class="btn-action btn-view" onclick="lihatData(\''.$id.'\')" title="Lihat">';
                $btn .= '<i class="fas fa-eye"></i>';
                $btn .= '</button>';

                $btn .= '<button class="btn-action btn-edit" onclick="editData(\''.$id.'\')" title="Edit">';
                $btn .= '<i class="fas fa-pencil-alt"></i>';
                $btn .= '</button>';

                $btn .= '<button class="btn-action btn-delete" onclick="hapusData(\''.$id.'\')" title="Hapus">';
                $btn .= '<i class="fas fa-trash"></i>';
                $btn .= '</button>';

                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show(string $id)
    {
        $jadwal = PelaksanaanWisuda::findOrFail($id);

        return view('admin.setting.jadwal-wisuda.show', [
            'jadwal' => $jadwal
        ]);
    }

    public function exportData(Request $request)
    {
        try {
            $data = PelaksanaanWisuda::query()
                ->select('nama_kegiatan', 'waktu_pelaksanaan', 'tempat_pelaksanaan', 'keterangan')
                ->orderBy('waktu_pelaksanaan', 'asc')
                ->get()
                ->map(function($item) {
                    return [
                        'nama_kegiatan' => $item->nama_kegiatan,
                        'waktu_pelaksanaan' => $item->waktu_pelaksanaan->format('d F Y H:i'),
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jadwal_pendaftaran_id' => 'required|uuid|exists:pendaftaran_wisudas,id', // Sesuaikan field name
            'nama_kegiatan' => 'required|string|max:255',
            'waktu_pelaksanaan' => 'required|date',
            'tempat_pelaksanaan' => 'required|string|max:255',
            'keterangan' => 'required|string',
        ]);

        $validated['pendaftaran_wisuda_id'] = $validated['jadwal_pendaftaran_id'];
        unset($validated['jadwal_pendaftaran_id']);

        try {
            PelaksanaanWisuda::create($validated);

            return redirect()->route('admin.setting.jadwal-wisuda.index')
                ->with('success', 'Jadwal wisuda berhasil ditambahkan.');

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
            'jadwal_pendaftaran_id' => 'required|uuid|exists:pendaftaran_wisudas,id',
            'nama_kegiatan' => 'required|string|max:255',
            'waktu_pelaksanaan' => 'required|date',
            'tempat_pelaksanaan' => 'required|string|max:255',
            'keterangan' => 'required|string',
        ]);

        // Ubah field name
        $validated['pendaftaran_wisuda_id'] = $validated['jadwal_pendaftaran_id'];
        unset($validated['jadwal_pendaftaran_id']);

        try {
            $jadwal->update($validated);

            return redirect()->route('admin.setting.jadwal-wisuda.index')
                ->with('success', 'Jadwal wisuda berhasil diperbarui.');

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
                'message' => 'Jadwal wisuda berhasil dihapus.'
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
            $jadwal = PelaksanaanWisuda::with(['pendaftaranWisuda.mahasiswa'])
                ->where('pendaftaran_wisuda_id', $pendaftaranId)
                ->first();

            if (!$jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $jadwal
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
            $data = PelaksanaanWisuda::query()
                ->orderBy('waktu_pelaksanaan', 'desc')
                ->get();

            $fileName = 'Jadwal_Wisuda_' . date('Y-m-d') . '.xlsx';

            return Excel::download(new JadwalWisudaExport($data), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $data = PelaksanaanWisuda::query()
                ->orderBy('waktu_pelaksanaan', 'desc')
                ->get();

            $fileName = 'Jadwal_Wisuda_' . date('Y-m-d') . '.pdf';

            $html = view('admin.setting.jadwal-wisuda.pdf', [
                'data' => $data,
                'title' => 'Jadwal Wisuda'
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

