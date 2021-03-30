<?php

namespace App\Models\Lokasi;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lokasi_kota';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['publish', 'created_at', 'updated_at'];

    /**
     * Relation
     */

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function kecamatan()
    {
        return $this->hasMany(Kelurahan::class, 'kota_id');
    }

    /**
     * Scope
     */

    public function scopePublish($query)
    {
        return $query->where('publish', 1);
    }

    public function scopeDefaultSort($query)
    {
        return $query->orderBy('nama');
    }
}
