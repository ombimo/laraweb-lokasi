<?php

namespace App\Models\Lokasi;

use Illuminate\Database\Eloquent\Model;

class Dusun extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lokasi_dusun';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['publish', 'created_at', 'updated_at'];

    /**
     * Relation
     */

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
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
