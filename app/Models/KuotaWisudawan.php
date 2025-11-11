<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KuotaWisudawan extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'kuota_wisudawans';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'pendaftaran_wisuda_id',
        'jumlah_kuota'
    ];

    protected $casts = [
        'id' => 'string',
        'jumlah_kuota' => 'integer'
    ];

    public function jadwalPendaftaran(): BelongsTo
    {
        return $this->belongsTo(JadwalPendaftaran::class, 'pendaftaran_wisuda_id', 'id');
    }

    public function scopeAktif($query)
    {
        return $query->whereHas('jadwalPendaftaran', function($q) {
            $q->where('status', 'Aktif');
        });
    }

    public function scopeTahunWisuda($query, $tahun)
    {
        return $query->whereHas('jadwalPendaftaran', function($q) use ($tahun) {
            $q->where('tahun_wisuda', 'like', "%{$tahun}%");
        });
    }

    public function getTahunWisudaAttribute(): ?string
    {
        return $this->jadwalPendaftaran->tahun_wisuda ?? null;
    }

    public function isKuotaTersedia(): bool
    {
        $totalPendaftar = 0;
        return $totalPendaftar < $this->jumlah_kuota;
    }

    public function getSisaKuotaAttribute(): int
    {
        $totalPendaftar = 0;
        return max(0, $this->jumlah_kuota - $totalPendaftar);
    }
}
