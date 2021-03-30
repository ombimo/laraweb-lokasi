<?php

namespace App\Models\Lokasi;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lokasi_provinsi';

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
        return $this->hasMany(Kota::class, 'provinsi_id');
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
