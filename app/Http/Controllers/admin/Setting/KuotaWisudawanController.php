<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KuotaWisudawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: Fetch data from database
        // $kuotaWisudawans = KuotaWisudawan::with('pendaftaranWisuda')->latest()->get();
        
        return view('admin.setting.kuota-wisudawan.index', [
            // 'kuotaWisudawans' => $kuotaWisudawans,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TODO: Fetch pendaftaran wisuda for dropdown
        // $pendaftaranWisudas = PendaftaranWisuda::where('status', 'Aktif')->get();
        
        return view('admin.setting.kuota-wisudawan.create', [
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
            'jumlah_kuota' => 'required|integer|min:1',
        ]);

        try {
            // TODO: Check if kuota already exists for this pendaftaran_wisuda_id
            // $existing = KuotaWisudawan::where('pendaftaran_wisuda_id', $validated['pendaftaran_wisuda_id'])->first();
            // if ($existing) {
            //     return redirect()->back()->withInput()->with('error', 'Kuota untuk pendaftaran wisuda ini sudah ada.');
            // }
            
            // TODO: Create new record
            // KuotaWisudawan::create($validated);
            
            return redirect()
                ->route('admin.setting.kuota-wisudawan.index')
                ->with('success', 'Kuota wisudawan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan kuota wisudawan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // TODO: Fetch data from database
        // $kuotaWisudawan = KuotaWisudawan::with('pendaftaranWisuda')->findOrFail($id);
        
        return view('admin.setting.kuota-wisudawan.show', [
            // 'kuotaWisudawan' => $kuotaWisudawan,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // TODO: Fetch data from database
        // $kuotaWisudawan = KuotaWisudawan::findOrFail($id);
        // $pendaftaranWisudas = PendaftaranWisuda::where('status', 'Aktif')->get();
        
        return view('admin.setting.kuota-wisudawan.edit', [
            // 'kuotaWisudawan' => $kuotaWisudawan,
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
            'jumlah_kuota' => 'required|integer|min:1',
        ]);

        try {
            // TODO: Check if kuota already exists for this pendaftaran_wisuda_id (excluding current)
            // $existing = KuotaWisudawan::where('pendaftaran_wisuda_id', $validated['pendaftaran_wisuda_id'])
            //     ->where('id', '!=', $id)
            //     ->first();
            // if ($existing) {
            //     return redirect()->back()->withInput()->with('error', 'Kuota untuk pendaftaran wisuda ini sudah ada.');
            // }
            
            // TODO: Update record
            // $kuotaWisudawan = KuotaWisudawan::findOrFail($id);
            // $kuotaWisudawan->update($validated);
            
            return redirect()
                ->route('admin.setting.kuota-wisudawan.index')
                ->with('success', 'Kuota wisudawan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui kuota wisudawan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // TODO: Delete record
            // $kuotaWisudawan = KuotaWisudawan::findOrFail($id);
            // $kuotaWisudawan->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Kuota wisudawan berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus kuota wisudawan.'
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

