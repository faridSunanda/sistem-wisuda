<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JadwalPendaftaran extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pendaftaran_wisudas';

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tahun_wisuda',
        'status',
        'waktu_buka_pendaftaran',
        'waktu_tutup_pendaftaran',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'waktu_buka_pendaftaran' => 'datetime',
        'waktu_tutup_pendaftaran' => 'datetime',
    ];

    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    public function scopeSedangBerlangsung($query)
    {
        $now = now();
        return $query->where('status', 'Aktif')
                    ->where('waktu_buka_pendaftaran', '<=', $now)
                    ->where('waktu_tutup_pendaftaran', '>=', $now);
    }

    public function isSedangBerlangsung()
    {
        $now = now();
        return $this->status === 'Aktif' &&
               $this->waktu_buka_pendaftaran <= $now &&
               $this->waktu_tutup_pendaftaran >= $now;
    }

    public function isAkanDatang()
    {
        return $this->status === 'Aktif' &&
               $this->waktu_buka_pendaftaran > now();
    }

    public function isSudahBerakhir()
    {
        return $this->status === 'Aktif' &&
               $this->waktu_tutup_pendaftaran < now();
    }

    public function kuotaWisudawan(): HasOne
    {
        return $this->hasOne(KuotaWisudawan::class, 'pendaftaran_wisuda_id', 'id');
    }
}
