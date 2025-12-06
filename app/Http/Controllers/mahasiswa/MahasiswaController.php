<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Biodata;
use App\Models\Wisuda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BiodataController extends Controller
{
    public function index()
    {
        // Ambil biodata milik user yang login
        $biodata = Biodata::with(['dosenPembimbings', 'sertifikatKompetensis'])
            ->where('user_id', auth()->id())
            ->first();

        // Ambil wisuda aktif
        $wisudaAktif = Wisuda::where('status', 'aktif')->first();

        return view('mahasiswa.data-diri.index', compact('biodata', 'wisudaAktif'));
    }

    public function update(Request $request)
    {
        // Cari biodata berdasarkan user_id
        $biodata = Biodata::where('user_id', auth()->id())->first();

        // Validasi
        $validated = $request->validate([
            'nik' => 'required|string|max:20',
            'nim' => 'required|string|max:20',
            'nirm' => 'nullable|string|max:50',
            'nirl' => 'nullable|string|max:50',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status_mahasiswa' => 'required|string|max:50',
            'tahun_masuk' => 'required|digits:4',
            'fakultas' => 'required|string|max:100',
            'program_studi' => 'required|string|max:100',
            'alamat_rumah' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'judul_skripsi' => 'required|string',
            'kesan_pesan' => 'nullable|string',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dosen_pembimbing' => 'nullable|array',
        ]);

        try {
            // Cari wisuda aktif
            $wisudaAktif = Wisuda::where('status', 'aktif')->first();

            if (!$wisudaAktif) {
                return redirect()->back()->with('error', 'Tidak ada periode wisuda yang aktif.');
            }

            if ($biodata) {
                // Update biodata existing
                
                // Handle upload foto
                if ($request->hasFile('foto_profile')) {
                    // Hapus foto lama jika ada
                    if ($biodata->foto_profile && Storage::exists('public/' . $biodata->foto_profile)) {
                        Storage::delete('public/' . $biodata->foto_profile);
                    }
                    
                    // Simpan foto baru
                    $fotoPath = $request->file('foto_profile')->store('foto-profil', 'public');
                    $validated['foto_profile'] = $fotoPath;
                }

                $biodata->update($validated);
                
                // Handle dosen pembimbing
                if ($request->has('dosen_pembimbing')) {
                    $this->updateDosenPembimbing($biodata, $request->dosen_pembimbing);
                }

                $message = 'Biodata berhasil diperbarui!';
            } else {
                // Create new biodata
                $validated['user_id'] = auth()->id();
                $validated['wisuda_id'] = $wisudaAktif->id;
                $validated['id'] = \Illuminate\Support\Str::uuid();

                // Handle upload foto
                if ($request->hasFile('foto_profile')) {
                    $fotoPath = $request->file('foto_profile')->store('foto-profil', 'public');
                    $validated['foto_profile'] = $fotoPath;
                }

                $biodata = Biodata::create($validated);
                
                // Handle dosen pembimbing
                if ($request->has('dosen_pembimbing')) {
                    $this->updateDosenPembimbing($biodata, $request->dosen_pembimbing);
                }

                $message = 'Biodata berhasil disimpan!';
            }

            return redirect()->route('mahasiswa.biodata.index')->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Error saving biodata: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan biodata: ' . $e->getMessage());
        }
    }

    // Method untuk sinkronisasi data dari API
    public function syncFromApi()
    {
        try {
            // Implementasi sinkronisasi dari API
            // ... kode sinkronisasi Anda ...

            return redirect()->route('mahasiswa.biodata.index')->with('success', 'Data berhasil disinkronisasi dari API');
        } catch (\Exception $e) {
            return redirect()->route('mahasiswa.biodata.index')->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
    }

    // Helper method untuk update dosen pembimbing
    private function updateDosenPembimbing($biodata, $dosenList)
    {
        // Hapus semua dosen pembimbing existing
        $biodata->dosenPembimbings()->delete();

        // Tambahkan dosen pembimbing baru
        foreach ($dosenList as $namaDosen) {
            if (!empty(trim($namaDosen))) {
                $biodata->dosenPembimbings()->create([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'nama' => trim($namaDosen),
                    'urutan' => $biodata->dosenPembimbings()->count() + 1
                ]);
            }
        }
    }
}