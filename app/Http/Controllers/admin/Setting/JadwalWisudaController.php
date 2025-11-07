<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalWisudaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: Fetch data from database
        // $jadwalWisudas = PelaksanaanWisuda::with('pendaftaranWisuda')->latest()->get();
        
        return view('admin.setting.jadwal-wisuda.index', [
            // 'jadwalWisudas' => $jadwalWisudas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TODO: Fetch pendaftaran wisuda for dropdown
        // $pendaftaranWisudas = PendaftaranWisuda::where('status', 'Aktif')->get();
        
        return view('admin.setting.jadwal-wisuda.create', [
            // 'pendaftaranWisudas' => $pendaftaranWisudas,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pendaftaran_wisuda_id' => 'required|uuid|exists:pendaftaran_wisudas,id',
            'nama_kegiatan' => 'required|string|max:255',
            'waktu_pelaksanaan' => 'required|date',
            'tempat_pelaksanaan' => 'required|string|max:255',
            'keterangan' => 'required|string',
        ]);

        try {
            // TODO: Create new record
            // PelaksanaanWisuda::create($validated);
            
            return redirect()
                ->route('admin.setting.jadwal-wisuda.index')
                ->with('success', 'Jadwal wisuda berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan jadwal wisuda.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // TODO: Fetch data from database
        // $jadwalWisuda = PelaksanaanWisuda::with('pendaftaranWisuda')->findOrFail($id);
        
        return view('admin.setting.jadwal-wisuda.show', [
            // 'jadwalWisuda' => $jadwalWisuda,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // TODO: Fetch data from database
        // $jadwalWisuda = PelaksanaanWisuda::findOrFail($id);
        // $pendaftaranWisudas = PendaftaranWisuda::where('status', 'Aktif')->get();
        
        return view('admin.setting.jadwal-wisuda.edit', [
            // 'jadwalWisuda' => $jadwalWisuda,
            // 'pendaftaranWisudas' => $pendaftaranWisudas,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'pendaftaran_wisuda_id' => 'required|uuid|exists:pendaftaran_wisudas,id',
            'nama_kegiatan' => 'required|string|max:255',
            'waktu_pelaksanaan' => 'required|date',
            'tempat_pelaksanaan' => 'required|string|max:255',
            'keterangan' => 'required|string',
        ]);

        try {
            // TODO: Update record
            // $jadwalWisuda = PelaksanaanWisuda::findOrFail($id);
            // $jadwalWisuda->update($validated);
            
            return redirect()
                ->route('admin.setting.jadwal-wisuda.index')
                ->with('success', 'Jadwal wisuda berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui jadwal wisuda.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // TODO: Delete record
            // $jadwalWisuda = PelaksanaanWisuda::findOrFail($id);
            // $jadwalWisuda->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Jadwal wisuda berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus jadwal wisuda.'
            ], 500);
        }
    }

    /**
     * Get data for DataTables or API.
     */
    public function getData(Request $request)
    {
        // TODO: Implement DataTables server-side processing
        // return response()->json($data);
    }
}

