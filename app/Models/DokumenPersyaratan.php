<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DokumenPersyaratan extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'dokumen_persyaratans';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tipe_dokumen',
        'nama_dokumen',
        'keterangan',
        'berkas'
    ];

    protected $casts = [
        'id' => 'string'
    ];
}
