<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DokumenPersyaratanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: Fetch data from database
        // $dokumenPersyaratans = DokumenPersyaratan::latest()->get();
        
        return view('admin.setting.dokumen-persyaratan.index', [
            // 'dokumenPersyaratans' => $dokumenPersyaratans,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.setting.dokumen-persyaratan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe_dokumen' => 'required|string|max:100',
            'nama_dokumen' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'berkas' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        try {
            // TODO: Handle file upload
            // if ($request->hasFile('berkas')) {
            //     $validated['berkas'] = $request->file('berkas')->store('dokumen-persyaratan', 'public');
            // }
            
            // TODO: Create new record
            // DokumenPersyaratan::create($validated);
            
            return redirect()
                ->route('admin.setting.dokumen-persyaratan.index')
                ->with('success', 'Dokumen persyaratan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan dokumen persyaratan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // TODO: Fetch data from database
        // $dokumenPersyaratan = DokumenPersyaratan::findOrFail($id);
        
        return view('admin.setting.dokumen-persyaratan.show', [
            // 'dokumenPersyaratan' => $dokumenPersyaratan,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // TODO: Fetch data from database
        // $dokumenPersyaratan = DokumenPersyaratan::findOrFail($id);
        
        return view('admin.setting.dokumen-persyaratan.edit', [
            // 'dokumenPersyaratan' => $dokumenPersyaratan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'tipe_dokumen' => 'required|string|max:100',
            'nama_dokumen' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'berkas' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        try {
            // TODO: Handle file upload
            // $dokumenPersyaratan = DokumenPersyaratan::findOrFail($id);
            // if ($request->hasFile('berkas')) {
            //     // Delete old file
            //     Storage::disk('public')->delete($dokumenPersyaratan->berkas);
            //     $validated['berkas'] = $request->file('berkas')->store('dokumen-persyaratan', 'public');
            // } else {
            //     unset($validated['berkas']);
            // }
            
            // TODO: Update record
            // $dokumenPersyaratan->update($validated);
            
            return redirect()
                ->route('admin.setting.dokumen-persyaratan.index')
                ->with('success', 'Dokumen persyaratan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui dokumen persyaratan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // TODO: Delete record and file
            // $dokumenPersyaratan = DokumenPersyaratan::findOrFail($id);
            // Storage::disk('public')->delete($dokumenPersyaratan->berkas);
            // $dokumenPersyaratan->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Dokumen persyaratan berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus dokumen persyaratan.'
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

