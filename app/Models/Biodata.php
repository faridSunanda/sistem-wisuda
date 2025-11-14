<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Biodata extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'nim',
        'nik',
        'nirm',
        'nirl',
        'foto_profile',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_mahasiswa',
        'tahun_masuk',
        'fakultas',
        'program_studi',
        'alamat_rumah',
        'no_telepon',
        'judul_skripsi',
        'kesan_pesan',
    ];

     protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

public function dosenPembimbings()
    {
        // Satu Biodata 'hasMany' (memiliki banyak) DosenPembimbing
        return $this->hasMany(DosenPembimbing::class, 'biodata_id');
    }

    public function sertifikatKompetensi()
    {
        return $this->hasMany(SertifikatKompetensi::class);
    }

    public function sertifikatBahasaInternasional()
    {
        return $this->hasMany(SertifikatBahasaInternasional::class);
    }

    public function sertifikatMagang()
    {
        return $this->hasMany(SertifikatMagang::class);
    }

    public function sertifikatPendidikanKarakter()
    {
        return $this->hasMany(SertifikatPendidikanKarakter::class);
    }

    public function sertifikatOrganisasi()
    {
        return $this->hasMany(SertifikatOrganisasi::class);
    }

    public function sertifikatPenghargaan()
    {
        return $this->hasMany(SertifikatPenghargaan::class);
    }
}
