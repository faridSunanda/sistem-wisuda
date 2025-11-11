<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\PelaksanaanWisuda;
use App\Models\JadwalPendaftaran;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

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
}

