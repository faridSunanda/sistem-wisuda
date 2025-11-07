<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalPendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: Fetch data from database
        // $jadwalPendaftarans = PendaftaranWisuda::latest()->get();
        
        return view('admin.setting.jadwal-pendaftaran.index', [
            // 'jadwalPendaftarans' => $jadwalPendaftarans,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.setting.jadwal-pendaftaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_wisuda' => 'required|integer|min:2000|max:2100',
            'status' => 'required|string|in:Draft,Aktif,Nonaktif',
            'waktu_buka_pendaftaran' => 'required|date',
            'waktu_tutup_pendaftaran' => 'required|date|after:waktu_buka_pendaftaran',
        ]);

        try {
            // TODO: Create new record
            // PendaftaranWisuda::create($validated);
            
            return redirect()
                ->route('admin.setting.jadwal-pendaftaran.index')
                ->with('success', 'Jadwal pendaftaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan jadwal pendaftaran.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // TODO: Fetch data from database
        // $jadwalPendaftaran = PendaftaranWisuda::findOrFail($id);
        
        return view('admin.setting.jadwal-pendaftaran.show', [
            // 'jadwalPendaftaran' => $jadwalPendaftaran,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // TODO: Fetch data from database
        // $jadwalPendaftaran = PendaftaranWisuda::findOrFail($id);
        
        return view('admin.setting.jadwal-pendaftaran.edit', [
            // 'jadwalPendaftaran' => $jadwalPendaftaran,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'tahun_wisuda' => 'required|integer|min:2000|max:2100',
            'status' => 'required|string|in:Draft,Aktif,Nonaktif',
            'waktu_buka_pendaftaran' => 'required|date',
            'waktu_tutup_pendaftaran' => 'required|date|after:waktu_buka_pendaftaran',
        ]);

        try {
            // TODO: Update record
            // $jadwalPendaftaran = PendaftaranWisuda::findOrFail($id);
            // $jadwalPendaftaran->update($validated);
            
            return redirect()
                ->route('admin.setting.jadwal-pendaftaran.index')
                ->with('success', 'Jadwal pendaftaran berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui jadwal pendaftaran.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // TODO: Delete record
            // $jadwalPendaftaran = PendaftaranWisuda::findOrFail($id);
            // $jadwalPendaftaran->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Jadwal pendaftaran berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus jadwal pendaftaran.'
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

