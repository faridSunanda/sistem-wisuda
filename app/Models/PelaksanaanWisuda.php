<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelaksanaanWisuda extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'pelaksanaan_wisudas';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'pendaftaran_wisuda_id',
        'nama_kegiatan',
        'waktu_pelaksanaan',
        'tempat_pelaksanaan',
        'keterangan'
    ];

    protected $casts = [
        'id' => 'string',
        'waktu_pelaksanaan' => 'datetime'
    ];

    public function jadwalPendaftaran(): BelongsTo
    {
        return $this->belongsTo(JadwalPendaftaran::class, 'pendaftaran_wisuda_id');
    }

    public function getWaktuPelaksanaanFormattedAttribute(): string
    {
        return $this->waktu_pelaksanaan->format('d F Y H:i');
    }

    public function getTahunWisudaAttribute(): ?string
    {
        return $this->jadwalPendaftaran->tahun_wisuda ?? null;
    }
}
