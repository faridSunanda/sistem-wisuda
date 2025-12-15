<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sertifikat extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'sertifikats';
    
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'biodata_id',
        'jenis',
        'nama_sertifikat',
        'penerbit',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_terbit',
        'berkas',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_terbit' => 'date',
    ];

    public function biodata()
    {
        return $this->belongsTo(Biodata::class);
    }
}