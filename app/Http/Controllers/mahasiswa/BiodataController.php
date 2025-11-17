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

class BiodataController extends Controller
{
    /**
     * Menampilkan halaman form edit biodata.
     */
    public function edit()
    {
        $user = Auth::user();
        
        $biodata = $user->biodata()->with('dosenPembimbings')->first();
        
        return view('mahasiswa.data-diri.index', compact('biodata'));
    }

    /**
     * Meng-update biodata, data user, dan data dosen pembimbing.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $biodataId = $user->biodata->id ?? 'NULL';

        
        $validator = Validator::make($request->all(), [
            
            'name_lengkap' => 'required|string|max:255',
            'email'        => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],

            
            
            'nik'               => 'nullable|string|digits:16|unique:biodatas,nik,' . $biodataId . ',id,deleted_at,NULL',
            'nim'               => 'nullable|string|max:20|unique:biodatas,nim,' . $biodataId . ',id,deleted_at,NULL',
            'nirm'              => 'nullable|string|max:20',
            'nirl'              => 'nullable|string|max:20',
            'foto_profile'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tempat_lahir'      => 'nullable|string|max:100',
            'tanggal_lahir'     => 'nullable|date',
            'jenis_kelamin'     => 'nullable|string',
            'status_mahasiswa'  => 'nullable|string',
            'tahun_masuk'       => 'nullable|digits:4',
            'fakultas'          => 'nullable|string',
            'program_studi'     => 'nullable|string',
            'alamat_rumah'      => 'nullable|string',
            'no_telepon'        => 'nullable|string|max:15',
            'judul_skripsi'     => 'nullable|string',
            'kesan_pesan'       => 'nullable|string',

            
            'dosen_pembimbing'   => 'nullable|array',
            'dosen_pembimbing.*' => 'nullable|string|max:255', 
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        
        
        try {
            DB::beginTransaction();

            $user->update([
                'name_lengkap' => $request->input('name_lengkap'),
                'email'        => $request->input('email'),
            ]);

            $biodataData = $request->except([
                '_token', 'name_lengkap', 'email', 'foto_profile', 'dosen_pembimbing'
            ]);

            if ($request->hasFile('foto_profile')) {
                if ($user->biodata && $user->biodata->foto_profile) {
                    Storage::disk('public')->delete($user->biodata->foto_profile);
                }
                
                $path = $request->file('foto_profile')->store('foto_profil', 'public');
                $biodataData['foto_profile'] = $path;
            }

            $biodata = $user->biodata()->updateOrCreate(
                ['user_id' => $user->id], 
                $biodataData              
            );

            $biodata->dosenPembimbings()->delete();

            $dosenNames = $request->input('dosen_pembimbing', []);
            $dosenDataToInsert = [];
            foreach ($dosenNames as $namaDosen) {
                if (!empty($namaDosen)) {
                    $dosenDataToInsert[] = ['nama' => $namaDosen]; 
                }
            }

            if (!empty($dosenDataToInsert)) {
                $biodata->dosenPembimbings()->createMany($dosenDataToInsert);
            }

            DB::commit();

            return back()->with('success', 'Biodata berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Gagal memperbarui biodata: ' . $e->getMessage()); 
        }
    }
}