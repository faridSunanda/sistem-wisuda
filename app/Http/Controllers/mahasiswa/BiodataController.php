<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\DosenPembimbing;
use App\Models\Wisuda;
use App\Models\Biodata;

class BiodataController extends Controller
{
    /**
     * Menampilkan halaman form biodata.
     */
    public function index()
    {
        $user = Auth::user();

        $biodata = $user->biodata()->with('dosenPembimbings')->first();

        // Cari wisuda dengan status 'dibuka' (bukan 'aktif')
        $wisudaAktif = Wisuda::where('status', 'dibuka')->first();

        return view('mahasiswa.data-diri.index', compact('biodata', 'wisudaAktif'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Cari wisuda dengan status 'dibuka'
        $wisudaAktif = Wisuda::where('status', 'dibuka')->first();

        // DEBUG: Log data wisuda
        \Log::info('=== DEBUG WISUDA ===');
        \Log::info('Wisuda ditemukan: ' . ($wisudaAktif ? 'YA' : 'TIDAK'));
        if ($wisudaAktif) {
            \Log::info('Wisuda ID: ' . $wisudaAktif->id);
            \Log::info('Wisuda Angkatan: ' . $wisudaAktif->angkatan);
        }

        if (!$wisudaAktif) {
            return back()->with('error', 'Tidak ada periode wisuda yang dibuka. Silakan hubungi admin.');
        }

        $biodataId = $user->biodata->id ?? 'NULL';

        // Validasi
        $validator = Validator::make($request->all(), [
            'name_lengkap' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nik' => [
                'nullable',
                'string',
                'digits:16',
                $biodataId 
                    ? Rule::unique('biodatas', 'nik')->ignore($biodataId, 'id')->whereNull('deleted_at')
                    : Rule::unique('biodatas', 'nik')->whereNull('deleted_at')
            ],
            'nim' => [
                'nullable',
                'string',
                'max:20',
                $biodataId
                    ? Rule::unique('biodatas', 'nim')->ignore($biodataId, 'id')->whereNull('deleted_at')
                    : Rule::unique('biodatas', 'nim')->whereNull('deleted_at')
            ],
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|string',
            'status_mahasiswa' => 'nullable|string',
            'tahun_masuk' => 'nullable|digits:4',
            'fakultas' => 'nullable|string',
            'program_studi' => 'nullable|string',
            'alamat_rumah' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:15',
            'judul_skripsi' => 'nullable|string',
            'kesan_pesan' => 'nullable|string',
            'dosen_pembimbing' => 'nullable|array',
            'dosen_pembimbing.*' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Update user data
            $user->update([
                'name_lengkap' => $request->input('name_lengkap'),
                'email' => $request->input('email'),
            ]);

            // Prepare biodata data
            $biodataData = $request->except([
                '_token', 'name_lengkap', 'email', 'foto_profile', 'dosen_pembimbing'
            ]);

            // TAMBAHKAN WISUDA_ID ke data
            $biodataData['wisuda_id'] = $wisudaAktif->id;

            // DEBUG: Log data sebelum save
            \Log::info('=== DATA BIODATA SEBELUM SAVE ===');
            \Log::info('User ID: ' . $user->id);
            \Log::info('Wisuda ID yang akan disimpan: ' . $biodataData['wisuda_id']);
            \Log::info('Data lengkap: ', $biodataData);

            // Handle foto profile
            if ($request->hasFile('foto_profile')) {
                if ($user->biodata && $user->biodata->foto_profile) {
                    Storage::disk('public')->delete($user->biodata->foto_profile);
                }

                $path = $request->file('foto_profile')->store('foto_profil', 'public');
                $biodataData['foto_profile'] = $path;
            }

            // Jika biodata belum ada, kita perlu set wisuda_id
            if (!$biodata) {
                $activeWisuda = \App\Models\Wisuda::where('status', 'aktif')->first();
                if (!$activeWisuda) {
                    throw new \Exception('Tidak ada periode wisuda yang aktif saat ini.');
                }
                $biodataData['wisuda_id'] = $activeWisuda->id;
            }

            // Update or create biodata
            $biodata = $user->biodata()->updateOrCreate(
                ['user_id' => $user->id],
                $biodataData
            );

            // DEBUG: Log setelah save
            \Log::info('=== SETELAH SAVE BIODATA ===');
            \Log::info('Biodata ID: ' . $biodata->id);
            \Log::info('Wisuda ID di biodata: ' . $biodata->wisuda_id);

            // Handle dosen pembimbing
            $biodata->dosenPembimbings()->delete();

            $dosenNames = $request->input('dosen_pembimbing', []);
            $dosenDataToInsert = [];
            foreach ($dosenNames as $index => $namaDosen) {
                if (!empty($namaDosen)) {
                    $dosenDataToInsert[] = [
                        'id' => \Illuminate\Support\Str::uuid(),
                        'nama' => $namaDosen,
                        'urutan' => $index + 1
                    ];
                }
            }

            if (!empty($dosenDataToInsert)) {
                $biodata->dosenPembimbings()->createMany($dosenDataToInsert);
            }

            DB::commit();
            return back()->with('success', 'Biodata berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating biodata: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Gagal memperbarui biodata: ' . $e->getMessage());
        }
    }

    /**
     * Method untuk sinkronisasi dari API (jika diperlukan)
     */
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



    private function handlePhotoUpload(Request $request, $biodata = null)
    {
        if ($biodata && $biodata->foto_profile) {
            Storage::disk('public')->delete($biodata->foto_profile);
        }

        return $request->file('foto_profile')->store('foto_profil', 'public');
    }

    private function syncDosenPembimbing(Biodata $biodata, array $dosenNames)
    {
        $biodata->dosenPembimbings()->delete();

        $dosenDataToInsert = array_map(
            fn($nama) => ['nama' => $nama],
            array_filter($dosenNames, fn($nama) => !empty($nama))
        );

        if (!empty($dosenDataToInsert)) {
            $biodata->dosenPembimbings()->createMany($dosenDataToInsert);
        }
    }
}

