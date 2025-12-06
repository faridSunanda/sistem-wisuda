<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Biodata;

class BiodataController extends Controller
{
    public function edit()
    {
        $biodata = Auth::user()->biodata()->with('dosenPembimbings')->first();
        return view('mahasiswa.data-diri.index', compact('biodata'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $biodata = $user->biodata;
        $biodataId = $biodata->id ?? null;

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

            $user->update([
                'name_lengkap' => $request->input('name_lengkap'),
                'email' => $request->input('email'),
            ]);

            $biodataData = $request->except([
                '_token', 'name_lengkap', 'email', 'foto_profile', 'dosen_pembimbing', 'nirm', 'nirl'
            ]);

            if ($request->hasFile('foto_profile')) {
                $biodataData['foto_profile'] = $this->handlePhotoUpload($request, $biodata);
            }

            // Jika biodata belum ada, kita perlu set wisuda_id
            if (!$biodata) {
                $activeWisuda = \App\Models\Wisuda::where('status', 'aktif')->first();
                if (!$activeWisuda) {
                    throw new \Exception('Tidak ada periode wisuda yang aktif saat ini.');
                }
                $biodataData['wisuda_id'] = $activeWisuda->id;
            }

            $biodata = $user->biodata()->updateOrCreate(
                ['user_id' => $user->id],
                $biodataData
            );

            $this->syncDosenPembimbing($biodata, $request->input('dosen_pembimbing', []));

            DB::commit();
            return back()->with('success', 'Biodata berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui biodata: ' . $e->getMessage());
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
