<?php

namespace App\Models\Lokasi;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lokasi_kecamatan';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['publish', 'created_at', 'updated_at'];

    /**
     * Relation
     */

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }

    public function kelurahan()
    {
        return $this->hasMany(Kelurahan::class, 'kecamatan_id');
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
