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
        'wisuda_id',
        'nim',
        'nik',
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
        'is_bayar',
        'is_verified_akademik', 
        'is_verified_keuangan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_verified_akademik' => 'boolean',
        'is_verified_keuangan' => 'boolean',
        'is_bayar' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wisuda()
    {
        return $this->belongsTo(Wisuda::class);
    }

    public function dosenPembimbings()
    {
        return $this->hasMany(DosenPembimbing::class, 'biodata_id');
    }

    public function sertifikatKompetensi()
    {
        return $this->hasMany(Sertifikat::class)->where('jenis', 'kompetensi');
    }

    public function sertifikatBahasaInternasional()
    {
        return $this->hasMany(Sertifikat::class)->where('jenis', 'bahasa');
    }

    public function sertifikatMagang()
    {
        return $this->hasMany(Sertifikat::class)->where('jenis', 'magang');
    }

    public function sertifikatPendidikanKarakter()
    {
        return $this->hasMany(Sertifikat::class)->where('jenis', 'karakter');
    }

    public function sertifikatOrganisasi()
    {
        return $this->hasMany(Sertifikat::class)->where('jenis', 'organisasi');
    }

    public function sertifikatPenghargaan()
    {
        return $this->hasMany(Sertifikat::class)->where('jenis', 'penghargaan');
    }
}