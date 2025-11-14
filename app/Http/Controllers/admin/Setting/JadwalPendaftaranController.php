<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\JadwalPendaftaran;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Exports\JadwalPendaftaranExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class JadwalPendaftaranController extends Controller
{
    public function index()
    {
        return view('admin.setting.jadwal-pendaftaran.index');
    }

    public function getData(Request $request)
    {
        try {
            $data = JadwalPendaftaran::query()->orderBy('tahun_wisuda', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status_badge', function($row) {
                    return $this->getStatusBadge($row->status);
                })
                ->addColumn('waktu_buka_formatted', function($row) {
                    return Carbon::parse($row->waktu_buka_pendaftaran)->format('d F Y H:i');
                })
                ->addColumn('waktu_tutup_formatted', function($row) {
                    return Carbon::parse($row->waktu_tutup_pendaftaran)->format('d F Y H:i');
                })
                ->addColumn('status_waktu', function($row) {
                    return $this->getStatusWaktu($row);
                })
                ->addColumn('aksi', function($row) {
                    return $this->getActionButtons($row->id);
                })
                ->rawColumns(['status_badge', 'status_waktu', 'aksi'])
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

    private function getStatusBadge($status)
    {
        $statusText = $status;
        $badgeClass = 'badge ';

        if ($status == 'Buka') {
            $statusText = 'Buka';
            $badgeClass .= 'badge-success';

        } elseif ($status == 'Tutup') {
            $statusText = 'Tutup';
            $badgeClass .= 'badge-secondary';

        } elseif ($status == 'Draft') {
            $statusText = 'Draft';
            $badgeClass .= 'badge-warning';

        } else {
            $statusText = $status;
            $badgeClass .= 'badge-info';
        }

        return '<span class="'.$badgeClass.'">'.$statusText.'</span>';
    }

    private function getStatusWaktu($item)
    {
        $now = now();
        $buka = Carbon::parse($item->waktu_buka_pendaftaran);
        $tutup = Carbon::parse($item->waktu_tutup_pendaftaran);

        if ($now < $buka) {
            return '<span class="badge badge-warning">Belum Dimulai</span>';
        } elseif ($now >= $buka && $now <= $tutup) {
            return '<span class="badge badge-success">Sedang Berlangsung</span>';
        } else {
            return '<span class="badge badge-danger">Sudah Berakhir</span>';
        }
    }

    private function getActionButtons($id)
    {
        return '<div class="flex items-center justify-center gap-2">' .
            '<button class="btn-action btn-view" onclick="lihatData(\''.$id.'\')" title="Lihat">' .
            '<i class="fas fa-eye"></i>' .
            '</button>' .
            '<button class="btn-action btn-edit" onclick="editData(\''.$id.'\')" title="Edit">' .
            '<i class="fas fa-pencil-alt"></i>' .
            '</button>' .
            '<button class="btn-action btn-delete" onclick="hapusData(\''.$id.'\', this)" title="Hapus">' .
            '<i class="fas fa-trash"></i>' .
            '</button>' .
            '</div>';
    }

    public function exportData(Request $request)
    {
        try {
            $data = JadwalPendaftaran::query()
                ->select('tahun_wisuda', 'status', 'waktu_buka_pendaftaran', 'waktu_tutup_pendaftaran')
                ->orderBy('tahun_wisuda', 'desc')
                ->get()
                ->map(function($item) {
                    return [
                        'tahun_wisuda' => $item->tahun_wisuda,
                        'status' => $item->status,
                        'waktu_buka_pendaftaran' => Carbon::parse($item->waktu_buka_pendaftaran)->format('d F Y H:i'),
                        'waktu_tutup_pendaftaran' => Carbon::parse($item->waktu_tutup_pendaftaran)->format('d F Y H:i'),
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

    public function create()
    {
        return view('admin.setting.jadwal-pendaftaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_wisuda' => 'required|integer|min:2000|max:2100',
            'status' => 'required|in:Draft,Buka,Tutup',
            'waktu_buka_pendaftaran' => 'required|date',
            'waktu_tutup_pendaftaran' => 'required|date|after:waktu_buka_pendaftaran',
        ]);

        $existing = JadwalPendaftaran::where('tahun_wisuda', $validated['tahun_wisuda'])->exists();
        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jadwal untuk tahun wisuda ' . $validated['tahun_wisuda'] . ' sudah ada.');
        }

        JadwalPendaftaran::create($validated);

        return redirect()->route('admin.setting.jadwal-pendaftaran.index')
            ->with('success', 'Jadwal pendaftaran wisuda berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $jadwal = JadwalPendaftaran::findOrFail($id);

        return view('admin.setting.jadwal-pendaftaran.show', [
            'jadwal' => $jadwal
        ]);
    }

    public function edit(string $id)
    {
        $jadwal = JadwalPendaftaran::findOrFail($id);

        return view('admin.setting.jadwal-pendaftaran.edit', [
            'jadwal' => $jadwal
        ]);
    }

    public function update(Request $request, string $id)
    {
        $jadwal = JadwalPendaftaran::findOrFail($id);

        $validated = $request->validate([
            'tahun_wisuda' => 'required|integer|min:2000|max:2100',
            'status' => 'required|in:Draft,Buka,Tutup',
            'waktu_buka_pendaftaran' => 'required|date',
            'waktu_tutup_pendaftaran' => 'required|date|after:waktu_buka_pendaftaran',
        ]);

        // Validasi tambahan: cek apakah tahun wisuda sudah ada (kecuali untuk data ini)
        $existing = JadwalPendaftaran::where('tahun_wisuda', $validated['tahun_wisuda'])
            ->where('id', '!=', $id)
            ->exists();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jadwal untuk tahun wisuda ' . $validated['tahun_wisuda'] . ' sudah ada.');
        }

        $jadwal->update($validated);

        return redirect()->route('admin.setting.jadwal-pendaftaran.index')
            ->with('success', 'Jadwal pendaftaran wisuda berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        try {
            $jadwal = JadwalPendaftaran::findOrFail($id);
            $jadwal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal pendaftaran wisuda berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function activate(string $id)
    {
        try {
            $jadwal = JadwalPendaftaran::findOrFail($id);

            JadwalPendaftaran::where('status', 'Aktif')->update(['status' => 'Nonaktif']);

            $jadwal->update(['status' => 'Aktif']);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal pendaftaran berhasil diaktifkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaktifkan jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $data = JadwalPendaftaran::query()
                ->orderBy('tahun_wisuda', 'desc')
                ->get();

            $fileName = 'Jadwal_Pendaftaran_' . date('Y-m-d') . '.xlsx';

            return Excel::download(new JadwalPendaftaranExport($data), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $data = JadwalPendaftaran::query()
                ->orderBy('tahun_wisuda', 'desc')
                ->get();

            $fileName = 'Jadwal_Pendaftaran_' . date('Y-m-d') . '.pdf';

            $html = view('admin.setting.jadwal-pendaftaran.pdf', [
                'data' => $data,
                'title' => 'Jadwal Pendaftaran Wisuda'
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
