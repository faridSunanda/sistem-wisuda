<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SertifikatMagang extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    public function getKeyType(){ return 'string'; }
    public function getIncrementing(){ return false; }

    protected $fillable = [
        'biodata_id',
        'nama_sertifikat',
        'penerbit',
        'tanggal_terbit',
    ];

    protected $casts = [
        'id' => 'string',
        'tanggal_terbit' => 'date',
    ];

    public function biodata()
    {
        return $this->belongsTo(Biodata::class);
    }
}