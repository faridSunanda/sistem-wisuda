<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class DosenPembimbing extends Model
{
    use HasUuids, SoftDeletes;

    
    protected $table = 'dosen_pembimbings';

    
    protected $fillable = ['biodata_id', 'nama'];

    /**
     * Relasi ini menyatakan bahwa DosenPembimbing milik satu Biodata.
     */
    public function biodata()
    {
        return $this->belongsTo(Biodata::class);
    }
}