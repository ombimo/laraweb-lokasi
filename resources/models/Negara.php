<?php

namespace App\Models\Lokasi;

use Illuminate\Database\Eloquent\Model;

class Negara extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lokasi_negara';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['publish', 'created_at', 'updated_at'];

    /**
     * Relation
     */

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
