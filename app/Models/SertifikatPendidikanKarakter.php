<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SertifikatPendidikanKarakter extends Model
{
    use HasFactory, SoftDeletes, HasUuids; 

    protected $table = 'sertifikat_pendidikan_karakters';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'biodata_id',
        'nama_sertifikat',
        'penerbit',
        'tanggal_terbit'
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
    ];

    public function biodata()
    {
        return $this->belongsTo(Biodata::class);
    }
}