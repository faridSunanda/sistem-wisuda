<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AlurPendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: Fetch data from database
        // $alurPendaftarans = AlurPendaftaran::orderBy('no_urut')->get();
        
        return view('admin.setting.alur-pendaftaran.index', [
            // 'alurPendaftarans' => $alurPendaftarans,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.setting.alur-pendaftaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_urut' => 'required|integer|unique:alur_pendaftarans,no_urut',
            'judul' => 'required|string|max:255',
            'keterangan' => 'required|string',
        ]);

        try {
            // TODO: Create new record
            // AlurPendaftaran::create($validated);
            
            return redirect()
                ->route('admin.setting.alur-pendaftaran.index')
                ->with('success', 'Alur pendaftaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan alur pendaftaran.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // TODO: Fetch data from database
        // $alurPendaftaran = AlurPendaftaran::findOrFail($id);
        
        return view('admin.setting.alur-pendaftaran.show', [
            // 'alurPendaftaran' => $alurPendaftaran,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // TODO: Fetch data from database
        // $alurPendaftaran = AlurPendaftaran::findOrFail($id);
        
        return view('admin.setting.alur-pendaftaran.edit', [
            // 'alurPendaftaran' => $alurPendaftaran,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'no_urut' => 'required|integer|unique:alur_pendaftarans,no_urut,' . $id,
            'judul' => 'required|string|max:255',
            'keterangan' => 'required|string',
        ]);

        try {
            // TODO: Update record
            // $alurPendaftaran = AlurPendaftaran::findOrFail($id);
            // $alurPendaftaran->update($validated);
            
            return redirect()
                ->route('admin.setting.alur-pendaftaran.index')
                ->with('success', 'Alur pendaftaran berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui alur pendaftaran.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // TODO: Delete record
            // $alurPendaftaran = AlurPendaftaran::findOrFail($id);
            // $alurPendaftaran->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Alur pendaftaran berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus alur pendaftaran.'
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

